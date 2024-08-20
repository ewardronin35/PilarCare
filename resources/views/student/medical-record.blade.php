<x-app-layout>
    <style>
        .container {
            display: flex;
            min-height: 100vh;
            padding: 20px;
        }

        .forms-container {
            display: flex;
            gap: 30px;
            margin-top: 50px;
            width: 95%;
        }

        .form-container,
        .history-container {
            flex: 1;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            max-height: 80vh;
            background-color: #f0f4f8; /* Light gray background for the entire page */S
        }

       

       

        .profile-picture {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
        }

        .profile-picture img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
            border: 3px solid #007bff; /* Blue border for profile picture */
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

        .hidden-input {
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
            color: #333; /* Dark text color for labels */
        }

        .form-group img {
            margin-right: 10px;
        }

        .form-group input,
        .form-group select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9; /* Light gray input background */
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #007bff; /* Blue border on focus */
        }

        .form-section {
            margin-top: 20px;
        }

        .form-section h2 {
            color: #007bff; /* Blue section header */
            border-bottom: 2px solid #007bff; /* Blue underline */
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .form-group button {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            margin-top: 20px;
        }

        .form-group button:hover {
            background-color: #218838;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #f8f9fa; /* Light background for the table */
        }

        .history-table th,
        .history-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .history-table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .history-table tr:nth-child(even) {
            background-color: #f2f2f2; /* Alternate row color */
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

<div class="main-content">
        <div class="forms-container">
            <!-- Medical Record Form -->
            <div class="form-container">
                <div class="profile-picture">
                    <img id="profile-picture-preview" src="{{ $information->profile_picture ? asset('storage/' . $information->profile_picture) : asset('images/pilarLogo.jpg') }}" alt="Profile Picture">
                    <button id="profile-picture-button" class="button">Choose Profile Picture</button>
                    <input type="file" id="profile-picture-upload" name="profile_picture" accept="image/*" class="hidden-input">
                </div>
                <form method="POST" action="{{ route('student.medical-record.store') }}" enctype="multipart/form-data" id="medical-record-form">
                    @csrf
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="name"><img src="https://img.icons8.com/ios-filled/50/000000/name.png" alt="name icon" width="20"> Name</label>
                            <input type="text" id="name" name="name" value="{{ $name }}" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="birthdate"><img src="https://img.icons8.com/ios-filled/50/000000/calendar.png" alt="calendar icon" width="20"> Birthdate</label>
                            <input type="date" id="birthdate" name="birthdate" value="{{ $information->birthdate ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="age"><img src="https://img.icons8.com/ios-filled/50/000000/hourglass.png" alt="hourglass icon" width="20"> Age</label>
                            <input type="number" id="age" name="age" value="{{ $age }}" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="address"><img src="https://img.icons8.com/ios-filled/50/000000/address.png" alt="address icon" width="20"> Address</label>
                            <input type="text" id="address" name="address" value="{{ $information->address ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="personal-contact-number"><img src="https://img.icons8.com/ios-filled/50/000000/phone.png" alt="phone icon" width="20"> Personal Contact Number</label>
                            <input type="text" id="personal-contact-number" name="personal_contact_number" value="{{ $information->personal_contact_number ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="emergency-contact-number"><img src="https://img.icons8.com/ios-filled/50/000000/phone.png" alt="phone icon" width="20"> Emergency Contact Number</label>
                            <input type="text" id="emergency-contact-number" name="emergency_contact_number" value="{{ $information->emergency_contact_number ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="father-name"><img src="https://img.icons8.com/ios-filled/50/000000/father.png" alt="father icon" width="20"> Father's Name/Legal Guardian</label>
                            <input type="text" id="father-name" name="father_name" value="{{ $information->parent_name_father ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="mother-name"><img src="https://img.icons8.com/ios-filled/50/000000/mother.png" alt="mother icon" width="20"> Mother's Name/Legal Guardian</label>
                            <input type="text" id="mother-name" name="mother_name" value="{{ $information->parent_name_mother ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="past-illness"><img src="https://img.icons8.com/ios-filled/50/000000/medical-history.png" alt="medical history icon" width="20"> Past Illness and Injuries</label>
                            <input type="text" id="past-illness" name="past_illness" value="{{ $information->medical_history ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="chronic-conditions"><img src="https://img.icons8.com/ios-filled/50/000000/heart-with-pulse.png" alt="heart icon" width="20"> Chronic Conditions</label>
                            <input type="text" id="chronic-conditions" name="chronic_conditions" value="{{ $information->chronic_conditions ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="surgical-history"><img src="https://img.icons8.com/ios-filled/50/000000/scalpel.png" alt="scalpel icon" width="20"> Surgical History</label>
                            <input type="text" id="surgical-history" name="surgical_history" value="{{ $information->surgical_history ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="family-medical-history"><img src="https://img.icons8.com/ios-filled/50/000000/family.png" alt="family icon" width="20"> Family Medical History</label>
                            <input type="text" id="family-medical-history" name="family_medical_history" value="{{ $information->family_medical_history ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="allergies"><img src="https://img.icons8.com/?size=100&id=VRsLjkBqVGWW&format=png&color=000000" alt="allergies icon" width="20"> Allergies (specify)</label>
                        <input type="text" id="allergies" name="allergies" value="{{ $information->allergies ?? '' }}" required>
                    </div>
                    <div class="form-section">
                        <h2>Medicines OK to give/apply at the clinic (select all that apply)</h2>
                        <div class="form-group">
                            <select id="medicines" name="medicines[]" multiple class="form-control" required>
                                <option value="Paracetamol" @if(in_array('Paracetamol', explode(',', $information->medicines ?? ''))) selected @endif>Paracetamol</option>
                                <option value="Ibuprofen" @if(in_array('Ibuprofen', explode(',', $information->medicines ?? ''))) selected @endif>Ibuprofen</option>
                                <option value="Mefenamic Acid" @if(in_array('Mefenamic Acid', explode(',', $information->medicines ?? ''))) selected @endif>Mefenamic Acid</option>
                                <option value="Citirizine/Loratadine" @if(in_array('Citirizine/Loratadine', explode(',', $information->medicines ?? ''))) selected @endif>Citirizine/Loratadine</option>
                                <option value="Camphor + Menthol Liniment" @if(in_array('Camphor + Menthol Liniment', explode(',', $information->medicines ?? ''))) selected @endif>Camphor + Menthol Liniment</option>
                                <option value="PPA" @if(in_array('PPA', explode(',', $information->medicines ?? ''))) selected @endif>PPA</option>
                                <option value="Phenylephrine" @if(in_array('Phenylephrine', explode(',', $information->medicines ?? ''))) selected @endif>Phenylephrine</option>
                                <option value="Antacid" @if(in_array('Antacid', explode(',', $information->medicines ?? ''))) selected @endif>Antacid</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="button" onclick="temporarySave('medical')">Save</button>
                    </div>
                </form>
            </div>

            <!-- Health History Section -->
            <div class="history-container">
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
                    </tbody>
                </table>

                <h2>Physical Examination History</h2>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Section</th>
                            <th>Height</th>
                            <th>Weight</th>
                            <th>Vision</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody id="physical-examination-history-body">
                        <!-- Dummy data -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('profile-picture-button').addEventListener('click', function () {
            document.getElementById('profile-picture-upload').click();
        });

        document.getElementById('profile-picture-upload').addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('profile-picture-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);

                // Show SweetAlert confirmation
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Picture Updated',
                    text: 'Your profile picture has been successfully updated.',
                });
            }
        });

        // Automatically calculate age based on birthdate
        document.getElementById('birthdate').addEventListener('change', function () {
            const birthdate = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - birthdate.getFullYear();
            const m = today.getMonth() - birthdate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) {
                age--;
            }
            document.getElementById('age').value = age;
        });

        function temporarySave(formType) {
            const medicalRecordForm = document.getElementById('medical-record-form');

            const formData = new FormData(medicalRecordForm);

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
    </script>
</x-app-layout>