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
            return 'green'; // Default color for Healthy or non-existing teeth
        }
    }

    // Show modal on tooth click and fetch the latest status
    $('.svg-container path').click(function () {
        var toothClass = $(this).attr('class');
        var toothNumber = toothClass.match(/tooth-(\d+)/)[1]; // Extract the tooth number

        // Get the dental_record_id from the hidden input
        var dentalRecordId = $('#dental-record-id').val();

        // Ensure dental_record_id is not empty
        if (!dentalRecordId) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Dental record not found. Please search for a record first.',
            });
            return;
        }

        // Fetch the tooth details (status, description, etc.)
        $.ajax({
            url: window.getToothStatusUrl, // Use the correct route
            method: 'GET',
            data: {
                tooth_number: toothNumber,
                dental_record_id: dentalRecordId  // Pass the dental_record_id here
            },
            success: function (response) {
                let status = response.status || 'Healthy'; // Default to Healthy if no status
                let description = teethData[toothNumber] || 'No description available'; // Use tooth description from teethData

                // Update modal fields
                $('#modal-tooth').val('Tooth ' + toothNumber);
                $('#modal-status').val(status);
                $('#modal-notes').val(description).prop('disabled', true);

                // Update the color of the clicked tooth
                let fillColor = getColorBasedOnStatus(status);
                $(`.tooth-${toothNumber}`).css('fill', fillColor);

                $('#previewModal').show();  // Show the modal
            },
            error: function (xhr) {
                console.error('Error fetching tooth status:', xhr.responseText);
            }
        });
    });

    // Close the modal when clicking the close button
    $('.close').click(function () {
        $('#previewModal').hide();
    });

    // Close the modal when clicking outside the modal
    $(window).click(function (event) {
        if (event.target.id === 'previewModal') {
            $('#previewModal').hide();
        }
    });

    // Search for dental records
    $('.search-btn').click(function () {
        var searchTerm = $('#search-bar').val();
    
        if (searchTerm === '') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please enter a valid Student ID.',
            });
            return;
        }
    
        $.ajax({
            url: window.searchRecordsUrl, // Use the global variable for the search route
            method: 'GET',
            data: {
                search_term: searchTerm
            },
            success: function (response) {
                if (response.dentalRecord) {
                    // Populate the dental record information
                    $('#id_number').val(response.dentalRecord.id_number);
                    $('#student-name').val(response.dentalRecord.patient_name);
                    $('#grade-sections').val(response.dentalRecord.grade_section);
    
                    // Set the dental_record_id in the hidden input field
                    $('#dental-record-id').val(response.dentalRecord.id);
    
                    // Handle teeth details
                    response.teeth.forEach(function (tooth) {
                        const parentClass = `.tooth-${tooth.tooth_number}`;
                        let fillColor = getColorBasedOnStatus(tooth.status);
                        $(parentClass).css('fill', fillColor);
                    });
    
                    // Handle missing teeth records (set default to green)
                    const allTeethNumbers = Object.keys(teethData);
                    allTeethNumbers.forEach(function (toothNumber) {
                        const parentClass = `.tooth-${toothNumber}`;
                        if (!response.teeth.some(t => t.tooth_number == toothNumber)) {
                            $(parentClass).css('fill', 'green');
                        }
                    });
    
                    Swal.fire({
                        icon: 'success',
                        title: 'Record Found',
                        text: 'Dental record found and loaded successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'No Record Found',
                        text: 'No dental record found for the provided ID number.',
                    });
                    $('#dental-record-id').val('');  // Clear the dental_record_id
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please type a valid Id Number.',
                });
            }
        });
    });
});