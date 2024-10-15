<!-- resources/views/auth/verify-email.blade.php -->

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

        .container-custom {
            background-color: #fff;
            width: 450px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 40px;
            animation: fadeInContainer 1s ease-in-out;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
        }

        .logo {
            max-width: 60px;
            margin-right: 10px;
        }

        .logo-text {
            color: #003FFF;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
        }

        .center-title {
            text-align: center;
            color: #171A1F;
            font-size: 24px;
            margin-bottom: 20px;
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
            margin-bottom: 15px;
        }

        .submit-button {
            background-color: #1CE5FF;
            border: none;
            padding: 12px 25px;
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

        .logout-button {
            text-align: center;
            margin-top: 10px;
        }

        .logout-button button {
            color: #1CE5FF;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            text-decoration: underline;
        }

        .logout-button button:hover {
            color: #17B0CC;
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
        @media (max-width: 500px) {
            .container-custom {
                width: 90%;
                padding: 30px;
            }

            .logo-text {
                font-size: 22px;
            }

            .center-title {
                font-size: 20px;
            }

            .submit-button,
            .logout-button button {
                font-size: 14px;
                padding: 10px 20px;
            }
        }
    </style>

    <!-- Container -->
    <div class="container-custom">
        <!-- Logo and Branding -->
        <div class="logo-container">
            <img src="{{ asset('images/pilarLogo.png') }}" alt="Pilar College of Zamboanga City Logo" class="logo">
            <div class="logo-text">PilarCare</div>
        </div>

        <!-- Title -->
        <div class="center-title">
            {{ __('Verify Your Email Address') }}
        </div>

        <!-- Confirmation Message -->
        <div class="message">
            {{ __('Thank you for registering! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        <!-- Status Message -->
        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success text-center" role="alert">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <!-- Resend Verification Email Button -->
        <div class="button-container">
            <form method="POST" action="{{ route('verification.send') }}" style="width: 100%;">
                @csrf
                <button type="submit" class="submit-button">
                    {{ __('Resend Verification Email') }}
                </button>
            </form>
        </div>

        <!-- Logout Button -->
        <div class="logout-button">
    <form method="POST" action="{{ route('custom.logout') }}">
        @csrf
        <button type="submit">
            {{ __('Log Out') }}
        </button>
    </form>
</div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Optional: Include Bootstrap JS if needed -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // SweetAlert for Logout Confirmation
    document.getElementById('logoutBtn').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the default button action

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to log out?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, log me out!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logoutForm').submit(); // Submit the logout form
            }
        });
    });

    // Optional: Show success message after logout
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Logged Out',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif
</script>
</x-guest-layout>
