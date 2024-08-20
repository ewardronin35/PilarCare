<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .modal-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            margin-bottom: 20px;
        }

        .modal-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 500px;
            text-align: center;
            padding: 20px;
            margin-bottom: 20px;
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
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
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
        }

        .submit-btn:hover {
            background-color: #218838;
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
    </style>

    <div class="container">
        <form id="upload-pictures-form" method="POST" action="{{ route('student.health-examination.store') }}" enctype="multipart/form-data">
            @csrf

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

            <button type="submit" class="submit-btn">Submit All</button>
        </form>

        <div id="pending-approval-message" class="pending-approval" style="display: none;">
            Your submission is pending approval. Please wait for further instructions. Thank you!
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const fileInputs = document.querySelectorAll('input[type="file"]');
            const maxFileCounts = {
                lab_result_pictures: 4,
                xray_pictures: 2,
                health_examination_picture: 1,
            };

            // This will store the names of files already uploaded globally
            const uploadedFiles = new Set();

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

                    // Check for duplicates in the new files
                    files.forEach(file => {
                        if (uploadedFiles.has(file.name)) {
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

                    // Append new files to the uploaded files set and update the displayed list
                    files.forEach(file => {
                        uploadedFiles.add(file.name);

                        const fileItem = document.createElement('div');
                        fileItem.classList.add('file-item');

                        const fileName = document.createElement('span');
                        fileName.textContent = file.name;

                        const progressBarContainer = document.createElement('div');
                        progressBarContainer.classList.add('progress-bar');

                        const progressBar = document.createElement('span');
                        progressBar.style.width = '0%';

                        progressBarContainer.appendChild(progressBar);

                        const fileRemove = document.createElement('span');
                        fileRemove.innerHTML = '&times;';
                        fileRemove.classList.add('file-remove');
                        fileRemove.addEventListener('click', function () {
                            uploadedFiles.delete(file.name);
                            fileList.removeChild(fileItem);
                        });

                        fileItem.appendChild(fileName);
                        fileItem.appendChild(progressBarContainer);
                        fileItem.appendChild(fileRemove);

                        fileList.appendChild(fileItem);

                        // Simulate progress bar
                        setTimeout(() => {
                            progressBar.style.width = '100%';
                        }, 1000);
                    });

                    // Show SweetAlert after files are added
                    Swal.fire({
                        icon: 'success',
                        title: 'Files Uploaded',
                        text: 'Your files have been added to the list successfully.'
                    });
                });
            });

            const uploadForm = document.getElementById('upload-pictures-form');
            if (uploadForm) {
                uploadForm.addEventListener('submit', function (event) {
                    event.preventDefault();

                    const formData = new FormData(this);

                    fetch('{{ route('student.health-examination.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                    })
                        .then(response => response.json()) {
        if (!response.ok) {
            return response.text().then(text => { throw new Error(text) });
        }
        return response.json();
    })
                        .then(data => {
                            if (data.success) {
                                const uploadSection = document.getElementById('upload-section');
                                if (uploadSection) uploadSection.style.display = 'none';

                                const pendingApprovalMessage = document.getElementById('pending-approval-message');
                                if (pendingApprovalMessage) pendingApprovalMessage.style.display = 'block';

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pictures Uploaded',
                                    text: 'Your pictures have been uploaded successfully and are now pending approval. Please wait for further instructions.',
                                });
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

            setInterval(function () {
                fetch('{{ route('student.health-examination.status') }}', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.is_approved) {
                            window.location.href = '{{ route('student.medical-record.create') }}';
                        }
                    })
                    .catch(error => {
                        console.error('Error checking approval status:', error);
                    });
            }, 5000);
        });
    </script>
</x-app-layout>
