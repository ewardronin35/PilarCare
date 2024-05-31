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
            margin-right: 10px;
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
        }

        .form-group button:hover {
            background-color: #00b8e6;
        }

        .appointment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
            <header class="header relative">
                <div></div>
                <div class="user-info">
                    <span class="username">{{ Auth::user()->name }}</span>
                    <span class="role">Admin</span>
                    <div class="notification-icon">
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
                    <img src="{{ asset('images/pilarLogo.png') }}" alt="User Avatar" class="user-avatar">
                </div>
            </header>

            <div class="form-container">
                <h2>Schedule Appointment</h2>
                <form method="POST" action="{{ route('appointment.add') }}">
                    @csrf
                    <div class="form-group">
                        <label for="patient-id">Patient ID</label>
                        <input type="text" id="patient-id" name="patient_id" required>
                    </div>
                    <div class="form-group">
                        <label for="patient-name">Patient Name</label>
                        <input type="text" id="patient-name" name="patient_name" required>
                    </div>
                    <div class="form-group">
                        <label for="appointment-date">Appointment Date</label>
                        <input type="date" id="appointment-date" name="appointment_date" required>
                    </div>
                    <div class="form-group">
                        <label for="appointment-time">Appointment Time</label>
                        <input type="time" id="appointment-time" name="appointment_time" required>
                    </div>
                    <div class="form-group">
                        <button type="submit">Schedule Appointment</button>
                    </div>
                </form>
            </div>

            <!-- Appointment Table Content -->
            <h1>Appointments</h1>
            <table class="appointment-table">
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>Patient Name</th>
                        <th>Appointment Date</th>
                        <th>Appointment Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->patient_id }}</td>
                            <td>{{ $appointment->patient_name }}</td>
                            <td>{{ $appointment->appointment_date }}</td>
                            <td>{{ $appointment->appointment_time }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button onclick="document.getElementById('update-form-{{ $appointment->id }}').style.display='block'">Edit</button>
                                    <form method="POST" action="{{ route('appointment.delete', $appointment->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this appointment?')">Delete</button>
                                    </form>
                                </div>
                                <div id="update-form-{{ $appointment->id }}" style="display:none" class="form-container">
                                    <h2>Update Appointment</h2>
                                    <form method="POST" action="{{ route('appointment.update', $appointment->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="patient-id-{{ $appointment->id }}">Patient ID</label>
                                            <input type="text" id="patient-id-{{ $appointment->id }}" name="patient_id" value="{{ $appointment->patient_id }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="patient-name-{{ $appointment->id }}">Patient Name</label>
                                            <input type="text" id="patient-name-{{ $appointment->id }}" name="patient_name" value="{{ $appointment->patient_name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="appointment-date-{{ $appointment->id }}">Appointment Date</label>
                                            <input type="date" id="appointment-date-{{ $appointment->id }}" name="appointment_date" value="{{ $appointment->appointment_date }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="appointment-time-{{ $appointment->id }}">Appointment Time</label>
                                            <input type="time" id="appointment-time-{{ $appointment->id }}" name="appointment_time" value="{{ $appointment->appointment_time }}" required>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit">Update Appointment</button>
                                            <button type="button" onclick="document.getElementById('update-form-{{ $appointment->id }}').style.display='none'">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
