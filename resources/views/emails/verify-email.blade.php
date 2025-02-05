<!DOCTYPE html>
<html>
<head>
    <title>Verify Your Email Address</title>
    <style>
        body {
            font-family: 'Arial, sans-serif';
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .content p {
            margin-bottom: 15px;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .footer {
            background-color: #f1f1f1;
            color: #666;
            text-align: center;
            padding: 10px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Verify Your Email Address</h1>
        </div>
        <div class="content">
            <p>Hello, {{ $notifiable->name }}!</p>
            <p>
                Thank you for registering with <strong>{{ config('app.name') }}</strong>.
                Please confirm your email address to activate your account.
            </p>
            <p>
                Click the button below to verify your email address:
            </p>
            <p style="text-align: center;">
                <a href="{{ $url }}" class="btn" target="_blank">Verify Email Address</a>
            </p>
            <p>
                If you did not create this account, you can safely ignore this email.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
