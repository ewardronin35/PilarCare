document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('search-student').addEventListener('click', function() {
        var studentId = document.getElementById('student-id').value;

        if (studentId) {
            fetch('/admin/get-student-info/' + studentId)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data);

                    if (data.success) {
                        document.getElementById('name').value = data.student.name;
                        document.getElementById('grade-section').value = data.student.grade_section;
                        document.getElementById('birthdate').value = data.information.birthdate;
                        document.getElementById('age').value = data.information.age;
                        document.getElementById('dental-record-id').value = data.student.dental_record_id; // Ensure the dental_record_id is set

                        Swal.fire({
                            icon: 'success',
                            title: 'Student Found',
                            text: 'Student data has been successfully retrieved.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.message || 'Student not found!',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching student data:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while fetching student data.',
                    });
                });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'Please enter a Student ID.',
            });
        }
    });

    // Listen for the submit event on the form
    const form = document.querySelector('form[action="' + window.dentalExamStoreUrl + '"]');
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Check if the required fields are filled
        const dateOfExamination = document.getElementById('date').value;
        const dentalRecordId = document.getElementById('dental-record-id').value; // Check dental_record_id

        if (!dateOfExamination || !dentalRecordId) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Date of Examination and Dental Record ID are required.',
            });
            return; // Stop the form submission
        }

        const formData = new FormData(form);
        formData.append('dental_record_id', dentalRecordId); // Ensure this is appended to formData

        // Handle checkboxes manually to ensure they are always sent
        const checkboxes = [
            'carries_free', 'poor_oral_hygiene', 'gum_infection', 
            'restorable_caries', 'personal_attention', 'oral_prophylaxis', 
            'fluoride_application', 'gum_treatment', 'ortho_consultation', 
            'extraction', 'endodontic', 'radiograph', 'prosthesis', 
            'others_specify'
        ];

        checkboxes.forEach(id => {
            const checkbox = document.getElementById(id);
            formData.append(id, checkbox && checkbox.checked ? '1' : '0');
        });

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            return response.json().then(data => {
                if (!response.ok) {
                    if (data.errors) {
                        console.log('Validation errors:', data.errors);
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please check your input.',
                            footer: JSON.stringify(data.errors)
                        });
                    }
                    throw new Error('Validation failed');
                }
                return data;
            });
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Saved',
                    text: data.message || 'Dental Examination saved successfully!',
                }).then(() => {
                    form.reset();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'An error occurred while saving the dental examination.',
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while processing your request.',
            });
        });
    });


});
