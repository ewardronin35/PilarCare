<x-app-layout>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f8fc;
        }

        .settings-container {
            max-width: 800px;
            margin: 40px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            animation: fadeInUp 0.8s ease-in-out;
        }

        .profile-section {
            flex: 1;
            text-align: center;
            padding-right: 30px;
            border-right: 1px solid #ddd;
            position: relative;
        }

        .profile-image-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

        .form-section {
            flex: 3;
            padding-left: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease-in-out;
        }

        .form-group input:focus, .form-group select:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
        }

        .form-group input[type="file"] {
            display: none;
        }

        .change-image-btn {
            display: block;
            margin-top: 20px;
            text-align: center;
            cursor: pointer;
            color: #007bff;
            transition: color 0.3s;
        }

        .change-image-btn:hover {
            color: #0056b3;
        }

        .form-group button {
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .form-group button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

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
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 500px;
            text-align: center;
        }

        #imageUpload {
            display: none;
        }   
    </style>

    <div class="settings-container">
        <!-- Profile Image Section -->
        <div class="profile-section">
            <div class="profile-image-container">
                <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : asset('images/default-profile.png') }}" id="profileImage" alt="Profile Image">
            </div>
            <label for="imageUpload" class="change-image-btn">Change Profile Image</label>
            <input type="file" id="imageUpload" name="profile_image" accept="image/*">
        </div>

        <!-- Form Section -->
        <div class="form-section">
            <h2>Account Settings</h2>
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
                @csrf
                @method('PUT')

                <!-- ID Number -->
                <div class="form-group">
                    <label for="id_number">ID Number</label>
                    <input type="text" id="id_number" name="id_number" value="{{ Auth::user()->id_number }}" readonly>
                </div>

                <!-- First Name -->
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="{{ Auth::user()->first_name }}" required>
                </div>

                <!-- Last Name -->
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="{{ Auth::user()->last_name }}" required>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required>
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
    </div>

    <!-- Modal -->
    <div class="modal-overlay" id="emailVerificationModal">
        <div class="modal-content">
            <h2>Email Verification Required</h2>
            <p>Please verify your new email address to complete the update.</p>
            <button id="closeModal" class="form-group button">Close</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Profile image preview
        document.getElementById('imageUpload').addEventListener('change', function() {
            const [file] = this.files;
            if (file) {
                document.getElementById('profileImage').src = URL.createObjectURL(file);
            }
        });

        // SweetAlert for form submission
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
