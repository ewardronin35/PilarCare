<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilar Care</title>
    <link rel="icon" href="{{ asset('images/pilarLogo.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .logo-img {
            width: 150px;
            height: auto;
        }

        .welcome-container {
            background: linear-gradient(to right, rgba(29, 78, 216, 0.9), rgba(37, 99, 235, 0.9));
            border-radius: 15px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .welcome-container h1 {
            color: #fff;
            font-size: 36px;
            font-weight: bold;
        }

        .welcome-container p {
            color: #e5e7eb;
            font-size: 18px;
        }

        .welcome-button {
            background-color: #1d4ed8;
            color: #fff;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .welcome-button:hover {
            background-color: #2563eb;
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen bg-blue-100">
    <div class="welcome-container">
        <img src="{{ asset('images/pilarLogo.png') }}" alt="PilarCare Logo" class="logo-img mb-8 max-w-xs">
        <h1 class="text-3xl font-bold mb-4">Welcome to Pilar Care</h1>
        <p class="text-gray-200 mb-8">Dedicated to caring for you.</p>
        <a href="{{ route('login') }}" class="welcome-button">
            Proceed to Sign In
        </a>
    </div>
</body>
</html>
