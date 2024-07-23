<x-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
        }

        .container {
            background-color: #fff;
            width: 750px;
            height: 500px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            overflow: hidden;
            animation: fadeInContainer 1s ease-in-out;
        }

        .login-form-container {
            width: 50%;
            padding: 30px;
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

        .button-container {
            display: flex;
            justify-content: center;
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

        .right-side {
            width: 50%;
            height: 100%;
            padding: 30px;
            background-color: #003FFF;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            animation: slideIn 1s ease-in-out;
        }

        .logo {
            max-width: 100%;
            max-height: 100%;
            animation: bounceIn 1s ease-in-out;
        }

        .logo-text {
            margin-top: 20px;
            color: #fff;
            font-size: 24px;
            text-align: center;
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
            from {
                background-color: #0caac8;
            }
            to {
                background-color: #1CE5FF;
            }
        }

        @keyframes fadeInContainer {
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
                transform: translateX(100%);
            }
            to {
                transform: translateX(0);
            }
        }

        @keyframes bounceIn {
            from {
                transform: scale(0.5);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Ensure the Toastr container is always on top */
        #toast-container {
            z-index: 9999 !important;
        }

        .label {
            color: black;
        }
    </style>

    <div class="container">
        <div class="login-form-container">
            <form method="POST" action="{{ route('login') }}" class="login-form" id="login-form">
                @csrf
                <div class="center-signup">
                    <h2>Sign in</h2>
                </div>

                <div class="form-group mt-4">
                    <x-input-label for="id_number" :value="__('ID Number')" class="label"/>
                    <div class="input-wrapper">
                        <i class="fa-regular fa-id-card"></i>
                        <x-text-input id="id_number" class="form-control"
                                      type="text" name="id_number" :value="old('id_number')"
                                      required autofocus autocomplete="username"
                                      maxlength="7" pattern="[A-Za-z]{1}[0-9]{6}"
                                      placeholder="Enter your ID number" />
                    </div>
                    <x-input-error :messages="$errors->get('id_number')" class="mt-2" />
                </div>

                <div class="form-group mt-4">
                    <x-input-label for="password" :value="__('Password')" class="label"/>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <x-text-input id="password" class="form-control"
                                      type="password"
                                      name="password"
                                      required autocomplete="current-password"
                                      placeholder="Enter your password" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-center mt-4">
                    @if (Route::has('password.request'))
                        <a class="forgot-password-link" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <div class="button-container mt-4 mb-3">
                    <button type="submit" class="log-in-button">
                        {{ __('Log in') }}
                    </button>
                </div>

                <div class="flex items-center justify-center mt-2">
                    <p>Don't have an account? <a href="{{ route('register') }}" class="signup-link">Sign up</a></p>
                </div>
            </form>
        </div>

        <div class="right-side">
            <img src="{{ asset('images/pilarLogo.png') }}" alt="Pilar College of Zamboanga City Logo" class="logo">
            <div class="logo-text">PilarCare</div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('login-form').addEventListener('submit', function(event) {
                event.preventDefault();
                login();
            });
        });

        function login() {
            const form = document.getElementById('login-form');
            const formData = new FormData(form);

            fetch('{{ route('login') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return response.text().then(text => { throw new Error(text) });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    toastr.success('Login successful.', 'Success', {
                        closeButton: true,
                        progressBar: true,
                        positionClass: 'toast-top-right',
                        timeOut: 3000,
                        onHidden: function() {
                            window.location.href = data.redirect;
                        }
                    });
                } else {
                    const errors = Object.values(data.errors).flat().join('<br>');
                    toastr.error(errors, 'Error', {
                        closeButton: true,
                        progressBar: true,
                        positionClass: 'toast-top-right',
                        timeOut: 5000,
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('An unexpected error occurred. Please try again later.', 'Error', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    timeOut: 5000,
                });
            });
        }
    </script>
</x-guest-layout>
