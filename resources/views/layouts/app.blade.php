<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'PilarCare') }}</title>
    <link rel="icon" href="{{ asset('images/pilarLogo.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">


    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<body class="font-sans antialiased">
    <div class="container">
        @if(Auth::check())
            @if(Auth::user()->role == 'Admin')
                <x-adminsidebar />
            @elseif(Auth::user()->role == 'Student')
                <x-studentsidebar />
            @elseif(Auth::user()->role == 'Parent')
                <x-parentsidebar />
            @elseif(Auth::user()->role == 'Teacher')
                <x-teachersidebar />
            @elseif(Auth::user()->role == 'Staff')
                <x-staffsidebar />
            @endif
        @endif

        <div class="main-content">
            <header class="header">
                <div class="breadcrumb">
                    <span>
                        {{ ucfirst(Auth::user()->role) }}
                    </span>
                    <span class="separator">/</span>
                    <span>{{ $pageTitle ?? ucfirst(str_replace('.', ' ', Route::currentRouteName())) }}</span>
                </div>
                <div class="fixed-user-info">
                    <div class="notification-icon" id="notification-icon">
                        <i class="fas fa-bell"></i>
                        <span class="notification-count">3</span> <!-- Example notification count -->
                        <div class="notification-dropdown" id="notification-dropdown">
                            <div class="dropdown-item">No new notifications</div>
                        </div>
                    </div>
                    <span class="username">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                    <div class="user-avatar" id="user-avatar">
                        <img src="{{ asset('images/pilarLogo.jpg') }}" alt="Profile Image">
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item"><i class="fas fa-cog"></i> Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" class="dropdown-item"
                               onclick="event.preventDefault(); showLogoutAlert();"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </form>
                    </div>
                </div>
            </header>

            <div class="content">
                {{ $slot }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Notification bell animation on page load
        document.addEventListener('DOMContentLoaded', function() {
            const bellIcon = document.querySelector('.notification-icon i');
            bellIcon.classList.add('ringing');
            
            setTimeout(() => {
                bellIcon.classList.remove('ringing');
            }, 2000); // 2-second animation duration

            // Toggle user avatar dropdown
            document.getElementById('user-avatar').addEventListener('click', function() {
                var dropdown = document.getElementById('dropdown-menu');
                dropdown.classList.toggle('active');
            });

            // Toggle notification dropdown
            document.getElementById('notification-icon').addEventListener('click', function() {
                var notificationDropdown = document.getElementById('notification-dropdown');
                notificationDropdown.classList.toggle('active');
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                var dropdown = document.getElementById('dropdown-menu');
                var notificationDropdown = document.getElementById('notification-dropdown');
                if (!dropdown.contains(event.target) && !event.target.closest('#user-avatar')) {
                    dropdown.classList.remove('active');
                }
                if (!notificationDropdown.contains(event.target) && !event.target.closest('#notification-icon')) {
                    notificationDropdown.classList.remove('active');
                }
            });
        });

        // Show logout confirmation alert
        function showLogoutAlert() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('form[method="POST"]').submit();
                }
            });
        }
    </script>
</body>
</html>
