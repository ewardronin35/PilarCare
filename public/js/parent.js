$(document).ready(function () {
    // CSRF Token Setup (if using POST requests)
    // Uncomment the following lines if you switch to POST for AJAX requests
    /*
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    */

    // Define teeth data mapping
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

    // Function to switch tabs with active class handling
    function switchTab(tabId) {
        // Hide all tab contents and remove 'active' class
        $('.tab-content').hide().removeClass('active');
        // Remove 'active' class from all tab buttons
        $('.tab-button').removeClass('active');
        // Show the selected tab and add 'active' class
        $('#' + tabId).show().addClass('active');
        // Add 'active' class to the corresponding button
        $('.tab-button[data-tab="' + tabId + '"]').addClass('active');
    }

    // Add event listeners to tab buttons
    $('.tab-button').on('click', function (e) {
        e.preventDefault();
        const targetTab = $(this).data('tab');
        switchTab(targetTab);
    });

    // Function to determine the fill color based on the tooth status
    function getColorBasedOnStatus(status) {
        if (!status) {
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
                return 'green'; // Default color for unknown statuses
        }
    }

    // Function to apply fill color to a tooth in the SVG
    function applyToothColor(toothNumber, fillColor) {
        const toothElement = $(`.tooth-${toothNumber}`);
        if (toothElement.length) {
            toothElement.css({
                'fill': fillColor,
                'transition': 'fill 0.5s ease-in-out'
            });
        }
    }

    // Function to populate tooth colors based on status
    function populateTeeth(teeth) {
        const allTeethNumbers = Object.keys(teethData); // List of all tooth numbers

        // First, set all teeth to default green color
        allTeethNumbers.forEach(function (toothNumber) {
            applyToothColor(toothNumber, 'green'); // Default color
        });

        // Now, if we have data for teeth, overwrite colors
        if (Array.isArray(teeth)) {
            teeth.forEach(function (tooth) {
                const toothNumber = tooth.tooth_number;
                const status = tooth.status;

                if (toothNumber === undefined || status === undefined) {
                    console.warn(`Incomplete tooth data:`, tooth);
                    return; // Skip this tooth if data is incomplete
                }

                const fillColor = getColorBasedOnStatus(status);
                applyToothColor(toothNumber, fillColor);
            });
        } else {
            console.warn('Teeth data is undefined or not an array. All teeth will be displayed with default color.');
        }
    }

    // Mapping for boolean values
    const booleanMapping = {
        0: 'No',
        1: 'Yes',
        '0': 'No',
        '1': 'Yes',
        true: 'Yes',
        false: 'No',
    };

    // Function to map boolean values
    function mapBoolean(value) {
        return booleanMapping[value] || 'N/A';
    }

    // Function to populate dental history
    function populateDentalHistory(dentalRecord) {
        if (!dentalRecord) {
            console.error('Dental history data is undefined or null:', dentalRecord);
            return;
        }

        // Populate Patient Information
        const patientInfoBody = $('#preview-patient-info-body');
        patientInfoBody.empty();

        const patientName = dentalRecord.patient_name || 'N/A';
        const formattedDOB = dentalRecord.birthdate ? new Date(dentalRecord.birthdate).toLocaleDateString() : 'N/A';
        const lastVisitDate = dentalRecord.lastExamination && dentalRecord.lastExamination.date_of_examination ? new Date(dentalRecord.lastExamination.date_of_examination).toLocaleDateString() : 'N/A';

        // Added Grade and Section Fields
        const grade = dentalRecord.grade_section ? dentalRecord.grade_section : 'N/A';

        patientInfoBody.append(`
            <tr>
                <td><strong>Patient Name:</strong></td>
                <td>${patientName}</td>
            </tr>
            <tr>
                <td><strong>Date of Birth:</strong></td>
                <td>${formattedDOB}</td>
            </tr>
            <tr>
                <td><strong>Grade & Section:</strong></td>
                <td>${grade}</td>
            </tr>
            <tr>
                <td><strong>Last Visit Date:</strong></td>
                <td>${lastVisitDate}</td>
            </tr>
        `);

        $('#record-id_number').val(dentalRecord.id_number || 'N/A');
        $('#student-name').val(patientName);
        $('#grade-sections').val(grade);

        // Populate Previous Examinations
        const dentalHistoryBody = $('#preview-dental-history-body');
        dentalHistoryBody.empty();

        if (dentalRecord.previousExaminations && dentalRecord.previousExaminations.length > 0) {
            dentalRecord.previousExaminations.forEach(function (exam) {
                const formattedDate = exam.date_of_examination ? new Date(exam.date_of_examination).toLocaleDateString() : 'N/A';
                const dentistName = exam.dentist_name || 'N/A';
                const findings = exam.findings || 'N/A';

                dentalHistoryBody.append(`
                    <tr>
                        <td>${formattedDate}</td>
                        <td>${dentistName}</td>
                        <td>${findings}</td>
                    </tr>
                `);
            });
        } else {
            dentalHistoryBody.append('<tr><td colspan="3">No dental examinations history available.</td></tr>');
        }

        // Populate Tooth History
        const toothHistoryBody = $('#preview-tooth-history-body');
        toothHistoryBody.empty();

        if (dentalRecord.teeth && dentalRecord.teeth.length > 0) {
            dentalRecord.teeth.forEach(function (tooth) {
                const toothNumber = tooth.tooth_number || 'N/A';
                const status = tooth.status || 'N/A';
                const notes = tooth.notes || 'N/A';
                const formattedDate = tooth.updated_at ? new Date(tooth.updated_at).toLocaleDateString() : 'N/A';

                // Handle 'dental_pictures' which should be an array
                let dentalPictures = tooth.dental_pictures || [];
                if (typeof dentalPictures === 'string') {
                    try {
                        dentalPictures = JSON.parse(dentalPictures);
                    } catch (e) {
                        console.error('Error parsing dental_pictures:', e);
                        dentalPictures = [];
                    }
                }

                let picturesHtml = 'N/A';
                if (Array.isArray(dentalPictures) && dentalPictures.length > 0) {
                    picturesHtml = dentalPictures.map(pic => `<img src="/storage/${pic}" alt="Dental Picture" style="max-width: 100px; margin-right: 5px; cursor: pointer;" class="dental-picture-preview">`).join('');
                }

                const row = `
                    <tr>
                        <td>Tooth ${toothNumber}</td>
                        <td>${status}</td>
                        <td>${notes}</td>
                        <td>${picturesHtml}</td>
                        <td>${formattedDate}</td>
                    </tr>
                `;
                toothHistoryBody.append(row);
            });

            // Initialize DataTables for Tooth History Table
            $('#preview-tooth-history-table').DataTable({
                responsive: true,
                searching: true,
                paging: true,
                language: {
                    emptyTable: "No tooth history available."
                }
            });
        } else {
            toothHistoryBody.append('<tr><td colspan="5">No tooth history available.</td></tr>');
        }

        // Populate Next Scheduled Appointment
        const appointmentDate = $('#preview-appointment-date');
        const appointmentPurpose = $('#preview-appointment-purpose');

        if (dentalRecord.nextAppointment) {
            const formattedAppointmentDate = new Date(dentalRecord.nextAppointment.appointment_date).toLocaleDateString();
            appointmentDate.text(formattedAppointmentDate);
            appointmentPurpose.text(dentalRecord.nextAppointment.purpose || 'N/A');
        } else {
            appointmentDate.text('N/A');
            appointmentPurpose.text('N/A');
        }
    }

    // Function to handle image preview using SweetAlert2
    $(document).on('click', '.dental-picture-preview', function () {
        const src = $(this).attr('src');
        Swal.fire({
            title: 'Image Preview',
            imageUrl: src,
            imageAlt: 'Dental Picture',
            showConfirmButton: false,
            showCloseButton: true,
        });
    });

    // Function to fetch and populate preview data via AJAX
    function fetchAndPopulatePreview(idNumber) {
        $.ajax({
            url: window.getDentalRecordPreviewUrl,
            method: 'GET',
            data: { id_number: idNumber }, // jQuery handles encoding
            beforeSend: function () {
                // Show a loading indicator
                Swal.fire({
                    title: 'Loading...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function (response) {
                Swal.close(); // Close the loading indicator
    
                if (response && response.success) {
                    // Populate Dental History
                    populateDentalHistory(response);
    
                    // Populate Teeth Colors on the Dental Record Tab
                    populateTeeth(response.teeth);
    
                    // Switch to Preview Tab
                    switchTab('preview-tab');
    
                    // Show a success message
                    Swal.fire('Success', 'Dental record preview loaded successfully.', 'success');
                } else {
                    Swal.fire('Error', response.message || 'No data found for the selected dental record.', 'error');
                }
            },
            error: function (xhr, status, error) {
                Swal.close(); // Close the loading indicator
                console.error('Error fetching dental record preview:', error);
    
                // Attempt to parse error response
                let errorMessage = 'Failed to fetch dental record preview.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire('Error', errorMessage, 'error');
            }
        });
    }
    

    // Event listener for Preview buttons
    $('.preview-btn').on('click', function () {
        const idNumber = $(this).data('id'); // data-id is id_number
        if (idNumber) {
            fetchAndPopulatePreview(idNumber);
        } else {
            console.error('No ID number found for this preview button.');
            Swal.fire('Error', 'Invalid ID number.', 'error');
        }
    });

    // Initialize DataTables for the dental records table
    $('#dental-records-table').DataTable({
        responsive: true,
        searching: true,
        paging: true,
        ordering: true,
        language: {
            emptyTable: "No dental records available."
        }
    });
});
