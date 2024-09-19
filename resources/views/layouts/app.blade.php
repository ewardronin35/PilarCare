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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.9.0/main.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.9.0/main.min.css" rel="stylesheet" />
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
<script src="{{ mix('js/app.js') }}"></script>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<body class="font-sans antialiased">
<div class="container">
        @if(Auth::check())
            @switch(Auth::user()->role)
                @case('Admin')
                    <x-adminsidebar />
                    @break
                @case('Student')
                    <x-studentsidebar />
                    @break
                @case('Parent')
                    <x-parentsidebar />
                    @break
                @case('Teacher')
                    <x-teachersidebar />
                    @break
                @case('Staff')
                    <x-staffsidebar />
                    @break
                @case('Nurse')
                    <x-nursesidebar />
                    @break
                @case('Doctor')
                    <x-doctorsidebar />
                    @break
            @endswitch
        @endif

        <div class="main-content">
            <header class="header">
                <div class="breadcrumb">
                    <span>{{ ucfirst(Auth::user()->role) }}</span>
                    <span class="separator">/</span>
                    <span>{{ $pageTitle ?? ucfirst(str_replace('.', ' ', Route::currentRouteName())) }}</span>
                </div>
                <div class="fixed-user-info">
                    <div class="notification-icon" id="notification-icon">
                        <i class="fas fa-bell"></i>
                        <div class="notification-dot" style="display: none;"></div> <!-- Will be shown if there are unread notifications -->
                        <div class="notification-dropdown" id="notification-dropdown">
                            <div class="dropdown-item">No new notifications</div>
                        </div>
                    </div>
                  
                    <span class="username">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                    @php
    $profilePicturePath = Auth::user()->information->profile_picture ?? 'images/pilarLogo.jpg';
@endphp

<div class="user-avatar" id="user-avatar">
    <img src="{{ asset('storage/' . $profilePicturePath) }}" alt="Profile Image">
</div>
                    <div class="dropdown-menu" id="logoutDropdown">
                       
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" class="dropdown-item"
                               onclick="event.preventDefault(); showLogoutAlert();">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
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
      document.addEventListener('DOMContentLoaded', function() {
    const bellIcon = document.querySelector('.notification-icon i');
    const notificationDot = document.querySelector('.notification-dot');
    const notificationDropdown = document.getElementById('notification-dropdown');

    // Check if the elements exist before adding event listeners or manipulating them
    if (!notificationDot) {
        console.error('Error: notificationDot element is not defined in the DOM.');
    }

    if (!notificationDropdown) {
        console.error('Error: notificationDropdown element is not defined in the DOM.');
    }

    if (!bellIcon) {
        console.error('Error: bellIcon element is not defined in the DOM.');
    }

    if (bellIcon && notificationDot && notificationDropdown) {
        // Bell icon animation on click
        document.getElementById('notification-icon').addEventListener('click', function() {
            bellIcon.classList.add('ringing');
            notificationDot.classList.add('blowing');
            notificationDropdown.classList.toggle('active');

            fetchNotifications();

            setTimeout(() => {
                bellIcon.classList.remove('ringing');
                notificationDot.classList.remove('blowing');
            }, 1000); // 1-second animation duration
        });
    }

    // Toggle user avatar dropdown
    document.getElementById('user-avatar')?.addEventListener('click', function() {
        var dropdown = document.getElementById('logoutDropdown');
        dropdown?.classList.toggle('active');
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        var dropdown = document.getElementById('logoutDropdown');
        var notificationDropdown = document.getElementById('notification-dropdown');
        if (!dropdown.contains(event.target) && !event.target.closest('#user-avatar')) {
            dropdown?.classList.remove('active');
        }
        if (!notificationDropdown.contains(event.target) && !event.target.closest('#notification-icon')) {
            notificationDropdown?.classList.remove('active');
        }
    });
});

function fetchNotifications() {
    fetch('{{ route('notifications.index') }}')
        .then(response => response.json())
        .then(data => {
            const notificationDropdown = document.getElementById('notification-dropdown');
            const notificationDot = document.querySelector('.notification-dot');

            if (!notificationDropdown || !notificationDot) {
                console.error('Error: notificationDropdown or notificationDot is not defined.');
                return;
            }

            notificationDropdown.innerHTML = ''; // Clear previous notifications

            if (data.notifications.length > 0) {
                data.notifications.forEach(notification => {
                    const notificationItem = document.createElement('div');
                    notificationItem.classList.add('dropdown-item');
                    notificationItem.textContent = `${notification.title}: ${notification.message}`;

                    // Mark notification as opened when clicked
                    notificationItem.addEventListener('click', () => {
                        markAsOpened(notification.id);
                        notificationItem.classList.add('opened');
                    });

                    notificationDropdown.appendChild(notificationItem);
                });

                // Show unread count
                if (data.unreadCount > 0) {
                    notificationDot.style.display = 'block';
                } else {
                    notificationDot.style.display = 'none';
                }
            } else {
                notificationDropdown.innerHTML = '<div class="dropdown-item">No new notifications</div>';
                notificationDot.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
            const notificationDropdown = document.getElementById('notification-dropdown');
            notificationDropdown.innerHTML = '<div class="dropdown-item">Failed to load notifications</div>';
        });
}

function markAsOpened(notificationId) {
    fetch(`/notifications/${notificationId}/mark-as-opened`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        console.log(data.message); // Handle success message if needed
    })
    .catch(error => console.error('Error marking notification as opened:', error));
}

// Optionally, refresh notifications count in real-time
setInterval(fetchNotifications, 30000); // Fetch notifications every 30 seconds

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
