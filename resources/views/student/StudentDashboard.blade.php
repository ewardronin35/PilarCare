<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
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
            width: 100%;
            max-width: 800px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            animation: fadeInUp 0.5s ease-in-out;
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

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
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
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
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
            width: 48%;
        }

        .row .form-group label {
            display: flex;
            align-items: center;
        }

        .row .form-group label i {
            margin-right: 10px;
        }

        .optional-sibling {
            display: none;
        }

        .toggle-sibling {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            text-align: center;
            text-decoration: none;
        }

        .toggle-sibling:hover {
            background-color: #0056b3;
        }

        .next-button {
            background-color: #28a745;
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
            justify-content: center;
            gap: 10px;
        }

        .next-button:hover {
            background-color: #218838;
        }

        .submit-button {
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
            justify-content: center;
            gap: 10px;
        }

        .submit-button:hover {
            background-color: #0056b3;
        }

        .disclaimer {
            margin-bottom: 20px;
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

        .arrow {
            display: inline-block;
            transition: transform 0.3s;
        }

        .arrow:hover {
            transform: translateX(5px);
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

        <div class="data-table-wrapper no-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        <th>Name</th>
                        <th>Appointment Type</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $appointment->name }}</td>
                        <td>{{ $appointment->appointment_type }}</td>
                        <td>{{ $appointment->appointment_date }}</td>
                        <td>{{ $appointment->appointment_time }}</td>
                        <td>
                            <button onclick="openModal({{ $appointment->id }})">Cancel / Reschedule</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- The Modal -->
    <div id="welcomeModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="disclaimer">
                <h3>Data Privacy Disclaimer</h3>
                <p>
                    In compliance with the Data Privacy Act of 2012, all gathered data from the participant will be treated with utmost confidentiality to protect the participant’s/respondent’s privacy.
                </p>
                <p>
                    In accordance with the Medical Act of 1959 (Republic Act No. 2382), all medical records managed through the clinic system will be kept confidential to protect patient privacy.
                </p>
                <p>
                    In line with the Dental Act of 1998 (Republic Act No. 8978), all dental records handled through the clinic system will be treated with strict confidentiality to ensure patient privacy.
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
                <div class="row">
                    <div class="form-group">
                        <label for="parent_name_father"><i class="fas fa-user"></i> Father's Name</label>
                        <input type="text" id="parent_name_father" name="parent_name_father" required>
                    </div>
                    <div class="form-group">
                        <label for="parent_name_mother"><i class="fas fa-user"></i> Mother's Name</label>
                        <input type="text" id="parent_name_mother" name="parent_name_mother" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="profile_picture"><i class="fas fa-camera"></i> Profile Picture</label>
                        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label for="birthdate"><i class="fas fa-calendar-alt"></i> Your Birthdate</label>
                        <input type="date" id="birthdate" name="birthdate" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="emergency_contact_number"><i class="fas fa-phone-alt"></i> Emergency Contact Number</label>
                        <input type="text" id="emergency_contact_number" name="emergency_contact_number" required>
                    </div>
                    <div class="form-group">
                        <label for="personal_contact_number"><i class="fas fa-phone"></i> Personal Contact Number</label>
                        <input type="text" id="personal_contact_number" name="personal_contact_number" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="address"><i class="fas fa-home"></i> Address</label>
                    <input type="text" id="address" name="address" required>
                </div>
                <div class="form-group">
                    <label for="medical_history"><i class="fas fa-notes-medical"></i> Medical History</label>
                    <textarea id="medical_history" name="medical_history" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="allergies"><i class="fas fa-allergies"></i> Allergies</label>
                    <textarea id="allergies" name="allergies" rows="2" required></textarea>
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
                <div class="form-group">
                    <label for="surgical_history"><i class="fas fa-scalpel"></i> Surgical History</label>
                    <textarea id="surgical_history" name="surgical_history" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="chronic_conditions"><i class="fas fa-heartbeat"></i> Chronic Conditions</label>
                    <textarea id="chronic_conditions" name="chronic_conditions" rows="3" required></textarea>
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
        const agreeDisclaimer = document.getElementById("agree_disclaimer");
        const agreeTerms = document.getElementById("agree_terms");
        const csrfToken = document.querySelector('meta[name="csrf-token"]');

        if (!csrfToken) {
            console.error('CSRF token not found in meta tags');
            return;
        }

        if (modal) {
            // Display modal if required
            if (@json($showModal)) {
                modal.style.display = 'flex';

                // Show countdown timer before allowing next button click
                let countdown = 10;
                const timerInterval = setInterval(() => {
                    Swal.fire({
                        title: `Please wait ${countdown} seconds`,
                        text: "You can proceed once the countdown is complete.",
                        icon: 'info',
                        timer: countdown * 1000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        willClose: () => {
                            clearInterval(timerInterval);
                            nextButton.disabled = false;
                        }
                    });
                    countdown--;
                }, 1000);
            }

            // Close modal
            const closeModal = document.querySelector('.close');
            if (closeModal) {
                closeModal.addEventListener('click', function() {
                    modal.style.display = 'none';
                });
            }

            // Disable closing modal by clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    event.preventDefault();
                }
            });

            if (agreeDisclaimer && nextButton) {
                // Enable next button after disclaimer is agreed
                agreeDisclaimer.addEventListener('change', function() {
                    nextButton.disabled = !this.checked;
                });

                // Show form after clicking next
                nextButton.addEventListener('click', function() {
                    document.querySelector('.disclaimer').style.display = 'none';
                    welcomeForm.style.display = 'block';

                    // Animate the form coming into view
                    welcomeForm.style.opacity = 0;
                    welcomeForm.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        welcomeForm.style.transition = 'opacity 0.5s, transform 0.5s';
                        welcomeForm.style.opacity = 1;
                        welcomeForm.style.transform = 'translateY(0)';
                    }, 100);
                });
            }
        }

        if (agreeTerms && submitButton) {
            // Disable submit button and enable after delay when terms are agreed
            agreeTerms.addEventListener('change', function() {
                if (this.checked) {
                    submitButton.disabled = true;
                    setTimeout(() => {
                        submitButton.disabled = false;
                    }, 1000); // 1 second delay
                } else {
                    submitButton.disabled = true;
                }
            });
        }

        if (welcomeForm) {
            // Form submission with AJAX
            welcomeForm.addEventListener('submit', function(event) {
                event.preventDefault();
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
    });
</script>
</x-app-layout>