<x-app-layout :pageTitle="'Dashboard'">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- SweetAlert2 for Alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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
    width: 95%;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    transition: background-color 0.3s ease-in-out;
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
        .legend-color.pending {
            background-color: #ffc107
            ;
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
        /* Status Text Colors */
.status-approved {
    color: #28a745; /* Green */
    font-weight: bold;
}

.status-pending {
    color: #ffc107; /* Yellow */
    font-weight: bold;
}

.status-no-record {
    color: #dc3545; /* Red */
    font-weight: bold;
}

/* Calendar Cell Status Colors */
.calendar-cell-free {
    background-color: #66ff66; /* Green for Free */
    color: #fff;
}

.calendar-cell-appointed {
    background-color: #ff4d4d; /* Red for Appointed */
    color: #fff;
}

.calendar-cell-pending {
    background-color: #ffc107; /* Yellow for Pending */
    color: #fff;
}

    
    </style>

<div class="main-content">
        <!-- Profile Box -->
        <div class="profile-box">
            <img src="{{ asset('images/pilarLogo.jpg') }}" alt="Profile Image">
            <div class="profile-info">
    <h2>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h2>

    @if(strtolower(Auth::user()->role) === 'parent')
        <p>Parent of:</p>
        <ul>
            @forelse($studentsStatus as $studentStatus)
                <li>
                    <strong>{{ $studentStatus['name'] }}</strong>
                </li>
            @empty
                <li>No children associated.</li>
            @endforelse
        </ul>
    @endif

    <a href="{{ route('profile.edit') }}" class="edit-profile-btn">Edit Profile</a>
</div>
</div>
        <!-- Statistics Section -->
        <div class="statistics">
            <div class="stat-box">
                <a href="{{ route('parent.appointment') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/appointment-reminders.png" alt="Appointments Icon">
                    <h2>{{ $appointmentCount }}</h2>
                    <p>Appointments</p>
                </a>
            </div>
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

            <!-- Medical Record Stat Box -->
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
            <div class="stat-box">
                <a href="{{ route('student.complaint') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/complaint.png" alt="Complaints Icon">
                    <h2>{{ $complaintCount }}</h2>
                    <p>Complaints</p>
                </a>
            </div>
        </div>

        <!-- Calendar Section -->
        <div class="calendar-container">
            <h2>Appointment Calendar</h2>
            <div class="calendar-controls">
            <button id="previousMonthButton">&#8249; Previous</button>
<span id="calendar-month-year"></span>
<button id="nextMonthButton">Next &#8250;</button>

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
            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color free"></div>
                    <span>Free</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color appointed"></div>
                    <span>Appointed</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color pending"></div>
                    <span>Pending</span>
                </div>
            </div>
        </div>

        <!-- Appointment Modal -->
        <div id="preview-modal" class="modal">
            <div class="modal-content">
                <span class="close" id="closePreviewModal">&times;</span>
                <h2>Appointments on <span id="preview-date"></span></h2>
                <div class="appointments-container">
                    <ul id="appointments-list">
                        <!-- Appointments will be dynamically inserted here -->
                    </ul>
                </div>
            </div>
        </div>

        <!-- Welcome Modal -->
        <div id="welcomeModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div id="welcomeMessage" class="welcome-message show">
                    <img src="{{ asset('images/pilarLogo.jpg') }}" alt="PilarCare Logo" width="100">
                    <h2>Welcome to PilarCare, {{ Auth::user()->first_name }}!</h2>
                </div>
                <div class="disclaimer" id="disclaimerSection">
                    <h3>Data Privacy Disclaimer</h3>
                    <p>
                        In compliance with the Data Privacy Act of 2012, all gathered data from the participant will be treated with utmost confidentiality to protect the participant’s/respondent’s privacy.
                    </p>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="agree_disclaimer" name="agree_disclaimer" required>
                            I have read and understood the above statement. I agree to participate voluntarily in this research without any force.
                        </label>
                    </div>
                    <p>After agreeing to the terms, you will proceed to view your dashboard.</p>
                    <button type="button" class="next-button" id="nextButton" disabled>
                        Next <span class="arrow">&rarr;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Define closePreviewModal in the global scope
        function closePreviewModal() {
            const modal = document.getElementById('preview-modal');
            if (modal) {
                modal.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {

            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

            // Validation functions
            function validateLetters(input) {
                setTimeout(() => {
                    input.value = input.value.replace(/[^A-Za-z\s]/g, '');
                }, 1);
            }

            function validateNumbers(input) {
                setTimeout(() => {
                    input.value = input.value.replace(/[^0-9]/g, '');
                }, 1);
            }

            // Function to format time to 12-hour AM/PM
            function formatTimeTo12Hour(timeString) {
                const [hoursStr, minutesStr] = timeString.split(':');
                let hours = parseInt(hoursStr, 10);
                const minutes = minutesStr;
                const ampm = hours >= 12 ? 'PM' : 'AM';

                hours = hours % 12 || 12;
                return `${hours}:${minutes} ${ampm}`;
            }

            // Function to change the month in the calendar
            let currentMonth = new Date().getMonth();
            let currentYear = new Date().getFullYear();

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

    const previousMonthButton = document.getElementById('previousMonthButton');
    const nextMonthButton = document.getElementById('nextMonthButton');

    if (previousMonthButton) {
        previousMonthButton.addEventListener('click', function() {
            changeMonth(-1);
        });
    }

    if (nextMonthButton) {
        nextMonthButton.addEventListener('click', function() {
            changeMonth(1);
        });
    }
    function isValidDate(year, month, day) {
    const date = new Date(year, month - 1, day); // Months are zero-indexed in JavaScript
    return date.getFullYear() === year && date.getMonth() + 1 === month && date.getDate() === day;
}
            // Function to render the calendar
            function renderCalendar(month, year) {
    const calendarBody = document.getElementById('calendar-body');
    if (!calendarBody) return;
    calendarBody.innerHTML = ''; // Clear previous calendar

    const firstDay = new Date(year, month).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

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
                cell.innerHTML = ''; // Empty cells after the last day
            } else {
                let day = date;
                cell.innerHTML = day;
                const currentDate = new Date(year, month, day);

                if (isValidDate(year, month + 1, day)) {
                    cell.onclick = function () {
                        openPreviewModal(day, month + 1, year);
                    };

                    // Add appointment markers
                    fetchAppointmentMarkers(day, month + 1, year, cell);
                }

                // Highlight today's date
                const today = new Date();
                if (day === today.getDate() && year === today.getFullYear() && month === today.getMonth()) {
                    cell.classList.add('active');
                }

                date++;
            }
            // Append the cell to the row after processing
            row.appendChild(cell);
        }
        calendarBody.appendChild(row);
    }
}


            // Fetch and mark appointments
            function fetchAppointmentMarkers(day, month, year, cell) {
                const formattedDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                // Determine user role
                const userRole = '{{ strtolower(Auth::user()->role) }}';

                let fetchUrl = '';
                if (userRole === 'parent') {
                    fetchUrl = `/parent/appointments/by-date?date=${formattedDate}`;
                } else {
                    fetchUrl = `/student/appointments/by-date?date=${formattedDate}`;
                }

                fetch(fetchUrl, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Server responded with status ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    // Remove existing marker classes to prevent duplication
                    cell.classList.remove('calendar-cell-free', 'calendar-cell-appointed', 'calendar-cell-pending');
                    cell.innerHTML = day;

                    if (data.appointments && data.appointments.length > 0) {
                        data.appointments.forEach(appointment => {
                            const status = appointment.status.toLowerCase();

                            if (status === 'approved') {
                                cell.classList.add('calendar-cell-appointed');
                                cell.innerHTML += ' <span class="status-approved">(Approved)</span>';
                            } else if (status === 'pending') {
                                cell.classList.add('calendar-cell-pending');
                                cell.innerHTML += ' <span class="status-pending">(Pending)</span>';
                            }
                        });
                    } else {
                        // If no appointments, mark the cell as free
                        cell.classList.add('calendar-cell-free');
                        cell.innerHTML += ' <span class="status-free">(Free)</span>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching appointment markers:', error);
                });
            }

            // Function to open the preview modal
            function openPreviewModal(day, month, year) {
                const formattedDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                document.getElementById('preview-date').innerText = formattedDate;

                const userRole = '{{ strtolower(Auth::user()->role) }}';
                let fetchUrl = '';

                if (userRole === 'parent') {
                    fetchUrl = `/parent/appointments/by-date?date=${formattedDate}`;
                } else {
                    fetchUrl = `/student/appointments/by-date?date=${formattedDate}`;
                }

                fetch(fetchUrl, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Server responded with status ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const appointmentsList = document.getElementById('appointments-list');
                    if (!appointmentsList) return;
                    appointmentsList.innerHTML = ''; // Clear previous appointments

                    if (data.appointments && data.appointments.length > 0) {
                        data.appointments.forEach(appointment => {
                            const timeFormatted = formatTimeTo12Hour(appointment.appointment_time);

                            const li = document.createElement('li');
                            li.innerHTML = `<p><span>${timeFormatted}</span> - ${appointment.child_name} - ${appointment.appointment_type} with Dr. ${appointment.doctor_name}</p>`;
                            appointmentsList.appendChild(li);
                        });
                    } else {
                        const li = document.createElement('li');
                        li.innerText = userRole === 'parent' ? 'No appointments for your child.' : 'No appointments for this day.';
                        appointmentsList.appendChild(li);
                    }

                    const modal = document.getElementById('preview-modal');
                    if (modal) {
                        modal.style.display = 'flex'; // Show the modal
                    }
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

            // Function to handle the welcome modal
            const welcomeModal = document.getElementById("welcomeModal");
            const nextButton = document.getElementById("nextButton");
            const welcomeMessage = document.getElementById('welcomeMessage');
            const agreeDisclaimer = document.getElementById("agree_disclaimer");
            const disclaimerSection = document.getElementById('disclaimerSection');

            if (welcomeModal && nextButton && agreeDisclaimer) {
                // Agree to disclaimer
                agreeDisclaimer.addEventListener('change', function() {
                    nextButton.disabled = !this.checked;
                });

                // Countdown before enabling the next button and closing the modal
                nextButton.addEventListener('click', function() {
                    let countdown = 5; // Reduced countdown time for better UX
                    nextButton.disabled = true;
                    nextButton.innerHTML = `Please wait ${countdown}s`;

                    const countdownInterval = setInterval(() => {
                        countdown--;
                        nextButton.innerHTML = `Please wait ${countdown}s`;

                        if (countdown === 0) {
                            clearInterval(countdownInterval);
                            nextButton.innerHTML = `Next <span class="arrow">&rarr;</span>`;
                            nextButton.disabled = false;

                            // Hide welcome message and close the modal
                            if (welcomeMessage) welcomeMessage.style.display = 'none';
                            if (disclaimerSection) disclaimerSection.style.display = 'none';
                            welcomeModal.style.display = 'none';

                            // Optional: Show a success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Welcome!',
                                text: 'You have successfully viewed your dashboard.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    }, 1000);
                });

                // Close modal when clicking on the close button
                const closeButtons = welcomeModal.querySelectorAll('.close');
                closeButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        welcomeModal.style.display = 'none';
                    });
                });

                // Close modal when clicking outside the modal content
                window.addEventListener('click', function(event) {
                    if (event.target === welcomeModal) {
                        welcomeModal.style.display = 'none';
                    }
                });
            }

            // Close Preview Modal when clicking the close button
            const closePreviewButtons = document.querySelectorAll('#preview-modal .close');
            closePreviewButtons.forEach(button => {
                button.addEventListener('click', closePreviewModal);
            });

            // Initialize the calendar
            renderCalendar(currentMonth, currentYear);
        });
    </script>
</x-app-layout>
