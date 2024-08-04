<!-- resources/views/student/medical-record.blade.php -->
<x-app-layout>
    <style>
        .container {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }

        .sidebar {
            width: 80px; /* Collapsed width */
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
            width: 250px; /* Expanded width */
        }

        .main-content {
            margin-left: 80px; /* Collapsed sidebar width */
            width: calc(100% - 80px);
            padding: 20px;
            transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
        }

        .sidebar:hover ~ .main-content {
            margin-left: 250px; /* Expanded sidebar width */
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
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .form-group button:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        .form-group button:active {
            transform: scale(0.95);
        }

        .tab-buttons {
            margin-bottom: 20px;
        }

        .tab-buttons button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .tab-buttons button:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        .tab-buttons button:active {
            transform: scale(0.95);
        }

        .tab-buttons button.active {
            background-color: #00a8cc;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeInUp 0.5s ease-in-out;
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
            <div class="tab-buttons">
                <button id="medical-record-tab" class="active" onclick="showTab('medical-record')">Medical Record</button>
                <button id="dental-record-tab" onclick="showTab('dental-record')">Dental Record</button>
            </div>

            <!-- Medical Record Content -->
            <div id="medical-record" class="tab-content active">
                <h1>Student Medical Record</h1>
                <div class="form-container">
                    <form method="POST" action="{{ route('student.medical-record.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="birthdate">Birthdate</label>
                            <input type="date" id="birthdate" name="birthdate" required>
                        </div>
                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="number" id="age" name="age" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" required>
                        </div>
                        <div class="form-group">
                            <label for="father-name">Father's Name/Legal Guardian</label>
                            <input type="text" id="father-name" name="father_name" required>
                        </div>
                        <div class="form-group">
                            <label for="mother-name">Mother's Name/Legal Guardian</label>
                            <input type="text" id="mother-name" name="mother_name" required>
                        </div>
                        <div class="form-group">
                            <label for="medical-illness">Pertinent Medical Illness</label>
                            <input type="text" id="medical-illness" name="medical_illness" required>
                        </div>
                        <div class="form-group">
                            <label for="allergies">Allergies (specify)</label>
                            <input type="text" id="allergies" name="allergies" required>
                        </div>
                        <div class="form-group">
                            <label for="pediatrician">Pediatrician</label>
                            <input type="text" id="pediatrician" name="pediatrician" required>
                        </div>
                        <div class="form-group">
                            <label for="picture">Upload Picture</label>
                            <input type="file" id="picture" name="picture" accept="image/*" required>
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

                        <div class="form-section">
                            <h2>Consent</h2>
                            <div class="form-group">
                                <p>
                                    I, ____________________________, parent/legal guardian of ____________________________, authorize the health professional to administer medication and do medical treatment as needed. In case of emergency, the patient will be transported to the nearest hospital (Brent Hospital).
                                </p>
                                <div class="form-group-inline">
                                    <label for="consent-date">Date</label>
                                    <input type="date" id="consent-date" name="consent_date" required>
                                </div>
                                <div class="form-group-inline">
                                    <label for="signature">Signature over printed name</label>
                                    <input type="text" id="signature" name="signature" required>
                                </div>
                                <div class="form-group-inline">
                                    <label for="contact-no">Contact No:</label>
                                    <input type="tel" id="contact-no" name="contact_no" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Dental Record Content -->
            <div id="dental-record" class="tab-content">
                <h1>Dental Record Page</h1>
                <div class="form-container">
                    <form method="POST" action="{{ route('student.dental-record.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="patient-name">Patient Name</label>
                            <input type="text" id="patient-name" name="patient_name" required>
                        </div>
                        <div class="form-group">
                            <label for="date-of-birth">Date of Birth</label>
                            <input type="date" id="date-of-birth" name="date_of_birth" required>
                        </div>
                        <div class="form-group">
                            <label for="treatment">Treatment</label>
                            <textarea id="treatment" name="treatment" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="dentist">Dentist</label>
                            <select id="dentist" name="dentist" required>
                                <option value="">Select Dentist</option>
                                <option value="Dr. Smith">Dr. Smith</option>
                                <option value="Dr. Jones">Dr. Jones</option>
                                <option value="Dr. Brown">Dr. Brown</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit">Submit</button>
                        </div>
                    </form>

                    <!-- Button to toggle the detailed dental examination form -->
                    <button class="toggle-button" onclick="toggleDentalExam()">Show/Hide Dental Examination</button>

                    <!-- Detailed Dental Examination Form -->
                    <div class="dental-exam-container" id="dental-exam-container">
                        <h2>Student Dental Examination</h2>
                        <div class="form-group">
                            <label for="grade-section">Grade and Section</label>
                            <input type="text" id="grade-section" name="grade_section" required>
                        </div>
                        <div class="form-group">
                            <label for="birthdate">Birthdate</label>
                            <input type="date" id="birthdate" name="birthdate" required>
                        </div>
                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="text" id="age" name="age" required>
                        </div>
                        <div class="form-group">
                            <label>Oral Examination Revealed the Following Conditions:</label>
                            <div>
                                <input type="checkbox" id="caries-free" name="conditions[]" value="Caries-Free">
                                <label for="caries-free">Caries-Free</label>
                            </div>
                            <div>
                                <input type="checkbox" id="poor-oral-hygiene" name="conditions[]" value="Poor Oral Hygiene">
                                <label for="poor-oral-hygiene">Poor Oral Hygiene</label>
                            </div>
                            <div>
                                <input type="checkbox" id="gum-infection" name="conditions[]" value="Gum Infection">
                                <label for="gum-infection">Gum Infection</label>
                            </div>
                            <div>
                                <input type="checkbox" id="restorable-caries" name="conditions[]" value="Restorable Caries">
                                <label for="restorable-caries">Restorable Caries</label>
                            </div>
                            <div>
                                <input type="text" id="other-conditions" name="other_conditions" placeholder="Others: Specify">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Remarks and Recommendations:</label>
                            <div>
                                <input type="checkbox" id="tooth-brushing" name="recommendations[]" value="Need personal attention in tooth brushing">
                                <label for="tooth-brushing">Need personal attention in tooth brushing</label>
                            </div>
                            <div>
                                <input type="checkbox" id="oral-prophylaxis" name="recommendations[]" value="For oral prophylaxis">
                                <label for="oral-prophylaxis">For oral prophylaxis</label>
                            </div>
                            <div>
                                <input type="checkbox" id="fluoride-application" name="recommendations[]" value="For Fluoride application">
                                <label for="fluoride-application">For Fluoride application</label>
                            </div>
                            <div>
                                <input type="checkbox" id="gum-treatment" name="recommendations[]" value="For Gum/Periodontal treatment">
                                <label for="gum-treatment">For Gum/Periodontal treatment</label>
                            </div>
                            <div>
                                <input type="checkbox" id="orthodontic-consultation" name="recommendations[]" value="For Orthodontic Consultation">
                                <label for="orthodontic-consultation">For Orthodontic Consultation</label>
                            </div>
                            <div>
                                <input type="checkbox" id="pits-fissure-sealant" name="recommendations[]" value="For Pits and Fissure Sealant">
                                <label for="pits-fissure-sealant">For Pits and Fissure Sealant: Tooth #</label>
                                <input type="text" id="pits-fissure-sealant-tooth" name="pits_fissure_sealant_tooth">
                            </div>
                            <div>
                                <input type="checkbox" id="filling" name="recommendations[]" value="For Filling">
                                <label for="filling">For Filling: Tooth #</label>
                                <input type="text" id="filling-tooth" name="filling_tooth">
                            </div>
                            <div>
                                <input type="text" id="other-recommendations" name="other_recommendations" placeholder="Others: Specify">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleDentalExam() {
            const dentalExamContainer = document.getElementById('dental-exam-container');
            if (dentalExamContainer.style.display === 'none' || dentalExamContainer.style.display === '') {
                dentalExamContainer.style.display = 'block';
            } else {
                dentalExamContainer.style.display = 'none';
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

        document.getElementById('notification-icon').addEventListener('click', function() {
            var dropdown = document.getElementById('notification-dropdown');
            dropdown.classList.toggle('active');
        });

        // Close the dropdown if clicked outside
        document.addEventListener('click', function(event) {
            var dropdown = document.getElementById('notification-dropdown');
            var icon = document.getElementById('notification-icon');
            if (!dropdown.contains(event.target) && !icon.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });

        // Initialize by showing the medical record tab
        showTab('medical-record');
    </script>
</x-app-layout>
