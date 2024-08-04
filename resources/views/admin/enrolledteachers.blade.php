<x-app-layout>
    <style>
    .container {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }

        .sidebar {
            width: 80px;
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
        }    </style>

    <div class="main-content">
        <div class="upload-section">
            <h2>Upload Teacher List</h2>
            <p>Please ensure the Excel file follows the format: ID Number, First Name, Last Name, HED/BED</p>
            <form id="upload-form" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="file">Upload Excel File</label>
                    <input type="file" name="file" id="file" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="preview-button">Upload</button>
                </div>
            </form>
        </div>

        <div class="students-section">
            <h2>Enrolled Teachers</h2>
            @if($teachers->isEmpty())
                <p>No teachers enrolled yet.</p>
            @else
                <table class="students-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>HED/BED</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="students-table-body">
                        @foreach($teachers as $teacher)
                            <tr id="student-row-{{ $teacher->id }}">
                                <td>{{ $teacher->id_number }}</td>
                                <td>{{ $teacher->first_name }}</td>
                                <td>{{ $teacher->last_name }}</td>
                                <td>{{ $teacher->grade_or_course }}</td>
                                <td>
                                    <button class="preview-button" style="background-color: {{ $teacher->approved ? '#28a745' : '#dc3545' }};">
                                        {{ $teacher->approved ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" class="toggle-approval" data-student-id="{{ $teacher->id }}" {{ $teacher->approved ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
                    updateStudentsTable(data.teachers);
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

        function updateStudentsTable(teachers) {
            var tbody = document.getElementById('students-table-body');
            tbody.innerHTML = '';
            teachers.forEach(teacher => {
                var row = document.createElement('tr');
                row.id = 'student-row-' + teacher.id;

                row.innerHTML = `
                    <td>${teacher.id_number}</td>
                    <td>${teacher.first_name}</td>
                    <td>${teacher.last_name}</td>
                    <td>${teacher.grade_or_course}</td>
                    <td>
                        <button class="preview-button" style="background-color: ${teacher.approved ? '#28a745' : '#dc3545'};">
                            ${teacher.approved ? 'Active' : 'Inactive'}
                        </button>
                    </td>
                    <td>
                        <label class="switch">
                            <input type="checkbox" class="toggle-approval" data-student-id="${teacher.id}" ${teacher.approved ? 'checked' : ''}>
                            <span class="slider"></span>
                        </label>
                    </td>
                `;
                tbody.appendChild(row);
            });
            attachToggleApprovalEvents();
        }

        function attachToggleApprovalEvents() {
            document.querySelectorAll('.toggle-approval').forEach(input => {
                input.addEventListener('change', function() {
                    var studentId = this.getAttribute('data-student-id');
                    var approved = this.checked ? 1 : 0;

                    var formData = new FormData();
                    formData.append('approved', approved);

                    var actionUrl = `{{ url('admin/teachers') }}/${studentId}/toggle-approval`;

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
                            updateStudentRow(studentId, data.teacher);
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

        function updateStudentRow(studentId, teacher) {
            var row = document.getElementById('student-row-' + studentId);
            row.innerHTML = `
                <td>${teacher.id_number}</td>
                <td>${teacher.first_name}</td>
                <td>${teacher.last_name}</td>
                <td>${teacher.grade_or_course}</td>
                <td>
                    <button class="preview-button" style="background-color: ${teacher.approved ? '#28a745' : '#dc3545'};">
                        ${teacher.approved ? 'Active' : 'Inactive'}
                    </button>
                </td>
                <td>
                    <label class="switch">
                        <input type="checkbox" class="toggle-approval" data-student-id="${teacher.id}" ${teacher.approved ? 'checked' : ''}>
                        <span class="slider"></span>
                    </label>
                </td>
            `;
            attachToggleApprovalEvents();
        }

        // Initial load
        fetch('{{ route('admin.teachers.enrolled') }}', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(teachers => {
            updateStudentsTable(teachers);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    </script>
</x-app-layout>
