<x-app-layout :pageTitle="'Health Examination'">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- SweetAlert2 for Alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }
        .modal-container-wrapper {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: 20px;
            gap: 50px;
            flex-wrap: wrap;
        }
        .school-year-container {
            margin-bottom: 20px;
            text-align: center;
        }

        .school-year-container label {
            font-weight: bold;
            margin-right: 10px;
            font-size: 18px;
        }

        .school-year-container input {
            border: 1px solid #ccc;
            padding: 8px;
            border-radius: 5px;
            font-size: 16px;
            width: 200px;
            text-align: center;
            background-color: #f9f9f9;
        }
        .main-content {
            margin-top: 100px;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .modal-container {
            flex: 1;
            max-width: calc(33.33% - 30px);
            width: 100%;
        }
        .modal-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
            text-align: center;
            padding: 20px;
            margin-bottom: 30px;
        }
        .modal-card h3 {
            margin: 15px 0 5px 0;
        }
        .upload-section {
            border: 2px dashed #007bff;
            border-radius: 10px;
            padding: 20px;
            position: relative;
            cursor: pointer;
            background-color: #f9f9f9;
            transition: background-color 0.3s;
        }
        .upload-section:hover {
            background-color: #e6f0ff;
        }
        .upload-section input[type=file] {
            opacity: 0;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        .upload-section .upload-text {
            color: #007bff;
            font-weight: bold;
            font-size: 16px;
        }
        .file-list {
            margin-top: 20px;
            text-align: left;
        }
        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .file-item .file-name {
            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
        }
        .file-item .file-remove {
            color: #ff0000;
            cursor: pointer;
            font-size: 18px;
        }
        .submit-btn, .update-btn {
            background-color: #007bff;
            color: white;
            font-family: 'Poppins', sans-serif;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            margin-top: 20px;
            margin: 20px auto;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeInUp 0.5s ease-in-out;
            z-index: 1000;
        }
        .submit-btn:hover, .update-btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .submit-btn:active, .update-btn:active {
            transform: scale(0.95);
        }
        .pending-approval {
            text-align: center;
            font-size: 1.2rem;
            color: #007bff;
            margin-top: 20px;
            border: 2px solid #007bff;
            padding: 20px;
            border-radius: 10px;
            background-color: #e9f7fe;
            width: 80%;
        }
        .existing-submissions {
            width: 100%;
            margin-top: 40px;
        }
        .submission-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .submission-table th, .submission-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        .submission-table th {
            background-color: #f2f2f2;
            color: #333;
        }
        .submission-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .submission-card p {
            margin: 0;
            font-size: 1rem;
        }
        .submission-card button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .submission-card button:hover {
            background-color: #218838;
        }
        /* Modal Styling */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            animation: fadeIn 0.3s ease-in-out;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            max-width: 800px;
            border-radius: 10px;
            animation: slideIn 0.3s ease-in-out;
            position: relative;
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
        .modal-body img {
            width: 100%;
            height: auto;
            border-radius: 5px;
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
        /* Responsive Design */
        @media (max-width: 768px) {
            .modal-container-wrapper {
                flex-direction: column;
                align-items: center;
            }
            .modal-container {
                max-width: 100%;
            }
            .submission-table, .submission-card {
                font-size: 0.9rem;
            }
            .submit-btn, .update-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
            .upload-section .upload-text {
                font-size: 14px;
            }
            .file-item .file-name {
                font-size: 0.9rem;
            }
            .file-item .file-remove {
                font-size: 16px;
            }
        }
    </style>

    <div class="container">
    <h2>Upload Your Health Examination Documents</h2>


        <!-- Upload Section -->
        <div id="upload-section">
            <form id="upload-pictures-form" method="POST" action="{{ route('staff.health-examination.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="school-year-container">
                        <label for="school_year">School Year:</label>
                        <input type="text" name="school_year" id="school_year" value="{{ trim($currentSchoolYear->year) }}" readonly>
                        </div>

                <div class="modal-container-wrapper">
                    <!-- Lab Exam Modal -->
                    <div class="modal-container">
                        <div class="modal-card">
                            <h3>Lab Exam - Up to 10 Pictures</h3>
                            <div class="upload-section" id="lab-upload-section">
                                <span class="upload-text">Drag files to upload or Browse files</span>
                                <input type="file" name="lab_result_picture[]" id="lab_result_picture" accept="image/*" multiple>
                            </div>
                            <div class="file-list" id="lab_result_picture-file-list"></div>
                        </div>
                    </div>

                    <!-- X-ray Modal -->
                    <div class="modal-container">
                        <div class="modal-card">
                            <h3>X-ray - Up to 10 Pictures</h3>
                            <div class="upload-section" id="xray-upload-section">
                                <span class="upload-text">Drag files to upload or Browse files</span>
                                <input type="file" name="xray_picture[]" id="xray_picture" accept="image/*" multiple>
                            </div>
                            <div class="file-list" id="xray_picture-file-list"></div>
                        </div>
                    </div>

                    <!-- Health Examination Modal -->
                    <div class="modal-container">
    <div class="modal-card">
        <h3>Health Examination - Up to 10 Pictures</h3>
        <div class="upload-section" id="health-upload-section">
            <span class="upload-text">Drag files to upload or Browse files</span>
            <input type="file" name="health_examination_picture[]" id="health_examination_picture" accept="image/*" multiple>
        </div>
        <div class="file-list" id="health_examination_picture-file-list"></div>
    </div>
    </div>
    </div>

                <button type="submit" class="submit-btn">Submit All</button>
            </form>
        </div>

        <!-- Pending Approval Message -->
        <div id="pending-approval-message" class="pending-approval" style="display: none;">
            Your submission is pending approval. Please wait for further instructions. Thank you!
        </div>


    <!-- View Submission Modal -->
    <div id="view-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>View Health Examination Submission</h2>
                <span class="close" onclick="closeViewModal()">&times;</span>
            </div>
            <div class="modal-body" id="view-modal-body">
                <!-- Content will be loaded via JavaScript -->
            </div>
        </div>
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const pendingApprovalMessage = document.getElementById('pending-approval-message');
            const uploadSection = document.getElementById('upload-section');

            function checkApprovalStatus() {
    fetch('{{ route('staff.health-examination.status') }}', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
    })
    .then(response => {
        if (!response.ok) {
            // Handle HTTP errors
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Approval Status Data:', data);
        if (!data.exists) {
            // No submission exists, show upload section
            pendingApprovalMessage.style.display = 'none';
            uploadSection.style.display = 'block';
        } else if (data.is_approved === true) {
            // Submission is approved, redirect to next step
            Swal.fire({
                icon: 'success',
                title: 'Approved',
                text: 'Your submission has been approved. Proceed to the next step.',
            }).then(() => {
                window.location.href = '{{ route('staff.dashboard') }}';
            });
        } else {
            // Submission exists and is pending approval
            pendingApprovalMessage.style.display = 'block';
            uploadSection.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error checking approval status:', error);
    });
}


            // Call checkApprovalStatus on page load
            checkApprovalStatus();

            const fileInputs = document.querySelectorAll('input[type="file"]');
            const globalUploadedFiles = new Set(); // Global set to track all uploaded files

            const sectionNames = {
                'lab_result_picture': 'Lab Picture',
                'xray_picture': 'X-ray Picture',
                'health_examination_picture': 'Health Examination Picture',

            };

            const fileCount = {
                'lab_result_picture': 1,
                'xray_picture': 1,
                'health_examination_picture': 1,

            };

            fileInputs.forEach(input => {
                input.addEventListener('change', function () {
                    const section = input.name.replace('[]', '').replace('update_', '');
                    const fileListId = input.id + '-file-list';
                    const fileList = document.getElementById(fileListId);

                    if (!fileList) {
                        console.error('File list element not found for input:', input.id);
                        return;
                    }

                    const files = Array.from(this.files);

                    // Enforce maximum number of files per section
                    const maxFiles = 10;
                    const existingFiles = fileList.children.length;
                    if (existingFiles + files.length > maxFiles) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Too Many Files',
                            text: `You can upload a maximum of ${maxFiles} files per section.`,
                        });
                        input.value = ''; // Clear the input
                        return;
                    }

                    let duplicateFound = false;

                    // Check for duplicates across all modals
                    files.forEach(file => {
                        if (globalUploadedFiles.has(file.name)) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Duplicate File',
                                text: `The file "${file.name}" has already been uploaded. Please choose a different file.`,
                            });
                            duplicateFound = true;
                        }
                    });

                    if (duplicateFound) {
                        input.value = ''; // Clear the input to prevent submission of duplicate files
                        return;
                    }

                    files.forEach(file => {
                        globalUploadedFiles.add(file.name); // Track globally

                        const fileItem = document.createElement('div');
                        fileItem.classList.add('file-item');

                        const fileName = document.createElement('span');
                        fileName.classList.add('file-name');

                        // Use custom file names based on the section and file count
                        fileName.textContent = sectionNames[input.id] ? `${sectionNames[input.id]} ${fileCount[input.id]}` : file.name;
                        fileCount[input.id]++; // Increment count for the section

                        const fileRemove = document.createElement('span');
                        fileRemove.innerHTML = '&times;';
                        fileRemove.classList.add('file-remove');
                        fileRemove.addEventListener('click', function () {
                            globalUploadedFiles.delete(file.name); // Remove from global set
                            fileList.removeChild(fileItem);

                            // Decrease count when removing the file
                            if (sectionNames[input.id]) {
                                fileCount[input.id]--;
                            }
                        });

                        fileItem.appendChild(fileName);
                        fileItem.appendChild(fileRemove);
                        fileList.appendChild(fileItem);

                        // Attach click event to preview image in modal
                        fileName.addEventListener('click', function () {
                            previewImageInModal(file); // Trigger image preview
                        });
                    });

                    Swal.fire({
                        icon: 'success',
                        title: 'Files Uploaded',
                        text: 'Your files have been added to the list successfully.',
                    });
                });
            });

            // Handle Upload Form Submission
            const uploadForm = document.getElementById('upload-pictures-form');
            if (uploadForm) {
                uploadForm.addEventListener('submit', function (event) {
                    event.preventDefault();

                    const formData = new FormData(this);

                    showSpinner();

                    fetch('{{ route('staff.health-examination.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json', // Ensure JSON response
                        },
                    })
                    .then(response => {
                        hideSpinner();
                        if (!response.ok) {
                            return response.json().then(data => { throw data; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Store response data:', data);
                        if (data.success) {
                            // Reset the form and global file tracking
                            uploadForm.reset();
                            document.querySelectorAll('.file-list').forEach(list => list.innerHTML = '');
                            globalUploadedFiles.clear();
                            Object.keys(fileCount).forEach(key => fileCount[key] = 1);

                            Swal.fire({
                                icon: 'success',
                                title: 'Pictures Uploaded',
                                text: 'Your pictures have been uploaded successfully and are now pending approval.',
                            });

                            // Update the UI by checking approval status
                            checkApprovalStatus();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Upload Failed',
                                text: data.message || 'There was a problem uploading your pictures. Please try again.',
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Upload Failed',
                            text: error.message || 'An unexpected error occurred. Please try again.',
                        });
                        console.error('Error:', error);
                    });
                });
            }

            // Function to open image preview modal
            function previewImageInModal(file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    Swal.fire({
                        title: 'Preview',
                        imageUrl: e.target.result,
                        imageAlt: 'Uploaded Image',
                        showCloseButton: true,
                        showCancelButton: true,
                        focusConfirm: false,
                        confirmButtonText: 'Close',
                    });
                };
                reader.readAsDataURL(file); // Read the file as a data URL to display in SweetAlert
            }

            // Spinner Functions
            function showSpinner() {
                document.getElementById('spinner-overlay').style.display = 'flex';
            }

            function hideSpinner() {
                document.getElementById('spinner-overlay').style.display = 'none';
            }

            // Update Submission Functionality
            function openUpdateForm(examId) {
                // Fetch the submission details
                fetch(`/staff/health-examination/${examId}/details`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Populate the update form with existing data if needed
                        document.getElementById('examination_id').value = data.data.id;
                        document.getElementById('update_school_year').value = data.data.school_year;

                        // Open the update modal
                        document.getElementById('update-modal').style.display = 'flex';
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Failed to fetch submission details.',
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred. Please try again.',
                    });
                    console.error('Error fetching submission details:', error);
                });
            }

            function closeUpdateModal() {
                document.getElementById('update-modal').style.display = 'none';
            }

            // Handle Update Form Submission
            const updateForm = document.getElementById('update-form');
            if (updateForm) {
                updateForm.addEventListener('submit', function (event) {
                    event.preventDefault();

                    const formData = new FormData(this);

                    showSpinner();

                    fetch('{{ route('staff.health-examination.update') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                    })
                    .then(response => {
                        hideSpinner();
                        if (!response.ok) {
                            return response.text().then(text => { throw new Error(text); });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: data.message || 'Your submission has been updated successfully.',
                            }).then(() => {
                                closeUpdateModal();
                                location.reload(); // Reload to reflect changes
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Update Failed',
                                text: data.message || 'There was a problem updating your submission.',
                            });
                        }
                    })
                    .catch(error => {
                        hideSpinner();
                        Swal.fire({
                            icon: 'error',
                            title: 'Update Failed',
                            text: 'An unexpected error occurred. Please try again.',
                        });
                        console.error('Error:', error);
                    });
                });
            }

            // View Submission Functionality
            function viewSubmission(examId) {
                // Fetch the submission details
                fetch(`/staff/health-examination/${examId}/details`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const modalBody = document.getElementById('view-modal-body');
                        const exam = data.data;

                        // Construct HTML to display submission details
                        let html = `
                            <p><strong>School Year:</strong> ${exam.school_year}</p>
                            <p><strong>Status:</strong> ${exam.is_approved ? '<span style="color: green; font-weight: bold;">Approved</span>' : '<span style="color: orange; font-weight: bold;">Pending</span>'}</p>
                            <h3>Health Examination Pictures</h3>
                        `;

                        if (exam.health_examination_pictures && exam.health_examination_pictures.length > 0) {
                            exam.health_examination_pictures.forEach(healthPic => {
                                html += `<img src="{{ asset('storage/') }}/${healthPic}" alt="Health Examination Picture" style="width: 200px; margin: 10px;">`;
                            });
                        } else {
                            html += `<p>No Health Examination pictures uploaded.</p>`;
                        }

                        html += `<h3>X-ray Pictures</h3>`;

                        if (exam.xray_pictures && exam.xray_pictures.length > 0) {
                            exam.xray_pictures.forEach(xray => {
                                html += `<img src="{{ asset('storage/') }}/${xray}" alt="X-ray Picture" style="width: 200px; margin: 10px;">`;
                            });
                        } else {
                            html += `<p>No X-ray pictures uploaded.</p>`;
                        }

                        html += `<h3>Lab Result Pictures</h3>`;

                        if (exam.lab_result_pictures && exam.lab_result_pictures.length > 0) {
                            exam.lab_result_pictures.forEach(lab => {
                                html += `<img src="{{ asset('storage/') }}/${lab}" alt="Lab Result Picture" style="width: 200px; margin: 10px;">`;
                            });
                        } else {
                            html += `<p>No Lab Result pictures uploaded.</p>`;
                        }

                        modalBody.innerHTML = html;

                        // Open the view modal
                        document.getElementById('view-modal').style.display = 'flex';
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Failed to fetch submission details.',
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred. Please try again.',
                    });
                    console.error('Error fetching submission details:', error);
                });
            }

            function closeViewModal() {
                document.getElementById('view-modal').style.display = 'none';
            }

            // Close modals when clicking outside of the modal content
            window.onclick = function(event) {
                const modals = document.querySelectorAll('.modal');
                modals.forEach(modal => {
                    if (event.target == modal) {
                        modal.style.display = 'none';
                    }
                });
            }
        });
    </script>
</x-app-layout>
