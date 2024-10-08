<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Your existing CSS styles... */
        /* Ensure all CSS remains unchanged except for any image URL corrections */

        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif; 
        }

        .stats-and-table-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 30px;
            padding: 20px;
        }

        .main-content {
            margin-top: 30px;
        }

        .tabs {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
            justify-content: space-around;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 10px 10px 0 0;
        }

        .tab:hover {
            background-color: #c9d1d9;
        }

        .tab.active {
            background-color: #007bff;
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-in-out;
            width: 100%;
            max-width: 500px;
            box-sizing: border-box;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .form-group label {
            flex: 1 1 100%;
            margin-bottom: 5px;
            font-size: 1rem;
        }

        .form-group input,
        .form-group select {
            flex: 1 1 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .form-group .search-btn {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            margin-left: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 1rem;
        }

        .form-group .search-btn:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        .form-group .search-btn:active {
            transform: scale(0.95);
        }

        .appointment-table {
            animation: fadeInUp 0.5s ease-in-out;
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .appointment-table th,
        .appointment-table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
            color: #333;
        }

        .appointment-table th {
            background-color: #f5f5f5;
            color: #333;
            font-weight: 600;
            border-bottom: 1px solid #ddd;
        }

        .appointment-table td {
            border-bottom: 1px solid #eee;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .action-buttons button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            transform-origin: center;
            font-size: 1rem;
        }

        .action-buttons button:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        .action-buttons button:active {
            transform: scale(0.95);
        }

        .calendar-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-in-out;
            flex: 1.5;
            margin-left: 20px;
            width: 90%;
        }

        .calendar-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .calendar-controls button {
            background-color: #00d1ff;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 1rem;
        }

        .calendar-controls button:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        .calendar {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .calendar th,
        .calendar td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        .calendar th {
            background-color: #00d1ff;
            color: white;
        }

        .calendar td.active {
            background-color: #00b8e6;
            color: white;
        }

        .calendar td:hover {
            background-color: #f0f8ff;
        }

        .chart-container {
            flex: 1;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .chart-container h2 {
            text-align: center;
            color: #0056b3;
            margin-bottom: 20px;
        }

        /* Appointment List container (Table section) */
        .appointment-list-container {
            flex: 2;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .appointment-list-container h2 {
            text-align: center;
            color: #0056b3;
            margin-bottom: 20px;
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

        .appointment-preview {
            animation: fadeInUp 0.5s ease-in-out;
            background-color: #f8f9fa;
            color: #0056b3;
            font-size: 0.8rem;
            padding: 5px;
            border-radius: 5px;
            margin-top: 5px;
        }

        .appointment-preview p {
            margin: 0;
        }

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

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
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

        .modal h2 {
            margin-top: 0;
            font-size: 1.8rem;
            text-align: center;
            color: #0056b3;
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

        /* Add Appointment Button Styles */
        .add-appointment-btn {
            background-color: #00d1ff;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            transition: background-color 0.3s, box-shadow 0.3s, transform 0.2s ease-in-out;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .add-appointment-btn:hover {
            background-color: #00b8e6;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
            transform: scale(1.05);
        }

        .add-appointment-btn:active {
            transform: scale(0.98);
            box-shadow: 0 3px 4px rgba(0, 0, 0, 0.2);
        }

        /* Active Status Styling */
        .active-status {
            display: inline-block;
            margin-top: 5px;
            font-size: 0.9rem;
        }

        /* Profile Box Styling */
        .profile-box {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: box-shadow 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .profile-box:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        .profile-info h2 {
            margin: 0;
            font-size: 1.2rem;
            color: #0056b3;
        }

        .profile-info p {
            margin: 5px 0 0 0;
            color: #333;
            font-size: 1rem;
        }
        
    </style>

    <main class="main-content">
        <div class="tabs">
            <div class="tab active" onclick="showTab('doctors-appointment-calendar')">Doctors, Add Appointment & Calendar</div>
            <div class="tab" onclick="showTab('stats-appointment-list')">Statistics & Appointment List</div>
        </div>

        <!-- Doctors, Add Appointment and Calendar Tab -->
        <div id="doctors-appointment-calendar" class="tab-content active">
            <div style="display: flex; justify-content: space-between; width: 100%; gap: 20px;">
                <!-- Form Section -->
                <div class="form-container" style="flex: 1;">
                    <h2>Add Appointment</h2>
                    <form id="add-form">
                        @csrf
                        <div class="form-group">
                            <label for="id-number">ID Number</label>
                            <div style="display: flex; align-items: center;">
                                <input type="text" id="id-number" name="id_number" placeholder="Enter ID Number" required maxlength="7">
                                <input type="hidden" id="edit-status" name="status" value="{{ isset($appointment) ? $appointment->status : 'pending' }}">
                                <button type="button" class="search-btn" onclick="fetchPatientName()">Search</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="patient-name">Patient Name</label>
                            <input type="text" id="patient-name" name="patient_name" required>
                        </div>
                        <div class="form-group input-row">
                            <div>
                                <label for="appointment-date">Appointment Date</label>
                                <input type="date" id="appointment-date" name="appointment_date" required>
                            </div>
                            <div>
                                <label for="appointment-time">Appointment Time</label>
                                <input type="time" id="appointment-time" name="appointment_time" required>
                            </div>
                        </div>
                        <div class="form-group">
            <label for="appointment-type">Appointment Type</label>
            <select id="appointment-type" name="appointment_type" required>
            <option value="General Checkup">General Checkup</option>
<option value="Dental Cleaning">Dental Cleaning</option>
<option value="Cavity Treatment">Cavity Treatment</option>
<option value="Orthodontics Consultation">Orthodontics Consultation</option>
<option value="Tooth Extraction">Tooth Extraction</option>
<option value="Tooth Filling">Tooth Filling</option>
<option value="Root Canal Treatment">Root Canal Treatment</option>
<option value="Periodontal Treatment">Periodontal Treatment</option>
<option value="Teeth Whitening">Teeth Whitening</option>
<option value="Braces Adjustment">Braces Adjustment</option>
<option value="Wisdom Tooth Consultation">Wisdom Tooth Consultation</option>
<option value="Dental X-ray">Dental X-ray</option>
<option value="Oral Cancer Screening">Oral Cancer Screening</option>
<option value="General Physical Examination">General Physical Examination</option>
<option value="Cardiac Examination">Cardiac Examination</option>
<option value="Orthopedic Examination">Orthopedic Examination</option>
<option value="Neurological Examination">Neurological Examination</option>
<option value="Pulmonary Function Test">Pulmonary Function Test</option>
<option value="Vision Examination">Vision Examination</option>
<option value="Hearing Examination">Hearing Examination</option>
<option value="Sports Physical">Sports Physical</option>
<option value="Pre-Employment Physical">Pre-Employment Physical</option>
<option value="Pre-Operative Physical">Pre-Operative Physical</option>
<option value="Skin Examination">Skin Examination</option>
<option value="Diabetes Screening">Diabetes Screening</option>
<option value="Hypertension Screening">Hypertension Screening</option>
<option value="Cholesterol Check">Cholesterol Check</option>
                <!-- Add more options as needed -->
            </select>
        </div>
        <div class="form-group">
                            <label for="doctor">Select Doctor</label>
                            <select id="doctor" name="doctor_id" required>
                                <option value="" disabled selected>Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">
                                        {{ $doctor->user->first_name }} {{ $doctor->user->last_name }} ({{ $doctor->specialization }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="button" class="add-appointment-btn" onclick="addAppointment()">Add Appointment</button>
                        </div>
                    </form>
                </div>

                <!-- Calendar Section -->
                <div class="calendar-container" style="flex: 1.5;">
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
                </div>

                <!-- Doctors Profile Section -->
                <div class="form-container" style="flex: 1;">
                    <div id="doctors-section" style="flex: 1;">
                        <h2>Our Doctors</h2>
                        <div class="content-row" style="display: flex; flex-direction: column; gap: 20px;">
                            @forelse ($doctors as $doctor)
                                <div class="profile-box">
                                    <!-- Display doctor's profile picture if available, else show a default image -->
                                    <img src="{{ $doctor->user->profile_picture 
                                        ? asset('storage/' . $doctor->user->profile_picture) 
                                        : 'https://img.icons8.com/ios-filled/100/000000/user-male-circle.png' }}" 
                                         alt="{{ $doctor->user->first_name ?? 'Doctor' }}" 
                                         loading="lazy" 
                                         style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">

                                    <div class="profile-info">
                                        <h2>{{ $doctor->user->first_name ?? 'First Name' }} {{ $doctor->user->last_name ?? 'Last Name' }}</h2>
                                        <p>{{ $doctor->specialization ?? 'Specialization' }}</p>
                                        @if ($doctor->approved)
                                            <span class="active-status" style="color: green; font-weight: bold;">Active</span>
                                        @else
                                            <span class="active-status" style="color: red; font-weight: bold;">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p>No doctors available.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics and Appointment List Tab -->
        <div id="stats-appointment-list" class="tab-content">
            <div class="stats-and-table-container">
                <div class="chart-container">
                    <h2>Statistics</h2>
                    <canvas id="appointmentsChart"></canvas>
                </div>
                <div class="appointment-list-container">
                    <h2>Appointment List</h2>
                    <div class="filter-container">
                        <label for="status-filter">Filter by Status: </label>
                        <select id="status-filter" onchange="filterAppointments()" style="width: 200px; padding: 8px; border-radius: 5px;">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                        </select>
                    </div>
                    <table class="appointment-table">
    <thead>
        <tr>
            <th>ID Number</th>
            <th>Patient Name</th>
            <th>Appointment Date</th>
            <th>Appointment Time</th>
            <th>Appointment Type</th>
            <th>Doctor</th> <!-- New Column -->
            <th>Status</th> <!-- New Column -->
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($appointments as $appointment)
            <tr id="appointment-row-{{ $appointment->id }}">
                <td>{{ $appointment->id_number }}</td>
                <td>{{ $appointment->patient_name }}</td>
                <td>{{ $appointment->appointment_date }}</td>
                <td>{{ $appointment->appointment_time }}</td>
                <td>{{ $appointment->appointment_type }}</td>
                <td>{{ $appointment->doctor->user->first_name ?? 'N/A' }} {{ $appointment->doctor->user->last_name ?? '' }}</td> <!-- Display Doctor's Name -->
                <td>
                    @if ($appointment->status === 'confirmed')
                        <span style="color: green; font-weight: bold;">Confirmed</span>
                    @else
                        <span style="color: orange; font-weight: bold;">Pending</span>
                    @endif
                </td>
                <td>
                    <div class="action-buttons">
                        @if ($appointment->status !== 'confirmed')
                            <button id="confirm-btn-{{ $appointment->id }}" 
                                    onclick="confirmAppointment({{ $appointment->id }})">
                                Confirm
                            </button>
                        @endif
                        
                        <button id="reschedule-btn-{{ $appointment->id }}" 
                                onclick="openEditModal({{ $appointment->id }})">
                            Reschedule
                        </button>

                        <button onclick="confirmDelete({{ $appointment->id }})">Delete</button>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

                </div>
            </div>
        </div>

       <!-- Edit Appointment Modal -->
<div id="edit-modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Edit Appointment</h2>
        <form id="edit-form">
            @csrf
            @method('PUT') <!-- Add method field for PUT request -->
            <input type="hidden" id="edit-appointment-id" name="id">
            <div class="form-group">
                <label for="edit-id-number">ID Number</label>
                <input type="text" id="edit-id-number" name="id_number" required maxlength="7">
            </div>
            <div class="form-group">
                <label for="edit-patient-name">Patient Name</label>
                <input type="text" id="edit-patient-name" name="patient_name" required>
            </div>
            <div class="form-group input-row">
                <div>
                    <label for="edit-appointment-date">Appointment Date</label>
                    <input type="date" id="edit-appointment-date" name="appointment_date" required>
                </div>
                <div>
                    <label for="edit-appointment-time">Appointment Time</label>
                    <input type="time" id="edit-appointment-time" name="appointment_time" required>
                </div>
            </div>
            <div class="form-group">
                <label for="edit-appointment-type">Appointment Type</label>
                <select id="edit-appointment-type" name="appointment_type" required>
                <option value="General Checkup">General Checkup</option>
<option value="Dental Cleaning">Dental Cleaning</option>
<option value="Cavity Treatment">Cavity Treatment</option>
<option value="Orthodontics Consultation">Orthodontics Consultation</option>
<option value="Tooth Extraction">Tooth Extraction</option>
<option value="Tooth Filling">Tooth Filling</option>
<option value="Root Canal Treatment">Root Canal Treatment</option>
<option value="Periodontal Treatment">Periodontal Treatment</option>
<option value="Teeth Whitening">Teeth Whitening</option>
<option value="Braces Adjustment">Braces Adjustment</option>
<option value="Wisdom Tooth Consultation">Wisdom Tooth Consultation</option>
<option value="Dental X-ray">Dental X-ray</option>
<option value="Oral Cancer Screening">Oral Cancer Screening</option>
<option value="General Physical Examination">General Physical Examination</option>
<option value="Cardiac Examination">Cardiac Examination</option>
<option value="Orthopedic Examination">Orthopedic Examination</option>
<option value="Neurological Examination">Neurological Examination</option>
<option value="Pulmonary Function Test">Pulmonary Function Test</option>
<option value="Vision Examination">Vision Examination</option>
<option value="Hearing Examination">Hearing Examination</option>
<option value="Sports Physical">Sports Physical</option>
<option value="Pre-Employment Physical">Pre-Employment Physical</option>
<option value="Pre-Operative Physical">Pre-Operative Physical</option>
<option value="Skin Examination">Skin Examination</option>
<option value="Diabetes Screening">Diabetes Screening</option>
<option value="Hypertension Screening">Hypertension Screening</option>
<option value="Cholesterol Check">Cholesterol Check</option>

                    <!-- Add more options as needed -->
                </select>
            </div>
            <div class="form-group">
                <label for="edit-doctor">Select Doctor</label>
                <select id="edit-doctor" name="doctor_id" required>
                    <option value="" disabled selected>Select Doctor</option>
                    <!-- Doctors will be populated via JavaScript -->
                </select>
            </div>
            <div class="form-group">
                <button type="button" onclick="updateAppointment()">Update Appointment</button>
            </div>
        </form>
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
    </main>

    <!-- Include SweetAlert2 and Chart.js Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Tab Switching Function
        function showTab(tabId) {
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(tabContent => {
                tabContent.classList.remove('active');
            });

            const selectedTabContent = document.getElementById(tabId);
            selectedTabContent.classList.add('active');

            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            document.querySelector(`.tab[onclick="showTab('${tabId}')"]`).classList.add('active');
        }

        const userRole = "{{ strtolower(Auth::user()->role) }}";

        // Fetch Patient Name Function
        function fetchPatientName() {
            const idNumber = document.getElementById('id-number').value;

            // Ensure that the ID number is not blank or default
            if (!idNumber || idNumber === "C120000") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid ID Number',
                    text: 'Please enter a valid ID number before searching.',
                    timer: 3000,
                    showConfirmButton: false
                });
                return;
            }

            // Send a request to the server to fetch the patient's name based on the ID number
            fetch(`/doctor/appointment/fetch-patient-name/${idNumber}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error,
                            timer: 3000,
                            showConfirmButton: false
                        });
                    } else {
                        document.getElementById('patient-name').value = `${data.first_name} ${data.last_name}`;
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Patient name fetched successfully!',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching patient name:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                });
        }

        // Show Notification Function
        function showNotification(message) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: message,
                timer: 3000,
                showConfirmButton: false
            });
        }

        // Open Edit Modal Function
        function openEditModal(id) {
    const appointment = document.getElementById(`appointment-row-${id}`);
    document.getElementById('edit-appointment-id').value = id;
    document.getElementById('edit-id-number').value = appointment.children[0].innerText;
    document.getElementById('edit-patient-name').value = appointment.children[1].innerText;
    document.getElementById('edit-appointment-date').value = appointment.children[2].innerText;
    document.getElementById('edit-appointment-time').value = appointment.children[3].innerText;
    document.getElementById('edit-appointment-type').value = appointment.children[4].innerText;
    
    // Populate doctors dropdown
    populateEditDoctorsDropdown();
    
    // Select the current doctor
    const doctorName = appointment.children[5].innerText.trim(); // Assuming the 6th column is Doctor
    const doctorSelect = document.getElementById('edit-doctor');
    for (let i = 0; i < doctorSelect.options.length; i++) {
        const option = doctorSelect.options[i];
        if (option.textContent.includes(doctorName)) {
            option.selected = true;
            break;
        }
    }

    document.getElementById('edit-modal').style.display = 'block';
}

        // Close Edit Modal Function
        function closeEditModal() {
            document.getElementById('edit-modal').style.display = 'none';
        }

        // Close Preview Modal Function
        function closePreviewModal() {
            document.getElementById('preview-modal').style.display = 'none';
        }

        // Add Appointment Function
        function addAppointment() {
            const form = document.getElementById('add-form');
            const formData = new FormData(form);

            fetch('{{ route("doctor.appointment.add") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.success,
                        timer: 3000,
                        showConfirmButton: false
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error || 'Error adding appointment',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unexpected error occurred.',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        }

        // Confirm Delete Function
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('/doctor/appointment/delete/') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById(`appointment-row-${id}`).remove();
                                showNotification('Appointment deleted successfully');
                                setTimeout(() => {
                                    location.reload();
                                }, 3000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error deleting appointment',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            }
                        }).catch(error => {
                            console.error('Error:', error);
                        });
                }
            });
        }

        // Calendar Rendering Functions
        const currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();

        function renderCalendar(month, year) {
            const monthString = String(month + 1).padStart(2, '0');
            const fetchUrl = `/${userRole}/appointments/by-month?month=${year}-${monthString}`;

            fetch(fetchUrl)
                .then(response => response.json())
                .then(data => {
                    const appointmentsByDate = {};

                    if (data.appointments && data.appointments.length > 0) {
                        data.appointments.forEach(appointment => {
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
                    renderCalendarDays(month, year, {}); // Proceed without appointments
                });
        }

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

            for (let i = 0; i < 6; i++) {
                let row = document.createElement('tr');
                for (let j = 0; j < 7; j++) {
                    let cell = document.createElement('td');
                    if (i === 0 && j < firstDay) {
                        cell.appendChild(document.createTextNode(''));
                    } else if (date > daysInMonth) {
                        break;
                    } else {
                        let selectedDate = date;
                        cell.textContent = selectedDate;

                        const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(selectedDate).padStart(2, '0')}`;

                        // Mark the cell with class based on appointments
                        if (appointmentsByDate[dateString]) {
                            cell.classList.add('booked'); // Has appointments
                        } else {
                            cell.classList.add('available'); // No appointments
                        }

                        // Add click event
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

        // Initialize Calendar on Page Load
        document.addEventListener('DOMContentLoaded', function () {
            renderCalendar(currentMonth, currentYear);
        });

        // Change Month Function
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

        // Chart.js Initialization with Safe Data Passing
        const ctx = document.getElementById('appointmentsChart').getContext('2d');
        const appointmentsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total', 'Upcoming', 'Completed', 'Dr. Isnani', 'Dr. Gan'],
                datasets: [{
                    label: 'Number of Appointments',
                    data: [{{ $totalAppointments }}, {{ $upcomingAppointments }}, {{ $completedAppointments }}, {{ $drIsnaniAppointments }}, {{ $drGanAppointments }}],
                    backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'],
                    borderColor: ['rgba(75, 192, 192, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        // Confirm Appointment Function
     // Confirm Appointment Function
function confirmAppointment(id) {
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('status', 'confirmed'); // Set status to confirmed

    fetch(`/doctor/appointment/confirm/${id}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Appointment Confirmed',
                text: data.message,
                timer: 3000,
                showConfirmButton: false
            });

            // Update the status cell
            const row = document.getElementById(`appointment-row-${id}`);
            const statusCell = row.querySelector('td:nth-child(7)'); // Status is the 7th column
            statusCell.innerHTML = '<span style="color: green; font-weight: bold;">Confirmed</span>';

            // Hide the confirm button and show the reschedule button
            document.getElementById(`confirm-btn-${id}`).style.display = 'none';
            document.getElementById(`reschedule-btn-${id}`).style.display = 'inline-block';
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Could not confirm appointment.',
                timer: 3000,
                showConfirmButton: false
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred.',
            timer: 3000,
            showConfirmButton: false
        });
    });
}
function populateEditDoctorsDropdown() {
    fetch('{{ route("doctor.appointment.getApprovedDoctors") }}')
        .then(response => response.json())
        .then(data => {
            const doctorSelect = document.getElementById('edit-doctor');
            doctorSelect.innerHTML = '<option value="" disabled selected>Select Doctor</option>'; // Reset options
            data.doctors.forEach(doctor => {
                const option = document.createElement('option');
                option.value = doctor.id;
                option.textContent = `${doctor.user.first_name} ${doctor.user.last_name} (${doctor.specialization})`;
                doctorSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching doctors:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load doctors.',
                timer: 3000,
                showConfirmButton: false
            });
        });
}

        // Update Appointment Function
        function updateAppointment() {
            const form = document.getElementById('edit-form');
            const formData = new FormData(form);
            const id = document.getElementById('edit-appointment-id').value;
            const data = {};

            formData.forEach((value, key) => {
                data[key] = value;
            });

            fetch(`/doctor/appointment/update/${id}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Appointment Rescheduled Successfully');
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error rescheduling appointment',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Get Appointments by Date URL
        const getAppointmentsByDateUrl = `{{ route('doctor.appointments.by-date') }}`;

        // Open Preview Modal Function
        function openPreviewModal(date, month, year) {
            const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;

            document.getElementById('preview-date').innerText = formattedDate;

            fetch(`${getAppointmentsByDateUrl}?date=${formattedDate}`)
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
                            li.innerText = `${appointment.patient_name} - ${timeFormatted} (${appointment.appointment_type})`;
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
        function filterAppointments() {
    const filterValue = document.getElementById('status-filter').value;
    const rows = document.querySelectorAll('.appointment-table tbody tr');

    rows.forEach(row => {
        const statusCell = row.querySelector('td:nth-child(7)').innerText.toLowerCase();
        if (filterValue === "" || statusCell.includes(filterValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

    </script>
</x-app-layout>
