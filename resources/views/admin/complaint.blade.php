<x-app-layout>
    <style>
        .container {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }

        .sidebar {
            width: 100px;
            background-color: #00d2ff;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            transition: width 0.3s ease-in-out;
        }

        .sidebar:hover {
            width: 250px;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0;
            transition: opacity 0.3s ease-in-out;
        }

        .logo-img {
            width: 40px;
            height: 40px;
            margin-right: 34px;
        }

        .logo-text {
            font-size: 1.25rem;
            font-weight: bold;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
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

        .header {
           display: flex;
    justify-content: space-between;
         align-items: center;
    padding: 20px;
    width: 100%; /* Ensure the header spans the full width */
    background-color: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: relative; /* Ensure it stays in the document flow */
    z-index: 2; /* Ensure it stays on top of other content */
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

        .complaints-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .complaints-table th,
        .complaints-table td {
            padding: 10px;
            text-align: left;
        }

        .complaints-table th {
            background-color: #f5f5f5;
            color: #333;
            font-weight: 600;
            border-bottom: 1px solid #ddd;
        }

        .complaints-table td {
            border-bottom: 1px solid #eee;
        }

        .tab-buttons {
            display: flex;
            margin-bottom: 20px;
        }

        .tab-buttons button {
            padding: 10px 20px;
            border: none;
            background: none;
            cursor: pointer;
            transition: color 0.3s, border-bottom 0.3s;
        }

        .tab-buttons button.active {
            color: #007bff;
            border-bottom: 2px solid #007bff;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .preview-button {
            background-color: #00d1ff;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .preview-button:hover {
            background-color: #00b8e6;
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
            animation: fadeIn 0.5s ease-in-out;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 10px;
            animation: slideIn 0.5s ease-in-out;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-body {
            margin-top: 10px;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Complaints Table -->
        <div class="complaints-section">
            <h2>Complaints by Role</h2>
            <div class="tab-buttons">
                <button id="student-tab" class="active" onclick="showTab('student')">Student</button>
                <button id="staff-tab" onclick="showTab('staff')">Staff</button>
                <button id="parent-tab" onclick="showTab('parent')">Parent</button>
                <button id="teacher-tab" onclick="showTab('teacher')">Teacher</button>
            </div>

            <div id="student" class="tab-content active">
                <!-- Student Complaints Table -->
                <table class="complaints-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Health History</th>
                            <th>Description of Sickness</th>
                            <th>Pain Assessment</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($studentComplaints as $complaint)
                            <tr>
                                <td>{{ $complaint->name }}</td>
                                <td>{{ $complaint->health_history }}</td>
                                <td>{{ $complaint->sickness_description }}</td>
                                <td>{{ $complaint->pain_assessment }}</td>
                                <td>{{ $complaint->status }}</td>
                                <td><button class="preview-button" onclick="openModal({{ $complaint->id }})">Preview</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="staff" class="tab-content">
                <!-- Staff Complaints Table -->
                <table class="complaints-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Health History</th>
                            <th>Description of Sickness</th>
                            <th>Pain Assessment</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($staffComplaints as $complaint)
                            <tr>
                                <td>{{ $complaint->name }}</td>
                                <td>{{ $complaint->health_history }}</td>
                                <td>{{ $complaint->sickness_description }}</td>
                                <td>{{ $complaint->pain_assessment }}</td>
                                <td>{{ $complaint->status }}</td>
                                <td><button class="preview-button" onclick="openModal({{ $complaint->id }})">Preview</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="parent" class="tab-content">
                <!-- Parent Complaints Table -->
                <table class="complaints-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Health History</th>
                            <th>Description of Sickness</th>
                            <th>Pain Assessment</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($parentComplaints as $complaint)
                            <tr>
                                <td>{{ $complaint->name }}</td>
                                <td>{{ $complaint->health_history }}</td>
                                <td>{{ $complaint->sickness_description }}</td>
                                <td>{{ $complaint->pain_assessment }}</td>
                                <td>{{ $complaint->status }}</td>
                                <td><button class="preview-button" onclick="openModal({{ $complaint->id }})">Preview</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="teacher" class="tab-content">
                <!-- Teacher Complaints Table -->
                <table class="complaints-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Health History</th>
                            <th>Description of Sickness</th>
                            <th>Pain Assessment</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teacherComplaints as $complaint)
                            <tr>
                                <td>{{ $complaint->name }}</td>
                                <td>{{ $complaint->health_history }}</td>
                                <td>{{ $complaint->sickness_description }}</td>
                                <td>{{ $complaint->pain_assessment }}</td>
                                <td>{{ $complaint->status }}</td>
                                <td><button class="preview-button" onclick="openModal({{ $complaint->id }})">Preview</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="complaint-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Complaint Details</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body" id="modal-body">
                <!-- Complaint details will be loaded here -->
            </div>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        function showTab(tabId) {
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(tabContent => {
                tabContent.classList.remove('active');
            });

            const selectedTabContent = document.getElementById(tabId);
            if (selectedTabContent) {
                selectedTabContent.classList.add('active');
            }

            const tabButtons = document.querySelectorAll('.tab-buttons button');
            tabButtons.forEach(button => {
                button.classList.remove('active');
            });

            const selectedTabButton = document.getElementById(tabId + '-tab');
            if (selectedTabButton) {
                selectedTabButton.classList.add('active');
            }
        }

        function openModal(complaintId) {
            fetch(`/admin/complaint/${complaintId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    const modalBody = document.getElementById('modal-body');
                    modalBody.innerHTML = `
                        <p><strong>Name:</strong> ${data.name}</p>
                        <p><strong>Age:</strong> ${data.age}</p>
                        <p><strong>Birthdate:</strong> ${data.birthdate}</p>
                        <p><strong>Contact Number:</strong> ${data.contact_number}</p>
                        <p><strong>Health History:</strong> ${data.health_history}</p>
                        <p><strong>Pain Assessment:</strong> ${data.pain_assessment}</p>
                        <p><strong>Description of Sickness:</strong> ${data.sickness_description}</p>
                        <p><strong>Status:</strong> ${data.status}</p>
                        <p><strong>Student Type:</strong> ${data.student_type}</p>
                        <p><strong>Program:</strong> ${data.program || 'N/A'}</p>
                        <p><strong>Year:</strong> ${data.year || 'N/A'}</p>
                        <p><strong>Section:</strong> ${data.section || 'N/A'}</p>
                        <p><strong>Grade:</strong> ${data.grade || 'N/A'}</p>
                        <p><strong>Strand:</strong> ${data.strand || 'N/A'}</p>
                    `;
                    document.getElementById('complaint-modal').style.display = 'block';
                })
                .catch(error => console.error('Error fetching complaint details:', error));
        }

        function closeModal() {
            document.getElementById('complaint-modal').style.display = 'none';
        }

        document.getElementById('notification-icon').addEventListener('click', function() {
            const dropdown = document.getElementById('notification-dropdown');
            dropdown.classList.toggle('active');
        });

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notification-dropdown');
            const icon = document.getElementById('notification-icon');
            if (!dropdown.contains(event.target) && !icon.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });

        showTab('student');
    </script>
</x-app-layout>
