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

        .form-group-inline {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .form-group-inline .form-group {
            flex: 1;
            min-width: calc(50% - 20px);
        }

        .profile-picture {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        .profile-picture img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            background-color: lightgray;
        }

        .profile-picture .button {
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

        .profile-picture .button:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        .profile-picture .button:active {
            transform: scale(0.95);
        }

        .dental-record-container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .teeth-chart {
            width: 100%;
            max-width: 600px;
            margin-top: 20px;
        }

        .dental-form {
            flex: 1;
            max-width: 600px;
        }

        .dental-form table {
            width: 100%;
            border-collapse: collapse;
        }

        .dental-form th,
        .dental-form td {
            padding: 5px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .checkbox-group label {
            display: block;
            margin-bottom: 5px;
        }

        .checkbox-group input {
            margin-right: 10px;
        }
        
        .health-history-content {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-in-out;
        }
        
        .health-history-item {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
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
                <button id="health-history-tab" onclick="showTab('health-history')" @if(!$isApproved) disabled @endif>Health History</button>
            </div>

            @if(!$isApproved && !$isPending)
            <div id="upload-pictures" class="tab-content active">
                <h1>Upload Health Examination Pictures</h1>
                <div class="form-container">
                    <form id="health-exam-form" method="POST" action="{{ route('student.health-examination.store') }}" enctype="multipart/form-data" onsubmit="return validateForm()">
                        @csrf
                        <div class="form-group">
                            <label for="pictures" class="button">Upload Pictures (Health Exam, X-ray, Lab Result)</label>
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
            <div id="medical-record" class="tab-content @if($isApproved) active @endif">
                <h1>Student Medical Record</h1>
                <div class="form-container">
                    <div class="profile-picture">
                        <img id="profile-picture-preview" src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture">
                        <label for="profile-picture-upload" class="button">Choose Profile Picture</label>
                        <input type="file" id="profile-picture-upload" name="profile_picture" accept="image/*">
                    </div>
                    <form method="POST" action="{{ route('student.medical-record.store') }}" enctype="multipart/form-data" id="medical-record-form">
                        @csrf
                        <div class="form-group-inline">
                            <div class="form-group">
                                <label for="name">Name <i class="fa-regular fa-user"></i></label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="birthdate">Birthdate <i class="fa-regular fa-calendar-alt"></i></label>
                                <input type="date" id="birthdate" name="birthdate" required>
                            </div>
                        </div>
                        <div class="form-group-inline">
                            <div class="form-group">
                                <label for="age">Age <i class="fa-regular fa-hourglass-half"></i></label>
                                <input type="number" id="age" name="age" required>
                            </div>
                            <div class="form-group">
                                <label for="address">Address <i class="fa-regular fa-map-marker-alt"></i></label>
                                <input type="text" id="address" name="address" required>
                            </div>
                        </div>
                        <div class="form-group-inline">
                            <div class="form-group">
                                <label for="personal-contact-number">Personal Contact Number <i class="fa-regular fa-phone"></i></label>
                                <input type="text" id="personal-contact-number" name="personal_contact_number" required>
                            </div>
                            <div class="form-group">
                                <label for="emergency-contact-number">Emergency Contact Number <i class="fa-regular fa-phone-alt"></i></label>
                                <input type="text" id="emergency-contact-number" name="emergency_contact_number" required>
                            </div>
                        </div>
                        <div class="form-group-inline">
                            <div class="form-group">
                                <label for="father-name">Father's Name/Legal Guardian <i class="fa-regular fa-user"></i></label>
                                <input type="text" id="father-name" name="father_name" required>
                            </div>
                            <div class="form-group">
                                <label for="mother-name">Mother's Name/Legal Guardian <i class="fa-regular fa-user"></i></label>
                                <input type="text" id="mother-name" name="mother_name" required>
                            </div>
                        </div>
                        <div class="form-group-inline">
                            <div class="form-group">
                                <label for="past-illness">Past Illness and Injuries <i class="fa-regular fa-notes-medical"></i></label>
                                <input type="text" id="past-illness" name="past_illness" required>
                            </div>
                            <div class="form-group">
                                <label for="chronic-conditions">Chronic Conditions <i class="fa-regular fa-heartbeat"></i></label>
                                <input type="text" id="chronic-conditions" name="chronic_conditions" required>
                            </div>
                        </div>
                        <div class="form-group-inline">
                            <div class="form-group">
                                <label for="surgical-history">Surgical History <i class="fa-regular fa-scalpel"></i></label>
                                <input type="text" id="surgical-history" name="surgical_history" required>
                            </div>
                            <div class="form-group">
                                <label for="family-medical-history">Family Medical History <i class="fa-regular fa-history"></i></label>
                                <input type="text" id="family-medical-history" name="family_medical_history" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="allergies">Allergies (specify) <i class="fa-regular fa-allergies"></i></label>
                            <input type="text" id="allergies" name="allergies" required>
                        </div>
                        <div class="form-section">
                            <h2>Medicines OK to give/apply at the clinic (check)</h2>
                            <div class="checkbox-group">
                                <label><input type="checkbox" name="medicines[]" value="Paracetamol"> Paracetamol</label>
                                <label><input type="checkbox" name="medicines[]" value="Ibuprofen"> Ibuprofen</label>
                                <label><input type="checkbox" name="medicines[]" value="Mefenamic Acid"> Mefenamic Acid</label>
                                <label><input type="checkbox" name="medicines[]" value="Citirizine/Loratadine"> Citirizine/Loratadine</label>
                                <label><input type="checkbox" name="medicines[]" value="Camphor + Menthol Liniment"> Camphor + Menthol Liniment</label>
                                <label><input type="checkbox" name="medicines[]" value="PPA"> PPA</label>
                                <label><input type="checkbox" name="medicines[]" value="Phenylephrine"> Phenylephrine</label>
                                <label><input type="checkbox" name="medicines[]" value="Antacid"> Antacid</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="button" class="button" onclick="temporarySave('medical')">Save </button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="dental-record" class="tab-content">
                <h1>Student Dental Record</h1>
                <div class="dental-record-container">
                    <div class="teeth-chart">
                        <img src="https://i0.wp.com/coreem.net/content/uploads/2019/10/Classification-of-Teeth.png?fit=676%2C722&ssl=1&fbclid=IwZXh0bgNhZW0CMTAAAR3sEwpCmV8_yU6M4DnI6wbG9pF6GKd2RXVnJ0nM90ukOQFrCFa15J2-0do_aem_mxPOrrPZUhH5Iw7RCWUNog" alt="Teeth Chart" style="width:100%;">
                    </div>
                    <form method="POST" action="{{ route('student.dental-record.store') }}" enctype="multipart/form-data" class="dental-form">
                        @csrf
                        <div class="form-group-inline">
                            <div class="form-group">
                                <label for="name">Name <i class="fa-regular fa-user"></i></label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="year-section">Year and Section <i class="fa-regular fa-calendar-alt"></i></label>
                                <input type="text" id="year-section" name="year_section" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="date">Date <i class="fa-regular fa-calendar-alt"></i></label>
                            <input type="date" id="date" name="date" required>
                        </div>
                        <div class="form-group">
                            <label for="diagnosis">Diagnosis <i class="fa-regular fa-notes-medical"></i></label>
                            <input type="text" id="diagnosis" name="diagnosis" required>
                        </div>
                        <div class="form-group">
                            <label for="recommendation">Recommendation <i class="fa-regular fa-receipt"></i></label>
                            <input type="text" id="recommendation" name="recommendation" required>
                        </div>
                        <div class="form-group">
                            <label for="prescription">Doctor's/Dentist's Prescription <i class="fa-regular fa-prescription"></i></label>
                            <textarea id="prescription" name="prescription" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="nurse-note">Nurse's Note <i class="fa-regular fa-notes-medical"></i></label>
                            <textarea id="nurse-note" name="nurse_note" required></textarea>
                        </div>
                        <div class="form-group-inline">
                            <div class="form-group">
                                <label for="upper-molars">Upper Molar Right First <i class="fa fa-tooth"></i></label>
                                <select id="upper-molars" name="upper_molars[]" required>
                                    <option value="healthy">Healthy</option>
                                    <option value="cavity">Cavity</option>
                                    <option value="missing">Missing</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="upper-molars-2">Upper Molar Right Second <i class="fa fa-tooth"></i></label>
                                <select id="upper-molars-2" name="upper_molars[]" required>
                                    <option value="healthy">Healthy</option>
                                    <option value="cavity">Cavity</option>
                                    <option value="missing">Missing</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="upper-molars-3">Upper Molar Right Third <i class="fa fa-tooth"></i></label>
                                <select id="upper-molars-3" name="upper_molars[]" required>
                                    <option value="healthy">Healthy</option>
                                    <option value="cavity">Cavity</option>
                                    <option value="missing">Missing</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group-inline">
                            <div class="form-group">
                                <label for="upper-premolars">Upper Premolar Right First <i class="fa fa-tooth"></i></label>
                                <select id="upper-premolars" name="upper_premolars[]" required>
                                    <option value="healthy">Healthy</option>
                                    <option value="cavity">Cavity</option>
                                    <option value="missing">Missing</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="upper-premolars-2">Upper Premolar Right Second <i class="fa fa-tooth"></i></label>
                                <select id="upper-premolars-2" name="upper_premolars[]" required>
                                    <option value="healthy">Healthy</option>
                                    <option value="cavity">Cavity</option>
                                    <option value="missing">Missing</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group-inline">
                            <div class="form-group">
                                <label for="upper-canine">Upper Canine Right <i class="fa fa-tooth"></i></label>
                                <select id="upper-canine" name="upper_canine[]" required>
                                    <option value="healthy">Healthy</option>
                                    <option value="cavity">Cavity</option>
                                    <option value="missing">Missing</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group-inline">
                            <div class="form-group">
                                <label for="upper-incisors">Upper Incisor Right First <i class="fa fa-tooth"></i></label>
                                <select id="upper-incisors" name="upper_incisors[]" required>
                                    <option value="healthy">Healthy</option>
                                    <option value="cavity">Cavity</option>
                                    <option value="missing">Missing</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="upper-incisors-2">Upper Incisor Right Second <i class="fa fa-tooth"></i></label>
                                <select id="upper-incisors-2" name="upper_incisors[]" required>
                                    <option value="healthy">Healthy</option>
                                    <option value="cavity">Cavity</option>
                                    <option value="missing">Missing</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group-inline">
                            <div class="form-group">
                                <label for="lower-molars">Lower Molar Right First <i class="fa fa-tooth"></i></label>
                                <select id="lower-molars" name="lower_molars[]" required>
                                    <option value="healthy">Healthy</option>
                                    <option value="cavity">Cavity</option>
                                    <option value="missing">Missing</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="lower-molars-2">Lower Molar Right Second <i class="fa fa-tooth"></i></label>
                                <select id="lower-molars-2" name="lower_molars[]" required>
                                    <option value="healthy">Healthy</option>
                                    <option value="cavity">Cavity</option>
                                    <option value="missing">Missing</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="lower-molars-3">Lower Molar Right Third <i class="fa fa-tooth"></i></label>
                                <select id="lower-molars-3" name="lower_molars[]" required>
                                    <option value="healthy">Healthy</option>
                                    <option value="cavity">Cavity</option>
                                    <option value="missing">Missing</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group-inline">
                            <div class="form-group">
                                <label for="lower-premolars">Lower Premolar Right First <i class="fa fa-tooth"></i></label>
                                <select id="lower-premolars" name="lower_premolars[]" required>
                                    <option value="healthy">Healthy</option>
                                    <option value="cavity">Cavity</option>
                                    <option value="missing">Missing</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="lower-premolars-2">Lower Premolar Right Second <i class="fa fa-tooth"></i></label>
                                <select id="lower-premolars-2" name="lower_premolars[]" required>
                                    <option value="healthy">Healthy</option>
                                    <option value="cavity">Cavity</option>
                                    <option value="missing">Missing</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group-inline">
                            <div class="form-group">
                                <label for="lower-canine">Lower Canine Right <i class="fa fa-tooth"></i></label>
                                <select id="lower-canine" name="lower_canine[]" required>
                                    <option value="healthy">Healthy</option>
                                    <option value="cavity">Cavity</option>
                                    <option value="missing">Missing</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group-inline">
                            <div class="form-group">
                                <label for="lower-incisors">Lower Incisor Right First <i class="fa fa-tooth"></i></label>
                                <select id="lower-incisors" name="lower_incisors[]" required>
                                    <option value="healthy">Healthy</option>
                                    <option value="cavity">Cavity</option>
                                    <option value="missing">Missing</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="lower-incisors-2">Lower Incisor Right Second <i class="fa fa-tooth"></i></label>
                                <select id="lower-incisors-2" name="lower_incisors[]" required>
                                    <option value="healthy">Healthy</option>
                                    <option value="cavity">Cavity</option>
                                    <option value="missing">Missing</option>
                                </select>
                            </div>
                        </div>
                        <!-- Repeat the above pattern for all 32 teeth -->
                        <div class="form-group">
                            <button type="submit" class="button">Submit</button>
                            <button type="button" class="button" onclick="temporarySave('dental')">Save Temporarily</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="health-history" class="tab-content">
                <h1>Health History</h1>
                <div class="health-history-content" id="health-history-content">
                    <!-- Health history items will be dynamically added here -->
                </div>
            </div>
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

        function temporarySave(recordType) {
            let formData = new FormData();
            if (recordType === 'medical') {
                formData = new FormData(document.getElementById('medical-record-form'));
            } else if (recordType === 'dental') {
                formData = new FormData(document.getElementById('dental-form'));
            }

            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            const healthHistoryContent = document.getElementById('health-history-content');
            const historyItem = document.createElement('div');
            historyItem.className = 'health-history-item';

            for (const [key, value] of Object.entries(data)) {
                const p = document.createElement('p');
                p.textContent = `${key}: ${value}`;
                historyItem.appendChild(p);
            }

            healthHistoryContent.appendChild(historyItem);

            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: `${recordType.charAt(0).toUpperCase() + recordType.slice(1)} record saved temporarily.`
            });
        }
    </script>
</x-app-layout>
