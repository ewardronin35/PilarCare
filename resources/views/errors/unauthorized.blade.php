<!-- resources/views/errors/unauthorized.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized Access</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f8d7da;
            color: #721c24;
        }

        .container {
            margin-top: 100px;
        }

        h1 {
            font-size: 3em;
        }

        p {
            font-size: 1.5em;
        }

        .back-link {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #f5c6cb;
            color: #721c24;
            border-radius: 5px;
            text-decoration: none;
        }

        .back-link:hover {
            background-color: #f1a8ac;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Unauthorized Access!</h1>
        <p>{{ $message }}</p>
        <p>You attempted to access: <strong>{{ $action }}</strong></p>
        <a href="{{ url('/') }}" class="back-link">Go Back to Home</a>
    </div>
</body>
</html>
