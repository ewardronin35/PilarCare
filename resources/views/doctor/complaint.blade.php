<x-app-layout :pageTitle="' Complaints'">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- SweetAlert2 for Alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart.js for Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <style>
        /* Existing Styles */
body{
    font-family: 'Poppins', sans-serif;

}
        .main-content {
            margin-top: 40px;
            position: relative; /* To contain absolutely positioned elements */
        }

        .container {
            display: flex;
            font-family: 'Poppins', sans-serif;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        /* Styling for the Form */
        .form-container {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 48%;
            box-sizing: border-box;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-group label {
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-group label i {
            margin-right: 8px;
            color: #007bff;
            font-size: 18px;
        }

        .form-group .input-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            padding: 8px 10px;
            border: 1px solid #ddd;
            border-radius: 50px;
            font-size: 14px;
            width: 100%;
            box-sizing: border-box;
        }

        .form-group textarea {
            border-radius: 20px;
            resize: none;
        }

        .form-group button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
            transition: background-color 0.3s;
        }

        .form-group button:hover {
            background-color: #00b8e6;
        }

        .search-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            position: relative; /* To contain absolute elements if any */
        }

        .search-input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 25px;
            border: 1px solid #ddd;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .search-container .input-wrapper {
            flex: 1;
        }

        .search-container button {
            background-color: #00d1ff;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 50px;
            margin-right: 300px;
            margin-bottom: 20px;
            cursor: pointer;
            font-size: 14px;
            right: 0;
            top: 0;
            height: 100%;
        }

        .search-container button:hover {
            background-color: #00b8e6;
        }

        .table-container {
            width: 100%;
            max-height: 400px; /* Adjust the height as needed */
            overflow-x: auto;
            overflow-y: auto;
            background-color: #fff;
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 20px;
            box-sizing: border-box;
            margin-bottom: 20px;
        }

        .complaints-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .complaints-table th,
        .complaints-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .complaints-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-transform: uppercase;
        }

        .complaints-table tr:hover {
            background-color: #f1f1f1;
        }

        .complaints-table td {
            background-color: #fff;
        }

        .preview-button,
        .pdf-button,
        .download-button {
            background-color: #00d1ff;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 5px;
            text-decoration: none; /* For anchor tags */
        }

        .preview-button:hover,
        .pdf-button:hover,
        .download-button:hover {
            background-color: #00b8e6;
        }

        .pdf-button[disabled],
        .pdf-button[disabled]:hover,
        .download-button[disabled],
        .download-button[disabled]:hover {
            background-color: #6c757d;
            cursor: not-allowed;
        }

        /* Tabs */
        .tabs.main-tabs {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
            justify-content: space-around;
        }

        .tab.main-tab {
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            width: 50%;
            background-color: #e0e0e0;
            border-radius: 10px 10px 0 0;
        }

        .tab.main-tab:hover {
            background-color: #c9d1d9;
        }

        .tab.main-tab.active {
            background-color: #007bff;
            color: white;
        }

        .tabs.inner-tabs {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
            justify-content: space-around;
        }

        .tab.inner-tab {
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            width: 25%;
            background-color: #e0e0e0;
            border-radius: 10px 10px 0 0;
        }

        .tab.inner-tab:hover {
            background-color: #c9d1d9;
        }

        .tab.inner-tab.active {
            background-color: #007bff;
            color: white;
        }

        .main-tab-content,
        .inner-tab-content {
            display: none;

            opacity: 0;
            transform: translateX(-20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
            width: 100%;
            top: 0;
            left: 0;
            pointer-events: none; /* Prevent interaction when not active */
        }

        .main-tab-content.active,
        .inner-tab-content.active {
            opacity: 1;
            transform: translateX(0);
            position: relative;
            pointer-events: auto;
            display: block;
            animation: fadeIn 0.5s forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 60%;
            border-radius: 10px;
            animation: slideDown 0.5s ease-in-out;
        }

        @keyframes slideDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
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
            cursor: pointer;
        }

        .input-container {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin-bottom: 20px;
        }

        /* Styling for the input field */
        .input-container input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 30px;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .input-container input:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        }

        /* Placeholder styling */
        .input-container input::placeholder {
            color: #aaa;
            font-size: 14px;
        }

        /* Styling for the icon */
        .input-container i {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #007bff;
            font-size: 18px;
        }

        .confine-status-wrapper {
            margin-top: 15px;
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }

        .radio-group label {
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .radio-group input[type="radio"] {
            margin-right: 5px;
        }

        /* Generate Report Button Styling */
        .generate-report-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s, transform 0.3s;
            margin-bottom: 20px;
        }

        .generate-report-btn:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .generate-report-btn:active {
            transform: scale(0.95);
        }

        /* Spinner Overlay */
        #spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #007bff;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .form-container {
                width: 100%;
            }
        }

        .complaint-list-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            margin-bottom: 400px
        }
  /* Responsive adjustments */
  @media (max-width: 1200px) {
        .container {
            flex-direction: row;
        }

        .form-container {
            width: 48%;
        }

        .search-container button {
            margin-right: 200px;
        }
    }

    @media (max-width: 992px) {
        .form-container {
            width: 100%;
        }

        .form-group {
            flex-direction: column;
        }

        .form-group label {
            width: 100%;
        }

        .search-container {
            flex-direction: column;
            align-items: flex-start;
        }

        .search-container button {
            margin-right: 0;
            width: 100%;
            margin-top: 10px;
        }

        .radio-group {
            flex-direction: column;
        }

        .tabs.main-tabs,
        .tabs.inner-tabs {
            flex-direction: column;
        }

        .tab.main-tab,
        .tab.inner-tab {
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 768px) {
        .container {
            flex-direction: column;
        }

        .form-container {
            width: 100%;
        }

        .form-group {
            flex-direction: column;
        }

        .form-group label {
            width: 100%;
        }

        .search-container {
            flex-direction: column;
            align-items: stretch;
        }

        .search-container button {
            margin-right: 0;
            width: 100%;
            margin-top: 10px;
        }

        .radio-group {
            flex-direction: column;
        }

        .tabs.main-tabs,
        .tabs.inner-tabs {
            flex-direction: column;
        }

        .tab.main-tab,
        .tab.inner-tab {
            width: 100%;
            text-align: center;
        }

        .generate-report-btn {
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 576px) {
        .form-container {
            padding: 20px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            font-size: 12px;
            padding: 6px 8px;
        }

        .form-group button {
            padding: 8px 16px;
            font-size: 14px;
        }

        .search-input {
            font-size: 14px;
            padding: 10px;
        }

        .complaints-table th,
        .complaints-table td {
            padding: 10px;
            font-size: 12px;
        }

        .preview-button,
        .pdf-button,
        .download-button {
            padding: 4px 8px;
            font-size: 0.8rem;
        }

        .tabs.main-tabs,
        .tabs.inner-tabs {
            margin-bottom: 10px;
        }

        .tab.main-tab,
        .tab.inner-tab {
            padding: 8px 12px;
            font-size: 14px;
        }

        .modal-content {
            width: 90%;
            margin: 30% auto;
            padding: 15px;
        }

        .input-container input {
            padding: 8px 12px;
            font-size: 12px;
        }

        .generate-report-btn {
            padding: 8px 12px;
            font-size: 12px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border-width: 6px;
        }
    }

    .complaint-list-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        margin-bottom: 400px;
    }
    </style>

    <main class="main-content">
        <!-- Main Tabs for Add Complaint and Complaint List -->
        <div class="tabs main-tabs">
            <div class="tab main-tab active" onclick="showTab('add-complaint', this)">
                <i class="fas fa-plus-circle"></i> Add Complaint
            </div>
            <div class="tab main-tab" onclick="showTab('complaint-table', this)">
                <i class="fas fa-list-alt"></i> Complaint List
            </div>
        </div>

        <!-- Main Tab Contents -->
        <div id="add-complaint" class="main-tab-content active">
            <div class="container">
                <div class="form-container">
                    <h2>Add Complaint</h2>

                    <!-- Search section -->
                    <div class="search-container">
                        <div class="form-group">
                            <label for="id_number">
                                <i class="fas fa-id-card"></i> ID Number
                            </label>
                            <div class="input-container">
                                <input type="text" id="id_number" name="id_number" placeholder="Enter ID Number" maxlength="7">
                            </div>
                        </div>

                        <button type="button" onclick="fetchPersonData()">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>

                    <form id="complaint-form" action="{{ route('doctor.complaint.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="role" id="role" value="">
                        <input type="hidden" name="year" value="{{ date('Y') }}">
                        <input type="hidden" name="id_number" id="hidden_id_number" value="">

                        <!-- First Name and Last Name fields -->
                        <div class="form-group">
                            <div class="input-wrapper">
                                <label for="first_name"><i class="fas fa-user"></i> First Name</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                            </div>
                            <div class="input-wrapper">
                                <label for="last_name"><i class="fas fa-user"></i> Last Name</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                            </div>
                        </div>

                        <!-- Age and Birthdate fields -->
                        <div class="form-group">
                            <div class="input-wrapper">
                                <label for="age"><i class="fas fa-hourglass-half"></i> Age</label>
                                <input type="number" id="age" name="age" required>
                            </div>
                            <div class="input-wrapper">
                                <label for="birthdate"><i class="fas fa-calendar"></i> Birthdate</label>
                                <input type="date" id="birthdate" name="birthdate" required>
                            </div>
                        </div>

                        <!-- Contact Number and Pain Assessment fields -->
                        <div class="form-group">
                            <div class="input-wrapper">
                                <label for="personal_contact_number"><i class="fas fa-phone"></i> Personal Contact Number</label>
                                <input type="text" id="personal_contact_number" name="personal_contact_number" value="">
                            </div>
                            <div class="input-wrapper">
                                <label for="pain_assessment"><i class="fas fa-thermometer-half"></i> Pain Assessment (1 to 10)</label>
                                <select id="pain_assessment" name="pain_assessment" required>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Medicine Given field -->
                        <div class="form-group">
                            <div class="input-wrapper">
                                <label for="medicine_given"><i class="fas fa-pills"></i> Medicine Given</label>
                                <select id="medicine_given" name="medicine_given" required>
                                    <option value="">Select Medicine</option>
                                    <!-- Options will be populated via AJAX -->
                                </select>
                            </div>
                        </div>

                        <!-- Description of Sickness and Confine Status fields -->
                        <div class="form-group">
                            <div class="textarea-wrapper">
                                <label for="sickness_description"><i class="fas fa-notes-medical"></i> Description of Sickness</label>
                                <textarea id="sickness_description" name="sickness_description" rows="4" required></textarea>
                            </div>

                            <!-- Confine Status -->
                            <div class="confine-status-wrapper">
                                <label for="confine_status"><i class="fas fa-bed"></i> Confine Status</label>
                                <div class="radio-group">
                                    <label>
                                        <input type="radio" name="confine_status" value="confined" required> Confined
                                    </label>
                                    <label>
                                        <input type="radio" name="confine_status" value="not_confined" required> Not Confined
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Go Home Status -->
                        <div class="form-group">
                            <label for="go_home"><i class="fas fa-home"></i> Go Home Status</label>
                            <div class="radio-group">
                                <label>
                                    <input type="radio" name="go_home" value="yes" required> Yes
                                </label>
                                <label>
                                    <input type="radio" name="go_home" value="no" required> No
                                </label>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="form-group">
                            <button type="submit">
                                <i class="fas fa-paper-plane"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
                <div class="form-container">
                    <div class="table-container">
                        <div class="statistics-container">
                            <h3>Statistics</h3>
                            <div class="statistics-item">
                                <span>Most Common Complaint: </span>{{ $mostCommonComplaint }}
                            </div>
                            <div class="statistics-item">
                                <span>Most Used Medicine: </span>{{ $mostUsedMedicine }}
                            </div>
                            <div class="chart-container">
                                <canvas id="complaint-chart"></canvas>
                            </div>
                        </div>
                    </div>
                     <!-- Generate Report -->
                     <div class="generate-report-container">
                                <h2>Generate Complaint Statistics Report</h2>
                                <form id="report-form" method="GET" action="{{ route('doctor.complaint.statisticsReport') }}">
                                @csrf
                                <div class="form-group">
    <label for="report-period">Select Report Period</label>
    <select id="report-period" name="report_period" required>
        <option value="">-- Select Period --</option>
        <option value="daily">Daily</option>
        <option value="weekly">Weekly</option> <!-- Corrected -->
        <option value="monthly">Monthly</option> <!-- Corrected -->
    </select>
</div>
                                    <div class="form-group">
                                        <label for="report-date">Select Date</label>
                                        <input type="date" id="report-date" name="report_date" required>
                                    </div>
                                    <div class="form-group">
                                        <button type="button" class="generate-report-btn" onclick="generateComplaintsReport()">Generate Report</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Complaint List Tab Content -->
        <div id="complaint-table" class="main-tab-content">
            <div class="complaint-list-container">
                <!-- Inner Tabs for Complaint Roles -->
                <div class="tabs inner-tabs">
                    <div class="tab inner-tab active" onclick="showInnerTab('student-complaints', this)">Student Complaints</div>
                    <div class="tab inner-tab" onclick="showInnerTab('staff-complaints', this)">Staff Complaints</div>
                    <div class="tab inner-tab" onclick="showInnerTab('teacher-complaints', this)">Teacher Complaints</div>
                </div>

                <!-- Inner Tab Contents -->
                <!-- Student Complaints Tab -->
                <div id="student-complaints" class="inner-tab-content active">
                    <h2>Student Complaints</h2>
                    <!-- Search bar for student complaints -->
                    <div class="table-container">
                        <table class="complaints-table" id="student-complaints-table">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Complaint Date</th> <!-- New Column -->
                    <th>Confine Status</th> <!-- New Column -->
                    <th>Go Home Status</th> <!-- New Column -->
                                    <th>Description of Sickness</th>
                                    <th>Pain Assessment</th>
                                    <th>Medicine Given</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($studentComplaints as $complaint)
                                    <tr id="complaint-row-{{ $complaint->id }}">
                                        <td>{{ $complaint->first_name }}</td>
                                        <td>{{ $complaint->last_name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($complaint->created_at)->format('Y-m-d') }}</td> <!-- Updated -->
                        <td>{{ ucfirst($complaint->confine_status) }}</td> <!-- Confine Status -->
                        <td>{{ ucfirst($complaint->go_home ?? 'N/A') }}</td> <!-- Go Home Status -->
                                        <td>{{ $complaint->sickness_description }}</td>
                                        <td>{{ $complaint->pain_assessment }}</td>
                                        <td>{{ $complaint->medicine_given }}</td>
                                        <td>
                                            <button class="preview-button" onclick="openModal({{ $complaint->id }})">Preview</button>
                                            @if($complaint->report_url)
                                                <a href="{{ $complaint->report_url }}" target="_blank" class="pdf-button">
                                                    <i class="fas fa-file-pdf"></i> View PDF
                                                </a>
                                             
                                            @else
                                                <button class="pdf-button" disabled title="PDF not available">
                                                    <i class="fas fa-file-pdf"></i> View PDF
                                                </button>
                                               
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Staff Complaints Tab -->
                <div id="staff-complaints" class="inner-tab-content">
                    <h2>Staff Complaints</h2>
                    <div class="table-container">
                        <table class="complaints-table" id="staff-complaints-table">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Complaint Date</th> <!-- New Column -->
                    <th>Confine Status</th> <!-- New Column -->
                    <th>Go Home Status</th> <!-- New Column -->
                                    <th>Description of Sickness</th>
                                    <th>Pain Assessment</th>
                                    <th>Medicine Given</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($staffComplaints as $complaint)
                                    <tr id="complaint-row-{{ $complaint->id }}">
                                        <td>{{ $complaint->first_name }}</td>
                                        <td>{{ $complaint->last_name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($complaint->created_at)->format('Y-m-d') }}</td> <!-- Updated -->
                        <td>{{ ucfirst($complaint->confine_status) }}</td> <!-- Confine Status -->
                        <td>{{ ucfirst($complaint->go_home ?? 'N/A') }}</td> <!-- Go Home Status -->
                                        <td>{{ $complaint->sickness_description }}</td>
                                        <td>{{ $complaint->pain_assessment }}</td>
                                        <td>{{ $complaint->medicine_given }}</td>
                                        <td>
                                            <button class="preview-button" onclick="openModal({{ $complaint->id }})">Preview</button>
                                            @if($complaint->report_url)
                                                <a href="{{ $complaint->report_url }}" target="_blank" class="pdf-button">
                                                    <i class="fas fa-file-pdf"></i> View PDF
                                                </a>
                                             
                                            @else
                                                <button class="pdf-button" disabled title="PDF not available">
                                                    <i class="fas fa-file-pdf"></i> View PDF
                                                </button>
                                              
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

             
                <!-- Teacher Complaints Tab -->
                <div id="teacher-complaints" class="inner-tab-content">
                    <h2>Teacher Complaints</h2>
                    <div class="table-container">
                        <table class="complaints-table" id="teacher-complaints-table">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Description of Sickness</th>
                                    <th>Complaint Date</th> <!-- New Column -->
                    <th>Confine Status</th> <!-- New Column -->
                    <th>Go Home Status</th> <!-- New Column -->
                                    <th>Pain Assessment</th>
                                    <th>Medicine Given</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($teacherComplaints as $complaint)
                                    <tr id="complaint-row-{{ $complaint->id }}">
                                        <td>{{ $complaint->first_name }}</td>
                                        <td>{{ $complaint->last_name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($complaint->created_at)->format('Y-m-d') }}</td> <!-- Updated -->
                        <td>{{ ucfirst($complaint->confine_status) }}</td> <!-- Confine Status -->
                        <td>{{ ucfirst($complaint->go_home ?? 'N/A') }}</td> <!-- Go Home Status -->
                                        <td>{{ $complaint->sickness_description }}</td>
                                        <td>{{ $complaint->pain_assessment }}</td>
                                        <td>{{ $complaint->medicine_given }}</td>
                                        <td>
                                            <button class="preview-button" onclick="openModal({{ $complaint->id }})">Preview</button>
                                            @if($complaint->report_url)
                                                <a href="{{ $complaint->report_url }}" target="_blank" class="pdf-button">
                                                    <i class="fas fa-file-pdf"></i> View PDF
                                                </a>
                                             
                                            @else
                                                <button class="pdf-button" disabled title="PDF not available">
                                                    <i class="fas fa-file-pdf"></i> View PDF
                                                </button>
                                        
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal for Complaint Preview -->
    <div id="complaint-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div id="modal-body"></div>
        </div>
    </div>

    <!-- Spinner Overlay -->
    <div id="spinner-overlay">
        <div class="spinner"></div>
    </div>

    <script>
            let complaintChart;

        document.addEventListener('DOMContentLoaded', function() {
            $('#student-complaints-table').DataTable();
            $('#staff-complaints-table').DataTable();
            $('#teacher-complaints-table').DataTable();
            fetchAvailableMedicines();
            renderChart();
        });

        // Function to switch between main tabs
        function showTab(tabId, element) {
            console.log(`Switching to tab: ${tabId}`);

            // Hide all main tab contents
            const tabContents = document.querySelectorAll('.main-tab-content');
            tabContents.forEach(tabContent => {
                if (tabContent.id !== tabId) {
                    tabContent.classList.remove('active');
                }
            });

            // Remove active class from all main tabs
            const tabs = document.querySelectorAll('.main-tab');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            // Show the selected main tab content with animation
            const selectedTabContent = document.getElementById(tabId);
            if (selectedTabContent) {
                selectedTabContent.classList.add('active');
            } else {
                console.error(`Tab content with id ${tabId} not found.`);
            }

            // Add active class to the selected main tab
            element.classList.add('active');
        }

        // Function to switch between inner complaint tabs
        function showInnerTab(tabId, element) {
            console.log(`Switching to inner tab: ${tabId}`);

            // Hide all inner tab contents
            const innerTabContents = document.querySelectorAll('.inner-tab-content');
            innerTabContents.forEach(innerTabContent => {
                if (innerTabContent.id !== tabId) {
                    innerTabContent.classList.remove('active');
                }
            });

            // Remove active class from all inner tabs
            const innerTabs = document.querySelectorAll('.inner-tab');
            innerTabs.forEach(tab => {
                tab.classList.remove('active');
            });

            // Show the selected inner tab content with animation
            const selectedInnerTabContent = document.getElementById(tabId);
            if (selectedInnerTabContent) {
                selectedInnerTabContent.classList.add('active');
            } else {
                console.error(`Inner tab content with id ${tabId} not found.`);
            }

            // Add active class to the selected inner tab
            element.classList.add('active');
        }

        // Fetch available medicines
        function fetchAvailableMedicines() {
            fetch('{{ route('doctor.inventory.available-medicines') }}')
                .then(response => response.json())
                .then(data => {
                    const medicineSelect = document.getElementById('medicine_given');
                    medicineSelect.innerHTML = '<option value="">Select Medicine</option>';
                    data.forEach(medicine => {
                        const option = document.createElement('option');
                        option.value = medicine;
                        option.textContent = medicine;
                        medicineSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching medicines:', error);
                });
        }
    
        // Fetch person data based on ID number
        function fetchPersonData() {
            const idNumber = document.getElementById('id_number').value;

            if (!idNumber) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please enter an ID number.'
                });
                return;
            }

            fetch(`/doctor/complaint/person/${idNumber}`)
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.error || 'Unknown error');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('first_name').value = data.first_name || '';
                    document.getElementById('last_name').value = data.last_name || '';
                    document.getElementById('age').value = data.age || '';
                    document.getElementById('birthdate').value = data.birthdate || '';
                    document.getElementById('personal_contact_number').value = data.personal_contact_number || '';
                    document.getElementById('role').value = data.role || '';
                    document.getElementById('hidden_id_number').value = data.id_number || '';

                    Swal.fire({
                        icon: 'success',
                        title: 'Person Data Fetched',
                        text: 'Person data successfully fetched.'
                    });
                })
                .catch(error => {
                    console.error('Error fetching person data:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred: ' + error.message
                    });
                });
        }

        // Open modal to preview complaint details
        function openModal(complaintId) {
            fetch(`/doctor/complaint/${complaintId}`)
                .then(response => response.json())
                .then(data => {
                    const modalBody = document.getElementById('modal-body');
                    modalBody.innerHTML = `
                        <p><strong>First Name:</strong> ${data.first_name}</p>
                        <p><strong>Last Name:</strong> ${data.last_name}</p>
                        <p><strong>Description of Sickness:</strong> ${data.sickness_description}</p>
                        <p><strong>Pain Assessment:</strong> ${data.pain_assessment}</p>
                        <p><strong>Confine Status:</strong> ${data.confine_status}</p>
                        <p><strong>Status:</strong> ${data.status}</p>
                        ${data.pdf_url ? `
                            <a href="${data.pdf_url}" target="_blank" class="pdf-link">
                                <i class="fas fa-file-pdf"></i> View PDF
                            </a>
                        ` : ''}
                    `;
                    document.getElementById('complaint-modal').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error fetching complaint details:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message
                    });
                });
        }

        // Close modal
        function closeModal() {
            document.getElementById('complaint-modal').style.display = 'none';
        }
        function renderChart() {
    fetch('{{ route('doctor.complaint.statistics') }}', {
        method: 'GET',
        credentials: 'same-origin', // Ensure cookies are sent
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        const ctx = document.getElementById('complaint-chart').getContext('2d');

        // Destroy existing chart instance if it exists
        if (complaintChart) {
            complaintChart.destroy();
        }

        // Create a new chart instance
        complaintChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Most Common Complaint', 'Most Used Medicine'],
                datasets: [{
                    label: 'Occurrences',
                    data: [data.commonComplaintCount, data.mostUsedMedicineCount],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    })
    .catch(error => {
        console.error('Error fetching statistics:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to load chart data. Please try again later.'
        });
    });
}

       // Handle complaint form submission with AJAX and SweetAlert
document.getElementById('complaint-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    const form = event.target;
    const formData = new FormData(form); // Collect form data

    Swal.fire({
        title: 'Submit Complaint',
        text: "Are you sure you want to submit this complaint?",
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

            fetch("{{ route('doctor.complaint.store') }}", { // Use the admin route here
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                Swal.close(); // Close the loading spinner

                if (data.success) {
                    // Clear all form fields
                    form.reset();

                    // Prepare the new complaint row HTML
                    let newRow = `
                        <tr id="complaint-row-${data.complaint_id}">
                            <td>${data.first_name}</td>
                            <td>${data.last_name}</td>
                            <td>${new Date().toISOString().split('T')[0]}</td> <!-- Record Date (current date) -->
                            <td>${capitalizeFirstLetter(data.confine_status)}</td> <!-- Confine Status -->
                            <td>${capitalizeFirstLetter(data.go_home)}</td> <!-- Go Home Status -->
                            <td>${data.sickness_description}</td>
                            <td>${data.pain_assessment}</td>
                            <td>${data.medicine_given}</td>
                            <td>
                                <button class="preview-button" onclick="openModal(${data.complaint_id})">Preview</button>
                                ${data.report_url ? `
                                    <a href="${data.report_url}" target="_blank" class="pdf-button">
                                        <i class="fas fa-file-pdf"></i> View PDF
                                    </a>
                                    <a href="${data.report_url}" download class="download-button">
                                        <i class="fas fa-download"></i> Download PDF
                                    </a>
                                ` : `
                                    <button class="pdf-button" disabled title="PDF not available">
                                        <i class="fas fa-file-pdf"></i> View PDF
                                    </button>
                                    <button class="download-button" disabled title="PDF not available">
                                        <i class="fas fa-download"></i> Download PDF
                                    </button>
                                `}
                            </td>
                        </tr>
                    `;

                    // Append the new row to the appropriate table based on role
                    const role = data.role.toLowerCase();
                    let tableId = '';

                    switch(role) {
                        case 'student':
                            tableId = 'student-complaints-table';
                            break;
                        case 'staff':
                            tableId = 'staff-complaints-table';
                            break;
                        case 'teacher':
                            tableId = 'teacher-complaints-table';
                            break;
                        default:
                            tableId = 'student-complaints-table'; // Default to student
                    }

                    document.querySelector(`#${tableId} tbody`).insertAdjacentHTML('beforeend', newRow);

                    // Optionally, update statistics and charts
                    renderChart();

                    // SweetAlert success message with option to view the PDF
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Complaint submitted successfully.',
                        showCancelButton: data.report_url ? true : false,
                        confirmButtonText: data.report_url ? 'View Report' : 'Close',
                        cancelButtonText: 'Close',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed && data.report_url) {
                            window.open(data.report_url, '_blank');
                        }
                    });
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        let errorMessages = '';
                        for (let key in data.errors) {
                            errorMessages += `${data.errors[key][0]}<br>`;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: errorMessages
                        });
                    } else {
                        // SweetAlert error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'An error occurred while submitting the form.'
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);

                // SweetAlert error message for any network issues
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong. Please try again later.'
                });
            });
        }
    });
});




        //
function generateComplaintsReport() {
    const reportPeriod = document.getElementById('report-period').value;
    const reportDate = document.getElementById('report-date').value;

    // Front-end Validation
    if (!reportPeriod || !reportDate) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please select both report period and date.'
        });
        return;
    }

    // Confirmation Prompt
    Swal.fire({
        title: 'Generate Report',
        text: `Do you want to generate a ${capitalizeFirstLetter(reportPeriod)} report for ${reportDate}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, generate it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading spinner
            Swal.fire({
                title: 'Generating Report...',
                text: 'Please wait while your report is being generated.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Construct the URL with query parameters
            const url = new URL("{{ route('doctor.complaint.statisticsReport') }}", window.location.origin);
            url.searchParams.append('report_period', reportPeriod);
            url.searchParams.append('report_date', reportDate);

            fetch(url, { // Use GET with query parameters
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Unknown error');
                    });
                }
                return response.json();
            })
            .then(data => {
                Swal.close(); // Close the loading spinner

                if (data.success) {
                    // Automatically open the generated PDF in a new tab
                    window.open(data.report_url, '_blank');

                    // Optional: Notify the user
                    Swal.fire({
                        icon: 'success',
                        title: 'Report Generated',
                        text: 'Your Complaints Statistics Report has been generated successfully and opened in a new tab.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'An error occurred while generating the report.'
                    });
                }
            })
            .catch(error => {
                console.error('Error generating report:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'An unexpected error occurred while generating the report.'
                });
            });
        }
    });
}

        // Generate Appointment Statistics Report with SweetAlert

    </script>
</x-app-layout>
