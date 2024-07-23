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
            width: 800px;
            height: 600px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            overflow: hidden;
            animation: fadeInContainer 1s ease-in-out;
        }

        .form-container {
            width: 50%;
            padding: 40px;
        }

        .center-title {
            text-align: center;
            color: #171A1F;
            font-size: 34px;
            margin-bottom: 20px;
        }

        .form h3 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #424856;
        }

        .form label {
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

        .form input[type="email"] {
            background-color: #F3F4F6;
            border: 1px solid #ccc;
            padding: 12px 20px 12px 35px;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            color: black;
            transition: all 0.3s ease-in-out;
        }

        .form input[type="email"]:focus {
            border-color: #1CE5FF;
            box-shadow: 0 0 8px rgba(28, 229, 255, 0.6);
        }

        .form .reset-password-button,
        .form .back-to-login-button {
            margin-top: 20px;
            background-color: #1CE5FF;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .form .reset-password-button:hover,
        .form .back-to-login-button:hover {
            background-color: #17B0CC;
            transform: scale(1.05);
        }

        .form .reset-password-button:active,
        .form .back-to-login-button:active {
            transform: scale(0.95);
        }

        .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .right-side {
            width: 50%;
            height: 100%;
            padding: 40px;
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
    </style>

    <div class="container">
        <div class="form-container">
            <form method="POST" action="{{ route('password.email') }}" class="form" id="password-reset-form">
                @csrf
                <div class="center-title">
                    <h2>Forgot Password</h2>
                </div>
                
                <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

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

                <div class="button-container mt-5 mb-5">
                    <button type="submit" class="reset-password-button">
                        {{ __('Email Password Reset Link') }}
                    </button>
                    <a href="{{ route('login') }}" class="back-to-login-button">
                        {{ __('Back to Login') }}
                    </a>
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
</x-guest-layout>
