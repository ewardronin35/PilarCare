<x-guest-layout>
    <!-- Custom container to wrap the form and testimonial section -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            background-color: rgba(255, 255, 255, 0.9);
            width: 800px;
            height: 550px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            overflow: hidden;
            animation: fadeInContainer 1s ease-in-out;
        }

        .login-form-container {
            width: 50%;
            padding: 40px;
        }

        .center-signup {
            text-align: center;
            color: #171A1F;
            font-size: 34px;
            margin-bottom: 20px;
        }

        .login-form h3 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #424856;
        }

        .login-form label {
            color: #424856;
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
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

        .login-form input[type="email"],
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

        .login-form input[type="email"]:focus,
        .login-form input[type="password"]:focus {
            border-color: #1CE5FF;
            box-shadow: 0 0 8px rgba(28, 229, 255, 0.6);
        }

        .login-form .log-in-button {
            margin-top: 20px;
            margin-right: 20px;
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
            padding: 40px;
            background-color: #003FFF;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: slideIn 1s ease-in-out;
        }

        .logo {
            max-width: 100%;
            max-height: 100%;
            animation: bounceIn 1s ease-in-out;
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
    </style>
    <div class="container">
        <!-- Left Side (Login Form) -->
        <div class="login-form-container">
            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf
                <div class="center-signup">
                    <h2>Sign in</h2>
                </div>

                <!-- Email Address -->
                <div class="form-group mt-5">
                    <x-input-label for="email" :value="__('Email')" />
                    <div class="input-wrapper">
                        <i class="fa-regular fa-envelope"></i>
                        <x-text-input id="email" class="form-control"
                                      type="email" name="email" :value="old('email')"
                                      required autofocus autocomplete="username"
                                      placeholder="example.email@gmail.com" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="form-group mt-5">
                    <x-input-label for="password" :value="__('Password')" />
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <x-text-input id="password" class="form-control"
                                      type="password"
                                      name="password"
                                      required autocomplete="current-password"
                                      placeholder="Enter at least 8+ characters" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Forgot Password Link -->
                <div class="flex items-center justify-center mt-5">
                    @if (Route::has('password.request'))
                        <a class="forgot-password-link" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <!-- Log In Button -->
                <div class="button-container mt-5 mb-5">
                    <x-primary-button class="log-in-button">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>

                <div class="flex items-center justify-center mt-5">
                    <p>Don't have an account? <a href="{{ route('register') }}" class="signup-link">Sign up</a></p>
                </div>
            </form>
        </div>

        <!-- Right Side (Logo) -->
        <div class="right-side">
            <img src="{{ asset('images/pilarLogo.png') }}" alt="Pilar College of Zamboanga City Logo" class="logo">
        </div>
    </div>
</x-guest-layout>