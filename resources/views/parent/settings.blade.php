<x-app-layout :pageTitle="'Profile Management'">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Global Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f8fc;
            margin: 0;
            padding: 0;
        }
        
        .settings-container {
            max-width: 1000px;
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
            flex: 1 1 48%;
            padding: 20px;
        
            margin-bottom: 20px; /* Add spacing between form containers */
            box-sizing: border-box;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .form-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        }
        
        /* Profile Image Section */
        .profile-section {
            width: 100%;
            text-align: center;
            margin-bottom: 30px; /* Increased spacing */
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
            margin-top: 15px; /* Increased spacing */
            text-align: center;
            cursor: pointer;
            color: #007bff;
            transition: color 0.3s;
            font-size: 16px;
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
            box-sizing: border-box;
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
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px; /* Increased font size */
            transition: background-color 0.3s, transform 0.3s;
        }
        
        .form-group button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        
        /* Error Message Styling */
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
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
        
        /* Additional Styles for File Name Display */
        .file-name {
            display: block;
            margin-top: 10px;
            font-size: 14px;
            color: #555;
        }
    /* Hide the actual file input */
.profile-section input[type="file"] {
    display: none;
}
.password-strength {
    height: 5px;
    width: 100%;
    background-color: #ddd;
    border-radius: 3px;
    margin-top: 5px;
}

.password-strength.strength-weak {
    background-color: red;
    width: 33%;
}

.password-strength.strength-medium {
    background-color: orange;
    width: 66%;
}

.password-strength.strength-strong {
    background-color: green;
    width: 100%;
}

    </style>

    
    <div class="settings-container">
        <!-- Left Form: Account Settings -->
        <div class="form-container">
            <h2>Account Settings</h2>
            <form action="{{ route('parent.settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
                @csrf
                @method('PUT')
                @if ($errors->any())
    <div class="error-message">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                <!-- Profile Image Section -->
        <!-- Profile Image Section -->
<div class="profile-section">
    <div class="profile-image-container">
        <img src="{{ $information && $information->profile_picture ? asset('storage/' . $information->profile_picture) : asset('images/default-profile.jpg') }}" alt="Profile Image" id="profileImage">
    </div>
    <label for="imageUpload" class="change-image-btn">Change Profile Picture</label>
    <input type="file" name="profile_picture" id="imageUpload" accept="image/*" onchange="previewProfileImage(event)">
    <span id="fileName" class="file-name"></span>
    @error('profile_picture')
        <div class="error-message">{{ $message }}</div>
    @enderror
</div>

                
                <!-- ID Number -->
                <div class="form-group">
                    <label for="id_number">ID Number</label>
                    <input type="text" id="id_number" name="id_number" value="{{ $user->id_number }}" readonly>
                </div>

                <!-- First Name -->
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                    @error('first_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Last Name -->
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                    @error('last_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
    <label for="password">New Password (leave blank to keep current)</label>
    <input type="password" id="password" name="password" minlength="8" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" title="Password must be at least 8 characters long and contain both letters and numbers.">
    <div id="passwordStrength" class="password-strength"></div>
    @error('password')
        <div class="error-message">{{ $message }}</div>
    @enderror
    <small>Password must be at least 8 characters long and contain both letters and numbers.</small>
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
      
    </div>
    <div id="emailVerificationModal" class="custom-modal">
        <div class="custom-modal-content">
            <h2>Email Verification Required</h2>
            <p>Please verify your new email address to continue using your account.</p>
            <button id="verifyNowBtn">Verify Email</button>
        </div>
    </div>
    <!-- SweetAlert2 Library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Profile picture preview and file name display
        function previewProfileImage(event) {
            const [file] = event.target.files;
            if (file) {
                document.getElementById('profileImage').src = URL.createObjectURL(file);
                document.getElementById('fileName').textContent = file.name;
            } else {
                document.getElementById('fileName').textContent = '';
            }
        }

        // SweetAlert for account settings form submission
        document.getElementById('saveChangesBtn').addEventListener('click', function(e) {
            e.preventDefault();  // Prevent default form submission

            const originalEmail = "{{ $user->email }}";
            const newEmail = document.getElementById('email').value.trim();

            let emailChanged = originalEmail !== newEmail;

            if (emailChanged) {
                // Show confirmation modal for email change
                Swal.fire({
                    title: 'Confirm Email Change',
                    html: `
                        <p>Are you sure you want to change your email address?</p>
                        <p><strong>Old Email:</strong> ${originalEmail}</p>
                        <p><strong>New Email:</strong> ${newEmail}</p>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, change it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed to show the main confirmation modal
                        showMainConfirmation();
                    }
                });
            } else {
                // No email change, proceed to main confirmation
                showMainConfirmation();
            }
        });

        // Function to show the main confirmation modal
        function showMainConfirmation() {
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
                    // Submit the form
                    document.getElementById('settingsForm').submit();
                }
            });
        }
// Password Strength Indicator
const passwordInput = document.getElementById('password');
const passwordStrength = document.getElementById('passwordStrength');

passwordInput.addEventListener('input', function() {
    const value = passwordInput.value;
    let strength = 0;

    // Check for letters
    if (/[A-Za-z]/.test(value)) strength += 1;

    // Check for numbers
    if (/\d/.test(value)) strength += 1;

    // Check for minimum length
    if (value.length >= 8) strength += 1;

    // Update strength indicator
    if (strength === 0) {
        passwordStrength.className = 'password-strength';
    } else if (strength === 1) {
        passwordStrength.classList.remove('strength-medium', 'strength-strong');
        passwordStrength.classList.add('strength-weak');
    } else if (strength === 2) {
        passwordStrength.classList.remove('strength-weak', 'strength-strong');
        passwordStrength.classList.add('strength-medium');
    } else if (strength === 3) {
        passwordStrength.classList.remove('strength-weak', 'strength-medium');
        passwordStrength.classList.add('strength-strong');
    }
});

        // SweetAlert for additional information form submission
    
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
                    // Create a form to submit the DELETE request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('parent.settings.delete') }}";

                    // CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = "{{ csrf_token() }}";
                    form.appendChild(csrfInput);

                    // Method spoofing
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });

        // Show SweetAlert if profile updated successfully
        function openEmailVerificationModal() {
            const modal = document.getElementById('emailVerificationModal');
            modal.style.display = 'block';
            document.body.classList.add('modal-open'); // Prevent background scrolling
        }

        // Function to close the custom modal
        function closeEmailVerificationModal() {
            const modal = document.getElementById('emailVerificationModal');
            modal.style.display = 'none';
            document.body.classList.remove('modal-open'); // Restore background scrolling
        }

        // Handle the "Verify Email" button click in the modal
        document.getElementById('verifyNowBtn').addEventListener('click', function() {
            // Redirect to the verification notice page
            window.location.href = "{{ route('verification.notice') }}";
        });

        // Show custom modal if email verification is required
        @if(session('email_verification_required'))
            openEmailVerificationModal();
        @endif

        // Show SweetAlert if profile updated successfully and no email verification is required
        @if(session('success') && !session('email_verification_required'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        @endif
    </script>
</x-app-layout>  