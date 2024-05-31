<x-app-layout>
    <style>
        .container {
            display: flex;
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
        }

        .sidebar:hover {
            width: 250px; /* Expanded width */
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            transition: opacity 0.3s ease-in-out;
        }

        .logo-img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .logo-text {
            font-size: 1.25rem;
            font-weight: bold;
            opacity: 0;
        }

        .sidebar:hover .logo-text {
            opacity: 1;
        }

        .menu ul {
            list-style: none;
            padding: 0;
        }

        .menu li {
            display: flex;
            align-items: center;
            margin: 10px 0;
            padding: 10px 20px;
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .menu li:hover {
            background-color: #009ec3;
            transform: translateX(10px);
        }

        .menu li a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            display: flex;
            align-items: center;
        }

        .menu li a .icon {
            min-width: 20px;
            margin-right: 10px;
            text-align: center;
        }

        .menu-text {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .sidebar:hover .menu-text {
            opacity: 1;
        }

        .sidebar-footer ul {
            list-style: none;
            padding: 0;
        }

        .sidebar-footer li {
            display: flex;
            align-items: center;
            margin: 10px 0;
            padding: 10px 20px;
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .sidebar-footer li:hover {
            background-color: #009ec3;
            transform: translateX(10px);
        }

        .sidebar-footer li a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            display: flex;
            align-items: center;
        }

        .sidebar-footer li a .icon {
            min-width: 20px;
            margin-right: 10px;
            text-align: center;
        }

        .main-content {
            margin-left: 80px; /* Collapsed sidebar width */
            width: 100%;
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
        }

        .sidebar:hover ~ .main-content {
            margin-left: 250px; /* Expanded sidebar width */
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info .username {
            margin-right: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .notification-icon {
            margin-right: 20px;
            position: relative;
        }

        .notification-dropdown {
            display: none;
            position: absolute;
            top: 50px;
            right: 10px;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .notification-dropdown.active {
            display: block;
        }

        .notification-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background-color: #f9f9f9;
            transform: translateX(10px);
        }

        .notification-item .icon {
            margin-right: 10px;
        }

        .notification-header {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
            font-weight: bold;
        }

        .form-container {
            margin-top: 20px;
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
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
        }

        .form-group input[type="radio"] {
            width: auto;
            margin-right: 10px;
        }

        .form-section {
            margin-bottom: 20px;
        }

        .form-section h2 {
            margin-bottom: 15px;
            font-size: 1.5em;
            border-bottom: 2px solid #00d1ff;
            padding-bottom: 5px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #00d1ff;
            color: white;
        }

        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
        }

        .checkbox-group label {
            width: 50%;
            margin-bottom: 10px;
        }

        .dental-exam-container {
            display: none;
            margin-top: 20px;
        }

        .toggle-button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .toggle-button:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        .toggle-button:active {
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
        }

        .search-bar {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-bar input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .search-bar button {
            padding: 10px 20px;
            background-color: #00d1ff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .search-bar button:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        .search-bar button:active {
            transform: scale(0.95);
        }
    </style>

    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <img src="{{ asset('images/pilarLogo.png') }}" alt="PilarCare Logo" class="logo-img">
                <span class="logo-text hidden md:inline-block">PilarCare</span>
            </div>
            <nav class="menu">
                <ul>
                <li><a href="{{ route('dashboard') }}"><span class="icon"><i class="fas fa-tachometer-alt"></i></span><span class="menu-text">Dashboard</span></a></li>
                <li><a href="{{ route('complaint') }}"><span class="icon"><i class="fas fa-comments"></i></span><span class="menu-text">Complaint</span></a></li>
                    <li><a href="{{ route('medical-record') }}"><span class="icon"><i class="fas fa-notes-medical"></i></span><span class="menu-text">Records</span></a></li>
                    <li><a href="{{ route('appointment') }}"><span class="icon"><i class="fas fa-calendar-check"></i></span><span class="menu-text">Appointment</span></a></li>
                    <li><a href="{{ route('inventory') }}"><span class="icon"><i class="fas fa-boxes"></i></span><span class="menu-text">Inventory</span></a></li>
                    <li><a href="{{ route('monitoring-report-log') }}"><span class="icon"><i class="fas fa-chart-line"></i></span><span class="menu-text">Monitoring and Report</span></a></li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <ul>
                    <li><a href="#"><span class="icon"><i class="fas fa-cogs"></i></span><span class="menu-text">Settings</span></a></li>
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="icon"><i class="fas fa-sign-out-alt"></i></span><span class="menu-text">Logout</span></a></li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </ul>
            </div>
        </aside>

        <main class="main-content">
            
            <!-- Search Bar -->
            <div class="search-bar">
                <input type="text" id="search-school-id" placeholder="Search School ID">
                <button onclick="searchSchoolId()">Search</button>
            </div>

            <div class="tab-buttons">
                <button id="medical-record-tab" class="active" onclick="showTab('medical-record')">Medical Record</button>
                <button id="dental-record-tab" onclick="showTab('dental-record')">Dental Record</button>
            </div>

            <!-- Medical Record Content -->
            <div id="medical-record" class="tab-content active">
                <h1>Student Medical Record</h1>
                <div class="form-container">
                    <form>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" disabled>
                        </div>
                        <div class="form-group">
                            <label for="birthdate">Birthdate</label>
                            <input type="date" id="birthdate" name="birthdate" disabled>
                        </div>
                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="number" id="age" name="age" disabled>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" disabled>
                        </div>
                        <div class="form-group">
                            <label for="father-name">Father's Name/Legal Guardian</label>
                            <input type="text" id="father-name" name="father_name" disabled>
                        </div>
                        <div class="form-group">
                            <label for="mother-name">Mother's Name/Legal Guardian</label>
                            <input type="text" id="mother-name" name="mother_name" disabled>
                        </div>
                        <div class="form-group">
                            <label for="medical-illness">Pertinent Medical Illness</label>
                            <input type="text" id="medical-illness" name="medical_illness" disabled>
                        </div>
                        <div class="form-group">
                            <label for="allergies">Allergies (specify)</label>
                            <input type="text" id="allergies" name="allergies" disabled>
                        </div>
                        <div class="form-group">
                            <label for="pediatrician">Pediatrician</label>
                            <input type="text" id="pediatrician" name="pediatrician" disabled>
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
                                    <input type="date" id="consent-date" name="consent_date" disabled>
                                </div>
                                <div class="form-group-inline">
                                    <label for="signature">Signature over printed name</label>
                                    <input type="text" id="signature" name="signature" disabled>
                                </div>
                                <div class="form-group-inline">
                                    <label for="contact-no">Contact No:</label>
                                    <input type="tel" id="contact-no" name="contact_no" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" disabled>Submit</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Dental Record Content -->
            <div id="dental-record" class="tab-content">
                <h1>Dental Record Page</h1>
                <div class="form-container">
                    <form>
                       
                        <div class="form-group">
                            <label for="patient-name">Patient Name</label>
                            <input type="text" id="patient-name" name="patient_name" disabled>
                        </div>
                        <div class="form-group">
                            <label for="date-of-birth">Date of Birth</label>
                            <input type="date" id="date-of-birth" name="date_of_birth" disabled>
                        </div>
                        <div class="form-group">
                            <label for="treatment">Treatment</label>
                            <textarea id="treatment" name="treatment" rows="4" disabled></textarea>
                        </div>
                        <div class="form-group">
                            <label for="dentist">Dentist</label>
                            <select id="dentist" name="dentist" disabled>
                                <option value="">Select Dentist</option>
                                <option value="Dr. Smith">Dr. Smith</option>
                                <option value="Dr. Jones">Dr. Jones</option>
                                <option value="Dr. Brown">Dr. Brown</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" disabled>Submit</button>
                        </div>
                    </form>

                    <!-- Button to toggle the detailed dental examination form -->
                    <button class="toggle-button" onclick="toggleDentalExam()">Show/Hide Dental Examination</button>

                    <!-- Detailed Dental Examination Form -->
                    <div class="dental-exam-container" id="dental-exam-container">
                        <h2>Student Dental Examination</h2>
                        <div class="form-group">
                            <label for="grade-section">Grade and Section</label>
                            <input type="text" id="grade-section" name="grade_section" disabled>
                        </div>
                        <div class="form-group">
                            <label for="birthdate">Birthdate</label>
                            <input type="date" id="birthdate" name="birthdate" disabled>
                        </div>
                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="text" id="age" name="age" disabled>
                        </div>
                        <div class="form-group">
                            <label>Oral Examination Revealed the Following Conditions:</label>
                            <div>
                                <input type="checkbox" id="caries-free" name="conditions[]" value="Caries-Free" disabled>
                                <label for="caries-free">Caries-Free</label>
                            </div>
                            <div>
                                <input type="checkbox" id="poor-oral-hygiene" name="conditions[]" value="Poor Oral Hygiene" disabled>
                                <label for="poor-oral-hygiene">Poor Oral Hygiene</label>
                            </div>
                            <div>
                                <input type="checkbox" id="gum-infection" name="conditions[]" value="Gum Infection" disabled>
                                <label for="gum-infection">Gum Infection</label>
                            </div>
                            <div>
                                <input type="checkbox" id="restorable-caries" name="conditions[]" value="Restorable Caries" disabled>
                                <label for="restorable-caries">Restorable Caries</label>
                            </div>
                            <div>
                                <input type="text" id="other-conditions" name="other_conditions" placeholder="Others: Specify" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Remarks and Recommendations:</label>
                            <div>
                                <input type="checkbox" id="tooth-brushing" name="recommendations[]" value="Need personal attention in tooth brushing" disabled>
                                <label for="tooth-brushing">Need personal attention in tooth brushing</label>
                            </div>
                            <div>
                                <input type="checkbox" id="oral-prophylaxis" name="recommendations[]" value="For oral prophylaxis" disabled>
                                <label for="oral-prophylaxis">For oral prophylaxis</label>
                            </div>
                            <div>
                                <input type="checkbox" id="fluoride-application" name="recommendations[]" value="For Fluoride application" disabled>
                                <label for="fluoride-application">For Fluoride application</label>
                            </div>
                            <div>
                                <input type="checkbox" id="gum-treatment" name="recommendations[]" value="For Gum/Periodontal treatment" disabled>
                                <label for="gum-treatment">For Gum/Periodontal treatment</label>
                            </div>
                            <div>
                                <input type="checkbox" id="orthodontic-consultation" name="recommendations[]" value="For Orthodontic Consultation" disabled>
                                <label for="orthodontic-consultation">For Orthodontic Consultation</label>
                            </div>
                            <div>
                                <input type="checkbox" id="pits-fissure-sealant" name="recommendations[]" value="For Pits and Fissure Sealant" disabled>
                                <label for="pits-fissure-sealant">For Pits and Fissure Sealant: Tooth #</label>
                                <input type="text" id="pits-fissure-sealant-tooth" name="pits_fissure_sealant_tooth" disabled>
                            </div>
                            <div>
                                <input type="checkbox" id="filling" name="recommendations[]" value="For Filling" disabled>
                                <label for="filling">For Filling: Tooth #</label>
                                <input type="text" id="filling-tooth" name="filling_tooth" disabled>
                            </div>
                            <div>
                                <input type="text" id="other-recommendations" name="other_recommendations" placeholder="Others: Specify" disabled>
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
        // Disable all form fields initially
        enableFormFields(false);
    </script>
</x-app-layout>
