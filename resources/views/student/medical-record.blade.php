<x-app-layout :pageTitle="'Medical Record'">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .medicine-dropdown-menu {
    display: none;
    flex-direction: column;
}
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

        .forms-container {
            display: flex;
            gap: 20px;
            margin: 0 auto;
            grid-template-columns: 1fr 1fr; /* Two columns layout */

        }
        @keyframes fadeInForms {
    from {
        opacity: 0;
        transform: translateY(20px); /* Optional: Slight move-up effect */
    }
    to {
        opacity: 1;
        transform: translateY(0); /* Bring back to the original position */
    }
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
    animation: fadeInForms 0.7s ease-in-out; /* Animation lasts 0.7s */
    width: 100%; /* Ensure full width */
}

.form-containerd {
    flex: 1;
    background-color: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
    max-height: 75vh;
    animation: fadeInForms 0.7s ease-in-out; /* Animation lasts 0.7s */
    border: 1px solid #eaeaea;
    width: 100%; /* Ensure full width */
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
            margin-bottom: 20px;
        }

        .profile-picture img {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-bottom: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
            align-items: flex-start;
        }

        .form-group label {
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
        }

        .form-group input {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            color: #333;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-section {
            margin-top: 20px;
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

        .history-table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    margin-bottom: 20px;
    overflow: hidden; /* Make sure content doesn't overflow */
}
.history-table th, .history-table td {
    padding: 12px;
    text-align: left;
    font-size: 0.95rem;
    color: #333;
}
.history-table th {
    background-color: #007bff; /* Blue background for headers */
    color: white;
    font-weight: bold;
    border-right: 2px solid white; /* White line between headers */
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
        .tab-buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .tab-buttons button {
            font-family: 'Poppins', sans-serif;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-right: 10px;
          
            transition: background-color 0.3s, transform 0.3s;
        }

        .tab-buttons button.active {
            background-color: #0056b3;
            transform: scale(1.1);
        }

       

      
        
       /* Table Styling */
       .table-container {
    background-color: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
    border: 1px solid #eaeaea;
    margin-top: 20px;
    margin-bottom: 20px;
}
/* Image Container Styling */
.image-container {
    width: 60px;
    height: 60px;
    overflow: hidden;
    border-radius: 5px;
    border: 2px solid #007bff;
    display: flex;
    justify-content: center;
    align-items: center;
}

.image-container img {
    width: 100%;
    height: auto;
}

.image-previews {
    display: flex;
    gap: 10px;
}

.image-previews .image-container {
    width: 80px;
    height: 80px;
    cursor: pointer; /* Make the images clickable for preview */
}

/* Heading */
h1 {
    font-size: 1.8rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 20px;
    text-align: center;
}

/* Modal Styling for Image Preview */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}
.modal.active {
    display: flex;
    opacity: 1;
}
.custom-multi-select {
        position: relative;
        display: inline-block;
    }
    .custom-dropdown {
    position: relative;
    display: inline-block;
    width: 200px;
}

    .dropdown-toggle {
        background-color: #007bff;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;

    }
    .medicine-dropdown-menu {
    position: absolute;
    background-color: #f9f9f9;
    min-width: 200px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    padding: 12px 16px;
    z-index: 1;
    display: none;
    border-radius: 8px;
    flex-direction: column;
}

.medicine-dropdown-menu label {
    display: block;
    padding: 10px 0;
    cursor: pointer;
}

.dropdown-toggle:focus + .medicine-dropdown-menu, 
.dropdown-toggle:hover + .medicine-dropdown-menu {
    display: flex;
}

.tab-content {
    display: none;
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}

.tab-content.active {
    display: block;
    opacity: 1;
}
.modal-content {
    position: relative;
    width: 80%;
    max-width: 600px; /* Fixed size */
    height: 80%;
    max-height: 600px; /* Fixed size */
    background-color: white;
    border-radius: 10px;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    animation: zoomIn 0.4s ease-in-out; /* Animation */
}

.modal-content img {
    max-width: 100%;
    max-height: 100%;
}


.close {
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 25px;
    color: #333;
    cursor: pointer;
}
@keyframes zoomIn {
    from {
        transform: scale(0.5);
    }
    to {
        transform: scale(1);
    }
}
/* Add some animation to the modal appearance */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
    
}
@keyframes fadeOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
    }
}
.btn {
    display: inline-block;
    padding: 12px 20px;
    font-family: 'Poppins', sans-serif;
    text-align: center;
    text-decoration: none;
    color: white;
    background-color: #007bff;
    border-radius: 8px;
    border: none;
    transition: all 0.3s ease;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 123, 255, 0.3);
}

.btn:hover {
    background-color: #0056b3;
    box-shadow: 0 6px 10px rgba(0, 123, 255, 0.4);
    transform: translateY(-2px);
}

.btn:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.4);
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
}
@media (max-width: 768px) {
    .history-table th, .history-table td {
        font-size: 0.85rem; /* Reduce font size for smaller screens */
        padding: 10px;
    }
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

/* Sub-Tab Buttons */
.sub-tab-buttons {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
    gap: 10px;
}

.sub-tab-buttons button {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 8px;
}
.physical-exam-container {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    align-items: flex-start;
}

/* Physical Examination Table Styling */
.physical-exam-table {
    flex: 1;
    min-width: 300px; /* Ensure table doesn't get too small */
    overflow-x: auto;
}

/* Physical Examination Chart Styling */
.physical-exam-chart {
    flex: 1;
    min-width: 300px; /* Ensure chart doesn't get too small */
    display: flex;
    justify-content: center;
    align-items: center;
}

/* BMI Chart Canvas Styling */
.physical-exam-chart canvas {
    width: 100%;
    height: 300px; /* Adjust as needed */
}
.sub-tab-buttons button.active {
    background-color: #0056b3;
    transform: scale(1.05);
}

.sub-tab-buttons button:hover:not(.active) {
    background-color: #0056b3;
}

/* Sub-Tab Content */
.sub-tab-content {
    display: none;
    font-family: 'Poppins', sans-serif;

    animation: fadeInUp 0.5s ease-in-out;
    transition: opacity 0.5s ease-in-out;
}

.sub-tab-content.active {
    display: block;
    opacity: 1;
}

/* Adjustments for Responsive Design */
@media (max-width: 768px) {
    .sub-tab-buttons {
        flex-direction: column;
        align-items: center;
    }

    .sub-tab-buttons button {
        width: 100%;
        max-width: 300px;
    }

    .physical-exam-container {
        flex-direction: column;
    }

    .physical-exam-chart {
        width: 100%;
    }
}

    </style>
<div class="main-content">
    @if($medicalRecord)
        <input type="hidden" id="medical-record-id" name="medical_record_id" value="{{ $medicalRecord->id }}">
    @endif

    <!-- TAB BUTTONS -->
    <div class="tab-buttons">
        <button id="tab1" data-tab="medical-record" class="active" onclick="showTab('medical-record')">
            <i class="fas fa-notes-medical"></i> Medical Record
        </button>
        <button id="tab2" data-tab="health-documents" onclick="showTab('health-documents')">
            <i class="fas fa-file-medical"></i> Health Documents
        </button>
        <button id="tab3" data-tab="medicine-intake" onclick="showTab('medicine-intake')">
            <i class="fas fa-pills"></i> Medicine Intake
        </button>
    </div>

    <!-- TAB 1: Medical Record -->
    <div id="medical-record" class="tab-content active">
        <div class="forms-container">
            <!-- ============================= -->
            <!-- PATIENT INFORMATION FORM     -->
            <!-- ============================= -->
            <div class="form-containerd">
                <div class="form-header">
                    <h2>Patient Information</h2>
                </div>
                <div id="patient-info-message"></div>
                
                <input type="hidden" name="is_current" value="true">
                
                <div class="profile-picture">
                    <img
                        id="profile-picture-preview"
                        src="{{ $information->profile_picture 
                             ? asset('storage/' . $information->profile_picture) 
                             : asset('images/pilarLogo.jpg') }}"
                        alt="Profile Picture"
                    />
                </div>

                <div class="form-group-inline">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ $name }}"
                            readonly
                            required
                        />
                    </div>
                    <div class="form-group">
                        <label for="birthdate">Birthdate</label>
                        <input
                            type="text"
                            id="birthdate"
                            name="birthdate"
                            value="{{ $information->birthdate 
                                     ? \Carbon\Carbon::parse($information->birthdate)->format('Y-m-d') 
                                     : 'N/A' }}"
                            readonly
                            required
                        />
                    </div>
                </div>

                <div class="form-group-inline">
                    <div class="form-group">
                        <label for="age">Age</label>
                        <input
                            type="text"
                            id="age"
                            name="age"
                            value="{{ ($medicalRecord && $medicalRecord->age) 
                                      ? $medicalRecord->age 
                                      : ($age != 0 ? $age : 'N/A') }}"
                            readonly
                            required
                        />
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input
                            type="text"
                            id="address"
                            name="address"
                            value="{{ $information->address ?? '' }}"
                            required
                        />
                    </div>
                </div>

                <div class="form-group-inline">
                    <div class="form-group">
                        <label for="personal-contact-number">Personal Contact Number</label>
                        <input
                            type="text"
                            id="personal_contact_number"
                            name="personal_contact_number"
                            value="{{ $information->contact_number ?? '' }}"
                            required
                        />
                    </div>
                    <div class="form-group">
                        <label for="emergency-contact-number">Emergency Contact Number</label>
                        <input
                            type="text"
                            id="emergency_contact_number"
                            name="emergency_contact_number"
                            value="{{ $information->emergency_contact ?? '' }}"
                            required
                        />
                    </div>
                </div>

                <div class="form-group-inline">
                    <div class="form-group">
                        <label for="father-name">Father's Name</label>
                        <input
                            type="text"
                            id="father_name"
                            name="father_name"
                            value="{{ $information->father_name ?? '' }}"
                            readonly
                            required
                        />
                    </div>
                    <div class="form-group">
                        <label for="mother-name">Mother's Name</label>
                        <input
                            type="text"
                            id="mother_name"
                            name="mother_name"
                            value="{{ $information->mother_name ?? '' }}"
                            readonly
                            required
                        />
                    </div>
                </div>
            </div>
            <!-- ============================= -->
            <!-- MEDICAL INFORMATION FORM     -->
            <!-- ============================= -->
            <div class="form-containerd">
                <div class="form-header">
                    <h2>Medical Information</h2>
                </div>
                <div class="form-group">
                    <input type="date" id="record_date" name="record_date" value="{{ now()->toDateString() }}" hidden />
                </div>
                <div id="medical-info-message"></div>

                <div class="form-group-inline">
                    <div class="form-group">
                        <label for="past-illness">Past Illnesses/Injuries</label>
                        <input
                            type="text"
                            id="past-illness"
                            name="past_illness"
                            value="{{ $medicalRecord->past_illness 
                                     ?? $information->medical_history 
                                     ?? 'N/A' }}"
                            readonly
                            required
                        />
                    </div>
                    <div class="form-group">
                        <label for="chronic-conditions">Chronic Conditions</label>
                        <input
                            type="text"
                            id="chronic-conditions"
                            name="chronic_conditions"
                            value="{{ $medicalRecord->chronic_conditions ?? $information->chronic_conditions ?? '' }}"
                            readonly
                            required
                        />
                    </div>
                </div>

                <div class="form-group-inline">
                    <div class="form-group">
                        <label for="surgical-history">Surgical History</label>
                        <input
                            type="text"
                            id="surgical-history"
                            name="surgical_history"
                            value="{{ $medicalRecord->surgical_history ?? $information->surgical_history ?? '' }}"
                            readonly
                            required
                        />
                    </div>
                    <div class="form-group">
                        <label for="family-medical-history">Family Medical History</label>
                        <input
                            type="text"
                            id="family-medical-history"
                            name="family_medical_history"
                            value="{{ $medicalRecord->family_medical_history ?? $information->family_medical_history ?? '' }}"
                            readonly
                            required
                        />
                    </div>
                </div>

                <div class="form-group-inline">
                    <div class="form-group">
                        <label for="allergies">Allergies</label>
                        <input
                            type="text"
                            id="allergies"
                            name="allergies"
                            value="{{ $medicalRecord->allergies ?? $information->allergies ?? '' }}"
                            readonly
                            required
                        />
                    </div>
                    <div class="form-group">
                        <label for="medical-condition">Medical Condition</label>
                        <input
                            type="text"
                            id="medical-condition"
                            name="medical_condition"
                            value="{{ $medicalRecord->medical_condition ?? $information->medical_condition ?? '' }}"
                            readonly
                            required
                        />
                    </div>
                </div>

                <!-- Medicines allowed -->
                <div class="form-section">
                    <h2>Medicines that are ok to give</h2>
                    <div class="custom-dropdown">
                        <button id="medicineDropdown" class="dropdown-toggle">Select Medicines</button>
                        <div class="form-section">
                            <h2>Medicines that are ok to give</h2>
                            <div class="form-group">
                                    <label>Choose Medicines:</label>
                                    <div>
                                        @php
                                            // Retrieve medicines from the latest medical record if available,
                                            // otherwise fall back to the information provided.
                                            $medicines = $latestMedicalRecord->medicines ?? $information->medicines ?? [];
                                            // If for any reason it is not an array, explode by comma.
                                            if (!is_array($medicines)) {
                                                $medicines = explode(',', $medicines);
                                            }
                                        @endphp
                                        <label>
                                            <input type="checkbox" name="medicines[]" value="Paracetamol"
                                                @if(in_array('Paracetamol', $medicines))
                                                    checked
                                                @endif>
                                            Paracetamol
                                        </label>
                                        <label>
                                            <input type="checkbox" name="medicines[]" value="Ibuprofen"
                                                @if(in_array('Ibuprofen', $medicines))
                                                    checked
                                                @endif>
                                            Ibuprofen
                                        </label>
                                        <label>
                                            <input type="checkbox" name="medicines[]" value="Mefenamic Acid"
                                                @if(in_array('Mefenamic Acid', $medicines))
                                                    checked
                                                @endif>
                                            Mefenamic Acid
                                        </label>
                                        <label>
                                            <input type="checkbox" name="medicines[]" value="Citirizine/Loratadine"
                                                @if(in_array('Citirizine/Loratadine', $medicines))
                                                    checked
                                                @endif>
                                            Citirizine/Loratadine
                                        </label>
                                        <label>
                                            <input type="checkbox" name="medicines[]" value="Camphor + Menthol Liniment"
                                                @if(in_array('Camphor + Menthol Liniment', $medicines))
                                                    checked
                                                @endif>
                                            Camphor + Menthol Liniment
                                        </label>
                                        <label>
                                            <input type="checkbox" name="medicines[]" value="PPA"
                                                @if(in_array('PPA', $medicines))
                                                    checked
                                                @endif>
                                            PPA
                                        </label>
                                        <label>
                                            <input type="checkbox" name="medicines[]" value="Phenylephrine"
                                                @if(in_array('Phenylephrine', $medicines))
                                                    checked
                                                @endif>
                                            Phenylephrine
                                        </label>
                                        <label>
                                            <input type="checkbox" name="medicines[]" value="Antacid"
                                                @if(in_array('Antacid', $medicines))
                                                    checked
                                                @endif>
                                            Antacid
                                        </label>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Health Documents -->
                <div class="form-section">
                    <input type="hidden" name="is_current" value="1">
                    <input type="hidden" name="is_approved" value="1">
                    <h2>Health Documents</h2>
                    @if(!empty($medicalRecord->health_documents))
                        <div class="health-documents-preview">
                            @foreach($medicalRecord->health_documents as $doc)
                                <a
                                    href="{{ asset('storage/' . $doc) }}"
                                    target="_blank"
                                    class="btn btn-sm btn-primary"
                                    style="margin-bottom: 5px;"
                                >
                                    <i class="fas fa-file-alt"></i> View Document
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p>N/A</p>
                    @endif
                </div>
            </div><!-- end .form-containerd -->
        </div><!-- end .forms-container -->
    </div><!-- end #medical-record.tab-content -->


    <!-- TAB 2: Health Documents (with sub-tabs) -->
    <div id="health-documents" class="tab-content">
        <!-- Sub-Tab Buttons -->
        <div class="sub-tab-buttons">
            <button data-subtab="medical-record-history" class="active" onclick="showSubTab('medical-record-history')">
                <i class="fas fa-history"></i> Medical Record History
            </button>
            <button data-subtab="physical-exam-history" onclick="showSubTab('physical-exam-history')">
                <i class="fas fa-heartbeat"></i> Physical Examination History
            </button>
            <button data-subtab="health-exam-documents" onclick="showSubTab('health-exam-documents')">
                <i class="fas fa-file-alt"></i> Health Examination Documents
            </button>
        </div>

        <!-- SUB-TAB 1: Medical Record History -->
        <div class="sub-tab-content active" id="medical-record-history">
            <div class="form-container">
                <div class="form-header">
                    <h2>Medical Record History</h2>
                </div>
                <table class="history-table" id="medical-record-history-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Address</th>
                            <th>Father's Name</th>
                            <th>Mother's Name</th>
                            <th>Past Illnesses/Injuries</th>
                            <th>Chronic Conditions</th>
                            <th>Surgical History</th>
                            <th>Family Medical History</th>
                            <th>Allergies</th>
                            <th>Medical Condition</th>
                            <th>Medicines</th>
                            <th>Health Documents</th>
                            <th>Record Date</th>
                        </tr>
                    </thead>
                    <tbody id="medical-record-history-body">
    @forelse($previousRecords as $record)
        <tr>
            <td>{{ $record->name }}</td>
            <td>{{ $record->age }}</td>
            <td>{{ $record->address }}</td>
            <td>{{ $record->father_name }}</td>
            <td>{{ $record->mother_name }}</td>
            <td>{{ $record->past_illness }}</td>
            <td>{{ $record->chronic_conditions }}</td>
            <td>{{ $record->surgical_history }}</td>
            <td>{{ $record->family_medical_history }}</td>
            <td>{{ $record->allergies }}</td>
            <td>{{ $record->medical_condition }}</td>
            <td>
                @if(is_array($record->medicines) && !empty($record->medicines))
                    {{ implode(', ', $record->medicines) }}
                @else
                    N/A
                @endif
            </td>
            <td>
                @if($record->health_documents)
                    @php
                        $documents = is_array($record->health_documents)
                                        ? $record->health_documents
                                        : json_decode($record->health_documents, true);
                    @endphp
                    @foreach($documents as $document)
                        <a href="{{ asset('storage/' . $document) }}" target="_blank" class="btn btn-sm btn-primary" style="margin-bottom: 5px;">
                            <i class="fas fa-file-alt"></i> View
                        </a><br>
                    @endforeach
                @else
                    N/A
                @endif
            </td>
            <td>{{ \Carbon\Carbon::parse($record->record_date)->format('Y-m-d') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="14">No historical records available.</td>
        </tr>
    @endforelse
</tbody>
                </table>
            </div><!-- end .form-container -->
        </div><!-- end #medical-record-history -->

        <!-- SUB-TAB 2: Physical Exam History -->
        <div class="sub-tab-content" id="physical-exam-history">
            <div class="form-container">
                <div class="form-header">
                    <h2>Physical Examination History</h2>
                </div>
                <div class="physical-exam-container">
                    <div class="physical-exam-table">
                        <table class="history-table" id="physical-examination-history-table">
                            <thead>
                                <tr>
                                    <th>Height (cm)</th>
                                    <th>Weight (kg)</th>
                                    <th>Vision</th>
                                    <th>Remarks</th>
                                    <th>MD Approved</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($physicalExaminations as $examination)
                                    <tr>
                                        <td>{{ $examination->height }}</td>
                                        <td>{{ $examination->weight }}</td>
                                        <td>{{ $examination->vision }}</td>
                                        <td>{{ $examination->remarks }}</td>
                                        <td>{{ $examination->md_approved ? 'Yes' : 'No' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- end .physical-exam-table -->

                    <!-- Chart canvas -->
                    <div class="physical-exam-chart">
                        <canvas id="bmiChart"></canvas>
                    </div>
                </div><!-- end .physical-exam-container -->
            </div><!-- end .form-container -->
        </div><!-- end #physical-exam-history -->

        <!-- SUB-TAB 3: Health Exam Documents -->
        <div class="sub-tab-content" id="health-exam-documents">
            <div class="form-container">
                <div class="form-header">
                    <h2>Health Examination Documents</h2>
                    @if(isset($medicalRecord))
                        <a
                            href="{{ route('student.medical-record.downloadPdf', $medicalRecord->id) }}"
                            class="btn btn-primary no-spinner"
                        >
                            Download Medical Record PDF
                        </a>
                    @endif
                    <a
                        href="{{ route('student.health-examination.downloadPdf', $healthExamination->id) }}"
                        class="btn btn-primary no-spinner"
                    >
                        Download Examination Pictures PDF
                    </a>
                </div>
                <div class="table-container">
                    <table class="history-table" id="health-exam-pictures-table">
                        <thead>
                            <tr>
                                <th>School Year</th>
                                <th>Health Exam Picture</th>
                                <th>X-ray Pictures</th>
                                <th>Lab Result Pictures</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($healthExaminationPictures->isEmpty())
                                <tr>
                                    <td colspan="4">No health examination pictures available.</td>
                                </tr>
                            @else
                                @foreach($healthExaminationPictures as $examination)
                                    <tr>
                                        <td>{{ $examination->school_year ?? 'Unknown' }}</td>
                                        <td>
                                            @if(is_array($examination->health_examination_picture) 
                                                && !empty($examination->health_examination_picture))
                                                <div class="image-previews">
                                                    @foreach($examination->health_examination_picture as $picture)
                                                        <div class="image-container">
                                                            <img
                                                                src="{{ asset('storage/' . $picture) }}"
                                                                alt="Health Examination Picture"
                                                            />
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span>No Health Exam Picture</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="image-previews">
                                                @if($examination->xray_picture)
                                                    @foreach($examination->xray_picture as $xray)
                                                        <div class="image-container">
                                                            <img
                                                                src="{{ asset('storage/' . $xray) }}"
                                                                alt="X-ray Picture"
                                                            />
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <span>No X-ray Pictures</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="image-previews">
                                                @if($examination->lab_result_picture)
                                                    @foreach($examination->lab_result_picture as $lab)
                                                        <div class="image-container">
                                                            <img
                                                                src="{{ asset('storage/' . $lab) }}"
                                                                alt="Lab Result Picture"
                                                            />
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <span>No Lab Result Pictures</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div><!-- end .table-container -->
            </div><!-- end .form-container -->
        </div><!-- end #health-exam-documents -->
    </div><!-- end #health-documents.tab-content -->


    <!-- TAB 3: Medicine Intake -->
    <div id="medicine-intake" class="tab-content">
        <div id="medicine-intake-form-container" class="form-container">
            <div class="form-header">
                <h2>Medicine Intake</h2>
            </div>
            <form method="POST" action="{{ route('student.medicine-intake.store') }}" onsubmit="submitMedicineIntakeForm(event)">
                @csrf
                <input type="hidden" name="id_number" value="{{ Auth::user()->id_number }}">
                <div class="form-group-inline" style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                    <div class="form-group" style="flex: 1; margin-right: 10px;">
                        <label for="medicine_name" style="font-weight: 500; margin-bottom: 5px;">Medicine Name</label>
                        <input
                            type="text"
                            id="medicine_name"
                            name="medicine_name"
                            value="{{ old('medicine_name') }}"
                            required
                            style="padding: 10px; border-radius: 8px; border: 1px solid #ddd; width: 100%; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"
                        />
                    </div>
                    <div class="form-group" style="flex: 1; margin-left: 10px;">
                        <label for="dosage" style="font-weight: 500; margin-bottom: 5px;">Dosage</label>
                        <input
                            type="number"
                            id="dosage"
                            name="dosage"
                            value="{{ old('dosage') }}"
                            required
                            min="1"
                            max="10"
                            step="1"
                            style="padding: 10px; border-radius: 8px; border: 1px solid #ddd; width: 100%; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"
                        />
                    </div>
                </div>

                <div class="form-group-inline" style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                    <div class="form-group" style="flex: 1; margin-right: 10px;">
                        <label for="intake_time" style="font-weight: 500; margin-bottom: 5px;">Time of Intake</label>
                        <input
                            type="time"
                            id="intake_time"
                            name="intake_time"
                            value="{{ old('intake_time') }}"
                            required
                            style="padding: 10px; border-radius: 8px; border: 1px solid #ddd; width: 60%; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"
                        />
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="notes" class="input-label" style="margin-left: -25px;">Notes</label>
                        <textarea
                            id="notes"
                            name="notes"
                            class="styled-textarea"
                        >{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="form-group" style="text-align: center;">
                    <button
                        type="submit"
                        class="button btn-primary"
                        style="padding: 12px 20px; font-size: 1rem; background-color: #007bff; color: white; border-radius: 8px; cursor: pointer;"
                    >
                        Save
                    </button>
                </div>
            </form>
        </div>

        <!-- Medicine Intake History Table -->
        <div class="table-container">
            <h2 style="text-align:center;">Medicine Intake History</h2>
            <table class="history-table" id="medicine-intake-history-table">
                <thead>
                    <tr>
                        <th>Medicine Name</th>
                        <th>Dosage</th>
                        <th>Time of Intake</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody id="medicine-intake-history-body">
                    @foreach($medicineIntakes as $intake)
                        <tr>
                            <td>{{ $intake->medicine_name }}</td>
                            <td>{{ $intake->dosage }}</td>
                            <td>{{ $intake->intake_time }}</td>
                            <td>{{ $intake->notes }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div><!-- end #medicine-intake.tab-content -->

    <!-- MODAL for image previews, if needed -->
    <div id="imageModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <img id="modalImage" src="" alt="Modal Image">
        </div>
    </div>
</div><!-- end .main-content -->

    <!-- Include jQuery (already included in your code) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

    <!-- Existing Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    const storageBaseUrl = "{{ asset('storage') }}";
    const storeMedicalRecordUrl = "{{ route('student.medical-record.store') }}";

</script>

    <script>
function showTab(tabId) {
    console.log("Switching to tab:", tabId);

    // Remove 'active' from all tab containers
    document.querySelectorAll('.tab-content').forEach(tab => {
        console.log("Removing active class from tab:", tab.id);

        tab.classList.remove('active');
        // Optionally reset inline opacity (if you are using it for a fade)
        tab.style.opacity = 1;
    });
    
    // Remove 'active' from all buttons
    document.querySelectorAll('.tab-buttons button').forEach(btn => {
        console.log("Removing active class from button with data-tab:", btn.getAttribute('data-tab'));

        btn.classList.remove('active');
    });
    
    // Add 'active' to the tab container with the matching id
    const activeTab = document.getElementById(tabId);
    if (activeTab) {
        console.log("Activated tab container:", tabId);

        activeTab.classList.add('active');
    } else {
        console.error("No tab container found with id:", tabId);
    }
    
    // Instead of selecting by the onclick attribute,
    // select the button with the matching data-tab attribute.
    const activeButton = document.querySelector(`button[data-tab="${tabId}"]`);
    if (activeButton) {
        activeButton.classList.add('active');
        console.log("Activated tab button with data-tab:", tabId);

    } else {
        console.error("No tab button found with data-tab:", tabId);
    }
}


function debounce(func, wait) {
    let timeout;
    return function(...args) {
        const later = () => {
            clearTimeout(timeout);
            func.apply(this, args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function showSubTab(tabId) {
    console.log("Switching to sub-tab:", tabId);
    
    // Remove active classes from all sub-tab contents and buttons
    document.querySelectorAll('.sub-tab-content').forEach(tab => {
        console.log("Removing active class from sub-tab content:", tab.id);
        tab.classList.remove('active');
    });
    document.querySelectorAll('.sub-tab-buttons button').forEach(btn => {
        console.log("Removing active class from sub-tab button with data-subtab:", btn.getAttribute('data-subtab'));
        btn.classList.remove('active');
    });
    
    // Activate the matching sub-tab content
    const activeSubTab = document.getElementById(tabId);
    if (activeSubTab) {
        activeSubTab.classList.add('active');
        console.log("Activated sub-tab content:", tabId);
    } else {
        console.error("No sub-tab content found with id:", tabId);
    }
    
    // Activate the corresponding sub-tab button by matching its data attribute
    const activeSubButton = document.querySelector(`button[data-subtab="${tabId}"]`);
    if (activeSubButton) {
        activeSubButton.classList.add('active');
        console.log("Activated sub-tab button with data-subtab:", tabId);
    } else {
        console.error("No sub-tab button found with data-subtab:", tabId);
    }
}

 document.addEventListener('DOMContentLoaded', function() {
    $(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   // Check if the hidden field exists and if its value is empty.
   const medRecordInput = document.getElementById('medical-record-id');
            if (!medRecordInput || medRecordInput.value.trim() === '') {
                autoCreateMedicalRecord();
            }
        });
        
        function autoCreateMedicalRecord() {
            // Gather minimal data from the form
            const payload = {
    name: "{{ $name }}",
    // Use ISO format so new Date() parses it correctly
    birthdate: "{{ $information->birthdate ? \Carbon\Carbon::parse($information->birthdate)->format('Y-m-d') : '1970-01-01' }}",
    // If the birthdate isn’t set, pass 0 for age (the view will display N/A)
    age: "{{ $information->birthdate ? \Carbon\Carbon::parse($information->birthdate)->age : 0 }}",
    address: "{{ $information->address ?? 'N/A' }}",
    personal_contact_number: "{{ $information->contact_number ?? 'N/A' }}",
    emergency_contact_number: "{{ $information->emergency_contact ?? 'N/A' }}",
    father_name: "{{ $information->father_name ?? 'N/A' }}",
    mother_name: "{{ $information->mother_name ?? 'N/A' }}",
    past_illness: "N/A",
    chronic_conditions: "N/A",
    surgical_history: "N/A",
    family_medical_history: "N/A",
    allergies: "N/A",
    medical_condition: "N/A",
    is_approved: 1,
    is_current: 1
};
    $.ajax({
        url: storeMedicalRecordUrl,
        method: 'POST',
        data: payload,
        success: function(res) {
            if (res.success && res.medical_record) {
                Swal.fire({
                    icon: 'success',
                    title: 'Medical Record Created',
                    text: 'A new medical record has been automatically created.',
                    timer: 3000,
                    showConfirmButton: false
                });
                // Optionally, set the hidden field so the auto-creation is not repeated
                let medRecordInput = document.getElementById('medical-record-id');
                if (!medRecordInput) {
                    medRecordInput = document.createElement('input');
                    medRecordInput.type = 'hidden';
                    medRecordInput.id = 'medical-record-id';
                    medRecordInput.name = 'medical_record_id';
                    document.body.appendChild(medRecordInput);
                }
                medRecordInput.value = res.medical_record.id;
            } else {
                console.warn('Failed to auto-create record:', res.message || '');
            }
        },
        error: function(err) {
            console.error('Error auto-creating record:', err);
        }
    });
}
    const id_number = "{{ $user->id_number }}";  // Ensure the user variable is passed in Blade
    loadBMIChart(id_number);
    
    $('#health-exam-pictures-table').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "responsive": true,
                "language": {
                    "emptyTable": "No health examination pictures available."
                },
                "columnDefs": [
                    { "orderable": false, "targets": [1, 2, 3] } // Disable ordering on image columns
                ]
            });

            // Initialize DataTables for Medicine Intake History Table
            $('#medicine-intake-history-table').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "responsive": true,
                "language": {
                    "emptyTable": "No medicine intake history available."
                }
            });

            // Initialize DataTables for Health Documents Table
            $('#health-documents-table').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "responsive": true,
                "language": {
                    "emptyTable": "No health documents available."
                }
            });

            // Initialize DataTables for Medical Record History Table
            $('#medical-record-history-table').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "responsive": true,
                "language": {
                    "emptyTable": "No medical record history available."
                }
            });

            // Initialize DataTables for Physical Examination History Table
            $('#physical-examination-history-table').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "responsive": true,
                "language": {
                    "emptyTable": "No physical examination history available."
                }
            });

    // Attach event listeners to dynamically loaded images for modal preview

    // Handle dropdown menu for medicine selection
    const healthDocumentsContainer = document.querySelector('.table-container');
    if (healthDocumentsContainer) {
        healthDocumentsContainer.addEventListener('click', function(event) {
            if (event.target.tagName === 'IMG') {
                openModal(event.target.src);  // Call openModal with the clicked image's source
            }
        });
    }

    const toggleButton = document.getElementById('medicineDropdown');
    const dropdownMenu = toggleButton.nextElementSibling;

      toggleButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default behavior
        event.stopPropagation(); // Stop the event from propagating to other elements

        // Toggle visibility of dropdown menu
        if (dropdownMenu.style.display === 'none' || dropdownMenu.style.display === '') {
            dropdownMenu.style.display = 'flex';
        } else {
            dropdownMenu.style.display = 'none';
        }
    });

    // Close the dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!toggleButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.style.display = 'none';
        }
    });

});

function promptCreateMedicalRecord() {
    Swal.fire({
        title: 'Create Medical Record',
        text: "Medical information fields will be left empty. Profile information will be filled.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, create it!',
        cancelButtonText: 'No, cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            // Gather profile information from hidden inputs or existing fields
            const profileData = {
                id_number: $('#id_number').val(),
                patient_name: $('#patient_name').val(),
                is_current: true,
                is_approved: false,
                record_date: new Date().toISOString().split('T')[0],
            };

            // Send AJAX request to create the medical record with null medical info
            $.ajax({
                url: "{{ route('student.medical-record.store') }}",
                type: 'POST',
                data: profileData,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Medical Record Created',
                            text: 'Your medical record has been created and is pending approval.',
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Failed to create medical record.',
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Server error:', xhr);
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = 'There was a problem creating the medical record.';

                        if (errors) {
                            errorMessage = Object.values(errors).map(err => err.join(', ')).join('<br>');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: errorMessage,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was a problem creating the medical record. Please try again.',
                        });
                    }
                }
            });
        }
    });
}

function submitMedicalRecordForm(formData) {
            $.ajax({
                url: "{{ route('student.medical-record.store') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log('Server response:', response);
                    if (response && response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Record Submitted',
                            text: 'Your medical record has been submitted and is pending approval.',
                        });
                        // Reset the form and reload the page
                        $('#medical-record-form')[0].reset();
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'The server did not return the expected data.',
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Server error:', xhr);
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = 'There was a problem saving the record.';
                        if (errors) {
                            errorMessage = Object.values(errors).map(err => err.join(', ')).join('<br>');
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: errorMessage,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was a problem saving the record. Please try again.',
                        });
                    }
                }
            });
        }
    

// -------------------------------------------------
// Function: Create a New Medical Record via AJAX
// -------------------------------------------------
function createMedicalRecord() {
    var idNumber = $('#id_number').val(); // Ensure you have an input with id 'id_number'
    var patientName = $('#patient_name').val(); // Ensure you have an input with id 'patient_name'

    console.log('Creating new medical record for ID Number:', idNumber);

    // Send AJAX POST request to create the medical record
    $.ajax({
        url: storeMedicalRecordUrl, // Define this URL in your Blade view
        method: 'POST',
        data: {
            id_number: idNumber,
            patient_name: patientName,
            // Add any other necessary user information here
        },
        success: function(response) {
            if (response.medical_record_id) {
                // Set the medical_record_id in the main hidden input (if applicable)
                medicalRecordId = response.medical_record_id;
                $('#medical-record-id').val(medicalRecordId); // Ensure you have an input with id 'medical-record-id'

                console.log('New Medical Record ID set:', medicalRecordId);

                // Hide the create button to prevent duplicate records
                $('#create-medical-record').hide(); // Ensure you have a button with id 'create-medical-record'

                // Show success modal and reload the page after closing
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'A new medical record has been created successfully.',
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload(); // Reload the page after SweetAlert closes
                });
            } else {
                console.error('No Medical Record ID returned from the server.');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to create medical record!',
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        },
        error: function(xhr) {
            console.error('Error saving medical record:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to create medical record!',
                timer: 3000,
                showConfirmButton: false
            });
        }
    });
}

document.querySelectorAll('.image-container img').forEach(image => {
    image.addEventListener('click', function() {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');

        // Ensure the modal and modal image exist
        if (modal && modalImage) {
            modalImage.src = this.src;
            modal.classList.add('active'); // Add active class to show the modal with animation
        } else {
            console.error('Modal or modal image element not found!');
        }
    });
});
function openModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');

    if (modal && modalImage) {
        modalImage.src = imageSrc;  // Set the modal image source to the clicked image
        modal.style.display = 'flex';  // Show the modal
        modal.classList.add('active');  // Add the active class to apply animations if necessary
    } else {
        console.error('Modal or modal image element not found!');
    }
}

// Function to close the modal
function closeModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';  // Hide the modal
    modal.classList.remove('active');  // Remove the active class
}
// Close the modal when clicking outside the modal content
window.onclick = function(event) {
    const modal = document.getElementById('imageModal');
    if (event.target === modal) {
        closeModal();  // Close the modal if the user clicks outside of the modal content
    }
};
       

        

        // Automatically calculate age based on birthdate
        document.getElementById('birthdate').addEventListener('change', function () {
            const birthdate = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - birthdate.getFullYear();
            const m = today.getMonth() - birthdate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) {
                age--;
            }
            document.getElementById('age').value = age;
        });

     





function loadBMIChart(id_number) {
    $.ajax({
        url: `/student/physical-exam/bmi-data/${id_number}`,  // Include the 'student' prefix
        type: 'GET',
        success: function(response) {
            const ctx = document.getElementById('bmiChart').getContext('2d');

            const bmiData = {
                labels: response.bmiData.dates,  // Dates for the x-axis
                datasets: [{
                    label: 'BMI',
                    data: response.bmiData.bmis,  // BMI values
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 1
                }]
            };

            new Chart(ctx, {
                type: 'line',
                data: bmiData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        },
        error: function(xhr, status, error) {
            console.error("Error fetching BMI data: ", xhr.responseText);
        }
    });
}


function previewMedicalInfoImages(event) {
    const previewsContainer = document.getElementById('medical-info-previews');
    previewsContainer.innerHTML = ''; // Clear existing previews

    const files = event.target.files;
    if (files) {
        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgElement = document.createElement('img');
                imgElement.src = e.target.result;
                imgElement.style.maxWidth = '100px';
                imgElement.style.marginRight = '10px';

                const labelElement = document.createElement('p');
                labelElement.textContent = `Medical Information Picture ${index + 1}`;
                labelElement.style.textAlign = 'center';

                const previewContainer = document.createElement('div');
                previewContainer.style.textAlign = 'center';
                previewContainer.appendChild(imgElement);
                previewContainer.appendChild(labelElement);

                previewsContainer.appendChild(previewContainer);
            };
            reader.readAsDataURL(file);
        });
    }
}
var medicalPicturesElement = document.getElementById('medical_pictures');
if (medicalPicturesElement) {
    medicalPicturesElement.addEventListener('change', function(event) {
        const previewsContainer = document.getElementById('picture-previews');
        if(previewsContainer) {
            previewsContainer.innerHTML = ''; // Clear existing previews
        }
    
        const files = event.target.files;
        if (files) {
            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const wrapper = document.createElement('div');
                    wrapper.classList.add('image-wrapper');
    
                    const imgElement = document.createElement('img');
                    imgElement.src = e.target.result;
    
                    const label = document.createElement('span');
                    label.classList.add('image-label');
                    label.textContent = `Picture ${index + 1}`;
    
                    wrapper.appendChild(imgElement);
                    wrapper.appendChild(label);
                    previewsContainer.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            });
        }
    });
}

function submitMedicineIntakeForm(event) {
    event.preventDefault(); // Prevent default form submission

    // Show a loading alert while the request is processing
    Swal.fire({
        title: 'Submitting...',
        text: 'Please wait while your medicine intake is being recorded.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    let form = event.target;
    let formData = new FormData(form);

    // Retrieve CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json' // Ensure the server returns JSON
        },
        body: formData,
    })
    .then(response => {
        // If the response is not OK, try to parse it as text
        if (!response.ok) {
            return response.text();
        }

        // Check if the response is valid JSON before parsing
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            return response.text();
        }
    })
    .then(data => {
        // Close the loading alert if it is still open
        // (The .finally() below will also ensure that it is closed)
        // Swal.close();

        // If the response is a string, treat it as an error message.
        if (typeof data === 'string') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data,
            });
            return;
        }

        if (data.success) {
            // Append the new medicine intake record(s) to the table.
            data.medicineIntakes.forEach(medicineIntake => {
                let newRow = `
                    <tr>
                        <td>${medicineIntake.medicine_name || 'N/A'}</td>
                        <td>${medicineIntake.dosage || 'N/A'}</td>
                        <td>${medicineIntake.intake_time || 'N/A'}</td>
                        <td>${medicineIntake.notes || 'No notes'}</td>
                    </tr>
                `;
                document.getElementById('medicine-intake-history-body').innerHTML += newRow;
            });

            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Medicine intake recorded successfully!',
                timer: 3000,
                showConfirmButton: false,
            }).then(() => {
                location.reload(); // Optionally reload the page or update the UI
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to record medicine intake.',
            });
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Network Error',
            text: 'An error occurred while trying to save the medicine intake. Please check your connection and try again.',
        });
    })
    .finally(() => {
        // Ensure the loading alert is closed in all cases.
        Swal.close();
    });
}




    </script>
</x-app-layout>
