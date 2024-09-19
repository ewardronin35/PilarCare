<x-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.nocaptcha.sitekey') }}"></script>

    <style>
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
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
            animation: fadeInContainer 1s ease-in-out;
        }

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

        .center-signup {
            text-align: center;
            color: #171A1F;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 16px;
        }

        .input-wrapper i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: black;
        }

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

        .login-form .log-in-button {
            margin-top: 20px;
            background-color: #1CE5FF;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .login-form .log-in-button:hover {
            background-color: #17B0CC;
            transform: scale(1.05);
        }

        .login-form .log-in-button:active {
            transform: scale(0.95);
        }

        .forgot-password-link {
            color: #00BDD6;
            text-decoration: none;
            cursor: pointer;
            display: block;
            margin-top: 10px;
            text-align: center;
        }

        .forgot-password-link:hover {
            color: #009EB2;
        }

        .signup-link {
            color: #00BDD6;
            text-decoration: none;
            cursor: pointer;
        }

        .signup-link:hover {
            color: #009EB2;
        }

        @keyframes fadeInBackground {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInContainer {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Ensure the Toastr container is always on top */
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

        .label {
            color: black;
        }

        /* Loading overlay styles */
        #loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 24px;
        }

        .spinner {
            border: 8px solid #f3f3f3;
            border-radius: 50%;
            border-top: 8px solid #3498db;
            width: 60px;
            height: 60px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <div id="loading-overlay">
        <div class="spinner"></div>
        <div>Loading...</div>
    </div>

    <div class="container">
        <div class="logo-container">
            <img src="{{ asset('images/pilarLogo.png') }}" alt="Pilar College of Zamboanga City Logo" class="logo">
            <div class="logo-text">PilarCare</div>
        </div>
        <div class="center-signup">
            <h2>Sign in</h2>
        </div>
        <form method="POST" action="{{ route('login.perform') }}" class="login-form" id="login-form">
            @csrf
            <div class="mb-3 position-relative">
                <x-input-label for="id_number" :value="__('ID Number')" class="label"/>
                <div class="input-wrapper">
                    <i class="fa-regular fa-id-card"></i>
                    <x-text-input id="id_number" class="form-control ps-5"
                                  type="text" name="id_number" :value="old('id_number')"
                                  required autofocus autocomplete="username"
                                  maxlength="7" pattern="[A-Za-z]{1}[0-9]{6}"
                                  placeholder="Enter your ID number" />
                </div>
                <x-input-error :messages="$errors->get('id_number')" class="mt-2" />
            </div>

            <div class="mb-3 position-relative">
                <x-input-label for="password" :value="__('Password')" class="label"/>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <x-text-input id="password" class="form-control ps-5"
                                  type="password"
                                  name="password"
                                  required autocomplete="current-password"
                                  placeholder="Enter your password" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- reCAPTCHA v3 token -->
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

            <div class="text-center mt-4">
                @if (Route::has('password.request'))
                    <a class="forgot-password-link" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div class="d-grid mt-3">
                <button type="submit" class="btn btn-primary log-in-button">
                    {{ __('Log in') }}
                </button>
            </div>

            <div class="text-center mt-2">
                <p>Don't have an account? <a href="{{ route('register') }}" class="signup-link">Sign up</a></p>
            </div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadingOverlay = document.getElementById('loading-overlay');

            document.getElementById('login-form').addEventListener('submit', function(event) {
                event.preventDefault();
                if (loadingOverlay) showLoadingOverlay();
                generateRecaptchaTokenAndLogin();
            });
        });

        function showLoadingOverlay() {
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.style.display = 'flex';
            }
        }

        function hideLoadingOverlay() {
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.style.display = 'none';
            }
        }

        function generateRecaptchaTokenAndLogin() {
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config('services.nocaptcha.sitekey') }}', { action: 'login' }).then(function(token) {
                    document.getElementById('g-recaptcha-response').value = token;
                    refreshTokenAndLogin();
                });
            });
        }

        function refreshTokenAndLogin() {
            fetch('/refresh-csrf', {
                method: 'GET',
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                document.querySelector('input[name="_token"]').value = data.csrf_token;
                login();
            })
            .catch(error => {
                hideLoadingOverlay();
                console.error('Error:', error);
                toastr.error('An unexpected error occurred while refreshing the CSRF token. Please try again later.', 'Error', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-center', // Center the Toastr notification
                    timeOut: 2000,
                });
            });
        }

        function login() {
            const form = document.getElementById('login-form');
            const formData = new FormData(form);

            fetch('{{ route('login.perform') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => {
                if (response.status === 419) {
                    throw new Error('Session expired. Please refresh the page and try again.');
                }
                return response.json();
            })
            .then(data => {
                hideLoadingOverlay();
                if (data.success) {
                    toastr.success('Login successful.', 'Success', {
                        closeButton: true,
                        progressBar: true,
                        positionClass: 'toast-top-center', // Center the Toastr notification
                        timeOut: 2000,
                        onHidden: function() {
                            showLoadingOverlay();
                            window.location.href = data.redirect;
                        }
                    });
                } else {
                    const errors = Object.values(data.errors).flat().join('<br>');
                    toastr.error(errors, 'Error', {
                        closeButton: true,
                        progressBar: true,
                        positionClass: 'toast-top-center', // Center the Toastr notification
                        timeOut: 2000,
                    });
                }
            })
            .catch(error => {
                hideLoadingOverlay();
                console.error('Error:', error);
                toastr.error(error.message || 'An unexpected error occurred. Please try again later.', 'Error', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-center', // Center the Toastr notification
                    timeOut: 2000,
                });
            });
        }
    </script>
</x-guest-layout>
