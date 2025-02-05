<!DOCTYPE html>
<html>
<head>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <title>Reset Your Password</title>
    <style>
        /* Clinic Theme Styles */
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            font-family: 'Roboto', sans-serif;
            color: #333333;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background-color: #00d1ff; /* Clinic's Primary Color */
            padding: 20px;
            text-align: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-right: 10px;
        }

        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 1px;
        }

        .content {
            padding: 30px;
            text-align: center;
        }

        .content h2 {
            margin-bottom: 20px;
            color: #333333;
        }

        .content p {
            margin-bottom: 20px;
            line-height: 1.6;
            color: #555555;
        }

        .button {
            background-color: #00d1ff; /* Clinic's Primary Color */
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
            display: inline-block;
        }

        .button:hover {
            background-color: #00b8e6; /* Darker Shade on Hover */
        }

        .footer {
            background-color: #f4f6f9;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #888888;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                margin: 20px;
            }

            .logo-text {
                font-size: 20px;
            }

            .button {
                padding: 10px 20px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header with Logo -->
        <div class="header">
            <div class="logo-container">
                <img src="{{ asset('images/pilarLogo.png') }}" alt="Pilar College of Zamboanga City Logo" class="logo">
                <div class="logo-text">PilarCare</div>
            </div>
        </div>

        <!-- Email Content -->
        <div class="content">
            <h2>Password Reset Request</h2>
            <p>Hello {{ $user->first_name }},</p>
            <p>We received a request to reset your password. Click the button below to reset it:</p>
            <p><a href="{{ $resetUrl }}" class="button">Reset Password</a></p>
            <p>If you did not request a password reset, please ignore this email or contact support if you have questions.</p>
        </div>

        <!-- Footer -->
        <!-- Footer -->
<div class="footer">
    &copy; {{ date('Y') }} PilarCare. All rights reserved.<br>
    <a href="{{ url('/') }}" style="color: #00d1ff; text-decoration: none;">Visit Our Website</a><br>
    <div style="margin-top: 10px;">
        <a href="https://facebook.com/yourclinic" style="margin: 0 5px;">
            <img src="{{ asset('images/facebook-icon.png') }}" alt="Facebook" width="24" height="24">
        </a>
        <a href="https://twitter.com/yourclinic" style="margin: 0 5px;">
            <img src="{{ asset('images/twitter-icon.png') }}" alt="Twitter" width="24" height="24">
        </a>
        <a href="https://instagram.com/yourclinic" style="margin: 0 5px;">
            <img src="{{ asset('images/instagram-icon.png') }}" alt="Instagram" width="24" height="24">
        </a>
    </div>
</div>

</body>
</html>
