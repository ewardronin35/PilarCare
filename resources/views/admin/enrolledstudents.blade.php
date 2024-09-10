<x-app-layout>
    <style>
        /* Add your existing styles here */
        .container {
            margin-top: 30px;
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }
      
        .main-content {
            margin-top: 20px;
        }

        .students-table {
            width: 95%;
            height: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
        }

        .students-table td {
            background-color: #fff;
        }

        .preview-button {
            background-color: #00d1ff;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .preview-button:hover {
            background-color: #00b8e6;
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

        .file-upload-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 2px dashed #00d1ff;
            padding: 20px;
            border-radius: 10px;
            background-color: #f9f9f9;
            margin-top: 20px;
            width: 100%;
            max-width: 500px;
            box-sizing: border-box;
            margin: 0 auto;
        }

        .file-upload-container input[type="file"] {
            display: none;
        }

        .file-upload-container label {
            display: block;
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            margin-bottom: 10px;
        }

        .file-upload-container label:hover {
            background-color: #00b8e6;
        }

        .file-upload-container .file-name {
            font-size: 16px;
            color: #333;
            margin-top: 10px;
        }

        .center-text {
            text-align: center;
        }

        .late-student-form-container {
            margin-top: 40px;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
        }

        .late-student-form-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .late-student-form-container label {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .late-student-form-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .late-student-form-container button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .late-student-form-container button:hover {
            background-color: #00b8e6;
        }

        .tabs {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: border-color 0.3s ease-in-out;
        }

        .tab.active {
            border-bottom: 2px solid #00d2ff;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.5s ease-in-out;
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

        .edit-form {
            display: none;
            margin-top: 20px;
        }

        .delete-button {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        .save-button {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .save-button:hover {
            background-color: #218838;
        }

        .toggle-button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            margin-bottom: 10px;
        }

        .toggle-button:hover {
            background-color: #00b8e6;
        }

        #upload-section {
            transition: max-height 0.5s ease-in-out, opacity 0.5s ease-in-out;
            overflow: hidden;
            max-height: 500px; /* Adjust as needed */
            opacity: 1;
        }

        #upload-section.hidden {
            max-height: 0;
            opacity: 0;
            padding: 0;
            margin: 0;
        }

        .students-section {
            max-height: 400px;
            overflow-y: auto;
            margin-top: 20px;
        }

        .students-table-container {
            overflow-x: auto;
        }

        /* Search input styles */
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
            width: 300px;
                }
/* Modal Background Overlay */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4); /* Dark overlay background */
    justify-content: center;
    align-items: center; /* Centers the modal vertically and horizontally */
    display: flex;
}

/* Modal Content */
.modal-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    width: 40%;
    max-width: 600px;
    animation: slideIn 0.5s ease-out;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* Close Button */
.close {
    color: #aaa;
    float: right;
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

/* Modal Header */
.modal-content h2 {
    font-size: 20px;
    margin-bottom: 15px;
    color: #333;
    text-align: center;
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


    </style>

    <div class="main-content">
        <div class="tabs">
            <div class="tab active" data-tab="upload-tab">Upload Student List</div>
            <div class="tab" data-tab="late-student-tab">Add Late Students</div>
            <div class="tab" data-tab="students-tab">View Students</div>
        </div>

        <div id="upload-tab" class="tab-content active">
            <div class="upload-section center-text">
                <h2>Upload Student List</h2>
                <p>Please ensure the Excel file follows the format: ID Number, First Name, Last Name, Grade/Course</p>
                <a href="{{ route('admin.download.templates') }}" class="toggle-button">Download Excel Template</a>

                <button id="toggle-upload" class="toggle-button">Hide Upload Section</button>
                <div id="upload-section">
                    <form id="upload-form" enctype="multipart/form-data">
                        @csrf
                        <div class="file-upload-container">
                            <label for="file">Choose File</label>
                            <input type="file" name="file" id="file" required>
                            <div class="file-name" id="file-name">No file chosen</div>
                            <button type="submit" class="preview-button">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="late-student-tab" class="tab-content">
            <div class="late-student-form-container">
                <h2>Add Late Students</h2>
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
                    <button type="submit" class="preview-button">Add Student</button>
                </form>
            </div>
        </div>

        <div id="students-tab" class="tab-content">
            <div class="students-section">
                <h2>Enrolled Students</h2>
                <div class="search-container">
                    <input type="text" id="student-search" placeholder="Search by ID, Name, or Grade/Course">
                </div>
                @if($students->isEmpty())
                    <p>No students enrolled yet.</p>
                @else
                    <table class="students-table" id="students-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Grade/Course</th>
                                <th>Status</th>
                                <th>Actions</th>
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
                                        <button class="preview-button edit-button" data-student-id="{{ $student->id }}">Edit</button>
                                        <button class="delete-button" onclick="deleteStudent({{ $student->id }})">Delete</button>
                                        <label class="switch">
                                            <input type="checkbox" class="toggle-approval" data-student-id="{{ $student->id }}" {{ $student->approved ? 'checked' : '' }}>
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                </tr>
                                <tr id="edit-form-row-{{ $student->id }}" class="edit-form">
                                    <td colspan="6">
                                        <form id="edit-form-{{ $student->id }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $student->id }}">
                                            <label for="id_number">ID Number</label>
                                            <input type="text" name="id_number" value="{{ $student->id_number }}" required>
                                            <label for="first_name">First Name</label>
                                            <input type="text" name="first_name" value="{{ $student->first_name }}" required>
                                            <label for="last_name">Last Name</label>
                                            <input type="text" name="last_name" value="{{ $student->last_name }}" required>
                                            <label for="grade_or_course">Grade/Course</label>
                                            <input type="text" name="grade_or_course" value="{{ $student->grade_or_course }}" required>
                                            <button type="submit" class="save-button">Save</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
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
            
            <button type="submit" class="save-button">Save</button>
        </form>
    </div>
</div>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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
    // Toggle upload section
    document.getElementById('edit-student-modal').style.display = 'none';

    const toggleUploadSection = () => {
        const uploadSection = document.getElementById('upload-section');
        uploadSection.style.display = uploadSection.style.display === 'none' ? 'block' : 'none';
    };

    // Tab functionality
    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(tc => tc.classList.remove('active'));
            this.classList.add('active');
            document.getElementById(this.getAttribute('data-tab')).classList.add('active');
            toggleTableVisibility();
        });
    });

    function toggleTableVisibility() {
        const isLateStudentTabActive = document.querySelector('#late-student-tab').classList.contains('active');
        document.querySelector('.students-section').style.display = isLateStudentTabActive ? 'none' : 'block';
    }

    // File selection feedback
    document.getElementById('file').addEventListener('change', function(event) {
        const fileName = event.target.files[0].name;
        document.getElementById('file-name').textContent = fileName;
    });

    const toggleButton = document.getElementById('toggle-upload');
    const uploadSection = document.getElementById('upload-section');
    toggleButton.addEventListener('click', function() {
        if (uploadSection.classList.contains('hidden')) {
            uploadSection.classList.remove('hidden');
            toggleButton.textContent = 'Hide Upload Section';
        } else {
            uploadSection.classList.add('hidden');
            toggleButton.textContent = 'Show Upload Section';
        }
    });
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
                    <button class="preview-button edit-button" data-student-id="${student.id}">Edit</button>
                    <button class="delete-button" onclick="deleteStudent(${student.id})">Delete</button>
                    <label class="switch">
                        <input type="checkbox" class="toggle-approval" data-student-id="${student.id}" ${student.approved ? 'checked' : ''}>
                        <span class="slider"></span>
                    </label>
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
            var newInput = input.cloneNode(true);
            input.parentNode.replaceChild(newInput, input);
        });

        document.querySelectorAll('.toggle-approval').forEach(input => {
            input.addEventListener('change', function() {
                var studentId = this.getAttribute('data-student-id');
                var approved = this.checked ? 1 : 0;

                var formData = new FormData();
                formData.append('approved', approved);

                var actionUrl = `{{ url('admin/students') }}/${studentId}/toggle-approval`;

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

        fetch(`{{ url('admin/students') }}/${studentId}/edit`, {
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

    // Fetch enrolled students on page load
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
});
</script>
</x-app-layout>