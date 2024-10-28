<x-app-layout :pageTitle="'Manage Teachers'">   
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

        /* Teachers (Teachers) Table */
        .teachers-section {
            overflow-y: auto;
            margin-top: 20px;
        }

        .teachers-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .teachers-table th,
        .teachers-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .teachers-table th {
            background-color: #00d2ff;
            color: white;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .teachers-table td {
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
    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <div class="main-content">
        <!-- Tabs -->
        <div class="tabs">
            <div class="tab active" data-tab="upload-tab">
                <i class="fas fa-upload"></i>
                Upload Teacher List
            </div>
         
            <div class="tab" data-tab="teachers-tab">
                <i class="fas fa-users"></i>
                View Teachers
            </div>
        </div>

        <!-- Upload Teacher List Tab Content -->
        <div id="upload-tab" class="tab-content active">
            <div class="forms-container">
                <!-- Upload Teacher List Form -->
                <div class="form-wrapper">
                    <h2><i class="fas fa-file-upload"></i> Upload Teacher List</h2>
                    <p>Please ensure the Excel file follows the format: ID Number, First Name, Last Name, Subject/Department</p>
                    <a href="{{ route('admin.download.teacher') }}" class="download-template-button">
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

                <!-- Add Late Teachers Form -->
                <div class="form-wrapper">
                    <h2><i class="fas fa-user-plus"></i> Add Late Teachers</h2>
                    <form id="late-teacher-form">
                        @csrf
                        <label for="late-id_number">ID Number</label>
                        <input type="text" id="late-id_number" name="late-id_number" required maxlength="7" pattern="[A-Za-z][0-9]{6}" title="ID number must start with a letter followed by 6 digits.">
                        <label for="late-first_name">First Name</label>
                        <input type="text" id="late-first_name" name="late-first_name" required>
                        <label for="late-last_name">Last Name</label>
                        <input type="text" id="late-last_name" name="late-last_name" required>
                        <label for="late-subject_or_department">Subject/Department</label>
                        <input type="text" id="late-subject_or_department" name="late-subject_or_department" required>
                        <button type="submit" class="preview-button"><i class="fas fa-user-plus"></i> Add Teacher</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Teachers Tab Content -->
        <div id="teachers-tab" class="tab-content">
            <div class="teachers-section">
                <h2>Enrolled Teachers</h2>
                
                @if($teachers->isEmpty())
                    <p>No teachers enrolled yet.</p>
                @else
                    <div class="teachers-table-container">
                        <table class="teachers-table" id="teachers-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Subject/Department</th>
                                    <th>Status</th>
                                    <th>Toggle Status</th>
                                    <th>Actions</th>

                                </tr>
                            </thead>
                            <tbody id="teacher-table-body">
                                @foreach($teachers as $teacher)
                                    <tr id="teacher-row-{{ $teacher->id }}">
                                        <td>{{ $teacher->id_number }}</td>
                                        <td>{{ $teacher->first_name }}</td>
                                        <td>{{ $teacher->last_name }}</td>
                                        <td>{{ $teacher->bed_or_hed }}</td>
                                        <td>
    <button class="preview-button status-button" style="background-color: {{ $teacher->approved ? '#28a745' : '#dc3545' }};">
        {{ $teacher->approved ? 'Active' : 'Inactive' }}
    </button>
</td>
<td>
    <label class="switch">
        <input type="checkbox" class="toggle-approval" data-teacher-id="{{ $teacher->id }}" {{ $teacher->approved ? 'checked' : '' }}>
        <span class="slider"></span>
    </label>
</td>
<td>
    <button class="edit-button" data-teacher-id="{{ $teacher->id }}">
        <i class="fas fa-edit"></i> Edit
    </button>
    <button class="delete-button" onclick="deleteTeacher({{ $teacher->id }})">
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

            <!-- Edit Teacher Modal -->
            <div id="edit-teacher-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Edit Teacher</h2>
                    <form id="edit-teacher-form">
                        @csrf
                        <input type="hidden" name="id" id="edit-teacher-id">

                        <label for="edit-id-number">ID Number</label>
                        <input type="text" name="id_number" id="edit-id-number" required maxlength="7" pattern="[A-Za-z][0-9]{6}" title="ID number must start with a letter followed by 6 digits.">

                        <label for="edit-first-name">First Name</label>
                        <input type="text" name="first_name" id="edit-first-name" required>

                        <label for="edit-last-name">Last Name</label>
                        <input type="text" name="last_name" id="edit-last-name" required>

                        <label for="edit-subject-or-department">Subject/Department</label>
                        <input type="text" name="bed_or_hed" id="edit-subject-or-department" required>

                        <button type="submit" class="save-button"><i class="fas fa-save"></i> Save</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Font Awesome -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#teachers-table').DataTable({
                "pageLength": 10,
                "searching": true,
                "ordering": true,
                "lengthChange": true
            })
    // Tab functionality
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

    // Search functionality for Teachers
  

    // Upload form submission
    document.getElementById('upload-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);

        fetch('{{ route('admin.teachers.import') }}', {
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
                fetchAndUpdateTeacherTable(); // Re-fetch and update the table
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

    // Add late teacher form submission
    document.getElementById('late-teacher-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);

        fetch('{{ route('admin.teachers.add') }}', {
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
                fetchAndUpdateTeacherTable(); // Re-fetch and update the table
                document.getElementById('late-teacher-form').reset();
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
                text: 'There was a problem adding the teacher.',
                showConfirmButton: true,
            });
        });
    });

    // Fetch updated teacher table
    function fetchAndUpdateTeacherTable() {
        fetch('{{ route('admin.teachers.enrolled') }}', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(teachers => {
            updateTeacherTable(teachers);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Update teacher table
    function updateTeacherTable(teachers) {
        var tbody = document.getElementById('teacher-table-body');
        if (!tbody) {
            console.error("Element with ID 'teacher-table-body' not found.");
            return;
        }
        tbody.innerHTML = '';
        teachers.forEach(teacher => {
            var row = document.createElement('tr');
            row.id = 'teacher-row-' + teacher.id;

            row.innerHTML = 
                `<td>${teacher.id_number}</td>
                <td>${teacher.first_name}</td>
                <td>${teacher.last_name}</td>
                <td>${teacher.bed_or_hed}</td>
                <td>
                    <button class="preview-button status-button" style="background-color: ${teacher.approved ? '#28a745' : '#dc3545'};">
                        ${teacher.approved ? 'Active' : 'Inactive'}
                    </button>
                </td>
                <td>
                    <label class="switch">
                        <input type="checkbox" class="toggle-approval" data-teacher-id="${teacher.id}" ${teacher.approved ? 'checked' : ''}>
                        <span class="slider"></span>
                    </label>
                </td>
                <td>
                    <button class="edit-button" data-teacher-id="${teacher.id}"><i class="fas fa-edit"></i> Edit</button>
                    <button class="delete-button" onclick="deleteTeacher(${teacher.id})"><i class="fas fa-trash-alt"></i> Delete</button>
                </td>`;
            tbody.appendChild(row);
        });
        attachToggleApprovalEvents();
        attachEditEvents();
    }

    // Toggle approval events
    function attachToggleApprovalEvents() {
        document.querySelectorAll('.toggle-approval').forEach(input => {
            input.addEventListener('change', function() {
                var teacherId = this.getAttribute('data-teacher-id');
                var approved = this.checked ? 1 : 0;

                var formData = new FormData();
                formData.append('approved', approved);

                var actionUrl = `/admin/teachers/${teacherId}/toggle-approval`;

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
                        updateTeacherRow(teacherId, data.teacher);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was a problem updating the teacher status.',
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
                        text: 'There was a problem updating the teacher status.',
                        showConfirmButton: true,
                    });
                    // Revert the checkbox state
                    this.checked = !approved;
                });
            });
        });
    }

    // Update teacher row
    function updateTeacherRow(teacherId, teacher) {
        var row = document.getElementById('teacher-row-' + teacherId);
        if (!row) {
            console.error(`Row for teacherId ${teacherId} not found`);
            return;
        }

        var statusButton = row.querySelector('.status-button');

        // Update button text and background color
        if (teacher.approved == 1) {
            statusButton.textContent = 'Active';
            statusButton.style.backgroundColor = '#28a745';
        } else {
            statusButton.textContent = 'Inactive';
            statusButton.style.backgroundColor = '#dc3545';
        }
    }

    // Delete teacher function
    window.deleteTeacher = function(teacherId) {
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
                fetch(`/admin/teachers/${teacherId}`, {
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
                        document.getElementById('teacher-row-' + teacherId).remove();
                    } else {
                        Swal.fire('Error!', 'There was a problem deleting the teacher.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'There was a problem deleting the teacher.', 'error');
                });
            }
        });
    }

    // Edit button events
    function attachEditEvents() {
        document.querySelectorAll('.edit-button').forEach(button => {
            button.addEventListener('click', function() {
                var teacherId = this.getAttribute('data-teacher-id');

                // Fetch teacher data and open the modal
                fetch(`/admin/teachers/${teacherId}`)
                    .then(response => response.json())
                    .then(teacher => {
                        openEditModal(teacher); // Open the modal with the teacher data
                    })
                    .catch(error => {
                        console.error('Error fetching teacher data:', error);
                        Swal.fire('Error', 'Unable to fetch teacher data', 'error');
                    });
            });
        });
    }

    // Function to open the modal and populate it with teacher data
    function openEditModal(teacher) {
        document.getElementById('edit-teacher-id').value = teacher.id;
        document.getElementById('edit-id-number').value = teacher.id_number;
        document.getElementById('edit-first-name').value = teacher.first_name;
        document.getElementById('edit-last-name').value = teacher.last_name;
        document.getElementById('edit-subject-or-department').value = teacher.bed_or_hed;

        // Display the modal
        document.getElementById('edit-teacher-modal').style.display = 'flex';
    }

    // Close the modal when clicking the 'X' button
    document.querySelector('.close').addEventListener('click', function() {
        document.getElementById('edit-teacher-modal').style.display = 'none';
    });

    // Form submission inside the modal
    document.getElementById('edit-teacher-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        var teacherId = document.getElementById('edit-teacher-id').value;

        fetch(`/admin/teachers/${teacherId}/edit`, {
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
                fetchAndUpdateTeacherTable(); // Re-fetch and update the table
                document.getElementById('edit-teacher-modal').style.display = 'none'; // Close the modal
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
                text: 'There was a problem updating the teacher.',
                showConfirmButton: true,
            });
        });
    });

    // Initial fetch of teachers on page load
    fetchAndUpdateTeacherTable();
});

// Fetch enrolled teachers
function fetchAndUpdateTeacherTable() {
    fetch('{{ route('admin.teachers.enrolled') }}', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(teachers => {
        updateTeacherTable(teachers);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Update teacher table
function updateTeacherTable(teachers) {
    var tbody = document.getElementById('teacher-table-body');
    if (!tbody) {
        console.error("Element with ID 'teacher-table-body' not found.");
        return;
    }
    tbody.innerHTML = '';
    teachers.forEach(teacher => {
        var row = document.createElement('tr');
        row.id = 'teacher-row-' + teacher.id;

        row.innerHTML = 
            `<td>${teacher.id_number}</td>
            <td>${teacher.first_name}</td>
            <td>${teacher.last_name}</td>
            <td>${teacher.bed_or_hed}</td>
            <td>
                <button class="preview-button status-button" style="background-color: ${teacher.approved ? '#28a745' : '#dc3545'};">
                    ${teacher.approved ? 'Active' : 'Inactive'}
                </button>
            </td>
            <td>
                <label class="switch">
                    <input type="checkbox" class="toggle-approval" data-teacher-id="${teacher.id}" ${teacher.approved ? 'checked' : ''}>
                    <span class="slider"></span>
                </label>
            </td>
            <td>
                <button class="edit-button" data-teacher-id="${teacher.id}"><i class="fas fa-edit"></i> Edit</button>
                <button class="delete-button" onclick="deleteTeacher(${teacher.id})"><i class="fas fa-trash-alt"></i> Delete</button>
            </td>`;
        tbody.appendChild(row);
    });
    attachToggleApprovalEvents();
    attachEditEvents();
}

// Toggle approval events
function attachToggleApprovalEvents() {
    document.querySelectorAll('.toggle-approval').forEach(input => {
        input.addEventListener('change', function() {
            var teacherId = this.getAttribute('data-teacher-id');
            var approved = this.checked ? 1 : 0;

            var formData = new FormData();
            formData.append('approved', approved);

            var actionUrl = `/admin/teachers/${teacherId}/toggle-approval`;

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
                    updateTeacherRow(teacherId, data.teacher);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was a problem updating the teacher status.',
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
                    text: 'There was a problem updating the teacher status.',
                    showConfirmButton: true,
                });
                // Revert the checkbox state
                this.checked = !approved;
            });
        });
    });
}

// Update teacher row
function updateTeacherRow(teacherId, teacher) {
    var row = document.getElementById('teacher-row-' + teacherId);
    if (!row) {
        console.error(`Row for teacherId ${teacherId} not found`);
        return;
    }

    var statusButton = row.querySelector('.status-button');

    // Update button text and background color
    if (teacher.approved == 1) {
        statusButton.textContent = 'Active';
        statusButton.style.backgroundColor = '#28a745';
    } else {
        statusButton.textContent = 'Inactive';
        statusButton.style.backgroundColor = '#dc3545';
    }
}

// Delete teacher function
window.deleteTeacher = function(teacherId) {
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
            fetch(`/admin/teachers/${teacherId}`, {
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
                    document.getElementById('teacher-row-' + teacherId).remove();
                } else {
                    Swal.fire('Error!', 'There was a problem deleting the teacher.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'There was a problem deleting the teacher.', 'error');
            });
        }
    });
}

// Edit button events
function attachEditEvents() {
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', function() {
            var teacherId = this.getAttribute('data-teacher-id');

            // Fetch teacher data and open the modal
            fetch(`/admin/teachers/${teacherId}`)
                .then(response => response.json())
                .then(teacher => {
                    openEditModal(teacher); // Open the modal with the teacher data
                })
                .catch(error => {
                    console.error('Error fetching teacher data:', error);
                    Swal.fire('Error', 'Unable to fetch teacher data', 'error');
                });
        });
    });
}

// Function to open the modal and populate it with teacher data
function openEditModal(teacher) {
    document.getElementById('edit-teacher-id').value = teacher.id;
    document.getElementById('edit-id-number').value = teacher.id_number;
    document.getElementById('edit-first-name').value = teacher.first_name;
    document.getElementById('edit-last-name').value = teacher.last_name;
    document.getElementById('edit-subject-or-department').value = teacher.bed_or_hed;

    // Display the modal
    document.getElementById('edit-teacher-modal').style.display = 'flex';
}

// Close the modal when clicking the 'X' button
document.querySelector('.close').addEventListener('click', function() {
    document.getElementById('edit-teacher-modal').style.display = 'none';
});

// Form submission inside the modal
document.getElementById('edit-teacher-form').addEventListener('submit', function(event) {
    event.preventDefault();
    var formData = new FormData(this);
    var teacherId = document.getElementById('edit-teacher-id').value;

    fetch(`/admin/teachers/${teacherId}/edit`, {
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
            fetchAndUpdateTeacherTable(); // Re-fetch and update the table
            document.getElementById('edit-teacher-modal').style.display = 'none'; // Close the modal
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
            text: 'There was a problem updating the teacher.',
            showConfirmButton: true,
        });
    });
});

        </script>
    </x-app-layout>
