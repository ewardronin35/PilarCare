<x-app-layout :pageTitle="' Approval Health Examination'">   
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS Links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.9/css/fixedHeader.dataTables.min.css">
    
    <!-- JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Ensure jQuery is loaded first -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.9/js/dataTables.fixedHeader.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* General Styling */
        body {
            background-color: #f5f7fa;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        .main-content {
            margin-top: 30px;
        }

        /* Search Bar Styling */
        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-bar input {
            width: 300px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px 0 0 5px;
            outline: none;
            font-size: 1rem;
        }

        .search-bar button {
            padding: 10px 15px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 1rem;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }

        /* Tab Navigation Styling */
        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
            flex-wrap: wrap;
            gap: 10px;
        }

        .tab-btn {
            background: none;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            color: #555;
            transition: color 0.3s, border-bottom 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tab-btn:hover {
            color: #007bff;
        }

        .tab-btn.active {
            color: #007bff;
            border-bottom: 3px solid #007bff;
        }

        /* Table Container */
        .table-container {
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            background-color: #fff;
        }
/* Table Image Styling */
.table-image {
    width: 100px;        /* Set desired width */
    height: 100px;        /* Height adjusts to maintain aspect ratio */
    object-fit: cover;   /* Adjust image to cover the container */
    border-radius: 5px;
    cursor: pointer;
    margin: 0 5px 5px 0;
    transition: transform 0.3s;
}

.table-image:hover {
    transform: scale(1.05);
}

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px; /* Ensure table has a minimum width */
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: center;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: 600;
            color: #333;
         
        }

        table tbody tr:hover {
            background-color: #f9f9f9;
            cursor: pointer;
        }

        /* Action Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s, transform 0.3s;
            color: white;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }

        /* Modal Styling */
     /* Modal Styling */
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    animation: fadeIn 0.3s ease-in-out;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: #fefefe;
    padding: 20px;
    border-radius: 10px;
    animation: slideIn 0.3s ease-in-out;
    position: relative;
    max-width: 90%;
    max-height: 90%;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.8rem;
    color: #007bff;
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
}

.modal-body {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-body img {
    max-width: 50%;
    max-height: 50%;
    width: auto;
    height: auto;
    border-radius: 5px;
}


        /* Image Previews */
        .image-previews img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 5px 5px 0;
            transition: transform 0.3s;
        }

        .image-previews img:hover {
            transform: scale(1.05);
        }

        /* Spinner Overlay */
        #spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #007bff;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Animations */
        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        table thead th {
    position: sticky;
    top: 0;
    z-index: 2;
    background-color: #f2f2f2; /* Matches the header background */
    color: #333;
}
table thead th { 
            position: sticky; top: 0; z-index: 2;
            background-color: #f2f2f2; /* Background for sticky header */
        }
        /* Responsive Design */
        @media (max-width: 768px) {
            .tabs {
                flex-direction: column;
                align-items: center;
            }

            .tab-btn {
                width: 100%;
                text-align: center;
                border-bottom: 1px solid #ddd;
            }

            .tab-btn:last-child {
                border-bottom: none;
            }

            .table-container {
                max-height: 300px;
            }

            table {
                min-width: 600px;
            }

            .image-previews img {
                width: 80px;
                height: 80px;
            }

            .btn {
                padding: 6px 10px;
                font-size: 0.8rem;
            }

            .search-bar input {
                width: 200px;
            }
        }

        /* School Year Reset Styles */
        .school-year-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px 0;
        }

        .school-year-container select {
            padding: 10px;
            font-size: 16px;
            margin-bottom: 10px;
            width: 200px;
        }

        .reset-button {
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
        }

        .reset-button:hover {
            background-color: #c82333;
        }
        /* Button Styles */
.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0069d9;
    border-color: #0062cc;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 8px 16px;
    font-size: 0.9rem;
    border-radius: 5px;
    color: #fff;
    text-decoration: none;
    margin: 5px;
    transition: background-color 0.3s, transform 0.3s;
}

.btn:hover {
    transform: scale(1.05);
}
/* Button Group */
.button-group {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .button-group {
        flex-direction: column;
        align-items: stretch;
    }

    .btn {
        width: 100%;
        text-align: center;
    }
}
    </style>
    <div class="main-content">
        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tab Navigation -->
        <div class="tabs">
            <button class="tab-btn active" data-role="pending-approvals" onclick="switchTab('pending-approvals')">
                <i class="fas fa-file-medical"></i> Pending Approvals
            </button>
            <button class="tab-btn" data-role="school-year-reset" onclick="switchTab('school-year-reset')">
                <i class="fas fa-calendar-alt"></i> School Year Reset
            </button>
            <button class="tab-btn" data-role="reminders" onclick="switchTab('reminders')">
                <i class="fas fa-bell"></i> Reminders
            </button>
        </div>

        <!-- Pending Approvals Tab Content -->
        <div class="table-container tab-content" id="pending-approvals">
        <div class="button-group" style="margin-bottom: 10px;">
        <button type="button" class="btn btn-success" id="bulk-approve-btn">
            <i class="fas fa-check"></i> Bulk Approve
        </button>
        <button type="button" class="btn btn-danger" id="bulk-reject-btn">
            <i class="fas fa-times"></i> Bulk Reject
        </button>
    </div>
            <table id="health-examinations-table" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                    <th><input type="checkbox" id="select-all-checkbox"></th>
                        <th>Patient Name</th>
                        <th>School Year</th>
                        <th>Health Exam Pictures</th>
                        <th>X-ray Pictures</th>
                        <th>Lab Result Pictures</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded via AJAX -->
                </tbody>
            </table>
        </div>

        <!-- School Year Reset Tab Content -->
        <div class="school-year-container tab-content" id="school-year-reset" style="display: none;">
            <h2>Select School Year to Reset Data</h2>
            <select id="school-year-select">
                @foreach($schoolYears as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>

            <button type="button" class="reset-button" id="reset-school-year">Reset School Year</button>
        </div>
    </div>
    <div class="table-container tab-content" id="reminders" style="display: none;">
    <h2>Reminders for Incomplete Health Examinations</h2>
    <div class="button-group">
        <button type="button" class="btn btn-primary" id="send-reminder-btn">
            <i class="fas fa-paper-plane"></i> Send Reminder
        </button>
        <button type="button" class="btn btn-secondary" id="select-all-btn">
            <i class="fas fa-check-square"></i> Select All
        </button>
    </div>
    <table id="reminders-table" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all-checkbox"></th>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Grade/Course</th>
                <th>Section</th>
                <th>Pending Since</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded via AJAX -->
        </tbody>
    </table>
</div>
    <!-- Image Preview Modal -->
    <div id="image-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Image Preview</h2>
                <span class="close" onclick="closeImageModal()">&times;</span>
            </div>
            <div class="modal-body">
                <img id="modal-image" src="" alt="Image Preview">
            </div>
        </div>
    </div>

    <!-- Spinner Overlay -->
    <div id="spinner-overlay">
        <div class="spinner"></div>
    </div>

    <!-- Your JavaScript -->
    <script>
        // CSRF Token Setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Function to open image modal
        function openImageModal(src) {
            const modal = document.getElementById('image-modal');
            const modalImg = document.getElementById('modal-image');
            modalImg.src = src;
            modal.style.display = 'flex';
        }

        // Function to close image modal
        function closeImageModal() {
            const modal = document.getElementById('image-modal');
            modal.style.display = 'none';
        }

        // Function to approve a health examination
        function approveExamination(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to approve this health examination!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    showSpinner();
                    fetch(`/nurse/health-examination/${id}/approve`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideSpinner();
                        if (data.success) {
                            Swal.fire(
                                'Approved!',
                                data.message || 'The examination has been approved.',
                                'success'
                            ).then(() => {
                                // Reload DataTable to reflect changes
                                $('#health-examinations-table').DataTable().ajax.reload(null, false);
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'The examination could not be approved.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        hideSpinner();
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'There was a problem with the approval process.',
                            'error'
                        );
                    });
                }
            });
        }

        // Function to reject a health examination
        function rejectExamination(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to reject this health examination!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    showSpinner();
                    fetch(`/nurse/health-examination/${id}/reject`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideSpinner();
                        if (data.success) {
                            Swal.fire(
                                'Rejected!',
                                data.message || 'The examination has been rejected.',
                                'success'
                            ).then(() => {
                                // Reload DataTable to reflect changes
                                $('#health-examinations-table').DataTable().ajax.reload(null, false);
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'The examination could not be rejected.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        hideSpinner();
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'There was a problem with the rejection process.',
                            'error'
                        );
                    });
                }
            });
        }

        // Function to switch tabs
        function switchTab(tabId) {
    showSpinner(); // Show spinner when switching tabs

    // Remove 'active' class from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => button.classList.remove('active'));

    // Add 'active' class to the clicked tab
    const activeTab = document.querySelector(`.tab-btn[data-role="${tabId}"]`);
    if (activeTab) {
        activeTab.classList.add('active');
    }

    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => content.style.display = 'none');

    // Show the selected tab content
    const activeContent = document.getElementById(tabId);
    if (activeContent) {
        activeContent.style.display = (tabId === 'school-year-reset') ? 'flex' : 'block';
        hideSpinner(); // Hide spinner after content is displayed

        // Initialize Reminders DataTable if not already initialized
        if (tabId === 'reminders' && !$('#reminders-table').hasClass('dataTable')) {
            initializeRemindersTable();
        }
    } else {
        hideSpinner(); // Hide spinner if content not found
    }

    // Optionally, refresh data based on the active tab
    if (tabId === 'pending-approvals') {
        $('#health-examinations-table').DataTable().ajax.reload(null, false); // Reload DataTable via AJAX
    }
}

function initializeRemindersTable() {
    $('#reminders-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("nurse.healthExaminations.remindersData") }}',
            type: 'GET',
            dataSrc: function(json) {
                if (json.error) {
                    Swal.fire('Error!', json.error, 'error');
                    return [];
                }
                return json.data;
            },
            error: function (xhr, error, thrown) {
                hideSpinner();
                console.error('Error fetching reminders data:', xhr.responseText);
                Swal.fire(
                    'Error!',
                    'Failed to load reminders data. Please try again later.',
                    'error'
                );
            }
        },
        columns: [
            { 
                data: 'id',
                name: 'id',
                render: function(data) {
                    return `<input type="checkbox" class="select-student" data-student-id="${data}">`;
                },
                orderable: false,
                searchable: false
            },
            { data: 'id_number', name: 'id_number' },
            { data: 'student_name', name: 'student_name' },
            { data: 'grade_or_course', name: 'grade_or_course' },
            { data: 'section', name: 'section' },
            { data: 'pending_since', name: 'pending_since' },
        ],
        order: [[1, 'asc']],
        language: {
            emptyTable: "No students pending health examinations."
        }
    });
}


        // Spinner Functions
        function showSpinner() {
            document.getElementById('spinner-overlay').style.display = 'flex';
        }

        function hideSpinner() {
            document.getElementById('spinner-overlay').style.display = 'none';
        }

        // Initialize DataTables with AJAX
        $(document).ready(function () {
            const healthExaminationsTable = $('#health-examinations-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/nurse/health-examinations/pending-data',
                    type: 'GET',
                    data: function (d) {
                        // You can add additional parameters here if needed
                        // Example: d.searchQuery = $('#search-input').val();
                    },
                    error: function (xhr, error, thrown) {
                        hideSpinner();
                        console.error('Error fetching pending examinations:', xhr.responseText);
                        Swal.fire(
                            'Error!',
                            'Failed to load pending examinations. Please try again later.',
                            'error'
                        );
                    }
                },
                columns: [
                    { 
                data: 'id',
                name: 'id',
                render: function(data) {
                    return `<input type="checkbox" class="select-exam" data-exam-id="${data}">`;
                },
                orderable: false,
                searchable: false
            },
                    { data: 'user_name', name: 'user_name' },
                    { data: 'school_year', name: 'school_year' },
                    {
                        data: 'health_examination_pictures',
                        name: 'health_examination_pictures',
                        render: function(data) {
    if (data.length > 0) {
        return data.map(pic => `<img src="${pic}" alt="Health Examination Picture" onclick="openImageModal('${pic}')" class="table-image">`).join('');
    }
    return 'No Health Examination pictures uploaded.';
},

                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'xray_pictures',
                        name: 'xray_pictures',
                        render: function(data) {
                            if (data.length > 0) {
                                return data.map(pic => `<img src="${pic}" alt="X-ray Picture" onclick="openImageModal('${pic}')" class="table-image">`).join('');
                            }
                            return 'No X-ray pictures uploaded.';
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'lab_result_pictures',
                        name: 'lab_result_pictures',
                        render: function(data) {
                            if (data.length > 0) {
                                return data.map(pic => `<img src="${pic}" alt="Lab Result Picture" onclick="openImageModal('${pic}')" class="table-image">`).join('');
                            }
                            return 'No Lab Result pictures uploaded.';
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id',
                        name: 'actions',
                        render: function(data) {
                            return `
                                <button type="button" class="btn btn-success" onclick="approveExamination(${data})">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button type="button" class="btn btn-danger" onclick="rejectExamination(${data})">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                fixedHeader: true,
                responsive: true,
                language: {
                    emptyTable: "No pending examinations available."
                }
            });

            // Switch to default tab and load data
            const defaultRole = 'pending-approvals';
            switchTab(defaultRole); // Switch to the default tab
            healthExaminationsTable.ajax.reload(null, false); // Load data via AJAX
            startRealTimeRefresh(); // Start real-time refresh
        });
        $('#select-all-checkbox').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.select-exam').prop('checked', isChecked);
    });
    $(document).on('change', '.select-exam', function() {
        if (!$(this).is(':checked')) {
            $('#select-all-checkbox').prop('checked', false);
        } else if ($('.select-exam:checked').length === $('.select-exam').length) {
            $('#select-all-checkbox').prop('checked', true);
        }
    });
    $('#bulk-approve-btn').on('click', function() {
        const selectedExams = [];
        $('.select-exam:checked').each(function() {
            selectedExams.push($(this).data('exam-id'));
        });

        if (selectedExams.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Selection',
                text: 'Please select at least one examination to approve.',
            });
            return;
        }

        Swal.fire({
            title: 'Bulk Approve',
            text: `Are you sure you want to approve ${selectedExams.length} examination(s)?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, approve them!'
        }).then((result) => {
            if (result.isConfirmed) {
                showSpinner();

                // Send AJAX request for bulk approval
                $.ajax({
                    url: '/nurse/health-examinations/bulk-approve',
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: JSON.stringify({
                        examination_ids: selectedExams
                    }),
                    success: function(data) {
                        hideSpinner();
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Approved!',
                                text: data.message || `${data.approved_count} examination(s) approved successfully.`,
                                showConfirmButton: false,
                                timer: 2000
                            });
                            // Reload DataTable to reflect changes
                            healthExaminationsTable.ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'Failed to approve some examinations.',
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        hideSpinner();
                        console.error('Error approving examinations:', xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON.message || 'Failed to approve examinations.',
                        });
                    }
                });
            }
        });
    });
    $('#bulk-reject-btn').on('click', function() {
        const selectedExams = [];
        $('.select-exam:checked').each(function() {
            selectedExams.push($(this).data('exam-id'));
        });

        if (selectedExams.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Selection',
                text: 'Please select at least one examination to reject.',
            });
            return;
        }

        Swal.fire({
            title: 'Bulk Reject',
            text: `Are you sure you want to reject ${selectedExams.length} examination(s)?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, reject them!'
        }).then((result) => {
            if (result.isConfirmed) {
                showSpinner();

                // Send AJAX request for bulk rejection
                $.ajax({
                    url: '/nurse/health-examinations/bulk-reject',
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: JSON.stringify({
                        examination_ids: selectedExams
                    }),
                    success: function(data) {
                        hideSpinner();
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Rejected!',
                                text: data.message || `${data.rejected_count} examination(s) rejected successfully.`,
                                showConfirmButton: false,
                                timer: 2000
                            });
                            // Reload DataTable to reflect changes
                            healthExaminationsTable.ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'Failed to reject some examinations.',
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        hideSpinner();
                        console.error('Error rejecting examinations:', xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON.message || 'Failed to reject examinations.',
                        });
                    }
                });
            }
        });
    });
        // Real-Time Refresh Function
        function startRealTimeRefresh() {
            // Fetch and update the table every 10 seconds (10000 milliseconds)
            setInterval(() => {
                const activeTab = document.querySelector('.tab-btn.active');
                const role = activeTab ? activeTab.getAttribute('data-role') : 'pending-approvals';
                // const searchQuery = document.getElementById('search-input').value.trim(); // Uncomment if you have a search input
                if (role === 'pending-approvals') {
                    $('#health-examinations-table').DataTable().ajax.reload(null, false); // Reload DataTable via AJAX without resetting pagination
                }
                // Add additional conditions if other tabs require periodic refresh
            }, 10000);
        }

        // Function to reset school year
        document.getElementById('reset-school-year').addEventListener('click', function () {
            const selectedYear = document.getElementById('school-year-select').value;

            // Confirmation Dialog before Reset
            Swal.fire({
                title: 'Are you sure?',
                text: `You want to reset the school year to ${selectedYear}? Users will need to upload new health examinations for this year.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, reset it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    showSpinner();

                    // Send AJAX request to reset school year data
                    fetch(`/nurse/reset-school-year`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            school_year: selectedYear
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideSpinner();
                        if (data.success) {
                            Swal.fire(
                                'Reset!',
                                data.message || `The school year has been reset to ${selectedYear}. Users can now upload new health examinations.`,
                                'success'
                            ).then(() => {
                                // Reload to reflect changes
                                location.reload(); // Reload the page
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'Failed to reset the school year data.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        hideSpinner();
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'There was a problem resetting the school year data.',
                            'error'
                        );
                    });
                }
            });
        });

        // Close modal when clicking outside of the modal content
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });
        }
        const remindersTable = $('#reminders-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("nurse.healthExaminations.remindersData") }}',
            type: 'GET',
            error: function (xhr, error, thrown) {
                hideSpinner();
                console.error('Error fetching reminders data:', xhr.responseText);
                Swal.fire(
                    'Error!',
                    'Failed to load reminders data. Please try again later.',
                    'error'
                );
            }
        },
        columns: [
            { 
                data: 'id',
                name: 'id',
                render: function(data) {
                    return `<input type="checkbox" class="select-student" data-student-id="${data}">`;
                },
                orderable: false,
                searchable: false
            },
            { data: 'id_number', name: 'id_number' },
            { data: 'student_name', name: 'student_name' },
            { data: 'grade_or_course', name: 'grade_or_course' },
            { data: 'section', name: 'section' },
            { data: 'pending_since', name: 'pending_since' },
        ],
        order: [[1, 'asc']],
        language: {
            emptyTable: "No students pending health examinations."
        }
    });

    // Handle Select All Checkbox
   // Handle Select All Checkbox
$('#select-all-checkbox').on('change', function() {
    const isChecked = $(this).is(':checked');
    $('.select-student').prop('checked', isChecked);
});

// Handle individual checkbox changes to update Select All checkbox
$(document).on('change', '.select-student', function() {
    if (!$(this).is(':checked')) {
        $('#select-all-checkbox').prop('checked', false);
    } else if ($('.select-student:checked').length === $('.select-student').length) {
        $('#select-all-checkbox').prop('checked', true);
    }
});
    // Handle Send Reminder Button Click
    $('#send-reminder-btn').on('click', function() {
        const selectedStudents = [];
        $('.select-student:checked').each(function() {
            selectedStudents.push($(this).data('student-id'));
        });

        if (selectedStudents.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Selection',
                text: 'Please select at least one student to send reminders.',
            });
            return;
        }

        Swal.fire({
            title: 'Send Reminders?',
            text: `Are you sure you want to send reminders to ${selectedStudents.length} student(s)?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, send them!'
        }).then((result) => {
            if (result.isConfirmed) {
                showSpinner();

                // Send AJAX request to send reminders
                $.ajax({
                    url: '{{ route("nurse.healthExaminations.sendReminders") }}',
                    method: 'POST',
                    data: {
                        student_ids: selectedStudents,
                        _token: csrfToken
                    },
                    success: function(data) {
                        hideSpinner();
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 2000
                            });
                            // Reload Reminders DataTable
                            remindersTable.ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Failed to send reminders.',
                                showConfirmButton: true,
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        hideSpinner();
                        console.error('Error sending reminders:', xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON.message || 'Failed to send reminders.',
                            showConfirmButton: true,
                        });
                    }
                });
            }
        });
    });

    // Handle Select All Button Click
    $('#select-all-btn').on('click', function() {
        const isChecked = $('#select-all-checkbox').prop('checked');
        $('.select-student').prop('checked', !isChecked);
        $('#select-all-checkbox').prop('checked', !isChecked);
    });
    </script>

</x-app-layout>
