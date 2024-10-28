<x-app-layout :pageTitle="' Dashboard'">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .main-content {
            margin-top: 40px;
            transition: margin-left 0.3s ease-in-out;
            overflow-y: auto;
        }

        .profile-box {
            position: relative; /* Required for the overlay */
            display: flex;
            align-items: center;
            padding: 20px;
            background-image: url('{{ asset('images/bg.jpg') }}');
            background-size: cover;
            background-position: center;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            color: white;
            overflow: hidden; /* Clip the overlay within the border-radius */
        }

        .profile-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5); /* 50% opacity black overlay */
            z-index: 1; /* Place behind the profile content */
        }

        .profile-box img {
            border-radius: 50%;
            width: 80px;
            height: 80px;
            margin-right: 20px;
            z-index: 2; /* Ensure the profile image is above the overlay */
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            z-index: 2; /* Ensure text content is above the overlay */
        }


        .profile-info h2 {
            margin: 0;
            font-size: 1.5em;
        }

        .profile-info p {
            margin: 0;
        }

        .edit-profile-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            text-align: center;
            text-decoration: none;
            margin-top: 10px;
        }

        .edit-profile-btn:hover {
            background-color: #0056b3;
        }

        .statistics {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    gap: 10px;
    flex-wrap: nowrap; /* Prevent wrapping */
}

       
.statistics .stat-box {
    background-color: #ffffff;
    color: #333;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    flex: 1; /* Allows boxes to grow equally */
    margin: 0 5px; /* Adjust margin to handle gaps */
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
            color: #007bff;
            text-decoration: none;
        }

        .stat-box a:visited {
            color: #007bff;
        }

        .stat-box a:hover {
            text-decoration: none;
        }

        .content-row {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            gap: 10px;
            flex-wrap: wrap;
        }
        .statistics .stat-box:first-child {
    margin-left: 0; /* Remove left margin for the first box */
}

.statistics .stat-box:last-child {
    margin-right: 0; /* Remove right margin for the last box */
}
/* Calendar Container */
.calendar-container {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    width: 49%;
    box-sizing: border-box;
}

/* Calendar Controls */
.calendar-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.calendar-controls button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.calendar-controls button:hover {
    background-color: #0056b3;
}

/* Calendar Table */
.calendar {
    width: 100%;
    border-collapse: collapse;
}

.calendar th, .calendar td {
    width: 14.28%; /* 100% / 7 days */
    height: 80px;
    text-align: center;
    vertical-align: middle;
    border: 1px solid #ddd;
    position: relative;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.calendar th {
    background-color: #f5f5f5;
    color: #333;
    font-weight: bold;
}

.calendar td {
    background-color: #f9f9f9;
}

.calendar td:hover {
    background-color: #e6f7ff;
}

/* Status Colors */
.calendar td.green {
    background-color: #d4edda; /* Light Green */
}

.calendar td.yellow {
    background-color: #fff3cd; /* Light Yellow */
}

.calendar td.red {
    background-color: #f8d7da; /* Light Red */
}

/* Active Date Highlight */
.calendar td.active {
    border: 2px solid #007bff;
    background-color: #cce5ff !important;
}

/* Calendar Legend */
.calendar-legend {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    gap: 20px;
    margin-top: 10px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.9rem;
}

.legend-color {
    width: 15px;
    height: 15px;
    border-radius: 3px;
}

/* Legend Colors */
.legend-green {
    background-color: #28a745;
}

.legend-yellow {
    background-color: #ffc107;
}

.legend-red {
    background-color: #dc3545;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .calendar-container {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .statistics .stat-box {
        width: calc(50% - 10px);
    }

    .content-row {
        flex-direction: column;
    }

    .chart-container, .calendar-container {
        width: 100%;
    }

    .calendar th, .calendar td {
        height: 60px;
    }
}

        .chart-container, .calendar-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 49%;
            box-sizing: border-box;
        }

        .chart-container h3, .calendar-container h2 {
            margin-top: 0;
            color: #0056b3;
        }

        .data-table-wrapper {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 49%;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .data-table th, .data-table td {
            padding: 10px;
            text-align: left;
        }

        .data-table th {
            background-color: #f5f5f5;
            color: #333;
            font-weight: 600;
            border-bottom: 1px solid #ddd;
        }

        .data-table td {
            border-bottom: 1px solid #eee;
        }

        .nav-tabs {
            border-bottom: 1px solid #ddd;
            display: flex;
            gap: 10px;
            list-style: none; /* Remove default list-style */
            padding-left: 0;
        }

        .nav-tabs .nav-item {
            margin-bottom: -1px;
        }

        .nav-tabs .nav-link {
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease-in-out;
        }

        .nav-tabs .nav-link:hover {
            background-color: #0056b3;
        }

        .nav-tabs .nav-link.active {
            background-color: #0056b3;
        }

        .tab-content {
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-top: none;
        }

        .tab-pane {
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .tab-pane.active {
            display: block;
            opacity: 1;
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

        /* Calendar Specific Styles */
        .calendar-legend {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            gap: 20px;
            margin-top: 10px;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.9rem;
        }

        .legend-color {
            width: 15px;
            height: 15px;
            border-radius: 3px;
            animation: pulse 2s infinite;
        }

        .legend-green {
            background-color: #28a745;
        }

        .legend-yellow {
            background-color: #ffc107;
        }

        .legend-red {
            background-color: #dc3545;
        }

        /* Pulse Animation for Legend Colors */
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.7;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Calendar Cell Animations */
        .calendar td.green,
        .calendar td.yellow,
        .calendar td.red {
            position: relative;
            overflow: hidden;
            z-index: 1; /* Ensure cell content is above the ::after */
        }

        .calendar td.green::after,
        .calendar td.yellow::after,
        .calendar td.red::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            animation: fadeInScale 0.5s ease-in-out;
            z-index: -1;
        }

        .calendar td.green::after {
            background-color: rgba(40, 167, 69, 0.3);
        }

        .calendar td.yellow::after {
            background-color: rgba(255, 193, 7, 0.3);
        }

        .calendar td.red::after {
            background-color: rgba(220, 53, 69, 0.3);
        }

        @keyframes fadeInScale {
            from {
                transform: scale(0.8);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6); /* Dark background with opacity */
            animation: fadeIn 0.4s ease-in-out;
        }

        .modal-content {
            background-color: white;
            margin: 5% auto; /* Reduced top margin for better positioning */
            padding: 30px 40px;
            border-radius: 12px;
            max-width: 600px;
            width: 90%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.4s ease-in-out;
            overflow-y: auto;
            max-height: 80%;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
            }
            to {
                transform: translateY(0);
            }
        }

        .modal h2 {
            margin-top: 0;
            font-size: 1.8rem;
            text-align: center;
            color: #0056b3;
            margin-bottom: 20px;
        }

        /* Close Button */
        .close {
            color: #999;
            float: right;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover, .close:focus {
            color: #333;
            text-decoration: none;
            cursor: pointer;
        }

        /* Appointments List Styles */
        .appointments-container {
            padding: 15px 0;
        }

        #appointments-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        #appointments-list li {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 10px;
            transition: transform 0.2s ease-in-out;
        }

        #appointments-list li:hover {
            background-color: #f0f8ff;
            transform: scale(1.02);
        }

        /* Appointment Text */
        #appointments-list li p {
            margin: 0;
            font-size: 1rem;
            color: #333;
        }

        #appointments-list li p span {
            font-weight: bold;
            color: #0056b3;
        }

        /* Custom Scrollbar for Modal Content */
        .modal-content::-webkit-scrollbar {
            width: 8px;
        }

        .modal-content::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 5px;
        }

        .modal-content::-webkit-scrollbar-thumb:hover {
            background-color: #888;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .statistics .stat-box {
                width: calc(50% - 10px);
            }

            .content-row {
                flex-direction: column;
            }

            .chart-container, .calendar-container {
                width: 100%;
            }
        }
    </style>

    <div class="main-content">
        <!-- Profile Section -->
        <div class="profile-box">
            <img src="{{ asset('images/pilarLogo.jpg') }}" alt="Profile Image">
            <div class="profile-info">
                <h2>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h2>
                <p>{{ Auth::user()->role }}</p>
                <a href="{{ route('profile.edit') }}" class="edit-profile-btn">Edit Profile</a>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Statistics Section -->
        <div class="statistics">
            <!-- Appointments Count -->
            <div class="stat-box">
                <a href="{{ route('doctor.appointments') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/appointment-reminders.png" alt="Appointments Icon">
                    <h2>{{ $doctorAppointmentsCount }}</h2>
                    <p>My Appointments</p>
                </a>
            </div>

            <!-- Complaints Count -->
            <div class="stat-box">
                <a href="{{ route('doctor.complaints') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/complaint.png" alt="Complaints Icon">
                    <h2>{{ $complaintCount }}</h2>
                    <p>Complaints</p>
                </a>
            </div>

            <!-- Medical Records Count -->
            <div class="stat-box">
                <a href="{{ route('doctor.medical-record.index') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/medical-doctor.png" alt="Medical Records Icon">
                    <h2>{{ $medicalRecordCount }}</h2>
                    <p>Medical Records</p>
                </a>
            </div>

            <!-- Dental Records Count -->
            <div class="stat-box">
                <a href="{{ route('doctor.dental-record.index') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/tooth.png" alt="Dental Records Icon">
                    <h2>{{ $dentalRecordCount }}</h2>
                    <p>Dental Records</p>
                </a>
            </div>
        </div>

        <!-- Appointments Statistics Chart and Calendar -->
        <div class="content-row">
            <!-- Appointments Statistics Chart -->
            <div class="chart-container">
                <h3>Appointments This Year</h3>
                <canvas id="appointmentsChart"></canvas>
            </div>

            <!-- Appointment Calendar -->
            <div class="calendar-container">
                <h2>Appointment Calendar</h2>
                <div class="calendar-controls">
                    <button onclick="changeMonth(-1)">Previous</button>
                    <span id="calendar-month-year"></span>
                    <button onclick="changeMonth(1)">Next</button>
                </div>
                <table class="calendar">
                    <thead>
                        <tr>
                            <th>Sun</th>
                            <th>Mon</th>
                            <th>Tue</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                            <th>Sat</th>
                        </tr>
                    </thead>
                    <tbody id="calendar-body">
                        <!-- Dynamically generated calendar rows go here -->
                    </tbody>
                </table>
                <!-- Legend Section -->
                <div class="calendar-legend">
                    <div class="legend-item">
                        <div class="legend-color legend-green"></div>
                        <span>Free</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color legend-yellow"></div>
                        <span>Pending</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color legend-red"></div>
                        <span>Confirmed</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Appointments Modal -->
        <div id="preview-modal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closePreviewModal()">&times;</span>
                <h2>Appointments on <span id="preview-date"></span></h2>
                <ul id="appointments-list">
                    <!-- Appointments will be dynamically inserted here -->
                </ul>
            </div>
        </div>
    </div>

    <!-- Include SweetAlert2 Library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Chart.js
            var ctx = document.getElementById('appointmentsChart').getContext('2d');

            var monthLabels = [
                @foreach(range(1, 12) as $month)
                    '{{ date("F", mktime(0, 0, 0, $month, 10)) }}'@if(!$loop->last), @endif
                @endforeach
            ];

            var appointmentsData = @json($appointmentsPerMonth);

            var appointmentsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: 'Appointments per Month',
                        data: appointmentsData,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)', // Light Blue
                        borderColor: 'rgba(54, 162, 235, 1)', // Blue
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }
                }
            });

            // Initialize Calendar
            renderCalendar(currentMonth, currentYear);
        });

        // Global Variables for Calendar
        const currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();

        /**
         * Render Calendar Function
         */
        function renderCalendar(month, year) {
            const monthString = String(month + 1).padStart(2, '0');
            let fetchUrl = `/doctor/appointments-by-month-doctor?month=${year}-${monthString}`;

            // Fetch the data
            fetch(fetchUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const appointmentsByDate = {};

                    if (data.appointments && data.appointments.length > 0) {
                        data.appointments.forEach(appointment => {
                            // Assuming 'appointment_date' is in 'YYYY-MM-DD' format
                            const date = appointment.appointment_date;
                            if (!appointmentsByDate[date]) {
                                appointmentsByDate[date] = [];
                            }
                            appointmentsByDate[date].push(appointment);
                        });
                    }

                    renderCalendarDays(month, year, appointmentsByDate);
                })
                .catch(error => {
                    console.error('Error fetching appointments for the month:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load appointments for the selected month.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    renderCalendarDays(month, year, {}); // Proceed without appointments
                });
        }

        /**
         * Render Calendar Days Function
         */
        function renderCalendarDays(month, year, appointmentsByDate) {
            const calendarBody = document.getElementById('calendar-body');
            calendarBody.innerHTML = '';
            const monthYearText = document.getElementById('calendar-month-year');
            const firstDay = new Date(year, month).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const monthNames = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];
            monthYearText.textContent = `${monthNames[month]} ${year}`;
            let date = 1;

            for (let i = 0; i < 6; i++) { // 6 weeks max in a month
                let row = document.createElement('tr');
                for (let j = 0; j < 7; j++) { // 7 days a week
                    let cell = document.createElement('td');
                    if (i === 0 && j < firstDay) {
                        cell.appendChild(document.createTextNode(''));
                    } else if (date > daysInMonth) {
                        break;
                    } else {
                        let selectedDate = date;
                        cell.textContent = selectedDate;

                        // Format dateString as 'YYYY-MM-DD'
                        const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(selectedDate).padStart(2, '0')}`;

                        // Determine the class based on appointment statuses
                        if (appointmentsByDate[dateString]) {
                            const appointments = appointmentsByDate[dateString];
                            let hasConfirmed = false;
                            let hasPending = false;

                            appointments.forEach(appointment => {
                                const status = appointment.status.toLowerCase().trim();
                                if (status === 'confirmed') {
                                    hasConfirmed = true;
                                } else if (status === 'pending') {
                                    hasPending = true;
                                }
                            });

                            if (hasConfirmed) {
                                cell.classList.add('red'); // Confirmed appointments
                            } else if (hasPending) {
                                cell.classList.add('yellow'); // Pending appointments
                            }
                        } else {
                            cell.classList.add('green'); // Free date
                        }

                        // Add click event to open preview modal
                        cell.onclick = () => {
                            openPreviewModal(selectedDate, month, year);
                        };

                        // Highlight today's date
                        const today = new Date();
                        if (selectedDate === today.getDate() && year === today.getFullYear() && month === today.getMonth()) {
                            cell.classList.add('active');
                        }

                        date++;
                    }
                    row.appendChild(cell);
                }
                calendarBody.appendChild(row);
            }
        }

        /**
         * Change Month Function
         */
        function changeMonth(direction) {
            currentMonth += direction;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            } else if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar(currentMonth, currentYear);
        }

        /**
         * Open Preview Modal Function
         */
        function openPreviewModal(date, month, year) {
            const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;

            document.getElementById('preview-date').innerText = formattedDate;

            fetch(`/doctor/appointments-by-date?date=${formattedDate}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const appointmentsList = document.getElementById('appointments-list');
                    appointmentsList.innerHTML = ''; // Clear previous appointments

                    if (data.appointments && data.appointments.length > 0) {
                        data.appointments.forEach(appointment => {
                            // Convert appointment_time to 12-hour AM/PM format
                            const timeString = appointment.appointment_time;
                            const timeFormatted = formatTimeTo12Hour(timeString);

                            const li = document.createElement('li');
                            li.innerText = `${appointment.patient_name} - ${timeFormatted} (${appointment.appointment_type}) - Status: ${capitalizeFirstLetter(appointment.status)}`;
                            appointmentsList.appendChild(li);
                        });
                    } else {
                        const li = document.createElement('li');
                        li.innerText = 'No appointments for this day.';
                        appointmentsList.appendChild(li);
                    }

                    const modal = document.getElementById('preview-modal');
                    modal.style.display = 'block'; // Show the modal
                })
                .catch(error => {
                    console.error('Error fetching appointments:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load appointments.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                });
        }

        /**
         * Helper function to format time to 12-hour AM/PM
         */
        function formatTimeTo12Hour(timeString) {
            // Assume timeString is in "HH:mm:ss" or "HH:mm" format
            const [hoursStr, minutesStr] = timeString.split(':');
            let hours = parseInt(hoursStr, 10);
            const minutes = minutesStr;
            const ampm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12 || 12; // Convert '0' to '12' for 12 AM
            return `${hours}:${minutes} ${ampm}`;
        }

        /**
         * Helper function to capitalize first letter
         */
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        /**
         * Close Preview Modal Function
         */
        function closePreviewModal() {
            document.getElementById('preview-modal').style.display = 'none';
        }

        /**
         * Close Modal When Clicking Outside of Modal Content
         */
        window.onclick = function(event) {
            const previewModal = document.getElementById('preview-modal');
            if (event.target == previewModal) {
                previewModal.style.display = 'none';
            }
        }
    </script>

    <!-- Preview Appointments Modal (Hidden initially) -->
    <div id="preview-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closePreviewModal()">&times;</span>
            <h2>Appointments on <span id="preview-date"></span></h2>
            <ul id="appointments-list">
                <!-- Appointments will be dynamically inserted here -->
            </ul>
        </div>
    </div>
</x-app-layout>
