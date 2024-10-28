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
                <div class="form-wrapper">
                    <h2><i class="fas fa-user-plus"></i> Add Late Staff</h2>
                    <form id="late-staff-form">
                        @csrf
                        <label for="late-id_number">ID Number</label>
                        <input type="text" id="late-id_number" name="late-id_number" required maxlength="7" pattern="[A-Za-z][0-9]{6}" title="ID number must start with a letter followed by 6 digits.">
                        
                        <label for="late-first_name">First Name</label>
                        <input type="text" id="late-first_name" name="late-first_name" required>
                        
                        <label for="late-last_name">Last Name</label>
                        <input type="text" id="late-last_name" name="late-last_name" required>
                        
                        <label for="late-department">Department</label>
                        <input type="text" id="late-department" name="late-department" required>
                        
                        <button type="submit" class="preview-button"><i class="fas fa-user-plus"></i> Add Staff</button>
                    </form>
                </div>
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
            <div id="edit-staff-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Edit Staff</h2>
                    <form id="edit-staff-form">
                        @csrf
                        <input type="hidden" name="id" id="edit-staff-id">
                        
                        <label for="edit-id-number">ID Number</label>
                        <input type="text" name="id_number" id="edit-id-number" required>
                        
                        <label for="edit-first-name">First Name</label>
                        <input type="text" name="first_name" id="edit-first-name" required>
                        
                        <label for="edit-last-name">Last Name</label>
                        <input type="text" name="last_name" id="edit-last-name" required>
                        
                        <label for="edit-department">Department</label>
                        <input type="text" name="department" id="edit-department" required>
                        
                        <button type="submit" class="save-button"><i class="fas fa-save"></i> Save</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

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

            // Function to open the modal and populate it with staff data
            function openEditModal(staff) {
                document.getElementById('edit-staff-id').value = staff.id;
                document.getElementById('edit-id-number').value = staff.id_number;
                document.getElementById('edit-first-name').value = staff.first_name;
                document.getElementById('edit-last-name').value = staff.last_name;
                document.getElementById('edit-department').value = staff.department;

                // Display the modal
                document.getElementById('edit-staff-modal').style.display = 'flex';
            }

            // Close the modal when clicking the 'X' button
            document.querySelectorAll('.close').forEach(closeBtn => {
                closeBtn.addEventListener('click', function() {
                    this.parentElement.parentElement.style.display = 'none';
                });
            });

            // Close the modal when clicking outside the modal content
            window.onclick = function(event) {
                const modals = document.querySelectorAll('.modal');
                modals.forEach(modal => {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                });
            }

            // Global deleteStaff function to be available on button click
            function deleteStaff(staffId) {
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
                            if (data.success) {
                                Swal.fire('Deleted!', data.message, 'success');
                                document.getElementById('staff-row-' + staffId).remove();
                            } else {
                                Swal.fire('Error!', 'There was a problem deleting the staff.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error!', 'There was a problem deleting the staff.', 'error');
                        });
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Initialize modal
                $('#staff-table').DataTable({
                "pageLength": 10, // Set default page length
                "searching": true, // Enable search
                "ordering": true,  // Enable column ordering
                "lengthChange": true // Enable changing the number of rows displayed
            });
                document.getElementById('edit-staff-modal').style.display = 'none';

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

                // Add late staff form submission
                document.getElementById('late-staff-form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    var formData = new FormData(this);

                    fetch('{{ route('admin.staff.add') }}', {
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
                            document.getElementById('late-staff-form').reset();
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
                            text: 'There was a problem adding the staff member.',
                            showConfirmButton: true,
                        });
                    });
                });

                // Fetch updated staff table
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
                                <button class="preview-button edit-button" data-staff-id="${staffMember.id}"><i class="fas fa-edit"></i> Edit</button>
                                <button class="delete-button" onclick="deleteStaff(${staffMember.id})"><i class="fas fa-trash-alt"></i> Delete</button>
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
                            var staffId = this.getAttribute('data-staff-id');
                            var approved = this.checked ? 1 : 0;

                            var formData = new FormData();
                            formData.append('approved', approved);

                            var actionUrl = `/admin/staff/${staffId}/toggle-approval`;

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

                    var button = row.querySelector('.status-button');
                    var checkbox = row.querySelector(`input[data-staff-id="${staffId}"]`);

                    // Update button text and background color
                    if (staff.approved == 1) {
                        button.textContent = 'Active';
                        button.style.backgroundColor = '#28a745';
                    } else {
                        button.textContent = 'Inactive';
                        button.style.backgroundColor = '#dc3545';
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
                                .then(staff => {
                                    openEditModal(staff); // Open the modal with the staff data
                                })
                                .catch(error => {
                                    console.error('Error fetching staff data:', error);
                                    Swal.fire('Error', 'Unable to fetch staff data', 'error');
                                });
                        });
                    });
                }

                // Form submission inside the modal
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
                            document.getElementById('edit-staff-modal').style.display = 'none'; // Close the modal
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

                // Initial fetch of staff
                fetchAndUpdateStaffTable();
            });
        </script>
    </div>
</x-app-layout>
