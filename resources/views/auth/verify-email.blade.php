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

        .logout-button {
            text-align: center;
            margin-top: 20px;
        }

        .logout-button button {
            color: #171A1F;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            text-decoration: underline;
        }

        .logout-button button:hover {
            color: #003FFF;
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
                {{ __('Verify Email') }}
            </div>
            <div class="message">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <div class="button-container">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="submit-button">
                        {{ __('Resend Verification Email') }}
                    </button>
                </form>
            </div>

            <div class="logout-button">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">
                        {{ __('Log Out') }}
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
