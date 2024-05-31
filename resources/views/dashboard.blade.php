<x-app-layout>
    <style>
        .container {
            display: flex;
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
        }

        .sidebar:hover {
            width: 250px; /* Expanded width */
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            transition: opacity 0.3s ease-in-out;
        }

        .logo-img {
            width: 40px;
            height: 40px;
            margin-right: 30px;
        }

        .logo-text {
            font-size: 1.25rem;
            font-weight: bold;
            opacity: 0;
        }

        .sidebar:hover .logo-text {
            opacity: 1;
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
            min-width: 20px;
            margin-right: 10px;
            text-align: center;
        }

        .menu-text {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .sidebar:hover .menu-text {
            opacity: 1;
        }

        .sidebar-footer ul {
            list-style: none;
            padding: 0;
        }

        .sidebar-footer li {
            display: flex;
            align-items: center;
            margin: 10px 0;
            padding: 10px 20px;
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .sidebar-footer li:hover {
            background-color: #009ec3;
            transform: translateX(10px);
        }

        .sidebar-footer li a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            display: flex;
            align-items: center;
        }

        .sidebar-footer li a .icon {
            min-width: 20px;
            margin-right: 10px;
            text-align: center;
        }

        .main-content {
            margin-left: 80px; /* Collapsed sidebar width */
            width: 100%;
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
            background-color: white;
            border-bottom: 1px solid #e0e0e0;
            position: relative;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info .username {
            margin-right: 10px;
            font-weight: bold;
        }

        .user-info .role {
            margin-right: 20px;
            color: gray;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .notification-icon {
            margin-right: 20px;
            position: relative;
        }

        .notification-dropdown {
            display: none;
            position: absolute;
            top: 50px;
            right: 10px;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .notification-dropdown.active {
            display: block;
        }

        .notification-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background-color: #f9f9f9;
            transform: translateX(10px);
        }

        .notification-item .icon {
            margin-right: 10px;
        }

        .notification-header {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
            font-weight: bold;
        }

        .h1-header {
            text-align: center;
            margin: 20px 0;
        }

        .image-container-dashboard {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 80px); /* Adjust based on header height */
        }

        .image-container-dashboard img {
            max-width: 100%;
            height: auto;
        }
    </style>

    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <img src="{{ asset('images/pilarLogo.png') }}" alt="PilarCare Logo" class="logo-img">
                <span class="logo-text hidden md:inline-block">PilarCare</span>
            </div>
            <nav class="menu">
                <ul>
                <li><a href="{{ route('dashboard') }}"><span class="icon"><i class="fas fa-tachometer-alt"></i></span><span class="menu-text">Dashboard</span></a></li>
                <li><a href="{{ route('complaint') }}"><span class="icon"><i class="fas fa-comments"></i></span><span class="menu-text">Complaint</span></a></li>
                    <li><a href="{{ route('medical-record') }}"><span class="icon"><i class="fas fa-notes-medical"></i></span><span class="menu-text">Records</span></a></li>
                    <li><a href="{{ route('appointment') }}"><span class="icon"><i class="fas fa-calendar-check"></i></span><span class="menu-text">Appointment</span></a></li>
                    <li><a href="{{ route('inventory') }}"><span class="icon"><i class="fas fa-boxes"></i></span><span class="menu-text">Inventory</span></a></li>
                    <li><a href="{{ route('monitoring-report-log') }}"><span class="icon"><i class="fas fa-chart-line"></i></span><span class="menu-text">Monitoring and Report</span></a></li>

                </ul>
            </nav>
            <div class="sidebar-footer">
                <ul>
                    <li><a href="#"><span class="icon"><i class="fas fa-cogs"></i></span><span class="menu-text">Settings</span></a></li>
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="icon"><i class="fas fa-sign-out-alt"></i></span><span class="menu-text">Logout</span></a></li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </ul>
            </div>
        </aside>

        <main class="main-content">
            <!-- Header -->
         

            <!-- Welcome message -->
            <h1 class="h1-header">Welcome, {{ Auth::user()->name }}!</h1>

            <!-- Image container -->
            <div class="image-container-dashboard">
                <img src="{{ asset('images/pilarLogo.png') }}" alt="Login Image">
            </div>
        </main>
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
</x-app-layout>
