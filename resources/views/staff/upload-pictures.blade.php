<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Poppins', sans-serif;
        }
        .modal-container-wrapper {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: 20px;
            gap: 50px;
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
        .file-item .progress-bar {
            width: 70%;
            height: 5px;
            border-radius: 5px;
            background-color: #e0e0e0;
            margin-left: 10px;
            position: relative;
        }
        .file-item .progress-bar span {
            display: block;
            height: 100%;
            border-radius: 5px;
            background-color: #007bff;
            width: 0%;
            transition: width 0.3s;
        }
        .file-item .file-remove {
            color: #ff0000;
            cursor: pointer;
            font-size: 18px;
        }
        .submit-btn {
            background-color: #007bff;
            color: white;
            font-family: 'Poppins', sans-serif;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            margin-top: 20px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeInUp 0.5s ease-in-out;
            position: fixed;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
        }
        .submit-btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .submit-btn:active {
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
        .school-year-container {
            width: 100%;
            text-align: center;
            margin-bottom: 20px;
        }
        .school-year-container select {
            padding: 10px;
            font-family: 'Poppins', sans-serif;

            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #007bff;
        }
    </style>

    <div class="container">
        <div class="school-year-container">
            <label for="school_year">Select School Year:</label>
            <select name="school_year" id="school_year">
                @php
                    $currentYear = date('Y');
                    $nextYear = $currentYear + 1;
                    $schoolYear = $currentYear . '-' . $nextYear;
                @endphp
                <option value="{{ $schoolYear }}">{{ $schoolYear }}</option>
            </select>
        </div>

        <div id="upload-section">
            <form id="upload-pictures-form" method="POST" action="{{ route('staff.health-examination.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-container-wrapper">
                    <!-- Lab Exam Modal -->
                    <div class="modal-container">
                        <div class="modal-card">
                            <h3>Lab Exam - 4 Pictures</h3>
                            <div class="upload-section" id="lab-upload-section">
                                <span class="upload-text">Drag files to upload or Browse files</span>
                                <input type="file" name="lab_result_pictures[]" id="lab_result_pictures" accept="image/*" multiple>
                            </div>
                            <div class="file-list" id="lab_result_pictures-file-list"></div>
                        </div>
                    </div>

                    <!-- X-ray Modal -->
                    <div class="modal-container">
                        <div class="modal-card">
                            <h3>X-ray - 2 Pictures</h3>
                            <div class="upload-section" id="xray-upload-section">
                                <span class="upload-text">Drag files to upload or Browse files</span>
                                <input type="file" name="xray_pictures[]" id="xray_pictures" accept="image/*" multiple>
                            </div>
                            <div class="file-list" id="xray_pictures-file-list"></div>
                        </div>
                    </div>

                    <!-- Health Examination Modal -->
                    <div class="modal-container">
                        <div class="modal-card">
                            <h3>Health Examination - 1 Picture</h3>
                            <div class="upload-section" id="health-upload-section">
                                <span class="upload-text">Drag files to upload or Browse files</span>
                                <input type="file" name="health_examination_picture" id="health_examination_picture" accept="image/*">
                            </div>
                            <div class="file-list" id="health_examination_picture-file-list"></div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="submit-btn">Submit All</button>
            </form>
        </div>

        <div id="pending-approval-message" class="pending-approval" style="display: none;">
            Your submission is pending approval. Please wait for further instructions. Thank you!
        </div>
    </div>
    <div id="image-preview-section" style="margin-top: 20px;">
    <h4>Image Preview</h4>
    <div id="preview-container" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const pendingApprovalMessage = document.getElementById('pending-approval-message');
    const uploadSection = document.getElementById('upload-section');

    // Check submission status once on page load
    fetch('{{ route('staff.health-examination.status') }}', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    })
    .then(response => response.json())
    .then(data => {
        if (!data.exists) {
            Swal.fire({
                icon: 'info',
                title: 'Welcome to Health Examination',
                text: 'Please upload your health examination pictures and lab results.',
            }).then(() => {
                localStorage.removeItem('uploadCompleted');
                pendingApprovalMessage.style.display = 'none';
                uploadSection.style.display = 'block';
            });
        } else if (data.is_approved) {
            Swal.fire({
                icon: 'success',
                title: 'Approved',
                text: 'Your submission has been approved. Proceed to the next step.',
            }).then(() => {
                window.location.href = '{{ route('staff.medical-record.create') }}';
            });
        } else if (data.is_declined) {
            Swal.fire({
                icon: 'error',
                title: 'Submission Declined',
                text: 'Your submission has been declined. Please upload proper pictures and try again.',
            }).then(() => {
                localStorage.removeItem('uploadCompleted');
                pendingApprovalMessage.style.display = 'none';
                uploadSection.style.display = 'block';
            });
        }
    })
    .catch(error => {
        console.error('Error checking approval status:', error);
    });

    const fileInputs = document.querySelectorAll('input[type="file"]');
    const globalUploadedFiles = new Set(); // Global set to track all uploaded files

    const sectionNames = {
        'lab_result_pictures': 'Lab Picture',
        'xray_pictures': 'X-ray Picture',
        'health_examination_picture': 'Health Examination Picture',
    };

    const fileCount = {
        'lab_result_pictures': 1,
        'xray_pictures': 1,
        'health_examination_picture': 1,
    };

    fileInputs.forEach(input => {
        input.addEventListener('change', function () {
            const section = input.name.replace('[]', '');
            const fileListId = input.id + '-file-list';
            const fileList = document.getElementById(fileListId);

            if (!fileList) {
                console.error('File list element not found for input:', input.id);
                return;
            }

            const files = Array.from(this.files);
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
                fileName.textContent = sectionNames[section] ? `${sectionNames[section]} ${fileCount[section]}` : file.name;
                fileCount[section]++; // Increment count for the section

                const fileRemove = document.createElement('span');
                fileRemove.innerHTML = '&times;';
                fileRemove.classList.add('file-remove');
                fileRemove.addEventListener('click', function () {
                    globalUploadedFiles.delete(file.name); // Remove from global set
                    fileList.removeChild(fileItem);

                    // Decrease count when removing the file
                    if (sectionNames[section]) {
                        fileCount[section]--;
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

    const uploadForm = document.getElementById('upload-pictures-form');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch('{{ route('staff.health-examination.store') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text); });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    uploadSection.style.display = 'none';
                    pendingApprovalMessage.style.display = 'block';

                    Swal.fire({
                        icon: 'success',
                        title: 'Pictures Uploaded',
                        text: 'Your pictures have been uploaded successfully and are now pending approval.',
                    });

                    // Store a flag in localStorage to keep the state after reload
                    localStorage.setItem('uploadCompleted', 'true');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Failed',
                        text: 'There was a problem uploading your pictures. Please try again.',
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed',
                    text: 'An unexpected error occurred. Please try again.',
                });
                console.error('Error:', error);
            });
        });
    }

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
});
</script>
</x-app-layout>