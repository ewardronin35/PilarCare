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
            justify-content: center;
            margin-top: 30px;
            margin-bottom: 40px;
        }

        .form-wrapper {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    flex: 0 1 60%; /* Adjusted flex properties */
    max-width: 600px; /* Set a reasonable max-width */
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
        form select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        form input[type="text"]:hover,
        form select:hover {
            border-color: #00d1ff;
        }

        /* Teachers Table */
        .teachers-section {
            overflow-x: auto;
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
        .modal-content input[type="text"],
        .modal-content select {
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

        /* Download Template Button */
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
            margin-bottom: 15px;
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
        .badge {
    padding: 5px 10px;
    border-radius: 5px;
    color: white;
    font-size: 12px;
}

.badge-primary {
    background-color: #007bff;
}

.badge-secondary {
    background-color: #6c757d;
}
.action-button {
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
    margin-right: 5px; /* Optional: spacing between buttons */
}

/* Specific Styles for Each Action */
.view-button {
    background-color: #17a2b8; /* Teal */
    width: 100%;
    max-width: 150px;
}

.view-button:hover {
    background-color: #138496;
}

.edit-button {
    background-color: #007bff; /* Blue */
}

.edit-button:hover {
    background-color: #0069d9;
}

.delete-button {
    background-color: #dc3545; /* Red */
}

.delete-button:hover {
    background-color: #c82333;
}
/* Profile Picture Container */
.profile-picture-container {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.profile-picture-container img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid #00d2ff;
}

/* Details Container */
.details-container {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* Responsive Adjustments */
@media (max-width: 600px) {
    .profile-picture-container img {
        width: 100px;
        height: 100px;
    }
}


    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


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
                    <p>Please ensure the Excel file follows the format: ID Number, First Name, Last Name, BED or HED, Course</p>
                    <a href="{{ route('admin.download.teacher') }}" class="download-template-button">
                        <i class="fas fa-download"></i> Download Excel Template
                    </a>

                    <div id="upload-section">
                    <form id="upload-form" action="{{ route('admin.teachers.import') }}" method="POST" enctype="multipart/form-data">
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

                <!-- Add Teacher Form -->
               
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
                        <table class="teachers-table" id="teachers-table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th> <!-- Added select-all checkbox -->
                                    <th>ID Number</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Department</th>
                                    <th>Course</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Toggle Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="teacher-table-body">
                                @foreach($teachers as $teacher)
                                    <tr id="teacher-row-{{ $teacher->id }}">
                                        <td><input type="checkbox" class="select-teacher" data-teacher-id="{{ $teacher->id }}"></td>
                                        <td>{{ $teacher->id_number }}</td>
                                        <td>{{ $teacher->first_name }}</td>
                                        <td>{{ $teacher->last_name }}</td>
                                        <td>{{ $teacher->bed_or_hed == 'BED' ? 'Basic Education Department (BED)' : 'Higher Education Department (HED)' }}</td>
                                        <td>{{ $teacher->course }}</td>
                                        <td>
                                            @if($teacher->role === 'program_head')
                                                <span class="badge badge-primary">Program Head</span>
                                            @else
                                                <span class="badge badge-secondary">Teacher</span>
                                            @endif
                                        </td>
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
                                            <button class="view-button" data-teacher-id="{{ $teacher->id }}"><i class="fas fa-eye"></i> View</button>
                                            <button class="edit-button" data-teacher-id="{{ $teacher->id }}"><i class="fas fa-edit"></i> Edit</button>
                                            <button class="delete-button" onclick="deleteTeacher({{ $teacher->id }})"><i class="fas fa-trash-alt"></i> Delete</button>
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

                        <label for="edit-id_number">ID Number</label>
                        <input type="text" name="id_number" id="edit-id_number" required maxlength="7" pattern="[A-Za-z][0-9]{6}" title="ID number must start with a letter followed by 6 digits.">

                        <label for="edit-first_name">First Name</label>
                        <input type="text" name="first_name" id="edit-first_name" required>

                        <label for="edit-last_name">Last Name</label>
                        <input type="text" name="last_name" id="edit-last_name" required>

                        <label for="edit-bed_or_hed">Department</label>
                        <select id="edit-bed_or_hed" name="bed_or_hed" required>
                            <option value="">-- Select Department --</option>
                            <option value="BED">Basic Education Department (BED)</option>
                            <option value="HED">Higher Education Department (HED)</option>
                        </select>

                        <!-- Edit Course Container -->
                        <div id="edit-course-container">
                            <label for="edit-course">Course</label>
                            <select id="edit-course" name="course" required>
                                <option value="">-- Select Course --</option>
                                <option value="Elementary">Elementary</option> <!-- Added Elementary as a course -->
                                <option value="BSIT">BSIT</option>
                                <option value="BSBA">BSBA</option>
                                <option value="BEED">BEED</option>
                                <option value="BSN">BSN</option>
                                <option value="BLIS">BLIS</option>
                                <option value="BSTM">BSTM</option>
                                <option value="BSHM">BSHM</option>
                                <!-- Add more courses as needed -->
                            </select>
                        </div>

                        <!-- Role Container for Edit -->
                        <div id="edit-role-container">
                            <label for="edit-role">Role</label>
                            <select id="edit-role" name="role" required>
                                <option value="">-- Select Role --</option>
                                <option value="teacher">Teacher</option>
                                <option value="program_head">Program Head</option>
                                <!-- Add more roles if necessary -->
                            </select>
                        </div>

                        <button type="submit" class="save-button"><i class="fas fa-save"></i> Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        var coursesWithProgramHead = @json($programHeads);
    </script>
 <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTables
        var table = $('#teachers-table').DataTable({
            "ajax": {
                "url": "{{ route('admin.teachers.enrolled-data') }}",
                "dataSrc": ""
            },
            "pageLength": 10,
            "searching": true,
            "ordering": true,
            "lengthChange": true,
            "responsive": true,
            "columnDefs": [
                { "orderable": false, "targets": [0,6,7,8,9] } // Disable ordering on Select, Role, Status, Toggle, and Actions columns
            ],
            "columns": [
                { 
                    "data": null,
                    "render": function (data, type, row) {
                        return `<input type="checkbox" class="select-teacher" data-teacher-id="${row.id}">`;
                    }
                },
                { "data": "id_number" },
                { "data": "first_name" },
                { "data": "last_name" },
                { 
                    "data": "bed_or_hed",
                    "render": function(data, type, row) {
                        return data === 'BED' ? 'Basic Education Department (BED)' : 'Higher Education Department (HED)';
                    }
                },
                { "data": "course" },
                { 
                    "data": "role",
                    "render": function(data, type, row) {
                        if(data === 'program_head') {
                            return `<span class="badge badge-primary">Program Head</span>`;
                        } else {
                            return `<span class="badge badge-secondary">Teacher</span>`;
                        }
                    }
                },
                { 
                    "data": "approved",
                    "render": function(data, type, row) {
                        var color = data ? '#28a745' : '#dc3545';
                        var text = data ? 'Active' : 'Inactive';
                        return `<button class="preview-button status-button" style="background-color: ${color};">${text}</button>`;
                    }
                },
                { 
                    "data": "approved",
                    "render": function(data, type, row) {
                        var checked = data ? 'checked' : '';
                        return `
                            <label class="switch">
                                <input type="checkbox" class="toggle-approval" data-teacher-id="${row.id}" ${checked}>
                                <span class="slider"></span>
                            </label>
                        `;
                    }
                },
                { 
                    "data": "id",
                    "render": function(data, type, row) {
                        return `
                           <!-- Actions Column in DataTable -->
<button class="action-button view-button" data-teacher-id="${data}">
    <i class="fas fa-eye"></i> View
</button>
<button class="action-button edit-button" data-teacher-id="${data}">
    <i class="fas fa-edit"></i> Edit
</button>
<button class="action-button delete-button" onclick="deleteTeacher(${data})">
    <i class="fas fa-trash-alt"></i> Delete
</button>

                        `;
                    }
                }
            ],
            "drawCallback": function(settings) {
                attachViewEvents(); // Re-attach view events after each draw
                attachToggleApprovalEvents(); // Re-attach toggle approval events
                attachEditEvents(); // Re-attach edit events
            }
        });

        // Initial attachment of view events
        attachViewEvents();

        // Tab functionality
        $('.tab').on('click', function() {
            $('.tab').removeClass('active');
            $('.tab-content').removeClass('active');
            $(this).addClass('active');
            $('#' + $(this).data('tab')).addClass('active');

            // If the 'View Teachers' tab is activated, reload the DataTable
            if ($(this).data('tab') === 'teachers-tab') {
                table.ajax.reload(null, false); // Reload data without resetting pagination
            }
        });

        // Handle Upload Form Submission via AJAX
        $('#upload-form').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            var formData = new FormData(this);

            // Show loading indicator
            Swal.fire({
                title: 'Uploading...',
                text: 'Please wait while your file is being uploaded.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    Swal.close(); // Close the loading Swal
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            $('#upload-form')[0].reset(); // Reset the form
                            $('#file-name').text('No file chosen');

                            // Reload the DataTable to fetch new data
                            table.ajax.reload(null, false);
                        });
                    } else {
                        // Display all error messages
                        Swal.fire({
                            icon: 'error',
                            title: 'Import Failed',
                            html: data.errors.join('<br>'),
                            showConfirmButton: true,
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    Swal.close(); // Close the loading Swal
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was a problem uploading the file.',
                        showConfirmButton: true,
                    });
                }
            });
        });

        // Select/Deselect All Checkboxes
        var selectAllCheckbox = document.getElementById('select-all');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                var checkboxes = document.querySelectorAll('.select-teacher');
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
        }

        // Handle Department Selection Change for Add Teacher Form
        var bedOrHedSelect = document.getElementById('bed_or_hed');
        var courseContainer = document.getElementById('course-container');
        var courseSelect = document.getElementById('course');
        var roleContainer = document.getElementById('role-container');
        var roleSelect = document.getElementById('role');

        if (bedOrHedSelect) {
            bedOrHedSelect.addEventListener('change', function() {
                var bedOrHed = this.value;

                if (bedOrHed === 'BED') {
                    // Set course to 'Elementary' and hide course selection
                    courseSelect.value = 'Elementary';
                    courseContainer.style.display = 'none';

                    // Set role to 'teacher' and hide role selection
                    roleSelect.value = 'teacher';
                    roleContainer.style.display = 'none';
                } else if (bedOrHed === 'HED') {
                    // Reset course selection and show course container
                    courseSelect.value = '';
                    courseContainer.style.display = 'block';

                    // Show role selection
                    roleContainer.style.display = 'block';
                    roleSelect.value = '';
                } else {
                    // If no selection, show both course and role containers
                    courseSelect.value = '';
                    courseContainer.style.display = 'block';

                    roleSelect.value = '';
                    roleContainer.style.display = 'block';
                }
            });

            // Initialize form based on existing selection
            (function initializeAddForm() {
                bedOrHedSelect.dispatchEvent(new Event('change'));
            })();
        }

        // Handle Department Selection Change for Edit Teacher Form
        var editBedOrHedSelect = document.getElementById('edit-bed_or_hed');
        var editCourseContainer = document.getElementById('edit-course-container');
        var editCourseSelect = document.getElementById('edit-course');
        var editRoleContainer = document.getElementById('edit-role-container');
        var editRoleSelect = document.getElementById('edit-role');

        if (editBedOrHedSelect) {
            editBedOrHedSelect.addEventListener('change', function() {
                var bedOrHed = this.value;

                if (bedOrHed === 'BED') {
                    // Set course to 'Elementary' and hide course selection
                    editCourseSelect.value = 'Elementary';
                    editCourseContainer.style.display = 'none';

                    // Set role to 'teacher' and hide role selection
                    editRoleSelect.value = 'teacher';
                    editRoleContainer.style.display = 'none';
                } else if (bedOrHed === 'HED') {
                    // Reset course selection and show course container
                    editCourseSelect.value = '';
                    editCourseContainer.style.display = 'block';

                    // Show role selection
                    editRoleContainer.style.display = 'block';
                    editRoleSelect.value = '';
                } else {
                    // If no selection, show both course and role containers
                    editCourseSelect.value = '';
                    editCourseContainer.style.display = 'block';

                    editRoleSelect.value = '';
                    editRoleContainer.style.display = 'block';
                }
            });

            // Initialize edit form based on existing selection
            (function initializeEditForm() {
                editBedOrHedSelect.dispatchEvent(new Event('change'));
            })();
        }

        // File selection feedback
        var fileInput = document.getElementById('file');
        if (fileInput) {
            fileInput.addEventListener('change', function(event) {
                if(event.target.files.length > 0){
                    const fileName = event.target.files[0].name;
                    document.getElementById('file-name').textContent = fileName;
                } else {
                    document.getElementById('file-name').textContent = 'No file chosen';
                }
            });
        }

        // Add teacher form submission
        var addTeacherForm = document.getElementById('add-teacher-form');
        if (addTeacherForm) {
            addTeacherForm.addEventListener('submit', function(event) {
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

                        // Append new teachers to DataTable
                        if (Array.isArray(data.teachers) && data.teachers.length > 0) {
                            data.teachers.forEach(teacher => {
                                table.row.add({
                                    id_number: teacher.id_number,
                                    first_name: teacher.first_name,
                                    last_name: teacher.last_name,
                                    bed_or_hed: teacher.bed_or_hed,
                                    course: teacher.course,
                                    role: teacher.role,
                                    approved: teacher.approved,
                                    id: teacher.id
                                }).draw(false);
                            });
                        }

                        addTeacherForm.reset();
                        // Reset form visibility based on default department selection
                        bedOrHedSelect.dispatchEvent(new Event('change'));
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
        }

        // Edit teacher form submission
        var editTeacherForm = document.getElementById('edit-teacher-form');
        if (editTeacherForm) {
            editTeacherForm.addEventListener('submit', function(event) {
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
                        // Update the specific row in DataTable
                        table.rows().every(function(rowIdx, tableLoop, rowLoop) {
                            var rowData = this.data();
                            if (rowData.id === data.teacher.id) {
                                this.data({
                                    id_number: data.teacher.id_number,
                                    first_name: data.teacher.first_name,
                                    last_name: data.teacher.last_name,
                                    bed_or_hed: data.teacher.bed_or_hed,
                                    course: data.teacher.course,
                                    role: data.teacher.role,
                                    approved: data.teacher.approved,
                                    id: data.teacher.id
                                }).draw(false);
                            }
                        });
                        closeEditModal(); // Close the modal
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
        }

        // Function to close the Edit Modal
        function closeEditModal() {
            var modal = document.getElementById('edit-teacher-modal');
            modal.style.display = 'none';
        }

        // Function to handle View button clicks
        function attachViewEvents() {
            document.querySelectorAll('.view-button').forEach(button => {
                button.addEventListener('click', function() {
                    var teacherId = this.getAttribute('data-teacher-id');
                    
                    // Show loading indicator
                    Swal.fire({
                        title: 'Fetching Data...',
                        text: 'Please wait while the teacher details are being fetched.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    // Fetch teacher data from the server
                    fetch(`/admin/teachers/${teacherId}`)
                        .then(response => response.json())
                        .then(data => {
                            Swal.close(); // Close the loading Swal
                            if (data.success) {
                                populateViewModal(data.teacher);
                                openViewModal();
                            } else {
                                Swal.fire('Error', data.message || 'Unable to fetch teacher data', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching teacher data:', error);
                            Swal.close(); // Close the loading Swal
                            Swal.fire('Error', 'Unable to fetch teacher data', 'error');
                        });
                });
            });
        }

        // Function to populate the View Modal with teacher data
        function populateViewModal(teacher) {
            document.getElementById('view-id_number').textContent = teacher.id_number;
            document.getElementById('view-first_name').textContent = teacher.first_name;
            document.getElementById('view-last_name').textContent = teacher.last_name;
            document.getElementById('view-bed_or_hed').textContent = teacher.bed_or_hed === 'BED' ? 'Basic Education Department (BED)' : 'Higher Education Department (HED)';
            document.getElementById('view-course').textContent = teacher.course;
            document.getElementById('view-role').textContent = teacher.role === 'program_head' ? 'Program Head' : 'Teacher';
            document.getElementById('view-status').textContent = teacher.approved ? 'Active' : 'Inactive';
            document.getElementById('view-father_name').textContent = teacher.father_name || 'N/A';
            document.getElementById('view-mother_name').textContent = teacher.mother_name || 'N/A';
            document.getElementById('view-contact_number').textContent = teacher.contact_number || 'N/A';
            document.getElementById('view-address').textContent = teacher.address || 'N/A';
            document.getElementById('view-emergency_contact').textContent = teacher.emergency_contact || 'N/A';
            document.getElementById('view-age').textContent = teacher.age || 'N/A';
            document.getElementById('view-birthdate').textContent = teacher.birthdate ? new Date(teacher.birthdate).toLocaleDateString() : 'N/A';
            document.getElementById('view-profile_picture').src = teacher.profile_picture_url || '{{ asset('images/default-profile.png') }}';
        }

        // Function to open the View Modal
        function openViewModal() {
            var modal = document.getElementById('view-teacher-modal');
            modal.style.display = 'flex';
        }

        // Function to close the View Modal
        function closeViewModal() {
            var modal = document.getElementById('view-teacher-modal');
            modal.style.display = 'none';
        }

        // Attach event listener to the close button of the View Modal
        document.querySelector('#view-teacher-modal .close').addEventListener('click', closeViewModal);

        // Also close the modal when clicking outside the modal content
        window.addEventListener('click', function(event) {
            var modal = document.getElementById('view-teacher-modal');
            if (event.target == modal) {
                closeViewModal();
            }
        });

        // Allow closing the modal with the Esc key
        document.addEventListener('keydown', function(event) {
            var modal = document.getElementById('view-teacher-modal');
            if (event.key === 'Escape' && modal.style.display === 'flex') {
                closeViewModal();
            }
        });

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
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait while the teacher is being deleted.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    fetch(`/admin/teachers/${teacherId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close(); // Close the loading Swal
                        if (data.success) {
                            Swal.fire('Deleted!', data.message, 'success');
                            table.row(`#teacher-row-${teacherId}`).remove().draw(false);
                        } else {
                            Swal.fire('Error!', 'There was a problem deleting the teacher.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.close(); // Close the loading Swal
                        Swal.fire('Error!', 'There was a problem deleting the teacher.', 'error');
                    });
                }
            });
        }

        // Function to fetch and update the teachers table
        function fetchAndUpdateTeacherTable() {
            table.ajax.reload(null, false); // Reload DataTables data without resetting pagination
        }

        // Bulk Toggle Approval Function (Optional)
        function bulkToggleApproval() {
            var selectedIds = Array.from(document.querySelectorAll('.select-teacher:checked')).map(input => input.getAttribute('data-teacher-id'));

            if(selectedIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one teacher to toggle approval.',
                });
                return;
            }

            Swal.fire({
                title: 'Toggle Approval?',
                text: `Are you sure you want to toggle approval for ${selectedIds.length} teacher(s)?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#00d2ff',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, toggle them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while the approvals are being toggled.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    fetch('/admin/teachers/bulk-toggle-approval', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ ids: selectedIds })
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close(); // Close the loading Swal
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Toggled!',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            fetchAndUpdateTeacherTable(); // Re-fetch and update the table
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'Failed to toggle approvals.',
                                showConfirmButton: true,
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.close(); // Close the loading Swal
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'There was a problem toggling the approvals.',
                            showConfirmButton: true,
                        });
                    });
                }
            });
        }

        // Function to handle Edit button clicks
        function attachEditEvents() {
            document.querySelectorAll('.edit-button').forEach(button => {
                button.addEventListener('click', function() {
                    var teacherId = this.getAttribute('data-teacher-id');

                    // Show loading indicator
                    Swal.fire({
                        title: 'Fetching Data...',
                        text: 'Please wait while the teacher details are being fetched.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    // Fetch teacher data and open the modal
                    fetch(`/admin/teachers/${teacherId}`)
                        .then(response => response.json())
                        .then(data => {
                            Swal.close(); // Close the loading Swal
                            if (data.success) {
                                openEditModal(data.teacher); // Open the modal with the teacher data
                            } else {
                                Swal.fire('Error', data.message || 'Unable to fetch teacher data', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching teacher data:', error);
                            Swal.close(); // Close the loading Swal
                            Swal.fire('Error', 'Unable to fetch teacher data', 'error');
                        });
                });
            });
        }

        // Function to update teacher row in the table
        function updateTeacherRow(teacherId, teacher) {
            table.rows().every(function(rowIdx, tableLoop, rowLoop) {
                var rowData = this.data();
                if (rowData.id === teacherId) {
                    this.data({
                        id_number: teacher.id_number,
                        first_name: teacher.first_name,
                        last_name: teacher.last_name,
                        bed_or_hed: teacher.bed_or_hed,
                        course: teacher.course,
                        role: teacher.role,
                        approved: teacher.approved,
                        id: teacher.id
                    }).draw(false);
                }
            });
        }

        // Function to open the Edit Modal with teacher data
        function openEditModal(teacher) {
            document.getElementById('edit-teacher-id').value = teacher.id;
            document.getElementById('edit-id_number').value = teacher.id_number;
            document.getElementById('edit-first_name').value = teacher.first_name;
            document.getElementById('edit-last_name').value = teacher.last_name;
            document.getElementById('edit-bed_or_hed').value = teacher.bed_or_hed;
            document.getElementById('edit-course').value = teacher.course;
            document.getElementById('edit-role').value = teacher.role; // Set role

            // Trigger change event to set course and role visibility
            document.getElementById('edit-bed_or_hed').dispatchEvent(new Event('change'));

            // Display the modal
            var modal = document.getElementById('edit-teacher-modal');
            modal.style.display = 'flex';
        }

        // Function to handle View button clicks
        function attachViewEvents() {
            document.querySelectorAll('.view-button').forEach(button => {
                button.addEventListener('click', function() {
                    var teacherId = this.getAttribute('data-teacher-id');
                    
                    // Show loading indicator
                    Swal.fire({
                        title: 'Fetching Data...',
                        text: 'Please wait while the teacher details are being fetched.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    // Fetch teacher data from the server
                    fetch(`/admin/teachers/${teacherId}`)
                        .then(response => response.json())
                        .then(data => {
                            Swal.close(); // Close the loading Swal
                            if (data.success) {
                                populateViewModal(data.teacher);
                                openViewModal();
                            } else {
                                Swal.fire('Error', data.message || 'Unable to fetch teacher data', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching teacher data:', error);
                            Swal.close(); // Close the loading Swal
                            Swal.fire('Error', 'Unable to fetch teacher data', 'error');
                        });
                });
            });
        }

        // Function to populate the View Modal with teacher data
        function populateViewModal(teacher) {
            document.getElementById('view-id_number').textContent = teacher.id_number;
            document.getElementById('view-first_name').textContent = teacher.first_name;
            document.getElementById('view-last_name').textContent = teacher.last_name;
            document.getElementById('view-bed_or_hed').textContent = teacher.bed_or_hed === 'BED' ? 'Basic Education Department (BED)' : 'Higher Education Department (HED)';
            document.getElementById('view-course').textContent = teacher.course;
            document.getElementById('view-role').textContent = teacher.role === 'program_head' ? 'Program Head' : 'Teacher';
            document.getElementById('view-status').textContent = teacher.approved ? 'Active' : 'Inactive';
            document.getElementById('view-father_name').textContent = teacher.father_name || 'N/A';
            document.getElementById('view-mother_name').textContent = teacher.mother_name || 'N/A';
            document.getElementById('view-contact_number').textContent = teacher.contact_number || 'N/A';
            document.getElementById('view-address').textContent = teacher.address || 'N/A';
            document.getElementById('view-emergency_contact').textContent = teacher.emergency_contact || 'N/A';
            document.getElementById('view-age').textContent = teacher.age || 'N/A';
            document.getElementById('view-birthdate').textContent = teacher.birthdate ? new Date(teacher.birthdate).toLocaleDateString() : 'N/A';
            document.getElementById('view-profile_picture').src = teacher.profile_picture_url || '{{ asset('images/default-profile.png') }}';
        }

        // Function to open the View Modal
        function openViewModal() {
            var modal = document.getElementById('view-teacher-modal');
            modal.style.display = 'flex';
        }

        // Function to close the View Modal
        function closeViewModal() {
            var modal = document.getElementById('view-teacher-modal');
            modal.style.display = 'none';
        }

        // Attach event listener to the close button of the View Modal
        document.querySelector('#view-teacher-modal .close').addEventListener('click', closeViewModal);

        // Also close the modal when clicking outside the modal content
        window.addEventListener('click', function(event) {
            var modal = document.getElementById('view-teacher-modal');
            if (event.target == modal) {
                closeViewModal();
            }
        });

        // Allow closing the modal with the Esc key
        document.addEventListener('keydown', function(event) {
            var modal = document.getElementById('view-teacher-modal');
            if (event.key === 'Escape' && modal.style.display === 'flex') {
                closeViewModal();
            }
        });

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
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait while the teacher is being deleted.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    fetch(`/admin/teachers/${teacherId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close(); // Close the loading Swal
                        if (data.success) {
                            Swal.fire('Deleted!', data.message, 'success');
                            table.row(`#teacher-row-${teacherId}`).remove().draw(false);
                        } else {
                            Swal.fire('Error!', 'There was a problem deleting the teacher.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.close(); // Close the loading Swal
                        Swal.fire('Error!', 'There was a problem deleting the teacher.', 'error');
                    });
                }
            });
        }

        // Function to fetch and update the teachers table
        function fetchAndUpdateTeacherTable() {
            table.ajax.reload(null, false); // Reload DataTables data without resetting pagination
        }

        // Bulk Toggle Approval Function (Optional)
        function bulkToggleApproval() {
            var selectedIds = Array.from(document.querySelectorAll('.select-teacher:checked')).map(input => input.getAttribute('data-teacher-id'));

            if(selectedIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one teacher to toggle approval.',
                });
                return;
            }

            Swal.fire({
                title: 'Toggle Approval?',
                text: `Are you sure you want to toggle approval for ${selectedIds.length} teacher(s)?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#00d2ff',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, toggle them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while the approvals are being toggled.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    fetch('/admin/teachers/bulk-toggle-approval', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ ids: selectedIds })
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close(); // Close the loading Swal
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Toggled!',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            fetchAndUpdateTeacherTable(); // Re-fetch and update the table
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'Failed to toggle approvals.',
                                showConfirmButton: true,
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.close(); // Close the loading Swal
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'There was a problem toggling the approvals.',
                            showConfirmButton: true,
                        });
                    });
                }
            });
        }

        // Initial function calls
        attachToggleApprovalEvents();
        attachEditEvents();
        attachViewEvents();

        // Attach Toggle Approval Events
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

        // Attach Edit Button Events
        function attachEditEvents() {
            document.querySelectorAll('.edit-button').forEach(button => {
                button.addEventListener('click', function() {
                    var teacherId = this.getAttribute('data-teacher-id');

                    // Show loading indicator
                    Swal.fire({
                        title: 'Fetching Data...',
                        text: 'Please wait while the teacher details are being fetched.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    // Fetch teacher data and open the modal
                    fetch(`/admin/teachers/${teacherId}`)
                        .then(response => response.json())
                        .then(data => {
                            Swal.close(); // Close the loading Swal
                            if (data.success) {
                                openEditModal(data.teacher); // Open the modal with the teacher data
                            } else {
                                Swal.fire('Error', data.message || 'Unable to fetch teacher data', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching teacher data:', error);
                            Swal.close(); // Close the loading Swal
                            Swal.fire('Error', 'Unable to fetch teacher data', 'error');
                        });
                });
            });
        }
    });
</script>

<!-- View Teacher Modal -->
<div id="view-teacher-modal" class="modal" role="dialog" aria-labelledby="view-teacher-title" aria-modal="true">
    <div class="modal-content">
        <span class="close" aria-label="Close">&times;</span>
        <h2 id="view-teacher-title"><i class="fas fa-eye"></i> View Teacher</h2>
        <div id="view-teacher-details">
            <!-- Profile Picture -->
            <div class="profile-picture-container">
                <img id="view-profile_picture" src="" alt="Profile Picture">
            </div>
            <!-- Teacher Details -->
            <div class="details-container">
                <p><strong>ID Number:</strong> <span id="view-id_number"></span></p>
                <p><strong>First Name:</strong> <span id="view-first_name"></span></p>
                <p><strong>Last Name:</strong> <span id="view-last_name"></span></p>
                <p><strong>Department:</strong> <span id="view-bed_or_hed"></span></p>
                <p><strong>Course:</strong> <span id="view-course"></span></p>
                <p><strong>Role:</strong> <span id="view-role"></span></p>
                <p><strong>Status:</strong> <span id="view-status"></span></p>
                <p><strong>Father's Name:</strong> <span id="view-father_name"></span></p>
                <p><strong>Mother's Name:</strong> <span id="view-mother_name"></span></p>
                <p><strong>Contact Number:</strong> <span id="view-contact_number"></span></p>
                <p><strong>Address:</strong> <span id="view-address"></span></p>
                <p><strong>Emergency Contact:</strong> <span id="view-emergency_contact"></span></p>
                <p><strong>Age:</strong> <span id="view-age"></span></p>
                <p><strong>Birthdate:</strong> <span id="view-birthdate"></span></p>
            </div>
        </div>
    </div>
</div>


</x-app-layout>
