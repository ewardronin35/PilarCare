<x-app-layout :pageTitle="'Complaints'">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- SweetAlert2 for Alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart.js for Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


    <style>
        /* Existing Styles */
        body {
            font-family: 'Poppins', sans-serif;
        }

        .main-content {
            margin-top: 30px;
            width: calc(100% - 80px);

        }

        .container {
            display: flex;
            justify-content: center; /* Centers the form horizontally */
            align-items: center; /* Centers the form vertically */
            font-family: 'Poppins', sans-serif;
            flex-wrap: wrap;
        }

        /* Styling for the Form */
        .form-container {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%; /* Full width on smaller screens */
            max-width: 1000px; /* Limit width for better readability */
            box-sizing: border-box;
            margin-top: 10px;
        }
        .form-containerd {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%; /* Full width on smaller screens */
            box-sizing: border-box;
            margin-top: 10px;
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

        /* Revised Search Button Styling */
        .search-container .search-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px; /* Adjust padding to ensure icon fits well */
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 14px;
            white-space: nowrap; /* Prevent text wrapping */
            transition: background-color 0.3s ease, transform 0.3s;
        }

        .search-container .search-button i {
            margin-right: 6px; /* Space between icon and text */
            font-size: 16px; /* Adjust icon size if necessary */
            position: relative;
            top: 6px; /* Aligns icon slightly above to match text baseline */
        }

        .search-container .search-button:hover {
            background-color: #00b8e6;
        }

        .search-container .search-button:active {
            transform: scale(0.95);
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
            padding: 8px 12px;
            border: none;
            border-radius: 50px;
            margin-left: 10px;
            cursor: pointer;
            font-size: 14px;
            width: auto;
            transition: background-color 0.3s ease, transform 0.3s;
        }

        .search-container button:hover {
            background-color: #00b8e6;
        }

        .search-container button:active {
            transform: scale(0.95);
        }

        .table-container {
            width: 100%;
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

        /* Button Color Adjustments */
        .preview-button {
            background-color: #6c757d; /* Gray */
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

        .preview-button:hover {
            background-color: #5a6268;
        }

        .pdf-button {
            background-color: #007bff; /* Blue */
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
            margin-left: 5px; /* Space between buttons */
        }

        .pdf-button:hover {
            background-color: #0069d9;
        }

        .download-button {
            background-color: #28a745; /* Green */
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
            margin-left: 5px; /* Space between buttons */
        }

        .download-button:hover {
            background-color: #218838;
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
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            max-width: 800px; /* Limit the width of the tabs */
        }

        .tab.main-tab {
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            flex: 1; /* Allow tabs to evenly distribute */
            background-color: #e0e0e0;
            border-radius: 10px 10px 0 0;
            margin: 0 5px;
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
            margin-top: 20px;
            justify-content: space-around;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
        }

        .tab.inner-tab {
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            width: 33.33%; /* Equally divides the space among three tabs */
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
            transition: opacity 0.5s ease;
            width: 100%;
            pointer-events: none; /* Prevent interaction when not active */
        }

        .main-tab-content.active,
        .inner-tab-content.active {
            opacity: 1;
            display: block;
            pointer-events: auto;
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
            margin: 10% auto;
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
/* Fade-in and Fade-out Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeOut {
    from { opacity: 1; }
    to { opacity: 0; }
}

/* Classes to Trigger Animations */
.fade-in {
    animation: fadeIn 0.5s forwards;
}

.fade-out {
    animation: fadeOut 0.5s forwards;
}
.main-tab-content,
.inner-tab-content {
    opacity: 0;
    display: none;
    width: 100%;
}

.main-tab-content.active,
.inner-tab-content.active {
    display: block;
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
                margin-left: 0;
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

            .spinner {
                width: 40px;
                height: 40px;
                border-width: 6px;
            }
        }

        @media (max-width: 1200px) {
            .container {
                flex-direction: row;
            }

            .form-container {
                width: 48%;
            }

            .search-container button {
                margin-left: 10px;
            }
        }

        .complaint-list-container {
            margin: 0 auto;
            padding: 0 20px;
        }

        .statistics-container {
            width: 100%;
        }

        .statistics-item {
            margin-bottom: 15px;
        }

        .statistics-item span {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        /* Adjust chart labels */
        .chart-container canvas {
            width: 100% !important;
            height: auto !important;
        }

        /* New Styles for Two-Column Layout in Statistics */
        .statistics-layout {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
}
.statistics-middle {
    flex: 1;
    min-width: 300px;
    display: flex;
    justify-content: center;
    align-items: center;
}

        .statistics-left,
        .statistics-right {
            flex: 1;
            min-width: 300px;
        }

        .statistics-left {
            /* Additional styling if needed */
        }

        .statistics-right {
            /* Additional styling if needed */
        }

        /* Ensure Generate Report form takes full width in right column */
        .generate-report-container {
            background-color: #f1f1f1;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            height: fit-content;
        }
        .prediction-container {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px; /* Adjust as needed */
    margin-bottom: 416px;
}

.prediction-container h3 {
    text-align: center;
    margin-bottom: 15px;
}

.prediction-item {
    margin-bottom: 15px;
}

.prediction-item span {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}
.chart-container {
    width: 100%;
    height: 200px; /* Adjust height for prediction charts */
}

/* Responsive Adjustments */
@media (max-width: 1200px) {
    .statistics-layout {
        flex-direction: column;
        align-items: center;
    }

    .statistics-left,
    .statistics-middle,
    .statistics-right {
        width: 100%;
        max-width: none;
    }

    .statistics-middle {
        margin: 20px 0;
    }
}
    </style>

    <main class="main-content">
        <!-- Main Tabs for Add Complaint, Complaint List, and Statistics -->
        <div class="tabs main-tabs">
            <div class="tab main-tab active" onclick="showTab('add-complaint', this)">
                <i class="fas fa-plus-circle"></i> Add Complaint
            </div>
            <div class="tab main-tab" onclick="showTab('complaint-table', this)">
                <i class="fas fa-list-alt"></i> Complaint List
            </div>
            <div class="tab main-tab" onclick="showTab('statistics', this)">
                <i class="fas fa-chart-bar"></i> Statistics
            </div>
        </div>

        <!-- Main Tab Contents -->
        <!-- Add Complaint Tab -->
        <div id="add-complaint" class="main-tab-content active">
            <div class="container">
                <div class="form-container">
                    <h2>Add Complaint</h2>

                    <!-- Search section with adjusted button alignment -->
                    <div class="search-container">
                        <div class="form-group">
                            <label for="id_number">
                                <i class="fas fa-id-card"></i> ID Number
                            </label>
                            <div class="input-container" style="display: flex; align-items: center; width: 100%;">
                                <input type="text" id="id_number" name="id_number" placeholder="Enter ID Number" maxlength="7" style="flex: 1;">
                                <button type="button" onclick="fetchPersonData()" class="search-button">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </div>

                    <form id="complaint-form" action="{{ route('nurse.complaint.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="role" id="role" value="">
                        <input type="hidden" name="year" value="{{ date('Y') }}">
                        <input type="hidden" name="id_number" id="hidden_id_number" value="">
                        <input type="hidden" name="first_name" id="first_name" value="">
                        <input type="hidden" name="last_name" id="last_name" value="">

                        <!-- Full Name field -->
                        <div class="form-group">
                            <div class="input-wrapper">
                                <label for="full_name"><i class="fas fa-user"></i> Full Name</label>
                                <input type="text" id="full_name" name="full_name" placeholder="First Name Last Name" value="{{ old('full_name') }}" required readonly>
                            </div>
                        </div>

                        <!-- Grade/Course and Section fields -->
                        <div class="form-group">
                        <div class="input-wrapper">
        <label for="grade_course"><i class="fas fa-school"></i> Grade/Course</label>
        <input type="text" id="grade_course" name="grade_course" placeholder="Enter Grade or Course" required>
    </div>
                            <div class="input-wrapper">
                                <label for="section"><i class="fas fa-users"></i> Section</label>
                                <input type="text" id="section" name="section" placeholder="Enter Section" required>
                            </div>
                        </div>

                        <!-- Medicine Given and Pain Assessment fields -->
                        <div class="form-group">
                            <div class="input-wrapper">
                                <label for="medicine_given"><i class="fas fa-pills"></i> Medicine Given</label>
                                <select id="medicine_given" name="medicine_given" required>
                                    <option value="">Select Medicine</option>
                                    <!-- Options will be populated via AJAX -->
                                </select>
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

                        <!-- Description of Sickness field -->
                        <div class="form-group">
                            <div class="input-wrapper">
                                <label for="sickness_description"><i class="fas fa-notes-medical"></i> Description of Sickness</label>
                                <textarea id="sickness_description" name="sickness_description" rows="4" required></textarea>
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
            </div>
        </div>

        <!-- Complaint List Tab -->
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
                    <div class="table-container">
                        <table class="complaints-table" id="student-complaints-table">
                            <thead>
                                <tr>
                                <th>Complaint Date</th>
                                <th>Complaint Time </th>
                                    <th>Full Name</th>
                                    <th>Year and Section</th>
                                    <th>Description of Sickness</th>
                                    <th>Medicine Given</th>
                                    <th>Pain Assessment</th>
                                    <th>Did Student Go Home?</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($studentComplaints as $complaint)
                                    <tr id="complaint-row-{{ $complaint->id }}">
                                    <td>{{ \Carbon\Carbon::parse($complaint->created_at)->format('Y-m-d') }}</td>
                                    <td><time datetime="{{ $complaint->created_at->toIso8601String() }}">{{ $complaint->created_at->format('h:i A') }}</time></td>                                        
                                    <td>{{ $complaint->first_name }} {{ $complaint->last_name }}</td>
                                        <td>{{ $complaint->grade_course ?? 'N/A' }} {{ $complaint->section ?? 'N/A' }}</td>
                                        <td>{{ $complaint->sickness_description }}</td>
                                        <td>{{ $complaint->medicine_given }}</td>
                                        <td>{{ $complaint->pain_assessment }}</td>
                                        <td>{{ ucfirst($complaint->go_home ?? 'N/A') }}</td>

                                        <td>
                                           
                                            @if($complaint->report_url)
                                               
                                                <a href="{{ $complaint->report_url }}" download class="download-button">
                                                    <i class="fas fa-download"></i> Download PDF
                                                </a>
                                            @else
                                               
                                                <button class="download-button" disabled title="PDF not available">
                                                    <i class="fas fa-download"></i> Download PDF
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
                                <th>Complaint Date</th>
                                <th>Complaint Time </th>
                                    <th>Full Name</th>
                                    <th>Description of Sickness</th>
                                    <th>Medicine Given</th>
                                    <th>Pain Assessment</th>
                                    <th>Did Student Go Home?</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($staffComplaints as $complaint)
                                <tr id="complaint-row-{{ $complaint->id }}">
                                    <td>{{ \Carbon\Carbon::parse($complaint->created_at)->format('Y-m-d') }}</td>
                                    <td><time datetime="{{ $complaint->created_at->toIso8601String() }}">{{ $complaint->created_at->format('h:i A') }}</time></td>                                        
                                    <td>{{ $complaint->first_name }} {{ $complaint->last_name }}</td>
                                        <td>{{ $complaint->sickness_description }}</td>
                                        <td>{{ $complaint->medicine_given }}</td>
                                        <td>{{ $complaint->pain_assessment }}</td>
                                        <td>{{ ucfirst($complaint->go_home ?? 'N/A') }}</td>

                                        <td>
                                           
                                            @if($complaint->report_url)
                                                <a href="{{ $complaint->report_url }}" target="_blank" class="pdf-button">
                                                    <i class="fas fa-file-pdf"></i> View PDF
                                                </a>
                                                <a href="{{ $complaint->report_url }}" download class="download-button">
                                                    <i class="fas fa-download"></i> Download PDF
                                                </a>
                                            @else
                                                <button class="pdf-button" disabled title="PDF not available">
                                                    <i class="fas fa-file-pdf"></i> View PDF
                                                </button>
                                                <button class="download-button" disabled title="PDF not available">
                                                    <i class="fas fa-download"></i> Download PDF
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
                                <th>Complaint Date</th>
                                <th>Complaint Time </th>
                                    <th>Full Name</th>
                                    <th>Description of Sickness</th>
                                    <th>Medicine Given</th>
                                    <th>Pain Assessment</th>
                                    <th>Did Student Go Home?</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($teacherComplaints as $complaint)
                                    <tr id="complaint-row-{{ $complaint->id }}">
                                        <td>{{ \Carbon\Carbon::parse($complaint->created_at)->format('Y-m-d') }}</td>
                                        <td><time datetime="{{ $complaint->created_at->toIso8601String() }}">{{ $complaint->created_at->format('h:i A') }}</time></td>                                        
                                        <td>{{ $complaint->first_name }} {{ $complaint->last_name }}</td>

                                        <td>{{ ucfirst($complaint->go_home ?? 'N/A') }}</td>
                                        <td>{{ $complaint->sickness_description }}</td>
                                        <td>{{ $complaint->pain_assessment }}</td>
                                        <td>{{ $complaint->medicine_given }}</td>
                                        <td>{{ $complaint->grade_course ?? 'N/A' }}</td>
                                        <td>{{ $complaint->section ?? 'N/A' }}</td>
                                        <td>
                                            <button class="preview-button" onclick="openModal({{ $complaint->id }})">
                                                <i class="fas fa-eye"></i> Preview
                                            </button>
                                            @if($complaint->report_url)
                                                <a href="{{ $complaint->report_url }}" target="_blank" class="pdf-button">
                                                    <i class="fas fa-file-pdf"></i> View PDF
                                                </a>
                                                <a href="{{ $complaint->report_url }}" download class="download-button">
                                                    <i class="fas fa-download"></i> Download PDF
                                                </a>
                                            @else
                                                <button class="pdf-button" disabled title="PDF not available">
                                                    <i class="fas fa-file-pdf"></i> View PDF
                                                </button>
                                                <button class="download-button" disabled title="PDF not available">
                                                    <i class="fas fa-download"></i> Download PDF
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
        </div>
    
        <!-- Statistics Tab -->
    <!-- Statistics Tab -->
<div id="statistics" class="main-tab-content">
    <div class="container">
        <div class="form-containerd">
            <h2 style="margin-left: 800px;">Statistics</h2>

            <div class="statistics-layout">
                <!-- Left Side: Statistics -->
                <div class="statistics-left">
                    <!-- Statistics Chart -->
                    <div class="table-container">
                        <div class="statistics-container">
                            <h3>Top 3 Statistics</h3>
                            <div class="statistics-item">
                                <span>Top 3 Most Common Complaints:</span>
                                <ol>
                                    @foreach ($topComplaints as $complaint)
                                        <li>{{ $complaint->complaint }} ({{ $complaint->count }} occurrences)</li>
                                    @endforeach
                                </ol>
                            </div>
                            <div class="statistics-item">
                                <span>Top 3 Most Used Medicines:</span>
                                <ol>
                                    @foreach ($topMedicines as $medicine)
                                        <li>{{ $medicine->medicine }} ({{ $medicine->count }} usages)</li>
                                    @endforeach
                                </ol>
                            </div>
                            <div class="chart-container" style="height: 400px;">
                                <canvas id="complaint-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Middle: Predictions -->
                <div class="statistics-middle">
                    <div class="prediction-container">
                        <h3>Predictions</h3>
                        <div class="prediction-item">
                            <span>Next Likely Complaint Type:</span>
                            <p id="next-complaint">Loading...</p>
                        </div>
                        <div class="prediction-item">
                            <span>Most Likely Medicine to Be Used:</span>
                            <p id="likely-medicine">Loading...</p>
                        </div>
                        <!-- Prediction Charts -->
                        <div class="chart-container" style="height: 200px;">
                            <canvas id="prediction-chart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Generate Report -->
                <div class="statistics-right">
                    <!-- Generate Report -->
                    <div class="generate-report-container">
                        <h2>Generate Complaint Statistics Report</h2>
                        <form id="report-form" method="GET" action="{{ route('nurse.complaint.statisticsReport') }}">
                            @csrf
                            <div class="form-group">
                                <label for="report-period">Select Report Period</label>
                                <select id="report-period" name="report_period" required>
                                    <option value="">-- Select Period --</option>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
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
        let studentTable, staffTable, teacherTable;

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTables
            studentTable = $('#student-complaints-table').DataTable();
    staffTable = $('#staff-complaints-table').DataTable();
    teacherTable = $('#teacher-complaints-table').DataTable();
            // Fetch available medicines for the dropdown
            fetchAvailableMedicines();
            fetchPredictions();

            // Render statistics charts
            renderChart();
        });
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

    fetch(`/nurse/complaint/person/${idNumber}`)
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.error || 'Unknown error');
                });
            }
            return response.json();
        })
        .then(data => {
            // Log data to ensure the response structure is as expected
            console.log('Fetched Data:', data);

            // Combine first and last names
            const fullName = `${data.first_name || ''} ${data.last_name || ''}`.trim();

            document.getElementById('full_name').value = fullName || '';
            document.getElementById('role').value = data.role || '';
            document.getElementById('hidden_id_number').value = data.id_number || '';

            // Populate grade/course and section
            document.getElementById('grade_course').value = data.grade_course || '';
            document.getElementById('section').value = data.section || '';

            document.getElementById('first_name').value = data.first_name || '';
            document.getElementById('last_name').value = data.last_name || '';


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


        // Utility function to capitalize the first letter of a string
        function capitalizeFirstLetter(string) {
            if (typeof string !== 'string' || !string) {
                return 'N/A'; // Or any default value you prefer
            }
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        // Function to switch between main tabs
       // Function to switch between main tabs with fade animations
function showTab(tabId, element) {
    console.log(`Switching to tab: ${tabId}`);

    // Find the currently active main tab-content
    const activeTabContent = document.querySelector('.main-tab-content.active');
    if (activeTabContent) {
        // Start fade-out animation
        activeTabContent.classList.add('fade-out');

        // Listen for the end of the fade-out animation
        activeTabContent.addEventListener('animationend', function handleFadeOut() {
            // Remove 'active' and 'fade-out' classes
            activeTabContent.classList.remove('active', 'fade-out');
            activeTabContent.removeEventListener('animationend', handleFadeOut);

            // Show and fade-in the new tab-content
            const newTabContent = document.getElementById(tabId);
            newTabContent.classList.add('active', 'fade-in');

            // Remove 'fade-in' class after animation completes
            newTabContent.addEventListener('animationend', function handleFadeIn() {
                newTabContent.classList.remove('fade-in');
                newTabContent.removeEventListener('animationend', handleFadeIn);
            });
        });
    } else {
        // If no active tab-content, directly show and fade-in the new tab
        const newTabContent = document.getElementById(tabId);
        newTabContent.classList.add('active', 'fade-in');

        newTabContent.addEventListener('animationend', function handleFadeIn() {
            newTabContent.classList.remove('fade-in');
            newTabContent.removeEventListener('animationend', handleFadeIn);
        });
    }

    // Remove 'active' class from all main tabs and add to the clicked tab
    const tabs = document.querySelectorAll('.main-tab');
    tabs.forEach(tab => tab.classList.remove('active'));
    element.classList.add('active');
}

// Function to switch between inner tabs with fade animations
function showInnerTab(tabId, element) {
    console.log(`Switching to inner tab: ${tabId}`);

    // Find the currently active inner tab-content
    const activeInnerTabContent = document.querySelector('.inner-tab-content.active');
    if (activeInnerTabContent) {
        // Start fade-out animation
        activeInnerTabContent.classList.add('fade-out');

        // Listen for the end of the fade-out animation
        activeInnerTabContent.addEventListener('animationend', function handleFadeOut() {
            // Remove 'active' and 'fade-out' classes
            activeInnerTabContent.classList.remove('active', 'fade-out');
            activeInnerTabContent.removeEventListener('animationend', handleFadeOut);

            // Show and fade-in the new inner tab-content
            const newInnerTabContent = document.getElementById(tabId);
            newInnerTabContent.classList.add('active', 'fade-in');

            // Remove 'fade-in' class after animation completes
            newInnerTabContent.addEventListener('animationend', function handleFadeIn() {
                newInnerTabContent.classList.remove('fade-in');
                newInnerTabContent.removeEventListener('animationend', handleFadeIn);
            });
        });
    } else {
        // If no active inner tab-content, directly show and fade-in the new inner tab
        const newInnerTabContent = document.getElementById(tabId);
        newInnerTabContent.classList.add('active', 'fade-in');

        newInnerTabContent.addEventListener('animationend', function handleFadeIn() {
            newInnerTabContent.classList.remove('fade-in');
            newInnerTabContent.removeEventListener('animationend', handleFadeIn);
        });
    }

    // Remove 'active' class from all inner tabs and add to the clicked tab
    const innerTabs = document.querySelectorAll('.inner-tab');
    innerTabs.forEach(tab => tab.classList.remove('active'));
    element.classList.add('active');
}


     
        // Fetch available medicines from the server
        function fetchAvailableMedicines() {
            fetch('{{ route('nurse.inventory.available-medicines') }}')
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

       
        // Render Statistics Charts
        function renderChart() {
            fetch('{{ route('nurse.complaint.statistics') }}', {
                method: 'GET',
                credentials: 'same-origin',
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
                if (!data.topComplaints || !data.topMedicines) {
                    throw new Error('Invalid data structure received from the server.');
                }

                // Prepare labels and data for complaints and medicines
                const complaintLabels = data.topComplaints.map(item => item.complaint);
                const complaintCounts = data.topComplaints.map(item => item.count);
                
                const medicineLabels = data.topMedicines.map(item => item.medicine);
                const medicineCounts = data.topMedicines.map(item => item.count);

                const ctx = document.getElementById('complaint-chart').getContext('2d');

                if (complaintChart) {
                    complaintChart.destroy();
                }

                complaintChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: complaintLabels.concat(medicineLabels), // Combine labels for spacing
                        datasets: [
                            {
                                label: 'Top 3 Most Common Complaints',
                                data: complaintCounts.concat(new Array(medicineLabels.length).fill(null)), // Leave space for medicines
                                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Top 3 Most Used Medicines',
                                data: new Array(complaintLabels.length).fill(null).concat(medicineCounts), // Align medicines to the right
                                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        }
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

        function fetchPredictions() {
    fetch('{{ route('nurse.complaint.predictions') }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('next-complaint').textContent = data.next_complaint;
            document.getElementById('likely-medicine').textContent = data.likely_medicine;

            // Render Prediction Chart
            renderPredictionChart(data.next_complaint, data.likely_medicine);
        } else {
            document.getElementById('next-complaint').textContent = 'Prediction unavailable.';
            document.getElementById('likely-medicine').textContent = 'Prediction unavailable.';

            // Optionally, hide or clear the prediction chart
            if (window.predictionChart) {
                window.predictionChart.destroy();
                window.predictionChart = null;
            }
        }
    })
    .catch(error => {
        console.error('Error fetching predictions:', error);
        document.getElementById('next-complaint').textContent = 'Prediction unavailable.';
        document.getElementById('likely-medicine').textContent = 'Prediction unavailable.';

        // Optionally, hide or clear the prediction chart
        if (window.predictionChart) {
            window.predictionChart.destroy();
            window.predictionChart = null;
        }
    });
}


     // Render Prediction Chart using Chart.js
function renderPredictionChart(nextComplaint, likelyMedicine) {
    const ctx = document.getElementById('prediction-chart').getContext('2d');

    // Destroy existing chart if it exists to prevent duplication
    if (window.predictionChart) {
        window.predictionChart.destroy();
    }

    const data = {
        labels: ['Next Complaint Type', 'Likely Medicine'],
        datasets: [{
            label: 'Predictions',
            data: [1, 1], // Dummy data for representation
            backgroundColor: [
                'rgba(255, 99, 132, 0.6)',
                'rgba(54, 162, 235, 0.6)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)'
            ],
            borderWidth: 1
        }]
    };

    const options = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            tooltip: {
                callbacks: {
                    afterLabel: function(context) {
                        if (context.label === 'Next Complaint Type') {
                            return nextComplaint;
                        } else if (context.label === 'Likely Medicine') {
                            return likelyMedicine;
                        }
                    }
                }
            }
        },
        scales: {
            y: {
                display: false,
                beginAtZero: true
            }
        }
    };

    // Assign the chart to a global variable to manage its lifecycle
    window.predictionChart = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: options
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

            fetch("{{ route('nurse.complaint.store') }}", { // Use the admin route here
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

                    // Prepare the new complaint row data in correct order
                    let createdAt = new Date(data.created_at);

                    let complaintDate = createdAt.toLocaleDateString('en-CA'); // Format as 'YYYY-MM-DD'
                    let complaintTime = createdAt.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }); // Format as 'HH:MM AM/PM'

                    let fullName = `${data.first_name || ''} ${data.last_name || ''}`.trim();

                    let yearAndSection = `${data.grade_course || 'N/A'} ${data.section || 'N/A'}`;

                    let description = data.sickness_description || 'N/A';
                    let medicine = data.medicine_given || 'N/A';
                    let painAssessment = data.pain_assessment || 'N/A';
                    let goHome = capitalizeFirstLetter(data.go_home);

                    let actionButtons = `
                        <button class="preview-button" onclick="openModal(${data.complaint_id})">
                            <i class="fas fa-eye"></i> Preview
                        </button>
                        ${data.report_url ? `
                           
                            <a href="${data.report_url}" download class="download-button">
                                <i class="fas fa-download"></i> Download PDF
                            </a>
                        ` : `
                            
                            <button class="download-button" disabled title="PDF not available">
                                <i class="fas fa-download"></i> Download PDF
                            </button>
                        `}
                    `;

                    let newRowData = [
                        complaintDate,
                        complaintTime,
                        fullName || 'N/A',
                        yearAndSection,
                        description,
                        medicine,
                        painAssessment,
                        goHome,
                        actionButtons
                    ];

                    // Determine which table to update based on role
                    const role = data.role.toLowerCase();
                    let tableInstance;

                    switch(role) {
                        case 'student':
                            tableInstance = studentTable;
                            break;
                        case 'staff':
                            tableInstance = staffTable;
                            break;
                        case 'teacher':
                            tableInstance = teacherTable;
                            break;
                        default:
                            tableInstance = studentTable; // Default to student
                    }

                    // Add the new row using DataTables API
                    tableInstance.row.add(newRowData).draw(false);

                    // Optionally, update statistics and charts
                    renderChart();
                    fetchPredictions(); // Update predictions as data has changed

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

        // Generate Complaint Statistics Report with SweetAlert
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
                    const url = new URL("{{ route('nurse.complaint.statisticsReport') }}", window.location.origin);
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

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('complaint-modal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>

</x-app-layout>
