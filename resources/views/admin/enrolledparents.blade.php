<x-app-layout :pageTitle="'Manage Parents'">   
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
            padding: 10px 3px;
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

        /* Parents (Staff) Table */
        .staff-section {
            overflow-y: auto;
            margin-top: 20px;
        }

        .staff-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .staff-table th,
        .staff-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .staff-table th {
            background-color: #00d2ff;
            color: white;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .staff-table td {
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
            padding: 12px 3px;
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

            .staff-table th,
            .staff-table td {
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
            <div class="tab active" data-tab="upload-tab">
                <i class="fas fa-upload"></i>
                Upload Parents List
            </div>
            <div class="tab" data-tab="parents-tab">
                <i class="fas fa-users"></i>
                View Parents List
            </div>
        </div>

        <!-- Upload Parents List Tab Content -->
        <div id="upload-tab" class="tab-content active">
            <div class="forms-container">
                <!-- Upload Parents List Form -->
                <div class="form-wrapper">
                    <h2><i class="fas fa-file-upload"></i> Upload Parents List</h2>
                    <p>Please ensure the Excel file follows the format: ID Number, Student ID, Relationship</p>
                    <a href="{{ route('admin.download.parents-template') }}" class="download-template-button">
                        <i class="fas fa-download"></i> Download Excel Template
                    </a>

                    <div id="upload-section">
                        <form id="upload-form" enctype="multipart/form-data">
                            @csrf
                            <div class="file-upload-container">
                                <label for="file"><i class="fas fa-paperclip"></i> Choose File</label>
                                <input type="file" name="file" id="file" required>
                                <div class="file-name" id="file-name">No file chosen</div>
                                <button type="submit" class="preview-button"><i class="fas fa-upload"></i> Upload</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Add Late Parents Form -->
                <div class="form-wrapper">
                    <h2><i class="fas fa-user-plus"></i> Add Late Parents</h2>
                    <form id="late-parents-form">
                        @csrf
                        <label for="late-id_number">ID Number</label>
                        <input type="text" id="late-id_number" name="late-id_number" required maxlength="7" pattern="[A-Za-z][0-9]{6}" title="ID number must start with a letter followed by 6 digits.">
                        
                        <label for="late-student_id">Student ID</label>
                        <input type="text" id="late-student_id" name="late-student_id" required maxlength="7" pattern="[A-Za-z][0-9]{6}" title="Student ID must start with a letter followed by 6 digits.">
                        
                        <label for="late-relationship">Relationship</label>
                        <input type="text" id="late-relationship" name="late-relationship" required>
                        
                        <button type="submit" class="preview-button"><i class="fas fa-user-plus"></i> Add Parent</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Parents List Tab Content -->
        <div id="parents-tab" class="tab-content">
            <div class="staff-section">
                <h2><i class="fas fa-users"></i> Enrolled Parents</h2>
                
                @if($parents->isEmpty())
                    <p>No parents enrolled yet.</p>
                @else
                    <div class="table-responsive">
                        <table class="staff-table" id="parents-table" aria-label="Enrolled Parents Table">
                            <thead>
                                <tr>
                                    <th scope="col"><i class="fas fa-id-card" aria-hidden="true"></i> ID</th>
                                    <th scope="col"><i class="fas fa-user" aria-hidden="true"></i> Parent First Name</th>
                                    <th scope="col"><i class="fas fa-user" aria-hidden="true"></i> Parent Last Name</th>
                                    <th scope="col"><i class="fas fa-child" aria-hidden="true"></i> Child's Name</th>
                                    <th scope="col"><i class="fas fa-check-circle" aria-hidden="true"></i> Relationship</th>
                                    <th scope="col"><i class="fas fa-info-circle" aria-hidden="true"></i> Status</th>
                                    <th scope="col"><i class="fas fa-toggle-on"></i> Toggle Status</th>
                                    <th scope="col"><i class="fas fa-tools"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody id="parents-table-body">
                                @foreach($parents as $parent)
                                    <tr id="parents-row-{{ $parent->id }}">
                                        <td>{{ $parent->id_number }}</td>
                                        <td>{{ $parent->first_name }}</td>
                                        <td>{{ $parent->last_name }}</td>
                                        <td>
                                            @if($parent->student)
                                                {{ $parent->student->first_name }} {{ $parent->student->last_name }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($parent->guardian_relationship !== 'Not Specified')
                                                {{ $parent->guardian_relationship }}
                                            @else
                                                <span class="text-warning">Not Specified</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="preview-button status-button" style="background-color: {{ $parent->approved ? '#28a745' : '#dc3545' }};" aria-label="Parent Status">
                                                {{ $parent->approved ? 'Active' : 'Inactive' }}
                                            </button>
                                        </td>
                                        <td>
                                            <label class="switch" aria-label="Toggle parent approval status">
                                                <input type="checkbox" class="toggle-approval" data-parent-id="{{ $parent->id }}" {{ $parent->approved ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <button class="edit-button" data-parent-id="{{ $parent->id }}" aria-label="Edit Parent">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="delete-button" onclick="deleteParent({{ $parent->id }})" aria-label="Delete Parent">
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

            <!-- Edit Parent Modal -->
            <div id="edit-parent-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Edit Parent</h2>
                    <form id="edit-parent-form">
                        @csrf
                        <input type="hidden" name="id" id="edit-parent-id">
                        
                        <label for="edit-id-number">ID Number</label>
                        <input type="text" name="id_number" id="edit-id-number" required maxlength="7" pattern="[A-Za-z][0-9]{6}" title="ID number must start with a letter followed by 6 digits.">
                        
                        <label for="edit-first-name">First Name</label>
                        <input type="text" name="first_name" id="edit-first-name" required>
                        
                        <label for="edit-last-name">Last Name</label>
                        <input type="text" name="last_name" id="edit-last-name" required>
                        
                        <label for="edit-student-id">Student ID</label>
                        <input type="text" name="student_id" id="edit-student-id" required maxlength="7" pattern="[A-Za-z][0-9]{6}" title="Student ID must start with a letter followed by 6 digits.">
                        
                        <label for="edit-relationship">Relationship</label>
                        <input type="text" name="relationship" id="edit-relationship" required>
                        
                        <button type="submit" class="save-button"><i class="fas fa-save"></i> Save</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Tab functionality
                $('#parents-table').DataTable({
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
                document.getElementById('file').addEventListener('change', function(event) {
                    if(event.target.files.length > 0){
                        const fileName = event.target.files[0].name;
                        document.getElementById('file-name').textContent = fileName;
                    } else {
                        document.getElementById('file-name').textContent = 'No file chosen';
                    }
                });

           

                // Upload form submission
                document.getElementById('upload-form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    var formData = new FormData(this);

                    fetch('{{ route('admin.parents.import') }}', {
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
                            fetchAndUpdateParentsTable(); // Re-fetch and update the table
                            document.getElementById('upload-form').reset();
                            document.getElementById('file-name').textContent = 'No file chosen';
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: data.errors.join('<br>'),
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

                // Add late parents form submission
                document.getElementById('late-parents-form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    var formData = new FormData(this);

                    fetch('{{ route('admin.parents.add') }}', {
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
                            fetchAndUpdateParentsTable(); // Re-fetch and update the table
                            document.getElementById('late-parents-form').reset();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: data.errors.join('<br>'),
                                showConfirmButton: true,
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was a problem adding the parent.',
                            showConfirmButton: true,
                        });
                    });
                });

                // Edit parent modal functionality
                const editModal = document.getElementById('edit-parent-modal');
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
                document.getElementById('edit-parent-form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    var formData = new FormData(this);
                    var parentId = document.getElementById('edit-parent-id').value;

                    // Convert FormData to JSON
                    var data = {};
                    formData.forEach((value, key) => {
                        data[key] = value;
                    });

                    fetch(`/admin/parents/${parentId}/edit`, {
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
                            fetchAndUpdateParentsTable(); // Re-fetch and update the table
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
                            text: 'There was a problem updating the parent.',
                            showConfirmButton: true,
                        });
                    });
                });

                // Initial fetch of parents on page load
                fetchAndUpdateParentsTable();

                // Function to fetch and update parents table
                function fetchAndUpdateParentsTable() {
                    fetch('{{ route('admin.parents.enrolled') }}', {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(parents => {
                        updateParentsTable(parents);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }

                // Function to update parents table
                function updateParentsTable(parents) {
    var tbody = document.getElementById('parents-table-body');
    if (!tbody) {
        console.error("Element with ID 'parents-table-body' not found.");
        return;
    }
    tbody.innerHTML = '';
    parents.forEach(parent => {
        var row = document.createElement('tr');
        row.id = 'parents-row-' + parent.id;

        row.innerHTML = `
            <td>${parent.id_number}</td>
            <td>${parent.first_name}</td>
            <td>${parent.last_name}</td>
            <td>${parent.student ? parent.student.first_name + ' ' + parent.student.last_name : '<span class="text-muted">N/A</span>'}</td>
            <td>
                ${parent.guardian_relationship !== 'Not Specified' 
                    ? parent.guardian_relationship 
                    : `<span class="text-warning">Not Specified</span> <a href="/admin/parents/${parent.id}/edit" class="edit-link" style="margin-left: 10px;"><i class="fas fa-edit"></i> Add Relationship</a>`}
            </td>
            <td>
                <button class="preview-button status-button" style="background-color: ${parent.approved ? '#28a745' : '#dc3545'};">
                    ${parent.approved ? 'Active' : 'Inactive'}
                </button>
            </td>
            <td>
                <label class="switch" aria-label="Toggle parent approval status">
                    <input type="checkbox" class="toggle-approval" data-parent-id="${parent.id}" ${parent.approved ? 'checked' : ''}>
                    <span class="slider"></span>
                </label>
            </td>
            <td>
                <button class="edit-button" data-parent-id="${parent.id}" aria-label="Edit Parent">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="delete-button" onclick="deleteParent(${parent.id})" aria-label="Delete Parent">
                    <i class="fas fa-trash-alt"></i> Delete
                </button>
            </td>
        `;

        tbody.appendChild(row);
    });
    attachToggleApprovalEvents();
    attachEditEvents();
}


                // Function to attach toggle approval events
                function attachToggleApprovalEvents() {
                    document.querySelectorAll('.toggle-approval').forEach(input => {
                        input.addEventListener('change', function() {
                            var parentId = this.getAttribute('data-parent-id');
                            var approved = this.checked ? 1 : 0;

                            var formData = new FormData();
                            formData.append('approved', approved);

                            var actionUrl = `/admin/parents/${parentId}/toggle-approval`;

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
                                    updateParentRow(parentId, data.parent);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'There was a problem updating the parent status.',
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
                                    text: 'There was a problem updating the parent status.',
                                    showConfirmButton: true,
                                });
                                // Revert the checkbox state
                                this.checked = !approved;
                            });
                        });
                    });
                }

                // Function to update parent row status
                function updateParentRow(parentId, parent) {
                    var row = document.getElementById('parents-row-' + parentId);
                    if (!row) {
                        console.error(`Row for parentId ${parentId} not found`);
                        return;
                    }

                    var statusButton = row.querySelector('.status-button');

                    // Update button text and background color
                    if (parent.approved == 1) {
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
                            var parentId = this.getAttribute('data-parent-id');

                            // Fetch parent data and open the modal
                            fetch(`/admin/parents/${parentId}`)
                                .then(response => response.json())
                                .then(parent => {
                                    openEditModal(parent); // Open the modal with the parent data
                                })
                                .catch(error => {
                                    console.error('Error fetching parent data:', error);
                                    Swal.fire('Error', 'Unable to fetch parent data', 'error');
                                });
                        });
                    });
                }

                // Function to open the edit modal and populate it with parent data
                function openEditModal(parent) {
                    document.getElementById('edit-parent-id').value = parent.id;
                    document.getElementById('edit-id-number').value = parent.id_number;
                    document.getElementById('edit-first-name').value = parent.first_name;
                    document.getElementById('edit-last-name').value = parent.last_name;
                    document.getElementById('edit-student-id').value = parent.student_id;
                    document.getElementById('edit-relationship').value = parent.guardian_relationship !== 'Not Specified' ? parent.guardian_relationship : '';

                    // Display the modal
                    document.getElementById('edit-parent-modal').style.display = 'flex';
                }

                // Global deleteParent function to be available on button click
                window.deleteParent = function(parentId) {
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
                            fetch(`/admin/parents/${parentId}`, {
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
                                    document.getElementById('parents-row-' + parentId).remove();
                                } else {
                                    Swal.fire('Error!', 'There was a problem deleting the parent.', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire('Error!', 'There was a problem deleting the parent.', 'error');
                            });
                        }
                    });
                }
            });

            // Function to attach event listeners for Edit and Toggle buttons after table update
            function attachToggleApprovalEvents() {
                document.querySelectorAll('.toggle-approval').forEach(input => {
                    input.addEventListener('change', function() {
                        var parentId = this.getAttribute('data-parent-id');
                        var approved = this.checked ? 1 : 0;

                        var formData = new FormData();
                        formData.append('approved', approved);

                        var actionUrl = `/admin/parents/${parentId}/toggle-approval`;

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
                                updateParentRow(parentId, data.parent);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'There was a problem updating the parent status.',
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
                                text: 'There was a problem updating the parent status.',
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
                        var parentId = this.getAttribute('data-parent-id');

                        // Fetch parent data and open the modal
                        fetch(`/admin/parents/${parentId}`)
                            .then(response => response.json())
                            .then(parent => {
                                openEditModal(parent); // Open the modal with the parent data
                            })
                            .catch(error => {
                                console.error('Error fetching parent data:', error);
                                Swal.fire('Error', 'Unable to fetch parent data', 'error');
                            });
                    });
                });
            }

            function openEditModal(parent) {
                document.getElementById('edit-parent-id').value = parent.id;
                document.getElementById('edit-id-number').value = parent.id_number;
                document.getElementById('edit-first-name').value = parent.first_name;
                document.getElementById('edit-last-name').value = parent.last_name;
                document.getElementById('edit-student-id').value = parent.student_id;
                document.getElementById('edit-relationship').value = parent.guardian_relationship !== 'Not Specified' ? parent.guardian_relationship : '';

                // Display the modal
                document.getElementById('edit-parent-modal').style.display = 'flex';
            }

            // Close the modal when clicking the 'X' button or outside the modal
            document.querySelectorAll('.close').forEach(closeBtn => {
                closeBtn.addEventListener('click', function() {
                    document.getElementById('edit-parent-modal').style.display = 'none';
                });
            });

            window.onclick = function(event) {
                const modal = document.getElementById('edit-parent-modal');
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            // Form submission inside the modal
            document.getElementById('edit-parent-form').addEventListener('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                var parentId = document.getElementById('edit-parent-id').value;

                // Convert FormData to JSON
                var data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });

                fetch(`/admin/parents/${parentId}/edit`, {
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
                        fetchAndUpdateParentsTable(); // Re-fetch and update the table
                        document.getElementById('edit-parent-modal').style.display = 'none'; // Close the modal
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
                        text: 'There was a problem updating the parent.',
                        showConfirmButton: true,
                    });
                });
            });

            // Function to update parent row status after toggle
            function updateParentRow(parentId, parent) {
                var row = document.getElementById('parents-row-' + parentId);
                if (!row) {
                    console.error(`Row for parentId ${parentId} not found`);
                    return;
                }

                var statusButton = row.querySelector('.status-button');

                // Update button text and background color
                if (parent.approved == 1) {
                    statusButton.textContent = 'Active';
                    statusButton.style.backgroundColor = '#28a745';
                } else {
                    statusButton.textContent = 'Inactive';
                    statusButton.style.backgroundColor = '#dc3545';
                }

                // Update relationship column if changed
                var relationshipCell = row.querySelector('td:nth-child(5)');
                if (parent.guardian_relationship !== 'Not Specified') {
                    relationshipCell.innerHTML = parent.guardian_relationship;
                } else {
                    relationshipCell.innerHTML = `<span class="text-warning">Not Specified</span> <a href="/admin/parents/${parentId}/edit" class="edit-link" style="margin-left: 10px;"><i class="fas fa-edit"></i> Add Relationship</a>`;
                }
            }
        </script>
    </div>
</x-app-layout>
