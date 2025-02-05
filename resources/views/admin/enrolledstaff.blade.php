<x-app-layout :pageTitle="'Manage Staffs'">   
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
            justify-content: center; /* Changed from space-between to center */
            margin-top: 30px;
            margin-bottom: 40px;
        }

        .form-wrapper {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex: 1 1 100%; /* Allow full width on smaller screens */
            max-width: 500px; /* Set a maximum width for larger screens */
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
        .edit-button,
        .view-button {
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

        .view-button {
            background-color: #17a2b8;
            width: 100%;
            max-width: 150px;
        }

        .view-button:hover {
            background-color: #138496;
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

        /* Staff Table */
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
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center; /* Center horizontally */
            align-items: center;     /* Center vertically */
            padding: 20px;
            box-sizing: border-box;
        }

        .modal.active {
            display: flex; /* Activate Flexbox */
        }

        /* Modal Content Styles */
        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 600px;
            animation: slideIn 0.5s ease-out;
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
                align-items: center; /* Center forms vertically */
            }

            .form-wrapper {
                max-width: 90%; /* Increase max-width on smaller screens */
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <div class="main-content">
        <!-- Tabs -->
        <div class="tabs">
            <div class="tab active" data-tab="upload-tab">
                <i class="fas fa-upload"></i>
                Upload Staff List
            </div>
            <div class="tab" data-tab="staff-tab">
                <i class="fas fa-users"></i>
                View Staff
            </div>
        </div>

        <!-- Upload Staff List Tab Content -->
        <div id="upload-tab" class="tab-content active">
            <div class="forms-container">
                <!-- Upload Staff List Form -->
                <div class="form-wrapper">
                    <h2><i class="fas fa-file-upload"></i> Upload Staff List</h2>
                    <p>Please ensure the Excel file follows the format: ID Number, First Name, Last Name, Department</p>
                    <a href="{{ route('admin.download.staffs') }}" class="download-template-button">
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

                <!-- Add Late Staff Form -->
                <!-- If you have an additional form for adding late staff, include it here -->
            </div>
        </div>

        <!-- View Staff Tab Content -->
        <div id="staff-tab" class="tab-content">
            <div class="staff-section">
                <h2><i class="fas fa-users"></i> Enrolled Staff</h2>
               
                @if($staff->isEmpty())
                    <p>No staff enrolled yet.</p>
                @else
                   
                    <table class="staff-table" id="staff-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-id-card"></i> ID</th>
                                <th><i class="fas fa-user"></i> First Name</th>
                                <th><i class="fas fa-user"></i> Last Name</th>
                                <th><i class="fas fa-building"></i> Position</th> <!-- New Department Column -->
                                <th><i class="fas fa-info-circle"></i> Status</th>
                                <th><i class="fas fa-toggle-on"></i> Toggle Status</th>
                                <th><i class="fas fa-tools"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody id="staff-table-body">
                            @foreach($staff as $s)
                                <tr id="staff-row-{{ $s->id }}">
                                    <td>{{ $s->id_number }}</td>
                                    <td>{{ $s->first_name }}</td>
                                    <td>{{ $s->last_name }}</td>
                                    <td>{{ $s->position }}</td> <!-- Display Department -->
                                    <td>
                                        <button class="preview-button status-button" style="background-color: {{ $s->approved ? '#28a745' : '#dc3545' }};">
                                            {{ $s->approved ? 'Active' : 'Inactive' }}
                                        </button>
                                    </td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" class="toggle-approval" data-staff-id="{{ $s->id }}" {{ $s->approved ? 'checked' : '' }}>
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <button class="preview-button view-button" data-staff-id="{{ $s->id }}">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        <button class="preview-button edit-button" data-staff-id="{{ $s->id }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="delete-button" onclick="deleteStaff({{ $s->id }})">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Edit Staff Modal -->
            <div id="edit-staff-modal" class="modal" role="dialog" aria-labelledby="edit-staff-title" aria-modal="true">
                <div class="modal-content">
                    <span class="close" aria-label="Close">&times;</span>
                    <h2 id="edit-staff-title">Edit Staff</h2>
                    <form id="edit-staff-form">
                        @csrf
                        @method('PUT') <!-- Use PUT method for updates -->
                        <input type="hidden" name="id" id="edit-staff-id">
                        
                        <label for="edit-id-number">ID Number</label>
                        <input type="text" name="id_number" id="edit-id-number" required maxlength="10" pattern="[A-Za-z][0-9]{6,9}" title="ID number must start with a letter followed by 6-9 digits.">
                        
                        <label for="edit-first-name">First Name</label>
                        <input type="text" name="first_name" id="edit-first-name" required>
                        
                        <label for="edit-last-name">Last Name</label>
                        <input type="text" name="last_name" id="edit-last-name" required>
                        
                        <label for="edit-position">Position</label>
                        <input type="text" name="position" id="edit-position" required>
                        
                        
                        <button type="submit" class="save-button"><i class="fas fa-save"></i> Save</button>
                    </form>
                </div>
            </div>

            <!-- View Staff Modal -->
            <div id="view-staff-modal" class="modal" role="dialog" aria-labelledby="view-staff-title" aria-modal="true">
                <div class="modal-content">
                    <span class="close" aria-label="Close">&times;</span>
                    <h2 id="view-staff-title">View Staff Details</h2>
                    <div id="view-staff-details">
                        <!-- Staff details will be dynamically inserted here -->
                        <p><strong>ID Number:</strong> <span id="view-id-number"></span></p>
                        <p><strong>First Name:</strong> <span id="view-first-name"></span></p>
                        <p><strong>Last Name:</strong> <span id="view-last-name"></span></p>
                        <p><strong>Position:</strong> <span id="view-position"></span></p>
                        <p><strong>Father's Name:</strong> <span id="view-father-name"></span></p>
                        <p><strong>Mother's Name:</strong> <span id="view-mother-name"></span></p>
                        <p><strong>Contact Number:</strong> <span id="view-contact-number"></span></p>
                        <p><strong>Address:</strong> <span id="view-address"></span></p>
                        <p><strong>Birthdate:</strong> <span id="view-birthdate"></span></p>
                        <p><strong>Emergency Contact:</strong> <span id="view-emergency-contact"></span></p>
                        <p><strong>Age:</strong> <span id="view-age"></span></p>
                        <p><strong>Status:</strong> <span id="view-status"></span></p>
                        <!-- Add more fields as necessary -->
                    </div>
                </div>
            </div>
        </div>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

        <!-- Font Awesome -->
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize DataTable
                var staffTable = $('#staff-table').DataTable({
                    "pageLength": 10, // Set default page length
                    "searching": true, // Enable search
                    "ordering": true,  // Enable column ordering
                    "lengthChange": true, // Enable changing the number of rows displayed
                    "responsive": true, // Enable responsive layout
                    "language": {
                        "search": "Search Staff:"
                    }
                });

                // Handle Tab Switching
                document.querySelectorAll('.tab').forEach(tab => {
                    tab.addEventListener('click', function() {
                        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
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

                    Swal.fire({
                        title: 'Uploading...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    fetch('{{ route('admin.staff.import') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            fetchAndUpdateStaffTable(); // Re-fetch and update the table
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

                // Delete staff function
                window.deleteStaff = function(staffId) {
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
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading()
                                }
                            });

                            fetch(`/admin/staff/${staffId}`, {
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
                                Swal.close();
                                if (data.success) {
                                    Swal.fire('Deleted!', data.message, 'success');
                                    staffTable.row('#staff-row-' + staffId).remove().draw();
                                } else {
                                    Swal.fire('Error!', 'There was a problem deleting the staff.', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.close();
                                Swal.fire('Error!', 'There was a problem deleting the staff.', 'error');
                            });
                        }
                    });
                }

                // Function to open the Edit Staff modal and populate it with staff data
                function openEditModal(staff) {
                    document.getElementById('edit-staff-id').value = staff.id;
                    document.getElementById('edit-id-number').value = staff.id_number;
                    document.getElementById('edit-first-name').value = staff.first_name;
                    document.getElementById('edit-last-name').value = staff.last_name;
                    document.getElementById('edit-position').value = staff.position;

                    // Show the modal by adding 'active' class
                    document.getElementById('edit-staff-modal').classList.add('active');
                }

                // Function to open the View Staff modal and populate it with staff data
                function openViewModal(staff) {
                    document.getElementById('view-id-number').textContent = staff.id_number;
                    document.getElementById('view-first-name').textContent = staff.first_name;
                    document.getElementById('view-last-name').textContent = staff.last_name;
                    document.getElementById('view-position').textContent = staff.position;
                    document.getElementById('view-father-name').textContent = staff.father_name || 'N/A';
                    document.getElementById('view-mother-name').textContent = staff.mother_name || 'N/A';
                    document.getElementById('view-contact-number').textContent = staff.contact_number || 'N/A';
                    document.getElementById('view-address').textContent = staff.address || 'N/A';
                    document.getElementById('view-birthdate').textContent = staff.birthdate || 'N/A';
                    document.getElementById('view-emergency-contact').textContent = staff.emergency_contact || 'N/A';
                    document.getElementById('view-age').textContent = staff.age || 'N/A';
                    document.getElementById('view-status').textContent = staff.approved ? 'Active' : 'Inactive';
                    
                    // Show the modal by adding 'active' class
                    document.getElementById('view-staff-modal').classList.add('active');
                }

                // Close the Edit and View modals when clicking the 'X' button
                document.querySelectorAll('.modal .close').forEach(closeBtn => {
                    closeBtn.addEventListener('click', function() {
                        this.parentElement.parentElement.classList.remove('active');
                    });
                });

                // Close the modals when clicking outside the modal content
                window.onclick = function(event) {
                    const modals = document.querySelectorAll('.modal');
                    modals.forEach(modal => {
                        if (event.target == modal) {
                            modal.classList.remove('active');
                        }
                    });
                }

                // Fetch and update staff table
                function fetchAndUpdateStaffTable() {
                    fetch('{{ route('admin.staff.enrolled') }}', {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(staff => {
                        updateStaffTable(staff);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Unable to fetch staff data.', 'error');
                    });
                }

                // Update staff table
                function updateStaffTable(staff) {
                    var tbody = document.getElementById('staff-table-body');
                    if (!tbody) {
                        console.error("Element with ID 'staff-table-body' not found.");
                        return;
                    }
                    tbody.innerHTML = '';
                    staff.forEach(staffMember => {
                        var row = document.createElement('tr');
                        row.id = 'staff-row-' + staffMember.id;

                        row.innerHTML = `
                            <td>${staffMember.id_number}</td>
                            <td>${staffMember.first_name}</td>
                            <td>${staffMember.last_name}</td>
                            <td>${staffMember.position}</td>
                            <td>
                                <button class="preview-button status-button" style="background-color: ${staffMember.approved ? '#28a745' : '#dc3545'};">
                                    ${staffMember.approved ? 'Active' : 'Inactive'}
                                </button>
                            </td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox" class="toggle-approval" data-staff-id="${staffMember.id}" ${staffMember.approved ? 'checked' : ''}>
                                    <span class="slider"></span>
                                </label>
                            </td>
                            <td>
                                <button class="preview-button view-button" data-staff-id="${staffMember.id}">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="preview-button edit-button" data-staff-id="${staffMember.id}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="delete-button" onclick="deleteStaff(${staffMember.id})">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                    // Re-initialize DataTable to recognize new rows
                    staffTable.destroy();
                    staffTable = $('#staff-table').DataTable({
                        "pageLength": 10,
                        "searching": true,
                        "ordering": true,
                        "lengthChange": true,
                        "responsive": true,
                        "language": {
                            "search": "Filter records:"
                        }
                    });
                    attachToggleApprovalEvents();
                    attachEditEvents(); 
                    attachViewEvents();
                }

                // Toggle approval events
                function attachToggleApprovalEvents() {
                    document.querySelectorAll('.toggle-approval').forEach(input => {
                        input.addEventListener('change', function() {
                            var staffId = this.getAttribute('data-staff-id');
                            var approved = this.checked ? 1 : 0;

                            var formData = new FormData();
                            formData.append('approved', approved);

                            var actionUrl = `/admin/staff/${staffId}/toggle-approval`;

                            Swal.fire({
                                title: 'Updating Approval Status...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading()
                                }
                            });

                            fetch(actionUrl, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                Swal.close();
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: data.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    updateStaffRow(staffId, data.staff);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'There was a problem updating the staff status.',
                                        showConfirmButton: true,
                                    });
                                    // Revert the checkbox state
                                    this.checked = !approved;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.close();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'There was a problem updating the staff status.',
                                    showConfirmButton: true,
                                });
                                // Revert the checkbox state
                                this.checked = !approved;
                            });
                        });
                    });
                }

                // Update staff row
                function updateStaffRow(staffId, staff) {
                    var row = document.getElementById('staff-row-' + staffId);
                    if (!row) {
                        console.error(`Row for staffId ${staffId} not found`);
                        return;
                    }

                    var statusButton = row.querySelector('.status-button');
                    var checkbox = row.querySelector(`input[data-staff-id="${staffId}"]`);

                    // Update status button text and color
                    if (staff.approved == 1) {
                        statusButton.textContent = 'Active';
                        statusButton.style.backgroundColor = '#28a745';
                    } else {
                        statusButton.textContent = 'Inactive';
                        statusButton.style.backgroundColor = '#dc3545';
                    }

                    // Update checkbox state
                    checkbox.checked = staff.approved == 1;
                }

                // Edit button events
                function attachEditEvents() {
                    document.querySelectorAll('.edit-button').forEach(button => {
                        button.addEventListener('click', function() {
                            var staffId = this.getAttribute('data-staff-id');

                            // Fetch staff data and open the modal
                            fetch(`/admin/staff/${staffId}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.staff) {
                                        openEditModal(data.staff);
                                    } else {
                                        Swal.fire('Error', 'Staff data not found.', 'error');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error fetching staff data:', error);
                                    Swal.fire('Error', 'Unable to fetch staff data', 'error');
                                });
                        });
                    });
                }

                // View button events
                function attachViewEvents() {
                    document.querySelectorAll('.view-button').forEach(button => {
                        button.addEventListener('click', function() {
                            var staffId = this.getAttribute('data-staff-id');

                            // Fetch staff data and open the modal
                            fetch(`/admin/staff/${staffId}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.staff) {
                                        openViewModal(data.staff);
                                    } else {
                                        Swal.fire('Error', 'Staff data not found.', 'error');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error fetching staff data:', error);
                                    Swal.fire('Error', 'Unable to fetch staff data', 'error');
                                });
                        });
                    });
                }

                // Form submission inside the Edit modal
                document.getElementById('edit-staff-form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    var formData = new FormData(this);
                    var staffId = document.getElementById('edit-staff-id').value;

                    fetch(`/admin/staff/${staffId}/edit`, {
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
                            fetchAndUpdateStaffTable(); // Re-fetch and update the table
                            document.getElementById('edit-staff-form').reset();
                            document.getElementById('edit-staff-modal').classList.remove('active'); // Close the modal
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
                            text: 'There was a problem updating the staff.',
                            showConfirmButton: true,
                        });
                    });
                });

                // Initialize all event attachments
                attachToggleApprovalEvents();
                attachEditEvents();
                attachViewEvents();
            });

            // Function to open the View Staff modal and populate it with staff data
            function openViewModal(staff) {
                document.getElementById('view-id-number').textContent = staff.id_number;
                document.getElementById('view-first-name').textContent = staff.first_name;
                document.getElementById('view-last-name').textContent = staff.last_name;
                document.getElementById('view-position').textContent = staff.position;
                document.getElementById('view-father-name').textContent = staff.father_name || 'N/A';
                document.getElementById('view-mother-name').textContent = staff.mother_name || 'N/A';
                document.getElementById('view-contact-number').textContent = staff.contact_number || 'N/A';
                document.getElementById('view-address').textContent = staff.address || 'N/A';
                document.getElementById('view-birthdate').textContent = staff.birthdate || 'N/A';
                document.getElementById('view-emergency-contact').textContent = staff.emergency_contact || 'N/A';
                document.getElementById('view-age').textContent = staff.age || 'N/A';
                document.getElementById('view-status').textContent = staff.approved ? 'Active' : 'Inactive';
                
                // Show the modal by adding 'active' class
                document.getElementById('view-staff-modal').classList.add('active');
            }

            // Close the View Staff modal when clicking the 'X' button
            document.querySelectorAll('#view-staff-modal .close').forEach(closeBtn => {
                closeBtn.addEventListener('click', function() {
                    document.getElementById('view-staff-modal').classList.remove('active');
                });
            });

            // Close the View Staff modal when clicking outside the modal content
            window.onclick = function(event) {
                const modals = document.querySelectorAll('.modal');
                modals.forEach(modal => {
                    if (event.target == modal) {
                        modal.classList.remove('active');
                    }
                });
            }

            // Function to open the Edit Staff modal and populate it with staff data
            function openEditModal(staff) {
                document.getElementById('edit-staff-id').value = staff.id;
                document.getElementById('edit-id-number').value = staff.id_number;
                document.getElementById('edit-first-name').value = staff.first_name;
                document.getElementById('edit-last-name').value = staff.last_name;
                document.getElementById('edit-position').value = staff.position;

                // Show the modal by adding 'active' class
                document.getElementById('edit-staff-modal').classList.add('active');
            }
        </script>

        <!-- View Staff Modal -->
        <div id="view-staff-modal" class="modal" role="dialog" aria-labelledby="view-staff-title" aria-modal="true">
            <div class="modal-content">
                <span class="close" aria-label="Close">&times;</span>
                <h2 id="view-staff-title">View Staff Details</h2>
                <div id="view-staff-details">
                    <!-- Staff details will be dynamically inserted here -->
                    <p><strong>ID Number:</strong> <span id="view-id-number"></span></p>
                    <p><strong>First Name:</strong> <span id="view-first-name"></span></p>
                    <p><strong>Last Name:</strong> <span id="view-last-name"></span></p>
                    <p><strong>Position:</strong> <span id="view-position"></span></p>
                    <p><strong>Father's Name:</strong> <span id="view-father-name"></span></p>
                    <p><strong>Mother's Name:</strong> <span id="view-mother-name"></span></p>
                    <p><strong>Contact Number:</strong> <span id="view-contact-number"></span></p>
                    <p><strong>Address:</strong> <span id="view-address"></span></p>
                    <p><strong>Birthdate:</strong> <span id="view-birthdate"></span></p>
                    <p><strong>Emergency Contact:</strong> <span id="view-emergency-contact"></span></p>
                    <p><strong>Age:</strong> <span id="view-age"></span></p>
                    <p><strong>Status:</strong> <span id="view-status"></span></p>
                    <!-- Add more fields as necessary -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
