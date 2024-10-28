<x-guest-layout>
    <!-- External Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.nocaptcha.sitekey') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    
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
            margin-bottom: 15px;
        }

        .logo {
            max-width: 40px;
            margin-right: 8px;
        }

        .logo-text {
            color: #003FFF;
            font-size: 22px;
            text-align: center;
        }

        /* Title Styling */
        .center-signup {
            text-align: center;
            color: #171A1F;
            font-size: 24px;
            margin-bottom: 15px;
        }

        /* Input Wrapper Styling */
        .input-wrapper {
            position: relative;
            margin-bottom: 12px;
        }

        .input-wrapper i.input-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: black;
            pointer-events: none; /* Allow clicks to pass through */
        }

        /* Show/Hide Password Icon Styling */
        .input-wrapper .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            cursor: pointer;
            transition: color 0.3s ease, transform 0.3s ease;
            z-index: 2; /* Ensure it's above the input field */
        }

        .input-wrapper .toggle-password:hover {
            color: #1CE5FF;
            transform: translateY(-50%) scale(1.2);
        }

        /* Input Fields Styling */
        .login-form input[type="text"],
        .login-form input[type="password"],
        .login-form input[type="email"] {
            background-color: #F3F4F6;
            border: 1px solid #ccc;
            padding: 8px 35px 8px 35px; /* Adjusted padding for icons */
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            color: black;
            transition: all 0.3s ease-in-out;
            font-size: 14px;
        }

        .login-form input[type="text"]:focus,
        .login-form input[type="password"]:focus,
        .login-form input[type="email"]:focus {
            border-color: #1CE5FF;
            box-shadow: 0 0 8px rgba(28, 229, 255, 0.6);
            outline: none;
        }

        /* Login Button Styling */
        .login-form .log-in-button {
            margin-top: 15px;
            background-color: #1CE5FF;
            border: none;
            padding: 10px 18px;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 14px;
            width: 100%;
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
            font-size: 12px;
        }

        .forgot-password-link:hover {
            color: #009EB2;
        }

        /* Signup Link Styling */
        .signup-link {
            color: #00BDD6;
            text-decoration: none;
            cursor: pointer;
        }

        .signup-link:hover {
            color: #009EB2;
        }

        /* Form Row Styling */
        .form-row {
            display: flex;
            justify-content: space-between;
        }

        .form-col {
            width: 48%;
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

        /* Spinner Overlay Styling */
        #spinner-overlay {
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
    </style>
    <div class="overlay"></div>

    <!-- Spinner Overlay -->
    <div id="spinner-overlay">
        <div class="spinner"></div>
    </div>

    <!-- Registration Container -->
    <div class="container">
        <!-- Logo and Branding -->
        <div class="logo-container">
            <img src="{{ asset('images/pilarLogo.png') }}" alt="Pilar College of Zamboanga City Logo" class="logo">
            <div class="logo-text">PilarCare</div>
        </div>
        <div class="center-signup">
            <h2>Sign up</h2>
        </div>
        <form method="POST" action="{{ route('register') }}" class="login-form" id="registration-form">
            @csrf
            <!-- ID Number Field -->
            <div class="mb-2 position-relative">
                <x-input-label for="id_number" :value="__('ID Number')" class="label"/>
                <div class="input-wrapper">
                    <i class="fa-regular fa-id-card input-icon"></i>
                    <x-text-input id="id_number" class="form-control ps-5"
                                  type="text" name="id_number" :value="old('id_number')"
                                  required autofocus autocomplete="username"
                                  maxlength="7" pattern="[A-Za-z]{1}[0-9]{6}"
                                  placeholder="Enter your ID number" />
                </div>
                <x-input-error :messages="$errors->get('id_number')" class="mt-1" />
            </div>

            <!-- First and Last Name Fields -->
            <div class="form-row mb-2">
                <div class="form-col position-relative">
                    <x-input-label for="first_name" :value="__('First Name')" class="label"/>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <x-text-input id="first_name" class="form-control ps-5"
                                      type="text" name="first_name" :value="old('first_name')"
                                      required autocomplete="first_name"
                                      placeholder="Enter your first name" />
                    </div>
                    <x-input-error :messages="$errors->get('first_name')" class="mt-1" />
                </div>

                <div class="form-col position-relative">
                    <x-input-label for="last_name" :value="__('Last Name')" class="label"/>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <x-text-input id="last_name" class="form-control ps-5"
                                      type="text" name="last_name" :value="old('last_name')"
                                      required autocomplete="last_name"
                                      placeholder="Enter your last name" />
                    </div>
                    <x-input-error :messages="$errors->get('last_name')" class="mt-1" />
                </div>
            </div>

            <!-- Email Field -->
            <div class="mb-2 position-relative">
                <x-input-label for="email" :value="__('Email')" class="label"/>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <x-text-input id="email" class="form-control ps-5"
                                  type="email" name="email" :value="old('email')"
                                  required autocomplete="email"
                                  placeholder="Example: user@gmail.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password Field with Show/Hide Toggle -->
            <div class="mb-2 position-relative">
                <x-input-label for="password" :value="__('Password')" class="label"/>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <x-text-input id="password" class="form-control ps-5"
                                  type="password" name="password"
                                  required autocomplete="new-password"
                                  placeholder="Enter your password" />
                    <i class="fas fa-eye toggle-password" data-target="password" aria-label="Toggle Password Visibility"></i>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Confirm Password Field with Show/Hide Toggle -->
            <div class="mb-2 position-relative">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="label"/>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <x-text-input id="password_confirmation" class="form-control ps-5"
                                  type="password" name="password_confirmation"
                                  required autocomplete="new-password"
                                  placeholder="Confirm your password" />
                    <i class="fas fa-eye toggle-password" data-target="password_confirmation" aria-label="Toggle Confirm Password Visibility"></i>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            <!-- Terms and Conditions Checkbox -->
            <div class="form-check mb-3">
                <input type="checkbox" id="agreeCheckbox" name="agree_terms" class="form-check-input" required>
                <label for="agreeCheckbox" class="form-check-label">
                    I agree to the <a href="#" onclick="openTermsModal()" style="color: #003FFF;">terms and conditions</a>
                </label>
            </div>

            <!-- Signup Button -->
            <div class="d-grid mt-2">
                <button type="submit" class="btn btn-primary log-in-button">
                    {{ __('Sign up') }}
                </button>
            </div>

            <!-- Signup Link -->
            <div class="text-center mt-2">
                <p>Already have an account? <a href="{{ route('login') }}" class="signup-link">Sign In</a></p>
            </div>

            <!-- reCAPTCHA v3 Token -->
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
        </form>
    </div>

    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="termsModalLabel">Data Privacy Act of 2012</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <h4>Overview</h4>
            <p>The Data Privacy Act of 2012, also known as Republic Act No. 10173, is a law in the Philippines that seeks to protect individual personal data in information and communications systems in the government and the private sector.</p>
            
            <h4>Data Subject Rights</h4>
            <p>The act grants individuals certain rights regarding their personal data, including the right to be informed, the right to access, the right to rectify, and the right to erase personal data.</p>

            <h4>Consent</h4>
            <p>Before collecting and processing personal data, organizations must obtain explicit consent from individuals. This consent must be freely given, specific, and informed.</p>

            <h4>Penalties</h4>
            <p>Violations of the Data Privacy Act may result in fines, imprisonment, or both, depending on the severity of the offense. Penalties may be applied to both data controllers and processors.</p>

            <h4>Other Important Information</h4>
            <p>For more detailed information about the law and your rights, you may visit the National Privacy Commission's website or read the full text of the Data Privacy Act of 2012.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- JavaScript for Spinner, Show Password, and Form Handling -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const spinnerOverlay = document.getElementById('spinner-overlay');
            const togglePasswordIcons = document.querySelectorAll('.toggle-password');

            // Show Spinner Overlay
            function showSpinner() {
                spinnerOverlay.style.display = 'flex';
            }

            // Hide Spinner Overlay
            function hideSpinner() {
                spinnerOverlay.style.display = 'none';
            }

            // Handle Form Submission
            document.getElementById('registration-form').addEventListener('submit', function(event) {
                event.preventDefault();
                showSpinner(); // Show the loading spinner
                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ config('services.nocaptcha.sitekey') }}', { action: 'submit' }).then(function(token) {
                        document.getElementById('g-recaptcha-response').value = token;
                        if (validateForm()) {
                            submitForm();
                        } else {
                            hideSpinner(); // Hide the spinner if validation fails
                        }
                    });
                });
            });

            // Validate Form Inputs
            function validateForm() {
                const idNumber = document.getElementById('id_number').value;
                const idNumberPattern = /^[A-Za-z]{1}[0-9]{6}$/;
                const password = document.getElementById('password').value;
                const passwordConfirmation = document.getElementById('password_confirmation').value;
                const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;

                if (!idNumberPattern.test(idNumber)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'ID number must start with a letter followed by 6 numbers.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    return false;
                }

                if (password !== passwordConfirmation) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Passwords do not match.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    return false;
                }

                if (!passwordRegex.test(password)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Password must be at least 8 characters long and contain both letters and numbers.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    return false;
                }

                if (!document.getElementById('agreeCheckbox').checked) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'You must agree to the terms and conditions to proceed.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    return false;
                }

                return true;
            }

            // Submit Form via AJAX
            async function submitForm() {
                const form = document.getElementById('registration-form');
                const formData = new FormData(form);

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });

                    const data = await response.json();
                    hideSpinner(); // Hide the spinner once the response is received

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                        }).then(() => {
                            window.location.href = '{{ route('dashboard') }}';
                        });
                    } else {
                        const errors = data.errors;
                        if (errors) {
                            for (const [field, messages] of Object.entries(errors)) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    html: messages.join('<br>'),
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Registration failed. Please check your inputs.',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            });
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    hideSpinner(); // Hide the spinner if an error occurs
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred. Please try again later.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                }
            }

            // Show/Hide Password Toggle Functionality
            togglePasswordIcons.forEach(function(icon) {
                icon.addEventListener('click', function() {
                    const target = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(target);
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        this.classList.remove('fa-eye-slash');
                        this.classList.add('fa-eye');
                    }
                });
            });

            // Function to Open Terms Modal
            window.openTermsModal = function() {
                var modal = new bootstrap.Modal(document.getElementById('termsModal'), {});
                modal.show();
            }
        });
    </script>
</x-guest-layout>
