<x-app-layout>
<meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css">
    <style>
 body {
        background-color: #f5f7fa;
        font-family: 'Poppins', sans-serif;
    }

    .main-content {
        flex-grow: 1;
        padding: 20px;
        margin-top: 28px;
        margin-left: 80px;
        transition: margin-left 0.3s ease-in-out;
        overflow-y: auto;
    }

    .profile-box {
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
    }

    .profile-box img {
        border-radius: 50%;
        width: 80px;
        height: 80px;
        margin-right: 20px;
    }

    .profile-info {
        display: flex;
        flex-direction: column;
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
    }

    .statistics .stat-box {
        background-color: #ffffff;
        color: #333;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        width: calc(33.33% - 10px);
        margin-bottom: 20px;
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

    .data-table-wrapper {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        width: 100%;
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

    .data-table th,
    .data-table td {
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
    @media screen and (max-width: 768px) {
    .modal-content {
        max-width: 95%; /* Ensures it doesn't take full screen width on mobile */
    }

    .row .form-group {
        width: 100%; /* Stack fields on smaller screens */
    } 
}
.calendar-container {
            width: 90%;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
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
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 1rem;
        }

        .calendar-controls button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
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
            transition: background-color 0.2s ease-in-out; /* Smooth transition */
        }

        .calendar td.active {
            background-color: #007bff;
            color: white;
        }

        .calendar td:hover {
            background-color: #f0f8ff;
        }

        /* Legend Styles */
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
            margin-right: 10px;
        }

        .legend-color.free {
            background-color: #66ff66;
        }

        .legend-color.appointed {
            background-color: #ff4d4d;
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

<div class="main-content">
        <div class="profile-box">
            <img src="{{ asset('images/pilarLogo.jpg') }}" alt="Profile Image">
            <div class="profile-info">
                <h2>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h2>
                <p>Parent of: 
                    @foreach (Auth::user()->students as $student)
                        <span>{{ $student->first_name }} {{ $student->last_name }}</span><br>
                    @endforeach
                </p>
                <!-- No edit profile button for parents -->
            </div>
        </div>

        <div class="statistics">
            <!-- Appointment Count for All Children -->
            <div class="stat-box">
                <img src="https://img.icons8.com/ios-filled/50/000000/appointment-reminders.png" alt="Appointments Icon">
                <h2>{{ $appointmentCount }}</h2>
                <p>Appointments</p>
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
            <div class="stat-box">
                <img src="https://img.icons8.com/ios-filled/50/000000/dental-braces.png" alt="Dental Record Icon">
                @if($hasDentalRecord)
                    <h2>Yes</h2>
                    <p>Dental Record Submitted</p>
                @else
                    <h2>No</h2>
                    <p>No Dental Record Submitted</p>
                @endif
            </div>

            <!-- Medical Record Status -->
            <div class="stat-box">
                <img src="https://img.icons8.com/ios-filled/50/000000/medical-history.png" alt="Medical Record Icon">
                @if($hasMedicalRecord)
                    <h2>Yes</h2>
                    <p>Medical Record Submitted</p>
                @else
                    <h2>No</h2>
                    <p>No Medical Record Submitted</p>
                @endif
            </div>

            <!-- Complaints -->
            <div class="stat-box">
                <img src="https://img.icons8.com/ios-filled/50/000000/complaint.png" alt="Complaints Icon">
                <h2>{{ $complaintCount }}</h2>
                <p>Complaints</p>
            </div>
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
                    <!-- Calendar rows will be dynamically generated here -->
                </tbody>
            </table>
            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color free"></div>
                    <span>Free</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color appointed"></div>
                    <span>Appointed</span>
                </div>
            </div>
        </div>
    </div>

    <script>
       // Global variables and functions
       let currentMonth;
let currentYear;
const csrfToken = document.querySelector('meta[name="csrf-token"]');
// Function to format time to 12-hour AM/PM
function formatTimeTo12Hour(timeString) {
    // Assume timeString is in "HH:mm:ss" or "HH:mm" format
    const [hoursStr, minutesStr] = timeString.split(':');
    let hours = parseInt(hoursStr, 10);
    const minutes = minutesStr;
    const ampm = hours >= 12 ? 'PM' : 'AM';

    hours = hours % 12 || 12; // Convert '0' to '12' for 12 AM
    return `${hours}:${minutes} ${ampm}`;
}

// Function to close the preview modal
function closePreviewModal() {
    const modal = document.getElementById('preview-modal');
    modal.style.display = 'none';
}

// Function to change the month in the calendar
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

// Function to validate date
function isValidDate(year, month, day) {
    const date = new Date(year, month - 1, day); // JS months are 0-indexed
    return date.getFullYear() === year && date.getMonth() + 1 === month && date.getDate() === day;
}

// Function to render the calendar
function renderCalendar(month, year) {
    const calendarBody = document.getElementById('calendar-body');
    calendarBody.innerHTML = ''; // Clear previous calendar

    const firstDay = new Date(year, month).getDay(); // Get the first day of the month
    const daysInMonth = new Date(year, month + 1, 0).getDate(); // Number of days in the month

    const monthYearText = document.getElementById('calendar-month-year');
    const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];
    monthYearText.textContent = `${monthNames[month]} ${year}`;

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

                // Highlight today's date
                const today = new Date();
                if (day === today.getDate() && year === today.getFullYear() && month === today.getMonth()) {
                    cell.classList.add('active');
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

    fetch(`/student/appointments/by-date?date=${formattedDate}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.appointments && data.appointments.length > 0) {
            // If there are appointments, mark the cell as red
            cell.style.backgroundColor = '#ff4d4d'; // Red for appointments
            cell.style.color = '#fff';
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

// Function to open the preview modal
function openPreviewModal(day, month, year) {
    const formattedDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

    document.getElementById('preview-date').innerText = formattedDate;

    fetch(`${getAppointmentsByDateUrl}?date=${formattedDate}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
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

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById("welcomeModal");
    const nextButton = document.getElementById("nextButton");
    const submitButton = document.getElementById("submitButton");
    const welcomeForm = document.getElementById("welcomeForm");
    const welcomeMessage = document.getElementById('welcomeMessage');
    const agreeDisclaimer = document.getElementById("agree_disclaimer");
    const agreeTerms = document.getElementById("agree_terms");
    const profilePreview = document.getElementById('profile_preview');
    const disclaimerSection = document.getElementById('disclaimerSection');

    let formInteraction = false;

    if (!csrfToken) {
        console.error('CSRF token not found in meta tags');
        return;
    }

    // Initialize the calendar
    const today = new Date();
    currentMonth = today.getMonth();
    currentYear = today.getFullYear();
    renderCalendar(currentMonth, currentYear);
        });
        </script>

</x-app-layout>
