<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Poppins', sans-serif; 
        }

        .main-content {
            margin-top: 80px;
        }

        .form-container {
            display: flex;
            justify-content: space-between;
            align-items: stretch;
            margin: 20px;
            gap: 20px;
            height: calc(100vh - 160px); /* Adjust based on header/footer */
        }

        .appointment-section {
            width: 50%;
            display: flex;
            flex-direction: column;
            gap: 20px;
            height: 100%;
        }

        .appointment-list, .history-list {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-in-out;
            overflow-y: auto;
            flex: 1;
        }

        .appointment-list h2, .history-list h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .appointment-table {
            font-family: 'Poppins', sans-serif; 
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
            width: 50%;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            animation: fadeInUp 0.5s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            height: 60%;
            display: flex;
            flex-direction: column;
        }

        .calendar-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .calendar-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .calendar-controls button {
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }

        .calendar-controls button:hover {
            background-color: #0056b3;
        }

        #calendar-month-year {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .calendar {
            width: 100%;
            border-collapse: collapse;
            flex: 1;
        }

        .calendar th {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
        }

        .calendar td {
            width: 14.28%;
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
            cursor: pointer;
            height: 100%; /* Adjust to fill the cell */
        }

        .calendar td.active {
            background-color: #007bff;
            color: white;
        }

        .calendar td:hover {
            background-color: #0056b3;
            color: white;
        }

        .calendar td.today {
            background-color: #add8e6; /* Light blue */
            color: white;
            border-radius: 50%;
        }

        .appointment-marker {
            background-color: #ffcc00;
            border-radius: 50%;
            width: 10px;
            height: 10px;
            display: inline-block;
            margin-left: 5px;
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
            background-color: rgba(0, 0, 0, 0.5);
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
            animation: slideIn 0.4s ease-in-out;
            overflow-y: auto;
            max-height: 80%;
        }

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

        /* Legend Section */
        .legend {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }

        .legend-item {
            display: flex;
            align-items: center;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            margin-right: 5px;
        }

        .legend-label {
            font-size: 0.9rem;
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

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
            }
            to {
                transform: translateY(0);
            }
        }

    </style>

    <div class="form-container">
        <div class="appointment-section">
            <!-- Upcoming Appointments -->
            <div class="appointment-list">
                <h2>Your Upcoming Appointments</h2>
                <div style="overflow-y: auto; max-height: calc(50% - 40px);">
                    <table class="appointment-table">
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
    @if($upcomingAppointments->isNotEmpty())
        @foreach($upcomingAppointments as $appointment)
            <tr>
                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}</td>
                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</td>
                <td>{{ $appointment->appointment_type }}</td>
                <td>{{ ucfirst($appointment->status) }}</td>
                <td>
                    @if($appointment->doctor)
                        {{ $appointment->doctor->first_name ?? '' }} {{ $appointment->doctor->last_name ?? '' }}
                    @else
                        N/A
                    @endif
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5">No upcoming appointments</td>
        </tr>
    @endif
</tbody>

                    </table>
                </div>
            </div>

            <!-- Appointment History -->
            <div class="history-list">
                <h2>Appointment History</h2>
                <div style="overflow-y: auto; max-height: calc(50% - 40px);">
                    <table class="appointment-table">
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
                            @if($completedAppointments->isNotEmpty())
                                @foreach($completedAppointments as $appointment)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</td>
                                        <td>{{ $appointment->appointment_type }}</td>
                                        <td>{{ ucfirst($appointment->status) }}</td>
                                        <td>
                    @if($appointment->doctor)
                        {{ $appointment->doctor->first_name ?? '' }} {{ $appointment->doctor->last_name ?? '' }}
                    @else
                        N/A
                    @endif
                </td>                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">No appointment history</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Calendar Section -->
        <div class="calendar-container">
            <h2>Appointment Calendar</h2>
            <div class="calendar-controls">
                <button onclick="changeMonth(-1)">&#8249; Previous</button>
                <span id="calendar-month-year"></span>
                <button onclick="changeMonth(1)">Next &#8250;</button>
            </div>
            <table class="calendar" id="calendar">
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
                    <!-- Calendar will be dynamically generated by JS -->
                </tbody>
            </table>
            <!-- Legend Section -->
            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color" style="background-color: #66ff66;"></div>
                    <div class="legend-label">No Appointments</div>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: #ffcc00;"></div>
                    <div class="legend-label">Pending Appointments</div>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: #ff4d4d;"></div>
                    <div class="legend-label">Confirmed Appointments</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointment Modal -->
    <div id="preview-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closePreviewModal()">&times;</span>
            <h2>Appointments on <span id="preview-date"></span></h2>
            <ul id="appointments-list">
                <!-- Appointments will be dynamically inserted here -->
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // JavaScript code to handle the calendar and appointments

        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();

        document.addEventListener('DOMContentLoaded', function() {
            renderCalendar(currentMonth, currentYear);
        });

        function changeMonth(delta) {
            currentMonth += delta;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            } else if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar(currentMonth, currentYear);
        }

        function renderCalendar(month, year) {
            const calendarBody = document.getElementById('calendar-body');
            calendarBody.innerHTML = ''; // Clear previous calendar

            const firstDay = new Date(year, month, 1).getDay(); // Get the first day of the month
            const daysInMonth = new Date(year, month + 1, 0).getDate(); // Number of days in the month

            // Update the calendar month and year display
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September",
                                "October", "November", "December"];
            document.getElementById('calendar-month-year').innerText = `${monthNames[month]} ${year}`;

            let date = 1;
            for (let i = 0; i < 6; i++) { // 6 rows (weeks)
                const row = document.createElement('tr');
                for (let j = 0; j < 7; j++) { // 7 columns (days)
                    const cell = document.createElement('td');
                    if (i === 0 && j < firstDay) {
                        cell.innerHTML = ''; // Empty cells before the first day
                    } else if (date > daysInMonth) {
                        break; // Exit when exceeding days in the month
                    } else {
                        let day = date; // Capture the current date
                        cell.innerHTML = day;
                        const currentDate = new Date(year, month, day);

                        // Mark today's date
                        const today = new Date();
                        if (currentDate.toDateString() === today.toDateString()) {
                            cell.classList.add('today');
                        }

                        // Only add click event for valid dates
                        if (isValidDate(year, month + 1, day)) {
                            cell.onclick = function () {
                                openPreviewModal(day, month + 1, year); // Pass adjusted month
                            };

                            // Add appointment markers
                            fetchAppointmentMarkers(day, month + 1, year, cell);
                        }

                        row.appendChild(cell);
                        date++;
                    }
                }
                calendarBody.appendChild(row);
            }
        }

        function isValidDate(year, month, day) {
            const date = new Date(year, month - 1, day); // JS months are 0-indexed
            return date.getFullYear() === year && date.getMonth() + 1 === month && date.getDate() === day;
        }

        function fetchAppointmentMarkers(day, month, year, cell) {
            const formattedDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

            fetch(`/student/appointments/by-date?date=${formattedDate}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.appointments && data.appointments.length > 0) {
                    // Check appointment status to determine color
                    const appointmentStatus = data.appointments[0].status.toLowerCase();
                    if (appointmentStatus === 'pending') {
                        cell.style.backgroundColor = '#ffcc00'; // Yellow for pending
                    } else if (appointmentStatus === 'confirmed') {
                        cell.style.backgroundColor = '#ff4d4d'; // Red for confirmed
                    } else {
                        cell.style.backgroundColor = '#66ff66'; // Green for others
                    }
                } else {
                    // If no appointments, mark the cell as green
                    cell.style.backgroundColor = '#66ff66'; // Green for free
                }
            })
            .catch(error => {
                console.error('Error fetching appointment markers:', error);
            });
        }

        const getAppointmentsByDateUrl = `{{ route('student.appointments.by-date') }}`;

        function openPreviewModal(day, month, year) {
            const formattedDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

            document.getElementById('preview-date').innerText = formattedDate;

            fetch(`${getAppointmentsByDateUrl}?date=${formattedDate}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                const appointmentsList = document.getElementById('appointments-list');
                appointmentsList.innerHTML = ''; // Clear previous appointments

                if (data.appointments && data.appointments.length > 0) {
                    data.appointments.forEach(appointment => {
                        // Convert appointment_time to 12-hour AM/PM format
                        const timeString = appointment.appointment_time;
                        const timeFormatted = formatTimeTo12Hour(timeString);

                        const li = document.createElement('li');
                        li.innerHTML = `<p><span>${timeFormatted}</span> - ${appointment.appointment_type} with Dr. ${appointment.doctor_name}</p>`;
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

        // Helper function to format time to 12-hour AM/PM
        function formatTimeTo12Hour(timeString) {
            // Assume timeString is in "HH:mm:ss" or "HH:mm" format
            const [hoursStr, minutesStr] = timeString.split(':');
            let hours = parseInt(hoursStr, 10);
            const minutes = minutesStr;
            const ampm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12 || 12; // Convert '0' to '12' for 12 AM
            return `${hours}:${minutes} ${ampm}`;
        }

        function closePreviewModal() {
            const modal = document.getElementById('preview-modal');
            modal.style.display = 'none';
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closePreviewModal();
            }
        });

    </script>

</x-app-layout>
