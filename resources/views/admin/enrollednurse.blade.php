<x-app-layout :pageTitle="'Manage Nurse'">   
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

        /* Nurses Table */
        .nurse-section {
            overflow-y: auto;
            margin-top: 20px;
        }

        .nurse-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .nurse-table th,
        .nurse-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .nurse-table th {
            background-color: #00d2ff;
            color: white;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .nurse-table td {
            background-color: #fff;
        }

        .status-button {
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: default;
            color: white;
            font-size: 14px;
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

            .nurse-table th,
            .nurse-table td {
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
    </style>

    <div class="main-content">
        <!-- Tabs -->
        <div class="tabs">
            <div class="tab active" data-tab="upload-nurses-tab">
                <i class="fas fa-upload"></i>
                Upload Nurses List
            </div>
            <div class="tab" data-tab="nurses-tab">
                <i class="fas fa-users"></i>
                View Nurses List
            </div>
        </div>

        <!-- Upload Nurses List Tab Content -->
        <div id="upload-nurses-tab" class="tab-content active">
            <div class="forms-container">
                <!-- Upload Nurses List Form -->
                <div class="form-wrapper">
                    <h2><i class="fas fa-file-upload"></i> Upload Nurses List</h2>
                    <p>Please ensure the Excel file follows the format: ID Number, First Name, Last Name, Department</p>
                    <a href="{{ route('admin.download.nurse') }}" class="download-template-button">
                        <i class="fas fa-download"></i> Download Excel Template
                    </a>

                    <div id="upload-section">
                        <form id="upload-form" enctype="multipart/form-data">
                            @csrf
                            <div class="file-upload-container">
                                <label for="nurse-file"><i class="fas fa-paperclip"></i> Choose File</label>
                                <input type="file" name="file" id="nurse-file" accept=".xlsx,.csv" required>
                                <div class="file-name" id="nurse-file-name">No file chosen</div>
                                <button type="submit" class="preview-button"><i class="fas fa-upload"></i> Upload</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Add Nurse Form -->
                <div class="form-wrapper">
                    <h2><i class="fas fa-user-plus"></i> Add Nurse</h2>
                    <form id="add-nurse-form">
                        @csrf
                        <label for="nurse-id_number">ID Number</label>
                        <input type="text" id="nurse-id_number" name="id_number" required maxlength="7" pattern="[A-Za-z][0-9]{6}" title="ID number must start with a letter followed by 6 digits.">

                        <label for="nurse-first_name">First Name</label>
                        <input type="text" id="nurse-first_name" name="first_name" required>

                        <label for="nurse-last_name">Last Name</label>
                        <input type="text" id="nurse-last_name" name="last_name" required>

                        <label for="nurse-department">Department</label>
                        <input type="text" id="nurse-department" name="department" required>

                        <button type="submit" class="preview-button"><i class="fas fa-user-plus"></i> Add Nurse</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Nurses List Tab Content -->
        <div id="nurses-tab" class="tab-content">
            <div class="nurse-section">
                <h2><i class="fas fa-users"></i> Enrolled Nurses</h2>
             
                @if($nurses->isEmpty())
                    <p>No nurses enrolled yet.</p>
                @else
                    <div class="table-responsive">
                        <table class="nurse-table" id="nurses-table" aria-label="Enrolled Nurses Table">
                            <thead>
                                <tr>
                                    <th scope="col"><i class="fas fa-id-card" aria-hidden="true"></i> ID Number</th>
                                    <th scope="col"><i class="fas fa-user" aria-hidden="true"></i> First Name</th>
                                    <th scope="col"><i class="fas fa-user" aria-hidden="true"></i> Last Name</th>
                                    <th scope="col"><i class="fas fa-building" aria-hidden="true"></i> Department</th>
                                    <th scope="col"><i class="fas fa-check-circle" aria-hidden="true"></i> Status</th>
                                    <th scope="col"><i class="fas fa-toggle-on"></i> Toggle Status</th>
                                    <th scope="col"><i class="fas fa-tools"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody id="nurses-table-body">
                                @foreach($nurses as $nurse)
                                    <tr id="nurse-row-{{ $nurse->id }}">
                                        <td>{{ $nurse->id_number }}</td>
                                        <td>{{ $nurse->first_name }}</td>
                                        <td>{{ $nurse->last_name }}</td>
                                        <td>{{ $nurse->department }}</td>
                                        <td>
                                            <button class="preview-button status-button" style="background-color: {{ $nurse->approved ? '#28a745' : '#dc3545' }};" aria-label="Nurse Status">
                                                {{ $nurse->approved ? 'Active' : 'Inactive' }}
                                            </button>
                                        </td>
                                        <td>
                                            <label class="switch" aria-label="Toggle nurse approval status">
                                                <input type="checkbox" class="toggle-approval" data-nurse-id="{{ $nurse->id }}" {{ $nurse->approved ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <button class="edit-button" data-nurse-id="{{ $nurse->id }}" aria-label="Edit Nurse">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="delete-button" onclick="deleteNurse({{ $nurse->id }})" aria-label="Delete Nurse">
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

            <!-- Edit Nurse Modal -->
            <div id="edit-nurse-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Edit Nurse</h2>
                    <form id="edit-nurse-form">
                        @csrf
                        <input type="hidden" name="id" id="edit-nurse-id">

                        <label for="edit-nurse-id_number">ID Number</label>
                        <input type="text" name="id_number" id="edit-nurse-id_number" required maxlength="7" pattern="[A-Za-z][0-9]{6}" title="ID number must start with a letter followed by 6 digits.">

                        <label for="edit-nurse-first_name">First Name</label>
                        <input type="text" name="first_name" id="edit-nurse-first_name" required>

                        <label for="edit-nurse-last_name">Last Name</label>
                        <input type="text" name="last_name" id="edit-nurse-last_name" required>

                        <label for="edit-nurse-department">Department</label>
                        <input type="text" name="department" id="edit-nurse-department" required>

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
                $('#nurses-table').DataTable({
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

                // File selection feedback
                document.getElementById('nurse-file').addEventListener('change', function(event) {
                    if(event.target.files.length > 0){
                        const fileName = event.target.files[0].name;
                        document.getElementById('nurse-file-name').textContent = fileName;
                    } else {
                        document.getElementById('nurse-file-name').textContent = 'No file chosen';
                    }
                });

                // Search functionality for Nurses
               

                // Upload form submission
                document.getElementById('upload-form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    var formData = new FormData(this);

                    fetch('{{ route('admin.nurses.import') }}', {
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
                            fetchAndUpdateNursesTable(); // Re-fetch and update the table
                            document.getElementById('upload-form').reset();
                            document.getElementById('nurse-file-name').textContent = 'No file chosen';
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

                // Add nurse form submission
                document.getElementById('add-nurse-form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    var formData = new FormData(this);

                    fetch('{{ route('admin.nurses.add') }}', {
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
                            fetchAndUpdateNursesTable(); // Re-fetch and update the table
                            document.getElementById('add-nurse-form').reset();
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
                            text: 'There was a problem adding the nurse.',
                            showConfirmButton: true,
                        });
                    });
                });

                // Edit nurse modal functionality
                const editModal = document.getElementById('edit-nurse-modal');
                const closeModalButtons = editModal.querySelectorAll('.close');

                closeModalButtons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        editModal.style.display = 'none';
                    });
                });

                window.onclick = function(event) {
                    if (event.target == editModal) {
                        editModal.style.display = 'none';
                    }
                }

                // Form submission inside the modal
                document.getElementById('edit-nurse-form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    var formData = new FormData(this);
                    var nurseId = document.getElementById('edit-nurse-id').value;

                    // Convert FormData to JSON
                    var data = {};
                    formData.forEach((value, key) => {
                        data[key] = value;
                    });

                    fetch(`/admin/nurses/${nurseId}/edit`, {
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
                            fetchAndUpdateNursesTable(); // Re-fetch and update the table
                            editModal.style.display = 'none'; // Close the modal
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
                            text: 'There was a problem updating the nurse.',
                            showConfirmButton: true,
                        });
                    });
                });

                // Initial fetch of nurses on page load
                fetchAndUpdateNursesTable();

                // Function to fetch and update nurses table
                function fetchAndUpdateNursesTable() {
                    fetch('{{ route('admin.nurses.enrolled') }}', {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(nurses => {
                        updateNursesTable(nurses);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }

                // Function to update nurses table
                function updateNursesTable(nurses) {
                    var tbody = document.getElementById('nurses-table-body');
                    if (!tbody) {
                        console.error("Element with ID 'nurses-table-body' not found.");
                        return;
                    }
                    tbody.innerHTML = '';
                    nurses.forEach(nurse => {
                        var row = document.createElement('tr');
                        row.id = 'nurse-row-' + nurse.id;

                        row.innerHTML = `
                            <td>${nurse.id_number}</td>
                            <td>${nurse.first_name}</td>
                            <td>${nurse.last_name}</td>
                            <td>${nurse.department}</td>
                            <td>
                                <button class="preview-button status-button" style="background-color: ${nurse.approved ? '#28a745' : '#dc3545'};">
                                    ${nurse.approved ? 'Active' : 'Inactive'}
                                </button>
                            </td>
                            <td>
                                <label class="switch" aria-label="Toggle nurse approval status">
                                    <input type="checkbox" class="toggle-approval" data-nurse-id="${nurse.id}" ${nurse.approved ? 'checked' : ''}>
                                    <span class="slider"></span>
                                </label>
                            </td>
                            <td>
                                <button class="edit-button" data-nurse-id="${nurse.id}" aria-label="Edit Nurse">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="delete-button" onclick="deleteNurse(${nurse.id})" aria-label="Delete Nurse">
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
                            var nurseId = this.getAttribute('data-nurse-id');
                            var approved = this.checked ? 1 : 0;

                            var formData = new FormData();
                            formData.append('approved', approved);

                            var actionUrl = `/admin/nurses/${nurseId}/toggle-approval`;

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
                                    updateNurseRow(nurseId, data.nurse);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'There was a problem updating the nurse status.',
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
                                    text: 'There was a problem updating the nurse status.',
                                    showConfirmButton: true,
                                });
                                // Revert the checkbox state
                                this.checked = !approved;
                            });
                        });
                    });
                }

                // Function to update nurse row status after toggle
                function updateNurseRow(nurseId, nurse) {
                    var row = document.getElementById('nurse-row-' + nurseId);
                    if (!row) {
                        console.error(`Row for nurseId ${nurseId} not found`);
                        return;
                    }

                    var statusButton = row.querySelector('.status-button');

                    // Update button text and background color
                    if (nurse.approved == 1) {
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
                            var nurseId = this.getAttribute('data-nurse-id');

                            // Fetch nurse data and open the modal
                            fetch(`/admin/nurses/${nurseId}`)
                                .then(response => response.json())
                                .then(nurse => {
                                    if (nurse.success) {
                                        openEditModal(nurse.nurse); // Open the modal with the nurse data
                                    } else {
                                        Swal.fire('Error', nurse.message || 'Unable to fetch nurse data.', 'error');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error fetching nurse data:', error);
                                    Swal.fire('Error', 'Unable to fetch nurse data.', 'error');
                                });
                        });
                    });
                }

                // Function to open the edit modal and populate it with nurse data
                function openEditModal(nurse) {
                    document.getElementById('edit-nurse-id').value = nurse.id;
                    document.getElementById('edit-nurse-id_number').value = nurse.id_number;
                    document.getElementById('edit-nurse-first_name').value = nurse.first_name;
                    document.getElementById('edit-nurse-last_name').value = nurse.last_name;
                    document.getElementById('edit-nurse-department').value = nurse.department;

                    // Display the modal
                    document.getElementById('edit-nurse-modal').style.display = 'flex';
                }

                // Global deleteNurse function to be available on button click
                window.deleteNurse = function(nurseId) {
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
                            fetch(`/admin/nurses/${nurseId}`, {
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
                                    document.getElementById('nurse-row-' + nurseId).remove();
                                } else {
                                    Swal.fire('Error!', 'There was a problem deleting the nurse.', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire('Error!', 'There was a problem deleting the nurse.', 'error');
                            });
                        }
                    });
                }

                // Form submission inside the modal
                document.getElementById('edit-nurse-form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    var formData = new FormData(this);
                    var nurseId = document.getElementById('edit-nurse-id').value;

                    // Convert FormData to JSON
                    var data = {};
                    formData.forEach((value, key) => {
                        data[key] = value;
                    });

                    fetch(`/admin/nurses/${nurseId}/edit`, {
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
                            fetchAndUpdateNursesTable(); // Re-fetch and update the table
                            document.getElementById('edit-nurse-modal').style.display = 'none'; // Close the modal
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
                            text: 'There was a problem updating the nurse.',
                            showConfirmButton: true,
                        });
                    });
                });

                // Function to update nurse row status after toggle
                function updateNurseRow(nurseId, nurse) {
                    var row = document.getElementById('nurse-row-' + nurseId);
                    if (!row) {
                        console.error(`Row for nurseId ${nurseId} not found`);
                        return;
                    }

                    var statusButton = row.querySelector('.status-button');

                    // Update button text and background color
                    if (nurse.approved == 1) {
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
                            var nurseId = this.getAttribute('data-nurse-id');

                            // Fetch nurse data and open the modal
                            fetch(`/admin/nurses/${nurseId}`)
                                .then(response => response.json())
                                .then(nurse => {
                                    if (nurse.success) {
                                        openEditModal(nurse.nurse); // Open the modal with the nurse data
                                    } else {
                                        Swal.fire('Error', nurse.message || 'Unable to fetch nurse data.', 'error');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error fetching nurse data:', error);
                                    Swal.fire('Error', 'Unable to fetch nurse data.', 'error');
                                });
                        });
                    });
                }

                // Initial fetch of nurses on page load
                fetchAndUpdateNursesTable();
            });

            // Function to attach event listeners for Edit and Toggle buttons after table update
            function attachToggleApprovalEvents() {
                document.querySelectorAll('.toggle-approval').forEach(input => {
                    input.addEventListener('change', function() {
                        var nurseId = this.getAttribute('data-nurse-id');
                        var approved = this.checked ? 1 : 0;

                        var formData = new FormData();
                        formData.append('approved', approved);

                        var actionUrl = `/admin/nurses/${nurseId}/toggle-approval`;

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
                                updateNurseRow(nurseId, data.nurse);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'There was a problem updating the nurse status.',
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
                                text: 'There was a problem updating the nurse status.',
                                showConfirmButton: true,
                            });
                            // Revert the checkbox state
                            this.checked = !approved;
                        });
                    });
                });
            }

            function attachEditEvents() {
                document.querySelectorAll('.edit-button').forEach(button => {
                    button.addEventListener('click', function() {
                        var nurseId = this.getAttribute('data-nurse-id');

                        // Fetch nurse data and open the modal
                        fetch(`/admin/nurses/${nurseId}`)
                            .then(response => response.json())
                            .then(nurse => {
                                if (nurse.success) {
                                    openEditModal(nurse.nurse); // Open the modal with the nurse data
                                } else {
                                    Swal.fire('Error', nurse.message || 'Unable to fetch nurse data.', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching nurse data:', error);
                                Swal.fire('Error', 'Unable to fetch nurse data.', 'error');
                            });
                    });
                });
            }

            function openEditModal(nurse) {
                document.getElementById('edit-nurse-id').value = nurse.id;
                document.getElementById('edit-nurse-id_number').value = nurse.id_number;
                document.getElementById('edit-nurse-first_name').value = nurse.first_name;
                document.getElementById('edit-nurse-last_name').value = nurse.last_name;
                document.getElementById('edit-nurse-department').value = nurse.department;

                // Display the modal
                document.getElementById('edit-nurse-modal').style.display = 'flex';
            }

            // Close the modal when clicking the 'X' button or outside the modal
            document.querySelectorAll('.close').forEach(closeBtn => {
                closeBtn.addEventListener('click', function() {
                    document.getElementById('edit-nurse-modal').style.display = 'none';
                });
            });

            window.onclick = function(event) {
                const modal = document.getElementById('edit-nurse-modal');
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            // Form submission inside the modal
            document.getElementById('edit-nurse-form').addEventListener('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                var nurseId = document.getElementById('edit-nurse-id').value;

                // Convert FormData to JSON
                var data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });

                fetch(`/admin/nurses/${nurseId}/edit`, {
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
                        fetchAndUpdateNursesTable(); // Re-fetch and update the table
                        document.getElementById('edit-nurse-modal').style.display = 'none'; // Close the modal
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
                        text: 'There was a problem updating the nurse.',
                        showConfirmButton: true,
                    });
                });
            });

            // Function to update nurse row status after toggle
            function updateNurseRow(nurseId, nurse) {
                var row = document.getElementById('nurse-row-' + nurseId);
                if (!row) {
                    console.error(`Row for nurseId ${nurseId} not found`);
                    return;
                }

                var statusButton = row.querySelector('.status-button');

                // Update button text and background color
                if (nurse.approved == 1) {
                    statusButton.textContent = 'Active';
                    statusButton.style.backgroundColor = '#28a745';
                } else {
                    statusButton.textContent = 'Inactive';
                    statusButton.style.backgroundColor = '#dc3545';
                }
            }
        </script>
    </div>
</x-app-layout>
