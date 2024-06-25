<x-app-layout>
    <style>
        .main-content {
            flex-grow: 1;
            padding: 20px;
            margin-left: 80px; /* Adjust margin to accommodate the sidebar */
            transition: margin-left 0.3s ease-in-out;
        }
        .sidebar:hover ~ .main-content {
            margin-left: 250px; /* Adjust margin when sidebar is expanded */
        }
        .image-container-dashboard {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(80vh - 80px); /* Adjust based on header height */
        }
        .image-container-dashboard img {
            max-width: 100%;
            height: auto;
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        .statistics {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .statistics .stat-box {
            background-color: #ffffff;
            color: #333;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 30%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease-in-out;
        }

        .statistics .stat-box:hover {
            background-color: #f0f0f0;
        }

        .stat-box img {
            width: 50px;
            height: 50px;
            display: block;
            margin: 0 auto 10px;
        }

        .stat-box a {
            color: blue; /* Set link color to blue */
            text-decoration: none; /* Remove underline */
        }

        .stat-box a:visited {
            color: blue; /* Ensure visited links remain blue */
        }

        .stat-box a:hover {
            text-decoration: none; /* Ensure underline doesn't appear on hover */
        }

        .notification-icon {
            position: fixed;
            top: 20px;
            right: 20px;
            cursor: pointer;
        }

        .notification-icon i {
            font-size: 24px;
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

    <div class="main-content">
        <!-- Notification Icon and Dropdown -->
        <div class="notification-icon" id="notification-icon">
            <i class="fas fa-bell"></i>
            <div id="notification-dropdown" class="notification-dropdown">
                <div class="notification-header">Notifications</div>
                <div id="notification-content"></div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="statistics">
            <div class="stat-box">
                <a href="{{ route('admin.appointment') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/appointment-reminders.png" alt="Appointments Icon">
                    <h2>{{ $appointmentCount }}</h2>
                    <p>Appointments</p>
                </a>
            </div>
            <div class="stat-box">
                <a href="{{ route('admin.complaint') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/complaint.png" alt="Complaints Icon">
                    <h2>{{ $complaintCount }}</h2>
                    <p>Complaints</p>
                </a>
            </div>
            <div class="stat-box">
                <a href="{{ route('admin.inventory') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/warehouse.png" alt="Inventory Icon">
                    <h2>{{ $inventoryCount }}</h2>
                    <p>Inventory Items</p>
                </a>
            </div>
        </div>
        <!-- Welcome message -->
        <h1 class="h1-header" style="margin-top: 80px;">Welcome, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}!</h1>

        <!-- Image container -->
        <div class="image-container-dashboard">
            <img src="{{ asset('images/pilarLogo.png') }}" alt="Login Image">
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('notification-icon').addEventListener('click', function() {
        var dropdown = document.getElementById('notification-dropdown');
        dropdown.classList.toggle('active');
        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            dropdown.style.display = 'block';
            fetchNotifications();
        } else {
            dropdown.style.display = 'none';
        }
    });

    function fetchNotifications() {
        fetch('{{ route("admin.notifications.index") }}')
            .then(response => response.json())
            .then(data => {
                const notificationContent = document.getElementById('notification-content');
                notificationContent.innerHTML = '';

                if (data.notifications.length > 0) {
                    data.notifications.forEach(notification => {
                        const notificationItem = document.createElement('div');
                        notificationItem.className = 'notification-item';
                        notificationItem.innerHTML = `
                            <span class="icon"><i class="fas fa-info-circle"></i></span>
                            <span>${notification.message}</span>
                        `;
                        notificationContent.appendChild(notificationItem);
                    });
                } else {
                    notificationContent.innerHTML = '<div class="notification-item">No notifications</div>';
                }
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }

    // Close the dropdown if clicked outside
    document.addEventListener('click', function(event) {
        var dropdown = document.getElementById('notification-dropdown');
        var icon = document.getElementById('notification-icon');
        if (!dropdown.contains(event.target) && !icon.contains(event.target)) {
            dropdown.classList.remove('active');
            dropdown.style.display = 'none';
        }
    });
</script>
</x-app-layout>
