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
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- Scripts -->
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
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <span class="separator">/</span>
                    <span>{{ $pageTitle ?? '' }}</span>
                </div>
                <div class="user-info">
                    <span class="username">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                    <span class="role">{{ Auth::user()->role }}</span>
                    <div class="user-avatar" id="user-avatar">
                        <img src="{{ asset('images/pilarLogo.jpg') }}" alt="Profile Image">
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" class="dropdown-item"
                                onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
                        </form>
                    </div>
                </div>
            </header>

            <div class="content">
                {{ $slot }}
            </div>
        </div>
    </div>

    <script>
        document.getElementById('user-avatar').addEventListener('click', function() {
            var dropdown = document.getElementById('dropdown-menu');
            dropdown.classList.toggle('active');
        });

        document.addEventListener('click', function(event) {
            var dropdown = document.getElementById('dropdown-menu');
            if (!dropdown.contains(event.target) && !event.target.closest('#user-avatar')) {
                dropdown.classList.remove('active');
            }
        });
    </script>
</body>
</html>
