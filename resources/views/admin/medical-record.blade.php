<x-app-layout>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        
        .container {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }
        .sidebar:hover .menu-text {
            opacity: 1;
        }

        .main-content {
            margin-left: 80px;
            margin-top: 20px;
            width: calc(100% - 80px);
            padding: 20px;
            transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
        }

        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .tab-buttons {
            display: flex;
            gap: 20px;
        }

        .tab-buttons button {
            padding: 10px 20px;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 1rem;
            transition: color 0.3s, border-bottom 0.3s;
        }

        .tab-buttons button.active {
            color: #007bff;
            border-bottom: 2px solid #007bff;
        }

        .forms-container {
            display: flex;
            gap: 20px;
            width: 90%;
            height: 80%;
            margin: 0 auto;
        }

        .form-container {
            flex: 1;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-in-out;
            overflow-y: auto;
            max-height: 70vh;
        }

        .form-container-gray {
            flex: 1;
            background-color: #d3d3d3;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-in-out;
            overflow-y: auto;
            max-height: 70vh;
        }

        .profile-picture {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .profile-picture img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .profile-picture button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .profile-picture button:hover {
            background-color: #0056b3;
        }

        .profile-picture input[type="file"] {
            display: none;
        }

        .form-group-inline {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group img {
            margin-right: 10px;
        }

        .form-group input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-section {
            margin-top: 20px;
        }

        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .checkbox-group label {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .form-group button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 50%; /* Set button width to 50% */
            margin-left: 170px;
            margin-top: 20px;

        }

        .form-group button:hover {
            background-color: #0056b3;
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

        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .history-table th, .history-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .history-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-bar input[type="text"] {
            width: 400px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .search-bar button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
            transition: background-color 0.3s;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }
     
    </style>

    <div class="main-content">
        <div class="tabs">
            <div class="tab-buttons">
                <button id="medical-tab" class="active" onclick="showTab('medical')">Medical Record</button>
                <button id="history-tab" onclick="showTab('history')">Health History</button>
            </div>
        </div>

        <div class="search-bar">
            <input type="text" placeholder="Search Records..." id="search-input">
            <button id="search-button">Search</button>
        </div>

        <div id="medical" class="forms-container">
            <div class="form-container-gray">
                <div class="profile-picture">
                    <img id="profile-picture-preview" src="{{ asset('images/pilarLogo.jpg') }}" alt="Profile Picture">
                    <button id="profile-picture-button" class="button">Choose Profile Picture</button>
                    <input type="file" id="profile-picture-upload" name="profile_picture" accept="image/*">
                </div>
                <form method="POST" action="{{ route('student.medical-record.store') }}" enctype="multipart/form-data" id="medical-record-form">
                    @csrf
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="name"><img src="https://img.icons8.com/ios-filled/50/000000/name.png" alt="name icon" width="20"> Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="birthdate"><img src="https://img.icons8.com/ios-filled/50/000000/calendar.png" alt="calendar icon" width="20"> Birthdate</label>
                            <input type="date" id="birthdate" name="birthdate" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="age"><img src="https://img.icons8.com/ios-filled/50/000000/hourglass.png" alt="hourglass icon" width="20"> Age</label>
                            <input type="number" id="age" name="age" required>
                        </div>
                        <div class="form-group">
                            <label for="address"><img src="https://img.icons8.com/ios-filled/50/000000/address.png" alt="address icon" width="20"> Address</label>
                            <input type="text" id="address" name="address" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="personal-contact-number"><img src="https://img.icons8.com/ios-filled/50/000000/phone.png" alt="phone icon" width="20"> Personal Contact Number</label>
                            <input type="text" id="personal-contact-number" name="personal_contact_number" required>
                        </div>
                        <div class="form-group">
                            <label for="emergency-contact-number"><img src="https://img.icons8.com/ios-filled/50/000000/phone.png" alt="phone icon" width="20"> Emergency Contact Number</label>
                            <input type="text" id="emergency-contact-number" name="emergency_contact_number" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="father-name"><img src="https://img.icons8.com/ios-filled/50/000000/father.png" alt="father icon" width="20"> Father's Name/Legal Guardian</label>
                            <input type="text" id="father-name" name="father_name" required>
                        </div>
                        <div class="form-group">
                            <label for="mother-name"><img src="https://img.icons8.com/ios-filled/50/000000/mother.png" alt="mother icon" width="20"> Mother's Name/Legal Guardian</label>
                            <input type="text" id="mother-name" name="mother_name" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="past-illness"><img src="https://img.icons8.com/ios-filled/50/000000/medical-history.png" alt="medical history icon" width="20"> Past Illness and Injuries</label>
                            <input type="text" id="past-illness" name="past_illness" required>
                        </div>
                        <div class="form-group">
                            <label for="chronic-conditions"><img src="https://img.icons8.com/ios-filled/50/000000/heart-with-pulse.png" alt="heart icon" width="20"> Chronic Conditions</label>
                            <input type="text" id="chronic-conditions" name="chronic_conditions" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="surgical-history"><img src="https://img.icons8.com/ios-filled/50/000000/scalpel.png" alt="scalpel icon" width="20"> Surgical History</label>
                            <input type="text" id="surgical-history" name="surgical_history" required>
                        </div>
                        <div class="form-group">
                            <label for="family-medical-history"><img src="https://img.icons8.com/ios-filled/50/000000/family.png" alt="family icon" width="20"> Family Medical History</label>
                            <input type="text" id="family-medical-history" name="family_medical_history" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="allergies"><img src="https://img.icons8.com/?size=100&id=VRsLjkBqVGWW&format=png&color=000000" alt="allergies icon" width="20"> Allergies (specify)</label>
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
                        <button type="button" class="button" onclick="temporarySave('medical')">Save</button>
                    </div>
                </form>
            </div>

            <div class="form-container-gray">
                <form method="POST" action="" id="physical-examination-form">
                    @csrf
                    <h2>Physical Examination</h2>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="height"><img src="https://img.icons8.com/ios-filled/50/000000/height.png" alt="height icon" width="20"> Height (cm)</label>
                            <input type="text" id="height" name="height" required>
                        </div>
                        <div class="form-group">
                            <label for="weight"><img src="https://img.icons8.com/ios-filled/50/000000/weight.png" alt="weight icon" width="20"> Weight (kg)</label>
                                
                            <input type="text" id="weight" name="weight" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="vision"><img src="https://img.icons8.com/?size=100&id=986&format=png&color=000000" alt="eye icon" width="20"> Vision</label>
                            <input type="text" id="vision" name="vision" required>
                        </div>
                        <div class="form-group">
                            <label for="medicine-intake"><img src="https://img.icons8.com/?size=100&id=9537&format=png&color=000000" alt="medicine icon" width="20"> Medicine Intake</label>
                                <input type="text" id="medicine-intake" name="medicine-intake" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="remarks"><img src="https://img.icons8.com/ios-filled/50/000000/note.png" alt="note icon" width="20"> Remarks</label>
                            <textarea id="remarks" name="remarks" rows="5" style="width: 99%;"></textarea>
                            </div>
                    <div class="form-group">
                        <button type="button" class="button" onclick="approveSignature()">MD's Signature</button>
                    </div>
                    <div class="form-group">
                        <button type="button" class="button" onclick="temporarySave('physical')">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="history" class="hidden forms-container">
            <div class="form-container">
                <h2>Medical Record History</h2>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Contact Number</th>
                            <th>Illness</th>
                            <th>Surgery</th>
                        </tr>
                    </thead>
                    <tbody id="medical-record-history-body">
                        <!-- Dummy data -->
                        <tr>
                            <td>John Doe</td>
                            <td>25</td>
                            <td>1234567890</td>
                            <td>Flu</td>
                            <td>Appendectomy</td>
                        </tr>
                        <tr>
                            <td>Jane Smith</td>
                            <td>30</td>
                            <td>0987654321</td>
                            <td>Asthma</td>
                            <td>None</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="form-container">
                <h2>Physical Examination History</h2>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Height</th>
                            <th>Weight</th>
                            <th>Vision</th>
                            <th>Medicine Intake</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody id="physical-examination-history-body">
                        <!-- Dummy data -->
                        <tr>
                            <td>150 cm</td>
                            <td>50 kg</td>
                            <td>20/20</td>
                            <td>No</td>
                            <td>Healthy</td>
                        </tr>
                        <tr>
                            <td>160 cm</td>
                            <td>55 kg</td>
                            <td>20/20</td>
                            <td>Yes</td>
                            <td>Healthy</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showTab(tabId) {
            const tabs = ['medical', 'history'];
            tabs.forEach(tab => {
                document.getElementById(tab).classList.add('hidden');
                document.getElementById(tab + '-tab').classList.remove('active');
            });
            document.getElementById(tabId).classList.remove('hidden');
            document.getElementById(tabId + '-tab').classList.add('active');
        }

        document.getElementById('profile-picture-upload').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-picture-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);

                // Show SweetAlert confirmation
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Picture Updated',
                    text: 'Your profile picture has been successfully updated.',
                });
            }
        });

        document.getElementById('profile-picture-button').addEventListener('click', function() {
            document.getElementById('profile-picture-upload').click();
        });

        function temporarySave(formType) {
            const medicalRecordForm = document.getElementById('medical-record-form');
            const physicalExaminationForm = document.getElementById('physical-examination-form');

            const formData = new FormData(
                formType === 'medical' ? medicalRecordForm : physicalExaminationForm
            );

            if (formType === 'medical') {
                const tbody = document.getElementById('medical-record-history-body');
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${formData.get('name')}</td>
                    <td>${formData.get('age')}</td>
                    <td>${formData.get('personal_contact_number')}</td>
                    <td>${formData.get('past_illness')}</td>
                    <td>${formData.get('surgical_history')}</td>
                `;
                tbody.appendChild(tr);
            } else {
                const tbody = document.getElementById('physical-examination-history-body');
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${formData.get('height')}</td>
                    <td>${formData.get('weight')}</td>
                    <td>${formData.get('vision')}</td>
                    <td>${formData.get('medicine_intake')}</td>
                    <td>${formData.get('remarks')}</td>
                `;
                tbody.appendChild(tr);
            }

            // Show SweetAlert confirmation
            Swal.fire({
                icon: 'success',
                title: 'Temporary Save',
                text: 'The form data has been temporarily saved.',
            });
        }

        function approveSignature() {
            Swal.fire({
                icon: 'success',
                title: 'Signature Approved',
                text: 'MD\'s signature has been approved.',
            });
        }

        document.getElementById('search-button').addEventListener('click', function() {
            const query = document.getElementById('search-input').value;
            alert(`Search triggered for: ${query}`);
        });
    </script>
</x-app-layout>
