<x-app-layout>
    <style>
        /* Add your existing styles here */
        .container {
            margin-top: 30px;
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }

        .sidebar {
            width: 100px;
            background-color: #00d2ff;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            transition: width 0.3s ease-in-out;
        }

        .sidebar:hover {
            width: 250px;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0;
            transition: opacity 0.3s ease-in-out;
        }

        .logo-img {
            width: 40px;
            height: 40px;
            margin-right: 34px;
        }

        .logo-text {
            font-size: 1.25rem;
            font-weight: bold;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .sidebar:hover .logo-text {
            opacity: 1;
        }

        .menu ul {
            list-style: none;
            padding: 0;
        }

        .menu li {
            display: flex;
            align-items: center;
            margin: 10px 0;
            padding: 10px 20px;
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .menu li:hover {
            background-color: #009ec3;
            transform: translateX(10px);
        }

        .menu li a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            display: flex;
            align-items: center;
        }

        .menu li a .icon {
            min-width: 20px;
            margin-right: 10px;
            text-align: center;
        }

        .menu-text {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .sidebar:hover .menu-text {
            opacity: 1;
        }

        .main-content {
            margin-left: 80px;
            width: calc(100% - 80px);
            padding: 20px;
            transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
            background: #f4f6f9;
        }

        .sidebar:hover ~ .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: calc(100% - 150px);
            position: fixed;
            top: 0;
            z-index: 999;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info .username {
            margin-right: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .notification-icon {
            margin-right: 20px;
            position: relative;
        }

        .notification-dropdown {
            display: none;
            position: absolute;
            top: 50px;
            right: 10px;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .notification-dropdown.active {
            display: block;
        }

        .notification-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background-color: #f9f9f9;
            transform: translateX(10px);
        }

        .notification-item .icon {
            margin-right: 10px;
        }

        .notification-header {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
            font-weight: bold;
        }

        .students-table {
            width: 95%;
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
    </style>

    <div class="main-content">
        <div class="tabs">
            <div class="tab active" data-tab="upload-tab">Upload Student List</div>
            <div class="tab" data-tab="late-student-tab">Add Late Students</div>
        </div>

        <div id="upload-tab" class="tab-content active">
            <div class="upload-section center-text">
                <h2>Upload Student List</h2>
                <p>Please ensure the Excel file follows the format: ID Number, First Name, Last Name, Grade/Course</p>
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

        <div class="students-section">
            <h2>Enrolled Students</h2>
            @if($students->isEmpty())
                <p>No students enrolled yet.</p>
            @else
                <table class="students-table">
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

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function toggleUploadSection() {
                const uploadSection = document.getElementById('upload-section');
                uploadSection.style.display = uploadSection.style.display === 'none' ? 'block' : 'none';
            }

            document.addEventListener('DOMContentLoaded', function() {
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
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => { throw new Error(text); });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            updateStudentsTable(data.students);
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
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => { throw new Error(text); });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            updateStudentsTable(data.students);
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

                function attachToggleApprovalEvents() {
                    // Remove existing event listeners
                    document.querySelectorAll('.toggle-approval').forEach(input => {
                        var newInput = input.cloneNode(true);
                        input.parentNode.replaceChild(newInput, input);
                    });

                    // Attach new event listeners
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
                            .then(response => {
                                if (!response.ok) {
                                    return response.text().then(text => { throw new Error(text); });
                                }
                                return response.json();
                            })
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

                function attachEditEvents() {
                    document.querySelectorAll('.edit-button').forEach(button => {
                        button.addEventListener('click', function() {
                            var studentId = this.getAttribute('data-student-id');
                            var editFormRow = document.getElementById('edit-form-row-' + studentId);
                            if (editFormRow) {
                                editFormRow.style.display = editFormRow.style.display === 'none' ? 'table-row' : 'none';
                            }
                        });
                    });

                    document.querySelectorAll('.edit-form').forEach(form => {
                        form.addEventListener('submit', function(event) {
                            event.preventDefault();
                            var formData = new FormData(this);
                            var studentId = formData.get('id');

                            fetch(`{{ url('admin/students') }}/${studentId}/edit`, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    return response.text().then(text => { throw new Error(text); });
                                }
                                return response.json();
                            })
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
                                    var editFormRow = document.getElementById('edit-form-row-' + studentId);
                                    if (editFormRow) {
                                        editFormRow.style.display = 'none';
                                    }
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
                    });
                }

                function deleteStudent(studentId) {
                    var formData = new FormData();
                    formData.append('_method', 'DELETE');

                    fetch(`{{ url('admin/students') }}/${studentId}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => { throw new Error(text); });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            document.getElementById('student-row-' + studentId).remove();
                            document.getElementById('edit-form-row-' + studentId).remove();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'There was a problem deleting the student.',
                                showConfirmButton: true,
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was a problem deleting the student.',
                            showConfirmButton: true,
                        });
                    });
                }

                // Initial load
                fetch('{{ route('admin.students.enrolled') }}', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(text); });
                    }
                    return response.json();
                })
                .then(students => {
                    updateStudentsTable(students);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        </script>
    </div>
</x-app-layout>
