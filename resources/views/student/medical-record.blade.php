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
            font-size: 16px;
        }

        .form-group button,
        .form-group label.button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            margin-top: 10px;
            text-align: center;
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

        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
        }

        .checkbox-group label {
            flex: 1 1 45%;
            margin: 5px 0;
        }

        .toggle-exam {
            margin-top: 20px;
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .toggle-exam:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        .toggle-exam:active {
            transform: scale(0.95);
        }

        .exam-container,
        .xray-container,
        .lab-exam-container {
            display: none;
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-in-out;
        }

        .table-container {
            margin-top: 20px;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table-container th,
        .table-container td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table-container th {
            background-color: #f2f2f2;
        }

        .teeth-icons {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .teeth-icon {
            width: 50px;
            height: 50px;
            background-color: #ddd;
            border-radius: 5px;
            margin: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .teeth-icon img {
            width: 100%;
            height: auto;
        }

        .icon {
            display: inline-block;
            width: 24px;
            height: 24px;
            margin-right: 8px;
            vertical-align: middle;
        }

        .form-group-inline {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-group-inline .form-group {
            flex: 1;
            margin-right: 10px;
        }

        .form-group-inline .form-group:last-child {
            margin-right: 0;
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

        .profile-picture {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .profile-picture img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            display: none;
        }

        .profile-picture input[type="file"] {
            display: none;
        }

        .profile-picture label {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .profile-picture label:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        .profile-picture label:active {
            transform: scale(0.95);
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
    </style>

    <div class="container">
        <main class="main-content">
            <div class="tab-buttons">
                <button id="medical-record-tab" class="active" onclick="showTab('medical-record')">Medical Record</button>
                <button id="dental-record-tab" onclick="showTab('dental-record')">Dental Record</button>
            </div>

            <div id="medical-record" class="tab-content active">
                <h1>Student Medical Record</h1>
                @php
                    $latestExamination = Auth::user()->healthExaminations->last();
                @endphp
                @if($latestExamination && $latestExamination->is_approved)
                    <div class="form-container">
                        <form method="POST" action="{{ route('student.medical-record.store') }}" enctype="multipart/form-data" onsubmit="return validateForm()">
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
                                    <label for="medical-illness">Pertinent Medical Illness <i class="fa-regular fa-notes-medical"></i></label>
                                    <input type="text" id="medical-illness" name="medical_illness" required>
                                </div>
                                <div class="form-group">
                                    <label for="allergies">Allergies (specify) <i class="fa-regular fa-allergies"></i></label>
                                    <input type="text" id="allergies" name="allergies" required>
                                </div>
                            </div>
                            <div class="form-group-inline">
                                <div class="form-group">
                                    <label for="pediatrician">Pediatrician <i class="fa-regular fa-stethoscope"></i></label>
                                    <input type="text" id="pediatrician" name="pediatrician" required>
                                </div>
                                <div class="form-group profile-picture">
                                    <label for="picture" class="button"><i class="fa-regular fa-image"></i> Upload Picture</label>
                                    <input type="file" id="picture" name="picture" accept="image/*" required>
                                    <img id="picture-preview" alt="Picture Preview">
                                </div>
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

                            <div class="form-section">
                                <h2>Physical Examination</h2>
                                <div class="table-container">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Section</th>
                                                <th>Height</th>
                                                <th>Weight</th>
                                                <th>Vision</th>
                                                <th>Remarks</th>
                                                <th>MD's Signature</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Dynamic rows can be added here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="form-section">
                                <h2>Consent</h2>
                                <div class="form-group">
                                    <p>
                                        I, ____________________________, parent/legal guardian of ____________________________, authorize the health professional to administer medication and do medical treatment as needed. In case of emergency, the patient will be transported to the nearest hospital (Brent Hospital).
                                    </p>
                                    <div class="form-group-inline">
                                        <div class="form-group">
                                            <label for="consent-date">Date <i class="fa-regular fa-calendar"></i></label>
                                            <input type="date" id="consent-date" name="consent_date" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="signature">Signature over printed name <i class="fa-regular fa-signature"></i></label>
                                            <input type="text" id="signature" name="signature" required>
                                        </div>
                                    </div>
                                    <div class="form-group-inline">
                                        <div class="form-group">
                                            <label for="contact-no">Contact No: <i class="fa-regular fa-phone"></i></label>
                                            <input type="tel" id="contact-no" name="contact_no" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit">Submit</button>
                            </div>
                        </form>
                    </div>

                    <button class="toggle-exam" onclick="toggleExam()">Toggle Health Examination and Local History</button>
                    <div class="exam-container" id="exam-container">
                        <h2>Health Examination and Local History</h2>
                        <form method="POST" action="{{ route('student.health-examination.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group-inline">
                                <div class="form-group">
                                    <label for="name">Name <i class="fa-regular fa-user"></i></label>
                                    <input type="text" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="birthdate">Date of Birth <i class="fa-regular fa-calendar"></i></label>
                                    <input type="date" id="birthdate" name="birthdate" required>
                                </div>
                            </div>
                            <div class="form-group-inline">
                                <div class="form-group">
                                    <label for="address">Address <i class="fa-regular fa-map-marker-alt"></i></label>
                                    <input type="text" id="address" name="address" required>
                                </div>
                                <div class="form-group">
                                    <label for="civil-status">Civil Status <i class="fa-regular fa-heart"></i></label>
                                    <input type="text" id="civil-status" name="civil_status" required>
                                </div>
                            </div>
                            <div class="form-group-inline">
                                <div class="form-group">
                                    <label for="sex">Sex <i class="fa-regular fa-venus-mars"></i></label>
                                    <input type="text" id="sex" name="sex" required>
                                </div>
                                <div class="form-group">
                                    <label for="type-of-work">Type of Work <i class="fa-regular fa-briefcase"></i></label>
                                    <input type="text" id="type-of-work" name="type_of_work" required>
                                </div>
                            </div>
                            <div class="form-group-inline">
                                <div class="form-group">
                                    <label for="date-of-examination">Date of Examination <i class="fa-regular fa-calendar"></i></label>
                                    <input type="date" id="date-of-examination" name="date_of_examination" required>
                                </div>
                                <div class="form-group">
                                    <label for="height">Height <i class="fa-regular fa-ruler-vertical"></i></label>
                                    <input type="text" id="height" name="height" required>
                                </div>
                            </div>
                            <div class="form-group-inline">
                                <div class="form-group">
                                    <label for="weight">Weight <i class="fa-regular fa-weight"></i></label>
                                    <input type="text" id="weight" name="weight" required>
                                </div>
                              
<div class="form-group">
                                    <label for="respiratory-system">Respiratory System <i class="fa-regular fa-lungs"></i></label>
                                    <input type="text" id="respiratory-system" name="respiratory_system" required>
                                </div>
                                <div class="form-group">
                                    <label for="circulatory-system">Circulatory System <i class="fa-regular fa-heartbeat"></i></label>
                                    <input type="text" id="circulatory-system" name="circulatory_system" required>
                                </div>
                                <div class="form-group">
                                    <label for="blood-pressure">Blood Pressure <i class="fa-regular fa-heartbeat"></i></label>
                                    <input type="text" id="blood-pressure" name="blood_pressure" required>
                                </div>
                                <div class="form-group">
                                    <label for="pulse">Pulse <i class="fa-regular fa-heartbeat"></i></label>
                                    <input type="text" id="pulse" name="pulse" required>
                                </div>
                                <div class="form-group">
                                    <label for="agility-test">Agility Test <i class="fa-regular fa-running"></i></label>
                                    <input type="text" id="agility-test" name="agility_test" required>
                                </div>
                                <div class="form-group">
                                    <label for="digestive-system">Digestive System <i class="fa-regular fa-stomach"></i></label>
                                    <input type="text" id="digestive-system" name="digestive_system" required>
                                </div>
                                <div class="form-group">
                                    <label for="genito-urinary">Genito Urinary <i class="fa-regular fa-procedures"></i></label>
                                    <input type="text" id="genito-urinary" name="genito_urinary" required>
                                </div>
                                <div class="form-group">
                                    <label for="urinalysis">Urinalysis <i class="fa-regular fa-flask"></i></label>
                                    <input type="text" id="urinalysis" name="urinalysis" required>
                                </div>
                                <div class="form-group">
                                    <label for="skin">Skin <i class="fa-regular fa-skin"></i></label>
                                    <input type="text" id="skin" name="skin" required>
                                </div>
                                <div class="form-group">
                                    <label for="locomotor">Locomotor <i class="fa-regular fa-walking"></i></label>
                                    <input type="text" id="locomotor" name="locomotor" required>
                                </div>
                                <div class="form-group">
                                    <label for="nervous-system">Nervous System <i class="fa-regular fa-brain"></i></label>
                                    <input type="text" id="nervous-system" name="nervous_system" required>
                                </div>
                                <div class="form-group">
                                    <label for="eyes">Eyes <i class="fa-regular fa-eye"></i></label>
                                    <input type="text" id="eyes" name="eyes" required>
                                </div>
                                <div class="form-group">
                                    <label for="color-perception">Color Perception <i class="fa-regular fa-palette"></i></label>
                                    <input type="text" id="color-perception" name="color_perception" required>
                                </div>
                                <div class="form-group">
                                    <label for="vision">Vision <i class="fa-regular fa-glasses"></i></label>
                                    <input type="text" id="vision" name="vision" required>
                                </div>
                                <div class="form-group">
                                    <label for="hearing">Hearing <i class="fa-regular fa-deaf"></i></label>
                                    <input type="text" id="hearing" name="hearing" required>
                                </div>
                                <div class="form-group">
                                    <label for="nose">Nose <i class="fa-regular fa-nose"></i></label>
                                    <input type="text" id="nose" name="nose" required>
                                </div>
                                <div class="form-group">
                                    <label for="throat">Throat <i class="fa-regular fa-throat"></i></label>
                                    <input type="text" id="throat" name="throat" required>
                                </div>
                                <div class="form-group">
                                    <label for="tooth-and-gum">Tooth and Gum <i class="fa-regular fa-teeth"></i></label>
                                    <input type="text" id="tooth-and-gum" name="tooth_and_gum" required>
                                </div>
                                <div class="form-group">
                                    <label for="immunization">Immunization <i class="fa-regular fa-syringe"></i></label>
                                    <input type="text" id="immunization" name="immunization" required>
                                </div>
                                <div class="form-group">
                                    <label for="remarks">Remarks <i class="fa-regular fa-clipboard"></i></label>
                                    <input type="text" id="remarks" name="remarks" required>
                                </div>
                                <div class="form-group">
                                    <label for="x-ray">X-ray / Flouroscopy <i class="fa-regular fa-x-ray"></i></label>
                                    <input type="text" id="x-ray" name="x_ray" required>
                                </div>
                                <div class="form-group">
                                    <label for="temperature">Temperature <i class="fa-regular fa-temperature-high"></i></label>
                                    <input type="text" id="temperature" name="temperature" required>
                                </div>
                                <div class="form-group">
                                    <label for="recommendation">Recommendation <i class="fa-regular fa-comment-medical"></i></label>
                                    <input type="text" id="recommendation" name="recommendation" required>
                                </div>
                                <div class="form-group-inline">
                                    <div class="form-group">
                                        <label for="employee-signature">Employee's Signature <i class="fa-regular fa-signature"></i></label>
                                        <input type="text" id="employee-signature" name="employee_signature" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="physician-signature">Physician's Signature <i class="fa-regular fa-signature"></i></label>
                                        <input type="text" id="physician-signature" name="physician_signature" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit">Save Examination</button>
                                </div>
                            </form>
                        </div>
                    @else
                        <p>Please upload a health examination picture to proceed with the dental record form.</p>
                    @endif
                </div>
            </main>
        </div>

        <script>
            function toggleExam() {
                const examContainer = document.getElementById('exam-container');
                if (examContainer.style.display === 'none' || examContainer.style.display === '') {
                    examContainer.style.display = 'block';
                } else {
                    examContainer.style.display = 'none';
                }
            }

            function toggleDentalExam() {
                const dentalExamContainer = document.getElementById('dental-exam-container');
                if (dentalExamContainer.style.display === 'none' || dentalExamContainer.style.display === '') {
                    dentalExamContainer.style.display = 'block';
                } else {
                    dentalExamContainer.style.display = 'none';
                }
            }

            function toggleXray() {
                const xrayContainer = document.getElementById('xray-container');
                if (xrayContainer.style.display === 'none' || xrayContainer.style.display === '') {
                    xrayContainer.style.display = 'block';
                } else {
                    xrayContainer.style.display = 'none';
                }
            }

            function toggleLabExam() {
                const labExamContainer = document.getElementById('lab-exam-container');
                if (labExamContainer.style.display === 'none' || labExamContainer.style.display === '') {
                    labExamContainer.style.display = 'block';
                } else {
                    labExamContainer.style.display = 'none';
                }
            }

            function showTab(tabId) {
                // Hide all tab content
                const tabContents = document.querySelectorAll('.tab-content');
                tabContents.forEach(tabContent => {
                    tabContent.classList.remove('active');
                });

                // Show the selected tab content
                const selectedTabContent = document.getElementById(tabId);
                selectedTabContent.classList.add('active');

                // Update tab buttons
                const tabButtons = document.querySelectorAll('.tab-buttons button');
                tabButtons.forEach(button => {
                    button.classList.remove('active');
                });

                document.getElementById(tabId + '-tab').classList.add('active');
            }

            // Initialize by showing the medical record tab
            showTab('medical-record');

            // Preview uploaded image
            document.getElementById('picture').addEventListener('change', function(event) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('picture-preview');
                    img.src = e.target.result;
                    img.style.display = 'block';
                };
                reader.readAsDataURL(event.target.files[0]);
            });

            document.getElementById('health_examination_picture').addEventListener('change', function(event) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('health-exam-picture-preview');
                    img.src = e.target.result;
                    img.style.display = 'block';
                };
                reader.readAsDataURL(event.target.files[0]);
            });

            // AJAX form submission for health examination picture upload
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
                        document.getElementById('status-message').style.display = 'block';
                        document.getElementById('status-message').textContent = 'Waiting for approval...';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });

            // Validate form to ensure picture is uploaded
            function validateForm() {
                const pictureInput = document.getElementById('picture');
                if (!pictureInput.value) {
                    alert('Please upload a profile picture before submitting.');
                    return false;
                }
                return true;
            }
        </script>
    </x-app-layout>
