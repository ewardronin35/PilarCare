<!-- resources/views/auth/reset-password.blade.php -->

<x-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            overflow: hidden;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 350px;
            width: 100%;
            animation: fadeInContainer 1s ease-in-out;
        }

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

        .center-title {
            text-align: center;
            color: #171A1F;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 12px;
        }

        .input-wrapper i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: black;
        }

        .form input[type="email"],
        .form input[type="password"] {
            background-color: #F3F4F6;
            border: 1px solid #ccc;
            padding: 8px 15px 8px 35px;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            color: black;
            transition: all 0.3s ease-in-out;
            font-size: 14px;
        }

        .form input[type="email"]:focus,
        .form input[type="password"]:focus {
            border-color: #1CE5FF;
            box-shadow: 0 0 8px rgba(28, 229, 255, 0.6);
        }

        .form .reset-password-button {
            margin-top: 15px;
            background-color: #1CE5FF;
            border: none;
            padding: 10px 18px;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 14px;
        }

        .form .reset-password-button:hover {
            background-color: #17B0CC;
            transform: scale(1.05);
        }

        .form .reset-password-button:active {
            transform: scale(0.95);
        }

        .form .back-to-login-button {
            margin-top: 15px;
            background-color: #fff;
            border: none;
            padding: 10px 18px;
            color: #1CE5FF;
            cursor: pointer;
            transition: color 0.3s, transform 0.3s;
            font-size: 14px;
            text-align: center;
            text-decoration: none; /* Ensure it's styled as a button */
            display: inline-block;
        }

        .form .back-to-login-button:hover {
            color: #17B0CC;
            transform: scale(1.05);
        }

        .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        @keyframes fadeInBackground {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInContainer {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="container">
        <div class="logo-container">
            <img src="{{ asset('images/pilarLogo.png') }}" alt="Pilar College of Zamboanga City Logo" class="logo">
            <div class="logo-text">PilarCare</div>
        </div>
        <div class="center-title">
            <h2>Reset Password</h2>
        </div>

        <!-- Include Flash Messages -->
        <x-flash-message />

        <form method="POST" action="{{ route('password.update') }}" class="form" id="password-reset-form">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email Address -->
            <div class="input-wrapper">
                <i class="fa-regular fa-envelope"></i>
                <input id="email" class="form-control"
                       type="email" name="email" value="{{ old('email', $email) }}"
                       required autofocus autocomplete="username"
                       placeholder="Enter your email" />
            </div>
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <!-- Password -->
            <div class="input-wrapper">
                <i class="fa-solid fa-lock"></i>
                <input id="password" class="form-control"
                       type="password" name="password"
                       required autocomplete="new-password"
                       placeholder="Enter new password" />
            </div>
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <!-- Confirm Password -->
            <div class="input-wrapper">
                <i class="fa-solid fa-lock"></i>
                <input id="password_confirmation" class="form-control"
                       type="password" name="password_confirmation"
                       required autocomplete="new-password"
                       placeholder="Confirm new password" />
            </div>
            @error('password_confirmation')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <div class="button-container mt-4">
                <button type="submit" class="reset-password-button">
                    {{ __('Reset Password') }}
                </button>

                <a href="{{ route('login') }}" class="back-to-login-button">
                    {{ __('Back to Login') }}
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
