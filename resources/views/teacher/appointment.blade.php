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
                margin: 20px;
                gap: 20px;
            }

            .appointment-list, .history-list {
                width: 45%;
                background-color: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                animation: fadeInUp 0.5s ease-in-out;
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
            }

            .calendar-container h2 {
                text-align: center;
                margin-bottom: 20px;
            }

            .calendar {
                width: 100%;
                border-collapse: collapse;
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
            }

            .calendar td.active {
                background-color: #007bff;
                color: white;
            }

            .calendar td:hover {
                background-color: #0056b3;
                color: white;
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
            }

            .close {
                color: #aaa;
                font-size: 28px;
                font-weight: bold;
                cursor: pointer;
            }

            .close:hover {
                color: black;
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

            /* Modal Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-50px);
    }
}

.modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 12px;
            max-width: 600px;
            width: 90%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.4s ease-in-out;
            overflow-y: auto;
            max-height: 80%;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
            }
            to {
                transform: translateY(0);
            }
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
        </style>

        <div class="form-container">
            <!-- Appointment List (Upcoming) -->

<div class="appointment-list">
    <h2>Your Upcoming Appointments</h2>
    <table class="appointment-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Type</th>
                <th>Status</th>
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
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4">No upcoming appointments</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

            <!-- Calendar Section -->
            <div class="calendar-container">
                <h2>Appointment Calendar</h2>
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
                <div style="display: flex; gap: 20px;">
        <div style="display: flex; align-items: center;">
            <div style="width: 20px; height: 20px; background-color: #66ff66; margin-right: 10px;"></div>
            <span>Free</span>
        </div>
        <div style="display: flex; align-items: center;">
            <div style="width: 20px; height: 20px; background-color: #ff4d4d; margin-right: 10px;"></div>
            <span>Appointed</span>
        </div>
            </div>
        </div>
<!-- Legend Section -->

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


        <!-- Appointment History -->
        <div class="history-list">
            <h2>Appointment History</h2>
            <table class="appointment-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($completedAppointments as $appointment)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</td>
                            <td>{{ $appointment->appointment_type }}</td>
                            <td>{{ ucfirst($appointment->status) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('keydown', function(event) {
    if (event.key === "Escape") {
        closePreviewModal();
    }
});

function isValidDate(year, month, day) {
            const date = new Date(year, month - 1, day); // JS months are 0-indexed
            return date.getFullYear() === year && date.getMonth() + 1 === month && date.getDate() === day;
        }

        function renderCalendar(month, year) {
            const calendarBody = document.getElementById('calendar-body');
            calendarBody.innerHTML = ''; // Clear previous calendar

            const firstDay = new Date(year, month).getDay(); // Get the first day of the month
            const daysInMonth = new Date(year, month + 1, 0).getDate(); // Number of days in the month

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

        // Fetch and mark appointments
        function fetchAppointmentMarkers(day, month, year, cell) {
            const formattedDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

            fetch(`/teacher/appointments/by-date?date=${formattedDate}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.appointments && data.appointments.length > 0) {
                    // If there are appointments, mark the cell as red
                    cell.style.backgroundColor = '#ff4d4d'; // Red for appointments
                } else {
                    // If no appointments, mark the cell as green
                    cell.style.backgroundColor = '#66ff66'; // Green for free
                }
            })
            .catch(error => {
                console.error('Error fetching appointment markers:', error);
            });
        }

        const getAppointmentsByDateUrl = `{{ route('teacher.appointments.by-date') }}`;

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

        // Initialize calendar on page load
        const today = new Date();
        const currentMonth = today.getMonth();
        const currentYear = today.getFullYear();
        renderCalendar(currentMonth, currentYear);
        </script>

    </x-app-layout>
