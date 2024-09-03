$(document).ready(function () {
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

    // Function to determine the fill color based on the tooth status
    function getColorBasedOnStatus(status) {
        if (status === 'Aching') {
            return 'red';
        } else if (status === 'Cavity') {
            return 'orange';
        } else if (status === 'Missing') {
            return 'gray';
        } else {
            return 'green'; // Default color for Healthy
        }
    }

    // Interaction logic for each tooth
    function handleToothInteraction(toothNumber) {
        const parentClass = `.tooth-${toothNumber}`; // Target the specific class

        // Set the initial color based on the status fetched via AJAX
        $.ajax({
            url: getToothStatusUrl, // Use the URL passed from Blade
            method: 'GET',
            data: {
                tooth_number: toothNumber,
                dental_record_id: $('#dental-record-id').val()
            },
            success: function (response) {
                let status = response.status || 'Healthy'; // Default to Healthy if not found
                let fillColor = getColorBasedOnStatus(status);
                $(parentClass).css('fill', fillColor);
                console.log(`Initial color set for Tooth ${toothNumber}: ${status}`);
            },
            error: function (xhr) {
                console.error('Error fetching tooth status:', xhr.responseText);
            }
        });

        // Hover event on tooth SVG
        $(parentClass).mouseover(function () {
            $(parentClass).css('fill', 'lightblue');
            console.log(`Hovered over Tooth ${toothNumber}`);
        }).mouseleave(function () {
            $(parentClass).css('fill', getColorBasedOnStatus(teethStatuses[toothNumber]));
            console.log(`Mouse left Tooth ${toothNumber}`);
        });

        // Focus event on input field
        $(`.input-tooth-${toothNumber}`).focus(function () {
            $(parentClass).css('fill', 'yellow');
            console.log(`Focused on Tooth ${toothNumber}`);
        }).blur(function () {
            $(parentClass).css('fill', getColorBasedOnStatus(teethStatuses[toothNumber]));
            console.log(`Blurred from Tooth ${toothNumber}`);
        });
    }

    // Apply interaction logic for each tooth
    const teethNumbers = [
        11, 12, 13, 14, 15, 16, 17, 18, 21, 22, 23, 24, 25, 26, 27, 28,
        31, 32, 33, 34, 35, 36, 37, 38, 41, 42, 43, 44, 45, 46, 47, 48
    ];
    teethNumbers.forEach(handleToothInteraction);

    // Show modal on tooth click and fetch the latest status
    $('.svg-container path').click(function () {
        var toothClass = $(this).attr('class');
        var toothNumber = toothClass.match(/tooth-(\d+)/)[1];
        var description = teethData[toothNumber] || 'No description available';

        $.ajax({
            url: getToothStatusUrl, // Use the URL passed from Blade
            method: 'GET',
            data: {
                tooth_number: toothNumber,
                dental_record_id: $('#dental-record-id').val()
            },
            success: function (response) {
                let status = response.status || 'Healthy'; // Default to Healthy if not found
                $('#modal-status').val(status);
                console.log(`Loaded Tooth ${toothNumber} Status: ${status}`);
            },
            error: function (xhr) {
                console.error('Error fetching tooth status:', xhr.responseText);
            }
        });

        $('#modal-tooth').val('Tooth ' + toothNumber);
        $('#modal-notes').val(description).prop('disabled', true);
        $('#previewModal').show();
    });

    // Close modal
    $('.close').click(function () {
        $('#previewModal').hide();
    });

    $(window).click(function (event) {
        if (event.target.id === 'previewModal') {
            $('#previewModal').hide();
        }
    });

    // Save tooth details with AJAX
    $('#save-tooth-details').click(function () {
        var toothNumber = $('#modal-tooth').val().split(' ')[1]; // Extract the tooth number
        var status = $('#modal-status').val();
        var notes = $('#modal-notes').val();
        var dentalRecordId = $('#dental-record-id').val(); // Retrieve the dental record ID

        console.log('Attempting to save tooth details...');
        console.log('Tooth Number:', toothNumber);
        console.log('Status:', status);
        console.log('Notes:', notes);
        console.log('Dental Record ID:', dentalRecordId);

        // Check if the dentalRecordId is present
        if (!dentalRecordId) {
            console.error('Error: Dental Record ID is missing.');
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Dental Record ID is missing.',
            });
            return; // Stop the function execution if dentalRecordId is missing
        }

        $.ajax({
            url: storeToothUrl, // Use the URL passed from Blade
            method: 'POST',
            data: {
                _token: $('input[name="_token"]').val(),
                dental_record_id: dentalRecordId, // Include the dental record ID
                tooth_number: toothNumber,
                status: status,
                notes: notes
            },
            success: function (response) {
                console.log('Response:', response); // Log the response for debugging

                // Update the color of the tooth based on the new status
                teethStatuses[toothNumber] = status; // Update the local teethStatuses object
                const parentClass = `.tooth-${toothNumber}`;
                $(parentClass).css('fill', getColorBasedOnStatus(status));

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Tooth details saved successfully!',
                    timer: 3000,
                    showConfirmButton: false
                });
                $('#previewModal').hide();
            },
            error: function (xhr) {
                console.error('Error saving tooth details:', xhr.responseText); // Log the error to the console for debugging
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to save tooth details!',
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        });
    });

    // Toggle form visibility
    $('.toggle-form-btn').click(function () {
        $('.dental-examination-form').toggle();
        $(this).text(function (i, text) {
            return text === "Hide Form" ? "Show Form" : "Hide Form";
        });

        console.log('Form visibility toggled');
    });
});


$('#save-dental-record').click(function () {
    var dentalRecordId = $('#dental-record-id').val();
    var idNumber = $('#id_number').val(); // Capture the ID number
    console.log('Saving dental record with ID:', dentalRecordId);
    console.log('ID Number:', idNumber);

    var formData = $('#dental-record-form').serialize();
    $.ajax({
        url: storeDentalRecordUrl, // Use the URL passed from Blade
        method: 'POST',
        data: formData,
        success: function (response) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Dental record saved successfully!',
                timer: 3000,
                showConfirmButton: false
            });
        },
        error: function (xhr) {
            console.error('Error:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to save dental record!',
                timer: 3000,
                showConfirmButton: false
            });
        }
    });
});
