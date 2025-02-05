<!DOCTYPE html>
<html>
<head>
    <title>Medical Record</title>
    <style>
        body {
            font-family: 'Arial, sans-serif';
            font-size: 12px;
            color: #333;
        }
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 100px;
            margin-right: 20px;
        }
        .header-text {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
        }
        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 15px;
        }
        .section h3 {
            background-color: #007bff;
            color: white;
            padding: 5px;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
            font-size: 0.8rem;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-warning {
            background-color: #ffc107;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ $logoBase64 }}" alt="Logo" class="logo">
        <div class="header-text">Medical Record</div>
    </div>

    @if($information && $information->profile_picture)
        <img src="{{ $profilePictureBase64 }}" alt="Profile Picture" class="profile-picture">
    @endif

    <div class="section">
        <h3>Patient Information</h3>
        <p><strong>ID Number:</strong> {{ $medicalRecord->id_number }}</p>
        <p><strong>Patient Name:</strong> {{ $medicalRecord->user ? $medicalRecord->user->first_name . ' ' . $medicalRecord->user->last_name : 'N/A' }}</p>
        <p><strong>Birthdate:</strong> {{ $medicalRecord->birthdate ? $medicalRecord->birthdate->format('Y-m-d') : 'N/A' }}</p>
        <p><strong>Age:</strong> {{ $medicalRecord->age ?? 'N/A' }}</p>
        <p><strong>Address:</strong> {{ $medicalRecord->address ?? 'N/A' }}</p>
        <p><strong>Contact Number:</strong> {{ $medicalRecord->personal_contact_number ?? 'N/A' }}</p>
        <p><strong>Father's Name:</strong> {{ $medicalRecord->father_name ?? 'N/A' }}</p>
        <p><strong>Mother's Name:</strong> {{ $medicalRecord->mother_name ?? 'N/A' }}</p>
    </div>

    <div class="section">
        <h3>Medical History</h3>
        <p><strong>Chronic Conditions:</strong> {{ $medicalRecord->chronic_conditions ?? 'N/A' }}</p>
        <p><strong>Surgical History:</strong> {{ $medicalRecord->surgical_history ?? 'N/A' }}</p>
        <p><strong>Family Medical History:</strong> {{ $medicalRecord->family_medical_history ?? 'N/A' }}</p>
        <p><strong>Allergies:</strong> {{ $medicalRecord->allergies ?? 'N/A' }}</p>
    </div>

    <div class="section">
        <h3>Medicines</h3>
        <p>{{ is_array(json_decode($medicalRecord->medicines)) ? implode(', ', json_decode($medicalRecord->medicines)) : 'N/A' }}</p>
    </div>

    <div class="section">
        <h3>Health Documents</h3>
        @if(is_array($medicalRecord->health_documents))
            @foreach($medicalRecord->health_documents as $doc)
                <p><a href="{{ asset('storage/' . $doc) }}" target="_blank">Download Document</a></p>
            @endforeach
        @else
            <p>No Documents</p>
        @endif
    </div>

    <div class="section">
        <h3>Approval Status</h3>
        <p>
            @if($medicalRecord->is_approved)
                <span class="badge badge-success">Approved</span>
            @else
                <span class="badge badge-warning">Pending</span>
            @endif
        </p>
    </div>

    @if($physicalExamination)
        <div class="section">
            <h3>Physical Examination</h3>
            <p><strong>Height:</strong> {{ $physicalExamination->height }} cm</p>
            <p><strong>Weight:</strong> {{ $physicalExamination->weight }} kg</p>
            <p><strong>BMI:</strong> {{ round($physicalExamination->weight / (($physicalExamination->height / 100) ** 2), 2) }}</p>
            <p><strong>Vision:</strong> {{ $physicalExamination->vision }}</p>
            <p><strong>Remarks:</strong> {{ $physicalExamination->remarks ?? 'N/A' }}</p>
        </div>
    @endif

    @if($medicineIntakes && $medicineIntakes->count() > 0)
        <div class="section">
            <h3>Medicine Intake History</h3>
            <table>
                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th>Date</th>
                        <th>Dosage</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicineIntakes as $intake)
                        <tr>
                            <td>{{ $intake->medicine_name }}</td>
                            <td>{{ $intake->created_at->format('Y-m-d') }}</td>
                            <td>{{ $intake->dosage }}</td>
                            <td>{{ $intake->reason }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</body>
</html>
