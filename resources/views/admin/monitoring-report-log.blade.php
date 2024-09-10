<x-app-layout>
    <style>
        .container {
            display: flex;
        }

        .main-content {
            margin-left: 80px; /* Collapsed sidebar width */
            width: 100%;
            margin-top: 40px;
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
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

        .tabs {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .tab:hover {
            background-color: #f0f0f0;
        }

        .tab.active {
            border-bottom: 2px solid #007bff;
            font-weight: bold;
            color: #007bff;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .appointments-table th {
            background-color: #00d1ff;
            color: white;
        }
    </style>

    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <div class="container">

        <main class="main-content">
            <!-- Tabs for different logs -->
            <div class="tabs">
                <div class="tab active" onclick="showTab('notification-log')">Notification Logs</div>
                <div class="tab" onclick="showTab('inventory-log')">Inventory Logs</div>
                <div class="tab" onclick="showTab('complaint-log')">Complaint Logs</div>
                <div class="tab" onclick="showTab('appointment-log')">Appointment Logs</div>
                <div class="tab" onclick="showTab('accounts-log')">Accounts Logs</div>
            </div>

            <!-- Notification Logs Content -->
            <div id="notification-log" class="tab-content active">
                <div class="content">
                    <div class="table-container">
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
            </div>

            <!-- Inventory Logs Content -->
            <div id="inventory-log" class="tab-content">
                <div class="content">
                    <div class="table-container">
                        <table class="appointments-table">
                            <thead>
                                <tr>
                                    <th>Item ID</th>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                    <th>Date Added</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1001</td>
                                    <td>Stethoscope</td>
                                    <td>10</td>
                                    <td>May 10, 2024</td>
                                    <td>Available</td>
                                </tr>
                                <!-- More rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Complaint Logs Content -->
            <div id="complaint-log" class="tab-content">
                <div class="content">
                    <div class="table-container">
                        <table class="appointments-table">
                            <thead>
                                <tr>
                                    <th>Complaint ID</th>
                                    <th>Complainant Name</th>
                                    <th>Complaint</th>
                                    <th>Date Filed</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2001</td>
                                    <td>John Doe</td>
                                    <td>Noise in dormitory</td>
                                    <td>May 15, 2024</td>
                                    <td>Resolved</td>
                                </tr>
                                <!-- More rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Appointment Logs Content -->
            <div id="appointment-log" class="tab-content">
                <div class="content">
                    <div class="table-container">
                        <table class="appointments-table">
                            <thead>
                                <tr>
                                    <th>ID Number</th>
                                    <th>Patient Name</th>
                                    <th>Appointment Date</th>
                                    <th>Appointment Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>C2100D</td>
                                    <td>Jane Doe</td>
                                    <td>May 20, 2024</td>
                                    <td>10:00 AM</td>
                                    <td>Completed</td>
                                </tr>
                                <!-- More rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Accounts Logs Content -->
            <div id="accounts-log" class="tab-content">
                <div class="content">
                    <div class="table-container">
                        <table class="appointments-table">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Username</th>
                                    <th>Action</th>
                                    <th>Date and Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>U2100D</td>
                                    <td>admin</td>
                                    <td>Login</td>
                                    <td>May 20, 2024 10:00 AM</td>
                                </tr>
                                <!-- More rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showTab(tabId) {
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(tabContent => {
                tabContent.classList.remove('active');
            });

            const selectedTabContent = document.getElementById(tabId);
            selectedTabContent.classList.add('active');

            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            document.querySelector(`.tab[onclick="showTab('${tabId}')"]`).classList.add('active');
        }

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
