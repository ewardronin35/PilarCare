<x-app-layout :pageTitle="'Medical Record'">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Include DataTables CSS -->

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">

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
                @foreach($medicalData as $index => $data)
                    @php
                        $student = $data['student'];
                        $information = $data['information'];
                        $medicalRecords = $data['medicalRecords'];
                        $latestMedicalRecord = $data['latestMedicalRecord'];
                        $physicalExaminations = $data['physicalExaminations'];
                        $medicineIntakes = $medicalRecords->pluck('medicineIntakes')->flatten();
                    @endphp

                    <!-- Patient Information -->
                    <div class="form-containerd">
                        <div class="form-header">
                            <h2>Patient Information - {{ $student->name }}</h2>
                        </div>

                        <div class="profile-picture">
                            <img src="{{ $information->profile_picture ? asset('storage/' . $information->profile_picture) : asset('images/pilarLogo.jpg') }}" alt="Profile Picture">
                        </div>

                        <div class="form-group-inline">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" 
               value="{{ $student->name ?? ($student->first_name . ' ' . $student->last_name) ?? ($information->first_name . ' ' . $information->last_name) ?? 'N/A' }}" 
               readonly>                            </div>
                            <div class="form-group">
                                <label>Birthdate</label>
                                <input type="date" value="{{ $information->birthdate ?? '' }}" readonly>
                            </div>
                        </div>
                        <div class="form-group-inline">
                            <div class="form-group">
                                <label>Age</label>
                                <input type="number" value="{{ isset($information->birthdate) ? \Carbon\Carbon::parse($information->birthdate)->age : '' }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" value="{{ $information->address ?? '' }}" readonly>
                            </div>
                        </div>
                        <div class="form-group-inline">
                            <div class="form-group">
                                <label>Personal Contact Number</label>
                                <input type="text" value="{{ $information->personal_contact_number ?? '' }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Emergency Contact Number</label>
                                <input type="text" value="{{ $information->emergency_contact_number ?? '' }}" readonly>
                            </div>
                        </div>
                        <div class="form-group-inline">
                            <div class="form-group">
                                <label>Father's Name/Legal Guardian</label>
                                <input type="text" value="{{ $information->parent_name_father ?? '' }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Mother's Name/Legal Guardian</label>
                                <input type="text" value="{{ $information->parent_name_mother ?? '' }}" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Medical Information -->
                    <div class="form-containerd">
                        <div class="form-header">
                            <h2>Medical Information</h2>
                        </div>
                        @if($latestMedicalRecord)
                            <div class="form-group">
                                <label>Record Date</label>
                                <input type="date" value="{{ $latestMedicalRecord->record_date }}" readonly>
                            </div>

                            <div class="form-group-inline">
                                <div class="form-group">
                                    <label>Past Illnesses/Injuries</label>
                                    <input type="text" value="{{ $latestMedicalRecord->past_illness }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Chronic Conditions</label>
                                    <input type="text" value="{{ $latestMedicalRecord->chronic_conditions }}" readonly>
                                </div>
                            </div>
                            <div class="form-group-inline">
                                <div class="form-group">
                                    <label>Surgical History</label>
                                    <input type="text" value="{{ $latestMedicalRecord->surgical_history }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Family Medical History</label>
                                    <input type="text" value="{{ $latestMedicalRecord->family_medical_history }}" readonly>
                                </div>
                            </div>
                            <div class="form-group-inline">
                                <div class="form-group">
                                    <label>Allergies</label>
                                    <input type="text" value="{{ $latestMedicalRecord->allergies }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Medical Condition</label>
                                    <input type="text" value="{{ $latestMedicalRecord->medical_condition }}" readonly>
                                </div>
                            </div>
                            <div class="form-section">
                                <h2>Medicines OK to give/apply at the clinic</h2>
                                @php
                                    $medicines = is_array($latestMedicalRecord->medicines) ? $latestMedicalRecord->medicines : json_decode($latestMedicalRecord->medicines, true);
                                @endphp
                                <ul>
                                    @if($medicines)
                                        @foreach($medicines as $medicine)
                                            <li>{{ $medicine }}</li>
                                        @endforeach
                                    @else
                                        <li>No medicines specified.</li>
                                    @endif
                                </ul>
                            </div>
                        @else
                            <p>No approved medical record available.</p>
                        @endif
                    </div>

                    <!-- Medicine Intake History -->
                    <div class="form-container">
                        <div class="form-header">
                            <h2>Medicine Intake History</h2>
                        </div>
                        @if($medicineIntakes->isNotEmpty())
                            <div class="table-container">
                            <table class="history-table" id="medicine-intake-history-table-{{ $index }}">
                            <thead>
                                        <tr>
                                            <th>Medicine Name</th>
                                            <th>Dosage</th>
                                            <th>Time of Intake</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($medicineIntakes as $intake)
                                            <tr>
                                                <td>{{ $intake->medicine_name }}</td>
                                                <td>{{ $intake->dosage }}</td>
                                                <td>{{ $intake->intake_time }}</td>
                                                <td>{{ $intake->notes ?? 'No notes' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p>No medicine intake history available.</p>
                        @endif
                    </div>


                @endforeach
            </div>
        </div>

        <div id="health-documents" class="tab-content">
    <div class="forms-container">
        @foreach($medicalData as $index => $data)
            @php
                $student = $data['student'];
                $information = $data['information'];
                $medicalRecords = $data['medicalRecords'];
                $latestMedicalRecord = $data['latestMedicalRecord'];
                $physicalExaminations = $data['physicalExaminations'];
                $healthExaminations = $data['healthExaminations'];
                $medicineIntakes = $medicalRecords->pluck('medicineIntakes')->flatten();
            @endphp

            <!-- Health Documents Section -->
            <div class="form-container">
                <div class="form-header">
                    <h1>Health Documents</h1>
                    @if($latestMedicalRecord)
                    <a href="{{ route('parent.medical-record.downloadPdf', $student->id_number) }}" class="btn btn-primary no-spinner">
                    Download Medical Record PDF
</a>
                    @endif
                    @if($healthExaminations->isNotEmpty())
    <a href="{{ route('parent.health-examination.downloadPdf', $student->id_number) }}" class="btn btn-primary no-spinner">
        Download Examination Pictures PDF
    </a>
@endif


                </div>

                <!-- Health Examination Pictures Table -->
                <div class="table-container">
                <table class="history-table" id="health-exam-pictures-table-{{ $index }}">
                <thead>
                            <tr>
                                <th>School Year</th>
                                <th>Health Exam Picture</th>
                                <th>X-ray Pictures</th>
                                <th>Lab Result Pictures</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($healthExaminations as $examination)
                                <tr>
                                    <td>{{ $examination->school_year ?? 'Unknown' }}</td>
                                    <td>
                                        @if($examination->health_examination_picture)
                                            <div class="image-previews">
                                                @foreach((array)$examination->health_examination_picture as $picture)
                                                    <div class="image-container">
                                                        <img src="{{ asset('storage/' . $picture) }}" alt="Health Exam Picture" onclick="openModal('{{ asset('storage/' . $picture) }}')">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span>No Health Exam Picture</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($examination->xray_picture)
                                            <div class="image-previews">
                                                @foreach($examination->xray_picture as $xray)
                                                    <div class="image-container">
                                                        <img src="{{ asset('storage/' . $xray) }}" alt="X-ray Picture" onclick="openModal('{{ asset('storage/' . $xray) }}')">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span>No X-ray Pictures</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($examination->lab_result_picture)
                                            <div class="image-previews">
                                                @foreach($examination->lab_result_picture as $lab)
                                                    <div class="image-container">
                                                        <img src="{{ asset('storage/' . $lab) }}" alt="Lab Result Picture" onclick="openModal('{{ asset('storage/' . $lab) }}')">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span>No Lab Result Pictures</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No health examination pictures available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Medicine Intake History Section -->
                <div class="form-container">
                    <div class="form-header">
                        <h2>Medicine Intake History</h2>
                    </div>
                    @if($medicineIntakes->isNotEmpty())
                    <table class="history-table" id="medicine-intake-history-table-{{ $index }}">
                    <thead>
                                <tr>
                                    <th>Medicine Name</th>
                                    <th>Dosage</th>
                                    <th>Time of Intake</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medicineIntakes as $intake)
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
                        <p>No medicine intake history available.</p>
                    @endif
                </div>

                <!-- BMI Chart -->
                <div class="form-container" style="flex: 1;">
                    <div class="form-header">
                        <h2>BMI Chart</h2>
                    </div>
                    <canvas id="bmiChart{{ $index }}"></canvas>
                </div>

                <!-- Health Documents Table -->
                <div class="form-header" style="margin-top: 30px;">
                    <h2>Health Documents</h2>
                </div>
                @if($medicalRecords->where('is_approved', 1)->isNotEmpty())
                <table class="history-table" id="health-documents-table-{{ $index }}" style="margin-top: 20px;">
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
                            @foreach($medicalRecords->where('is_approved', 1) as $record)
                                <tr>
                                    <td>
                                    @if($record->health_documents)
    @php
        $documents = is_array($record->health_documents) ? $record->health_documents : json_decode($record->health_documents, true);
    @endphp

    @foreach($documents as $document)
        <a href="{{ asset('storage/' . $document) }}" target="_blank" style="text-decoration: none; color: #007bff;">
            <i class="fas fa-file-alt"></i> View Document
                                                </a>
                                                <br>
                                            @endforeach
                                        @else
                                            <span>No Documents</span>
                                        @endif
                                    </td>
                                    <td>{{ $record->medical_condition }}</td>
                                    <td>{{ $record->allergies }}</td>
                                    <td>{{ $record->record_date }}</td>
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

                <!-- Medical Record History and Physical Examination -->
                <div class="form-container">
                    <div class="form-header">
                        <h2>Medical Record History</h2>
                    </div>
                    @if($medicalRecords->where('is_approved', 1)->isNotEmpty())
                    <table class="history-table" id="medical-record-history-table-{{ $index }}">
                    <thead>
                                <tr>
                                    <th>Past Illnesses/Injuries</th>
                                    <th>Chronic Conditions</th>
                                    <th>Surgical History</th>
                                    <th>Family Medical History</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medicalRecords->where('is_approved', 1) as $record)
                                    <tr>
                                        <td>{{ $record->past_illness }}</td>
                                        <td>{{ $record->chronic_conditions }}</td>
                                        <td>{{ $record->surgical_history }}</td>
                                        <td>{{ $record->family_medical_history }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No medical record history available.</p>
                    @endif

                    <h2>Physical Examination History</h2>
                    @if($physicalExaminations->isNotEmpty())
                    <table class="history-table" id="physical-examination-history-table-{{ $index }}">
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
                    @else
                        <p>No physical examination history available.</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>


   
   

    <div id="imageModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <img id="modalImage" src="" alt="Modal Image">
        </div>
    </div>
<!-- Include DataTables JS -->
<!-- jQuery (must be included before DataTables JS) -->
 <!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        
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
        }

        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');

            if (modal && modalImage) {
                modalImage.src = imageSrc;  // Set the modal image source to the clicked image
                modal.classList.add('active');  // Show the modal with animation
            } else {
                console.error('Modal or modal image element not found!');
            }
        }

        // Function to close the modal
        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.remove('active');  // Hide the modal
        }

        // Close the modal when clicking outside the modal content
        window.onclick = function(event) {
            const modal = document.getElementById('imageModal');
            if (event.target === modal) {
                closeModal();  // Close the modal if the user clicks outside of the modal content
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            @foreach($medicalData as $index => $data)
                // Initialize BMI Chart
                loadBMIChart('bmiChart{{ $index }}', '{{ $data['student']->id_number }}');

                // Initialize DataTables for each table
                $(document).ready(function() {
                    // Health Examination Pictures Table
                    $('#health-exam-pictures-table-{{ $index }}').DataTable({
                        "paging": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "responsive": true,
                        "language": {
                            "emptyTable": "No health examination pictures available."
                        },
                        "columnDefs": [
                            { "orderable": false, "targets": [1, 2, 3] } // Assuming columns 1, 2, 3 contain images
                        ]
                    });

                    $('#medicine-intake-history-table-{{ $index }}').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "responsive": true,
            "language": {
                "emptyTable": "No medicine intake history available."
            }
        });

                    // Health Documents Table
                    $('#health-documents-table-{{ $index }}').DataTable({
                        "paging": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "responsive": true,
                        "language": {
                            "emptyTable": "No health documents available."
                        }
                    });

                    // Medical Record History Table
                    $('#medical-record-history-table-{{ $index }}').DataTable({
                        "paging": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "responsive": true,
                        "language": {
                            "emptyTable": "No medical record history available."
                        }
                    });

                    // Physical Examination History Table
                    $('#physical-examination-history-table-{{ $index }}').DataTable({
                        "paging": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "responsive": true,
                        "language": {
                            "emptyTable": "No physical examination history available."
                        }
                    });
                });
            @endforeach
        });


        function loadBMIChart(chartId, id_number) {
    fetch(`/parent/physical-exam/bmi-data/${id_number}`)
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById(chartId).getContext('2d');

            const bmiData = {
                labels: data.bmiData.dates,
                datasets: [{
                    label: 'BMI',
                    data: data.bmiData.bmis,
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
        })
        .catch(error => {
            console.error("Error fetching BMI data: ", error);
        });
}

    </script>
</x-app-layout>
