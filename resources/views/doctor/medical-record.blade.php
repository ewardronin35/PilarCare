<x-app-layout :pageTitle="' Medical Record'">   
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">


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
            margin-bottom: 20px;
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
    </style>

    <div class="main-content">
        <div class="tabs">
            <div class="tab-buttons">
                <button id="medical-tab" class="active" onclick="showTab('medical')">Medical Record</button>
                <button id="history-tab" onclick="showTab('history')">Health History</button>
            </div>
        </div>

        <form method="GET" action="{{ route('doctor.medical-record.search') }}" id="search-form">
            <div class="search-bar">
                <input type="text" placeholder="Search Records..." id="search-input" maxlength="7">
                <button id="search-button">Search</button>
            </div>
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
                        <h2>Medical History</h2>
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
                        <h2>Medicines OK to give/apply at the clinic</h2>
                        <div class="checkbox-group">
                            <label><input type="checkbox" name="medicines[]" value="Paracetamol" @if(in_array('Paracetamol', json_decode($record->medicines ?? '[]'))) checked @endif> Paracetamol</label>
                            <label><input type="checkbox" name="medicines[]" value="Ibuprofen" @if(in_array('Ibuprofen', json_decode($record->medicines ?? '[]'))) checked @endif> Ibuprofen</label>
                            <label><input type="checkbox" name="medicines[]" value="Mefenamic Acid" @if(in_array('Mefenamic Acid', json_decode($record->medicines ?? '[]'))) checked @endif> Mefenamic Acid</label>
                            <label><input type="checkbox" name="medicines[]" value="Citirizine/Loratadine" @if(in_array('Citirizine/Loratadine', json_decode($record->medicines ?? '[]'))) checked @endif> Citirizine/Loratadine</label>
                            <label><input type="checkbox" name="medicines[]" value="Camphor + Menthol Liniment" @if(in_array('Camphor + Menthol Liniment', json_decode($record->medicines ?? '[]'))) checked @endif> Camphor + Menthol Liniment</label>
                            <label><input type="checkbox" name="medicines[]" value="PPA" @if(in_array('PPA', json_decode($record->medicines ?? '[]'))) checked @endif> PPA</label>
                            <label><input type="checkbox" name="medicines[]" value="Phenylephrine" @if(in_array('Phenylephrine', json_decode($record->medicines ?? '[]'))) checked @endif> Phenylephrine</label>
                            <label><input type="checkbox" name="medicines[]" value="Antacid" @if(in_array('Antacid', json_decode($record->medicines ?? '[]'))) checked @endif> Antacid</label>
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

    <form method="POST" action="{{ route('doctor.physical-examinations.store') }}" id="physical-examination-form">
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

        <div id="history" class="tab forms-container hidden">
            <!-- Medical Record History -->
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

            <!-- Physical Examination History -->
            <div class="form-container">
                <h2>Physical Examination History</h2>
                <div class="history-scrollable">
                <table class="history-table" id="physical-examination-history-table">
                <thead>
                        <tr>
                            <th>Height in CM</th>
                            <th>Weight in KG</th>
                            <th>BMI</th> <!-- New BMI Column -->
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

            <!-- Health Examination Uploads -->
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
const searchUrl = "{{ route('doctor.medical-record.search') }}";

// Variables to store DataTable instances
let medicalRecordTable;
let physicalExaminationTable;
let healthExaminationUploadsTable;
let medicineIntakeTable;

// Function to show tab
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
    fetch('{{ route("doctor.medical-records.history") }}')
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
        document.getElementById('name').value = data.medicalRecord.name || '';
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
        console.log('Set physical-exam-id_number to:', data.medicalRecord.id_number || '');

        // Medicines (assuming they are checkboxes)
        let medicines = Array.isArray(data.medicalRecord.medicines) ? data.medicalRecord.medicines : JSON.parse(data.medicalRecord.medicines || '[]');
        document.querySelectorAll("input[name='medicines[]']").forEach((checkbox) => {
            checkbox.checked = medicines.includes(checkbox.value);
        });
    } else {
        console.warn('No medical records found.');
    }

    // Populate Medical Record History
    if (data.medicalRecords) {
        populateMedicalRecordHistory(data.medicalRecords);
        console.log('Setting id_number to:', data.medicalRecord.id_number);
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
}

// Populate Medical Record History Table
function populateMedicalRecordHistory(records) {
    console.log('Medical Records:', records); // Debugging
    medicalRecordTable.clear(); // Clear existing data

    records.forEach(record => {
        // Medicines are already arrays due to model casting
        let medicines = Array.isArray(record.medicines) ? record.medicines.join(', ') : 'N/A';

        // Handle Health Examination Pictures
        let healthExamDocumentsHtml = '';
        if (record.health_examination_picture && Array.isArray(record.health_examination_picture) && record.health_examination_picture.length > 0) {
            healthExamDocumentsHtml = record.health_examination_picture.map((document, index) => `
                <a href="javascript:void(0);" onclick="openImageModal('/storage/${document}')">Health Exam ${index + 1}</a>
            `).join('<br>');
        } else {
            healthExamDocumentsHtml = 'No Health Exam Documents';
        }

        // Handle Lab Result Pictures
        let labResultDocumentsHtml = '';
        if (record.lab_result_picture && Array.isArray(record.lab_result_picture) && record.lab_result_picture.length > 0) {
            labResultDocumentsHtml = record.lab_result_picture.map((document, index) => `
                <a href="javascript:void(0);" onclick="openImageModal('/storage/${document}')">Lab Result ${index + 1}</a>
            `).join('<br>');
        } else {
            labResultDocumentsHtml = 'No Lab Result Documents';
        }

        // Handle X-ray Pictures
        let xrayDocumentsHtml = '';
        if (record.xray_picture && Array.isArray(record.xray_picture) && record.xray_picture.length > 0) {
            xrayDocumentsHtml = record.xray_picture.map((document, index) => `
                <a href="javascript:void(0);" onclick="openImageModal('/storage/${document}')">X-ray ${index + 1}</a>
            `).join('<br>');
        } else {
            xrayDocumentsHtml = 'No X-ray Documents';
        }

        // Combine all documents into one HTML string
        let healthDocumentsHtml = `
            <strong>Health Exams:</strong><br>${healthExamDocumentsHtml}<br>
            <strong>Lab Results:</strong><br>${labResultDocumentsHtml}<br>
            <strong>X-rays:</strong><br>${xrayDocumentsHtml}
        `;

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
document.getElementById('search-button').addEventListener('click', function (event) {
    event.preventDefault(); // Prevent form submission
    const query = document.getElementById('search-input').value.trim();

    // Simple validation for query
    if (!query) {
        Swal.fire({
            icon: 'error',
            title: 'Empty Search',
            text: 'Please enter an ID number',
        });
        return;
    }

    fetch(`${searchUrl}?query=${query}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok.');
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received in search:', data); // Debugging
            if (data.success) {
                populateFields(data);
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Medical record data found and populated successfully!',
                    timer: 2000, // Auto-close after 2 seconds
                    showConfirmButton: false
                }).then(() => {
                    // Optionally switch to the history tab after successful data fetch
                });
            } else {
                showNoDataAlert();
            }
        })
        .catch(error => {
            console.error('Error fetching search results:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while fetching the search results.',
            });
        });
});

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

// Function to clear forms
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
        const saveButton = form.querySelector('#save-button');
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

    </script>
</x-app-layout>