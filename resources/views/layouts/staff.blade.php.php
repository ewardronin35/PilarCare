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
        body {
            margin: 0;
            padding: 0;
            font-family: 'Nunito', sans-serif;
        }

        .container {
            display: flex;
            min-height: 100vh;
            flex-direction: row;
        }

        .sidebar {
            width: 80px; /* Collapsed width */
            background-color: #00d2ff;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            transition: width 0.3s ease-in-out;
            overflow: hidden;
            z-index: 1000;
        }

        .sidebar:hover {
            width: 250px; /* Expanded width */
        }

        .main-content {
            margin-left: 80px; /* Collapsed sidebar width */
            flex-grow: 1;
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
        }

        .sidebar:hover ~ .main-content {
            margin-left: 250px; /* Expanded sidebar width */
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
            position: fixed;
            top: 0;
            z-index: 999;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info .username {
            margin-right: 10px;
        }

        .user-info .role {
            margin-right: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .notification-icon {
            position: relative;
        }

        .notification-icon i {
            transition: transform 0.3s;
        }

        .notification-icon.active i {
            transform: rotate(45deg);
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

        .menu ul {
            list-style: none;
            padding: 0;
        }

        .menu li {
            display: flex;
            align-items: center;
            margin: 10px 0;
            padding: 10px 20px;
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .menu li:hover {
            background-color: #009ec3;
            transform: translateX(10px);
        }

        .menu li a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            display: flex;
            align-items: center;
        }

        .menu li a .icon {
            min-width: 30px;
            margin-right: 20px;
            text-align: center;
        }

        .menu-text {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .sidebar:hover .menu-text {
            opacity: 1;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="container">
        <!-- Sidebar inclusion based on user role -->
        @if(Auth::check())
            @if(Auth::user()->role == 'Admin')
                <x-adminsidebar />
            @elseif(Auth::user()->role == 'Parent')
                <x-parentsidebar />
            @elseif(Auth::user()->role == 'Student')
                <x-studentsidebar />
            @elseif(Auth::user()->role == 'Teacher')
                <x-teachersidebar />
            @elseif(Auth::user()->role == 'Staff')
                <x-staffsidebar />
            @endif
        @endif

        <div class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="user-info">
                    <span class="username">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                    <span class="role">{{ Auth::user()->role }}</span>
                    <img src="{{ asset('images/pilarLogo.png') }}" alt="User Avatar" class="user-avatar">
                </div>
                <div class="notification-icon relative" id="notification-icon">
                    <i class="fas fa-bell cursor-pointer"></i>
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
            </header>

            <!-- Page Content -->
            <div style="margin-top: 80px;"> <!-- Adjusted margin-top to account for header height -->
                {{ $slot }}
            </div>
        </div>
    </div>

    <script>
        document.getElementById('notification-icon').addEventListener('click', function() {
            var dropdown = document.getElementById('notification-dropdown');
            dropdown.classList.toggle('active');
            this.classList.toggle('active');
        });

        // Close the dropdown if clicked outside
        document.addEventListener('click', function(event) {
            var dropdown = document.getElementById('notification-dropdown');
            var icon = document.getElementById('notification-icon');
            if (!dropdown.contains(event.target) && !icon.contains(event.target)) {
                dropdown.classList.remove('active');
                icon.classList.remove('active');
            }
        });
    </script>
</body>
</html>
