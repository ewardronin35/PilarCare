$(document).ready(function () {
    // -------------------------------
    // 1. AJAX Setup with CSRF Token
    // -------------------------------
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // -------------------------------
    // 2. Initialize Variables
    // -------------------------------
    let dentalRecordId = $('#dental-record-id').val(); // Get the dental_record_id from the hidden input
    let teethStatuses = {}; // This will store the status and approval of each tooth

    const teethNumbers = [
        11, 12, 13, 14, 15, 16, 17, 18,
        21, 22, 23, 24, 25, 26, 27, 28,
        31, 32, 33, 34, 35, 36, 37, 38,
        41, 42, 43, 44, 45, 46, 47, 48
    ];

    let teethData = {
        11: 'Upper Right Central Incisor',
        12: 'Upper Right Lateral Incisor',
        13: 'Upper Right Canine',
        14: 'Upper Right First Premolar',
        15: 'Upper Right Second Premolar',
        16: 'Upper Right First Molar',
        17: 'Upper Right Second Molar',
        18: 'Upper Right Third Molar',
        21: 'Upper Left Central Incisor',
        22: 'Upper Left Lateral Incisor',
        23: 'Upper Left Canine',
        24: 'Upper Left First Premolar',
        25: 'Upper Left Second Premolar',
        26: 'Upper Left First Molar',
        27: 'Upper Left Second Molar',
        28: 'Upper Left Third Molar',
        31: 'Lower Left Central Incisor',
        32: 'Lower Left Lateral Incisor',
        33: 'Lower Left Canine',
        34: 'Lower Left First Premolar',
        35: 'Lower Left Second Premolar',
        36: 'Lower Left First Molar',
        37: 'Lower Left Second Molar',
        38: 'Lower Left Third Molar',
        41: 'Lower Right Central Incisor',
        42: 'Lower Right Lateral Incisor',
        43: 'Lower Right Canine',
        44: 'Lower Right First Premolar',
        45: 'Lower Right Second Premolar',
        46: 'Lower Right First Molar',
        47: 'Lower Right Second Molar',
        48: 'Lower Right Third Molar'
    };

    // ----------------------------------------
    // 3. Helper Function: Determine Fill Color
    // ----------------------------------------
    function getColorBasedOnStatus(status) {
        switch (status.toLowerCase()) {
            case 'aching':
                return 'red';
            case 'cavity':
                return 'orange';
            case 'missing':
                return 'gray';
            default:
                return 'green'; // Default color for Healthy
        }
    }

    // -------------------------------------------------
    // 4. Function: Create a New Dental Record via AJAX
    // -------------------------------------------------
    function createDentalRecord() {
        var idNumber = $('#id_number').val();
        var patientName = $('#patient_name').val();
        var additionalInfo = $('#additional-info').val();

        console.log('Creating new dental record for ID Number:', idNumber);

        // Send AJAX POST request to create the dental record
        $.ajax({
            url: storeDentalRecordUrl,
            method: 'POST',
            data: {
                id_number: idNumber,
                patient_name: patientName,
                additional_info: additionalInfo,
            },
            success: function(response) {
                if (response.dental_record_id) {
                    // Set the dental_record_id in the hidden input
                    dentalRecordId = response.dental_record_id;
                    $('#dental-record-id').val(dentalRecordId);

                    console.log('New Dental Record ID set:', dentalRecordId);

                    // Hide the save button
                    $('#save-dental-record').hide();

                    // Show success modal and reload the page after closing
                    Swal.fire({
                        icon: 'success',
                        title: 'Welcome!',
                        text: 'A new dental record has been created successfully.',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload(); // Reload the page after SweetAlert closes
                    });
                } else {
                    console.error('No Dental Record ID returned from the server.');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to create dental record!',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr) {
                console.error('Error saving dental record:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to create dental record!',
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        });
    }

    // -------------------------------------------------
    // 5. Function: Handle Individual Tooth Interaction
    // -------------------------------------------------
    function handleToothInteraction(toothNumber) {
        var parentClass = `.tooth-${toothNumber}`; // Target the specific tooth class

        $.ajax({
            url: getToothStatusUrl,
            method: 'GET',
            data: {
                tooth_number: toothNumber,
                dental_record_id: dentalRecordId
            },
            success: function (response) {
                let status = response.status || 'Healthy'; // Default to Healthy if not found
                let isApproved = response.is_approved !== undefined ? response.is_approved : true; // Default to true for new entries

                // Store the status and approval in teethStatuses
                teethStatuses[toothNumber] = {
                    status: status,
                    isApproved: isApproved
                };

                let fillColor = getColorBasedOnStatus(status);
                $(parentClass).css('fill', fillColor);
                console.log(`Initial color set for Tooth ${toothNumber}: ${status}`);

                // Modify the condition to allow interactions if there's no pending update
                if (!isApproved) {
                    $(parentClass)
                        .css('pointer-events', 'none')
                        .css('opacity', '0.6')
                        .attr('title', 'This tooth has a pending update and cannot be modified until approved.');
                    console.log(`Tooth ${toothNumber} is disabled due to pending update.`);
                } else {
                    // Enable the tooth if approved or no pending updates
                    $(parentClass)
                        .css('pointer-events', 'auto')
                        .css('opacity', '1')
                        .removeAttr('title');
                    console.log(`Tooth ${toothNumber} is active and can be updated.`);
                }
            },
            error: function (xhr) {
                console.error(`Error fetching status for Tooth ${toothNumber}:`, xhr.responseText);
            }
        });

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
            if (toothStatus && !toothStatus.isApproved) {
                Swal.fire({
                    icon: 'info',
                    title: 'Pending Approval',
                    text: 'This tooth has a pending update. Please wait for approval before making further changes.',
                });
                return;
            }

            // Existing click handler logic to open the modal
            var toothClass = $(this).attr('class');
            var toothNumberExtracted = toothClass.match(/tooth-(\d+)/)[1]; // Extract the tooth number
            var description = teethData[toothNumberExtracted] || 'No description available'; // Get tooth description
            var svgPath = $(this).attr('d'); // Capture the SVG path data from the clicked tooth
            console.log('SVG Path:', svgPath); // Debugging

            // Set svg_path in the modal
            $('#modal-svg-path').val(svgPath);
            console.log(`Set svg_path in modal: ${$('#modal-svg-path').val()}`); // Confirm setting

            // Fetch the current status of the tooth via AJAX
            $.ajax({
                url: getToothStatusUrl,
                method: 'GET',
                data: {
                    tooth_number: toothNumberExtracted,
                    dental_record_id: dentalRecordId
                },
                success: function (response) {
                    let status = response.status || 'Healthy'; // Default status if none is found
                    let isApproved = response.is_approved || false; // true or false

                    // Set the value of #modal-is-first-submission based on is_approved
                    $('#modal-is-first-submission').val(isApproved ? 'true' : 'false'); // true if approved, false if pending

                    // Show modal with the correct information
                    $('#modal-tooth').val('Tooth ' + toothNumberExtracted);
                    $('#modal-status').val(status);
                    $('#modal-notes').val(description);
                    // $('#modal-svg-path').val(svgPath); // Already set above

                    // Show modal with animation
                    $('#previewModal').css({ 'display': 'flex', 'opacity': 0 }).animate({ 'opacity': 1 }, 300);
                },
                error: function (xhr) {
                    console.error('Error fetching tooth status:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to fetch tooth details. Please try again.',
                    });
                }
            });
        });
    }

    // -------------------------------------------------
    // 6. Function: Handle All Teeth Interactions
    // -------------------------------------------------
    function handleAllTeeth() {
        teethNumbers.forEach(handleToothInteraction);
    }

    // -------------------------------------------------
    // 7. Function: Initialize Dental Record
    // -------------------------------------------------
    function initializeDentalRecord() {
        if (!dentalRecordId || dentalRecordId === '') {
            console.info('Info: Dental Record ID is missing.');
            Swal.fire({
                icon: 'info',
                title: 'Welcome to Dental Record',
                text: 'A new dental record will be created automatically.',
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                createDentalRecord();
            });
        } else {
            // Dental record exists, proceed to handle tooth interactions
            handleAllTeeth();

            // Fetch and populate dental examination history
            fetchDentalExaminationHistory();

            // Fetch and populate tooth history
            fetchToothHistory();
        }
    }

    // -------------------------------------------------
    // 8. Event Listeners for Modal Close
    // -------------------------------------------------
    // Close modal when clicking the close button
    $('.close').click(function () {
        $('#previewModal').animate({ 'opacity': 0 }, 300, function () {
            $(this).css('display', 'none'); // Hide after animation
        });
    });

    // Close modal when clicking outside of it
    $(window).click(function (event) {
        if (event.target.id === 'previewModal') {
            $('#previewModal').animate({ 'opacity': 0 }, 300, function () {
                $(this).css('display', 'none'); // Hide after animation
            });
        }
    });

    // -------------------------------------------------
    // 9. Event Listener: Save Dental Record (Manual Save)
    // -------------------------------------------------
    $('#save-dental-record').click(function (e) {
        e.preventDefault(); // Prevent default form submission

        var idNumber = $('#id_number').val(); // Capture the ID number
        console.log('Saving dental record for ID Number:', idNumber);

        var formData = $('#dental-record-form').serialize();

        // Temporarily disable the save button to prevent multiple submissions
        $(this).prop('disabled', true);

        $.ajax({
            url: storeDentalRecordUrl, // URL for storing dental record
            method: 'POST',
            data: formData,
            success: function (response) {
                // Server returns actual dental record ID
                if (response.dental_record_id) {
                    // Set the new dental record ID
                    dentalRecordId = response.dental_record_id;
                    $('#dental-record-id').val(dentalRecordId);

                    console.log('New Dental Record ID set:', response.dental_record_id);

                    // Hide the save button
                    $('#save-dental-record').hide();

                    // Display success message and then show welcome modal without reloading
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Dental record saved successfully!',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        // Show welcome modal and reload the page
                        Swal.fire({
                            icon: 'success',
                            title: 'Welcome!',
                            text: 'Your dental record has been created successfully.',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // Reload the page after SweetAlert closes
                        });
                    });
                } else {
                    console.error('No Dental Record ID returned from the server.');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to retrieve Dental Record ID!',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        // Re-enable the save button in case of error
                        $('#save-dental-record').prop('disabled', false);
                    });
                }
            },
            error: function (xhr) {
                console.error('Error saving dental record:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to save dental record!',
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {
                    // Re-enable the save button after error
                    $('#save-dental-record').prop('disabled', false);
                });
            }
        });
    });

    // -------------------------------------------------
    // 10. Event Listener: Save Tooth Details
    // -------------------------------------------------
    $('#save-tooth-details').off('click').on('click', function (e) {
        e.preventDefault();  // Immediately prevent form submission
        if (!dentalRecordId) {
            console.error('No dental_record_id available.');
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Dental Record ID is missing. Please save the dental record first.',
                timer: 3000,
                showConfirmButton: false
            });
            return;
        }

        // Capture values from the form
        var toothNumber = $('#modal-tooth').val().split(' ')[1]; // Extract the tooth number
        var status = $('#modal-status').val();
        var notes = $('#modal-notes').val();
        var updateImages = $('#modal-upload-images')[0].files; // Get selected images
        var isFirstSubmission = $('#modal-is-first-submission').val() === 'true'; // Check if this is a first submission

        // Debugging logs to check variables
        console.log(`Modal open isFirstSubmission: ${$('#modal-is-first-submission').val()}`);
        console.log(`Before submission - isFirstSubmission: ${isFirstSubmission}`);
        console.log(`dentalRecordId: ${dentalRecordId}, toothNumber: ${toothNumber}`);
        console.log(`status: ${status}, notes: ${notes}`);
        console.log(`updateImages length: ${updateImages.length}`);
        console.log(`SVG Path: ${$('#modal-svg-path').val()}`); // Log the svg_path

        // Validation: First submission does not require images, but subsequent submissions do
        if (!isFirstSubmission && updateImages.length === 0) {
            console.log('Image upload required for update but none provided.');

            // Show error message and stop form submission
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'You must upload images for the second submission.',
                timer: 3000,
                showConfirmButton: false
            });
            return false;  // Halt form submission
        }

        // If validation passed, proceed with the form submission
        console.log('All validation passed, proceeding with form submission.');

        // Disable button to prevent multiple submissions
        $('#save-tooth-details').prop('disabled', true);

        // Prepare FormData to send
        var formData = new FormData();
        formData.append('dental_record_id', dentalRecordId);
        formData.append('tooth_number', toothNumber);
        formData.append('status', status);
        formData.append('notes', notes);
        formData.append('svg_path', $('#modal-svg-path').val()); // Include svg_path

        // Append image files to FormData
        if (updateImages.length > 0) {
            console.log(`Appending ${updateImages.length} images.`);
            for (let i = 0; i < updateImages.length; i++) {
                formData.append('update_images[]', updateImages[i]);
            }
        }

        // AJAX call to store tooth details
        $.ajax({
            url: storeToothUrl, // Use the URL passed from Blade
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            // Existing success handler
            success: function (response) {
                console.log('AJAX success response:', response);

                if (response.update) {
                    console.log('Tooth updated successfully!');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Tooth details updated successfully! Awaiting approval.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    $('#previewModal').hide();

                    // Update the teethStatuses to reflect the pending update
                    teethStatuses[toothNumber] = {
                        status: status,
                        isApproved: false
                    };

                    // Disable further interactions for this tooth
                    $(`.tooth-${toothNumber}`)
                        .css('pointer-events', 'none')
                        .css('opacity', '0.6')
                        .attr('title', 'This tooth has a pending update and cannot be modified until approved.');
                    console.log(`Tooth ${toothNumber} is now pending approval.`);
                } else {
                    console.log('First-time save!');
                    $('#modal-is-first-submission').val('false'); // Ensure future submissions are marked as updates

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Tooth details saved successfully!',
                        timer: 3000,
                        showConfirmButton: false
                    });

                    $('#previewModal').hide();

                    // Update the teethStatuses to reflect the approved status
                    teethStatuses[toothNumber] = {
                        status: status,
                        isApproved: true
                    };

                    // Keep the tooth enabled since it's approved
                    $(`.tooth-${toothNumber}`)
                        .css('pointer-events', 'auto')
                        .css('opacity', '1')
                        .attr('title', '');
                    console.log(`Tooth ${toothNumber} is now approved and enabled.`);
                }
            },

            error: function (xhr) {
                console.error('Error saving tooth details:', xhr.responseText);

                if (xhr.responseJSON && xhr.responseJSON.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.error,
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to save tooth details!',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }

                // Re-enable the button after error
                $('#save-tooth-details').prop('disabled', false);
            },
            complete: function () {
                // Always re-enable the button after completion
                $('#save-tooth-details').prop('disabled', false);
            }
        });
    });

    // -------------------------------------------------
    // 11. Event Listener: Handle Image Uploads and Previews
    // -------------------------------------------------
    $('#modal-upload-images').change(function (event) {
        $('#image-preview-container').empty(); // Clear previous previews
        const files = event.target.files;
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            reader.onload = function (e) {
                const previewHtml = `
                    <div class="image-preview" style="display: inline-block; margin-right: 10px;">
                        <img src="${e.target.result}" alt="Dental Image Preview" class="preview-img img-thumbnail" style="max-width: 100px;">
                    </div>`;
                $('#image-preview-container').append(previewHtml);
            };
            reader.readAsDataURL(file); // Convert image to Base64 string
        }
    });

    // -------------------------------------------------
    // 12. Event Listener: Toggle Form Visibility
    // -------------------------------------------------
    $('.toggle-form-btn').click(function () {
        $('.dental-examination-form').toggle();
        $(this).text(function (i, text) {
            return text === "Hide Form" ? "Show Form" : "Hide Form";
        });

        console.log('Form visibility toggled');
    });

    // -------------------------------------------------
    // 13. Event Listener: Image Preview in Modal
    // -------------------------------------------------
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

    // -------------------------------------------------
    // 14. Function: Fetch and Populate Dental Examination History
    // -------------------------------------------------
    function fetchDentalExaminationHistory() {
        $.ajax({
            url: getDentalExaminationHistoryUrl,
            method: 'GET',
            data: { dental_record_id: dentalRecordId },
            success: function(response) {
                console.log('Dental Examination History:', response);
                populateDentalExaminationHistory(response);
            },
            error: function(xhr) {
                console.error('Error fetching dental examination history:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load dental examination history.',
                });
            }
        });
    }
    function populateDentalExaminationHistory(data) {
        if (!data || !Array.isArray(data)) {
            console.error('Dental examination history data is undefined, null, or not an array:', data);
            return;
        }
    
        const tableBody = $('#dental-examination-history-body');
        tableBody.empty();
    
        if (data.length === 0) {
            tableBody.append('<tr><td colspan="3">No previous examinations found.</td></tr>');
            return;
        }
    
        data.forEach(exam => {
            let examHtml = `
                <tr>
                    <td colspan="2"><strong>Date of Examination:</strong> ${exam.date_of_examination ? new Date(exam.date_of_examination).toLocaleDateString() : 'N/A'}</td>
                    <td><strong>Dentist Name:</strong> ${exam.dentist_name || 'N/A'}</td>
                </tr>
            `;
    
            // Define the fields to display
            const fields = [
                { key: 'findings', label: 'Findings' },
                { key: 'carries_free', label: 'Carries Free' },
                { key: 'poor_oral_hygiene', label: 'Poor Oral Hygiene' },
                { key: 'gum_infection', label: 'Gum Infection' },
                { key: 'restorable_caries', label: 'Restorable Caries' },
                { key: 'other_condition', label: 'Other Condition' },
                { key: 'personal_attention', label: 'Personal Attention' },
                { key: 'oral_prophylaxis', label: 'Oral Prophylaxis' },
                { key: 'fluoride_application', label: 'Fluoride Application' },
                { key: 'gum_treatment', label: 'Gum Treatment' },
                { key: 'ortho_consultation', label: 'Ortho Consultation' },
                { key: 'sealant_tooth', label: 'Sealant Tooth' },
                { key: 'filling_tooth', label: 'Filling Tooth' },
                { key: 'extraction_tooth', label: 'Extraction Tooth' },
                { key: 'endodontic_tooth', label: 'Endodontic Tooth' },
                { key: 'radiograph_tooth', label: 'Radiograph Tooth' },
                { key: 'prosthesis_tooth', label: 'Prosthesis Tooth' },
                { key: 'medical_clearance', label: 'Medical Clearance' },
                { key: 'other_recommendation', label: 'Other Recommendation' },
            ];
    
            let fieldCount = 0; // To check if any fields are displayed
    
            fields.forEach(field => {
                let value = exam[field.key];
                if (value !== null && value !== '' && value !== 0) {
                    fieldCount++;
                    // For boolean fields (tinyint), convert 1 to 'Yes', 0 to 'No'
                    if (typeof value === 'number' && (value === 0 || value === 1)) {
                        value = value === 1 ? 'Yes' : 'No';
                    }
                    examHtml += `
                        <tr>
                            <td colspan="3"><strong>${field.label}:</strong> ${value}</td>
                        </tr>
                    `;
                }
            });
    
            if (fieldCount === 0) {
                examHtml += `
                    <tr>
                        <td colspan="3">No details available.</td>
                    </tr>
                `;
            }
    
            // Add a separator row
            examHtml += `
                <tr>
                    <td colspan="3"><hr></td>
                </tr>
            `;
    
            tableBody.append(examHtml);
        });
    }
    
    // -------------------------------------------------
    // 15. Function: Fetch and Populate Tooth History
    // -------------------------------------------------
    function fetchToothHistory() {
        console.log('Fetching tooth history with Dental Record ID:', dentalRecordId);
        $.ajax({
            url: getToothHistoryUrl,
            method: 'GET',
            data: { dental_record_id: dentalRecordId },
            success: function(response) {
                console.log('Tooth History Response:', response);
                populateToothHistory(response);
            },
            error: function(xhr) {
                console.error('Error fetching tooth history:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load tooth history.',
                });
            }
        });
    }

    function populateToothHistory(data) {
        if (!data || !Array.isArray(data)) {
            console.error('Tooth history data is undefined, null, or not an array:', data);
            return;
        }
    
        const tableBody = $('#tooth-history-body');
        tableBody.empty();
    
        if (data.length === 0) {
            tableBody.append('<tr><td colspan="5">No tooth history found.</td></tr>');
            return;
        }
    
        data.forEach(tooth => {
            console.log('Processing tooth:', tooth); // Debugging
            const toothNumber = tooth.tooth_number ? tooth.tooth_number : 'N/A';
            const status = tooth.status ? tooth.status : 'N/A';
            const notes = tooth.notes ? tooth.notes : 'N/A';
            const updatedAt = tooth.updated_at ? new Date(tooth.updated_at).toLocaleDateString() : 'N/A';
    
            let dentalPicturesHtml = '';
    
            if (tooth.dental_pictures && tooth.dental_pictures.length > 0) {
                dentalPicturesHtml = '<div class="dental-pictures">';
    
                tooth.dental_pictures.forEach(picturePath => {
                    const pictureUrl = `/storage/${picturePath}`; // Ensure this path is correct
                    dentalPicturesHtml += `
                        <img src="${pictureUrl}" alt="Dental Picture" class="dental-picture-preview">
                    `;
                });
    
                dentalPicturesHtml += '</div>';
            } else {
                dentalPicturesHtml = 'None';
            }
    
            tableBody.append(`
                <tr>
                    <td>Tooth ${toothNumber}</td>
                    <td>${status}</td>
                    <td>${notes}</td>
                    <td>${dentalPicturesHtml}</td>
                    <td>${updatedAt}</td>
                </tr>
            `);
        });
    
        // Attach click event to dental picture previews
        $('.dental-picture-preview').off('click').on('click', function () {
            const src = $(this).attr('src');
            Swal.fire({
                title: 'Dental Picture Preview',
                imageUrl: src,
                imageAlt: 'Dental Picture',
                showConfirmButton: false,
                showCloseButton: true,
            });
        });
    }

    // -------------------------------------------------
    // 16. Function: Enable All Teeth Interactions
    // -------------------------------------------------
    function enableAllTeethInteractions() {
        $('.svg-container .tooth').css('pointer-events', 'auto').css('opacity', '1');
    }

    // Call this function at the end of your page load logic
    enableAllTeethInteractions();

    // -------------------------------------------------
    // 17. Initialize Dental Record on Page Load
    // -------------------------------------------------
    // Automatically initialize dental record on page load
    initializeDentalRecord();
});
