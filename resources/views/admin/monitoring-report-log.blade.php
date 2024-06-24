<x-app-layout>
    <style>
        .container {
            display: flex;
        }

        .main-content {
            margin-left: 80px; /* Collapsed sidebar width */
            width: 100%;
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
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info .username {
            margin-right: 10px;
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

        .form-container {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-in-out;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .form-group button:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        .form-group button:active {
            transform: scale(0.95);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .appointment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .appointment-table th,
        .appointment-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .appointment-table th {
            background-color: #00d1ff;
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }
    </style>

    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <div class="container">
        <x-adminsidebar /> <!-- Using the sidebar component -->

        <main class="main-content">
            <!-- Notification Logs Content -->
            <div class="content">
                <div class="tabs">
                    <a href="{{ route('admin.appointment') }}" class="tab active">Notification Logs</a>
                    <a href="#" class="tab">Clinic Logs</a>
                </div>
                <div class="table-container">
                    <!-- Example table -->
                    <table class="appointments-table">
                        <thead>
                            <tr>
                                <th>School ID</th>
                                <th>Full Name</th>
                                <th>Notified for</th>
                                <th>Date and Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Add table rows here -->
                            <tr>
                                <td>C2100D</td>
                                <td>Username</td>
                                <td>Appointment</td>
                                <td>May 20, 2024 10:00 AM</td>
                                <td>Scheduled</td>
                            </tr>
                            <!-- More rows as needed -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Form Container -->
            <div class="form-container">
                <h1>Release Notification</h1>
                <form method="POST" action="{{ route('admin.notifications.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="school_id">School ID/Staff ID</label>
                        <input type="text" id="school_id" name="school_id" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="notification_info">Notification Information</label>
                        <input type="text" id="notification_info" name="notification_info" required>
                    </div>
                    <div class="form-group">
                        <label>Notification for:</label>
                        <div>
                            <input type="radio" id="student" name="notification_for" value="student">
                            <label for="student">Student</label>
                            <input type="radio" id="parent" name="notification_for" value="parent">
                            <label for="parent">Parent</label>
                            <input type="radio" id="staff" name="notification_for" value="staff">
                            <label for="staff">Staff/Teachers</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit">Notify</button>
                    </div>
                </form>
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
