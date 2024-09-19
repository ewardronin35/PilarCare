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

        .form-container {
    flex: 1;
    background-color: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
    max-height: 75vh;
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
    .dropdown-menu {
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
    .dropdown-menu label {
        display: block;
        padding: 10px 0;
        cursor: pointer;
    }
    .dropdown-toggle:focus + .dropdown-menu, 
    .dropdown-toggle:hover + .dropdown-menu {
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
    </style>

    <div class="main-content">
        
    <div class="tab-buttons">
    <button id="tab1" class="active" onclick="showTab('medical-record')">Medical Record</button>
    <button id="tab2" onclick="showTab('health-documents')">Health Documents</button>
    <button id="tab3" onclick="showTab('physical-exam')"> Medicine Intake</button>

        </div>
        <div id="medical-record" class="tab-content active">
        <div class="forms-container">
            <!-- Profile Information -->
            <div class="form-container">
                <div class="form-header">
                    <h2>Patient Information</h2>
                </div>
        
                <form method="POST" action="{{ route('staff.medical-record.store') }}" enctype="multipart/form-data" id="medical-record-form" onsubmit="return checkSubmit()">
                    @csrf
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
                            <input type="text" id="personal-contact-number" name="personal_contact_number" value="{{ $information->personal_contact_number ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="emergency-contact-number">Emergency Contact Number</label>
                            <input type="text" id="emergency-contact-number" name="emergency_contact_number" value="{{ $information->emergency_contact_number ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="father-name">Father's Name/Legal Guardian</label>
                            <input type="text" id="father-name" name="father_name" value="{{ $information->parent_name_father ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="mother-name">Mother's Name/Legal Guardian</label>
                            <input type="text" id="mother-name" name="mother_name" value="{{ $information->parent_name_mother ?? '' }}" required>
                        </div>
                    </div>
            </div>

            <!-- Medical Information -->
            <div class="form-container">
                <div class="form-header">
                    <h2>Medical Information</h2>
                </div>
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
                    <div class="form-group">
                        <label for="allergies">Allergies</label>
                        <input type="text" id="allergies" name="allergies" value="{{ $information->allergies ?? '' }}" required>
                    </div>
                    <div class="form-section">
    <h2>Medicines OK to give/apply at the clinic</h2>
    <div class="custom-dropdown">
    <button id="medicineDropdown" class="dropdown-toggle">Select Medicines</button>
    <div class="dropdown-menu" style="display: none;">
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
                    <div class="form-group">
                        <button type="submit" class="button">Save</button>
                    </div>
                </form>
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
                <th>Allergies</th>
                <th>Illness</th>
                <th>Surgery</th>
            </tr>
        </thead>
        <tbody id="medical-record-history-body">
            @foreach($medicalRecords as $record)
                <tr>
                    <td>{{ $record->past_illness }}</td>
                    <td>{{ $record->chronic_conditions }}</td>
                    <td>{{ $record->allergies }}</td>
                    <td>{{ $record->past_illness }}</td>
                    <td>{{ $record->surgical_history }}</td>
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

    <!-- Medicine Intake History Section -->
    <h2>Medicine Intake History</h2>
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
       
                <tr>
           
                </tr>
   
            <tr>
                <td colspan="4">No medicine intake history available.</td>
            </tr>
        </tbody>
    </table>
</div>

            </div>
        </div>
    </div>
    </div>
    <div id="health-documents" class="tab-content">
        <div class="forms-container">
    <div class="form-container">
    <div class="form-header">
    <h1>Health Documents</h1>
    @if(isset($medicalRecord))
    <a href="{{ route('staff.medical-record.downloadPdf', $medicalRecord->id) }}" class="btn btn-primary">
        Download Medical Record PDF
    </a>
@endif
        <a href="{{ route('staff.health-examination.downloadPdf', $healthExamination->id) }}" class="btn btn-primary"> Download PDF </a>
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
    <table class="history-table">
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
    <div class="form-container" style="flex: 1;">
            <div class="form-header">
                <h2>BMI Chart</h2>
            </div>
            <canvas id="bmiChart"></canvas>
        </div>
</div>
</div>
<div id="physical-exam" class="tab-content">
    <div class="forms-container">
               

    <!-- Medicine Intake Section -->
    <div class="form-container">
    <div class="form-header" style="text-align: center; margin-bottom: 20px;">
        <h2>Medicine Intake</h2>
    </div>

    <form id="medicine-intake-form" method="POST" action="">
        @csrf
        <div class="form-group-inline" style="display: flex; justify-content: space-between; gap: 20px;">
            <div class="form-group" style="flex: 1;">
                <label for="medicine_name">Medicine Name</label>
                <input type="text" id="medicine_name" name="medicine_name" value="{{ old('medicine_name') }}" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
            </div>
            <div class="form-group" style="flex: 1;">
                <label for="dosage">Dosage</label>
                <input type="text" id="dosage" name="dosage" value="{{ old('dosage') }}" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
            </div>
        </div>

        <div class="form-group-inline" style="display: flex; justify-content: space-between; gap: 20px; margin-top: 20px;">
            <div class="form-group" style="flex: 1;">
                <label for="intake_time">Time of Intake</label>
                <input type="time" id="intake_time" name="intake_time" value="{{ old('intake_time') }}" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
            </div>
            <div class="form-group" style="flex: 1;">
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="form-group" style="margin-top: 20px; text-align: center;">
            <button type="submit" class="button btn-primary" style="padding: 12px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">Save</button>
        </div>
    </form>
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
    const healthDocumentsContainer = document.querySelector('#health-documents .image-previews');
    const updatePhysicalExamContainer = document.querySelector('#physical-exam-previews');

    // Handle dropdown menu for medicine selection
    const toggleButton = document.getElementById('medicineDropdown');
    const dropdownMenu = toggleButton.nextElementSibling;

    toggleButton.addEventListener('click', function() {
        dropdownMenu.style.display = dropdownMenu.style.display === 'none' ? 'block' : 'none';
    });

    // Close the dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!toggleButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.style.display = 'none';
        }
    });

    // Delegate the event listener for image preview to the health documents container
    if (healthDocumentsContainer) {
        healthDocumentsContainer.addEventListener('click', function(event) {
            if (event.target.tagName === 'IMG') {
                openModal(event.target.src);  // Assuming you have a function `openModal` to handle modal display
            }
        });
    }

    // Delegate the event listener for image preview to the physical exam container
    if (updatePhysicalExamContainer) {
        updatePhysicalExamContainer.addEventListener('click', function(event) {
            if (event.target.tagName === 'IMG') {
                openModal(event.target.src);  // Assuming you have a function `openModal` to handle modal display
            }
        });
    }
});

        function checkSubmit() {
    // Example validation: ensure that a field is filled
    const name = document.getElementById('name').value;
    if (!name) {
        alert('Please fill out the name field');
        return false; // Prevent form submission
    }

    // If everything is fine, return true to allow form submission
    return true;
}
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
    modalImage.src = imageSrc;
    modal.style.display = 'flex'; // Show the modal
}



// Close the modal when clicking the close button
function closeModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none'; // Hide the modal
}
// Close the modal when clicking outside the modal content
window.onclick = function(event) {
    const modal = document.getElementById('imageModal');
    if (event.target === modal) {
        modal.style.display = 'none';
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

        // Form Submission
        document.getElementById('medical-record-form').addEventListener('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(this);
 const requiredFields = ['name', 'birthdate', 'personal_contact_number', 'emergency_contact_number', 'father_name', 'mother_name'];

    let isValid = true;
    requiredFields.forEach(function(field) {
        const input = document.getElementById(field);
        if (!input.value) {
            isValid = false;
            input.style.border = '2px solid red';  // Highlight the missing fields
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: `Please fill out the ${input.previousElementSibling.innerText} field.`,
            });
        } else {
            input.style.border = '';  // Reset the border if the input is valid
        }
    });

    if (!isValid) return;
    // Debug to check if profile_picture exists in FormData
    if (formData.has('profile_picture')) {
        console.log('Profile picture exists in FormData');
        console.log(formData.get('profile_picture')); // Log file details
    } else {
        console.log('Profile picture missing in FormData');
    }

    $.ajax({
        url: "{{ route('staff.medical-record.store') }}",
        type: 'POST',
        data: formData,
        contentType: false, 
        processData: false,
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'Record Saved',
                text: 'The medical record has been saved successfully.',
            });

            let newRow = `
                <tr>
                    <td>${response.name}</td>
                    <td>${response.age}</td>
                    <td>${response.personal_contact_number}</td>
                    <td>${response.past_illness}</td>
                    <td>${response.surgical_history}</td>
                </tr>
            `;
            $('#medical-record-history-body').append(newRow);

            $('#medical-record-form')[0].reset();
        },
        error: function(response) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'There was a problem saving the record.',
            });
        }
    });
});
function loadBMIChart(id_number) {
    $.ajax({
        url: `/staff/physical-exam/bmi-data/${id_number}`,  // Include the 'student' prefix
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

function previewPhysicalExamsImages(event) {
    const previewsContainer = document.getElementById('physical-exam-previews');
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
                labelElement.textContent = `Physical Examination Picture ${index + 1}`;
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
    
    </script>
</x-app-layout>
