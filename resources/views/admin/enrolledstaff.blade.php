<x-app-layout>
    <style>
            .container {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }

      
        .main-content {
            margin-left: 80px;
            width: calc(100% - 80px);
            padding: 20px;
            transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
            background: #f4f6f9;
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
        }
    </style>

    <div class="main-content">
        <div class="upload-section">
            <h2>Upload Staff List</h2>
            <p>Please ensure the Excel file follows the format: ID Number, First Name, Last Name, Role</p>
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
            <h2>Enrolled Staff</h2>
            @if($staff->isEmpty())
                <p>No staff enrolled yet.</p>
            @else
                <table class="students-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="students-table-body">
                        @foreach($staff as $staffMember)
                            <tr id="student-row-{{ $staffMember->id }}">
                                <td>{{ $staffMember->id_number }}</td>
                                <td>{{ $staffMember->first_name }}</td>
                                <td>{{ $staffMember->last_name }}</td>
                                <td>{{ $staffMember->role }}</td>
                                <td>
                                    <button class="preview-button" style="background-color: {{ $staffMember->approved ? '#28a745' : '#dc3545' }};">
                                        {{ $staffMember->approved ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" class="toggle-approval" data-student-id="{{ $staffMember->id }}" {{ $staffMember->approved ? 'checked' : '' }}>
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

            fetch('{{ route('admin.staff.import') }}', {
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
                    updateStudentsTable(data.staff);
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

        function updateStudentsTable(staff) {
            var tbody = document.getElementById('students-table-body');
            tbody.innerHTML = '';
            staff.forEach(staffMember => {
                var row = document.createElement('tr');
                row.id = 'student-row-' + staffMember.id;

                row.innerHTML = `
                    <td>${staffMember.id_number}</td>
                    <td>${staffMember.first_name}</td>
                    <td>${staffMember.last_name}</td>
                    <td>${staffMember.role}</td>
                    <td>
                        <button class="preview-button" style="background-color: ${staffMember.approved ? '#28a745' : '#dc3545'};">
                            ${staffMember.approved ? 'Active' : 'Inactive'}
                        </button>
                    </td>
                    <td>
                        <label class="switch">
                            <input type="checkbox" class="toggle-approval" data-student-id="${staffMember.id}" ${staffMember.approved ? 'checked' : ''}>
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

                    var actionUrl = `{{ url('admin/staff') }}/${studentId}/toggle-approval`;

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
                            updateStudentRow(studentId, data.staffMember);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'There was a problem updating the staff status.',
                                showConfirmButton: true,
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was a problem updating the staff status.',
                            showConfirmButton: true,
                        });
                    });
                });
            });
        }

        function updateStudentRow(studentId, staffMember) {
            var row = document.getElementById('student-row-' + studentId);
            row.innerHTML = `
                <td>${staffMember.id_number}</td>
                <td>${staffMember.first_name}</td>
                <td>${staffMember.last_name}</td>
                <td>${staffMember.role}</td>
                <td>
                    <button class="preview-button" style="background-color: ${staffMember.approved ? '#28a745' : '#dc3545'};">
                        ${staffMember.approved ? 'Active' : 'Inactive'}
                    </button>
                </td>
                <td>
                    <label class="switch">
                        <input type="checkbox" class="toggle-approval" data-student-id="${staffMember.id}" ${staffMember.approved ? 'checked' : ''}>
                        <span class="slider"></span>
                    </label>
                </td>
            `;
            attachToggleApprovalEvents();
        }

        // Initial load
        fetch('{{ route('admin.staff.enrolled') }}', {
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
        .then(staff => {
            updateStudentsTable(staff);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    </script>
</x-app-layout>
