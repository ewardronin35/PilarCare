
<x-app-layout :pageTitle="'Manage Students'">
<head>
        <!-- DataTables CSS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
        <!-- DataTables FixedHeader CSS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.9/css/fixedHeader.dataTables.min.css">
        <!-- Font Awesome CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

        <!-- jQuery (required for DataTables) -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <!-- DataTables JS -->
        <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
        <!-- DataTables FixedHeader JS -->
       
        <script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.9/js/dataTables.fixedHeader.min.js"></script>
    </head>

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
            justify-content: center; /* Centers child forms horizontally */
            align-items: center;    /* Centers child forms vertically (if necessary) */
            margin: 30px auto;      /* Centers the container itself horizontally and adds top margin */
            max-width: 1200px;      /* Optional: Limits the maximum width of the container */
            padding: 0 20px;        /* Optional: Adds horizontal padding for better responsiveness */
            box-sizing: border-box; /* Ensures padding is included in the total width */
        }

        @media (max-width: 768px) {
            .form-wrapper {
                flex: 1 1 100%; /* Stacks forms vertically on smaller screens */
                max-width: 100%;
            }
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
        form select,
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
        form select:hover,
        form input[type="file"]:hover {
            border-color: #00d1ff;
        }

        /* Students Table */
        .students-section {
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



.modal-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    width: 100%;
    max-width: 600px;
    animation: slideIn 0.5s ease-out;
    /* Removed display: flex and related properties */
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
                            <!-- Grade/Course Selection -->
                            <label for="grade_or_course_selection">Grade/Course</label>
                            <select name="grade_or_course" id="grade_or_course_selection" required>
                                <option value="">Select Grade/Course</option>
                                
                                <!-- Elementary Grades -->
                                @for($grade = 1; $grade <= 6; $grade++)
                                    <option value="GRADE {{ $grade }}">Grade {{ $grade }}</option>
                                @endfor

                                <!-- Junior High School Grades -->
                                @for($grade = 7; $grade <= 10; $grade++)
                                    <option value="GRADE {{ $grade }}">Grade {{ $grade }}</option>
                                @endfor

                                <!-- Senior High School Grades -->
                                <option value="GRADE 11">Grade 11</option>
                                <option value="GRADE 12">Grade 12</option>

                                <!-- College Courses -->
                                <option value="BSBA">BSBA</option>
                                <option value="BSIT">BSIT</option>
                                <option value="BSTM">BSTM</option>
                                <option value="BSHM">BSHM</option>
                                <option value="BSN">BSN</option>
                                <option value="BLIS">BLIS</option>
                                <option value="BEED">BEED</option>
                            </select>

                            <!-- File Upload -->
                            <div class="file-upload-container">
                                <label for="file"><i class="fas fa-paperclip"></i> Choose File</label>
                                <input type="file" name="file" id="file" required>
                                <div class="file-name" id="file-name">No file chosen</div>
                                <button type="submit" class="preview-button"><i class="fas fa-upload"></i> Upload</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <!-- View Students Tab Content -->
        <div id="students-tab" class="tab-content">
            <div class="students-section">
                <h2><i class="fas fa-users"></i> Enrolled Students</h2>

                <!-- Add Semester, School Year, and Grade/Course Filters -->
                <div class="filter-container" style="margin-bottom: 20px; display: flex; gap: 20px; align-items: center;">
                    <!-- Existing Semester Filter -->
                    <div>
                        <label for="filter-semester">Semester:</label>
                        <select id="filter-semester" style="padding: 8px; border-radius: 5px; border: 1px solid #ddd;">
                            <option value="">All Semesters</option>
                            <option value="FIRST SEMESTER">First Semester</option>
                            <option value="SECOND SEMESTER">Second Semester</option>
                            <option value="SUMMER">Summer</option>
                        </select>
                    </div>
                    <!-- Existing School Year Filter -->
                    <div>
                        <label for="filter-school_year">School Year:</label>
                        <select id="filter-school_year" style="padding: 8px; border-radius: 5px; border: 1px solid #ddd;">
                            <option value="">All School Years</option>
                            @php
                                $currentYear = date('Y');
                                // Determine the start year based on the current month (assuming academic year starts in August)
                                $month = date('n');
                                if ($month >= 8) { // August or later
                                    $startYear = $currentYear;
                                } else { // Before August
                                    $startYear = $currentYear - 1;
                                }
                                $endYear = $startYear + 10; // Generate 10 years ahead
                            @endphp
                            @for($year = $startYear; $year < $endYear; $year++)
                                <option value="{{ $year }}-{{ $year +1 }}">{{ $year }}-{{ $year +1 }}</option>
                            @endfor
                        </select>
                    </div>
                    <!-- New Grade/Course Filter -->
                    <div>
                        <label for="filter-grade_course">Grade/Course:</label>
                        <select id="filter-grade_course" style="padding: 8px; border-radius: 5px; border: 1px solid #ddd;">
                            <option value="">All Grades/Courses</option>
                            <!-- Add your grade/course options here -->
                            <!-- Elementary Grades -->
                            @for($grade = 1; $grade <= 6; $grade++)
                                <option value="GRADE {{ $grade }}">Grade {{ $grade }}</option>
                            @endfor

                            <!-- Junior High School Grades -->
                            @for($grade = 7; $grade <= 10; $grade++)
                                <option value="GRADE {{ $grade }}">Grade {{ $grade }}</option>
                            @endfor

                            <!-- Senior High School Grades -->
                            <option value="GRADE 11">Grade 11</option>
                            <option value="GRADE 12">Grade 12</option>

                            <!-- College Courses -->
                            <option value="BSBA">BSBA</option>
                            <option value="BSIT">BSIT</option>
                            <option value="BSTM">BSTM</option>
                            <option value="BSHM">BSHM</option>
                            <option value="BSN">BSN</option>
                            <option value="BLIS">BLIS</option>
                            <option value="BEED">BEED</option>
                        </select>
                    </div>
                    <!-- Filter and Reset Buttons -->
                    <div>
                        <button id="filter-button" class="preview-button"><i class="fas fa-filter"></i> Filter</button>
                        <button id="reset-button" class="preview-button" style="background-color: #6c757d;"><i class="fas fa-redo"></i> Reset</button>
                    </div>
                </div>

                <div class="students-table-container">
                    <table class="students-table" id="students-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Grade/Course</th>
                                <th>Section</th> <!-- New Column -->
                                <th>Education Level</th>
                                <th>Status</th>
                                <th>Gender</th>
                                <th>Toggle Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTables will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Edit Student Modal -->
            <div id="edit-student-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Edit Student</h2>
                    <form id="edit-student-form">
                        @csrf
                        <input type="hidden" name="id_number" id="edit-student-id">
                        <label for="edit-id-number">ID Number</label>
                        <input type="text" name="id_number" id="edit-id-number" required maxlength="7" pattern="[A-Za-z][0-9]{6}" title="ID number must start with a letter followed by 6 digits." readonly>

                        <label for="edit-first-name">First Name</label>
                        <input type="text" name="first_name" id="edit-first-name" required>

                        <label for="edit-last-name">Last Name</label>
                        <input type="text" name="last_name" id="edit-last-name" required>

                        <label for="edit-grade-course">Grade/Course</label>
                        <input type="text" name="grade_or_course" id="edit-grade-course" required>

                        <label for="edit-section">Section</label> <!-- New Field -->
                        <input type="text" name="section" id="edit-section" required>

                        <label for="edit-education-level">Education Level</label>
                        <input type="text" name="education_level" id="edit-education-level">

                        <label for="edit-gender">Gender</label>
                        <input type="text" name="gender" id="edit-gender">

                        

                        <button type="submit" class="save-button"><i class="fas fa-save"></i> Save</button>
                    </form>
                </div>
            </div>  
        </div>

        <!-- View Student Modal -->
        <div id="view-student-modal" class="modal">
            <div class="modal-content">
                <span class="close-view">&times;</span>
                <h2><i class="fas fa-eye"></i> Student Details</h2>
                <div id="student-details">
                    <!-- Student details will be populated here via JavaScript -->
                </div>
            </div>
        </div>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
     $(document).ready(function() {
    // Initialize DataTable with AJAX
    var table = $('#students-table').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": {
            "url": "{{ route('admin.students.enrolled') }}",
            "type": "GET",
            "data": function(d) {
                d.semester = $('#filter-semester').val();
                d.school_year = $('#filter-school_year').val();
                d.grade_or_course = $('#filter-grade_course').val(); // Correct selector
            },
            "dataSrc": ""
        },
        "columns": [
            { "data": "id_number" },
            { "data": "first_name" },
            { "data": "last_name" },
            { "data": "grade_or_course" },
            { "data": "section" }, // New Column
            { "data": "education_level" },
            {
                "data": "is_enrolled",
                "render": function(data, type, row) {
                    var color = data ? '#28a745' : '#dc3545';
                    var status = data ? 'Enrolled' : 'Not Enrolled';
                    return `<button class="preview-button status-button" style="background-color: ${color};">
                                ${status}
                            </button>`;
                }
            },
            { "data": "gender" },
            {
                "data": null,
                "render": function(data, type, row) {
                    var checked = row.is_enrolled ? 'checked' : '';
                    return `<label class="switch">
                                <input type="checkbox" class="toggle-enrollment" data-student-id="${row.id_number}" data-semester="${row.semester}" data-school-year="${row.school_year}" data-grade-course="${row.grade_or_course}" ${checked}>
                                <span class="slider"></span>
                            </label>`;
                }
            },
            {
                "data": null,
                "orderable": true, "targets": 0,
                "render": function(data, type, row) {
                    return `<button class="preview-button view-button" data-student-id="${row.id_number}">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="edit-button" data-student-id="${row.id_number}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="delete-button" onclick="deleteStudent('${row.id_number}')">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>`;
                }
            }
        ],
        "fixedHeader": true,
        "responsive": true
    });

    // Handle Tab Switching
    $('.tab').on('click', function() {
        $('.tab').removeClass('active');
        $('.tab-content').removeClass('active');
        $(this).addClass('active');
        $('#' + $(this).data('tab')).addClass('active');
    });

    // File selection feedback
    $('#file').on('change', function() {
        if(this.files && this.files.length > 0){
            const fileName = this.files[0].name;
            $('#file-name').text(fileName);
        } else {
            $('#file-name').text('No file chosen');
        }
    });

    // Upload form submission
    $('#upload-form').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        Swal.fire({
            title: 'Uploading...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            }
        });

        $.ajax({
            url: '{{ route('admin.students.import') }}',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'Accept': 'application/json'
            },
            success: function(data) {
                Swal.close();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#upload-form')[0].reset();
                    $('#file-name').text('No file chosen');
                    table.ajax.reload(); // Refresh DataTable
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Import Failed',
                        html: data.errors.join('<br>'),
                        showConfirmButton: true,
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Import Failed',
                        html: xhr.responseJSON.errors.join('<br>'),
                        showConfirmButton: true,
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was a problem uploading the file.',
                        showConfirmButton: true,
                    });
                }
            }
        });
    });

    // Delete student function
    window.deleteStudent = function(id_number) {
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

                $.ajax({
                    url: `/admin/students/${id_number}`,
                    type: 'DELETE',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    success: function(data) {
                        Swal.close();
                        if (data.success) {
                            Swal.fire('Deleted!', data.message, 'success');
                            table.ajax.reload(); // Refresh DataTable
                        } else {
                            Swal.fire('Error!', 'There was a problem deleting the student.', 'error');
                        }
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire('Error!', 'There was a problem deleting the student.', 'error');
                    }
                });
            }
        });
    }

    // Toggle enrollment event using event delegation
    $('#students-table').on('change', '.toggle-enrollment', function() {
        var studentIdNumber = $(this).data('student-id');
        var isEnrolled = $(this).is(':checked') ? 1 : 0;
        var gradeOrCourse = $(this).data('grade-course'); // Now correctly retrieved

        // Determine semester and school year programmatically
        var semester = determineCurrentSemester();
        var schoolYear = determineCurrentSchoolYear();

        if (!semester || !schoolYear) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Information',
                text: 'Unable to determine the current semester and school year.',
                showConfirmButton: true,
            });
            // Revert the checkbox state
            $(this).prop('checked', !isEnrolled);
            return;
        }

        var formData = new FormData();
        formData.append('is_enrolled', isEnrolled);
        formData.append('semester', semester);
        formData.append('school_year', schoolYear);
        formData.append('grade_or_course', gradeOrCourse);

        Swal.fire({
            title: 'Updating Enrollment Status...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            }
        });

        $.ajax({
            url: `/admin/students/${studentIdNumber}/toggle-enrollment`,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            success: function(data) {
                Swal.close();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    table.ajax.reload(); // Refresh DataTable
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was a problem updating the enrollment status.',
                        showConfirmButton: true,
                    });
                    // Revert the checkbox state
                    $(this).prop('checked', !isEnrolled);
                }
            }.bind(this),
            error: function() {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was a problem updating the enrollment status.',
                    showConfirmButton: true,
                });
                // Revert the checkbox state
                $(this).prop('checked', !isEnrolled);
            }.bind(this)
        });
    });

    // Function to determine current semester based on current date
    function determineCurrentSemester() {
        var month = new Date().getMonth() + 1; // JavaScript months are 0-11
        if (month >= 1 && month <= 4) {
            return 'SUMMER';
        } else if (month >= 5 && month <= 8) {
            return 'FIRST SEMESTER';
        } else {
            return 'SECOND SEMESTER';
        }
    }

    // Function to determine current school year based on current date
    function determineCurrentSchoolYear() {
        var today = new Date();
        var year = today.getFullYear();
        var month = today.getMonth() + 1;

        if (month >= 8) { // August or later
            return year + '-' + (year + 1);
        } else { // Before August
            return (year - 1) + '-' + year;
        }
    }

    // Edit button event using event delegation
    $('#students-table').on('click', '.edit-button', function() {
        var studentIdNumber = $(this).data('student-id');

        Swal.fire({
            title: 'Fetching Student Data...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            }
        });

        $.ajax({
            url: `/admin/students/${studentIdNumber}`,
            type: 'GET',
            dataType: 'json',
            headers: {
                'Accept': 'application/json'
            },
            success: function(response) {
                Swal.close();
                if (response.student) {
                    var student = response.student;
                    var email = response.email || 'N/A';
                    $('#edit-student-id').val(student.id_number); // Use id_number as identifier
                    $('#edit-id-number').val(student.id_number);
                    $('#edit-first-name').val(student.first_name);
                    $('#edit-last-name').val(student.last_name);
                    $('#edit-grade-course').val(student.grade_or_course);
                    $('#edit-section').val(student.section); // Populate section
                    $('#edit-education-level').val(student.education_level);
                    $('#edit-gender').val(student.gender);
                    $('#edit-is-scholar').prop('checked', student.is_scholar);

                    $('#edit-student-modal').addClass('active'); // Show the modal
                } else {
                    Swal.fire('Error', 'Student data not found.', 'error');
                }
            },
            error: function() {
                Swal.close();
                Swal.fire('Error', 'Unable to fetch student data.', 'error');
            }
        });
    });

    // Close the edit modal when clicking the 'X' button
    $('.close').on('click', function() {
        $('#edit-student-modal').removeClass('active'); // Hide the modal
    });

    // Close the edit modal when clicking outside the modal content
    $(window).on('click', function(event) {
        if ($(event.target).is('#edit-student-modal')) {
            $('#edit-student-modal').removeClass('active'); // Hide the modal
        }
    });

    // Edit student form submission
    $('#edit-student-form').on('submit', function(e) {
        e.preventDefault();
        var studentIdNumber = $('#edit-student-id').val();
        var formData = $(this).serialize();

        Swal.fire({
            title: 'Updating...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            }
        });

        $.ajax({
            url: `/admin/students/${studentIdNumber}/edit`,
            type: 'POST',
            data: formData,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            success: function(data) {
                Swal.close();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#edit-student-modal').removeClass('active'); // Hide the modal
                    table.ajax.reload(); // Refresh DataTable
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: data.errors.join('<br>'),
                        showConfirmButton: true,
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: xhr.responseJSON.errors.join('<br>'),
                        showConfirmButton: true,
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was a problem updating the student.',
                        showConfirmButton: true,
                    });
                }
            }
        });
    });

    // View button event using event delegation
    $('#students-table').on('click', '.view-button', function() {
        var studentIdNumber = $(this).data('student-id');

        Swal.fire({
            title: 'Fetching Student Details...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            }
        });

        $.ajax({
            url: `/admin/students/${studentIdNumber}`,
            type: 'GET',
            dataType: 'json',
            headers: {
                'Accept': 'application/json'
            },
            success: function(response) {
                Swal.close();
                if (response.student) {
                    var student = response.student;
                    var email = response.email || 'N/A';
                    var htmlContent = `
                        <p><strong>ID Number:</strong> ${student.id_number}</p>
                        <p><strong>First Name:</strong> ${student.first_name}</p>
                        <p><strong>Last Name:</strong> ${student.last_name}</p>
                        <p><strong>Grade/Course:</strong> ${student.grade_or_course}</p>
                        <p><strong>Section:</strong> ${student.section}</p>
                        <p><strong>Education Level:</strong> ${student.education_level}</p>
                        <p><strong>Gender:</strong> ${student.gender}</p>
                        <p><strong>Father's Name:</strong> ${student.father_name}</p>
                        <p><strong>Mother's Name:</strong> ${student.mother_name}</p>
                        <p><strong>Contact Number:</strong> ${student.contact_number}</p>
                        <p><strong>Address:</strong> ${student.address}</p>
                        <p><strong>Emergency Contact:</strong> ${student.emergency_contact}</p>
                        <p><strong>Birthdate:</strong> ${student.birthdate}</p>
                        <p><strong>Age:</strong> ${student.age}</p>
                        <p><strong>Email:</strong> ${email}</p>
                        <p><strong>Approved:</strong> ${student.approved ? 'Yes' : 'No'}</p>
                    `;
                    $('#student-details').html(htmlContent);
                    
                    // Show the view modal
                    $('#view-student-modal').addClass('active');
                } else {
                    Swal.fire('Error', 'Student data not found.', 'error');
                }
            },
            error: function(xhr) {
                Swal.close();
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    Swal.fire('Error', xhr.responseJSON.error, 'error');
                } else {
                    Swal.fire('Error', 'Unable to fetch student data.', 'error');
                }
            }
        });
    });

    // Close the view modal when clicking the 'X' button
    $('.close-view').on('click', function() {
        $('#view-student-modal').removeClass('active'); // Hide the modal
    });

    // Close the view modal when clicking outside the modal content
    $(window).on('click', function(event) {
        if ($(event.target).is('#view-student-modal')) {
            $('#view-student-modal').removeClass('active'); // Hide the modal
        }
    });

    // Filter button event
    $('#filter-button').on('click', function() {
        table.ajax.reload();
    });

    // Reset button event
    $('#reset-button').on('click', function() {
        $('#filter-semester').val('');
        $('#filter-school_year').val('');
        $('#filter-grade_course').val(''); // Clear the grade/course filter

        table.ajax.reload();
    });

    // Initial fetch of students is handled by DataTables AJAX initialization
});

        </script>

    <!-- View Student Modal -->
    <div id="view-student-modal" class="modal">
        <div class="modal-content">
            <span class="close-view">&times;</span>
            <h2><i class="fas fa-eye"></i> Student Details</h2>
            <div id="student-details">
                <!-- Student details will be populated here via JavaScript -->
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
