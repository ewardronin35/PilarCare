<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    body {
        background-color: #f5f7fa;
        font-family: 'Poppins', sans-serif;
    }

    .main-content {
        flex-grow: 1;
        padding: 20px;
        margin-top: 30px;
        margin-left: 80px;
        transition: margin-left 0.3s ease-in-out;
        overflow-y: auto;
    }

    .profile-box {
        display: flex;
        align-items: center;
        padding: 20px;
        background-image: url('{{ asset('images/bg.jpg') }}');
        background-size: cover;
        background-position: center;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        color: white;
    }

    .profile-box img {
        border-radius: 50%;
        width: 80px;
        height: 80px;
        margin-right: 20px;
    }

    .profile-info {
        display: flex;
        flex-direction: column;
    }

    .profile-info h2 {
        margin: 0;
        font-size: 1.5em;
    }

    .profile-info p {
        margin: 0;
    }

    .edit-profile-btn {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease-in-out;
        text-align: center;
        text-decoration: none;
        margin-top: 10px;
    }

    .edit-profile-btn:hover {
        background-color: #0056b3;
    }

    .statistics {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        gap: 10px;
    }

    .statistics .stat-box {
        background-color: #ffffff;
        color: #333;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        width: calc(33.33% - 10px);
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease-in-out;
    }

    .statistics .stat-box:hover {
        background-color: #f0f0f0;
    }

    .stat-box img {
        width: 50px;
        height: 50px;
        display: block;
        margin: 0 auto 10px;
    }

    .stat-box a {
        color: #007bff;
        text-decoration: none;
    }

    .stat-box a:visited {
        color: #007bff;
    }

    .stat-box a:hover {
        text-decoration: none;
    }

    .data-table-wrapper {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        width: 100%;
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        animation: fadeInUp 0.5s ease-in-out;
    }

    .data-table th,
    .data-table td {
        padding: 10px;
        text-align: left;
    }

    .data-table th {
        background-color: #f5f5f5;
        color: #333;
        font-weight: 600;
        border-bottom: 1px solid #ddd;
    }

    .data-table td {
        border-bottom: 1px solid #eee;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Modal Styling */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
        border: 1px solid #888;
        max-width: 700px; /* Reduced max width to better fit the screen */
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        gap: 15px; /* Reduced gap between elements */
        animation: fadeInUp 0.5s ease-in-out;
        align-items: center; /* Center the image at the top */
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
    }

    /* Profile Picture Section */
    .profile-picture-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        margin-bottom: 10px; /* Reduced bottom margin */
        position: relative;
    }

    .profile-picture-container img {
        border-radius: 50%;
        width: 80px; /* Reduced width of the profile picture */
        height: 80px;
        object-fit: cover;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 10px;
    }

    .profile-picture-container input[type="file"] {
        display: none;
    }

    .profile-picture-container label {
        display: inline-block;
        background-color: #007bff;
        color: white;
        padding: 6px 12px; /* Reduced padding for the label */
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9rem;
        text-align: center;
        transition: background-color 0.3s;
    }

    .profile-picture-container label:hover {
        background-color: #0056b3;
    }

    /* Form Group Styling */
    .form-group {
        width: 100%;
        margin-top: 15px; /* Reduced top margin */
        display: flex;
        flex-direction: column;
        gap: 5px; /* Reduced gap */
    }

    .form-group label {
        font-weight: bold;
        color: #333;
    }

    .form-group input[type="text"],
    .form-group input[type="date"],
    .form-group input[type="file"],
    .form-group input[type="number"],
    .form-group textarea {
        padding: 8px; /* Reduced padding */
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 100%;
        box-sizing: border-box;
        font-size: 0.9rem; /* Adjust font size */
        font-family: 'Poppins', sans-serif;
    }

    .form-group input[type="checkbox"] {
        margin-right: 10px;
    }

    .row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .row .form-group {
        width: 48%; /* Reduced width to fit */
    }

    .optional-sibling {
        display: none;
    }

    .next-button {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        margin-top: 10px;
        display: flex;
        align-items: center;
        font-family: 'Poppins', sans-serif;
        justify-content: center;
        gap: 10px;
    }

    .next-button:hover {
        background-color: #0056b3;
    }

    .submit-button {
        background-color: #0056b3;
        color: white;
        padding: 8px 15px; /* Adjusted padding */
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .submit-button:hover {
        background-color: #003f88;
    }

    /* Disclaimer and Medicines Group */
    .disclaimer {
        margin-bottom: 15px; /* Reduced bottom margin */
        text-align: justify;
        font-size: 14px;
        color: #555;
    }

    .medicines-group {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .medicines-group label {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Miscellaneous styles for hover effects and interactions */
    .arrow {
        display: inline-block;
        transition: transform 0.3s;
    }

    .arrow:hover {
        transform: translateX(5px);
    }

    .welcome-message {
        text-align: center;
        font-size: 1.8em;
        margin-bottom: 20px;
    }
    @media screen and (max-width: 768px) {
    .modal-content {
        max-width: 95%; /* Ensures it doesn't take full screen width on mobile */
    }

    .row .form-group {
        width: 100%; /* Stack fields on smaller screens */
    } 
}
</style>


    <div class="main-content">
        <div class="profile-box">
            <img src="{{ asset('images/pilarLogo.jpg') }}" alt="Profile Image">
            <div class="profile-info">
                <h2>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h2>
                <p>{{ Auth::user()->role }}</p>
                <a href="{{ route('profile.edit') }}" class="edit-profile-btn">Edit Profile</a>
            </div>
        </div>

        <div class="statistics">
            <div class="stat-box">
                <a href="{{ route('student.appointment') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/appointment-reminders.png" alt="Appointments Icon">
                    <h2>{{ $appointmentCount }}</h2>
                    <p>Appointments</p>
                </a>
            </div>
            <div class="stat-box">
                <img src="https://img.icons8.com/ios-filled/50/000000/medical-doctor.png" alt="Health Record Icon">
                <h2>No</h2>
                <p>Health Record Submitted</p>
            </div>
            <div class="stat-box">
                <a href="{{ route('student.complaint') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/complaint.png" alt="Complaints Icon">
                    <h2>{{ $complaintCount }}</h2>
                    <p>Complaints</p>
                </a>
            </div>
        </div>

        <div class="calendar-wrapper">
    <div id="calendar"></div> <!-- FullCalendar will render here -->
</div>

    <!-- The Modal -->
    <div id="welcomeModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="welcomeMessage" class="welcome-message show">
                <img src="{{ asset('images/pilarLogo.jpg') }}" alt="PilarCare Logo" width="100">
                <h2>Welcome to PilarCare, {{ Auth::user()->first_name }}!</h2>
            </div>
            <div class="disclaimer" id="disclaimerSection">
                <h3>Data Privacy Disclaimer</h3>
                <p>
                    In compliance with the Data Privacy Act of 2012, all gathered data from the participant will be treated with utmost confidentiality to protect the participant’s/respondent’s privacy.
                </p>
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="agree_disclaimer" name="agree_disclaimer" required>
                        I have read and understood the above statement. I agree to participate voluntarily in this research without any force.
                    </label>
                </div>
                <p>After agreeing to the terms, you will proceed to fill out your profile information.</p>
                <button type="button" class="next-button" id="nextButton" disabled>
                    Next <span class="arrow">&rarr;</span>
                </button>
            </div>

            <form id="welcomeForm" action="{{ route('student.profile.store') }}" method="POST" enctype="multipart/form-data" style="display: none;">
                @csrf
                <div class="profile-picture-container">
    <img id="profile_preview" class="profile-pic-preview" src="{{ asset('images/default-profile.png') }}" alt="Profile Picture Preview" style="display: none;">
    <label for="profile_picture" class="profile-picture-label"><i class="fas fa-camera"></i> Change Profile Picture</label>
    <input type="file" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewImage(event)">
</div>

                <div class="row">
                <div class="form-group">
        <label for="parent_name_father"><i class="fas fa-user"></i> Father's Name</label>
        <input type="text" id="parent_name_father" name="parent_name_father" required 
       oninput="validateLetters(this)" onblur="validateLetters(this)" onpaste="validateLetters(this)" pattern="[A-Za-z\s]+">
    </div>
    <div class="form-group">
        <label for="parent_name_mother"><i class="fas fa-user"></i> Mother's Name</label>
        <input type="text" id="parent_name_mother" name="parent_name_mother" required 
        oninput="validateLetters(this)" onblur="validateLetters(this)" onpaste="validateLetters(this)" pattern="[A-Za-z\s]+">
    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="birthdate"><i class="fas fa-calendar-alt"></i> Your Birthdate</label>
                        <input type="date" id="birthdate" name="birthdate" required>
                    </div>
                    <div class="form-group">
                    <label for="address"><i class="fas fa-map-marker-alt"></i> Address</label>
                    <textarea id="address" name="address" rows="2" required></textarea>
                </div>
                </div>
                <div class="row">
                <div class="form-group">
        <label for="emergency_contact_number"><i class="fas fa-phone-alt"></i> Emergency Contact Number</label>
        <input type="text" id="emergency_contact_number" name="emergency_contact_number" required 
       maxlength="11" oninput="validateNumbers(this)" onblur="validateNumbers(this)" onpaste="validateNumbers(this)" pattern="\d{11}">
    </div>
    <div class="form-group">
        <label for="personal_contact_number"><i class="fas fa-phone"></i> Personal Contact Number</label>
        <input type="text" id="personal_contact_number" name="personal_contact_number" required 
       maxlength="11" oninput="validateNumbers(this)" onblur="validateNumbers(this)" onpaste="validateNumbers(this)" pattern="\d{11}">
    </div>
                </div>
                <div class="row">
                <div class="form-group">
                    <label for="medical_history"><i class="fas fa-notes-medical"></i> Medical History</label>
                    <textarea id="medical_history" name="medical_history" rows="3" required></textarea>
                </div>
                <div class="form-group">
                        <label for="allergies"><i class="fas fa-allergies"></i> Allergies</label>
                        <textarea id="allergies" name="allergies" rows="2" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="chronic_conditions"><i class="fas fa-heartbeat"></i> Chronic Conditions</label>
                        <textarea id="chronic_conditions" name="chronic_conditions" rows="2" required></textarea>
                    </div>
                    
                </div>
                <div class="form-group">
                    <label><i class="fas fa-pills"></i> Medicines (select all that apply)</label>
                    <div class="medicines-group">
                        <label><input type="checkbox" name="medicines[]" value="Paracetamol"> Paracetamol</label>
                        <label><input type="checkbox" name="medicines[]" value="Ibuprofen"> Ibuprofen</label>
                        <label><input type="checkbox" name="medicines[]" value="Mefenamic Acid"> Mefenamic Acid</label>
                        <label><input type="checkbox" name="medicines[]" value="Citirizine/Loratadine"> Citirizine/Loratadine</label>
                        <label><input type="checkbox" name="medicines[]" value="Camphor + Menthol Liniment"> Camphor + Menthol Liniment</label>
                        <label><input type="checkbox" name="medicines[]" value="PPA"> PPA</label>
                        <label><input type="checkbox" name="medicines[]" value="Phenylephrine"> Phenylephrine</label>
                        <label><input type="checkbox" name="medicines[]" value="Antacid"> Antacid</label>
                    </div>
                </div>
                <div class="row">
                <div class="form-group">
                    <label for="surgical_history"><i class="fas fa-scalpel"></i> Surgical History</label>
                    <textarea id="surgical_history" name="surgical_history" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="medical_history"><i class="fas fa-notes-medical"></i> Medical History</label>
                    <textarea id="medical_history" name="medical_history" rows="3" required></textarea>
                </div>
    </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="agree_terms" name="agree_terms" required>
                        I agree to the terms and conditions.
                    </label>
                </div>
                <input type="hidden" name="id_number" value="{{ Auth::user()->id_number }}">
                <button type="submit" class="submit-button" id="submitButton" disabled>
                    Submit <span class="arrow">&rarr;</span>
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById("welcomeModal");
            const nextButton = document.getElementById("nextButton");
            const submitButton = document.getElementById("submitButton");
            const welcomeForm = document.getElementById("welcomeForm");
            const welcomeMessage = document.getElementById('welcomeMessage');
            const agreeDisclaimer = document.getElementById("agree_disclaimer");
            const agreeTerms = document.getElementById("agree_terms");
            const profilePreview = document.getElementById('profile_preview');
            const disclaimerSection = document.getElementById('disclaimerSection');
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            let formInteraction = false;

            if (!csrfToken) {
                console.error('CSRF token not found in meta tags');
                return;
            }

            // Function to enable the beforeunload prompt
            function enableBeforeUnload() {
                window.addEventListener('beforeunload', preventFormClose);
            }

            // Validation for letters only in father's and mother's names
            function validateLetters(input) {
                input.value = input.value.replace(/[^A-Za-z\s]/g, '');
            }

            // Validation for numbers only in contact numbers
            function validateNumbers(input) {
                input.value = input.value.replace(/[^0-9]/g, '');
            }

            agreeDisclaimer.addEventListener('change', function() {
                nextButton.disabled = !this.checked;
            });

            // Countdown before enabling the next button and showing the form
            nextButton.addEventListener('click', function() {
                let countdown = 10;
                nextButton.disabled = true;
                nextButton.innerHTML = `Please wait ${countdown}s`;

                const countdownInterval = setInterval(() => {
                    countdown--;
                    nextButton.innerHTML = `Please wait ${countdown}s`;

                    if (countdown === 0) {
                        clearInterval(countdownInterval);
                        nextButton.innerHTML = `Next <span class="arrow">&rarr;</span>`;
                        nextButton.disabled = false;

                        // Hide welcome message and show form
                        welcomeMessage.style.display = 'none';
                        disclaimerSection.style.display = 'none';
                        welcomeForm.style.display = 'block';

                        // Animate the form coming into view
                        welcomeForm.style.opacity = 0;
                        welcomeForm.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            welcomeForm.style.transition = 'opacity 0.5s, transform 0.5s';
                            welcomeForm.style.opacity = 1;
                            welcomeForm.style.transform = 'translateY(0)';
                        }, 100);
                    }
                }, 1000);
            });

            // Function to remove the beforeunload prompt
            function disableBeforeUnload() {
                window.removeEventListener('beforeunload', preventFormClose);
            }

            // Function to show a warning when trying to leave the page
            function preventFormClose(event) {
                event.preventDefault();
                event.returnValue = ''; // Standard way to trigger a warning dialog
            }

            // Attach event listeners to form inputs to detect interaction
            if (welcomeForm) {
                const inputs = welcomeForm.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    input.addEventListener('input', () => {
                        formInteraction = true;
                        enableBeforeUnload(); // Enable the prompt after user interaction
                    });
                });

                // Form submission with AJAX
                welcomeForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    // Disable the beforeunload prompt before form submission
                    disableBeforeUnload();

                    const formData = new FormData(welcomeForm);

                    fetch('{{ route('student.profile.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Profile information updated successfully!',
                            }).then(() => {
                                modal.style.display = 'none';
                                location.reload(); // Reload the page after successful submission
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'There was an error updating your profile. Please try again.',
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An unexpected error occurred. Please try again later.',
                        });
                    });
                });
            }

            // Profile Picture Preview
            function previewImage(event) {
                const input = event.target; // Access the input element
                const reader = new FileReader();
                reader.onload = function() {
                    const profilePreview = document.getElementById('profile_preview');
                    profilePreview.src = reader.result;
                    profilePreview.style.display = 'block';
                }
                if (input.files && input.files[0]) {
                    reader.readAsDataURL(event.target.files[0]);
            }
        }
        document.getElementById('profile_picture').addEventListener('change', previewImage);

            // Show modal if $showModal is true
            if (modal) {
                if (@json($showModal)) {
                    modal.style.display = 'flex';
                }

                const closeModal = document.querySelector('.close');
                if (closeModal) {
                    closeModal.addEventListener('click', function() {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Incomplete Form',
                            text: 'You need to complete the form before proceeding.',
                        });
                    });
                }

                window.addEventListener('click', function(event) {
                    if (event.target === modal) {
                        event.preventDefault();
                    }
                });

                if (agreeDisclaimer && nextButton) {
                    agreeDisclaimer.addEventListener('change', function() {
                        nextButton.disabled = !this.checked;
                    });
                }
            }

            // Terms and conditions agreement before enabling the submit button
            if (agreeTerms && submitButton) {
                agreeTerms.addEventListener('change', function() {
                    submitButton.disabled = !this.checked;
                });
            }
            function validateLetters(input) {
        setTimeout(() => {
            input.value = input.value.replace(/[^A-Za-z\s]/g, '');  // Remove anything that is not a letter or space
        }, 1);
    }
    // Allow only numbers in the input
    function validateNumbers(input) {
        setTimeout(() => {
            input.value = input.value.replace(/[^0-9]/g, '');  // Remove anything that is not a digit
        }, 1);
    }
    var calendarEl = document.getElementById('calendar');
        if (calendarEl) {
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    @foreach($appointments as $appointment)
                    {
                        title: '{{ $appointment->name }} - {{ $appointment->appointment_type }}',
                        start: '{{ $appointment->appointment_date }}T{{ $appointment->appointment_time }}',
                        id: '{{ $appointment->id }}'
                    },
                    @endforeach
                ],
                eventClick: function(info) {
                    openModal(info.event.id);
                }
            });

            calendar.render();
        }

        function openModal(appointmentId) {
            console.log('Opening modal for appointment ID:', appointmentId);
        }
    });
        
    </script>
</x-app-layout>
