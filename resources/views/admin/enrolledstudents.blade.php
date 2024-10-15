<x-app-layout>
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

        /* Students Table */
        .students-section {
            max-height: 600px;
            overflow-y: auto;
            margin-top: 20px;
        }

        .students-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .students-table th,
        .students-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .students-table th {
            background-color: #00d2ff;
            color: white;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .students-table td {
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

        /* Modal Header */
        .modal-content h2 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #333;
            text-align: center;
        }

        /* Save Button in Modal */
        .modal-content .save-button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
            align-self: center;
            width: 100%;
            max-width: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .modal-content .save-button:hover {
            background-color: #218838;
        }

        /* Animations */
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .forms-container {
                flex-direction: column;
                align-items: center;
            }

            .form-wrapper {
                max-width: 100%;
            }

            .students-table th,
            .students-table td {
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

    </style>

    <div class="main-content">
        <!-- Tabs -->
        <div class="tabs">
            <div class="tab active" data-tab="upload-tab">
                <i class="fas fa-upload"></i>
                Upload Student List
            </div>
            <div class="tab" data-tab="students-tab">
                <i class="fas fa-user-graduate"></i>
                View Students
            </div>
        </div>

        <!-- Upload Student List Tab Content -->
        <div id="upload-tab" class="tab-content active">
            <div class="forms-container">
                <!-- Upload Student List Form -->
                <div class="form-wrapper">
                    <h2><i class="fas fa-file-upload"></i> Upload Student List</h2>
                    <p>Please ensure the Excel file follows the format: ID Number, First Name, Last Name, Grade/Course</p>
                    <a href="{{ route('admin.download.templates') }}" class="download-template-button">
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

                <!-- Add Late Students Form -->
                <div class="form-wrapper">
                    <h2><i class="fas fa-user-plus"></i> Add Late Students</h2>
                    <form id="late-student-form">
                        @csrf
                        <label for="late-id_number">ID Number</label>
                        <input type="text" id="late-id_number" name="late-id_number" required maxlength="7" pattern="[A-Za-z][0-9]{6}" title="ID number must start with a letter followed by 6 digits.">
                        
                        <label for="late-first_name">First Name</label>
                        <input type="text" id="late-first_name" name="late-first_name" required>
                        
                        <label for="late-last_name">Last Name</label>
                        <input type="text" id="late-last_name" name="late-last_name" required>
                        
                        <label for="late-grade_or_course">Grade/Course</label>
                        <input type="text" id="late-grade_or_course" name="late-grade_or_course" required>
                        
                        <button type="submit" class="preview-button"><i class="fas fa-user-plus"></i> Add Student</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Students Tab Content -->
        <div id="students-tab" class="tab-content">
            <div class="students-section">
                <h2><i class="fas fa-users"></i> Enrolled Students</h2>
                <div class="search-container">
                    <input type="text" id="student-search" placeholder="Search by ID, Name, or Grade/Course">
                </div>
                @if($students->isEmpty())
                    <p>No students enrolled yet.</p>
                @else
                    <div class="students-table-container">
                        <table class="students-table" id="students-table">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-id-card"></i> ID</th>
                                    <th><i class="fas fa-user"></i> First Name</th>
                                    <th><i class="fas fa-user"></i> Last Name</th>
                                    <th><i class="fas fa-graduation-cap"></i> Grade/Course</th>
                                    <th><i class="fas fa-info-circle"></i> Status</th>
                                    <th><i class="fas fa-toggle-on"></i> Toggle Status</th>
                                    <th><i class="fas fa-tools"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody id="students-table-body">
                                @foreach($students as $student)
                                    <tr id="student-row-{{ $student->id }}">
                                        <td>{{ $student->id_number }}</td>
                                        <td>{{ $student->first_name }}</td>
                                        <td>{{ $student->last_name }}</td>
                                        <td>{{ $student->grade_or_course }}</td>
                                        <td>
                                            <button class="preview-button status-button" style="background-color: {{ $student->approved ? '#28a745' : '#dc3545' }};">
                                                {{ $student->approved ? 'Active' : 'Inactive' }}
                                            </button>
                                        </td>
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" class="toggle-approval" data-student-id="{{ $student->id }}" {{ $student->approved ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <button class="edit-button" data-student-id="{{ $student->id }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="delete-button" onclick="deleteStudent({{ $student->id }})">
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

            <!-- Edit Student Modal -->
            <div id="edit-student-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Edit Student</h2>
                    <form id="edit-student-form">
                        @csrf
                        <input type="hidden" name="id" id="edit-student-id">
                        <label for="edit-id-number">ID Number</label>
                        <input type="text" name="id_number" id="edit-id-number" required>
                        
                        <label for="edit-first-name">First Name</label>
                        <input type="text" name="first_name" id="edit-first-name" required>
                        
                        <label for="edit-last-name">Last Name</label>
                        <input type="text" name="last_name" id="edit-last-name" required>
                        
                        <label for="edit-grade-course">Grade/Course</label>
                        <input type="text" name="grade_or_course" id="edit-grade-course" required>
                        
                        <button type="submit" class="save-button"><i class="fas fa-save"></i> Save</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Font Awesome -->
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <script>
            // Function to switch main tabs
            function switchTab(tabId) {
                document.querySelectorAll('.tab').forEach(tab => {
                    tab.classList.remove('active');
                });
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });

                document.querySelector(`.tab[data-tab="${tabId}"]`).classList.add('active');
                document.getElementById(tabId).classList.add('active');
            }

            // Initialize Main Tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');
                    switchTab(targetTab);
                });
            });

            // Function to open the modal and populate it with student data
            function openEditModal(student) {
                document.getElementById('edit-student-id').value = student.id;
                document.getElementById('edit-id-number').value = student.id_number;
                document.getElementById('edit-first-name').value = student.first_name;
                document.getElementById('edit-last-name').value = student.last_name;
                document.getElementById('edit-grade-course').value = student.grade_or_course;

                // Display the modal
                document.getElementById('edit-student-modal').style.display = 'flex';
            }

            // Close the modal when clicking the 'X' button
            document.querySelector('.close').addEventListener('click', function() {
                document.getElementById('edit-student-modal').style.display = 'none';
            });

            // Close the modal when clicking outside the modal content
            window.onclick = function(event) {
                const modal = document.getElementById('edit-student-modal');
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            // Global deleteStudent function to be available on button click
            function deleteStudent(studentId) {
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
                        fetch(`/admin/students/${studentId}`, {
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
                                document.getElementById('student-row-' + studentId).remove();
                            } else {
                                Swal.fire('Error!', 'There was a problem deleting the student.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error!', 'There was a problem deleting the student.', 'error');
                        });
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Initialize modal
                document.getElementById('edit-student-modal').style.display = 'none';

                // File selection feedback
                document.getElementById('file').addEventListener('change', function(event) {
                    if(event.target.files.length > 0){
                        const fileName = event.target.files[0].name;
                        document.getElementById('file-name').textContent = fileName;
                    } else {
                        document.getElementById('file-name').textContent = 'No file chosen';
                    }
                });

                // Toggle upload section
                const toggleButton = document.getElementById('toggle-upload');
                const uploadSection = document.getElementById('upload-section');
                toggleButton.addEventListener('click', function() {
                    if (uploadSection.classList.contains('hidden')) {
                        uploadSection.classList.remove('hidden');
                        toggleButton.innerHTML = '<i class="fas fa-toggle-off"></i> Hide Upload Section';
                    } else {
                        uploadSection.classList.add('hidden');
                        toggleButton.innerHTML = '<i class="fas fa-toggle-on"></i> Show Upload Section';
                    }
                });

                // Search functionality
                const searchInput = document.getElementById('student-search');
                searchInput.addEventListener('input', function() {
                    const searchValue = this.value.toLowerCase();
                    const tableRows = document.querySelectorAll('#students-table tbody tr');

                    tableRows.forEach(row => {
                        const id = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                        const firstName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                        const lastName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                        const gradeOrCourse = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

                        // Show the row if any of the fields match the search value
                        if (id.includes(searchValue) || firstName.includes(searchValue) || lastName.includes(searchValue) || gradeOrCourse.includes(searchValue)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });

                // Upload form submission
                document.getElementById('upload-form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    var formData = new FormData(this);

                    fetch('{{ route('admin.students.import') }}', {
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
                            fetchAndUpdateStudentsTable(); // Re-fetch and update the table
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

                // Add late student form submission
                document.getElementById('late-student-form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    var formData = new FormData(this);

                    fetch('{{ route('admin.students.add') }}', {
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
                            fetchAndUpdateStudentsTable(); // Re-fetch and update the table
                            document.getElementById('late-student-form').reset();
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
                            text: 'There was a problem adding the student.',
                            showConfirmButton: true,
                        });
                    });
                });

                // Fetch updated students table
                function fetchAndUpdateStudentsTable() {
                    fetch('{{ route('admin.students.enrolled') }}', {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(students => {
                        updateStudentsTable(students);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }

                // Update students table
                function updateStudentsTable(students) {
                    var tbody = document.getElementById('students-table-body');
                    if (!tbody) {
                        console.error("Element with ID 'students-table-body' not found.");
                        return;
                    }
                    tbody.innerHTML = '';
                    students.forEach(student => {
                        var row = document.createElement('tr');
                        row.id = 'student-row-' + student.id;

                        row.innerHTML = `
                            <td>${student.id_number}</td>
                            <td>${student.first_name}</td>
                            <td>${student.last_name}</td>
                            <td>${student.grade_or_course}</td>
                            <td>
                                <button class="preview-button status-button" style="background-color: ${student.approved ? '#28a745' : '#dc3545'};">
                                    ${student.approved ? 'Active' : 'Inactive'}
                                </button>
                            </td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox" class="toggle-approval" data-student-id="${student.id}" ${student.approved ? 'checked' : ''}>
                                    <span class="slider"></span>
                                </label>
                            </td>
                            <td>
                                <button class="edit-button" data-student-id="${student.id}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="delete-button" onclick="deleteStudent(${student.id})">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                    attachToggleApprovalEvents();
                    attachEditEvents();
                }

                // Toggle approval events
                function attachToggleApprovalEvents() {
                    document.querySelectorAll('.toggle-approval').forEach(input => {
                        input.addEventListener('change', function() {
                            var studentId = this.getAttribute('data-student-id');
                            var approved = this.checked ? 1 : 0;

                            var formData = new FormData();
                            formData.append('approved', approved);

                            var actionUrl = `/admin/students/${studentId}/toggle-approval`;

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
                                    updateStudentRow(studentId, data.student);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'There was a problem updating the student status.',
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
                                    text: 'There was a problem updating the student status.',
                                    showConfirmButton: true,
                                });
                                // Revert the checkbox state
                                this.checked = !approved;
                            });
                        });
                    });
                }

                // Update student row
                function updateStudentRow(studentId, student) {
                    var row = document.getElementById('student-row-' + studentId);
                    if (!row) {
                        console.error(`Row for studentId ${studentId} not found`);
                        return;
                    }

                    var button = row.querySelector('.status-button');
                    var checkbox = row.querySelector(`input[data-student-id="${studentId}"]`);

                    // Update button text and background color
                    if (student.approved == 1) {
                        button.textContent = 'Active';
                        button.style.backgroundColor = '#28a745';
                    } else {
                        button.textContent = 'Inactive';
                        button.style.backgroundColor = '#dc3545';
                    }

                    // Update checkbox state
                    checkbox.checked = student.approved == 1;
                }

                // Edit button events
                function attachEditEvents() {
                    document.querySelectorAll('.edit-button').forEach(button => {
                        button.addEventListener('click', function() {
                            var studentId = this.getAttribute('data-student-id');

                            // Fetch student data and open the modal
                            fetch(`/admin/students/${studentId}`)
                                .then(response => response.json())
                                .then(student => {
                                    openEditModal(student); // Open the modal with the student data
                                })
                                .catch(error => {
                                    console.error('Error fetching student data:', error);
                                    Swal.fire('Error', 'Unable to fetch student data', 'error');
                                });
                        });
                    });
                }

                // Form submission inside the modal
                document.getElementById('edit-student-form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    var formData = new FormData(this);
                    var studentId = document.getElementById('edit-student-id').value;

                    fetch(`/admin/students/${studentId}/edit`, {
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
                            fetchAndUpdateStudentsTable(); // Re-fetch and update the table
                            document.getElementById('edit-student-modal').style.display = 'none'; // Close the modal
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
                            text: 'There was a problem updating the student.',
                            showConfirmButton: true,
                        });
                    });
                });

                // Initial fetch of students
                fetchAndUpdateStudentsTable();
            });
        </script>
    </div>
</x-app-layout>
