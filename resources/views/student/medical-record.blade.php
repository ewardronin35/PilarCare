<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
            margin-top: 30px;
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

        .tab-content {
            display: none;
            animation: fadeInUp 0.5s ease-in-out;
            transition: opacity 0.5s ease-in-out; /* Transition effect */

        }

        .tab-content.active {
            display: block;
            opacity: 1;

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
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}

.tab-content.active {
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


    </style>

    <div class="main-content">
        
    <div class="tab-buttons">
    <button id="tab1" class="active" onclick="showTab('medical-record')">Medical Record</button>
    <button id="tab2" onclick="showTab('health-documents')">Health Documents</button>

        </div>
        <div id="medical-record" class="tab-content active">
        <div class="forms-container">
                <!-- Check if the latest medical record is approved or not -->
                @if($latestMedicalRecord && !$latestMedicalRecord->is_approved)
                <div class="alert alert-warning">
                        <strong>Warning:</strong> You cannot create a new medical record until the previous one is approved.
                    </div>
                @else

            <div class="form-containerd">
                <div class="form-header">
                    <h2>Patient Information</h2>
                </div>
                <div id="patient-info-message"></div>

                <form method="POST" action="{{ route('student.medical-record.store') }}" enctype="multipart/form-data" id="medical-record-form" onsubmit="return checkSubmit()">
                    @csrf
                    <input type="hidden" name="is_current" value="true">

                    <div class="profile-picture">
        <img id="profile-picture-preview" src="{{ $information->profile_picture ? asset('storage/' . $information->profile_picture) : asset('images/pilarLogo.jpg') }}" alt="Profile Picture">
        <button id="profile-picture-button" class="button" type="button">Choose Profile Picture</button>
        <input type="file" id="profile-picture-upload" name="profile_picture" accept="image/*">
    </div>

                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ $name }}" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="birthdate">Birthdate</label>
                            <input type="date" id="birthdate" name="birthdate" value="{{ $information->birthdate ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="number" id="age" name="age" value="{{ $age }}" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" value="{{ $information->address ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="personal-contact-number">Personal Contact Number</label>
                            <input type="text" id="personal_contact_number" name="personal_contact_number" value="{{ $information->personal_contact_number ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="emergency-contact-number">Emergency Contact Number</label>
                            <input type="text" id="emergency_contact_number" name="emergency_contact_number" value="{{ $information->emergency_contact_number ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="father-name">Father's Name/Legal Guardian</label>
                            <input type="text" id="father_name" name="father_name" value="{{ $information->parent_name_father ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="mother-name">Mother's Name/Legal Guardian</label>
                            <input type="text" id="mother_name" name="mother_name" value="{{ $information->parent_name_mother ?? '' }}" required>
                        </div>
                    </div>
            </div>

            <!-- Medical Information -->
            <div class="form-containerd">
                <div class="form-header">
                    <h2>Medical Information</h2>
                </div>
                <div class="form-group">
    <label for="record_date">Record Date</label>
    <input type="date" id="record_date" name="record_date" value="{{ now()->toDateString() }}" readonly>
</div>
<div id="medical-info-message"></div>

                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="past-illness">Past Illnesses/Injuries</label>
                            <input type="text" id="past-illness" name="past_illness" value="{{ $information->medical_history ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="chronic-conditions">Chronic Conditions</label>
                            <input type="text" id="chronic-conditions" name="chronic_conditions" value="{{ $information->chronic_conditions ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="surgical-history">Surgical History</label>
                            <input type="text" id="surgical-history" name="surgical_history" value="{{ $information->surgical_history ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="family-medical-history">Family Medical History</label>
                            <input type="text" id="family-medical-history" name="family_medical_history" value="{{ $information->family_medical_history ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
    <div class="form-group">
        <label for="allergies">Allergies</label>
        <input type="text" id="allergies" name="allergies" value="{{ $information->allergies ?? '' }}" required>
    </div>
    <div class="form-group">
        <label for="medical-condition">Medical Condition</label>
        <input type="text" id="medical-condition" name="medical_condition" value="{{ $information->medical_condition ?? '' }}" required>
    </div>

                    </div>
                    <div class="form-section">
    <h2>Medicines OK to give/apply at the clinic</h2>
    <div class="custom-dropdown">
    <button id="medicineDropdown" class="dropdown-toggle">Select Medicines</button>
    <div class="medicine-dropdown-menu" style="display: none;">
        <label>
            <input type="checkbox" name="medicines[]" value="Paracetamol" @if(in_array('Paracetamol', explode(',', $information->medicines ?? ''))) checked @endif> Paracetamol
        </label>
        <label>
            <input type="checkbox" name="medicines[]" value="Ibuprofen" @if(in_array('Ibuprofen', explode(',', $information->medicines ?? ''))) checked @endif> Ibuprofen
        </label>
        <label>
            <input type="checkbox" name="medicines[]" value="Mefenamic Acid" @if(in_array('Mefenamic Acid', explode(',', $information->medicines ?? ''))) checked @endif> Mefenamic Acid
        </label>
        <label>
            <input type="checkbox" name="medicines[]" value="Citirizine/Loratadine" @if(in_array('Citirizine/Loratadine', explode(',', $information->medicines ?? ''))) checked @endif> Citirizine/Loratadine
        </label>
        <label>
            <input type="checkbox" name="medicines[]" value="Camphor + Menthol Liniment" @if(in_array('Camphor + Menthol Liniment', explode(',', $information->medicines ?? ''))) checked @endif> Camphor + Menthol Liniment
        </label>
        <label>
            <input type="checkbox" name="medicines[]" value="PPA" @if(in_array('PPA', explode(',', $information->medicines ?? ''))) checked @endif> PPA
        </label>
        <label>
            <input type="checkbox" name="medicines[]" value="Phenylephrine" @if(in_array('Phenylephrine', explode(',', $information->medicines ?? ''))) checked @endif> Phenylephrine
        </label>
        <label>
            <input type="checkbox" name="medicines[]" value="Antacid" @if(in_array('Antacid', explode(',', $information->medicines ?? ''))) checked @endif> Antacid
        </label>
    </div>
</div>
</div>
<div class="form-section">
    <h2>Upload Medical Pictures</h2>
    
 <div class="file-input-container">
        <input type="file" id="medical_pictures" name="health_documents[]" multiple accept="image/*">
        <label for="medical_pictures" class="file-input-label">
            <i class="fas fa-upload"></i> Choose Files
        </label>
    </div>

<div id="picture-previews" class="custom-picture-previews"></div>

<div class="form-group">
<input type="hidden" name="is_current" value="1"> <!-- Assuming '1' means true -->
<input type="hidden" name="is_approved" value="0"> <!-- Assuming '1' means true -->

<button type="submit" class="button">Save</button>
</div>
</form>
            </div>


        </div>
        @endif
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
            <input type="text" id="medicine_name" name="medicine_name" value="{{ old('medicine_name') }}" required style="padding: 10px; border-radius: 8px; border: 1px solid #ddd; width: 100%; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        </div>
        <div class="form-group" style="flex: 1; margin-left: 10px;">
            <label for="dosage" style="font-weight: 500; margin-bottom: 5px;">Dosage</label>
            <input type="text" id="dosage" name="dosage" value="{{ old('dosage') }}" required style="padding: 10px; border-radius: 8px; border: 1px solid #ddd; width: 50%; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        </div>
    </div>

    <div class="form-group-inline" style="display: flex; justify-content: space-between; margin-bottom: 20px;">
        <div class="form-group" style="flex: 1; margin-right: 10px;">
            <label for="intake_time" style="font-weight: 500; margin-bottom: 5px;">Time of Intake</label>
            <input type="time" id="intake_time" name="intake_time" value="{{ old('intake_time') }}" required style="padding: 10px; border-radius: 8px; border: 1px solid #ddd; width: 60%; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        </div>
        <div class="form-group" style="flex: 1;">
    <label for="notes" class="input-label" style="margin-left: -25px;">Notes</label>
    <textarea id="notes" name="notes" class="styled-textarea">{{ old('notes') }}</textarea>
</div>
    </div>

    <div class="form-group" style="text-align: center;">
        <button type="submit" class="button btn-primary" style="padding: 12px 20px; font-size: 1rem; background-color: #007bff; color: white; border-radius: 8px; cursor: pointer;">Save</button>
    </div>
    </form>
</div>
    </div>
    </div>
    <div id="health-documents" class="tab-content">
        <div class="forms-container">
    <div class="form-container">
    <div class="form-header">
    <h1>Health Documents</h1>
    @if(isset($medicalRecord))
    <a href="{{ route('student.medical-record.downloadPdf', $medicalRecord->id) }}" class="btn btn-primary no-spinner">
        Download Medical Record PDF
    </a>
@endif
        <a href="{{ route('student.health-examination.downloadPdf', $healthExamination->id) }}" class="btn btn-primary no-spinner "> Download Examination Pictures PDF </a>
        <div class="table-container">
    <table class="history-table">
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
                    <td>{{ $examination->school_year ?? 'Unknown' }}</td> <!-- Display School Year -->
                    <td>
                        @if($examination->health_examination_picture)
                            <div class="image-container">
                                <img src="{{ asset('storage/' . $examination->health_examination_picture) }}" alt="Health Examination Picture">
                            </div>
                        @else
                            <span>No Health Exam Picture</span>
                        @endif
                    </td>
                    <td>
                        <div class="image-previews">
                            @if($examination->xray_picture)
                                @foreach(json_decode($examination->xray_picture) as $xray)
                                    <div class="image-container">
                                        <img src="{{ asset('storage/' . $xray) }}" alt="X-ray Picture">
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
                                @foreach(json_decode($examination->lab_result_picture) as $lab)
                                    <div class="image-container">
                                        <img src="{{ asset('storage/' . $lab) }}" alt="Lab Result Picture">
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
</div>
</div>
<div class="form-container">
    <div class="form-header">
        <h2>Medicine Intake History</h2>
    </div>
    @if(isset($medicalRecord) && $medicalRecord->medicineIntakes && $medicalRecord->medicineIntakes->isNotEmpty())
    <table class="history-table">
        <thead>
            <tr>
                <th>Medicine Name</th>
                <th>Dosage</th>
                <th>Time of Intake</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody id="medicine-intake-history-body">
            @foreach($medicalRecord->medicineIntakes as $intake)
                <tr>
                    <td>{{ $intake->medicine_name }}</td>
                    <td>{{ $intake->dosage }}</td>
                    <td>{{ $intake->intake_time }}</td>
                    <td>{{ $intake->notes ?? 'No notes' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No medicine intake history available</p>
@endif

</div>
    </div>
    <div class="form-container" style="flex: 1;">
            <div class="form-header">
                <h2>BMI Chart</h2>
            </div>
            <canvas id="bmiChart"></canvas>
            <div class="form-header" style="margin-top: 30px;">
    <h2>Health Documents</h2>
</div>

<!-- Health Documents Table -->
@if(isset($medicalRecords) && $medicalRecords->where('is_approved', 1)->isNotEmpty())
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr>
                <th>Document</th>
                <th>Medical Condition</th>
                <th>Allergies</th>
                <th>Record Date</th>
                <th>Is Current</th>
            </tr>
        </thead>
        <tbody>
            @foreach($medicalRecords->where('is_approved', 1) as $record) <!-- Only display approved records -->
                <tr>
                    <!-- Health Documents -->
                    <td>
                        @if($record->health_documents)
                            @foreach(json_decode($record->health_documents) as $document)
                                <a href="{{ asset('storage/' . $document) }}" target="_blank" style="text-decoration: none; color: #007bff;">
                                    <i class="fas fa-file-alt"></i> View Document
                                </a>
                                <br>
                            @endforeach
                        @else
                            No documents
                        @endif
                    </td>
                    <!-- Medical Condition -->
                    <td>{{ $record->medical_condition }}</td>
                    <!-- Allergies -->
                    <td>{{ $record->allergies }}</td>
                    <!-- Record Date -->
                    <td>{{ $record->record_date }}</td>
                    <!-- Is Current -->
                    <td>
                        @if($record->is_current)
                            <span style="color: green;">Yes</span>
                        @else
                            <span style="color: red;">No</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No approved medical records available.</p>
@endif
        </div>
        
            <!-- Medical Record History -->
            <div class="form-container">
    <div class="form-header">
        <h2>Medical Record History</h2>
    </div>
    <table class="history-table">
        <thead>
            <tr>
                <th>Past Illnesses/Injuries</th>
                <th>Chronic Conditions</th>
                <th>Surgical History</th>
                <th>Family Medical History</th>
            </tr>
        </thead>
        <tbody id="medical-record-history-body">
        @foreach($medicalRecords->where('is_approved', 1) as $record) <!-- Only display approved records -->
        <tr>
                    <td>{{ $record->past_illness }}</td>
                    <td>{{ $record->chronic_conditions }}</td>
                    <td>{{ $record->surgical_history }}</td>
                    <td>{{ $record->family_medical_history }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Physical Examination History</h2>
    <table class="history-table">
        <thead>
            <tr>
                <th>Height in (cm)</th>
                <th>Weight in (kg)</th>
                <th>Vision</th>
                <th>Remarks</th>
                <th>MD Approved</th>
            </tr>
        </thead>
        <tbody id="physical-examination-history-body">
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

    

        </tbody>
    </table>
</div>
 </div>
</div>
</div>

<div id="imageModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <img id="modalImage" src="" alt="Modal Image">
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
 document.addEventListener('DOMContentLoaded', function() {
    // Load BMI chart when the document is ready
    const id_number = "{{ $user->id_number }}";  // Ensure the user variable is passed in Blade
    loadBMIChart(id_number);

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
    
function showTab(tabId) {
    // Get all tab contents and tab buttons
    const tabs = document.querySelectorAll('.tab-content');
    const buttons = document.querySelectorAll('.tab-buttons button');

    // Hide all tab contents
    tabs.forEach(tab => {
        if (tab.classList.contains('active')) {
            tab.classList.remove('active');
            tab.style.opacity = 0; // Apply fade-out animation
        }
    });    
    // Remove active class from all buttons
    buttons.forEach(button => button.classList.remove('active'));
    
    setTimeout(() => {
        // Show the selected tab content and activate the corresponding button
        const activeTab = document.getElementById(tabId);
        activeTab.classList.add('active');
        activeTab.style.opacity = 1; // Apply fade-in animation
        document.querySelector(`button[onclick="showTab('${tabId}')"]`).classList.add('active');
    }, 300); // Delay for 300ms to match the fade-out effect
    // Show the selected tab content and activate the corresponding button
    document.getElementById(tabId).classList.add('active');
    document.querySelector(`button[onclick="showTab('${tabId}')"]`).classList.add('active');
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
        document.getElementById('profile-picture-button').addEventListener('click', function () {
            document.getElementById('profile-picture-upload').click();
        });

        document.getElementById('profile-picture-upload').addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('profile-picture-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);

                // SweetAlert confirmation
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Picture Updated',
                    text: 'Your profile picture has been successfully updated.',
                });
            }
        });

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

        document.getElementById('medical-record-form').addEventListener('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(this);
    const requiredFields = ['name', 'birthdate', 'personal_contact_number', 'emergency_contact_number', 'father_name', 'mother_name'];
    let isValid = true;

    // Validate required fields
    requiredFields.forEach(function(field) {
        const input = document.getElementById(field);

        if (input) {
            if (!input.value) {
                isValid = false;
                input.style.border = '2px solid red'; // Highlight the missing fields
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: `Please fill out the ${input.previousElementSibling.innerText} field.`,
                });
            } else {
                input.style.border = ''; // Reset the border if the input is valid
            }
        } else {
            console.warn(`Element with ID ${field} does not exist.`);
        }
    });

    // Ensure that at least one image is uploaded
    const medicalPictures = document.getElementById('medical_pictures');
    if (medicalPictures.files.length === 0) {
        isValid = false;
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please upload at least one medical picture before submitting.',
        });
    }

    // If validation fails, stop the submission
    if (!isValid) {
        console.log("Form is invalid. Aborting submission.");
        return;
    }

    // Make AJAX call to submit the form
    $.ajax({
        url: "{{ route('student.medical-record.store') }}",  // Adjust the route if needed
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

                // Append the new record to the medical record history table
                const record = response.medical_record;
                let newRow = `
                    <tr>
                        <td>${record.past_illness || 'N/A'}</td>
                        <td>${record.chronic_conditions || 'N/A'}</td>
                        <td>${record.surgical_history || 'N/A'}</td>
                        <td>${record.family_medical_history || 'N/A'}</td>
                        <td>${record.allergies || 'N/A'}</td>
                        <td>${record.medical_condition || 'N/A'}</td>
                        <td>${record.record_date || 'N/A'}</td>
                    </tr>
                `;
                $('#medical-record-history-body').append(newRow);

                const id_number = response.medical_record.id_number;
                startApprovalCheck(id_number);

                // Reset the form after successful submission
                $('#medical-record-form')[0].reset();

               // Dynamically show the "Pending for Approval" message in both sections without interfering with other forms
               const pendingMessage = `
    <div class="alert alert-warning" style="padding: 15px; border-radius: 8px; background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba;">
        <strong>Pending:</strong> Your medical record is awaiting approval.
    </div>
`;


// Insert the message in both Patient Information and Medical Information sections if they exist
const patientInfoMessageElement = document.getElementById('patient-info-message');
const medicalInfoMessageElement = document.getElementById('medical-info-message');

if (patientInfoMessageElement) {
    patientInfoMessageElement.innerHTML = pendingMessage;
}

if (medicalInfoMessageElement) {
    medicalInfoMessageElement.innerHTML = pendingMessage;
}

// Optionally, hide the form containers after submission
document.querySelectorAll('.form-containerd').forEach(container => {
    if (container.id !== 'medicine-intake-form-container') {
        container.style.display = 'none'; // Hide the form containers except for the medicine intake form
    }

  
const successMessage = `
    <div id="submitted-message" class="alert alert-info">
        <strong>Submitted:</strong> Your medical record has been successfully submitted and is awaiting approval.
    </div>
`;
    if (!document.getElementById('submitted-message')) {
        const mainContent = document.querySelector('.main-content');
        mainContent.insertAdjacentHTML('beforeend', successMessage);
    }
});


            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'The server did not return the expected data.',
                });
            }
        },
        error: function(response) {
            console.error('Server error:', response);

            // Handle validation errors returned from the server
            if (response.status === 422) {
                let errors = response.responseJSON.errors;
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
});


// Function to check approval status
function checkApprovalStatus(id_number) {
    $.ajax({
        url: "{{ route('student.medical-record.approval-status') }}",
        type: 'GET',
        data: { id_number: id_number },
        success: function(response) {
            if (response.is_approved) {
                Swal.fire({
                    icon: 'success',
                    title: 'Record Approved!',
                    text: 'Your medical record has been approved.',
                });

                // Update the UI to reflect approval
                document.getElementById('approval-status').textContent = 'Approved';
                document.getElementById('approval-status').style.color = 'green';

                // Remove the pending message if present
                const warningMessage = document.querySelector('.alert-warning');
                if (warningMessage) {
                    warningMessage.remove();
                }

                // Stop polling once approved
                clearInterval(approvalCheckInterval);
            }
        },
        error: function(response) {
            console.error('Error checking approval status:', response);
        }
    });
}

// Start polling for approval every 10 seconds
function startApprovalCheck(id_number) {
    const approvalCheckInterval = setInterval(function() {
        checkApprovalStatus(id_number);
    }, 50000); // Poll every 10 seconds
}

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
document.getElementById('medical_pictures').addEventListener('change', function(event) {
    const previewsContainer = document.getElementById('picture-previews');
    previewsContainer.innerHTML = ''; // Clear existing previews

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

function submitMedicineIntakeForm(event) {
    event.preventDefault();  // Prevent default form submission

    let form = event.target;
    let formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
    })
    .then(response => {
        console.log('Response Status:', response.status);
        console.log('Response Headers:', [...response.headers]);

        // If the response is not OK (e.g., 404 or other error)
        if (!response.ok) {
            return response.text();  // Parse as text for debugging
        }

        // Check if the response is valid JSON before parsing
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();  // Parse JSON response
        } else {
            return response.text();  // Parse as text for debugging purposes
        }
    })
    .then(data => {
        // If it's a string, it's likely an error HTML page or unexpected response

        if (data.success) {
        let newRow = `
            <tr>
                <td>${data.medicineIntake.medicine_name}</td>
                <td>${data.medicineIntake.intake_time}</td>
                <td>${data.medicineIntake.dosage}</td>
                <td>${data.medicineIntake.notes ?? 'No notes'}</td>
            </tr>
        `;
        document.getElementById('medicine-intake-history-body').innerHTML += newRow;

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Medicine intake recorded successfully!',
                    timer: 3000,
                    showConfirmButton: false,
                }).then(() => {
                    location.reload();  // Optionally reload the page or update the UI
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Failed to record medicine intake.',
                });
            }
        }
    })
    .catch(error => {
        // Catch any network errors
        console.error('Fetch Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Network Error',
            text: 'An error occurred while trying to save the medicine intake. Please check your connection and try again.',
        });
    });
}


    </script>
</x-app-layout>
