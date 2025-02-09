<x-app-layout :pageTitle="'Appointments'">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- Custom CSS -->
    <style>
        /* General Styles */
        body {
            background-color: #f4f6f9;
            font-family: 'Poppins', sans-serif; 
        }

        .main-content {
            margin-top: 30px;
        }

        .form-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin: 20px;
            gap: 20px;
            flex-direction: row; /* Ensure items are side by side */
        }

        .appointment-section {
            width: 60%; /* Adjusted width to allow calendar on the right */
            display: flex;
            flex-direction: column;
            gap: 20px;
            animation: fadeInUp 0.5s ease-in-out; /* Added animation */
        }

        .appointment-list, .history-list {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            overflow-y: auto;
            flex: 1;
            animation: fadeInUp 0.5s ease-in-out; /* Added animation */
        }

        .appointment-list h2, .history-list h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .appointment-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
        }

        .appointment-table th, .appointment-table td {
            padding: 10px;
            text-align: left;
        }

        .appointment-table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .appointment-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .appointment-table td {
            border-bottom: 1px solid #ddd;
        }

        .appointment-table tr:last-child td {
            border-bottom: none;
        }

        /* Calendar Styles */
        .calendar-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-sizing: border-box;
            width: 40%; /* Adjusted width to be on the right */
            animation: fadeInUp 0.5s ease-in-out; /* Added animation */
        }

        .calendar-container h2 {
            margin-top: 0;
            color: #0056b3;
            text-align: center;
        }

        .calendar-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .calendar-controls button {
            background-color: #007bff;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 1rem;
        }

        .calendar-controls button:hover {
            background-color: #0056b3;
        }

        .calendar {
            width: 100%;
            border-collapse: collapse;
        }

        .calendar th, .calendar td {
            width: 14.28%;
            height: 80px;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #ddd;
            position: relative;
            cursor: pointer;
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

        .legend-green {
            background-color: #28a745;
        }

        .legend-yellow {
            background-color: #ffc107;
        }

        .legend-red {
            background-color: #dc3545;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            width: 100%;
            max-width: 600px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            overflow-y: auto;
            max-height: 80%;
            animation: fadeInUp 0.5s ease-in-out; /* Added animation */
        }

        .close {
            color: #999;
            align-self: flex-end;
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
        }

        #appointments-list li p {
            margin: 0;
            font-size: 1rem;
            color: #333;
        }

        #appointments-list li p span {
            font-weight: bold;
            color: #0056b3;
        }

        /* Keyframes for Fade In Animation */
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

        /* Responsive Styles */
        @media (max-width: 768px) {
            .form-container {
                flex-direction: column;
                align-items: center;
            }

            .appointment-section, .calendar-container {
                width: 100%;
            }
        }
    </style>

    <div class="main-content">
        <div class="form-container">
            <div class="appointment-section">
                <!-- Upcoming Appointments -->
                <div class="appointment-list">
                    <h2>Your Upcoming Appointments</h2>
                    <div>
                        <table class="appointment-table" id="upcoming-appointments-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Doctor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingAppointments as $appointment)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</td>
                                        <td>{{ $appointment->appointment_type }}</td>
                                        <td>{{ ucfirst($appointment->status) }}</td>
                                        <td>
                                            @if($appointment->doctor)
                                                {{ $appointment->doctor->full_name ?? '' }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Appointment History -->
                <div class="history-list">
                    <h2>Appointment History</h2>
                    <div>
                        <table class="appointment-table" id="appointment-history-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Doctor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($completedAppointments as $appointment)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</td>
                                        <td>{{ $appointment->appointment_type }}</td>
                                        <td>{{ ucfirst($appointment->status) }}</td>
                                        <td>
                                            @if($appointment->doctor)
                                                {{ $appointment->doctor->full_name ?? '' }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Calendar Section -->
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
                <div class="appointments-container">
                    <ul id="appointments-list">
                        <!-- Appointments will be dynamically inserted here -->
                    </ul>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>

        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

        <!-- SweetAlert JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- JavaScript Variables for Routes -->
        <script>
            const routes = {
                getAppointmentsByMonth: "{{ route('teacher.appointments.by-month') }}",
                getAppointmentsByDate: "{{ route('teacher.appointments.by-date') }}",
            };
        </script>

        <!-- Custom JavaScript -->
        <script>
            $(document).ready(function () {
                // Initialize DataTables for appointment tables
                $('#upcoming-appointments-table').DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "language": {
                        "emptyTable": "No upcoming appointments available"
                    }
                });

                $('#appointment-history-table').DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "language": {
                        "emptyTable": "No appointment history available"
                    }
                });

                // Initialize Calendar
                renderCalendar(currentMonth, currentYear);
            });

            // Global Variables for Calendar
            const currentDate = new Date();
            let currentMonth = currentDate.getMonth();
            let currentYear = currentDate.getFullYear();
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            /**
             * Render Calendar Function
             */
            function renderCalendar(month, year) {
                const monthString = String(month + 1).padStart(2, '0');
                let fetchUrl = `${routes.getAppointmentsByMonth}?month=${year}-${monthString}`;

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
        // Split the date-time string on space and take the first part
        const appDate = new Date(appointment.appointment_date);
    // Format it as "YYYY-MM-DD"
    const dateKey = appDate.getFullYear() + '-' +
        String(appDate.getMonth() + 1).padStart(2, '0') + '-' +
        String(appDate.getDate()).padStart(2, '0');
    console.log("Appointment date key:", dateKey);

                    if (!appointmentsByDate[dateKey]) {
            appointmentsByDate[dateKey] = [];
        }
        appointmentsByDate[dateKey].push(appointment);
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
                            const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(selectedDate).padStart(2, '0')}`;

                            // Determine the class based on appointment statuses
                            if (appointmentsByDate[dateString]) {
                const appointments = appointmentsByDate[dateString];

                // Use .some() to check for statuses robustly.
                const hasConfirmed = appointments.some(app =>
                    app.status && app.status.toLowerCase().trim() === 'confirmed'
                );
                const hasPending = appointments.some(app =>
                    app.status && app.status.toLowerCase().trim() === 'pending'
                );

                if (hasConfirmed) {
                    cell.classList.add('red'); // Confirmed appointments
                } else if (hasPending) {
                    cell.classList.add('yellow'); // Pending appointments
                } else {
                    cell.classList.add('green'); // (if there are appointments with another status)
                }
            } else {
                cell.classList.add('green'); // Free date
            }

            // Add the click event to open the preview modal
            cell.onclick = () => {
                openPreviewModal(selectedDate, month, year);
            };

            // Highlight today's date
            const today = new Date();
            if (selectedDate === today.getDate() &&
                year === today.getFullYear() &&
                month === today.getMonth()
            ) {
                cell.classList.add('active');
            }

            row.appendChild(cell);
            date++;
        }
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
            function openPreviewModal(day, month, year) {
                const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                document.getElementById('preview-date').innerText = formattedDate;

                fetch(`${routes.getAppointmentsByDate}?date=${formattedDate}`)
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
                                li.innerHTML = `<p><span>${timeFormatted}</span> - ${appointment.appointment_type}</p>`;
                                appointmentsList.appendChild(li);
                            });
                        } else {
                            const li = document.createElement('li');
                            li.innerText = 'No appointments for this day.';
                            appointmentsList.appendChild(li);
                        }

                        const modal = document.getElementById('preview-modal');
                        modal.style.display = 'flex'; // Show the modal
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
                const [hoursStr, minutesStr] = timeString.split(':');
                let hours = parseInt(hoursStr, 10);
                const minutes = minutesStr;
                const ampm = hours >= 12 ? 'PM' : 'AM';

                hours = hours % 12 || 12; // Convert '0' to '12' for 12 AM
                return `${hours}:${minutes} ${ampm}`;
            }

            /**
             * Close Preview Modal Function
             */
            function closePreviewModal() {
                const modal = document.getElementById('preview-modal');
                modal.style.display = 'none';
            }

            // Close modal when clicking outside the modal content
            window.onclick = function(event) {
                const modal = document.getElementById('preview-modal');
                if (event.target == modal) {
                    closePreviewModal();
                }
            };
        </script>
    </div>
</x-app-layout>
