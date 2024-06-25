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

        .form-container, .table-container {
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

        .form-group-inline {
            display: flex;
            align-items: center;
            gap: 10px;
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

        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-bar input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table th {
            background-color: #f2f2f2;
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
                <button id="pending-approvals-tab" class="active" onclick="showTab('pending-approvals')">Pending Approvals</button>
                <button id="submit-exam-tab" onclick="showTab('submit-exam')">Submit Exam</button>
                <button id="medical-record-tab" onclick="showTab('medical-record')">Medical Record</button>
            </div>

            <!-- Pending Approvals Content -->
            <div id="pending-approvals" class="tab-content active">
                <h1>Pending Approvals</h1>
                <div class="table-container">
                    @if(isset($pendingExaminations) && $pendingExaminations->isNotEmpty())
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Picture</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingExaminations as $examination)
                                    <tr>
                                        <td>{{ $examination->user->first_name }} {{ $examination->user->last_name }}</td>
                                        <td><img src="{{ asset('storage/' . $examination->health_examination_picture) }}" alt="Health Examination Picture" width="100"></td>
                                        <td>
                                            <form action="{{ route('admin.health-examinations.approve', $examination->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.health-examinations.reject', $examination->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No pending examinations available.</p>
                    @endif
                </div>
            </div>

            <!-- Submit Health Examination Content -->
            <div id="submit-exam" class="tab-content">
                <h1>Submit Health Examination</h1>
                <div class="form-container">
                    <form method="POST" action="{{ route('admin.health-examinations.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="school_id">School ID</label>
                            <input type="text" id="school_id" name="school_id" required>
                        </div>
                        <div class="form-group">
                            <label for="student_type">Student Type</label>
                            <input type="text" id="student_type" name="student_type" required>
                        </div>
                        <div class="form-group">
                            <label for="health_examination_picture" class="button"><i class="fa-regular fa-image"></i> Upload Picture</label>
                            <input type="file" id="health_examination_picture" name="health_examination_picture" accept="image/*" required>
                            <img id="picture-preview" src="#" alt="Picture Preview" style="display: none;">
                        </div>
                        <div class="form-group">
                            <button type="submit">Submit Examination</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Medical Record Content -->
            <div id="medical-record" class="tab-content">
                <div class="search-bar">
                    <input type="text" id="search-school-id" placeholder="Search School ID">
                    <button onclick="searchSchoolId()">Search</button>
                </div>

                <h1>Student Medical Record</h1>
                <div class="form-container">
                    <form method="POST" action="{{ route('student.medical-record.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="medical-name" name="name" disabled>
                        </div>
                        <div class="form-group">
                            <label for="birthdate">Birthdate</label>
                            <input type="date" id="medical-birthdate" name="birthdate" disabled>
                        </div>
                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="number" id="medical-age" name="age" disabled>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="medical-address" name="address" disabled>
                        </div>
                        <div class="form-group">
                            <label for="father-name">Father's Name/Legal Guardian</label>
                            <input type="text" id="medical-father-name" name="father_name" disabled>
                        </div>
                        <div class="form-group">
                            <label for="mother-name">Mother's Name/Legal Guardian</label>
                            <input type="text" id="medical-mother-name" name="mother_name" disabled>
                        </div>
                        <div class="form-group">
                            <label for="medical-illness">Pertinent Medical Illness</label>
                            <input type="text" id="medical-illness" name="medical_illness" disabled>
                        </div>
                        <div class="form-group">
                            <label for="allergies">Allergies (specify)</label>
                            <input type="text" id="medical-allergies" name="allergies" disabled>
                        </div>
                        <div class="form-group">
                            <label for="pediatrician">Pediatrician</label>
                            <input type="text" id="medical-pediatrician" name="pediatrician" disabled>
                        </div>
                        <div class="form-group">
                            <label for="picture">Upload Picture</label>
                            <input type="file" id="medical-picture" name="picture" accept="image/*" disabled>
                            <img id="medical-picture-preview" src="#" alt="Picture Preview" style="display: none;">
                        </div>

                        <div class="form-section">
                            <h2>Medicines OK to give/apply at the clinic (check)</h2>
                            <div class="checkbox-group">
                                <label><input type="checkbox" name="medicines[]" value="Paracetamol" disabled> Paracetamol</label>
                                <label><input type="checkbox" name="medicines[]" value="Ibuprofen" disabled> Ibuprofen</label>
                                <label><input type="checkbox" name="medicines[]" value="Mefenamic Acid" disabled> Mefenamic Acid</label>
                                <label><input type="checkbox" name="medicines[]" value="Citirizine/Loratadine" disabled> Citirizine/Loratadine</label>
                                <label><input type="checkbox" name="medicines[]" value="Camphor + Menthol Liniment" disabled> Camphor + Menthol Liniment</label>
                                <label><input type="checkbox" name="medicines[]" value="PPA" disabled> PPA</label>
                                <label><input type="checkbox" name="medicines[]" value="Phenylephrine" disabled> Phenylephrine</label>
                                <label><input type="checkbox" name="medicines[]" value="Antacid" disabled> Antacid</label>
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
                                    <input type="date" id="medical-consent-date" name="consent_date" disabled>
                                </div>
                                <div class="form-group-inline">
                                    <label for="signature">Signature over printed name</label>
                                    <input type="text" id="medical-signature" name="signature" disabled>
                                </div>
                                <div class="form-group-inline">
                                    <label for="contact-no">Contact No:</label>
                                    <input type="tel" id="medical-contact-no" name="contact_no" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" disabled>Submit</button>
                        </div>
                    </form>
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

        function searchSchoolId() {
            // Get the search input value
            const schoolId = document.getElementById('search-school-id').value.trim();

            // Perform a dummy check (you can replace this with an actual AJAX call to fetch data)
            if (schoolId === '12345') {
                // Enable all form fields if the school ID is valid
                enableFormFields(true);
            } else {
                alert('School ID not found');
                // Disable all form fields if the school ID is invalid
                enableFormFields(false);
            }
        }

        function enableFormFields(enable) {
            const formFields = document.querySelectorAll('.form-container input, .form-container textarea, .form-container select, .form-container button');
            formFields.forEach(field => {
                field.disabled = !enable;
            });
        }

        document.getElementById('health_examination_picture').addEventListener('change', function(event) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('picture-preview');
                img.src = e.target.result;
                img.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        });

        document.addEventListener('DOMContentLoaded', function () {
            showTab('pending-approvals');
        });
    </script>
</x-app-layout>
