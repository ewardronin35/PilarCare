<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            padding-top: 20px;
            transition: width 0.3s ease-in-out;
            overflow: hidden;
            z-index: 1000;
        }

        .sidebar:hover {
            width: 250px;
        }

        .main-content {
            margin-left: 80px;
            width: calc(100% - 80px);
            padding: 20px;
            transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
        }

        .sidebar:hover ~ .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
        }

        .form-container {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-in-out;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group button,
        .form-group label.button {
            background-color: #00d1ff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            margin-top: 10px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-group button:hover,
        .form-group label.button:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        .form-group button:active,
        .form-group label.button:active {
            transform: scale(0.95);
        }

        .form-group input[type="file"] {
            display: none;
        }

        .tab-buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .tab-buttons button {
            background-color: transparent;
            color: #000;
            padding: 10px 20px;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: color 0.3s, border-color 0.3s;
            margin: 0 10px;
            font-size: 16px;
        }

        .tab-buttons button:hover,
        .tab-buttons button.active {
            color: #007bff;
            border-color: #007bff;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .image-previews {
            display: flex;
            gap: 10px;
        }

        .image-previews img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
        }

        .status-message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
        }

        .status-message.waiting {
            background-color: #ffeeba;
            color: #856404;
        }

        .status-message.approved {
            background-color: #d4edda;
            color: #155724;
        }

        .status-message.rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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
        <main class="main-content">
            @php
                $latestExamination = Auth::user()->healthExaminations->last();
                $isPending = $latestExamination && !$latestExamination->is_approved && !$latestExamination->is_rejected;
                $isApproved = $latestExamination && $latestExamination->is_approved;
            @endphp
            <div class="tab-buttons">
                @if(!$isApproved && !$isPending)
                <button id="upload-pictures-tab" class="active" onclick="showTab('upload-pictures')">Upload Pictures</button>
                @elseif($isPending)
                <button id="upload-pictures-tab" class="active" onclick="showTab('upload-pictures')">Pending Approval</button>
                @endif
                <button id="medical-record-tab" onclick="showTab('medical-record')" @if(!$isApproved) disabled @endif>Medical Record</button>
                <button id="dental-record-tab" onclick="showTab('dental-record')" @if(!$isApproved) disabled @endif>Dental Record</button>
            </div>

            @if(!$isApproved && !$isPending)
            <div id="upload-pictures" class="tab-content active">
                <h1>Upload Health Examination Pictures</h1>
                <div class="form-container">
                    <form id="health-exam-form" method="POST" action="{{ route('student.health-examination.store') }}" enctype="multipart/form-data" onsubmit="return validateForm()">
                        @csrf
                        <div class="form-group">
                            <label for="pictures" class="button">Select Pictures (Health Exam, X-ray, Lab Result)</label>
                            <input type="file" id="pictures" name="pictures[]" accept="image/*" multiple required>
                        </div>
                        <div class="image-previews" id="image-previews"></div>
                        <div class="form-group">
                            <button type="submit" id="upload-btn" class="button">Upload Pictures</button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            @if($isPending)
            <div id="upload-pictures" class="tab-content active">
                <div class="status-message waiting">Pending for approval, please wait. Thank you!</div>
            </div>
            @endif

            @if($isApproved)
            <div id="medical-record" class="tab-content">
                @include('student.medical-record')
            </div>
            <div id="dental-record" class="tab-content">
                @include('student.dental-record')
            @endif

            <div id="previewModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <img id="modal-image" src="" alt="Image Preview">
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showTab(tabId) {
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(tabContent => {
                tabContent.classList.remove('active');
            });

            const selectedTabContent = document.getElementById(tabId);
            selectedTabContent.classList.add('active');

            const tabButtons = document.querySelectorAll('.tab-buttons button');
            tabButtons.forEach(button => {
                button.classList.remove('active');
            });

            document.getElementById(tabId + '-tab').classList.add('active');
        }

        @if(!$latestExamination || !$latestExamination->is_approved)
        showTab('upload-pictures');
        @else
        showTab('medical-record');
        @endif

        document.getElementById('pictures').addEventListener('change', function(event) {
            const files = event.target.files;
            const previewsContainer = document.getElementById('image-previews');
            previewsContainer.innerHTML = ''; 

            if (files.length !== 3) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please upload exactly three pictures: health exam, X-ray, and lab result.'
                });
                return;
            }

            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.addEventListener('click', function() {
                        openModal(e.target.result);
                    });
                    previewsContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });

            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Pictures selected successfully. Now you can upload them.'
            });
        });

        document.getElementById('health-exam-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch('{{ route('student.health-examination.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Health examination pictures uploaded successfully. Pending for approval, please wait. Thank you!'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error uploading the pictures.'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was an error uploading the pictures.'
                });
                console.error('Error:', error);
            });
        });

        function validateForm() {
            const picturesInput = document.getElementById('pictures');
            if (picturesInput.files.length !== 3) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please upload exactly three pictures: health exam, X-ray, and lab result.'
                });
                return false;
            }
            return true;
        }

        function openModal(src) {
            const modal = document.getElementById('previewModal');
            const modalImage = document.getElementById('modal-image');
            modalImage.src = src;
            modal.style.display = 'block';
        }

        function closeModal() {
            const modal = document.getElementById('previewModal');
            if (modal) {
                modal.style.display = 'none';
            }
        }

        document.getElementById('profile-picture-upload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('profile-picture-preview');
                if (preview) {
                    preview.src = e.target.result;
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Profile picture uploaded successfully.'
                    });
                }
            };
            reader.readAsDataURL(file);
        });
    </script>
</x-app-layout>
