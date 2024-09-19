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
            margin-left: 80px;
            margin-top: 20px;
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
    </style>

    <div class="main-content">
        <div class="tabs">
            <div class="tab-buttons">
                <button id="medical-tab" class="active" onclick="showTab('medical')">Medical Record</button>
                <button id="history-tab" onclick="showTab('history')">Health History</button>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.medical-record.search') }}" id="search-form">
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

                <form method="POST" action="{{ route('student.medical-record.store') }}" enctype="multipart/form-data" id="medical-record-form">
                    @csrf
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
                        <button type="button" class="button" onclick="clearForm()">Clear</button>
                    </div>
                </form>
            </div>

            <!-- Physical Examination -->
            <div class="form-container">
                <div class="form-header">
                    <h2>Physical Examination</h2>
                </div>

                <form method="POST" action="{{ route('admin.physical-examination.store') }}" id="physical-examination-form">
                    @csrf
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="height">Height (cm)</label>
                            <input type="text" id="height" name="height" required oninput="calculateBMI()">
                        </div>
                        <div class="form-group">
                            <label for="weight">Weight (kg)</label>
                            <input type="text" id="weight" name="weight" required oninput="calculateBMI()">
                        </div>
                    </div>

                    <div class="form-group">
                        <p class="bmi-result">BMI: <span id="bmi-value">N/A</span></p>
                    </div>

                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="vision">Vision</label>
                            <input type="text" id="vision" name="vision" required>
                        </div>
                        <div class="form-group">
                            <label for="medicine-intake">Medicine Intake</label>
                            <input type="text" id="medicine-intake" name="medicine-intake" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea id="remarks" name="remarks" rows="5"></textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="button">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="history" class="tab forms-container hidden">
            <!-- Medical Record History -->
            <div class="form-container">
                <h2>Medical Record History</h2>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Chronic Conditions</th>
                            <th>Surgical History</th>
                            <th>Family Medical History</th>
                            <th>Allergies</th>
                            <th>Medicines</th>
                            <th>Health Documents</th> 
                            <th>Approval Status</th> 
                            <th>Current Status</th> 
                        </tr>
                    </thead>
                    <tbody id="medical-record-history-body">
                        <!-- This section will be dynamically populated with JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Physical Examination History -->
            <div class="form-container">
                <h2>Physical Examination History</h2>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Height</th>
                            <th>Weight</th>
                            <th>Vision</th>
                            <th>Medicine Intake</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody id="physical-examination-history-body">
                        <!-- This section will be dynamically populated with JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Health Examination Uploads -->
            <div class="form-container">
                <h2>Health Examination Uploads</h2>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>File</th>
                        </tr>
                    </thead>
                    <tbody id="health-examination-uploads-body">
                        <!-- This section will be dynamically populated with JavaScript -->
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// URL for search route
const searchUrl = "{{ route('admin.medical-record.search') }}";

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
    fetchMedicalHistory(false); // Fetch data without alerts
});

// Fetch medical history and show alerts based on success or failure
function fetchMedicalHistory(showAlertOnNoData = true) {
    fetch('{{ route("admin.medical-records.history") }}')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok.');
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received from server:', data); // Log full response
            
            if (data.success) {
                // Debugging: Check if the records exist and the length
                console.log('Records found:', data.medicalRecords);
                if (data.medicalRecords.length > 0 || data.physicalExaminations.length > 0 || data.healthExamination) {
                    populateFields(data); // Populate fields if data is available
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Medical record data found and populated successfully!',
                        timer: 2000, // Auto-close after 2 seconds
                        showConfirmButton: false
                    });
                } else if (showAlertOnNoData) {
                    showNoDataAlert(); // Show alert if no data found
                }
            } else {
                // Debugging: If `data.success` is false
                console.warn('No success status in the response:', data);
                if (showAlertOnNoData) {
                    showNoDataAlert();
                }
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

        // Medicines (assuming they are checkboxes)
        let medicines = JSON.parse(data.medicalRecord.medicines || '[]');
        document.querySelectorAll("input[name='medicines[]']").forEach((checkbox) => {
            checkbox.checked = medicines.includes(checkbox.value);
        });
    } else {
        console.warn('No medical records found.');
    }

    // Populate Medical Record History
    if (data.medicalRecords) {
        populateMedicalRecordHistory(data.medicalRecords);
    }

    // Populate Physical Examination History
    if (data.physicalExaminations && data.physicalExaminations.length > 0) {
        populatePhysicalExaminationHistory(data.physicalExaminations);
    }

    // Populate Health Examination Data
    if (data.healthExamination) {
        populateHealthExaminationHistory(data.healthExamination);
    }
    if (data.healthDocuments && data.healthDocuments.length > 0) {
        populateHealthDocuments(data.healthDocuments);
    }

    // Populate profile picture
    if (data.information && data.information.profile_picture) {
        document.getElementById('profile-picture-preview').src = `/storage/${data.information.profile_picture}`;
    }
}

// Populate Medical Record History Table
// Populate Medical Record History Table
function populateMedicalRecordHistory(records) {
    const medicalHistoryBody = document.getElementById('medical-record-history-body');
    medicalHistoryBody.innerHTML = ''; // Clear existing rows
    
    records.forEach(record => {
        // If medicines are stored as JSON or a comma-separated string, we should parse it
        let medicines = record.medicines;
        if (typeof medicines === 'string') {
            medicines = JSON.parse(medicines); // Adjust this if medicines are stored differently
        }

        // Check if health documents exist
        let healthDocumentsHtml = '';
        if (record.health_documents) {
            const healthDocuments = JSON.parse(record.health_documents); // Assuming health documents are stored as a JSON array
            healthDocumentsHtml = healthDocuments.map(document => `
                <a href="/path/to/health/documents/${document}" target="_blank">View Document</a>
            `).join('<br>'); // Links to view each document
        } else {
            healthDocumentsHtml = 'No documents';
        }

        const row = `
            <tr>
                <td>${record.chronic_conditions || 'N/A'}</td>
                <td>${record.surgical_history || 'N/A'}</td>
                <td>${record.family_medical_history || 'N/A'}</td>
                <td>${record.allergies || 'N/A'}</td>
                <td>${medicines ? medicines.join(', ') : 'N/A'}</td>
                <td>${healthDocumentsHtml}</td>
                <td>${record.is_approved ? 'Approved' : 'Pending Approval'}</td> <!-- Health document approval status -->
                <td>${record.is_current ? 'Yes' : 'No'}</td> <!-- Whether this record is current or not -->
            </tr>`;
        medicalHistoryBody.insertAdjacentHTML('beforeend', row);
    });
}

function populateHealthDocuments(documents) {
    const medicalHistoryBody = document.getElementById('medical-record-history-body');
    medicalHistoryBody.innerHTML = ''; // Clear existing rows

    documents.forEach(document => {
        const row = `
            <tr>
                <td>${document.chronic_conditions || 'N/A'}</td>
                <td>${document.surgical_history || 'N/A'}</td>
                <td>${document.family_medical_history || 'N/A'}</td>
                <td>${document.allergies || 'N/A'}</td>
                <td>${document.medicines ? document.medicines.join(', ') : 'N/A'}</td>
                <td>
                    <a href="javascript:void(0);" onclick="openImageModal('${document.file_path}')">
                        View Document
                    </a>
                </td>
                <td>${document.is_approved ? 'Approved' : 'Pending'}</td>
                <td>${document.is_current ? 'Current' : 'Not Current'}</td>
            </tr>`;
        medicalHistoryBody.insertAdjacentHTML('beforeend', row);
    });
}

// Populate Physical Examination History Table
function populatePhysicalExaminationHistory(exams) {
    const physicalExaminationBody = document.getElementById('physical-examination-history-body');
    physicalExaminationBody.innerHTML = ''; // Clear existing rows
    exams.forEach(exam => {
        const row = `
            <tr>
                <td>${exam.height || 'N/A'}</td>
                <td>${exam.weight || 'N/A'}</td>
                <td>${exam.vision || 'N/A'}</td>
                <td>${exam.medicine_intake || 'N/A'}</td>
                <td>${exam.remarks || 'N/A'}</td>
            </tr>`;
        physicalExaminationBody.insertAdjacentHTML('beforeend', row);
    });
}

// Populate Health Examination Uploads Table and Add Preview Modal for Images
function populateHealthExaminationHistory(healthExamination) {
    const healthExaminationUploadsBody = document.getElementById('health-examination-uploads-body');
    healthExaminationUploadsBody.innerHTML = ''; // Clear existing rows

    // Add Health Examination Picture with Preview Modal
    if (healthExamination.health_examination_picture) {
        const healthExamRow = `
            <tr>
                <td>2024-2025</td>
                <td><a href="javascript:void(0);" onclick="openImageModal('/storage/${healthExamination.health_examination_picture}')">View</a></td>
            </tr>`;
        healthExaminationUploadsBody.insertAdjacentHTML('beforeend', healthExamRow);
    }

    // Add Lab Results with Preview Modal
    if (healthExamination.lab_result_picture) {
        try {
            let labResults = JSON.parse(healthExamination.lab_result_picture);
            labResults.forEach((labResult, index) => {
                const labResultRow = `
                    <tr>
                        <td>2024-2025</td>
                        <td><a href="javascript:void(0);" onclick="openImageModal('/storage/${labResult}')">Lab Result ${index + 1}</a></td>
                    </tr>`;
                healthExaminationUploadsBody.insertAdjacentHTML('beforeend', labResultRow);
            });
        } catch (error) {
            console.error('Error parsing lab result pictures:', error);
        }
    }

    // Add X-ray Pictures with Preview Modal
    if (healthExamination.xray_picture) {
        try {
            let xrayPictures = JSON.parse(healthExamination.xray_picture);
            xrayPictures.forEach((xrayPicture, index) => {
                const xrayPictureRow = `
                    <tr>
                        <td>2024-2025</td>
                        <td><a href="javascript:void(0);" onclick="openImageModal('/storage/${xrayPicture}')">X-ray ${index + 1}</a></td>
                    </tr>`;
                healthExaminationUploadsBody.insertAdjacentHTML('beforeend', xrayPictureRow);
            });
        } catch (error) {
            console.error('Error parsing x-ray pictures:', error);
        }
    }
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
            title: 'Empty Query',
            text: 'Please enter a search term.',
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
            console.log('Search response:', data); // Log search response for debugging
            if (data.success) {
                populateFields(data);
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Medical record data found and populated successfully!',
                    timer: 2000, // Auto-close after 2 seconds
                    showConfirmButton: false
                });
            } else {
                showNoDataAlert(); // Show alert if no matching data found
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

// Auto-calculate BMI when height and weight are input
document.getElementById('height').addEventListener('input', calculateBMI);
document.getElementById('weight').addEventListener('input', calculateBMI);

</script>


</x-app-layout>