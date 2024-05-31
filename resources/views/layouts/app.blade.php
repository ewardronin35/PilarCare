<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'PilarCare') }}</title>
    <link rel="icon" href="{{ asset('images/pilarLogo.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ url('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <style>
        .notification-icon {
            position: relative;
        }

        .notification-dropdown {
            display: none;
            position: absolute;
            top: 40px;
            right: 0;
            width: 300px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .notification-dropdown.active {
            display: block;
        }

        .notification-header {
            font-weight: bold;
            padding: 10px;
            background: #f9f9f9;
            border-bottom: 1px solid #eee;
        }

        .notification-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background: #f1f1f1;
        }

        .notification-item .icon {
            margin-right: 10px;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 flex">
        <x-sidebar></x-sidebar>

        <div class="flex-1">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                <header class="header bg-white shadow flex justify-between items-center px-6 py-4">
                    <div></div> <!-- This empty div will push user-info to the right -->
                    <div class="user-info flex items-center">
                        <span class="username mr-4">{{ Auth::user()->name }}</span>
                        <span class="role mr-4">Admin</span>
                        <div class="notification-icon relative">
                            <i class="fas fa-bell cursor-pointer" id="notification-icon"></i>
                            <div id="notification-dropdown" class="notification-dropdown">
                                <div class="notification-header">Notifications</div>
                                <div class="notification-item">
                                    <span class="icon"><i class="fas fa-calendar-alt"></i></span>
                                    <span>You have a new appointment.</span>
                                </div>
                                <div class="notification-item">
                                    <span class="icon"><i class="fas fa-box-open"></i></span>
                                    <span>Inventory needs restocking.</span>
                                </div>
                                <div class="notification-item">
                                    <span class="icon"><i class="fas fa-comment-dots"></i></span>
                                    <span>New complaint received.</span>
                                </div>
                            </div>
                        </div>
                        <img src="{{ asset('images/pilarLogo.png') }}" alt="User Avatar" class="user-avatar rounded-full w-10 h-10 ml-4">
                    </div>
                </header>

                <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <script>
        document.getElementById('notification-icon').addEventListener('click', function() {
            var dropdown = document.getElementById('notification-dropdown');
            dropdown.classList.toggle('active');
        });

        // Close the dropdown if clicked outside
        document.addEventListener('click', function(event) {
            var dropdown = document.getElementById('notification-dropdown');
            var icon = document.getElementById('notification-icon');
            if (!dropdown.contains(event.target) && !icon.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });
    </script>
</body>
</html>
