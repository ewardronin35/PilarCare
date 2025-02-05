<x-app-layout :pageTitle="'Medical Record'">   
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }

        .main-content {
            margin-left: 80px;
            margin-top: 30px;
            width: calc(100% - 80px);
            padding: 20px;
        }

        .tab.hidden {
            display: none;
        }

        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #ddd;
        }

        .tab-buttons button {
            padding: 10px 20px;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 1.1rem;
            color: #007bff;
            font-weight: bold;
            transition: color 0.3s, border-bottom 0.3s;
            margin-bottom: -2px;
            border-bottom: 3px solid transparent;
        }

        .tab-buttons button.active {
            color: #007bff;
            border-bottom: 3px solid #007bff;
        }

        .forms-container {
            display: flex;
            gap: 20px;
            width: 95%;
            height: 80%;
            margin: 0 auto;
        }

        .form-container {
            flex: 1;
            background-color: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            max-height: 75vh;
            border: 1px solid #eaeaea;
        }
        .form-containers {
            background-color: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            max-height: 75vh;
            border: 1px solid #eaeaea;
        }

        .form-header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }

        .form-header h2 {
            color: #007bff;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .profile-picture {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .profile-picture img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }

        #profile-picture-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }

        #profile-picture-button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-align: center;
        }

        #profile-picture-button:hover {
            background-color: #0056b3;
        }

        .profile-picture button {
            background-color: #007bff;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 0.9rem;
        }

        .profile-picture button:hover {
            background-color: #0056b3;
        }

        .profile-picture input[type="file"] {
            display: none;
        }

        .form-group-inline {
            display: flex;
            gap: 20px;
        }

        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            resize: none;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            color: #333;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        #profile-picture-preview {
    width: 150px; /* Set the same size as the .profile-img */
    height: 150px;
    border-radius: 50%; /* Rounded image */
    object-fit: cover;
    margin-bottom: 10px; /* Adds spacing between the image and the button */
}

        .form-group textarea {
            resize: none;
            height: 100px;
        }

        .form-section {
            margin-top: 20px;
        }

        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .tab {
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .tab.active {
            opacity: 1;
            display: block;
        }

        .checkbox-group label {
            display: flex;
            align-items: center;
            font-weight: 500;
            color: #555;
            gap: 5px;
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
        }

        .form-group button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            font-size: 1.1rem;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .bmi-result {
            margin-top: 10px;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .history-table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    margin-bottom: 20px;
    overflow: hidden; 
        }

        .history-table th,
        .history-table td {
            padding: 12px;
    text-align: left;
    font-size: 0.95rem;
    color: #333;
        }

        .history-table th {
            background-color: #007bff; /* Blue background for headers */
    color: white;
    font-weight: bold;
    border-right: 2px solid white;
        }
        .history-table tr:nth-child(even) {
    background-color: #f2f2f2;
}
.history-table th:last-child {
    border-right: none; /* Remove border for the last column header */
}
.history-table td {
    border: 1px solid #ddd;
}

.history-table tr:nth-child(even) {
    background-color: #f2f2f2;
}

.history-table tr:hover {
    background-color: #e9f1ff; /* Highlight row on hover */
}
        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-bar input[type="text"] {
            width: 350px;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-size: 1rem;
        }

        .search-bar button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-left: 10px;
            transition: background-color 0.3s;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }
        .file-input-container {
    position: relative;
    width: 100%;
    max-width: 300px;
    margin: 20px 0;
}

#medical_pictures {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    opacity: 0;
    cursor: pointer;
}

.file-input-label {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #007bff;
    color: white;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.file-input-label:hover {
    background-color: #0056b3;
}

.file-input-label i {
    margin-right: 8px;
}

.custom-picture-previews {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 10px;
}

.custom-picture-previews img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.custom-picture-previews img:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 10px rgba(0, 123, 255, 0.3);
}

.custom-picture-previews .image-wrapper {
    position: relative;
}

.custom-picture-previews .image-label {
    position: absolute;
    top: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    padding: 5px;
    font-size: 0.75rem;
    border-bottom-right-radius: 8px;
}
.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 1rem;
    text-align: center;
}

.alert-warning {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeeba;
}

.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}
/* Styling for the label */
.input-label {
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 8px;
    display: block;
    color: #333;
}

/* Styling for the textarea */
.styled-textarea {
    padding: 12px 15px;
    width: 90%;
    height: 100px;
    border: 1px solid #ccc;
    border-radius: 10px;
    font-size: 16px;
    margin-left: -30px;
    color: #333;
    background-color: #f9f9f9;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    resize: vertical;
}
.scrollable-container {
            max-height: 400px; /* Adjust as needed */
            overflow-y: auto;
            padding-right: 10px; /* To prevent content from hiding behind scrollbar */
        }

        /* Scrollbar Styling */
        .scrollable-container::-webkit-scrollbar {
            width: 8px;
        }

        .scrollable-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 8px;
        }

        .scrollable-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 8px;
        }

        .scrollable-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
/* On focus, change border color and box-shadow for emphasis */
.styled-textarea:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 3px 6px rgba(0, 123, 255, 0.2);
    background-color: #fff;
}

/* Styling for placeholder text */
.styled-textarea::placeholder {
    color: #aaa;
    font-size: 14px;
}
.history-scrollable::-webkit-scrollbar {
    width: 8px;
}

.history-scrollable::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 8px;
}

.history-scrollable::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 8px;
}

.history-scrollable::-webkit-scrollbar-thumb:hover {
    background: #555;
}
.history-scrollable {
    max-height: 400px; /* Adjust the height as needed */
    overflow-y: auto;
}
@media (max-width: 1200px) {
        .container {
            flex-direction: column;
            padding: 10px;
        }

        .main-content {
            margin-left: 0;
            width: 100%;
            padding: 15px;
        }

        .forms-container {
            flex-direction: column;
            gap: 15px;
            width: 100%;
            height: auto;
        }

        .form-container {
            max-height: none;
            height: auto;
        }

        .tabs {
            flex-wrap: wrap;
        }

        .tab-buttons button {
            flex: 1 1 45%;
            margin-bottom: 10px;
        }

        .search-bar input[type="text"] {
            width: 100%;
            max-width: none;
        }

        .search-bar button {
            width: 100%;
            margin-left: 0;
            margin-top: 10px;
        }

        .history-table th,
        .history-table td {
            padding: 10px;
            font-size: 0.9rem;
        }

        .styled-textarea {
            width: 100%;
            margin-left: 0;
        }

        .file-input-container {
            max-width: 100%;
        }

        .custom-picture-previews img {
            width: 80px;
            height: 80px;
        }
    }

    /* Tablet (max-width: 992px) */
    @media (max-width: 992px) {
        .tab-buttons button {
            flex: 1 1 100%;
        }

        .forms-container {
            flex-direction: column;
            gap: 10px;
        }

        .form-group-inline {
            flex-direction: column;
            gap: 10px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            font-size: 0.9rem;
            padding: 10px;
        }

        .form-group button {
            font-size: 1rem;
            padding: 8px 16px;
        }

        .search-bar input[type="text"] {
            font-size: 0.9rem;
            padding: 8px;
        }

        .search-bar button {
            font-size: 0.9rem;
            padding: 8px 16px;
        }

        .history-table th,
        .history-table td {
            padding: 8px;
            font-size: 0.85rem;
        }

        .custom-picture-previews img {
            width: 70px;
            height: 70px;
        }

        .styled-textarea {
            font-size: 0.9rem;
            padding: 10px 12px;
        }
    }

    /* Mobile Devices (max-width: 768px) */
    @media (max-width: 768px) {
        .forms-container {
            flex-direction: column;
            gap: 10px;
        }

        .form-container {
            padding: 20px;
        }

        .tab-buttons button {
            font-size: 1rem;
            padding: 8px 16px;
        }

        .form-group-inline {
            flex-direction: column;
            gap: 10px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            font-size: 0.85rem;
            padding: 8px;
        }

        .form-group button {
            font-size: 0.95rem;
            padding: 8px 14px;
        }

        .search-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-bar input[type="text"] {
            width: 100%;
            margin-bottom: 10px;
        }

        .search-bar button {
            width: 100%;
            margin-left: 0;
            padding: 8px 14px;
        }

        .history-table th,
        .history-table td {
            padding: 6px;
            font-size: 0.8rem;
        }

        .custom-picture-previews img {
            width: 60px;
            height: 60px;
        }

        .styled-textarea {
            font-size: 0.85rem;
            padding: 8px 10px;
        }

        .file-input-container {
            max-width: 100%;
        }
    }

    /* Small Mobile Devices (max-width: 576px) */
    @media (max-width: 576px) {
        .main-content {
            padding: 10px;
        }

        .tab-buttons button {
            font-size: 0.9rem;
            padding: 6px 12px;
        }

        .form-group-inline {
            flex-direction: column;
            gap: 8px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            font-size: 0.8rem;
            padding: 6px;
        }

        .form-group button {
            font-size: 0.9rem;
            padding: 6px 12px;
        }

        .search-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-bar input[type="text"] {
            font-size: 0.8rem;
            padding: 6px;
        }

        .search-bar button {
            font-size: 0.8rem;
            padding: 6px 12px;
        }

        .history-table th,
        .history-table td {
            padding: 4px;
            font-size: 0.75rem;
        }

        .custom-picture-previews img {
            width: 50px;
            height: 50px;
        }

        .styled-textarea {
            font-size: 0.75rem;
            padding: 6px 8px;
        }

        .file-input-label {
            font-size: 0.9rem;
            padding: 8px;
        }

        #profile-picture-preview {
            width: 120px;
            height: 120px;
        }
    }
    /* Add this to your existing <style> section */
#search-form {
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

#search-form.hidden {
    opacity: 0;
    visibility: hidden;
}

#search-form.visible {
    opacity: 1;
    visibility: visible;
}
/* Sub-Tabs Navigation */
.sub-tabs {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
    border-bottom: 2px solid #ddd;
    padding: 10px;
    gap: 20px;
}

.sub-tab-buttons button {
    background-color: transparent;
    color: #007bff;
    font-size: 1.1rem;
    font-weight: bold;
    padding: 10px 20px;
    border: none;
    border-bottom: 3px solid transparent;
    transition: all 0.3s ease;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
}


.sub-tab-buttons button.active {
    color: #007bff;
    border-bottom: 2px solid #007bff;
}

/* Sub-Tabs Content */
.sub-tab {
    opacity: 0;
    transition: opacity 0.4s ease;
}

.sub-tab.active {
    opacity: 1;
    display: block;
}

.sub-tab.hidden {
    display: none;
}

/* Adjust history-table margins for better spacing within sub-tabs */
#medical-record-history-table,
#physical-examination-history-table,
#health-examination-uploads-table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    margin-bottom: 20px;
    overflow: hidden;
}
#history.forms-container {
    flex-direction: column;
    align-items: center; /* Center the sub-tabs horizontally */
}

/* Ensure the sub-tab-content takes full width */
#history .sub-tab-content {
    width: 100%;
    margin-top: 20px; 
    margin-left: 150px;
    /* Add some space between sub-tabs and content */
}

/* Optional: Adjust the width of sub-tabs buttons for better alignment */
#history .sub-tabs {
    width: 100%;
}

#history .sub-tab-buttons {
    display: flex;
    justify-content: center;
    flex-wrap: wrap; /* Allow buttons to wrap on smaller screens */
    gap: 10px; /* Add space between buttons */
}
.sub-tab-content .history-table th,
.sub-tab-content .history-table td {
    padding: 12px;
    text-align: left;
    font-size: 0.95rem;
    color: #333;
}

.sub-tab-content .history-table th {
    background-color: #007bff;
    color: white;
    font-weight: bold;
    border-right: 2px solid white;
}

.sub-tab-content .history-table tr:nth-child(even) {
    background-color: #f2f2f2;
}

.sub-tab-content .history-table tr:hover {
    background-color: #e9f1ff;
}
.btn-view {
            background-color: #17a2b8; /* Bootstrap Info Color */
            color: white;
            margin-right: 5px;
        }
        
        .btn-view:hover {
            background-color: #138496;
            color: white;
        }
        
        .btn-download {
            background-color: #28a745; /* Bootstrap Success Color */
            color: white;
        }
        
        .btn-download:hover {
            background-color: #218838;
            color: white;
        }
        .button-with-icon {
    display: flex;
    align-items: center;
    gap: 5px;
}
.form-container, .history-table {
    background-color: #ffffff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #e0e0e0;
}
.button {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s;
}
.button:hover {
    background-color: #0056b3;
}
#physical-examination-history .form-container {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    gap: 20px;
}

.history-table-container {
    flex: 1;
    max-width: 50%;
}

.chart-container {
    flex: 1;
    max-width: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

@media (max-width: 992px) {
    #physical-examination-history .form-container {
        flex-direction: column;
        align-items: center;
    }

    .history-table-container,
    .chart-container {
        max-width: 100%;
    }
}

    </style>

    <div class="main-content">
            <div class="tabs">
            <div class="tab-buttons">
        <button id="medical-tab" class="active" onclick="showTab('medical')">
            <i class="fas fa-user-md"></i> Medical Record
        </button>
        <button id="history-tab" onclick="showTab('history')">
            <i class="fas fa-history"></i> Health History
        </button>
        <button id="all-records-tab" onclick="showTab('all-records')">
            <i class="fas fa-file-medical-alt"></i> All Records
        </button>
    </div>
</div>


        <form method="GET" action="{{ route('nurse.medical-record.search') }}" id="search-form">
           
        </form>

        <div id="medical" class="tab forms-container">
            <!-- Profile Information -->
            <div class="form-container">
                <div class="form-header">
                    <h2>Patient Information</h2>
                </div>

                 
                    <div class="form-group-inline">
                        <div class="form-group profile-picture">
                            <label for="profile_picture">Profile Picture</label>
                            <img id="profile-picture-preview" 
     src="{{ isset($profilePictureUrl) ? $profilePictureUrl : asset('images/pilarLogo.jpg') }}" 
     alt="Profile Picture" class="profile-img">                        </div>
                    </div>

                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ $record->name ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="birthdate">Birthdate</label>
                            <input type="date" id="birthdate" name="birthdate" value="{{ $record->birthdate ?? '' }}" required>
                        </div>
                    </div>

                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="number" id="age" name="age" value="{{ $age ?? '' }}" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" value="{{ $record->address ?? '' }}" required>
                        </div>
                    </div>

                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="father-name">Father's Name</label>
                            <input type="text" id="father-name" name="father_name" value="{{ $record->father_name ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="mother-name">Mother's Name</label>
                            <input type="text" id="mother-name" name="mother_name" value="{{ $record->mother_name ?? '' }}" required>
                        </div>
                    </div>

                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="personal-contact-number">Personal Contact Number</label>
                            <input type="text" id="personal-contact-number" name="personal_contact_number" value="{{ $record->personal_contact_number ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="emergency-contact-number">Emergency Contact Number</label>
                            <input type="text" id="emergency-contact-number" name="emergency_contact_number" value="{{ $record->emergency_contact_number ?? '' }}" required>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Medical Information -->
            <div class="form-container">
                <div class="form-header">
                    <h2>Medical Information</h2>
                </div>

                <form method="POST" action="">
                    @csrf
                    <div class="form-section">
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="past-illness">Past Illnesses/Injuries</label>
                            <input type="text" id="past-illness" name="past_illness" value="{{ $record->past_illness ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="chronic-conditions">Chronic Conditions</label>
                            <input type="text" id="chronic-conditions" name="chronic_conditions" value="{{ $record->chronic_conditions ?? '' }}" required>
                        </div>
                    </div>

                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="surgical-history">Surgical History</label>
                            <input type="text" id="surgical-history" name="surgical_history" value="{{ $record->surgical_history ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="family-medical-history">Family Medical History</label>
                            <input type="text" id="family-medical-history" name="family_medical_history" value="{{ $record->family_medical_history ?? '' }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="allergies">Allergies</label>
                        <input type="text" id="allergies" name="allergies" value="{{ $record->allergies ?? '' }}" required>
                    </div>

                    <div class="form-section">
            <h2>Medicines at the clinic that are OK to give</h2>
            <div class="checkbox-group">
                @php
                    $availableMedicines = [
                        'Paracetamol',
                        'Ibuprofen',
                        'Mefenamic Acid',
                        'Citirizine/Loratadine',
                        'Camphor + Menthol Liniment',
                        'PPA',
                        'Phenylephrine',
                        'Antacid'
                    ];
                @endphp

                @foreach($availableMedicines as $medicine)
    <label for="medicine-{{ strtolower(str_replace([' ', '+', '/'], '-', $medicine)) }}">
        <input 
            type="checkbox" 
            id="medicine-{{ strtolower(str_replace([' ', '+', '/'], '-', $medicine)) }}" 
            name="medicines[]" 
            value="{{ $medicine }}" 
            @if(in_array($medicine, $record->medicines ?? [])) checked @endif
        >
        {{ $medicine }}
    </label>
@endforeach

            </div>
        </div>
                    <div class="form-group">
                    <button type="button" class="button" onclick="clearForm(this)">Clear</button>
                    </div>
                </form>
            </div>

            <!-- Physical Examination -->
       <div class="form-container">
    <div class="form-header">
        <h2>Physical Examination</h2>
    </div>

    <form method="POST" action="{{ route('nurse.physical-examinations.store') }}" id="physical-examination-form">
        @csrf
        <input type="hidden" id="physical-exam-id_number" name="id_number" value="{{ old('id_number') }}">
        <input type="hidden" id="md-approved" name="md_approved" value="1">

        <div class="form-group-inline">
            <div class="form-group">
                <label for="height">Height (cm)</label>
                <!-- Changed type to number, added min and step attributes -->
                <input
                    type="number"
                    id="height"
                    name="height"
                    required
                    min="0"
                    step="0.1"
                    oninput="calculateBMI()"
                    placeholder="e.g., 175.5"
                >
            </div>
            <div class="form-group">
                <label for="weight">Weight (kg)</label>
                <!-- Changed type to number, added min and step attributes -->
                <input
                    type="number"
                    id="weight"
                    name="weight"
                    required
                    min="0"
                    step="0.1"
                    oninput="calculateBMI()"
                    placeholder="e.g., 70.2"
                >
            </div>
        </div>

        <div class="form-group">
            <p class="bmi-result">BMI: <span id="bmi-value">N/A</span></p>
        </div>

        <div class="form-group-inline">
            <div class="form-group">
                <label for="vision">Vision</label>
                <!-- Changed type to number, added min and step attributes -->
                <input
                    type="number"
                    id="vision"
                    name="vision"
                    required
                    min="0"
                    step="0.01"
                    placeholder="e.g., 20.00"
                >
            </div>
        </div>

        <div class="form-group">
            <label for="remarks">Remarks</label>
            <!-- Remarks remain as textarea without restrictions -->
            <textarea id="remarks" name="remarks" rows="5" placeholder="Enter any additional remarks here..."></textarea>
        </div>

        <div class="form-group">
            <button type="submit" class="button" id="save-button" disabled>Save</button>
        </div>
    </form>

            </div>
        </div>
</div>
</div>
<div id="history" class="tab forms-container hidden">
    <!-- Sub-Tabs Navigation -->
    <div class="sub-tabs">
    <div class="sub-tab-buttons">
        <button id="medical-record-history-tab" class="active" onclick="showSubTab('medical-record-history')">
            <i class="fas fa-notes-medical"></i> Medical Record History
        </button>
        <button id="physical-examination-history-tab" onclick="showSubTab('physical-examination-history')">
            <i class="fas fa-file-medical"></i> Physical Examination History
        </button>
        <button id="health-examination-uploads-tab" onclick="showSubTab('health-examination-uploads')">
            <i class="fas fa-upload"></i> Health Examination Uploads
        </button>
        <button id="medicine-intake-history-tab" onclick="showSubTab('medicine-intake-history')">
                        <i class="fas fa-pills"></i> Medicine Intake History
                    </button>
    </div>
</div>

    <!-- Sub-Tabs Content -->
    <div class="sub-tab-content">
        <!-- Medical Record History Sub-Tab -->
        <div id="medical-record-history" class="sub-tab active">
            <div class="form-containers">
                <h2>Medical Record History</h2>
                <div class="history-scrollable">
                    <table class="history-table" id="medical-record-history-table">
                        <thead>
                            <tr>
                                <th>Chronic Conditions</th>
                                <th>Surgical History</th>
                                <th>Family Medical History</th>
                                <th>Allergies</th>
                                <th>Medicines</th>
                                <th>Health Documents</th> 
                                <th>Approval Status</th> 
                                <th>Current Record?</th> 
                            </tr>
                        </thead>
                        <tbody id="medical-record-history-body">
                            <!-- This section will be dynamically populated with JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Physical Examination History Sub-Tab -->
        <div id="physical-examination-history" class="sub-tab hidden">
    <div class="form-container">
        <!-- Left Side: Physical Examination History Table -->
        <div class="history-table-container">
            <h2>Physical Examination History</h2>
            <div class="history-scrollable">
                <table class="history-table" id="physical-examination-history-table">
                    <thead>
                        <tr>
                            <th>Height in CM</th>
                            <th>Weight in KG</th>
                            <th>BMI</th>
                            <th>Vision</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody id="physical-examination-history-body">
                        <!-- This section will be dynamically populated with JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Side: BMI Chart -->
        <div class="chart-container">
            <h3>BMI Over Time</h3>
            <canvas id="bmiChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>


        <!-- Health Examination Uploads Sub-Tab -->
        <div id="health-examination-uploads" class="sub-tab hidden">
            <div class="form-container">
                <h2>Health Examination Uploads</h2>
                <div class="history-scrollable">
                    <table class="history-table" id="health-examination-uploads-table">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody id="health-examination-uploads-body">
                            <!-- This section will be dynamically populated with JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="medicine-intake-history" class="sub-tab hidden">
        <div class="form-container">
                <div class="form-header">
                    <h2>Medicine Intake History</h2>
                </div>
                <div class="scrollable-container">
                <table class="history-table" id="medicine-intake-history-table">
                <thead>
                            <tr>
                                <th>Medicine</th>
                                <th>Date</th>
                                <th>Dosage</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody id="medicine-intake-history-body">
                            <!-- This will be populated via JS/AJAX -->
                        </tbody>
                    </table>
                </div>
                </div>
    </div>
</div>


    </div>
    <div id="all-records" class="tab forms-container hidden">
    <div class="form-container">
        <div class="form-header">
            <h2>All Medical Records</h2>
        </div>
      
        <table class="history-table" id="all-records-table">
            <thead>
                <tr>
                    <th>ID Number</th>
                    <th>Patient Name</th>
                    <th>Birthdate</th>
                    <th>Age</th>
                    <th>Address</th>
                    <th>Personal Contact Number</th> <!-- Updated -->
                    <th>Emergency Contact Number</th> <!-- Added -->
                    <th>Father's Name</th>
                    <th>Mother's Name</th>
                    <th>Past Illness</th> <!-- Added -->
                    <th>Chronic Conditions</th>
                    <th>Surgical History</th>
                    <th>Family Medical History</th>
                    <th>Allergies</th>
                    <th>Medicines</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="all-records-body">
                <!-- Data will be populated via DataTables AJAX -->
            </tbody>
        </table>
    </div>
</div>

@if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-warning">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // URL for search route
   // URL for search route
const searchUrl = "{{ route('nurse.medical-record.search') }}";

// Variables to store DataTable instances
let medicalRecordTable;
let physicalExaminationTable;
let healthExaminationUploadsTable;
let medicineIntakeTable;

function showTab(tabId) {
    const tabs = document.querySelectorAll('.tab');
    tabs.forEach(tab => {
        tab.style.opacity = 0;
        setTimeout(() => {
            tab.classList.add('hidden');
        }, 400);
    });

    setTimeout(() => {
        const selectedTab = document.getElementById(tabId);
        selectedTab.classList.remove('hidden');
        setTimeout(() => {
            selectedTab.style.opacity = 1;
        }, 50);
    }, 400);

    document.querySelectorAll('.tab-buttons button').forEach(button => {
        button.classList.remove('active');
    });
    
    document.getElementById(tabId + '-tab').classList.add('active');

    // Show or hide the search bar based on the selected tab
    const searchForm = document.getElementById('search-form');
    if (tabId === 'all-records') {
        searchForm.style.display = 'none';
    } else {
        searchForm.style.display = 'block';
    }

    // Initialize or reload DataTables as needed
    if (tabId === 'all-records') {
        if (!$('#all-records-table').hasClass('dataTable')) {
            initializeAllRecordsTable();
        } else {
            $('#all-records-table').DataTable().ajax.reload(null, false);
        }
    }

}
// Function to show sub-tabs within the Health History tab
function showSubTab(subTabId) {
    const subTabs = document.querySelectorAll('.sub-tab');
    subTabs.forEach(tab => {
        tab.style.opacity = 0;
        setTimeout(() => {
            tab.classList.add('hidden');
            tab.classList.remove('active');
        }, 400);
    });

    setTimeout(() => {
        const selectedSubTab = document.getElementById(subTabId);
        selectedSubTab.classList.remove('hidden');
        setTimeout(() => {
            selectedSubTab.style.opacity = 1;
            selectedSubTab.classList.add('active');
        }, 50);
    }, 400);

    // Update active state for sub-tab buttons
    document.querySelectorAll('.sub-tab-buttons button').forEach(button => {
        button.classList.remove('active');
    });

    document.getElementById(`${subTabId}-tab`).classList.add('active');
}

// Function to initialize the "All Records" DataTable
function initializeAllRecordsTable() {
    $('#all-records-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("nurse.medical-records.all-data") }}', // Ensure the route is correct
            type: 'GET',
            data: function (d) {
                // Pass additional parameters for custom search if needed
                d.searchQuery = $('#all-records-search-input').val();
            },
            error: function (xhr, error, thrown) {
                hideSpinner();
                console.error('Error fetching all medical records:', xhr.responseText);
                Swal.fire(
                    'Error!',
                    'Failed to load all medical records. Please try again later.',
                    'error'
                );
            }
        },
        columns: [
            { data: 'id_number', name: 'id_number' },
            { data: 'patient_name', name: 'patient_name' },
            { data: 'birthdate', name: 'birthdate' },
            { data: 'age', name: 'age' },
            { data: 'address', name: 'address' },
            { data: 'personal_contact_number', name: 'personal_contact_number' }, // Updated
            { data: 'emergency_contact_number', name: 'emergency_contact_number' }, // Added
            { data: 'father_name', name: 'father_name' },
            { data: 'mother_name', name: 'mother_name' },
            { data: 'past_illness', name: 'past_illness' }, // Added
            { data: 'chronic_conditions', name: 'chronic_conditions' },
            { data: 'surgical_history', name: 'surgical_history' },
            { data: 'family_medical_history', name: 'family_medical_history' },
            { data: 'allergies', name: 'allergies' },
            { data: 'medicines', name: 'medicines' },
           
            { 
                data: 'actions',
                name: 'actions',
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-info btn-sm btn-view" onclick="viewMedicalRecord(${data})" title="View Record">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <button class="btn btn-success btn-sm btn-download" onclick="downloadMedicalRecord(${data})" title="Download PDF">
                            <i class="fas fa-download"></i> Download PDF
                        </button>
                    `;
                },
                orderable: false,
                searchable: false
            }
        ],
        order: [[0, 'asc']],
        language: {
            emptyTable: "No medical records available."
        }
    });
}


// Function to handle custom search
$('#all-records-search-button').on('click', function(e) {
    e.preventDefault();
    $('#all-records-table').DataTable().ajax.reload();
});

// Function to open document modal (for Health Documents)
function openDocumentModal(documentPath) {
    const fileExtension = documentPath.split('.').pop().toLowerCase();
    let content = '';

    if (['jpg', 'jpeg', 'png', 'gif', 'svg'].includes(fileExtension)) {
        content = `<img src="/storage/${documentPath}" alt="Health Document" style="width:100%;">`;
    } else if (fileExtension === 'pdf') {
        content = `<embed src="/storage/${documentPath}" type="application/pdf" width="100%" height="600px" />`;
    } else {
        content = `<p>Cannot preview this file type.</p>`;
    }

    Swal.fire({
        title: 'Health Document Preview',
        html: content,
        showCloseButton: true,
        showConfirmButton: false,
        width: '80%',
        heightAuto: true,
    });
}


function viewMedicalRecord(recordId) {
    // Fetch record details via AJAX
    fetch(`/nurse/medical-records/${recordId}/view`) // Corrected URL
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Populate the form fields with the fetched data
                populateFields(data);
                
                // Switch to the 'medical' tab to display the populated form
                showTab('medical');
                
                // Optional: Show a success notification
                Swal.fire({
                    icon: 'success',
                    title: 'Record Loaded',
                    text: 'Medical record data has been loaded into the form.',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Failed to fetch medical record details.',
                });
            }
        })
        .catch(error => {
            console.error('Error fetching medical record details:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while fetching the medical record details.',
            });
        });
}


// Function to download medical record PDF
function downloadMedicalRecord(recordId) {
    window.location.href = `/nurse/medical-records/${recordId}/download-pdf`;
}


// Initialize tab on page load
document.addEventListener('DOMContentLoaded', function () {
    showTab('medical');
    initializeDataTables(); // Initialize DataTables after DOM is ready
});

// Function to initialize DataTables
function initializeDataTables() {
    // Store DataTable instances in variables
    medicalRecordTable = $('#medical-record-history-table').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        responsive: true,
    });

    physicalExaminationTable = $('#physical-examination-history-table').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        responsive: true,
    });

    healthExaminationUploadsTable = $('#health-examination-uploads-table').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        responsive: true,
    });

    medicineIntakeTable = $('#medicine-intake-history-table').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        responsive: true,
    });
}

// Fetch medical history and show alerts based on success or failure
function fetchMedicalHistory(showAlertOnNoData = true) {
    fetch('{{ route("nurse.medical-records.history") }}')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok.');
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received in fetchMedicalHistory:', data); // Debugging
            if (data.success) {
                // Populate the fields
                populateFields(data);

                // Show SweetAlert and switch to the history tab only once
                if (showAlertOnNoData) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Medical record data found and populated successfully!',
                        timer: 2000, // Auto-close after 2 seconds
                        showConfirmButton: false
                    }).then(() => {
                        // Optionally switch to the history tab after SweetAlert closes
                    });
                }
            } else if (showAlertOnNoData) {
                showNoDataAlert();
            }
        })
        .catch(error => {
            console.error('Error fetching history:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'There was an error fetching the history data.',
            });
        });
}

// Populate fields based on the fetched data
function populateFields(data) {
    console.log('Data received in populateFields:', data); // Debugging
    // Populate the medical record fields
    if (data.medicalRecord) {
        document.getElementById('name').value = data.medicalRecord.patient_name || '';
        document.getElementById('birthdate').value = data.medicalRecord.birthdate || '';
        document.getElementById('age').value = data.medicalRecord.age || '';
        document.getElementById('address').value = data.medicalRecord.address || '';
        document.getElementById('father-name').value = data.medicalRecord.father_name || '';
        document.getElementById('mother-name').value = data.medicalRecord.mother_name || '';
        document.getElementById('personal-contact-number').value = data.medicalRecord.personal_contact_number || '';
        document.getElementById('emergency-contact-number').value = data.medicalRecord.emergency_contact_number || '';

        // Populate medical history details
        document.getElementById('past-illness').value = data.medicalRecord.past_illness || '';
        document.getElementById('chronic-conditions').value = data.medicalRecord.chronic_conditions || '';
        document.getElementById('surgical-history').value = data.medicalRecord.surgical_history || '';
        document.getElementById('family-medical-history').value = data.medicalRecord.family_medical_history || '';
        document.getElementById('allergies').value = data.medicalRecord.allergies || '';
        document.getElementById('physical-exam-id_number').value = data.medicalRecord.id_number || '';

        // Medicines (assuming they are checkboxes)
        let medicines = data.medicalRecord.medicines;
        if (!Array.isArray(medicines)) {
            medicines = [];
        }

        document.querySelectorAll("input[name='medicines[]']").forEach((checkbox) => {
            checkbox.checked = medicines.includes(checkbox.value);
        });
    } else {
        console.warn('No medical records found.');
    }

    // Populate Medical Record History
    if (data.medicalRecords) {
        populateMedicalRecordHistory(data.medicalRecords);
        document.getElementById('physical-exam-id_number').value = data.medicalRecord.id_number || '';
        document.getElementById('save-button').disabled = false; // Enable the button
    } else {
        document.getElementById('save-button').disabled = true; // Keep the button disabled
    }

    // Populate Physical Examination History
    if (data.physicalExaminations && data.physicalExaminations.length > 0) {
        populatePhysicalExaminationHistory(data.physicalExaminations);
    }

    // Populate Health Examination Uploads
    if (data.healthExaminations && data.healthExaminations.length > 0) {
        populateHealthExaminationHistory(data.healthExaminations);
    } else {
        // If no health examinations found, clear the table
        healthExaminationUploadsTable.clear().draw();
    }

    // Populate profile picture
    if (data.information && data.information.profile_picture) {
        document.getElementById('profile-picture-preview').src = `/storage/${data.information.profile_picture}`;
    }

    // Populate Medicine Intake History
    if (data.medicineIntakes && data.medicineIntakes.length > 0) {
        populateMedicineIntakeHistory(data.medicineIntakes);
    } else {
        // If no records found, clear the table
        medicineIntakeTable.clear().draw();
    }
    if (data.bmiData && data.bmiData.dates.length > 0) {
        renderBMICChart(data.bmiData);
    } else {
        // Optionally, display a message or handle no BMI data
        document.getElementById('bmiChart').parentElement.innerHTML += '<p>No BMI data available.</p>';
    }
}
function populateMedicalRecordHistory(records) {
    console.log('Medical Records:', records); // Debugging
    medicalRecordTable.clear(); // Clear existing data

    records.forEach(record => {
        // Medicines are already arrays due to model casting
        let medicines = Array.isArray(record.medicines) ? record.medicines.join(', ') : 'N/A';

        // Handle Health Documents
        let healthDocumentsHtml = '';
        if (record.health_documents && Array.isArray(record.health_documents) && record.health_documents.length > 0) {
            healthDocumentsHtml = record.health_documents.map((document, index) => `
                <a href="javascript:void(0);" onclick="openDocumentModal('${document}')">Document ${index + 1}</a>
            `).join('<br>');
        } else {
            healthDocumentsHtml = 'No Health Documents';
        }

        // Add row data as an array
        medicalRecordTable.row.add([
            record.chronic_conditions || 'N/A',
            record.surgical_history || 'N/A',
            record.family_medical_history || 'N/A',
            record.allergies || 'N/A',
            medicines,
            healthDocumentsHtml,
            record.is_approved ? 'Approved' : 'Pending Approval',
            record.is_current ? 'Yes' : 'No'
        ]);
    });

    medicalRecordTable.draw(); // Redraw the table with new data
}



// Populate Physical Examination History Table with BMI
function populatePhysicalExaminationHistory(exams) {
    console.log('Physical Examinations:', exams); // Debugging
    physicalExaminationTable.clear(); // Clear existing data

    exams.forEach(exam => {
        // Parse height and weight as floats
        const heightCm = parseFloat(exam.height);
        const weightKg = parseFloat(exam.weight);

        // Initialize BMI as 'N/A'
        let bmi = 'N/A';

        // Calculate BMI if height and weight are valid numbers
        if (!isNaN(heightCm) && !isNaN(weightKg) && heightCm > 0) {
            const heightM = heightCm / 100; // Convert cm to meters
            bmi = (weightKg / (heightM * heightM)).toFixed(2); // BMI formula
        }

        // Add row data as an array
        physicalExaminationTable.row.add([
            !isNaN(heightCm) && heightCm > 0 ? heightCm : 'N/A',
            !isNaN(weightKg) && weightKg > 0 ? weightKg : 'N/A',
            bmi,
            exam.vision || 'N/A',
            exam.remarks || 'N/A'
        ]);
    });

    physicalExaminationTable.draw(); // Redraw the table with new data
}

// Populate Health Examination Uploads Table
function populateHealthExaminationHistory(healthExaminations) {
    console.log('Health Examinations:', healthExaminations); // Debugging
    healthExaminationUploadsTable.clear(); // Clear existing data

    if (!Array.isArray(healthExaminations) || healthExaminations.length === 0) {
        healthExaminationUploadsTable.draw(); // Redraw empty table
        return;
    }

    healthExaminations.forEach((healthExamination, examIndex) => {
        // Handle health_examination_picture
        if (healthExamination.health_examination_picture && Array.isArray(healthExamination.health_examination_picture) && healthExamination.health_examination_picture.length > 0) {
            healthExamination.health_examination_picture.forEach((picture, picIndex) => {
                healthExaminationUploadsTable.row.add([
                    healthExamination.school_year || 'N/A',
                    `<a href="javascript:void(0);" onclick="openImageModal('/storage/${picture}')">Health Exam ${picIndex + 1}</a>`
                ]);
            });
        }

        // Handle lab_result_picture
        if (healthExamination.lab_result_picture && Array.isArray(healthExamination.lab_result_picture) && healthExamination.lab_result_picture.length > 0) {
            healthExamination.lab_result_picture.forEach((labResult, labIndex) => {
                healthExaminationUploadsTable.row.add([
                    healthExamination.school_year || 'N/A',
                    `<a href="javascript:void(0);" onclick="openImageModal('/storage/${labResult}')">Lab Result ${labIndex + 1}</a>`
                ]);
            });
        }

        // Handle xray_picture
        if (healthExamination.xray_picture && Array.isArray(healthExamination.xray_picture) && healthExamination.xray_picture.length > 0) {
            healthExamination.xray_picture.forEach((xrayPicture, xrayIndex) => {
                healthExaminationUploadsTable.row.add([
                    healthExamination.school_year || 'N/A',
                    `<a href="javascript:void(0);" onclick="openImageModal('/storage/${xrayPicture}')">X-ray ${xrayIndex + 1}</a>`
                ]);
            });
        }
    });

    healthExaminationUploadsTable.draw(); // Redraw the table with new data
}

// Open Image Modal Function using SweetAlert
function openImageModal(imageUrl) {
    Swal.fire({
        imageUrl: imageUrl,
        imageAlt: 'Preview Image',
        showCloseButton: true,
        showConfirmButton: false,
    });
}

// Show alert if no data found
function showNoDataAlert() {
    Swal.fire({
        icon: 'error',
        title: 'No data found',
        text: 'No medical history data found.',
    });
}

// Search functionality when search button is clicked


// Function to calculate BMI and auto-update it
function calculateBMI() {
    const height = parseFloat(document.getElementById('height').value) / 100; // Convert cm to meters
    const weight = parseFloat(document.getElementById('weight').value);

    if (!isNaN(height) && !isNaN(weight) && height > 0) {
        const bmi = weight / (height * height);
        document.getElementById('bmi-value').textContent = bmi.toFixed(2);
    } else {
        document.getElementById('bmi-value').textContent = 'N/A';
    }
}

// Function to populate Medicine Intake History
function populateMedicineIntakeHistory(medicineIntakes) {
    console.log('Medicine Intakes:', medicineIntakes); // Debugging
    medicineIntakeTable.clear(); // Clear existing data

    if (medicineIntakes.length === 0) {
        medicineIntakeTable.draw(); // Redraw empty table
        return;
    }

    medicineIntakes.forEach(intake => {
        // Medicine Name
        const medicine = intake.medicine_name || 'N/A';

        // Date Handling
        let formattedDate = 'N/A';
        if (intake.created_at) {
            const parsedDate = new Date(intake.created_at);
            if (!isNaN(parsedDate)) {
                formattedDate = parsedDate.toLocaleDateString();
            }
        } else if (intake.date) {
            const parsedDate = new Date(intake.date);
            if (!isNaN(parsedDate)) {
                formattedDate = parsedDate.toLocaleDateString();
            }
        }

        // Dosage
        const dosage = intake.dosage || 'N/A';

        // Reason
        const reason = intake.reason || 'N/A';

        // Add row data as an array
        medicineIntakeTable.row.add([
            medicine,
            formattedDate,
            dosage,
            reason
        ]);
    });

    medicineIntakeTable.draw(); // Redraw the table with new data
}

// Handle Physical Examination Form Submission
document.getElementById('physical-examination-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    const form = event.target;
    const formData = new FormData(form);
    const idNumber = document.getElementById('physical-exam-id_number').value;

    // Debug: Log formData entries
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json',
        },
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        console.log('Physical Examination Form Submission Response:', data); // Debugging
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Physical Examination data saved successfully!',
                timer: 2000,
                showConfirmButton: false
            });
            
            // Reset specific fields without affecting id_number
            form.querySelectorAll('input[type="text"], textarea, input[type="number"]').forEach(input => {
                input.value = '';
            });
            document.getElementById('bmi-value').textContent = 'N/A';
            // Optionally, re-fetch history data
            fetchMedicalHistory(false);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'There was an error saving the data.',
            });
        }
    })
    .catch(error => {
        console.error('Error submitting form:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred.',
        });
    });
});


/// Function to clear forms
function clearForm(button) {
    if (!button) {
        console.error('No button element provided to clearForm.');
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Unable to identify the button element.',
        });
        return;
    }

    // Find the closest parent form of the clicked button
    const form = button.closest('form');

    if (form) {
        // Clear all input fields of type text, number, date
        form.querySelectorAll('input[type="text"], input[type="number"], input[type="date"]').forEach(input => {
            input.value = '';
        });

        // Uncheck all checkboxes and radio buttons
        form.querySelectorAll('input[type="checkbox"], input[type="radio"]').forEach(input => {
            input.checked = false;
        });

        // Clear all textarea fields
        form.querySelectorAll('textarea').forEach(textarea => {
            textarea.value = '';
        });

        // Reset BMI display if it exists within the form
        const bmiValue = form.querySelector('#bmi-value');
        if (bmiValue) {
            bmiValue.textContent = 'N/A';
        }

        // Optionally, disable the Save button if necessary
        const saveButton = form.querySelector('button[type="submit"]');
        if (saveButton) {
            saveButton.disabled = true;
        }

        // Display SweetAlert notification
        Swal.fire({
            icon: 'info',
            title: 'Form Cleared',
            text: 'All fields have been cleared successfully.',
            timer: 2000, // Auto-close after 2 seconds
            showConfirmButton: false
        });
    } else {
        // If no parent form is found, display an error alert
        console.error('No parent form found for the Clear button.');
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Unable to locate the form to clear.',
        });
    }
}


// Function to preview profile picture
function previewProfilePicture(event) {
    const preview = document.getElementById('profile-picture-preview');
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
function renderBMICChart(bmiData) {
    const ctx = document.getElementById('bmiChart').getContext('2d');

    // Destroy existing chart instance if it exists to prevent duplication
    if (window.bmiChartInstance) {
        window.bmiChartInstance.destroy();
    }

    window.bmiChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: bmiData.dates,
            datasets: [{
                label: 'BMI',
                data: bmiData.bmis,
                backgroundColor: 'rgba(54, 162, 235, 0.2)', // Light blue
                borderColor: 'rgba(54, 162, 235, 1)', // Blue
                borderWidth: 2,
                fill: true,
                tension: 0.1,
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(54, 162, 235, 1)',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'BMI Over Time'
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    suggestedMin: 15,
                    suggestedMax: 40,
                    title: {
                        display: true,
                        text: 'BMI'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                }
            }
        }
    });
}

    </script>
</x-app-layout>