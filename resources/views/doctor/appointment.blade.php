<x-app-layout :pageTitle="' Appointments'">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Legend Styling */
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

        /* General Styles */
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif; 
        }

        .stats-and-table-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 30px;
            flex-wrap: wrap;
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
            width: 100%;
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
            position: relative;
        }
        .calendar td .badge {
    position: absolute;
    top: 5px;
    right: 5px;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.8rem;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
}

.calendar td .badge.confirmed {
    background-color: #dc3545; /* Red */
}

.calendar td .badge.pending {
    background-color: #ffc107; /* Yellow */
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

        /* Appointment List container */
        .appointment-list-container {
            flex: 1;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-height: 700px; /* Increased height for more visibility */
            overflow-y: auto; /* Enable vertical scrolling */
            box-sizing: border-box;

        }

        .appointment-list-container::-webkit-scrollbar {
            width: 8px;
        }

        .appointment-list-container::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 5px;
        }

        .appointment-list-container::-webkit-scrollbar-thumb:hover {
            background-color: #888;
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

    


        /* Action Buttons */
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

        .modal .add-appointment-btn {
            background-color: #28a745;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            transition: background-color 0.3s, box-shadow 0.3s, transform 0.2s ease-in-out;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .modal .add-appointment-btn:hover {
            background-color: #218838;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
            transform: scale(1.02);
        }

        .modal .add-appointment-btn:active {
            transform: scale(0.98);
            box-shadow: 0 3px 4px rgba(0, 0, 0, 0.2);
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
/* General Button Styles */
.btn {
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background-color 0.3s, transform 0.3s;
    font-weight: 600;
}

/* Confirm Button */
.confirm-btn {
    background-color: #28a745; /* Green */
    color: white;
}

.confirm-btn:hover {
    background-color: #218838;
    transform: scale(1.05);
}

.confirm-btn:active {
    transform: scale(0.95);
}

/* Reschedule Button */
.reschedule-btn {
    background-color: #17a2b8; /* Blue */
    color: white;
}

.reschedule-btn:hover {
    background-color: #138496;
    transform: scale(1.05);
}

.reschedule-btn:active {
    transform: scale(0.95);
}

/* Delete Button */
.delete-btn {
    background-color: #dc3545; /* Red */
    color: white;
}

.delete-btn:hover {
    background-color: #c82333;
    transform: scale(1.05);
}

.delete-btn:active {
    transform: scale(0.95);
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

        /* Input Row Styling */
        .input-row {
            display: flex;
            gap: 20px;
        }

        .input-row .input-column {
            flex: 1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .stats-and-table-container {
                flex-direction: column;
            }

            .calendar-container,
            .form-container,
            .appointment-list-container {
                width: 100%;
                margin-left: 0;
            }
        }
    </style>


    @php
        $userRole = strtolower(Auth::user()->role);
        $currentDoctor = $userRole === 'doctor' ? Auth::user()->doctor : null;
    @endphp

    <main class="main-content">
        <div class="tabs">
            <div class="tab active" onclick="showTab('add-appointment-calendar')">Add Appointment & Calendar</div>
            <div class="tab" onclick="showTab('appointment-list')">Appointment List</div>
        </div>

        <!-- Add Appointment and Calendar Tab -->
        <div id="add-appointment-calendar" class="tab-content active">
            <div style="display: flex; justify-content: space-between; width: 100%; gap: 20px; flex-wrap: wrap;">
                <!-- Form Section -->
           <!-- Form Section -->
<div class="form-container" style="flex: 1;">
    <h2>Add Appointment</h2>
    <form id="add-form">
        @csrf
        <!-- ID Number Field -->
        <div class="form-group">
            <label for="id-number">ID Number</label>
            <div style="display: flex; align-items: center;">
                <input type="text" id="id-number" name="id_number" placeholder="Enter ID Number" required maxlength="7">
                <button type="button" class="search-btn" onclick="fetchPatientName()">Search</button>
            </div>
        </div>
        <!-- Patient Name Field -->
        <div class="form-group">
            <label for="patient-name">Patient Name</label>
            <input type="text" id="patient-name" name="patient_name" required readonly>
        </div>
        <!-- Appointment Date and Time -->
        <div class="form-group input-row">
            <div class="input-column">
                <label for="appointment-date">Appointment Date</label>
                <input type="date" id="appointment-date" name="appointment_date" required>
            </div>
            <div class="input-column">
                <label for="appointment-time">Appointment Time</label>
                <input type="time" id="appointment-time" name="appointment_time" required min="08:00" max="16:00" step="3600">
            </div>
        </div>
        <!-- Appointment Type -->
        <div class="form-group">
            <label for="appointment-type">Appointment Type</label>
            <select id="appointment-type" name="appointment_type" required>
                <!-- Add more options as needed -->
                <option value="General Checkup">General Checkup</option>
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
                                <option value="Cholesterol Check">Cholesterol Check</option>            </select>
        </div>
        <!-- Conditionally Render Doctor Field -->
        @if ($userRole === 'doctor' && $currentDoctor)
            <div class="form-group">
                <label for="doctor">Doctor</label>
                <input type="hidden" id="doctor-id" name="doctor_id" value="{{ $currentDoctor->id }}">
                <input type="text" value="{{ $currentDoctor->user->first_name }} {{ $currentDoctor->user->last_name }} ({{ $currentDoctor->specialization }})" readonly>
            </div>
        @else
            <div class="form-group">
                <label for="doctor">Select Doctor <span style="color: red;">*</span></label>
                <select id="doctor-id" name="doctor_id" required>
                    <option value="" disabled selected>Select Doctor</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">
                            {{ $doctor->user->first_name }} {{ $doctor->user->last_name }} ({{ $doctor->specialization }})
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
        <!-- Add Appointment Button -->
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
        </div>

        <!-- Appointment List Tab -->
        <div id="appointment-list" class="tab-content">
            <div class="appointment-list-container">
                <h2>Appointment List</h2>
                <p>Total Appointments: {{ $appointments->count() }}</p> <!-- Debugging Line -->
                <div class="filter-container" style="margin-bottom: 15px; display: flex; justify-content: flex-end; align-items: center; gap: 10px;">
                    <label for="status-filter">Filter by Status:</label>
                    <select id="status-filter" onchange="filterAppointments()" style="width: 200px; padding: 8px; border-radius: 5px;">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                    </select>
                </div>
                <!-- Appointment Table -->
                <table class="appointment-table">
                    <thead>
                        <tr>
                            <th>ID Number</th>
                            <th>Patient Name</th>
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
                            <th>Appointment Type</th>
                            <th>Status</th> <!-- Status Column -->
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($appointments as $appointment)
                            <tr id="appointment-row-{{ $appointment->id }}">
                                <td>{{ $appointment->id_number }}</td>
                                <td>{{ $appointment->patient_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                <td>{{ $appointment->appointment_type }}</td>
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
            <button class="btn confirm-btn" onclick="confirmAppointment({{ $appointment->id }})">Confirm</button>
        @endif
        
        <button class="btn reschedule-btn" onclick="openEditModal({{ $appointment->id }})">Reschedule</button>

        <button class="btn delete-btn" onclick="confirmDelete({{ $appointment->id }})">Delete</button>
    </div>
</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; color: #888;">No appointments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Appointment Modal -->
     <!-- Edit Appointment Modal -->
<div id="edit-modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Edit Appointment</h2>
        <form id="edit-form">
            @csrf
            @method('PUT') <!-- Add method field for PUT request -->
            <input type="hidden" id="edit-appointment-id" name="id">
            <!-- ID Number Field -->
            <div class="form-group">
                <label for="edit-id-number">ID Number</label>
                <input type="text" id="edit-id-number" name="id_number" required maxlength="7">
            </div>
            <!-- Patient Name Field -->
            <div class="form-group">
                <label for="edit-patient-name">Patient Name</label>
                <input type="text" id="edit-patient-name" name="patient_name" required>
            </div>
            <!-- Appointment Date and Time -->
            <div class="form-group input-row">
                <div class="input-column">
                    <label for="edit-appointment-date">Appointment Date</label>
                    <input type="date" id="edit-appointment-date" name="appointment_date" required>
                </div>
                <div class="input-column">
                    <label for="edit-appointment-time">Appointment Time</label>
                    <input type="time" id="edit-appointment-time" name="appointment_time" required min="08:00" max="16:00" step="3600">
                </div>
            </div>
            <!-- Appointment Type -->
            <div class="form-group">
                <label for="edit-appointment-type">Appointment Type</label>
                <select id="edit-appointment-type" name="appointment_type" required>
                    <!-- Add more options as needed -->
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
                            <option value="Cholesterol Check">Cholesterol Check</option>                    <!-- ... other options ... -->
                </select>
            </div>
            <!-- Conditionally Render Doctor Field -->
            @if ($userRole === 'doctor' && $currentDoctor)
                <div class="form-group">
                    <label for="edit-doctor">Doctor</label>
                    <input type="hidden" id="edit-doctor-id" name="doctor_id" value="{{ $currentDoctor->id }}">
                    <input type="text" value="{{ $currentDoctor->user->first_name }} {{ $currentDoctor->user->last_name }} ({{ $currentDoctor->specialization }})" readonly>
                </div>
            @else
                <div class="form-group">
                    <label for="edit-doctor">Select Doctor</label>
                    <select id="edit-doctor-id" name="doctor_id" required>
                        <option value="" disabled selected>Select Doctor</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">
                                {{ $doctor->user->first_name }} {{ $doctor->user->last_name }} ({{ $doctor->specialization }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
            <!-- Update Appointment Button -->
            <div class="form-group">
                <button type="button" class="add-appointment-btn" onclick="updateAppointment()">Update Appointment</button>
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

    <!-- Include SweetAlert2 Library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        // Front-end Validation
        if (!idNumber || idNumber.trim() === "") {
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
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
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
    if (!appointment) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Appointment data not found.',
            timer: 3000,
            showConfirmButton: false
        });
        return;
    }

    document.getElementById('edit-appointment-id').value = id;
    document.getElementById('edit-id-number').value = appointment.children[0].innerText;
    document.getElementById('edit-patient-name').value = appointment.children[1].innerText;

    // Convert formatted date back to Y-m-d for input value
    const dateParts = appointment.children[2].innerText.split(' ');
    const date = new Date(`${dateParts[0]} ${dateParts[1]} ${dateParts[2]}`);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const formattedDate = `${year}-${month}-${day}`;
    document.getElementById('edit-appointment-date').value = formattedDate;

    // Convert formatted time back to H:i for input value
    const timeParts = appointment.children[3].innerText.split(' ');
    let [hour, minute] = timeParts[0].split(':');
    const ampm = timeParts[1];
    hour = ampm === 'PM' && hour !== '12' ? parseInt(hour) + 12 : hour;
    const formattedTime = `${String(hour).padStart(2, '0')}:${minute}`;
    document.getElementById('edit-appointment-time').value = formattedTime;

    document.getElementById('edit-appointment-type').value = appointment.children[4].innerText;

    // Conditionally handle doctor field
    if (userRole === 'doctor' && "{{ $currentDoctor->id ?? '' }}" !== '') {
        // If user is a doctor, set the hidden doctor_id and display the read-only name
        document.getElementById('edit-doctor-id').value = "{{ $currentDoctor->id ?? '' }}";
    } else {
        // For non-doctors, select the appropriate doctor from the dropdown
        const doctorName = appointment.children[5].innerText.trim(); // Assuming the 6th column is Doctor
        const doctorSelect = document.getElementById('edit-doctor-id');
        for (let i = 0; i < doctorSelect.options.length; i++) {
            const option = doctorSelect.options[i];
            if (option.textContent.includes(doctorName)) {
                option.selected = true;
                break;
            }
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

    // Add Appointment Function with Front-end Validation
    function addAppointment() {
    const form = document.getElementById('add-form');
    const doctorSelect = document.getElementById('doctor-id');
    const doctorId = doctorSelect ? doctorSelect.value : document.getElementById('doctor-id').value;
    const appointmentDate = document.getElementById('appointment-date').value;
    const appointmentTime = document.getElementById('appointment-time').value;
    const addButton = form.querySelector('.add-appointment-btn');

    // Front-end Validation
    if (!appointmentDate) {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please select an appointment date.',
            timer: 3000,
            showConfirmButton: false
        });
        return;
    }

    if (!appointmentTime) {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please select an appointment time.',
            timer: 3000,
            showConfirmButton: false
        });
        return;
    }

    if (!doctorId) {
        Swal.fire({
            icon: 'warning',
            title: 'Doctor Not Selected',
            text: 'Please select a doctor before adding an appointment.',
            timer: 3000,
            showConfirmButton: false
        });
        return;
    }

    // Optional: Add Confirmation Prompt
    Swal.fire({
        title: 'Confirm Appointment',
        text: "Are you sure you want to add this appointment?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#00d1ff',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, add it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Disable the Add Appointment Button to Prevent Multiple Clicks
            addButton.disabled = true;
            addButton.style.cursor = 'not-allowed';
            addButton.style.opacity = '0.6';

            // Show Loading SweetAlert
            Swal.fire({
                title: 'Submitting...',
                text: 'Please wait while your appointment is being added.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Prepare FormData
            const formData = new FormData(form);

            fetch('{{ route("doctor.appointment.add") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                Swal.close(); // Close the loading SweetAlert
                addButton.disabled = false; // Re-enable the button
                addButton.style.cursor = 'pointer';
                addButton.style.opacity = '1';

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
                Swal.close(); // Close the loading SweetAlert
                addButton.disabled = false; // Re-enable the button
                addButton.style.cursor = 'pointer';
                addButton.style.opacity = '1';
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
                fetch(`/doctor/appointment/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
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
                })
                .catch(error => {
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
                        // Since 'appointment_date' is now 'YYYY-MM-DD'
                        const date = appointment.appointment_date; // No need to split
                        if (!appointmentsByDate[date]) {
                            appointmentsByDate[date] = [];
                        }
                        appointmentsByDate[date].push(appointment);
                    });
                }

                console.log('Appointments by Date:', appointmentsByDate); // Debugging

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

                    // Format dateString as 'YYYY-MM-DD'
                    const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(selectedDate).padStart(2, '0')}`;

                    // Determine the class based on appointment statuses
                    if (appointmentsByDate[dateString]) {
                        const appointments = appointmentsByDate[dateString];
                        let hasConfirmed = false;
                        let hasPending = false;

                        appointments.forEach(appointment => {
                            const status = appointment.status.toLowerCase().trim();
                            console.log(`Processing appointment ID ${appointment.id} with status: ${status}`);
                            if (status === 'confirmed') {
                                hasConfirmed = true;
                            } else if (status === 'pending') {
                                hasPending = true;
                            }
                        });

                        if (hasConfirmed) {
                            cell.classList.add('red'); // Confirmed appointments
                            console.log(`Date ${dateString} marked as confirmed (red)`);
                        } else if (hasPending) {
                            cell.classList.add('yellow'); // Pending appointments
                            console.log(`Date ${dateString} marked as pending (yellow)`);
                        }
                    } else {
                        cell.classList.add('green'); // Free date
                        console.log(`Date ${dateString} marked as free (green)`);
                    }

                    // Add click event
                    cell.onclick = () => {
                        openPreviewModal(selectedDate, month, year);
                    };

                    // Highlight today's date
                    const today = new Date();
                    if (selectedDate === today.getDate() && year === today.getFullYear() && month === today.getMonth()) {
                        cell.classList.add('active');
                        console.log(`Date ${dateString} is today and marked as active (blue)`);
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

                // Reload the page to update calendar and table
                setTimeout(() => {
                    location.reload();
                }, 3000);
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

    // Populate Edit Doctors Dropdown (Auto-select the logged-in doctor)
    function populateEditDoctorsDropdown() {
    if (userRole === 'doctor') {
        // Doctors cannot change the assigned doctor, no action needed
        return;
    }

    fetch('{{ route("doctor.appointment.getApprovedDoctors") }}')
        .then(response => response.json())
        .then(data => {
            const doctorSelect = document.getElementById('edit-doctor-id');
            if (!doctorSelect) return; // Exit if doctor select doesn't exist

            doctorSelect.innerHTML = '<option value="" disabled selected>Select Doctor</option>';

            if (data.available_doctors.length > 0) {
                data.available_doctors.forEach(doctor => {
                    const option = document.createElement('option');
                    option.value = doctor.id;
                    option.textContent = `${doctor.user.first_name} ${doctor.user.last_name} (${doctor.specialization})`;
                    doctorSelect.appendChild(option);
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Doctors Available',
                    text: 'No doctors are available on the selected date. Please choose another date.',
                    timer: 3000,
                    showConfirmButton: false
                });
                document.getElementById('edit-appointment-date').value = ''; // Clear date input
                doctorSelect.innerHTML = '<option value="" disabled selected>No Doctors Available</option>';
            }
        })
        .catch(error => {
            console.error('Error fetching approved doctors:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load doctors.',
                timer: 3000,
                showConfirmButton: false
            });
        });
}


    // Update Appointment Function with Front-end Validation
    function updateAppointment() {
    const form = document.getElementById('edit-form');
    const doctorSelect = document.getElementById('edit-doctor-id');
    const doctorId = doctorSelect ? doctorSelect.value : document.getElementById('edit-doctor-id').value;
    const appointmentDate = document.getElementById('edit-appointment-date').value;
    const appointmentTime = document.getElementById('edit-appointment-time').value;
    const updateButton = form.querySelector('.add-appointment-btn');

    // Front-end Validation
    if (!appointmentDate) {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please select an appointment date.',
            timer: 3000,
            showConfirmButton: false
        });
        return;
    }

    if (!appointmentTime) {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please select an appointment time.',
            timer: 3000,
            showConfirmButton: false
        });
        return;
    }

    if (!doctorId) {
        Swal.fire({
            icon: 'warning',
            title: 'Doctor Not Selected',
            text: 'Please select a doctor before updating the appointment.',
            timer: 3000,
            showConfirmButton: false
        });
        return;
    }

    // Confirmation Prompt
    Swal.fire({
        title: 'Confirm Update',
        text: "Are you sure you want to update this appointment?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#00d1ff',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Disable the button
            updateButton.disabled = true;
            updateButton.style.cursor = 'not-allowed';
            updateButton.style.opacity = '0.6';

            // Show loading
            Swal.fire({
                title: 'Updating...',
                text: 'Please wait while your appointment is being updated.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Prepare FormData
            const formData = new FormData(form);

            // For debugging: Log all FormData entries
            for (let pair of formData.entries()) {
                console.log(`${pair[0]}: ${pair[1]}`);
            }

            // Change method to POST and ensure _method=PUT is present
            fetch(`/doctor/appointment/update/${formData.get('id')}`, {
                method: 'POST', // Changed from 'PUT' to 'POST'
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                Swal.close(); // Close the loading SweetAlert
                updateButton.disabled = false; // Re-enable the button
                updateButton.style.cursor = 'pointer';
                updateButton.style.opacity = '1';

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
                        text: data.error || 'Error updating appointment',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            })
            .catch(error => {
                Swal.close(); // Close the loading SweetAlert
                updateButton.disabled = false; // Re-enable the button
                updateButton.style.cursor = 'pointer';
                updateButton.style.opacity = '1';
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
    });
}


    // Open Preview Modal Function
    function openPreviewModal(date, month, year) {
        const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;

        document.getElementById('preview-date').innerText = formattedDate;

        fetch(`{{ route('doctor.appointments.by-date') }}?date=${formattedDate}`)
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

    // Helper function to capitalize first letter
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    // Filter Appointments Function
    function filterAppointments() {
        const filterValue = document.getElementById('status-filter').value.toLowerCase();
        const rows = document.querySelectorAll('.appointment-table tbody tr');

        rows.forEach(row => {
            const statusCell = row.querySelector('td:nth-child(6)').innerText.toLowerCase();
            if (filterValue === "" || statusCell.includes(filterValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Close Modal When Clicking Outside of Modal Content
    window.onclick = function(event) {
        const editModal = document.getElementById('edit-modal');
        const previewModal = document.getElementById('preview-modal');
        if (event.target == editModal) {
            editModal.style.display = 'none';
        }
        if (event.target == previewModal) {
            previewModal.style.display = 'none';
        }
    }
</script>

</x-app-layout>
