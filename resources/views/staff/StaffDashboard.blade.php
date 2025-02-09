<x-app-layout :pageTitle="'Dashboard'">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- External CSS Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css">

    <!-- Internal CSS Styles -->
    <style>
        /* General Styles */
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .main-content {
    display: flex;
    flex-direction: column;
    overflow: hidden; /* Prevent page scrolling */
    padding: 20px;
    margin-left: 80px;
    margin-top: 28px; /* Adjust top margin to align with header */
    width: calc(100% - 80px);

}
   /* Modal Styling */
   .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
        border: 1px solid #888;
        max-width: 700px; /* Reduced max width to better fit the screen */
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        gap: 15px; /* Reduced gap between elements */
        animation: fadeInUp 0.5s ease-in-out;
        align-items: center; /* Center the image at the top */
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
    }

    /* Profile Picture Section */
    .profile-picture-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        margin-bottom: 10px; /* Reduced bottom margin */
        position: relative;
    }

    .profile-picture-container img {
        border-radius: 50%;
        width: 80px; /* Reduced width of the profile picture */
        height: 80px;
        object-fit: cover;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 10px;
    }

    .profile-picture-container input[type="file"] {
        display: none;
    }

    .profile-picture-container label {
        display: inline-block;
        background-color: #007bff;
        color: white;
        padding: 6px 12px; /* Reduced padding for the label */
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9rem;
        text-align: center;
        transition: background-color 0.3s;
    }

    .profile-picture-container label:hover {
        background-color: #0056b3;
    }

    /* Form Group Styling */
    .form-group {
        width: 100%;
        margin-top: 15px; /* Reduced top margin */
        display: flex;
        flex-direction: column;
        gap: 5px; /* Reduced gap */
    }

    .form-group label {
        font-weight: bold;
        color: #333;
    }

    .form-group input[type="text"],
    .form-group input[type="date"],
    .form-group input[type="file"],
    .form-group input[type="number"],
    .form-group textarea {
        padding: 8px; /* Reduced padding */
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 100%;
        box-sizing: border-box;
        font-size: 0.9rem; /* Adjust font size */
        font-family: 'Poppins', sans-serif;
    }

    .form-group input[type="checkbox"] {
        margin-right: 10px;
    }

    .row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .row .form-group {
        width: 48%; /* Reduced width to fit */
    }

    .optional-sibling {
        display: none;
    }

    .next-button {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        margin-top: 10px;
        display: flex;
        align-items: center;
        font-family: 'Poppins', sans-serif;
        justify-content: center;
        gap: 10px;
    }

    .next-button:hover {
        background-color: #0056b3;
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
    .submit-button {
        background-color: #0056b3;
        color: white;
        padding: 8px 15px; /* Adjusted padding */
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .submit-button:hover {
        background-color: #003f88;
    }

    /* Disclaimer and Medicines Group */
    .disclaimer {
        margin-bottom: 15px; /* Reduced bottom margin */
        text-align: justify;
        font-size: 14px;
        color: #555;
    }

    .medicines-group {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .medicines-group label {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Miscellaneous styles for hover effects and interactions */
    .arrow {
        display: inline-block;
        transition: transform 0.3s;
    }

    .arrow:hover {
        transform: translateX(5px);
    }

    .welcome-message {
        text-align: center;
        font-size: 1.8em;
        margin-bottom: 20px;
    }
        /* Profile Box */
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

        /* Statistics Section */
        .statistics {
            display: flex;
            justify-content: flex-start; /* Align items to the left */
            margin-top: 20px;
            gap: 10px;
            flex-wrap: wrap;
        }
        .container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px; /* Space between calendar and stat-box container */
    margin-top: 20px; /* Adds some spacing between profile section and calendar/statistics section */
}
.stat-box-container {
    display: grid;
    grid-template-columns: 1fr 1fr; /* Two stat boxes per row */
    gap: 20px; /* Space between stat boxes */
    width: 49%; /* Adjust width to leave space for the calendar */
}
.stat-box {
    background-color: #ffffff;
    color: #333;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease-in-out;
}


        .stat-box:hover {
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

        /* Content Row for Chart and Calendar */
        .content-row {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            gap: 10px;
            flex-wrap: wrap;
        }

         .calendar-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            width: 49%;
            box-sizing: border-box;
        }

        .chart-container h3, .calendar-container h2 {
            margin-top: 0;
            color: #0056b3;
        }

        /* Calendar Specific Styles */
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
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6); /* Dark background with opacity */
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            margin: 5% auto; /* Reduced top margin for better positioning */
            padding: 30px 40px;
            border-radius: 12px;
            max-width: 600px;
            width: 90%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 0.5s ease-in-out;
            overflow-y: auto;
            max-height: 80%;
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

        /* Legend Colors */
        .legend-color.free {
            background-color: #28a745; /* Green */
        }

        .legend-color.pending {
            background-color: #ffc107; /* Yellow */
        }

        .legend-color.confirmed {
            background-color: #dc3545; /* Red */
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
        #guardian_relationship {
        width: 100%;
        padding: 8px; /* Adjust padding for the dropdown */
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 0.9rem; /* Adjust font size */
        font-family: 'Poppins', sans-serif;
        background-color: #fff;
        color: #333;
        appearance: none; /* Remove default arrow */
        position: relative;
        transition: border-color 0.3s ease;
    }

    #guardian_relationship:hover {
        border-color: #007bff; /* Hover effect */
    }

    #guardian_relationship:focus {
        outline: none;
        border-color: #0056b3; /* Focus border color */
    }

    /* Add custom arrow for dropdown */
    #guardian_relationship::after {
        content: '\25BC'; /* Unicode for down arrow */
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #007bff;
        pointer-events: none;
    }
    </style>

  <!-- Main Content -->
<div class="main-content">
    <!-- Profile Section -->
    <div class="profile-box">
        <img src="{{ asset('images/pilarLogo.jpg') }}" alt="Profile Image">
        <div class="profile-info">
        <h2>
    @if(Auth::check())
        @if(Auth::user()->role === 'Staff')
            @if(Auth::user()->staff)
                {{ Auth::user()->staff->first_name ?? 'First Name Missing' }} {{ Auth::user()->staff->last_name ?? 'Last Name Missing' }}
            @else
                {{ 'Staff data not found for this user.' }}
            @endif
        @else
            {{ 'User role is not staff. Role is: ' . Auth::user()->role }}
        @endif
    @else
        {{ 'User not authenticated' }}
    @endif
</h2>
            <p>{{ Auth::user()->role }}</p>
        </div>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Container for Statistics and Calendar -->
    <div class="container">
        <!-- Calendar on the left -->
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

        <!-- Statistics on the right -->
        <div class="stat-box-container">
            <!-- Appointments Count -->
            <div class="stat-box">
                <a href="{{ route('staff.appointment') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/appointment-reminders.png" alt="Appointments Icon">
                    <h2>{{ $appointmentCount }}</h2>
                    <p>Appointments</p>
                </a>
            </div>

            <!-- Health Record Status -->
            <div class="stat-box">
                <img src="https://img.icons8.com/ios-filled/50/000000/medical-doctor.png" alt="Health Record Icon">
                @if($hasHealthExamination)
                    <h2>Yes</h2>
                    <p>Health Record Submitted</p>
                @else
                    <h2>No</h2>
                    <p>No Health Record Submitted</p>
                @endif
            </div>

            <!-- Dental Record Status -->
          

            <!-- Complaints Count -->
            <div class="stat-box">
                <a href="{{ route('staff.complaint') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/complaint.png" alt="Complaints Icon">
                    <h2>{{ $complaintCount }}</h2>
                    <p>Complaints</p>
                </a>
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

        
    </div>

    <!-- External JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    const routes = {
        getAppointmentsByMonth: "{{ route('staff.appointments.by-month') }}",
        getAppointmentsByDate: "{{ route('staff.appointments.by-date') }}",
    };
</script>

    <!-- Internal JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Chart.js

         
            // Initialize Calendar
            renderCalendar(currentMonth, currentYear);
        });

        // Global Variables for Calendar
        const currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();

        /**
         * Render Calendar Function
         */  function openPreviewModal(selectedDate, month, year) {
            // Construct the date string in 'YYYY-MM-DD' format.
            const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(selectedDate).padStart(2, '0')}`;
            // Update the modal's date display.
            document.getElementById('preview-date').textContent = dateString;
            // Set a loading message while fetching appointments.
            const appointmentsList = document.getElementById('appointments-list');
            appointmentsList.innerHTML = '<li>Loading...</li>';

            // Fetch appointments for the selected date.
            fetch(`${routes.getAppointmentsByDate}?date=${dateString}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })

                .then(data => {
                    appointmentsList.innerHTML = '';
                    if (data.appointments && data.appointments.length > 0) {
                        data.appointments.forEach(appointment => {
                            const li = document.createElement('li');
                            li.innerHTML = `<p>
                                <strong>Grade:</strong> ${appointment.grade_or_course}<br>
                                <strong>Section:</strong> ${appointment.section}<br>
                                <strong>Time:</strong> ${appointment.appointment_time}<br>
                                <strong>Type:</strong> ${appointment.appointment_type}<br>
                                <strong>Status:</strong> ${appointment.status}<br>
                                <strong>Doctor:</strong> ${appointment.doctor_name}
                            </p>`;
                            appointmentsList.appendChild(li);
                        });
                    } else {
                        appointmentsList.innerHTML = '<li>No appointments found for this date.</li>';
                    }
                    // Display the modal.
                    document.getElementById('preview-modal').style.display = 'flex';
                })
                .catch(error => {
                    console.error('Error fetching appointments:', error);
                    appointmentsList.innerHTML = `<li>Error fetching appointments: ${error.message}</li>`;
                    document.getElementById('preview-modal').style.display = 'flex';
                });
        }

        /**
         * CLOSE PREVIEW MODAL
         */
        function closePreviewModal() {
            document.getElementById('preview-modal').style.display = 'none';
        }
       

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
                    // Convert appointment_date into a Date object
                    const appDate = new Date(appointment.appointment_date);
                    // Format as "YYYY-MM-DD"
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
                // Optionally, you can append empty cells if you want a complete row.
                cell.appendChild(document.createTextNode(''));
            } else {
                let selectedDate = date;
                cell.textContent = selectedDate;

                // Format dateString as 'YYYY-MM-DD'
                const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(selectedDate).padStart(2, '0')}`;

                // Determine the class based on appointment statuses
                if (appointmentsByDate[dateString]) {
                    const appointments = appointmentsByDate[dateString];
                    let hasConfirmed = appointments.some(app =>
                        app.status && app.status.toLowerCase().trim() === 'confirmed'
                    );
                    let hasPending = appointments.some(app =>
                        app.status && app.status.toLowerCase().trim() === 'pending'
                    );

                    if (hasConfirmed) {
                        cell.classList.add('red'); // Confirmed appointments
                    } else if (hasPending) {
                        cell.classList.add('yellow'); // Pending appointments
                    }
                } else {
                    cell.classList.add('green'); // Free date
                }

                // Add click event to open the preview modal
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
                date++;
            }
            row.appendChild(cell);
        }
        // Append the complete row once after processing all cells
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
         * Close Preview Modal Function
         */
        
        /**
         * Profile Picture Preview
         */
        
        // Event listener for profile picture input
        
        /**
         * Validation for Philippine mobile number standard (09XXXXXXXXX)
         */
        

        /**
         * Form Interaction and Submission Handling
         */
        document.addEventListener('DOMContentLoaded', function() {
        


            // Initialize the calendar
            const today = new Date();
            currentMonth = today.getMonth();
            currentYear = today.getFullYear();
            renderCalendar(currentMonth, currentYear);

            // Agree to disclaimer
          
            // Countdown before enabling the next button and showing the form
            

           
                });

                // Form submission with AJAX
             
                
            

          
        
    </script>
</x-app-layout>
