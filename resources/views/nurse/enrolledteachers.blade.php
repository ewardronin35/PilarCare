<x-app-layout>
    <style>
        /* Add your existing styles here */
        .container {
            margin-top: 30px;
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }
.main-content{
    margin-top: 10px;
}
        .teachers-table {
            width: 95%;
            border-collapse: collapse;
            margin-top: 20px;
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
        }

        .teachers-table td {
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

        .late-teacher-form-container {
            margin-top: 40px;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
        }

        .late-teacher-form-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .late-teacher-form-container label {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .late-teacher-form-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .late-teacher-form-container button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .late-teacher-form-container button:hover {
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

        .teachers-section {
            max-height: 400px;
            overflow-y: auto;
            margin-top: 20px;
        }

        .teachers-table-container {
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
            <div class="tab active" data-tab="upload-tab">Upload Teacher List</div>
            <div class="tab" data-tab="late-teacher-tab">Add Late Teachers</div>
            <div class="tab" data-tab="teachers-tab">View Teachers</div>
        </div>

        <div id="upload-tab" class="tab-content active">
            <div class="upload-section center-text">
                <h2>Upload Teacher List</h2>
                
                <p>Please ensure the Excel file follows the format: ID Number, First Name, Last Name, Subject/Department</p>
                <a href="{{ route('nurse.download.teacher') }}" class="toggle-button">Download Excel Template</a>

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

        <div id="late-teacher-tab" class="tab-content">
            <div class="late-teacher-form-container">
                <h2>Add Late Teachers</h2>
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
                    <button type="submit" class="preview-button">Add Teacher</button>
                </form>
            </div>
        </div>

        <div id="teachers-tab" class="tab-content">
            <div class="teachers-section">
                <h2>Enrolled Teachers</h2>
                <div class="search-container">
                    <input type="text" id="teacher-search" placeholder="Search by ID, Name, or Subject/Department">
                </div>
                @if($teachers->isEmpty())
                    <p>No teachers enrolled yet.</p>
                @else
                    <table class="teachers-table" id="teachers-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Subject/Department</th>
                                <th>Status</th>
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
                                        <button class="preview-button edit-button" data-teacher-id="{{ $teacher->id }}">Edit</button>
                                        <button class="delete-button" onclick="deleteTeacher({{ $teacher->id }})">Delete</button>
                                        <label class="switch">
                                            <input type="checkbox" class="toggle-approval" data-teacher-id="{{ $teacher->id }}" {{ $teacher->approved ? 'checked' : '' }}>
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                </tr>
                                <tr id="edit-form-row-{{ $teacher->id }}" class="edit-form">
                                    <td colspan="6">
                                        <form id="edit-form-{{ $teacher->id }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $teacher->id }}">
                                            <label for="id_number">ID Number</label>
                                            <input type="text" name="id_number" value="{{ $teacher->id_number }}" required>
                                            <label for="first_name">First Name</label>
                                            <input type="text" name="first_name" value="{{ $teacher->first_name }}" required>
                                            <label for="last_name">Last Name</label>
                                            <input type="text" name="last_name" value="{{ $teacher->last_name }}" required>
                                            <label for="subject_or_department">Subject/Department</label>
                                            <input type="text" name="subject_or_department" value="{{ $teacher->bed_or_hed }}" required>
                                            <button type="submit" class="save-button">Save</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <div id="edit-teacher-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Teacher</h2>
        <form id="edit-teacher-form">
            @csrf
            <input type="hidden" name="id" id="edit-teacher-id">
            
            <label for="edit-id-number">ID Number</label>
            <input type="text" name="id_number" id="edit-id-number" required>
            
            <label for="edit-first-name">First Name</label>
            <input type="text" name="first_name" id="edit-first-name" required>
            
            <label for="edit-last-name">Last Name</label>
            <input type="text" name="last_name" id="edit-last-name" required>
            
            <label for="edit-grade-course">BED/HED</label>
            <input type="text" name="bed_or_hed" id="edit-grade-course" required>
            
            <button type="submit" class="save-button">Save</button>
        </form>
    </div>
</div>

        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Function to open the modal and populate it with teacher data
function openEditModal(teacher) {
    document.getElementById('edit-teacher-id').value = teacher.id;
    document.getElementById('edit-id-number').value = teacher.id_number;
    document.getElementById('edit-first-name').value = teacher.first_name;
    document.getElementById('edit-last-name').value = teacher.last_name;
    document.getElementById('edit-grade-course').value = teacher.bed_or_hed; // Use 'bed_or_hed' from the database
    // Display the modal
    document.getElementById('edit-teacher-modal').style.display = 'flex';
}

// Close the modal when clicking the 'X' button
document.querySelector('.close').addEventListener('click', function() {
    document.getElementById('edit-teacher-modal').style.display = 'none';
});

// Global deleteTeacher function to be available on button click
function deleteTeacher(teacherId) {
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
            fetch(`/nurse/teachers/${teacherId}`, {
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

document.addEventListener('DOMContentLoaded', function() {
    // Toggle upload section
    document.getElementById('edit-teacher-modal').style.display = 'none';

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
    const lateTeacherTab = document.querySelector('#late-teacher-tab');
    if (lateTeacherTab) {
        const isLateTeacherTabActive = lateTeacherTab.classList.contains('active');
        const teacherSection = document.querySelector('.teacher-section');
        if (teacherSection) {
            teacherSection.style.display = isLateTeacherTabActive ? 'none' : 'block';
        }
    }
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
    const searchInput = document.getElementById('teacher-search');
searchInput.addEventListener('input', function() {
    const searchValue = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#teachers-table tbody tr');

    tableRows.forEach(row => {
        const id = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
        const firstName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const lastName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        const subjectOrDepartment = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

        // Show the row if any of the fields match the search value
        if (id.includes(searchValue) || firstName.includes(searchValue) || lastName.includes(searchValue) || subjectOrDepartment.includes(searchValue)) {
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

        fetch('{{ route('nurse.teachers.import') }}', {
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

        fetch('{{ route('nurse.teachers.add') }}', {
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
        fetch('{{ route('nurse.teachers.enrolled') }}', {
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
                    <button class="preview-button edit-button" data-teacher-id="${teacher.id}">Edit</button>
                    <button class="delete-button" onclick="deleteTeacher(${teacher.id})">Delete</button>
                    <label class="switch">
                        <input type="checkbox" class="toggle-approval" data-teacher-id="${teacher.id}" ${teacher.approved ? 'checked' : ''}>
                        <span class="slider"></span>
                    </label>
                </td>`;
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
                var teacherId = this.getAttribute('data-teacher-id');
                var approved = this.checked ? 1 : 0;

                var formData = new FormData();
                formData.append('approved', approved);

                var actionUrl = `/nurse/teachers/${teacherId}/toggle-approval`;

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

        var button = row.querySelector('.status-button');
        var checkbox = row.querySelector(`input[data-teacher-id="${teacherId}"]`);

        // Update button text and background color
        if (teacher.approved == 1) {
            button.textContent = 'Active';
            button.style.backgroundColor = '#28a745';
        } else {
            button.textContent = 'Inactive';
            button.style.backgroundColor = '#dc3545';
        }

        // Update checkbox state
        checkbox.checked = teacher.approved == 1;
    }

    // Edit button events
    function attachEditEvents() {
        document.querySelectorAll('.edit-button').forEach(button => {
            button.addEventListener('click', function() {
                var teacherId = this.getAttribute('data-teacher-id');

                // Fetch teacher data and open the modal
                fetch(`/nurse/teachers/${teacherId}`)
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

    // Form submission inside the modal
    document.getElementById('edit-teacher-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        var teacherId = document.getElementById('edit-teacher-id').value;

        fetch(`/nurse/teachers/${teacherId}/edit`, {
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

    // Fetch enrolled teachers on page load
    fetch('{{ route('nurse.teachers.enrolled') }}', {
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
});
</script>

</x-app-layout>