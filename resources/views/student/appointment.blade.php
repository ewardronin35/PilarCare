<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
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

        .appointment-list {
            width: 45%;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-in-out;
        }

        .appointment-list h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .appointment-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
        }

        .appointment-table th,
        .appointment-table td {
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
    </style>

    <div class="form-container">
        <!-- Appointment List -->
        <div class="appointment-list">
            <h2>Your Appointments</h2>
            <table class="appointment-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->appointment_date }}</td>
                        <td>{{ $appointment->appointment_time }}</td>
                        <td>{{ $appointment->appointment_type }}</td>
                    </tr>
                    @endforeach
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
        </div>
    </div>

    <!-- Appointment Modal -->
    <div id="appointmentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Appointments on <span id="modal-date"></span></h3>
            <ul id="appointment-list"></ul>
        </div>
    </div>

    <script>
        // Function to render the calendar
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
                        break;
                    } else {
                        cell.innerHTML = date;
                        cell.onclick = function() {
                            fetchAppointments(date, month + 1, year);
                        };
                        fetchAppointmentMarkers(date, month + 1, year, cell); // Add markers
                        row.appendChild(cell);
                        date++;
                    }
                }
                calendarBody.appendChild(row);
            }
        }

        // Fetch and display appointments for the clicked date
        function fetchAppointments(day, month, year) {
    const formattedDate = `${year}-${month}-${day}`;
    fetch(`/appointments/by-date?date=${formattedDate}`)
        .then(response => response.json())
        .then(data => {
            const appointmentList = document.getElementById('appointment-list');
            appointmentList.innerHTML = ''; // Clear previous appointments

            if (data.appointments.length > 0) {
                data.appointments.forEach(appointment => {
                    const li = document.createElement('li');
                    li.textContent = `${appointment.appointment_time} - ${appointment.appointment_type}`;
                    appointmentList.appendChild(li);
                });
            } else {
                appointmentList.innerHTML = '<li>No appointments</li>';
            }

            document.getElementById('modal-date').textContent = formattedDate;
            openModal();
        })
        .catch(error => console.error('Error fetching appointments:', error));
}
        // Add appointment markers on calendar
        function fetchAppointmentMarkers(day, month, year, cell) {
            fetch(`/appointments/by-date?date=${year}-${month}-${day}`)
                .then(response => response.json())
                .then(data => {
                    if (data.appointments.length > 0) {
                        const marker = document.createElement('div');
                        marker.classList.add('appointment-marker');
                        cell.appendChild(marker);
                    }
                })
                .catch(error => console.error('Error fetching appointments:', error));
        }

        // Modal functions
        function openModal() {
            document.getElementById('appointmentModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('appointmentModal').style.display = 'none';
        }

        // Initialize calendar
        const today = new Date();
        const currentMonth = today.getMonth();
        const currentYear = today.getFullYear();
        renderCalendar(currentMonth, currentYear);
    </script>
</x-app-layout>
