<!-- resources/views/auth/email-verified.blade.php -->

<x-guest-layout>
    <!-- External Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Internal Styles -->
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
            width: 350px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 30px;
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
            text-align: center;
        }

        .center-title {
            text-align: center;
            color: #171A1F;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .message {
            font-size: 16px;
            color: #141B34;
            text-align: center;
            margin-bottom: 30px;
        }

        .button-container {
            display: flex;
            justify-content: center;
        }

        .submit-button {
            background-color: #1CE5FF;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            font-size: 16px;
            width: 100%;
        }

        .submit-button:hover {
            background-color: #17B0CC;
            transform: scale(1.02);
        }

        .submit-button:active {
            transform: scale(0.98);
        }

        .back-to-login-button {
            margin-top: 15px;
            background-color: #fff;
            border: 2px solid #1CE5FF;
            padding: 8px 18px;
            border-radius: 5px;
            color: #1CE5FF;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease, transform 0.3s ease;
            font-size: 14px;
            text-align: center;
            text-decoration: none; /* Ensure it's styled as a button */
            display: inline-block;
            width: 100%;
        }

        .back-to-login-button:hover {
            background-color: #1CE5FF;
            color: #fff;
            transform: scale(1.02);
        }

        .back-to-login-button:active {
            transform: scale(0.98);
        }

        @keyframes fadeInBackground {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInContainer {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive Design */
        @media (max-width: 400px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            .logo-text {
                font-size: 20px;
            }

            .center-title {
                font-size: 20px;
            }

            .submit-button,
            .back-to-login-button {
                font-size: 14px;
                padding: 8px 16px;
            }
        }
    </style>

    <!-- Container -->
    <div class="container">
        <!-- Logo and Branding -->
        <div class="logo-container">
            <img src="{{ asset('images/pilarLogo.png') }}" alt="Pilar College of Zamboanga City Logo" class="logo">
            <div class="logo-text">PilarCare</div>
        </div>

        <!-- Title -->
        <div class="center-title">
            {{ __('Email Verified') }}
        </div>

        <!-- Confirmation Message -->
        <div class="message">
            {{ __('Your email address has been successfully verified. You can now log in to your account.') }}
        </div>

        <!-- Buttons -->
        <div class="button-container">
            <form method="GET" action="{{ route('login') }}" style="width: 100%;">
                <button type="submit" class="submit-button">
                    {{ __('Log In') }}
                </button>
            </form>
        </div>

        <div class="button-container">
            <a href="{{ route('login') }}" class="back-to-login-button">
                {{ __('Back to Login') }}
            </a>
        </div>
    </div>
</x-guest-layout>
