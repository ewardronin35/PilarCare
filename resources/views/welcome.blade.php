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
        width: 150px; /* or any other size depending on your design */
        height: auto; /* maintain aspect ratio */
    }
</style>

</head>
<body class="flex items-center justify-center h-screen">
    <div class="flex w-full max-w-4xl shadow-lg">
        <!-- Logo and Welcome Message Side -->
        <div class="w-full bg-white p-10 flex flex-col justify-center items-center">
            <img src="{{ asset('images/pilarLogo.png') }}" alt="PilarCare Logo" class="logo-img mb-8 max-w-xs">
            <h1 class="text-3xl font-bold mb-4">Welcome to Pilar Care</h1>
            <p class="text-gray-700 mb-8">Dedicated to caring for you.</p>
            <a href="{{ route('login') }}" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition duration-300">
                Proceed to Sign In
            </a>
        </div>
    </div>
</body>
</html>
