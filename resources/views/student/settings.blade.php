<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Global Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f8fc;
        }

        .settings-container {
            max-width: 1200px;
            margin: 40px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            animation: fadeInUp 0.8s ease-in-out;
        }

        /* Form Containers */
        .form-container {
            flex: 1 1 45%;
            padding: 20px;
            box-sizing: border-box;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .form-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        }

        /* Profile Image Section */
        .profile-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-image-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
        }

        .profile-image-container:hover img {
            transform: scale(1.1);
        }

        .change-image-btn {
            display: block;
            margin-top: 10px;
            text-align: center;
            cursor: pointer;
            color: #007bff;
            transition: color 0.3s;
            font-size: 14px;
        }

        .change-image-btn:hover {
            color: #0056b3;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease-in-out;
        }

        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
        }

        .form-group input[type="file"] {
            display: none;
        }

        .form-group button {
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .form-group button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        /* Modal Styling */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            display: none;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease-in-out;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 500px;
            text-align: center;
            animation: slideIn 0.3s ease-in-out;
        }

        #imageUpload {
            display: none;
        }

        /* Animations */
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

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .settings-container {
                flex-direction: column;
            }

            .form-container {
                flex: 1 1 100%;
            }
        }
    </style>

    <div class="settings-container">
        <!-- Left Form: Account Settings -->
        <div class="form-container">
            <h2>Account Settings</h2>
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
                @csrf
                @method('PUT')

                <!-- ID Number -->
                <div class="form-group">
                    <label for="id_number">ID Number</label>
                    <input type="text" id="id_number" name="id_number" value="{{ $user->id_number }}" readonly>
                </div>

                <!-- First Name -->
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="{{ $user->first_name }}" required>
                </div>

                <!-- Last Name -->
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="{{ $user->last_name }}" required>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ $user->email }}" required>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">New Password (leave blank to keep current)</label>
                    <input type="password" id="password" name="password">
                </div>

                <!-- Save Changes Button -->
                <div class="form-group">
                    <button type="submit" id="saveChangesBtn">Save Changes</button>
                </div>
            </form>

            <!-- Delete Account Button -->
            <div class="form-group">
                <button class="form-group button" id="deleteAccountBtn">Delete Account</button>
            </div>
        </div>

        <!-- Right Form: Additional Information -->
        <div class="form-container">
            <h2>Additional Information</h2>
            <form action="{{ route('admin.settings.updateAdditional') }}" method="POST" enctype="multipart/form-data" id="additionalInfoForm">
                @csrf
                @method('PUT')

                <!-- Parent Names -->
                <div class="form-group">
                    <label for="parent_name_father">Father's Name</label>
                    <input type="text" id="parent_name_father" name="parent_name_father" value="{{ $user->parent_name_father }}" placeholder="Enter father's name">
                </div>

                <div class="form-group">
                    <label for="parent_name_mother">Mother's Name</label>
                    <input type="text" id="parent_name_mother" name="parent_name_mother" value="{{ $user->parent_name_mother }}" placeholder="Enter mother's name">
                </div>

                <!-- Guardian Information -->
                <div class="form-group">
                    <label for="guardian_name">Guardian's Name</label>
                    <input type="text" id="guardian_name" name="guardian_name" value="{{ $user->guardian_name }}" placeholder="Enter guardian's name">
                </div>

                <div class="form-group">
                    <label for="guardian_relationship">Guardian's Relationship</label>
                    <input type="text" id="guardian_relationship" name="guardian_relationship" value="{{ $user->guardian_relationship }}" placeholder="Enter relationship with guardian">
                </div>

                <!-- Emergency Contact -->
                <div class="form-group">
                    <label for="emergency_contact_number">Emergency Contact Number</label>
                    <input type="text" id="emergency_contact_number" name="emergency_contact_number" value="{{ $user->emergency_contact_number }}" placeholder="Enter emergency contact number">
                </div>

                <div class="form-group">
                    <label for="personal_contact_number">Personal Contact Number</label>
                    <input type="text" id="personal_contact_number" name="personal_contact_number" value="{{ $user->personal_contact_number }}" placeholder="Enter personal contact number">
                </div>

                <!-- Address -->
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" rows="3" required>{{ $user->address }}</textarea>
                </div>

                <!-- Birthdate -->
                <div class="form-group">
                    <label for="birthdate">Birthdate</label>
                    <input type="date" id="birthdate" name="birthdate" value="{{ $user->birthdate }}" required>
                </div>

                <!-- Save Additional Info Button -->
                <div class="form-group">
                    <button type="submit" id="saveAdditionalInfoBtn">Save Additional Information</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Profile Image Upload Modal (Optional) -->
    <div class="modal-overlay" id="imageUploadModal">
        <div class="modal-content">
            <h2>Change Profile Image</h2>
            <form action="{{ route('admin.settings.updateImage') }}" method="POST" enctype="multipart/form-data" id="imageUploadForm">
                @csrf
                @method('PUT')
                <input type="file" name="profile_image" id="modalImageUpload" accept="image/*" required>
                <div class="form-group">
                    <button type="submit">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 Library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Profile image preview
        document.getElementById('imageUpload').addEventListener('change', function() {
            const [file] = this.files;
            if (file) {
                document.getElementById('profileImage').src = URL.createObjectURL(file);
            }
        });

        // SweetAlert for account settings form submission
        document.getElementById('saveChangesBtn').addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Confirm Changes',
                text: 'Are you sure you want to save these changes?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('settingsForm').submit();
                }
            });
        });

        // SweetAlert for additional information form submission
        document.getElementById('saveAdditionalInfoBtn').addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Confirm Changes',
                text: 'Are you sure you want to save the additional information?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('additionalInfoForm').submit();
                }
            });
        });

        // SweetAlert for profile picture update
        document.getElementById('imageUpload').addEventListener('change', function() {
            Swal.fire({
                icon: 'success',
                title: 'Profile picture updated successfully',
                showConfirmButton: false,
                timer: 1500
            });
        });

        // SweetAlert for account deletion
        document.getElementById('deleteAccountBtn').addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Delete Account',
                text: 'Are you sure you want to delete your account? This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('admin.settings.delete') }}";
                }
            });
        });

        // Modal for email verification
        function showEmailVerificationModal() {
            document.getElementById('emailVerificationModal').style.display = 'flex';
        }

        document.getElementById('closeModal').addEventListener('click', function() {
            Swal.fire({
                title: 'Email verification required',
                text: 'You must verify your email before proceeding.',
                icon: 'info',
                confirmButtonText: 'OK'
            });
        });

        @if(session('email_verification_required'))
            showEmailVerificationModal();
        @endif
    </script>
</x-app-layout>
