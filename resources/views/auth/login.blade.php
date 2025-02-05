
<x-guest-layout>
    <!-- External Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Removed reCAPTCHA Script -->
    
    <!-- Internal Styles -->
    <style>
        /* Body Styling */
        body {
            background: url('{{ asset('images/bg.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Arial', sans-serif;
            animation: fadeInBackground 1s ease-in-out;
            overflow: hidden;
            position: relative; /* Necessary for overlay */
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Dark overlay with 50% opacity */
            z-index: 1; /* Behind the container but above the background */
        }
        /* Container Styling */
        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
            animation: fadeInContainer 1s ease-in-out;
            z-index: 2; /* Above the overlay */
            position: relative;
        }
        /* Logo Styling */
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .logo {
            max-width: 50px;
            margin-right: 10px;
        }

        .logo-text {
            color: #003FFF;
            font-size: 24px;
            font-weight: bold;
        }

        /* Title Styling */
        .center-signup {
            text-align: center;
            color: #171A1F;
            font-size: 28px;
            margin-bottom: 20px;
        }

        /* Input Wrapper Styling */
        .input-wrapper {
            position: relative;
            margin-bottom: 16px;
        }

        .input-wrapper i.fa-lock,
        .input-wrapper i.fa-id-card {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: black;
        }

        /* Password Toggle Icon Styling */
        .input-wrapper .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            cursor: pointer;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .input-wrapper .toggle-password:hover {
            color: #1CE5FF;
            transform: scale(1.2);
        }

        /* Input Fields Styling */
        .login-form input[type="text"],
        .login-form input[type="password"] {
            background-color: #F3F4F6;
            border: 1px solid #ccc;
            padding: 12px 20px 12px 35px;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            color: black;
            transition: all 0.3s ease-in-out;
        }

        .login-form input[type="text"]:focus,
        .login-form input[type="password"]:focus {
            border-color: #1CE5FF;
            box-shadow: 0 0 8px rgba(28, 229, 255, 0.6);
        }

        /* Login Button Styling */
        .login-form .log-in-button {
            margin-top: 20px;
            background-color: #1CE5FF;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            width: 100%;
            font-size: 16px;
        }

        .login-form .log-in-button:hover {
            background-color: #17B0CC;
            transform: scale(1.05);
        }

        .login-form .log-in-button:active {
            transform: scale(0.95);
        }

        /* Forgot Password Link Styling */
        .forgot-password-link {
            color: #00BDD6;
            text-decoration: none;
            cursor: pointer;
            display: block;
            margin-top: 10px;
            text-align: center;
            font-size: 14px;
        }

        .forgot-password-link:hover {
            color: #009EB2;
        }

        /* Signup Link Styling */
        .signup-link {
            color: #00BDD6;
            text-decoration: none;
            cursor: pointer;
            font-weight: bold;
        }

        .signup-link:hover {
            color: #009EB2;
        }

        /* Animations */
        @keyframes fadeInBackground {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInContainer {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Toastr Styling */
        #toast-container {
            z-index: 9999 !important;
        }

        /* Center the Toastr notification */
        #toast-container.toast-top-center {
            top: 20px;
            right: 0;
            left: 0;
            margin: auto;
        }

        /* Label Styling */
        .label {
            color: black;
        }

        /* Loading Overlay Styles */
        #loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.5); /* White with 50% transparency */
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        /* Spinner Styling - Light Blue Spinner */
        .spinner {
            border: 8px solid #f3f3f3; /* Light grey */
            border-radius: 50%;
            border-top: 8px solid #1CE5FF; /* Light blue */
            width: 60px;
            height: 60px;
            animation: spin 2s linear infinite;
        }

        /* Spinner Animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 500px) {
            .container {
                max-width: 90%;
                padding: 20px;
            }

            .logo-text {
                font-size: 20px;
            }

            .center-signup {
                font-size: 24px;
            }

            .login-form .log-in-button {
                font-size: 14px;
                padding: 10px 16px;
            }

            .forgot-password-link,
            .signup-link {
                font-size: 12px;
            }
        }
    </style>
    <!-- resources/views/auth/login.blade.php -->


    <div class="overlay"></div>

    <!-- Loading Overlay -->
    <div id="loading-overlay">
        <div class="spinner"></div>
    </div>

    <!-- Login Container -->
    <div class="container">
        <!-- Logo and Branding -->
        <div class="logo-container">
            <img src="{{ asset('images/pilarLogo.png') }}" alt="Pilar College of Zamboanga City Logo" class="logo">
            <div class="logo-text">PilarCare</div>
        </div>

        <!-- Title -->
        <div class="center-signup">
            <h2>Sign in</h2>
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login.perform') }}" class="login-form" id="login-form">
            
            @csrf

            <!-- ID Number Field -->

            <div class="mb-3 position-relative">
                <x-input-label for="id_number" :value="__('ID Number')" class="label"/>
                <div class="input-wrapper">
                    <i class="fa-regular fa-id-card"></i>
                    <x-text-input id="id_number" class="form-control ps-5"
                      type="text" name="id_number" :value="old('id_number')"
                      required autofocus autocomplete="username"
                      maxlength="8" pattern="[A-Za-z0-9]{1,8}"
                      placeholder="Enter your ID number" />
                </div>
                <x-input-error :messages="$errors->get('id_number')" class="mt-2" />
            </div>

            <!-- Password Field with Show/Hide Toggle -->
            <div class="mb-3 position-relative">
                <x-input-label for="password" :value="__('Password')" class="label"/>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <x-text-input id="password" class="form-control ps-5"
                                  type="password" name="password"
                                  required autocomplete="current-password"
                                  placeholder="Enter your password" />
                    <i class="fas fa-eye toggle-password" aria-label="Toggle password visibility"></i>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Forgot Password Link -->
            <div class="text-center mt-4">
                @if (Route::has('password.request'))
                    <a class="forgot-password-link" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <!-- Login Button -->
            <div class="d-grid mt-3">
                <button type="submit" class="btn btn-primary log-in-button">
                    {{ __('Sign in') }}
                </button>
            </div>

          
        </form>
    </div>

    <!-- External Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toastr Configuration and Session Status Handling -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadingOverlay = document.getElementById('loading-overlay');
            const togglePasswordElements = document.querySelectorAll('.toggle-password');
            const passwordInputs = document.querySelectorAll('input[type="password"]');

            // Handle form submission
            document.getElementById('login-form').addEventListener('submit', function(event) {
                event.preventDefault();
                if (validateForm()) {
                    showLoadingOverlay(); // Show the loading spinner
                    submitForm();
                }
            });

            // Display Toastr success message if session('status') exists
            @if(session('status'))
                toastr.success("{{ session('status') }}", "Success", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-center', // Center the Toastr notification
                    timeOut: 4000, // Increased timeout for better visibility
                });
            @endif

            // Show/Hide Password Toggle
            togglePasswordElements.forEach(function(togglePassword) {
                togglePassword.addEventListener('click', function() {
                    // Toggle the type attribute
                    const target = this.previousElementSibling; // Assuming the input is immediately before the toggle
                    if (target.type === 'password') {
                        target.type = 'text';
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-eye-slash');
                    } else {
                        target.type = 'password';
                        this.classList.remove('fa-eye-slash');
                        this.classList.add('fa-eye');
                    }
                });
            });
        });

        // Show Loading Overlay
        function showLoadingOverlay() {
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.style.display = 'flex';
            }
        }

        // Hide Loading Overlay
        function hideLoadingOverlay() {
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.style.display = 'none';
            }
        }

        // Validate Form Inputs
        function validateForm() {
            const idNumber = document.getElementById('id_number').value;
            const idNumberPattern = /^[A-Za-z0-9]{1,8}$/;
            const password = document.getElementById('password').value;

            if (!idNumberPattern.test(idNumber)) {
                toastr.error('The ID number may only contain letters and numbers and must be up to 8 characters long.', 'Error', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-center',
                    timeOut: 3000,
                });
                return false;
            }

            if (password.length < 8) {
                toastr.error('Password must be at least 8 characters long.', 'Error', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-center',
                    timeOut: 3000,
                });
                return false;
            }

            return true;
        }

        // Submit Form via AJAX
       // Submit Form via AJAX
async function submitForm() {
    const form = document.getElementById('login-form');
    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    console.log('FormData entries:', [...formData.entries()]);

    try {
        const response = await fetch(form.action, {
    method: 'POST',
    headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken, // Include CSRF token here

    },
    body: formData,
    credentials: 'include', // Changed from 'same-origin' to 'include'
});
        const status = response.status;

        // Try to parse JSON response
        let data;
        try {
            data = await response.json();
        } catch (e) {
            data = {};
        }

        // Update CSRF token if provided
        if (data.csrfToken) {
    document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrfToken);
    document.querySelector('input[name="_token"]').value = data.csrfToken;

        }

        hideLoadingOverlay(); // Hide the spinner once the response is received

        if (response.ok) {
            // HTTP status code 200-299
            if (data.success) {
                toastr.success('Login successful.', 'Success', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-center',
                    timeOut: 2000,
                    onHidden: function() {
                        window.location.href = data.redirect;
                    }
                });
            } else {
                const errors = data.errors ? Object.values(data.errors).flat().join('<br>') : 'An error occurred.';
                toastr.error(errors, 'Error', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-center',
                    timeOut: 4000,
                });
            }
        } else if (status === 422) {
            // Validation error
            const errors = data.errors ? Object.values(data.errors).flat().join('<br>') : 'Validation error.';
            toastr.error(errors, 'Error', {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-center',
                timeOut: 4000,
            });
        } else if (status === 419) {
            // CSRF token mismatch or session expired
            toastr.error('Session expired. Please refresh the page and try again.', 'Error', {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-center',
                timeOut: 4000,
            });
        } else {
            const message = data.message || 'An unexpected error occurred.';
            toastr.error(message, 'Error', {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-center',
                timeOut: 4000,
            });
        }
    } catch (error) {
        hideLoadingOverlay();
        console.error('Error:', error);
        toastr.error('An unexpected error occurred. Please try again later.', 'Error', {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-center',
            timeOut: 4000,
        });
    }
}

    </script>
</x-guest-layout>
