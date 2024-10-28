<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- ... existing head content ... -->
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'PilarCare') }}</title>
    <link rel="icon" href="{{ asset('images/pilarLogo.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- FullCalendar -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.9.0/main.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.9.0/main.min.css" rel="stylesheet" />
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Toastr CSS (if using CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Scripts -->
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Include Laravel's compiled JS -->
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
                <span>{{ $pageTitle ?? 'Default Title' }}</span>
                </div>
                <div class="fixed-user-info">
                    <div class="notification-icon" id="notification-icon" style="position: relative;">
                        <i class="fas fa-bell"></i>
                        <span class="badge" style="display: none; position: absolute; top: 0; right: 0; background-color: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px;">0</span>
                        <div class="notification-dot" style="display: none;"></div>

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
                    <div class="logout-dropdown-menu" id="logoutDropdown">
                        <form id="logout-form" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="#" class="dropdown-item" id="logout-button">
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

    <!-- Spinner Overlay -->
    <div id="spinner-overlay" role="alert" aria-live="assertive" aria-label="Loading">
        <div class="spinner" aria-hidden="true"></div>
    </div>

    <!-- Toastr JS (if using CDN) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Existing scripts... -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   
    <script src="{{ mix('js/app.js') }}"></script>
    <script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toastr options
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000"
            };

            // Notification Icon Elements
            const notificationIcon = document.getElementById('notification-icon');
            const bellIcon = notificationIcon?.querySelector('i');
            const notificationDot = document.querySelector('.notification-dot');
            const notificationDropdown = document.getElementById('notification-dropdown');


            // Overlay Elements

    

            // Logout Dropdown Elements
            const userAvatar = document.getElementById('user-avatar');
            const logoutDropdown = document.getElementById('logoutDropdown');
            const logoutButton = document.getElementById('logout-button');

            // Spinner Elements
            const spinnerOverlay = document.getElementById('spinner-overlay');

            // Function to Show Spinner
            function showSpinner() {
                spinnerOverlay.style.display = 'flex';
            }

            // Function to Hide Spinner
            function hideSpinner() {
                spinnerOverlay.style.display = 'none';
            }

            // Show spinner on sidebar link click, excluding submenu toggles
            const sidebarLinks = document.querySelectorAll('.sidebar a'); // Ensure sidebar links have the <a> tag within .sidebar
            sidebarLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    const href = link.getAttribute('href');
                    // Exclude links that are toggles, such as those with href="#" or javascript:void(0)
                    if (href && href !== '#' && !href.startsWith('javascript:') && !link.classList.contains('no-spinner')) {
                        showSpinner();
                    }
                });
            });

            // Show spinner on form submissions in sidebar (if any)
            const sidebarForms = document.querySelectorAll('.sidebar form');
            sidebarForms.forEach(function(form) {
                form.addEventListener('submit', function() {
                    showSpinner();
                });
            });

            // Hide spinner once the page has fully loaded
            window.addEventListener('load', function() {
                hideSpinner();
            });

            // Initial fetch of notifications
            fetchNotifications();

            // Set Interval to fetch notifications every 2 seconds
            setInterval(fetchNotifications, 5000); // 2000 milliseconds = 2 seconds
            let hasMarkedAsRead = false;

            // Notification Icon click
            if (bellIcon && notificationDot && notificationDropdown) {
    notificationIcon.addEventListener('click', debounce(function (e) {
        e.stopPropagation(); // Prevent event from bubbling up

        // Add bell ringing animation
        bellIcon.classList.add('ringing');
        notificationDot.classList.add('blowing');

        // Toggle dropdown with smooth animation
        notificationDropdown.classList.toggle('active');

        // Fetch notifications to ensure the latest are displayed
        fetchNotifications()
            .then(unreadCount => {
                if (unreadCount > 0) {
                    // Only mark as read if there are unread notifications
                    markAllAsRead();
                }
            })
            .catch(error => {
                console.error('Error in fetchNotifications:', error);
            });

        // Remove bell ringing and notification dot animations after 1 second
        setTimeout(() => {
            bellIcon.classList.remove('ringing');
            notificationDot.classList.remove('blowing');
        }, 1000); // 1-second animation duration
    }, 300)); // 300ms debounce delay
}

            if (userAvatar && logoutDropdown) {
                // Toggle Logout Dropdown on user avatar click
                userAvatar.addEventListener('click', function (e) {
                    e.stopPropagation(); // Prevent event from bubbling up
                    logoutDropdown.classList.toggle('active');
                });
            }

            // Handle Logout Button Click
            if (logoutButton) {
                logoutButton.addEventListener('click', function (e) {
                    e.preventDefault(); // Prevent the default link behavior

                    // Use SweetAlert to show a confirmation dialog
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You will be logged out of the application.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, logout!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Perform AJAX logout if confirmed
                            performLogout();
                        }
                    });
                });
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                // Close Logout Dropdown if clicked outside
                if (logoutDropdown.classList.contains('active') && !logoutDropdown.contains(event.target) && !userAvatar.contains(event.target)) {
                    logoutDropdown.classList.remove('active');
                }

                // Close Notification Dropdown if clicked outside
                if (notificationDropdown.classList.contains('active') && !notificationDropdown.contains(event.target) && !notificationIcon.contains(event.target)) {
                    notificationDropdown.classList.remove('active');
                }
            });

            // Handle Notification Dropdown Clicks
            if (notificationDropdown) {
                notificationDropdown.addEventListener('click', function(event) {
                    event.stopPropagation(); // Prevent event from bubbling up
                });
            }

            if (logoutDropdown) {
                logoutDropdown.addEventListener('click', function(event) {
                    event.stopPropagation(); // Prevent event from bubbling up
                });
            }

            // Function to Perform AJAX Logout
            function performLogout() {
                $.ajax({
                    url: "{{ route('logout') }}", // The logout route
                    type: 'POST', // Use POST request as required
                    data: {
                        _token: "{{ csrf_token() }}" // Pass CSRF token for security
                    },
                    success: function(response) {
                        // Show a SweetAlert success message upon successful logout
                        Swal.fire({
                            icon: 'success',
                            title: 'Logged Out',
                            text: 'You have been successfully logged out.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Redirect to the login page or another route after logout
                            window.location.href = "{{ route('login') }}";
                        });
                    },
                    error: function(xhr, status, error) {
                        // Show an error message if logout fails
                        console.error('Logout failed:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while trying to logout. Please try again.',
                        });
                    }
                });
            }

        // Function to Perform AJAX Fetch Notifications
        function fetchNotifications() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '{{ route('notifications.index') }}',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                const notificationDropdown = document.getElementById('notification-dropdown');
                const notificationDot = document.querySelector('.notification-dot');

                if (!notificationDropdown || !notificationDot) {
                    console.error('Error: notificationDropdown or notificationDot is not defined.');
                    reject('Notification dropdown or dot not found');
                    return;
                }

                // Display Toastr for each unread notification
                let unreadCount = 0;
                if (data.notifications.length > 0) {
                    data.notifications.forEach(notification => {
                        if (!notification.is_opened) {
                            // Display Toastr notification
                            toastr.info(notification.message, notification.title, {
                                timeOut: 5000,
                                closeButton: true,
                                progressBar: true,
                            });

                            // Automatically mark as opened after displaying
                            markAsOpened(notification.id);
                            unreadCount += 1;
                        }
                    });
                }

                // Existing dropdown handling
                notificationDropdown.innerHTML = ''; // Clear previous notifications

                if (data.notifications.length > 0) {
                    // Optional: Add "Mark All as Read" button
                    const markAllItem = document.createElement('div');
                    markAllItem.classList.add('dropdown-item');
                    markAllItem.id = 'mark-all-as-read';
                    markAllItem.style.cursor = 'pointer';
                    markAllItem.style.color = 'blue';
                    markAllItem.textContent = 'Mark All as Read';
                    markAllItem.addEventListener('click', () => {
                        markAllAsRead();
                    });
                    notificationDropdown.appendChild(markAllItem);

                    data.notifications.forEach(notification => {
                        const notificationItem = document.createElement('div');
                        notificationItem.classList.add('dropdown-item');

                        // Highlight unread notifications
                        if (!notification.is_opened) {
                            notificationItem.classList.add('unread');  // Custom class for unread
                        }

                        notificationItem.innerHTML = `
                            <div class="message">${notification.title}: ${notification.message}</div>
                            <div class="timestamp">${new Date(notification.scheduled_time).toLocaleString()}</div>
                        `;

                        // Mark notification as opened when clicked
                        notificationItem.addEventListener('click', () => {
                            if (!notification.is_opened) { // Only mark if unread
                                markAsOpened(notification.id).then(() => {
                                    notificationItem.classList.remove('unread'); // Remove unread class

                                    // Optionally, update the unread count
                                    if (unreadCount > 0) {
                                        unreadCount -= 1;
                                        if (unreadCount <= 0) {
                                            notificationDot.style.display = 'none';
                                        }
                                    }
                                });
                            }
                        });

                        notificationDropdown.appendChild(notificationItem);
                    });

                    // Show unread count
                    if (unreadCount > 0) {
                        notificationDot.style.display = 'block';
                    } else {
                        notificationDot.style.display = 'none';
                    }
                } else {
                    notificationDropdown.innerHTML = '<div class="dropdown-item">No new notifications</div>';
                    notificationDot.style.display = 'none';
                }

                resolve(unreadCount); // Resolve the Promise with the count
            },
            error: function(xhr, status, error) {
                console.error('Error fetching notifications:', error);
                const notificationDropdown = document.getElementById('notification-dropdown');
                notificationDropdown.innerHTML = '<div class="dropdown-item">Failed to load notifications</div>';
                reject(error); // Reject the Promise with the error
            }
        });
    });
}


        // Mark Notification as Opened
        function markAsOpened(notificationId) {
    return $.ajax({
        url: `/notifications/${notificationId}/mark-as-opened`,
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            console.log(data.message); // Handle success message if needed
        },
        error: function(xhr, status, error) {
            console.error('Error marking notification as opened:', error);
            toastr.error('Failed to mark notification as read.', 'Error', {
                timeOut: 3000,
                closeButton: true,
                progressBar: true,
            });
        }
    });
}


            // Function to Mark All Notifications as Read
            function markAllAsRead() {
    $.ajax({
        url: '{{ route('notifications.markAllAsRead') }}',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            if (data.message) {
                toastr.info(data.message, 'Notifications', {
                    timeOut: 3000,
                    closeButton: true,
                    progressBar: true,
                });
            }

            // Update UI to reflect that all notifications are read
            const notificationDot = document.querySelector('.notification-dot');
            if(notificationDot) {
                notificationDot.style.display = 'none';
            }

            // Remove the "unread" class from all notifications
            const unreadNotifications = document.querySelectorAll('.dropdown-item.unread');
            unreadNotifications.forEach(item => item.classList.remove('unread'));
        },
        error: function(xhr, status, error) {
            console.error('Error marking all notifications as read:', error);
            toastr.error('Failed to mark all notifications as read.', 'Error', {
                timeOut: 3000,
                closeButton: true,
                progressBar: true,
            });
        }
    });
}

         
        });
    </script>
</body>
</html>
