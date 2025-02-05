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

        <!-- Welcome Modal (Optional) -->
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
                    <p>After agreeing to the terms, you will proceed to fill out your profile information.</p>
                    <button type="button" class="next-button" id="nextButton" disabled>
                        Next <span class="arrow">&rarr;</span>
                    </button>
                </div>

                <form id="welcomeForm" action="{{ route('staff.profile.store') }}" method="POST" enctype="multipart/form-data" style="display: none;">
                    @csrf
                    <input type="hidden" id="guardian_first_name" name="guardian_first_name" value="">
    <input type="hidden" id="guardian_last_name" name="guardian_last_name" value="">
    <input type="hidden" id="guardian_relationship" name="guardian_relationship" value="">

                    <div class="profile-picture-container">
                        <img id="profile_preview" class="profile-pic-preview" src="{{ asset('images/default-profile.png') }}" alt="Profile Picture Preview" style="display: none;">
                        <label for="profile_picture" class="profile-picture-label"><i class="fas fa-camera"></i> Change Profile Picture</label>
                        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required onchange="previewImage(event)">
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="parent_name_father"><i class="fas fa-user"></i> Father's Name</label>
                            <input type="text" id="parent_name_father" name="parent_name_father" required 
                                oninput="validateLetters(this)" onblur="validateLetters(this)" onpaste="validateLetters(this)" pattern="[A-Za-z\s]+" title="Letters only">
                        </div>
                        <div class="form-group">
                            <label for="parent_name_mother"><i class="fas fa-user"></i> Mother's Name</label>
                            <input type="text" id="parent_name_mother" name="parent_name_mother" required 
                                oninput="validateLetters(this)" onblur="validateLetters(this)" onpaste="validateLetters(this)" pattern="[A-Za-z\s]+" title="Letters only">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="birthdate"><i class="fas fa-calendar-alt"></i> Your Birthdate</label>
                            <input type="date" id="birthdate" name="birthdate" required>
                        </div>
                        <div class="form-group">
                            <label for="address"><i class="fas fa-map-marker-alt"></i> Address</label>
                            <textarea id="address" name="address" rows="2" required></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="emergency_contact_number"><i class="fas fa-phone-alt"></i> Emergency Contact Number</label>
                            <input type="text" id="emergency_contact_number" name="emergency_contact_number" required 
                                maxlength="11" placeholder="09123456789"
                                oninput="validatePhilippineNumber(this)" 
                                pattern="09\d{9}" title="Must be 11 digits starting with 09">
                        </div>
                        <div class="form-group">
                            <label for="personal_contact_number"><i class="fas fa-phone"></i> Personal Contact Number</label>
                            <input type="text" id="personal_contact_number" name="personal_contact_number" required 
                                maxlength="11" placeholder="09123456789"
                                oninput="validatePhilippineNumber(this)" 
                                pattern="09\d{9}" title="Must be 11 digits starting with 09">
                        </div>
                    </div>

                   
                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="agree_terms" name="agree_terms" required>
                            I agree to the terms and conditions.
                        </label>
                    </div>

                    <input type="hidden" name="id_number" value="{{ Auth::user()->id_number }}">

                    <button type="submit" class="submit-button" id="submitButton" disabled>
                        Submit <span class="arrow">&rarr;</span>
                    </button>
                </form>
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
     <!-- Internal JavaScript -->
     <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Chart.js

        const parentNameFatherInput = document.getElementById('parent_name_father');
        const parentNameMotherInput = document.getElementById('parent_name_mother');

     
        if (parentNameFatherInput) {
            parentNameFatherInput.addEventListener('blur', function() {
                capitalizeWords(this);
            });
        }

        if (parentNameMotherInput) {
            parentNameMotherInput.addEventListener('blur', function() {
                capitalizeWords(this);
            });
        }
            // Initialize Calendar
            renderCalendar(currentMonth, currentYear);
        });

        // Global Variables for Calendar
        const currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const profilePreview = document.getElementById('profile_preview');

        /**
         * Render Calendar Function
         */
        function capitalizeWords(input) {
        let words = input.value.split(' ');
        for (let i = 0; i < words.length; i++) {
            if (words[i].length > 0) {
                words[i] = words[i][0].toUpperCase() + words[i].substr(1).toLowerCase();
            }
        }
        input.value = words.join(' ');
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

                        row.appendChild(cell);
                        date++;
                    }
                    calendarBody.appendChild(row);
                }
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
            const formattedDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

            document.getElementById('preview-date').innerText = formattedDate;

            fetch(`/staff/appointments/by-date?date=${formattedDate}`)
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
        function closePreviewModal() {
            const modal = document.getElementById('preview-modal');
            modal.style.display = 'none';
        }

        /**
         * Profile Picture Preview
         */
        function previewImage(event) {
            const input = event.target; // Access the input element
            const profilePreview = document.getElementById('profile_preview'); // Define it here

            const reader = new FileReader();
            reader.onload = function() {
                profilePreview.src = reader.result;
                profilePreview.style.display = 'block';
            }
            if (input.files && input.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }

        // Event listener for profile picture input
        const profilePictureInput = document.getElementById('profile_picture');
        if (profilePictureInput) {
            profilePictureInput.addEventListener('change', previewImage);
        }

        /**
         * Validation for letters only in names
         */
        function validateLetters(input) {
            setTimeout(() => {
                input.value = input.value.replace(/[^A-Za-z\s]/g, '');
            }, 1);
        }

        /**
         * Validation for Philippine mobile number standard (09XXXXXXXXX)
         */
        function validatePhilippineNumber(input) {
            setTimeout(() => {
                input.value = input.value.replace(/[^0-9]/g, ''); // Allow only numbers
                if (input.value.length > 11) { // Limit to 11 digits
                    input.value = input.value.slice(0, 11);
                }
            }, 1);
        }

        /**
         * Form Interaction and Submission Handling
         */
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

            // Initialize the calendar
            const today = new Date();
            currentMonth = today.getMonth();
            currentYear = today.getFullYear();
            renderCalendar(currentMonth, currentYear);

            // Agree to disclaimer
            if (agreeDisclaimer && nextButton) {
                agreeDisclaimer.addEventListener('change', function() {
                    nextButton.disabled = !this.checked;
                });
            }

            // Countdown before enabling the next button and showing the form
            if (nextButton) {
                nextButton.addEventListener('click', function() {
                    let countdown = 3;
                    nextButton.disabled = true;
                    nextButton.innerHTML = `Please wait ${countdown}s`;

                    const countdownInterval = setInterval(() => {
                        countdown--;
                        nextButton.innerHTML = `Please wait ${countdown}s`;

                        if (countdown === 0) {
                            clearInterval(countdownInterval);
                            nextButton.innerHTML = `Next <span class="arrow">&rarr;</span>`;
                            nextButton.disabled = false;

                            // Hide welcome message and show form
                            welcomeMessage.style.display = 'none';
                            disclaimerSection.style.display = 'none';
                            welcomeForm.style.display = 'block';

                            // Animate the form coming into view
                            welcomeForm.style.opacity = 0;
                            welcomeForm.style.transform = 'translateY(20px)';
                            setTimeout(() => {
                                welcomeForm.style.transition = 'opacity 0.5s, transform 0.5s';
                                welcomeForm.style.opacity = 1;
                                welcomeForm.style.transform = 'translateY(0)';
                            }, 100);
                        }
                    }, 1000);
                });
            }

            // Attach event listeners to form inputs to detect interaction
            if (welcomeForm) {
                const inputs = welcomeForm.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    input.addEventListener('input', () => {
                        formInteraction = true;
                        enableBeforeUnload(); // Enable the prompt after user interaction
                    });
                });

                // Form submission with AJAX
                welcomeForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    // Disable the beforeunload prompt before form submission
                    disableBeforeUnload();

                    const formData = new FormData(welcomeForm);

                    Swal.fire({
                        title: 'Submit Profile',
                        text: "Are you sure you want to submit your profile information?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#00d1ff',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, submit it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading spinner
                            Swal.fire({
                                title: 'Submitting...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            fetch('{{ route('staff.profile.store') }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                                }
                            })
                            .then(response => {
                                if (response.status === 422) {
                                    // Validation error
                                    return response.json().then(data => {
                                        throw data;
                                    });
                                }
                                return response.json();
                            })
                            .then(data => {
                                Swal.close(); // Close the loading spinner

                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                    }).then(() => {
                                        modal.style.display = 'none';
                                        location.reload(); // Reload the page after successful submission
                                    });
                                } else {
                                    // SweetAlert error message
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: data.message || 'There was an error updating your profile.',
                                    });
                                }
                            })
                            .catch(errorData => {
    Swal.close(); // Close the loading spinner

    if (errorData.errors) {
        let errorMessages = '';
        if (Array.isArray(errorData.errors)) {
            // Handle errors returned as an array
            errorData.errors.forEach(message => {
                errorMessages += `${message}<br>`;
            });
        } else {
            // Handle errors returned as an object
            for (const [field, messages] of Object.entries(errorData.errors)) {
                if (Array.isArray(messages)) {
                    const formattedField = field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    errorMessages += `<strong>${formattedField}:</strong> ${messages.join('<br>')}<br><br>`;
                } else {
                    console.error(`Unexpected validation error format for field ${field}:`, messages);
                }
            }
        }

        Swal.fire({
            icon: 'error',
            title: 'Validation Errors',
            html: errorMessages,
            confirmButtonText: 'OK'
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: errorData.message || 'An unexpected error occurred.',
        });
                                }
                            });
                        }
                    });
                });
            }

            // Profile Picture Preview Function
            function previewImage(event) {
                const input = event.target; // Access the input element
                const reader = new FileReader();
                reader.onload = function() {
                    profilePreview.src = reader.result;
                    profilePreview.style.display = 'block';
                }
                if (input.files && input.files[0]) {
                    reader.readAsDataURL(event.target.files[0]);
                }
            }

            // Show modal if $showModal is true
            if (modal) {
                if ({{ json_encode($showModal) }}) {
                    modal.style.display = 'flex';
                }

                const closeModal = document.querySelector('#welcomeModal .close');
                if (closeModal) {
                    closeModal.addEventListener('click', function() {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Incomplete Form',
                            text: 'You need to complete the form before proceeding.',
                        });
                    });
                }

                window.addEventListener('click', function(event) {
                    if (event.target === modal) {
                        event.preventDefault();
                    }
                });

                if (agreeDisclaimer && nextButton) {
                    agreeDisclaimer.addEventListener('change', function() {
                        nextButton.disabled = !this.checked;
                    });
                }
            }

            // Terms and conditions agreement before enabling the submit button
            if (agreeTerms && submitButton) {
                agreeTerms.addEventListener('change', function() {
                    submitButton.disabled = !this.checked;
                });
            }
        });

        /**
         * Enable the beforeunload prompt
         */
        function enableBeforeUnload() {
            window.addEventListener('beforeunload', preventFormClose);
        }

        /**
         * Disable the beforeunload prompt
         */
        function disableBeforeUnload() {
            window.removeEventListener('beforeunload', preventFormClose);
        }

        /**
         * Function to prevent form close
         */
        function preventFormClose(event) {
            event.preventDefault();
            event.returnValue = ''; // Standard way to trigger a warning dialog
        }
    </script>
</x-app-layout>
