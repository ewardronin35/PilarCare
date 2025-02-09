$(document).ready(function () {
    // Initialize essential variables
    let teethStatuses = {};
    let dentalRecordId = '';

    // Declare DataTables variables
    let dentalExaminationTable;
    let toothHistoryTable;
    let dentalRecordsTable;

    // Set today's date in the exam-date input field on page load
    const today = new Date().toISOString().substr(0, 10);
    $('#exam-date').val(today);

    // Teeth data mapping
    const teethData = {
        '11': 'Upper Right Central Incisor',
        '12': 'Upper Right Lateral Incisor',
        '13': 'Upper Right Canine',
        '14': 'Upper Right First Premolar',
        '15': 'Upper Right Second Premolar',
        '16': 'Upper Right First Molar',
        '17': 'Upper Right Second Molar',
        '18': 'Upper Right Third Molar',
        '21': 'Upper Left Central Incisor',
        '22': 'Upper Left Lateral Incisor',
        '23': 'Upper Left Canine',
        '24': 'Upper Left First Premolar',
        '25': 'Upper Left Second Premolar',
        '26': 'Upper Left First Molar',
        '27': 'Upper Left Second Molar',
        '28': 'Upper Left Third Molar',
        '31': 'Lower Left Central Incisor',
        '32': 'Lower Left Lateral Incisor',
        '33': 'Lower Left Canine',
        '34': 'Lower Left First Premolar',
        '35': 'Lower Left Second Premolar',
        '36': 'Lower Left First Molar',
        '37': 'Lower Left Second Molar',
        '38': 'Lower Left Third Molar',
        '41': 'Lower Right Central Incisor',
        '42': 'Lower Right Lateral Incisor',
        '43': 'Lower Right Canine',
        '44': 'Lower Right First Premolar',
        '45': 'Lower Right Second Premolar',
        '46': 'Lower Right First Molar',
        '47': 'Lower Right Second Molar',
        '48': 'Lower Right Third Molar'
    };

    // Initialize Dental Examinations History Table
    dentalExaminationTable = $('#preview-dental-history-table').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "responsive": true,
        "autoWidth": false,
        "columns": [
            { 
                "data": "date_of_examination", 
                "render": function(data) {
                    return data ? new Date(data).toLocaleDateString() : 'N/A';
                }
            },
            { "data": "dentist_name" },
            { 
                "data": "findings",
                "render": function(data) {
                    return data; // Assuming 'findings' is already HTML
                }
            }
        ],
        "language": {
            "emptyTable": "No previous examinations found."
        },
        "dom": 'Bfrtip', // For Buttons extension
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    // Initialize Tooth History Table
    toothHistoryTable = $('#preview-tooth-history-table').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "responsive": true,
        "autoWidth": false,
        "columns": [
            { 
                "data": "tooth_number", 
                "render": function(data) {
                    return `Tooth ${data}`;
                }
            },
            { "data": "status" },
            { "data": "notes" },
            { 
                "data": "dental_pictures",
                "render": function(data) {
                    if (Array.isArray(data) && data.length > 0) {
                        return data.map(pic => `<img src="/storage/${pic}" alt="Dental Picture" style="max-width: 100px; margin-right: 5px; cursor: pointer;" class="dental-picture-preview">`).join('');
                    }
                    return 'N/A';
                }
            },
            { 
                "data": "last_updated",
                "render": function(data) {
                    return data ? new Date(data).toLocaleDateString() : 'N/A';
                }
            },
            { 
                "data": null,
                "orderable": false,
                "searchable": false,
                "render": function(data, type, row, meta){
                    return `<button class="history-btn btn btn-secondary" data-tooth-number="${row.tooth_number}" data-dental-record-id="${row.dental_record_id}">
                                <i class="fas fa-history"></i> Preview History
                            </button>`;
                }
            }
        ],
        "language": {
            "emptyTable": "No tooth history found."
        },
        "dom": 'Bfrtip', // For Buttons extension
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    // Initialize Dental Records Table
    dentalRecordsTable = $('#dental-records-table').DataTable({
        "ajax": {
            "url": window.fetchDentalRecordsUrl,
            "type": "GET",
            "dataSrc": "data",
            "error": function (xhr, error, thrown) {
                console.error('DataTables AJAX error:', error, thrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Data Load Error',
                    text: 'An error occurred while loading dental records.',
                });
            }
        },
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "responsive": true,
        "autoWidth": false,
        "columns": [
            { "data": "id_number", "orderable": true },
            { "data": "patient_name", "orderable": true },
            { "data": "user_type", "orderable": true },
            { 
                "data": "actions", 
                "orderable": false,
                "searchable": false,
                "render": function(data, type, row, meta){
                    return data; // Data already contains HTML
                }
            }
        ],
        "language": {
            "emptyTable": "No dental records found."
        },
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "drawCallback": function(settings) {
            var api = this.api();
            api.rows().every(function () {
                let rowData = this.data();
                if (rowData.id_number == dentalRecordId) {
                    $(this.node()).addClass('highlighted');
                } else {
                    $(this.node()).removeClass('highlighted');
                }
            });
        }
    });
    new $.fn.dataTable.FixedHeader(dentalRecordsTable);

    
    

    // Handle Preview Button Click
    $('#dental-records-table tbody').on('click', '.preview-btn', function () {
        const dentalRecordIdParam = $(this).data('id');
    
        if (!dentalRecordIdParam) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Record',
                text: 'No dental record ID found for preview.',
            });
            return;
        }
    
        // Instead of writing the AJAX code here, call the helper function:
        loadDentalRecordPreview(dentalRecordIdParam);
    });
    

    // Handle "Preview History" Button Click in Tooth History Table
   // Handle "Preview History" Button Click in Tooth History Table
$('#preview-tooth-history-table tbody').on('click', '.history-btn', function () {
    const toothNumber = $(this).data('tooth-number');
    const dentalRecordIdParam = $(this).data('dental-record-id');

    if (!dentalRecordIdParam || !toothNumber) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Data',
            text: 'Tooth number or dental record ID is missing.',
        });
        return;
    }

    // Show loading indicator
    Swal.fire({
        title: 'Loading...',
        text: 'Fetching tooth history details.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // AJAX request to fetch tooth history
    $.ajax({
        url: window.getToothHistoryUrl, // Ensure this route exists
        method: 'GET',
        data: {
            dental_record_id: dentalRecordIdParam,
            tooth_number: toothNumber
        },
        success: function (response) {
            Swal.close(); // Close loading indicator

            if (response.success && response.toothHistories && response.toothHistories.length > 0) {
                let tableRows = '';
                response.toothHistories.forEach(function(history) {
                    // Prepare dental pictures HTML
                    let picturesHtml = '';
                    if (history.dental_pictures && history.dental_pictures.length > 0) {
                        history.dental_pictures.forEach(function(pic) {
                            picturesHtml += `<img src="/storage/${pic}" alt="Dental Picture" style="max-width: 50px; margin-right: 5px;">`;
                        });
                    } else {
                        picturesHtml = 'N/A';
                    }

                    // Format updated_at
                    let updatedDate = history.updated_at ? new Date(history.updated_at).toLocaleDateString() : 'N/A';

                    tableRows += `<tr>
                        <td>${history.tooth_number}</td>
                        <td>${history.status}</td>
                        <td>${history.notes || 'N/A'}</td>
                        <td>${picturesHtml}</td>
                        <td>${updatedDate}</td>
                    </tr>`;
                });

                // Insert rows into the table body
                $('#tooth-history-table tbody').html(tableRows);

                // If a DataTable instance is already initialized, destroy it first
                if ($.fn.DataTable.isDataTable('#tooth-history-table')) {
                    $('#tooth-history-table').DataTable().destroy();
                }

                // Reinitialize DataTable with your desired options
                $('#tooth-history-table').DataTable({
                    paging: true,
                    searching: false,  // Disable search if not needed
                    ordering: true,
                    info: false,
                    autoWidth: false,
                    responsive: true,
                    language: {
                        emptyTable: "No history records available."
                    }
                });

                // Show the Tooth History Modal
                $('#toothHistoryModal').addClass('active').fadeIn(300);
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'No History Found',
                    text: 'No history available for this tooth.',
                });
            }
        },
        error: function (xhr) {
            Swal.close(); // Close loading indicator

            let errorMessage = 'An error occurred while fetching tooth history.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage,
            });
        }
    });
});

    // Function to parse findings into a readable format
    function parseFindings(exam) {
        const findings = [];
        const examinationFields = {
            carries_free: 'Carries Free',
            poor_oral_hygiene: 'Poor Oral Hygiene',
            gum_infection: 'Gum Infection',
            restorable_caries: 'Restorable Carious Tooth/Teeth',
            other_condition: 'Other Condition',
            personal_attention: 'Need Personal Attention in Tooth Brushing',
            oral_prophylaxis: 'For Oral Prophylaxis',
            fluoride_application: 'For Fluoride Application',
            gum_treatment: 'For Gum/Periodontal Treatment',
            ortho_consultation: 'For Orthodontic Consultation',
            filling_tooth: 'For Filling: Tooth(s)',
            extraction_tooth: 'For Extraction: Tooth(s)',
            endodontic_tooth: 'For Endodontic Tx/RCT: Tooth(s)',
            radiograph_tooth: 'For Radiograph/X-ray: Tooth(s)',
            prosthesis_tooth: 'Needs Prosthesis/Denture: Tooth(s)',
            medical_clearance: 'Medical Clearance',
            other_recommendation: 'Other Recommendation'
        };

        Object.keys(examinationFields).forEach(field => {
            if (Array.isArray(exam[field]) && exam[field].length > 0) {
                const teethList = exam[field].map(toothNum => {
                    const toothKey = toothNum.toString();
                    const toothName = teethData[toothKey] || 'Unknown Tooth';
                    return `${toothKey}: ${toothName}`;
                }).join(', ');
                findings.push(`${examinationFields[field]}: ${teethList}`);
            } else if (exam[field] === true || exam[field] === 1) {
                findings.push(examinationFields[field]);
            }
        });

        if (findings.length === 0) {
            return 'No findings reported.';
        }

        // Convert findings array into an unordered list
        return `<ul>${findings.map(item => `<li>${item}</li>`).join('')}</ul>`;
    }

    // Tab functionality
    $('.tab-button').click(function () {
        const tab = $(this).data('tab');

        // Remove 'active' class from all buttons and tab contents
        $('.tab-button').removeClass('active');
        $('.tab-content').removeClass('active').hide();

        // Add 'active' class to the clicked button
        $(this).addClass('active');

        // Show the corresponding tab content with fade-in effect
        $('#' + tab).fadeIn(200).addClass('active');
    });

    // Function to toggle the select dropdown based on checkbox state
    function toggleToothSelect(checkboxId, selectId) {
        const checkboxSelector = `#${checkboxId}`;
        const selectSelector = `#${selectId}`;

        $(checkboxSelector).change(function () {
            if ($(this).is(':checked')) {
                $(selectSelector).prop('disabled', false).trigger('change');
            } else {
                // Re-enable the teeth in other selects
                const selectedTeeth = $(selectSelector).val();
                if (selectedTeeth) {
                    selectedTeeth.forEach(function(tooth) {
                        $('select[name$="_tooth[]"]').not(selectSelector).find(`option[value="${tooth}"]`).prop('disabled', false);
                    });
                }
                $(selectSelector).prop('disabled', true).val('').trigger('change');
            }
        });

        // Handle change event on the select to disable selected tooth in other selects
        $(selectSelector).change(function () {
            enableAllTeeth(); // Reset all teeth options
            const selectedTeeth = $(this).val();
            if (selectedTeeth) {
                selectedTeeth.forEach(function(tooth) {
                    disableSelectedTeeth(tooth, selectSelector);
                });
            }
        });

        // Initialize the state based on the current checkbox state
        if ($(checkboxSelector).is(':checked')) {
            $(selectSelector).prop('disabled', false);
        } else {
            $(selectSelector).prop('disabled', true).val('').trigger('change');
        }
    }

    // Function to disable selected teeth in other selects
    function disableSelectedTeeth(selectedTooth, currentSelectSelector) {
        $('select[name$="_tooth[]"]').not(currentSelectSelector).find(`option[value="${selectedTooth}"]`).prop('disabled', true);
    }

    // Function to enable all teeth (to reset)
    function enableAllTeeth() {
        $('select[name$="_tooth[]"] option').prop('disabled', false);
    }

    // Initialize toggle functions for each procedure
    toggleToothSelect('filling', 'filling-tooth');
    toggleToothSelect('extraction', 'extraction-tooth');
    toggleToothSelect('endodontic', 'endodontic-tooth');
    toggleToothSelect('radiograph', 'radiograph-tooth');
    toggleToothSelect('prosthesis', 'prosthesis-tooth');

    // Function to determine the fill color based on the tooth status
    function getColorBasedOnStatus(status) {
        if (!status) {
            console.warn('Status is undefined or null.');
            return 'green'; // Default color if status is missing
        }

        const normalizedStatus = status.toLowerCase();
        switch (normalizedStatus) {
            case 'aching':
                return 'red';
            case 'cavity':
                return 'orange';
            case 'missing':
                return 'gray';
            case 'healthy':
                return 'green';
            default:
                console.warn(`Unknown status "${status}". Defaulting to green.`);
                return 'green'; // Default color for unknown statuses
        }
    }

    // Function to populate teeth colors based on status
    function populateTeeth(teeth) {
        if (!Array.isArray(teeth)) {
            console.error('Teeth data is not an array:', teeth);
            return;
        }
    
        teeth.forEach(function (tooth) {
            const toothNumber = tooth.tooth_number;
            const status = tooth.status;
    
            if (toothNumber === undefined || status === undefined) {
                console.warn(`Incomplete tooth data:`, tooth);
                return; // Skip this tooth if data is incomplete
            }
    
            const fillColor = getColorBasedOnStatus(status);
            $(`.tooth-${toothNumber}`).css('fill', fillColor);
            console.log(`Tooth ${toothNumber} status: ${status}, color set to: ${fillColor}`);
        });
    }

    // Mapping for boolean values
    const booleanMapping = {
        0: 'No',
        1: 'Yes'
    };

    // Function to map boolean values
    function mapBoolean(value) {
        return booleanMapping[value] || 'N/A';
    }

    // Function to clear the dental record display before fetching
    function clearDentalRecordDisplay() {
        $('#record-id_number').val('');
        $('#record-patient-name').val(''); 
        $('#record-grade-section').val('');
        $('#form-id_number').val('');

        // Reset teeth colors to default (green)
        const allTeethNumbers = Object.keys(teethData);
        allTeethNumbers.forEach(function (toothNumber) {
            const parentClass = `.tooth-${toothNumber}`;
            $(parentClass).css('fill', 'green');  // Set default to green for Healthy
        });

        // Clear Dental History Tables using DataTables API
        dentalExaminationTable.clear().draw();
        toothHistoryTable.clear().draw();

        dentalExaminationTable.row.add({
            "date_of_examination": '',
            "dentist_name": '',
            "findings": 'No previous examinations found.'
        }).draw(false);

        toothHistoryTable.row.add({
            "tooth_number": '',
            "status": '',
            "notes": '',
            "dental_pictures": 'No tooth history found.',
            "last_updated": '',
            "dental_record_id": dentalRecordId
        }).draw(false);

        // Reset Patient Information in Preview Tab
        const patientInfoBody = $('#preview-patient-info-body');
        patientInfoBody.empty().append(`
            <tr>
                <td><strong>Patient Name:</strong></td>
                <td>N/A</td>
            </tr>
            <tr>
                <td><strong>Date of Birth:</strong></td>
                <td>N/A</td>
            </tr>
            <tr>
                <td><strong>Last Visit Date:</strong></td>
                <td>N/A</td>
            </tr>
        `);

        // Reset Next Appointment
        const appointmentBody = $('#preview-next-appointment-body');
        appointmentBody.empty().append('<tr><td colspan="2">No upcoming appointments found.</td></tr>');
    }

    // Image preview logic
    if ($('#modal-upload-images').length) {
        $('#modal-upload-images').change(function (event) {
            $('#image-preview-container').empty(); // Clear previous previews
            const files = event.target.files;
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                reader.onload = function (e) {
                    const previewHtml = `
                        <div class="image-preview">
                            <img src="${e.target.result}" alt="Dental Image Preview" class="preview-img img-thumbnail" style="max-width: 100px; margin-right: 10px; cursor: pointer;">
                        </div>`;
                    $('#image-preview-container').append(previewHtml);
                };
                reader.readAsDataURL(file); // Convert image to Base64 string
            }
        });
    } else {
        console.warn('No element with id #modal-upload-images found. Skipping image preview setup.');
    }

    // Modal image preview using SweetAlert
    $(document).on('click', '.preview-img', function () {
        const src = $(this).attr('src');
        Swal.fire({
            title: 'Image Preview',
            imageUrl: src,
            imageAlt: 'Preview Image',
            showConfirmButton: false,
            showCloseButton: true,
        });
    });

    // Initialize Select2 on tooth-select elements
    if ($('.tooth-select').length) {
        $('.tooth-select').select2({
            placeholder: "Select Teeth",
            allowClear: true,
            width: '100%'
        });
    } else {
        console.warn('No elements with class .tooth-select found to initialize Select2');
    }

    // Calculate age based on birthdate
    function calculateAge(birthdate) {
        const today = new Date();
        const birthDate = new Date(birthdate);
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }

    // Handle form submission for dental exam with SweetAlert loading indicator
    $('#dental-exam-form').on('submit', function (event) {
        event.preventDefault(); // Prevent form from refreshing

        const form = $(this);
        const formData = new FormData(form[0]);

        // Check if id_number is set
        const idNumber = $('#form-id_number').val();
        if (!idNumber) {
            Swal.fire({
                icon: 'error',
                title: 'Missing ID Number',
                text: 'Please search and select a valid dental record before submitting the form.',
            });
            return;
        }

        // Reference to the submit button
        const submitButton = form.find('button[type="submit"], input[type="submit"]');

        // Disable the submit button to prevent multiple submissions
        submitButton.prop('disabled', true);

        // Show loading SweetAlert
        Swal.fire({
            title: 'Submitting...',
            html: 'Please wait while we save your data.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // AJAX request to submit the dental exam data
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: formData,
            processData: false,  // Important for FormData
            contentType: false,  // Important for FormData
            success: function (response) {
                // Close the loading SweetAlert
                Swal.close();

                // Show success SweetAlert
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Dental examination data saved successfully!',
                    timer: 2000,
                    showConfirmButton: false
                });

                // Additional actions after successful submission (e.g., clearing the form)
                form[0].reset();

                // Reset date to today's date
                $('#exam-date').val(today); // Corrected from '#date' to '#exam-date'

                $('#form-id_number').val('');
                $('#record-id_number').val('');
                $('#record-patient-name').val(''); // Corrected from .text() to .val()
                $('#record-grade-section').val('');

                // Populate Dental Record Information in the record tab with 'N/A'
                $('#record-patient-name').val('N/A');
                $('#record-grade-section').val('N/A');

                // Populate Dental Examination Form Fields with 'N/A'
                $('#exam-name').val('N/A');
                $('#exam-grade-section').val('N/A');

                // Optionally, reset teeth colors and dental history
                clearDentalRecordDisplay();

                // Switch back to the Dental Record tab
                $('.tab-button').removeClass('active');
                $('.tab-content').removeClass('active').hide();
                $('[data-tab="record-tab"]').addClass('active');
                $('#record-tab').addClass('active').show();
            },
            error: function (xhr) {
                // Close the loading SweetAlert
                Swal.close();

                // Determine the error message
                let errorMessage = 'An error occurred while saving the dental examination data.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 422) {
                    // Handle validation errors
                    const errors = xhr.responseJSON.errors;
                    if (errors) {
                        errorMessage = Object.values(errors).flat().join('<br>'); // Concatenate all error messages
                    }
                }

                // Show error SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: errorMessage, // Use 'html' to render line breaks
                });
            },
            complete: function () {
                // Re-enable the submit button regardless of success or error
                submitButton.prop('disabled', false);
            }
        });
    });

    // Handle Tooth Details Save Button Click
    $('#save-tooth-details').on('click', function () {
        const form = $('#tooth-details-form');
        const formData = new FormData(form[0]); // Use FormData for file uploads
    
        // Show loading indicator
        Swal.fire({
            title: 'Saving...',
            text: 'Please wait while we save the tooth details.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    
        // AJAX request to save tooth details
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: formData,
            processData: false, // Prevent jQuery from processing the data
            contentType: false, // Prevent jQuery from setting content type
            success: function (response) {
                Swal.close(); // Close the loading indicator
    
                // Show success message and then, once it's closed, reload the preview
                Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
                    text: 'Tooth details have been saved successfully.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // After the success alert closes, get the current dental record ID
                    const currentDentalRecordId = $('#dental-record-id').val();
                    if (currentDentalRecordId) {
                        // Re-run the preview AJAX call to update the preview (including the tooth history table)
                        loadDentalRecordPreview(currentDentalRecordId);
                    }
                });
    
                // Close the modal immediately (or you can delay it if needed)
                $('#previewModal').fadeOut(300);
    
                // Optionally, refresh the dental records table as well:
                dentalRecordsTable.ajax.reload(null, false);
            },
            error: function (xhr) {
                Swal.close(); // Close the loading indicator
    
                let errorMessage = 'An error occurred while saving the tooth details.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if (errors) {
                        errorMessage = Object.values(errors).flat().join('<br>');
                    }
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: errorMessage,
                });
            }
        });
    });
    

    // Function to handle tooth interaction
    function handleToothInteraction(toothNumber) {
        var parentClass = `.tooth-${toothNumber}`; // Target the specific tooth class

        $.ajax({
            url: window.getToothStatusUrl,
            method: 'GET',
            data: {
                tooth_number: toothNumber,
                dental_record_id: dentalRecordId
            },
            success: function (response) {
                let status = response.status || 'Healthy'; // Default to Healthy if not found
              
                // Store the status in teethStatuses
                teethStatuses[toothNumber] = {
                    status: status
                };

                let fillColor = getColorBasedOnStatus(status);
                $(parentClass).css('fill', fillColor);
                console.log(`Tooth ${toothNumber} status: ${status}, color set to: ${fillColor}`);

                // Enable pointer events and set opacity
                $(parentClass)
                    .css('pointer-events', 'auto')
                    .css('opacity', '1')
                    .removeAttr('title');

                console.log(`Tooth ${toothNumber} is active and can be updated.`);

                // Attach or reattach event handlers based on submission type
                attachToothEventHandlers(toothNumber);
            },
            error: function (xhr) {
                console.error(`Error fetching status for Tooth ${toothNumber}:`, xhr.responseText);
            }
        });
    }

    // Function to attach event handlers to a tooth
    function attachToothEventHandlers(toothNumber) {
        var parentClass = `.tooth-${toothNumber}`;

        // Remove any existing event handlers to prevent duplicates
        $(parentClass).off('mouseover mouseleave click');

        // Hover events on tooth SVG
        $(parentClass).hover(
            function () { // Mouse enter
                if ($(this).css('pointer-events') !== 'none') {
                    $(this).css('fill', 'lightblue');
                    console.log(`Hovered over Tooth ${toothNumber}`);
                }
            },
            function () { // Mouse leave
                // Restore color based on current status
                const currentStatus = teethStatuses[toothNumber]?.status || 'Healthy';
                const fillColor = getColorBasedOnStatus(currentStatus);
                $(this).css('fill', fillColor);
                console.log(`Mouse left Tooth ${toothNumber}`);
            }
        );

        // Click event to open the modal only if the tooth is not disabled
        $(parentClass).click(function () {
            const toothStatus = teethStatuses[toothNumber];

            var description = teethData[toothNumber] || 'No description available'; // Get tooth description
            var svgPath = $(this).attr('d'); // Capture the SVG path data from the clicked tooth
            console.log('SVG Path:', svgPath); // Debugging

            // Set svg_path in the modal
            $('#modal-svg-path').val(svgPath);
            console.log('Set tooth_number in modal:', toothNumber);

            $('#modal-tooth-number').val(toothNumber);

            // Show Upload Images Section
            $('#upload-images-section').show();
            $('#upload-images-section label[for="modal-upload-images"]').html('Upload Dental Pictures:<span style="color: red;"> *</span>');
            $('#modal-upload-images').attr('title', 'Please upload images as proof for the update.');

            // Set the value of #modal-is-first-submission
            $('#modal-is-first-submission').val('false');

            // Show modal with the correct information
            $('#modal-tooth').val('Tooth ' + toothNumber + ': ' + description);
            $('#modal-status').val(toothStatus.status);
            $('#modal-notes').val(description);

            // Show modal with animation
            $('#previewModal').css({ 'display': 'flex', 'opacity': 0 }).animate({ 'opacity': 1 }, 300);
        });
    }

    // Function to determine the fill color based on the tooth status
    function getColorBasedOnStatus(status) {
        if (!status) {
            console.warn('Status is undefined or null.');
            return 'green'; // Default color if status is missing
        }

        const normalizedStatus = status.toLowerCase();
        switch (normalizedStatus) {
            case 'aching':
                return 'red';
            case 'cavity':
                return 'orange';
            case 'missing':
                return 'gray';
            case 'healthy':
                return 'green';
            default:
                console.warn(`Unknown status "${status}". Defaulting to green.`);
                return 'green'; // Default color for unknown statuses
        }
    }

    // Function to parse findings into a readable format
    function parseFindings(exam) {
        const findings = [];
        const examinationFields = {
            carries_free: 'Carries Free',
            poor_oral_hygiene: 'Poor Oral Hygiene',
            gum_infection: 'Gum Infection',
            restorable_caries: 'Restorable Carious Tooth/Teeth',
            other_condition: 'Other Condition',
            personal_attention: 'Need Personal Attention in Tooth Brushing',
            oral_prophylaxis: 'For Oral Prophylaxis',
            fluoride_application: 'For Fluoride Application',
            gum_treatment: 'For Gum/Periodontal Treatment',
            ortho_consultation: 'For Orthodontic Consultation',
            filling_tooth: 'For Filling: Tooth(s)',
            extraction_tooth: 'For Extraction: Tooth(s)',
            endodontic_tooth: 'For Endodontic Tx/RCT: Tooth(s)',
            radiograph_tooth: 'For Radiograph/X-ray: Tooth(s)',
            prosthesis_tooth: 'Needs Prosthesis/Denture: Tooth(s)',
            medical_clearance: 'Medical Clearance',
            other_recommendation: 'Other Recommendation'
        };

        Object.keys(examinationFields).forEach(field => {
            if (Array.isArray(exam[field]) && exam[field].length > 0) {
                const teethList = exam[field].map(toothNum => {
                    const toothKey = toothNum.toString();
                    const toothName = teethData[toothKey] || 'Unknown Tooth';
                    return `${toothKey}: ${toothName}`;
                }).join(', ');
                findings.push(`${examinationFields[field]}: ${teethList}`);
            } else if (exam[field] === true || exam[field] === 1) {
                findings.push(examinationFields[field]);
            }
        });

        if (findings.length === 0) {
            return 'No findings reported.';
        }

        // Convert findings array into an unordered list
        return `<ul>${findings.map(item => `<li>${item}</li>`).join('')}</ul>`;
    }

    // Handle Modal Close Buttons
    $('.modal .close').on('click', function () {
        $(this).closest('.modal').fadeOut(300);
    });

    // Optional: Close modal when clicking outside the modal content
    $(window).on('click', function (event) {
        if ($(event.target).is('#previewModal') || $(event.target).is('#toothHistoryModal')) {
            $(event.target).fadeOut(300);
        }
    });
    function loadDentalRecordPreview(dentalRecordIdParam) {
        // Show a loading indicator
        Swal.fire({
            title: 'Loading...',
            text: 'Fetching dental record details.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    
        $.ajax({
            url: window.getDentalRecordPreviewUrl,
            method: 'GET',
            data: { dental_record_id: dentalRecordIdParam },
            success: function (response) {
                Swal.close(); // Close the loading indicator
    
                if (response.dentalRecord) {
                    // Set global dentalRecordId and update hidden fields
                    dentalRecordId = response.dentalRecord.dental_record_id;
                    $('#dental-record-id').val(dentalRecordId);
    
                    // Switch to the preview tab
                    $('.tab-button').removeClass('active');
                    $('.tab-content').removeClass('active').hide();
                    $('[data-tab="preview-tab"]').addClass('active');
                    $('#preview-tab').addClass('active').show();
    
                    // Populate patient information
                    $('#preview-patient-name').text(response.name || 'N/A');
                    $('#preview-patient-dob').text(response.birthdate ? new Date(response.birthdate).toLocaleDateString() : 'N/A');
                    if (response.birthdate) {
                        const formattedBirthdate = new Date(response.birthdate).toISOString().substr(0, 10);
                        $('#birthdate').val(formattedBirthdate);
                        $('#age').val(calculateAge(response.birthdate));
                    } else {
                        $('#birthdate').val('N/A');
                        $('#age').val('N/A');
                    }
                    dentalRecordsTable.rows().every(function () {
                        let rowData = this.data();
                        if (rowData.id_number == dentalRecordId) {
                            $(this.node()).addClass('highlighted');
                        } else {
                            $(this.node()).removeClass('highlighted');
                        }
                    });
                    // Populate dental record form fields (record tab)
                    $('#record-id_number').val(response.dentalRecord.id_number || '');
                    $('#form-id_number').val(response.dentalRecord.id_number || '');
                    $('#record-patient-name').val(response.name || 'N/A');
                    $('#record-grade-section').val(response.grade_section || 'N/A');
    
                    // Populate exam form fields
                    $('#exam-name').val(response.name || 'N/A');
                    $('#exam-grade-section').val(response.grade_section || 'N/A');
    
                    // Populate last visit date
                    let lastExamination = null;
                    if (response.previousExaminations && response.previousExaminations.length > 0) {
                        lastExamination = response.previousExaminations[0];
                    }
                    $('#preview-last-visit').text(lastExamination && lastExamination.date_of_examination ? new Date(lastExamination.date_of_examination).toLocaleDateString() : 'N/A');
    
                    // Update Dental Examinations History DataTable
                    dentalExaminationTable.clear().draw();
                    if (response.previousExaminations && response.previousExaminations.length > 0) {
                        response.previousExaminations.forEach(exam => {
                            const formattedDate = exam.date_of_examination ? new Date(exam.date_of_examination).toLocaleDateString() : 'N/A';
                            const dentistName = exam.dentist_name || 'N/A';
                            const findings = parseFindings(exam);
                            dentalExaminationTable.row.add({
                                "date_of_examination": formattedDate,
                                "dentist_name": dentistName,
                                "findings": findings
                            }).draw(false);
                        });
                    } else {
                        dentalExaminationTable.row.add({
                            "date_of_examination": '',
                            "dentist_name": '',
                            "findings": 'No previous examinations found.'
                        }).draw(false);
                    }
    
                    // Update Tooth History DataTable
                    toothHistoryTable.clear().draw();
                    if (response.toothHistory && response.toothHistory.length > 0) {
                        response.toothHistory.forEach(tooth => {
                            const toothNumber = tooth.tooth_number || 'N/A';
                            const status = tooth.status || 'N/A';
                            const notes = tooth.notes || 'N/A';
                            const formattedDate = tooth.updated_at ? new Date(tooth.updated_at).toLocaleDateString() : 'N/A';
    
                            // Handle dental pictures
                            let dentalPictures = tooth.dental_pictures || [];
                            if (typeof dentalPictures === 'string') {
                                try {
                                    dentalPictures = JSON.parse(dentalPictures);
                                } catch (e) {
                                    console.error('Error parsing dental_pictures:', e);
                                    dentalPictures = [];
                                }
                            }
    
                            toothHistoryTable.row.add({
                                "tooth_number": tooth.tooth_number,
                                "status": status,
                                "notes": notes,
                                "dental_pictures": dentalPictures,
                                "last_updated": formattedDate,
                                "dental_record_id": dentalRecordId
                            }).draw(false);
                        });
                    } else {
                        toothHistoryTable.row.add({
                            "tooth_number": '',
                            "status": '',
                            "notes": '',
                            "dental_pictures": 'No tooth history found.',
                            "last_updated": '',
                            "dental_record_id": dentalRecordId
                        }).draw(false);
                    }
    
                    // Update next appointment details
                    const appointmentDate = response.nextAppointment ? new Date(response.nextAppointment.appointment_date).toLocaleDateString() : 'N/A';
                    const appointmentPurpose = response.nextAppointment ? response.nextAppointment.purpose || 'N/A' : 'N/A';
                    $('#preview-appointment-date').text(appointmentDate);
                    $('#preview-appointment-purpose').text(appointmentPurpose);
    
                    // (Additional UI updates, such as teeth coloring and event handlers)
                    Object.keys(teethData).forEach(function(toothNumber) {
                        handleToothInteraction(toothNumber);
                    });
    
                    Swal.fire({
                        icon: 'success',
                        title: 'Record Loaded',
                        text: 'Dental record loaded successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'No Record Found',
                        text: 'No dental record found for the selected ID number.',
                    });
                }
            },
            error: function (xhr) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON.message || 'An error occurred while fetching the dental record.',
                });
            }
        });
    }
});
