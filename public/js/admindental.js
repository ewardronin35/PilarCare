$(document).ready(function () {
    const today = new Date().toISOString().substr(0, 10);
    $('#date').val(today);

    $('#search-bar').on('input', function () {
        let input = $(this).val();
        // Allow only letters and numbers, removing special characters
        $(this).val(input.replace(/[^a-zA-Z0-9]/g, '').slice(0, 7)); // Keep only alphanumeric characters and limit to 7 characters
    });

    const teethData = {
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

    $('.tab-button').click(function () {
        const tab = $(this).data('tab');

        // Remove 'active' class from all buttons and tab contents
        $('.tab-button').removeClass('active');
        $('.tab-content').removeClass('active').fadeOut(200);

        // Add 'active' class to the clicked button
        $(this).addClass('active');

        // Show the corresponding tab content with fade-in effect
        $('#' + tab).fadeIn(200).addClass('active');
    });

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

    function populateDentalHistory(data) {
        if (!data) {
            console.error('Dental history data is undefined or null:', data);
            return;
        }

        // Populate Patient Information
        const patientInfoBody = $('#patient-info-body');
        patientInfoBody.empty();

        const formattedDOB = data.birthdate ? new Date(data.birthdate).toLocaleDateString() : 'N/A';
        const lastVisitDate = (data.previousExaminations && data.previousExaminations.length > 0)
            ? new Date(data.previousExaminations[0].date_of_examination).toLocaleDateString()
            : 'N/A';

        const patientName = data.name || 'N/A';
        const dateOfBirth = data.birthdate ? formattedDOB : 'N/A';

        patientInfoBody.append(`
            <tr>
                <td><strong>Patient Name:</strong></td>
                <td>${patientName}</td>
            </tr>
            <tr>
                <td><strong>Date of Birth:</strong></td>
                <td>${dateOfBirth}</td>
            </tr>
            <tr>
                <td><strong>Last Visit Date:</strong></td>
                <td>${lastVisitDate}</td>
            </tr>
        `);

        // Populate Previous Examinations
        const prevExamBody = $('#dental-examination-history-body');
        prevExamBody.empty();
        if (data.previousExaminations && data.previousExaminations.length > 0) {
            data.previousExaminations.forEach(exam => {
                const formattedDate = exam.date_of_examination ? new Date(exam.date_of_examination).toLocaleDateString() : 'N/A';
                const dentistName = exam.dentist_name || 'N/A';
                const findings = exam.findings || 'N/A';
                const row = `
                    <tr>
                        <td>${formattedDate}</td>
                        <td>${dentistName}</td>
                        <td>${findings}</td>
                    </tr>
                `;
                prevExamBody.append(row);
            });
        } else {
            prevExamBody.append('<tr><td colspan="3">No previous examinations found.</td></tr>');
        }

        // Populate Tooth History
        const toothHistoryBody = $('#tooth-history-body');
        toothHistoryBody.empty();
        if (data.toothHistory && data.toothHistory.length > 0) {
            data.toothHistory.forEach(tooth => {
                const formattedDate = tooth.updated_at ? new Date(tooth.updated_at).toLocaleDateString() : 'N/A';
                const dentalPictures = tooth.dental_pictures ? JSON.parse(tooth.dental_pictures) : [];
                let picturesHtml = 'N/A';
                if (dentalPictures.length > 0) {
                    picturesHtml = dentalPictures.map(pic => `<img src="/storage/${pic}" alt="Dental Picture" style="max-width: 100px; margin-right: 5px;">`).join('');
                }
                const row = `
                    <tr>
                        <td>${tooth.tooth_number}</td>
                        <td>${tooth.status}</td>
                        <td>${tooth.notes || 'N/A'}</td>
                        <td>${picturesHtml}</td>
                        <td>${formattedDate}</td>
                    </tr>
                `;
                toothHistoryBody.append(row);
            });
        } else {
            toothHistoryBody.append('<tr><td colspan="5">No tooth history found.</td></tr>');
        }

        // Populate Next Scheduled Appointment
        const appointmentBody = $('#next-appointment-body');
        appointmentBody.empty();
        if (data.nextAppointment) {
            const formattedDate = data.nextAppointment.appointment_date
                ? new Date(data.nextAppointment.appointment_date).toLocaleDateString()
                : 'N/A';
            const purpose = data.nextAppointment.purpose || 'N/A';
            const row = `
                <tr>
                    <td><strong>Date:</strong></td>
                    <td>${formattedDate}</td>
                </tr>
                <tr>
                    <td><strong>Purpose:</strong></td>
                    <td>${purpose}</td>
                </tr>
            `;
            appointmentBody.append(row);
        } else {
            appointmentBody.append('<tr><td colspan="2">No upcoming appointments found.</td></tr>');
        }
    }

    // Function to clear the dental record display before fetching
    function clearDentalRecordDisplay() {
        $('#record-id_number').val('');
        $('#student-name').val('');
        $('#grade-sections').val('');
        $('#dental-record-id').val('');

        // Reset teeth colors to default (green)
        const allTeethNumbers = Object.keys(teethData);
        allTeethNumbers.forEach(function (toothNumber) {
            const parentClass = `.tooth-${toothNumber}`;
            $(parentClass).css('fill', 'green');  // Set default to green for Healthy
        });

        // Clear Dental History Tables
        // Clear patient info
        const patientInfoBody = $('#patient-info-body');
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

        // Clear Previous Examinations
        $('#dental-examination-history-body').empty().append('<tr><td colspan="3">No previous examinations found.</td></tr>');

        // Clear Tooth History
        $('#tooth-history-body').empty().append('<tr><td colspan="5">No tooth history found.</td></tr>');

        // Clear Next Appointment
        $('#next-appointment-body').empty().append('<tr><td colspan="2">No upcoming appointments found.</td></tr>');
    }

    // Event listener for the search button in Dental Record Tab
    $('.search-btn').click(function () {
        const searchTerm = $('#search-bar').val().trim();

        if (searchTerm.length !== 7) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Input',
                text: 'Please enter a valid 7-character ID (letters and numbers only).',
            });
            return;
        }

        // Clear the previous dental record before performing a new search
        clearDentalRecordDisplay();

        $.ajax({
            url: window.searchRecordsUrl, // Use the global variable for the search route
            method: 'GET',
            data: { search_term: searchTerm },
            success: function (response) {
                console.log('AJAX Response:', response); // Debugging

                if (response.dentalRecord) {
                    let role = response.role ? response.role.toLowerCase() : 'unknown';
                    let gradeSectionLabel = 'Grade & Section';
                    let gradeSectionValue = response.grade_section || '';

                    // Adjust labels based on role
                    if (role === 'teacher') {
                        gradeSectionLabel = 'BED/HED';
                        gradeSectionValue = response.grade_section || '';
                    } else if (role === 'staff') {
                        gradeSectionLabel = 'Position';
                        gradeSectionValue = response.grade_section || '';
                    } else if (role === 'unknown') {
                        console.warn('User role is undefined. Using default labels.');
                        // Optionally set default labels or handle accordingly
                    }

                    // Update labels and inputs
           // Update labels and inputs
$('label[for="grade-sections"]').text(gradeSectionLabel);
$('label[for="grade-section"]').text(gradeSectionLabel);
$('#record-id_number').val(response.dentalRecord.id_number || '');
$('#form-id_number').val(response.dentalRecord.id_number || '');
$('#student-name').val(response.name || '');

// Add these lines to update the Dental Examination form
$('#name').val(response.name || ''); // Update Name in Dental Examination form
$('#grade-section').val(gradeSectionValue); // Update Grade & Section in Dental Examination form

$('#grade-sections').val(gradeSectionValue);
$('#birthdate').val(response.birthdate || '');
$('#age').val(response.age || '');
$('#date').val(today);
$('#dental-record-id').val(response.dentalRecord.dental_record_id || '');


                    // Populate Teeth Details and Dental History
                    populateTeeth(response.teeth);
                    populateDentalHistory(response);

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
                    $('#form-id_number').val('');  // Clear the hidden dental_record_id
                    $('#record-id_number').val('');
                }
            },
            error: function (xhr) {
                console.error('AJAX Error:', xhr.responseText); // Debugging
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON.message || 'An error occurred while searching.',
                });
            }
        });
    });

    // Image preview logic
    $('#modal-upload-images').change(function (event) {
        $('#image-preview-container').empty(); // Clear previous previews
        const files = event.target.files;
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            reader.onload = function (e) {
                const previewHtml = `
                    <div class="image-preview">
                        <img src="${e.target.result}" alt="Dental Image Preview" class="preview-img img-thumbnail" style="max-width: 100px; margin-right: 10px;">
                    </div>`;
                $('#image-preview-container').append(previewHtml);
            };
            reader.readAsDataURL(file); // Convert image to Base64 string
        }
    });

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

    // Handle form submission for dental exam
    $('#dental-exam-form').on('submit', function (event) {
        event.preventDefault(); // Prevent form from refreshing
        const formData = $(this).serialize(); // Serialize form data

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

        // AJAX request to submit the dental exam data
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Dental examination data saved successfully!',
                    timer: 2000,
                    showConfirmButton: false
                });
                // Additional actions after successful submission (e.g., clearing the form)
                $('#dental-exam-form')[0].reset();
                $('#date').val(today); // Reset date to today's date

                // Reset id_number fields
                $('#form-id_number').val('');
                $('#record-id_number').val('');

                // Optionally, reset teeth colors and dental history
                clearDentalRecordDisplay();
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON.message || 'An error occurred while saving the dental examination data.',
                });
            }
        });
    });
});
