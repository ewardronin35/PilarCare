<x-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
            width: 850px;
            height: auto;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            overflow: hidden;
            animation: fadeIn 1s ease-in-out;
        }

        .form-container {
            width: 60%;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .center-signup {
            text-align: center;
            color: #003FFF;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-container .message {
            font-size: 16px;
            color: #141B34;
            text-align: center;
            margin-bottom: 30px;
        }

        .button-container {
            display: flex;
            justify-content: center;
        }

        .form-container .submit-button {
            margin-top: 20px;
            background-color: #1CE5FF;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 18px;
            width: 100%;
        }

        .form-container .submit-button:hover {
            background-color: #17B0CC;
        }

        .right-side {
            width: 40%;
            background-color: #003FFF;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .right-side .logo {
            max-width: 100%;
            max-height: 100%;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>

    <div class="container">
        <!-- Left Side (Form) -->
        <div class="form-container">
            <div class="center-signup">
                {{ __('Email Verified') }}
            </div>
            <div class="message">
                {{ __('Your email address has been successfully verified. You can now log in to your account.') }}
            </div>
            <div class="button-container">
                <form method="GET" action="{{ route('login') }}">
                    <button type="submit" class="submit-button">
                        {{ __('Log In') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Side (Logo) -->
        <div class="right-side">
            <img src="{{ asset('images/pilarLogo.png') }}" alt="Pilar College of Zamboanga City Logo" class="logo">
        </div>
    </div>
</x-guest-layout>
