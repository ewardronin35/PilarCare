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
            overflow: hidden;
        }

        .container {
            background-color: #fff;
            width: 900px;
            height: 85%;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            overflow: hidden;
            animation: fadeIn 1s ease-in-out;
        }

        .form-container {
            width: 60%;
            height: 90%;
            padding: 40px;
            overflow-y: auto; /* Allow vertical scrolling within form container */
        }

        .center-signup {
            text-align: center;
            color: #171A1F;
            font-size: 34px;
            margin-bottom: 20px;
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
            transition: background-color 0.3s;
        }

        .form-container .submit-button:hover {
            background-color: #17B0CC;
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
            overflow: hidden; /* Prevent scrolling on the logo side */
        }

        .logo {
            max-width: 100%;
            max-height: 100%;
        }

        .terms {
            margin-top: 20px;
            color: #141B34;
        }

        .terms a {
            color: #003FFF;
            text-decoration: underline;
            cursor: pointer;
        }

        .error-message {
            color: red;
            display: none;
            margin-top: 10px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
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

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            animation: fadeIn 0.5s;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 10px;
            animation: slideIn 0.5s;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>

    <div class="container">
        <!-- Left Side (Form) -->
        <div class="form-container">
            <div class="center-signup">
                <h2>Verify Email</h2>
            </div>
            <div class="mb-4 text-sm text-gray-600">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <div class="mt-4 flex items-center justify-between">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf

                    <div class="button-container">
                        <button type="submit" class="submit-button">
                            {{ __('Resend Verification Email') }}
                        </button>
                    </div>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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

    <script>
        function showModal() {
            document.getElementById('termsModal').style.display = "block";
        }

        function hideModal() {
            document.getElementById('termsModal').style.display = "none";
        }

        // Close the modal if the user clicks outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('termsModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</x-guest-layout>
