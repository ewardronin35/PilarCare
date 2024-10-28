<x-app-layout :pageTitle="'Manage Doctors'">   
    <style>
        /* Import Poppins Font */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }

        .main-content {
            margin-top: 30px;
            box-sizing: border-box;
        }

        /* Tabs */
        .tabs {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 10px;
        }

        .tab {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: border-color 0.3s ease-in-out, background-color 0.3s;
            font-weight: 600;
            font-size: 16px;
            background-color: #e0e0e0;
            border-radius: 10px 10px 0 0;
            gap: 8px;
        }

        .tab:hover {
            background-color: #c9d1d9;
        }

        .tab.active {
            border-bottom: 2px solid #00d2ff;
            background-color: #ffffff;
        }

        .tab i {
            font-size: 18px;
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
            margin-top: 20px;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Container for Forms */
        .forms-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 30px;
            margin-bottom: 40px;
        }

        .form-wrapper {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex: 1 1 45%;
            max-width: 48%;
            box-sizing: border-box;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .form-wrapper h2 {
            margin-bottom: 10px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 20px;
        }

        .form-wrapper p {
            margin-bottom: 20px;
            color: #555;
            text-align: center;
        }

        /* File Upload Styles */
        .file-upload-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 2px dashed #00d1ff;
            padding: 20px;
            border-radius: 10px;
            background-color: #f9f9f9;
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 15px;
        }

        .file-upload-container input[type="file"] {
            display: none;
        }

        .file-upload-container label {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #00d1ff;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            margin-bottom: 10px;
            width: 100%;
            font-size: 16px;
            gap: 8px;
        }

        .file-upload-container label:hover {
            background-color: #00b8e6;
        }

        .file-upload-container .file-name {
            font-size: 16px;
            color: #333;
            margin-top: 10px;
            word-break: break-all;
        }

        /* Buttons */
        .preview-button,
        .toggle-button,
        .save-button,
        .delete-button,
        .edit-button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .preview-button {
            width: 100%;
            max-width: 200px;
        }

        .toggle-button {
            width: 100%;
            max-width: 200px;
        }

        .save-button {
            background-color: #28a745;
            width: 100%;
            max-width: 200px;
        }

        .save-button:hover {
            background-color: #218838;
        }

        .delete-button {
            background-color: #dc3545;
            width: 100%;
            max-width: 150px;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        .edit-button {
            background-color: #007bff;
            width: 100%;
            max-width: 150px;
        }

        .edit-button:hover {
            background-color: #0069d9;
        }

        /* Forms */
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        form label {
            font-size: 16px;
            margin-bottom: 5px;
            color: #333;
            align-self: flex-start;
        }

        form input[type="text"],
        form input[type="file"] {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        form input[type="text"]:hover,
        form input[type="file"]:hover {
            border-color: #00d1ff;
        }

        /* Doctors Table */
        .doctors-section {
            overflow-y: auto;
            margin-top: 20px;
        }

        .doctors-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .doctors-table th,
        .doctors-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .doctors-table th {
            background-color: #00d2ff;
            color: white;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .doctors-table td {
            background-color: #fff;
        }

        /* Status Text */
        .status-text {
            font-size: 14px;
            font-weight: 600;
        }

        /* Search Input */
        .search-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }

        .search-container input[type="text"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
            max-width: 300px;
            box-sizing: border-box;
        }

        /* Toggle Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 20px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 20px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 12px;
            width: 12px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #28a745;
        }

        input:checked + .slider:before {
            transform: translateX(14px);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
            animation: fadeIn 0.5s ease-in-out;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 600px;
            animation: slideIn 0.5s ease-out;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Close Button */
        .close {
            color: #aaa;
            align-self: flex-end;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
        }

        /* Input Fields */
        .modal-content input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
            font-size: 14px;
        }

        /* Save Button */
        .modal-content .save-button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
            display: inline-block;
            margin-top: 10px;
        }

        .modal-content .save-button:hover {
            background-color: #218838;
        }

        /* Animation for modal */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Download Excel Template Button Styles */
        .download-template-button {
            background-color: #ffc107; /* Amber color to stand out */
            color: #fff;
            padding: 12px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none; /* Remove underline from link */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .download-template-button:hover {
            background-color: #e0a800; /* Darker amber on hover */
            transform: translateY(-2px); /* Slight lift effect */
        }

        .download-template-button:active {
            transform: translateY(0); /* Remove lift on click */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .download-template-button i {
            font-size: 18px; /* Slightly larger icon */
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .forms-container {
                flex-direction: column;
                align-items: center;
            }

            .form-wrapper {
                max-width: 100%;
            }

            .doctors-table th,
            .doctors-table td {
                padding: 10px;
                font-size: 14px;
            }

            .search-container input[type="text"] {
                width: 100%;
                max-width: 100%;
            }

            .modal-content {
                width: 95%;
            }
        }
        /* Large Desktops and Smaller (max-width: 1200px) */
@media (max-width: 1200px) {
    /* Forms Container */
    .forms-container {
        flex-direction: column;
        align-items: center;
        gap: 15px;
        margin-bottom: 30px;
    }

    /* Form Wrapper */
    .form-wrapper {
        flex: 1 1 100%;
        max-width: 100%;
    }

    /* Tabs */
    .tabs {
        flex-wrap: wrap;
        gap: 5px;
    }

    /* Tab */
    .tab {
        flex: 1 1 45%;
        justify-content: center;
        padding: 8px 16px;
        font-size: 15px;
    }

    /* File Upload Container */
    .file-upload-container {
        width: 100%;
        padding: 15px;
    }

    /* File Upload Label */
    .file-upload-container label {
        font-size: 14px;
        padding: 8px 12px;
    }

    /* Buttons */
    .preview-button,
    .toggle-button,
    .save-button,
    .delete-button,
    .edit-button {
        font-size: 14px;
        padding: 8px 12px;
    }

    /* Doctors Table */
    .doctors-table th,
    .doctors-table td {
        padding: 10px;
        font-size: 14px;
    }

    /* Search Container */
    .search-container {
        justify-content: center;
    }

    .search-container input[type="text"] {
        max-width: 250px;
        width: 100%;
    }

    .search-container button {
        padding: 8px 16px;
        font-size: 14px;
    }

    /* Calendar Legend */
    .calendar-legend {
        justify-content: center;
        gap: 10px;
    }

    .legend-item {
        font-size: 0.85rem;
    }

    /* Download Template Button */
    .download-template-button {
        font-size: 14px;
        padding: 10px 18px;
    }
}

/* Tablets and Small Desktops (max-width: 992px) */
@media (max-width: 992px) {
    /* Tabs */
    .tab {
        flex: 1 1 100%;
        justify-content: center;
        padding: 8px 16px;
        font-size: 14px;
    }

    /* Forms Container */
    .forms-container {
        gap: 10px;
    }

    /* Form Wrapper */
    .form-wrapper {
        flex: 1 1 100%;
        max-width: 100%;
    }

    /* Form Headers */
    .form-wrapper h2 {
        font-size: 18px;
    }

    /* File Upload Container */
    .file-upload-container {
        padding: 15px;
    }

    /* File Upload Label */
    .file-upload-container label {
        font-size: 14px;
        padding: 8px 12px;
    }

    /* Buttons */
    .preview-button,
    .toggle-button,
    .save-button,
    .delete-button,
    .edit-button {
        font-size: 14px;
        padding: 8px 12px;
    }

    /* Doctors Table */
    .doctors-table th,
    .doctors-table td {
        padding: 10px;
        font-size: 14px;
    }

    /* Search Container */
    .search-container input[type="text"] {
        max-width: 200px;
    }

    .search-container button {
        padding: 8px 16px;
        font-size: 14px;
    }

    /* Calendar Legend */
    .calendar-legend {
        gap: 8px;
    }

    .legend-item {
        font-size: 0.8rem;
    }

    /* Download Template Button */
    .download-template-button {
        font-size: 14px;
        padding: 10px 18px;
    }
}

/* Mobile Devices and Small Tablets (max-width: 768px) */
@media (max-width: 768px) {
    /* Forms Container */
    .forms-container {
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    /* Form Wrapper */
    .form-wrapper {
        padding: 15px;
        max-width: 100%;
    }

    /* Tabs */
    .tabs {
        flex-direction: column;
        align-items: center;
        gap: 5px;
    }

    /* Tab */
    .tab {
        flex: 1 1 100%;
        justify-content: center;
        padding: 8px 16px;
        font-size: 14px;
    }

    /* File Upload Container */
    .file-upload-container {
        width: 100%;
        padding: 15px;
    }

    /* File Upload Label */
    .file-upload-container label {
        font-size: 14px;
        padding: 8px 12px;
    }

    /* Buttons */
    .preview-button,
    .toggle-button,
    .save-button,
    .delete-button,
    .edit-button {
        font-size: 14px;
        padding: 8px 12px;
        width: 100%;
        max-width: none;
    }

    /* Doctors Table */
    .doctors-table th,
    .doctors-table td {
        padding: 8px;
        font-size: 13px;
    }

    /* Search Container */
    .search-container {
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .search-container input[type="text"] {
        max-width: 100%;
        width: 100%;
    }

    .search-container button {
        width: 100%;
        padding: 8px 16px;
        font-size: 14px;
    }

    /* Calendar Legend */
    .calendar-legend {
        flex-direction: column;
        align-items: center;
        gap: 5px;
    }

    .legend-item {
        font-size: 0.75rem;
    }

    /* Download Template Button */
    .download-template-button {
        font-size: 14px;
        padding: 10px 18px;
        width: 100%;
    }
}

/* Small Mobile Devices (max-width: 576px) */
@media (max-width: 576px) {
    /* Main Content */
    .main-content {
        padding: 10px;
    }

    /* Forms Container */
    .forms-container {
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    /* Form Wrapper */
    .form-wrapper {
        padding: 10px;
        max-width: 100%;
    }

    /* Tabs */
    .tabs {
        flex-direction: column;
        align-items: center;
        gap: 5px;
    }

    /* Tab */
    .tab {
        flex: 1 1 100%;
        justify-content: center;
        padding: 6px 12px;
        font-size: 13px;
    }

    /* File Upload Container */
    .file-upload-container {
        width: 100%;
        padding: 10px;
    }

    /* File Upload Label */
    .file-upload-container label {
        font-size: 13px;
        padding: 6px 10px;
    }

    /* Buttons */
    .preview-button,
    .toggle-button,
    .save-button,
    .delete-button,
    .edit-button {
        font-size: 13px;
        padding: 6px 10px;
        width: 100%;
        max-width: none;
    }

    /* Doctors Table */
    .doctors-table th,
    .doctors-table td {
        padding: 6px;
        font-size: 12px;
    }

    /* Search Container */
    .search-container {
        flex-direction: column;
        align-items: center;
        gap: 5px;
    }

    .search-container input[type="text"] {
        max-width: 100%;
        width: 100%;
        font-size: 14px;
        padding: 6px;
    }

    .search-container button {
        width: 100%;
        padding: 6px 12px;
        font-size: 13px;
    }

    /* Calendar Legend */
    .calendar-legend {
        flex-direction: column;
        align-items: center;
        gap: 5px;
    }

    .legend-item {
        font-size: 0.7rem;
    }

    /* Download Template Button */
    .download-template-button {
        font-size: 13px;
        padding: 8px 14px;
        width: 100%;
    }
}

    </style>

    <div class="main-content">
        <!-- Tabs -->
        <div class="tabs">
            <div class="tab active" data-tab="upload-doctors-tab">
                <i class="fas fa-upload"></i>
                Upload Doctor List
            </div>
            <div class="tab" data-tab="view-doctors-tab">
                <i class="fas fa-users"></i>
                View Doctors
            </div>
        </div>

        <!-- Upload Doctor List Tab Content -->
        <div id="upload-doctors-tab" class="tab-content active">
            <div class="forms-container">
                <!-- Upload Doctor List Form -->
                <div class="form-wrapper">
                    <h2><i class="fas fa-file-upload"></i> Upload Doctor List</h2>
                    <p>Please ensure the Excel file follows the format: ID Number, First Name, Last Name, Specialization</p>
                    <a href="{{ route('admin.download.doctor') }}" class="download-template-button">
                        <i class="fas fa-download"></i> Download Excel Template
                    </a>

                    <div id="upload-section">
                        <form id="upload-form" enctype="multipart/form-data">
                            @csrf
                            <div class="file-upload-container">
                                <label for="doctor-file"><i class="fas fa-paperclip"></i> Choose File</label>
                                <input type="file" name="file" id="doctor-file" accept=".xlsx,.csv" required>
                                <div class="file-name" id="doctor-file-name">No file chosen</div>
                                <button type="submit" class="preview-button"><i class="fas fa-upload"></i> Upload</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Add Doctor Form -->
                <div class="form-wrapper">
                    <h2><i class="fas fa-user-plus"></i> Add Doctor</h2>
                    <form id="add-doctor-form">
                        @csrf
                        <label for="doctor-id_number">ID Number</label>
                        <input type="text" id="doctor-id_number" name="id_number" required maxlength="7" pattern="[A-Za-z][0-9]{6}" title="ID number must start with a letter followed by 6 digits.">

                        <label for="doctor-first_name">First Name</label>
                        <input type="text" id="doctor-first_name" name="first_name" required>

                        <label for="doctor-last_name">Last Name</label>
                        <input type="text" id="doctor-last_name" name="last_name" required>

                        <label for="doctor-specialization">Specialization</label>
                        <input type="text" id="doctor-specialization" name="specialization" required>

                        <button type="submit" class="preview-button"><i class="fas fa-user-plus"></i> Add Doctor</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Doctors Tab Content -->
        <div id="view-doctors-tab" class="tab-content">
            <div class="doctors-section">
                <h2><i class="fas fa-users"></i> Enrolled Doctors</h2>
             
                @if($doctors->isEmpty())
                    <p>No doctors enrolled yet.</p>
                @else
                    <div class="table-responsive">
                        <table class="doctors-table" id="doctors-table" aria-label="Enrolled Doctors Table">
                            <thead>
                                <tr>
                                    <th scope="col"><i class="fas fa-id-card" aria-hidden="true"></i> ID Number</th>
                                    <th scope="col"><i class="fas fa-user" aria-hidden="true"></i> First Name</th>
                                    <th scope="col"><i class="fas fa-user" aria-hidden="true"></i> Last Name</th>
                                    <th scope="col"><i class="fas fa-stethoscope" aria-hidden="true"></i> Specialization</th>
                                    <th scope="col"><i class="fas fa-check-circle" aria-hidden="true"></i> Status</th>
                                    <th scope="col"><i class="fas fa-toggle-on"></i> Toggle Status</th>
                                    <th scope="col"><i class="fas fa-tools"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody id="doctors-table-body">
                                @foreach($doctors as $doctor)
                                    <tr id="doctor-row-{{ $doctor->id }}">
                                        <td>{{ $doctor->id_number }}</td>
                                        <td>{{ $doctor->first_name }}</td>
                                        <td>{{ $doctor->last_name }}</td>
                                        <td>{{ $doctor->specialization }}</td>
                                        <td>
                                            <button class="preview-button status-button" style="background-color: {{ $doctor->approved ? '#28a745' : '#dc3545' }};" aria-label="Doctor Status">
                                                {{ $doctor->approved ? 'Active' : 'Inactive' }}
                                            </button>
                                        </td>
                                        <td>
                                            <label class="switch" aria-label="Toggle doctor approval status">
                                                <input type="checkbox" class="toggle-approval" data-doctor-id="{{ $doctor->id }}" {{ $doctor->approved ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <button class="edit-button" data-doctor-id="{{ $doctor->id }}" aria-label="Edit Doctor">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="delete-button" onclick="deleteDoctor({{ $doctor->id }})" aria-label="Delete Doctor">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Edit Doctor Modal -->
            <div id="edit-doctor-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Edit Doctor</h2>
                    <form id="edit-doctor-form">
                        @csrf
                        <input type="hidden" name="id" id="edit-doctor-id">

                        <label for="edit-doctor-id_number">ID Number</label>
                        <input type="text" name="id_number" id="edit-doctor-id_number" required maxlength="7" pattern="[A-Za-z][0-9]{6}" title="ID number must start with a letter followed by 6 digits.">

                        <label for="edit-doctor-first_name">First Name</label>
                        <input type="text" name="first_name" id="edit-doctor-first_name" required>

                        <label for="edit-doctor-last_name">Last Name</label>
                        <input type="text" name="last_name" id="edit-doctor-last_name" required>

                        <label for="edit-doctor-specialization">Specialization</label>
                        <input type="text" name="specialization" id="edit-doctor-specialization" required>

                        <button type="submit" class="save-button"><i class="fas fa-save"></i> Save</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Tab functionality
                $('#doctors-table').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true
    });
                document.querySelectorAll('.tab').forEach(tab => {
                    tab.addEventListener('click', function() {
                        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                        document.querySelectorAll('.tab-content').forEach(tc => tc.classList.remove('active'));
                        this.classList.add('active');
                        document.getElementById(this.getAttribute('data-tab')).classList.add('active');
                    });
                });

                // File selection feedback for Upload Doctor List
                document.getElementById('doctor-file').addEventListener('change', function(event) {
                    if(event.target.files.length > 0){
                        const fileName = event.target.files[0].name;
                        document.getElementById('doctor-file-name').textContent = fileName;
                    } else {
                        document.getElementById('doctor-file-name').textContent = 'No file chosen';
                    }
                });

                // Search functionality for Doctors
              
                // Upload form submission for Doctors
                document.getElementById('upload-form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    var formData = new FormData(this);

                    fetch('{{ route('admin.doctors.import') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            fetchAndUpdateDoctorsTable(); // Re-fetch and update the table
                            document.getElementById('upload-form').reset();
                            document.getElementById('doctor-file-name').textContent = 'No file chosen';
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: data.errors ? data.errors.join('<br>') : 'An error occurred.',
                                showConfirmButton: true,
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was a problem uploading the file.',
                            showConfirmButton: true,
                        });
                    });
                });

                // Add doctor form submission
                document.getElementById('add-doctor-form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    var formData = new FormData(this);

                    fetch('{{ route('admin.doctors.add') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            fetchAndUpdateDoctorsTable(); // Re-fetch and update the table
                            document.getElementById('add-doctor-form').reset();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: data.errors ? data.errors.join('<br>') : 'An error occurred.',
                                showConfirmButton: true,
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was a problem adding the doctor.',
                            showConfirmButton: true,
                        });
                    });
                });

                // Edit doctor modal functionality
                const editDoctorModal = document.getElementById('edit-doctor-modal');
                const closeDoctorModalButtons = editDoctorModal.querySelectorAll('.close');

                closeDoctorModalButtons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        editDoctorModal.style.display = 'none';
                    });
                });

                window.onclick = function(event) {
                    if (event.target == editDoctorModal) {
                        editDoctorModal.style.display = 'none';
                    }
                }

                // Form submission inside the modal for editing doctor
                document.getElementById('edit-doctor-form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    var formData = new FormData(this);
                    var doctorId = document.getElementById('edit-doctor-id').value;

                    // Convert FormData to JSON
                    var data = {};
                    formData.forEach((value, key) => {
                        data[key] = value;
                    });

                    fetch(`/admin/doctors/${doctorId}/edit`, {
                        method: 'POST',
                        body: JSON.stringify(data),
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            fetchAndUpdateDoctorsTable(); // Re-fetch and update the table
                            editDoctorModal.style.display = 'none'; // Close the modal
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: data.errors ? data.errors.join('<br>') : data.message,
                                showConfirmButton: true,
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was a problem updating the doctor.',
                            showConfirmButton: true,
                        });
                    });
                });

                // Initial fetch of doctors on page load
                fetchAndUpdateDoctorsTable();

                // Function to fetch and update doctors table
                function fetchAndUpdateDoctorsTable() {
                    fetch('{{ route('admin.doctors.enrolled') }}', {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(doctors => {
                        updateDoctorsTable(doctors);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }

                // Function to update doctors table
                function updateDoctorsTable(doctors) {
                    var tbody = document.getElementById('doctors-table-body');
                    if (!tbody) {
                        console.error("Element with ID 'doctors-table-body' not found.");
                        return;
                    }
                    tbody.innerHTML = '';
                    doctors.forEach(doctor => {
                        var row = document.createElement('tr');
                        row.id = 'doctor-row-' + doctor.id;

                        row.innerHTML = `
                            <td>${doctor.id_number}</td>
                            <td>${doctor.first_name}</td>
                            <td>${doctor.last_name}</td>
                            <td>${doctor.specialization}</td>
                            <td>
                                <button class="preview-button status-button" style="background-color: ${doctor.approved ? '#28a745' : '#dc3545'};" aria-label="Doctor Status">
                                    ${doctor.approved ? 'Active' : 'Inactive'}
                                </button>
                            </td>
                            <td>
                                <label class="switch" aria-label="Toggle doctor approval status">
                                    <input type="checkbox" class="toggle-approval" data-doctor-id="${doctor.id}" ${doctor.approved ? 'checked' : ''}>
                                    <span class="slider"></span>
                                </label>
                            </td>
                            <td>
                                <button class="edit-button" data-doctor-id="${doctor.id}" aria-label="Edit Doctor">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="delete-button" onclick="deleteDoctor(${doctor.id})" aria-label="Delete Doctor">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </td>`;
                        tbody.appendChild(row);
                    });
                    attachToggleApprovalEvents();
                    attachEditEvents();
                }

                // Function to attach toggle approval events
                function attachToggleApprovalEvents() {
                    document.querySelectorAll('.toggle-approval').forEach(input => {
                        input.addEventListener('change', function() {
                            var doctorId = this.getAttribute('data-doctor-id');
                            var approved = this.checked ? 1 : 0;

                            var formData = new FormData();
                            formData.append('approved', approved);

                            var actionUrl = `/admin/doctors/${doctorId}/toggle-approval`;

                            fetch(actionUrl, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: data.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    updateDoctorRow(doctorId, data.doctor);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'There was a problem updating the doctor status.',
                                        showConfirmButton: true,
                                    });
                                    // Revert the checkbox state
                                    this.checked = !approved;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'There was a problem updating the doctor status.',
                                    showConfirmButton: true,
                                });
                                // Revert the checkbox state
                                this.checked = !approved;
                            });
                        });
                    });
                }

                // Function to update doctor row status after toggle
                function updateDoctorRow(doctorId, doctor) {
                    var row = document.getElementById('doctor-row-' + doctorId);
                    if (!row) {
                        console.error(`Row for doctorId ${doctorId} not found`);
                        return;
                    }

                    var statusButton = row.querySelector('.status-button');

                    // Update button text and background color
                    if (doctor.approved == 1) {
                        statusButton.textContent = 'Active';
                        statusButton.style.backgroundColor = '#28a745';
                    } else {
                        statusButton.textContent = 'Inactive';
                        statusButton.style.backgroundColor = '#dc3545';
                    }
                }

                // Function to attach edit button events
                function attachEditEvents() {
                    document.querySelectorAll('.edit-button').forEach(button => {
                        button.addEventListener('click', function() {
                            var doctorId = this.getAttribute('data-doctor-id');

                            // Fetch doctor data and open the modal
                            fetch(`/admin/doctors/${doctorId}`)
                                .then(response => response.json())
                                .then(doctor => {
                                    if (doctor.success) {
                                        openEditModal(doctor.doctor); // Open the modal with the doctor data
                                    } else {
                                        Swal.fire('Error', doctor.message || 'Unable to fetch doctor data.', 'error');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error fetching doctor data:', error);
                                    Swal.fire('Error', 'Unable to fetch doctor data.', 'error');
                                });
                        });
                    });
                }

                // Function to open the edit modal and populate it with doctor data
                function openEditModal(doctor) {
                    document.getElementById('edit-doctor-id').value = doctor.id;
                    document.getElementById('edit-doctor-id_number').value = doctor.id_number;
                    document.getElementById('edit-doctor-first_name').value = doctor.first_name;
                    document.getElementById('edit-doctor-last_name').value = doctor.last_name;
                    document.getElementById('edit-doctor-specialization').value = doctor.specialization;

                    // Display the modal
                    document.getElementById('edit-doctor-modal').style.display = 'flex';
                }

                // Global deleteDoctor function to be available on button click
                window.deleteDoctor = function(doctorId) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/admin/doctors/${doctorId}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    _method: 'DELETE'
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Deleted!', data.message, 'success');
                                    document.getElementById('doctor-row-' + doctorId).remove();
                                } else {
                                    Swal.fire('Error!', 'There was a problem deleting the doctor.', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire('Error!', 'There was a problem deleting the doctor.', 'error');
                            });
                        }
                    });
                }

                // Function to update doctor row status after toggle (outside DOMContentLoaded for global access)
                function updateDoctorRow(doctorId, doctor) {
                    var row = document.getElementById('doctor-row-' + doctorId);
                    if (!row) {
                        console.error(`Row for doctorId ${doctorId} not found`);
                        return;
                    }

                    var statusButton = row.querySelector('.status-button');

                    // Update button text and background color
                    if (doctor.approved == 1) {
                        statusButton.textContent = 'Active';
                        statusButton.style.backgroundColor = '#28a745';
                    } else {
                        statusButton.textContent = 'Inactive';
                        statusButton.style.backgroundColor = '#dc3545';
                    }
                }

                // Function to attach edit button events
                function attachEditEvents() {
                    document.querySelectorAll('.edit-button').forEach(button => {
                        button.addEventListener('click', function() {
                            var doctorId = this.getAttribute('data-doctor-id');

                            // Fetch doctor data and open the modal
                            fetch(`/admin/doctors/${doctorId}`)
                                .then(response => response.json())
                                .then(doctor => {
                                    if (doctor.success) {
                                        openEditModal(doctor.doctor); // Open the modal with the doctor data
                                    } else {
                                        Swal.fire('Error', doctor.message || 'Unable to fetch doctor data.', 'error');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error fetching doctor data:', error);
                                    Swal.fire('Error', 'Unable to fetch doctor data.', 'error');
                                });
                        });
                    });
                }
            });

            // Global deleteDoctor function outside DOMContentLoaded to ensure accessibility
            function deleteDoctor(doctorId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/doctors/${doctorId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                _method: 'DELETE'
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Deleted!', data.message, 'success');
                                const doctorRow = document.getElementById(`doctor-row-${doctorId}`);
                                if (doctorRow) {
                                    doctorRow.remove();
                                }
                            } else {
                                Swal.fire('Error!', 'There was a problem deleting the doctor.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error!', 'There was a problem deleting the doctor.', 'error');
                        });
                    }
                });
            }
        </script>
    </div>
</x-app-layout>
