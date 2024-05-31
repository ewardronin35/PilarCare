<x-app-layout>
    <style>
        .container {
            display: flex;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
            margin-left: 80px; /* Adjust margin to accommodate the sidebar */
            transition: margin-left 0.3s ease-in-out;
        }
        .sidebar:hover + .main-content {
            margin-left: 250px; /* Adjust margin when sidebar is expanded */
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
        }
        .form-group button:hover {
            background-color: #00b8e6;
        }
        .complaints-section {
            margin-top: 20px;
        }
        .appointment-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .appointment-table th, .appointment-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .appointment-table th {
            background-color: #00d1ff;
            color: white;
        }
        .appointment-table td input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .add-complaint-button {
            display: inline-block;
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .add-complaint-button:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }
        .add-complaint-button:active {
            transform: scale(0.95);
        }
        .notification-icon {
            position: relative;
            cursor: pointer;
        }
        .notification-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 250px;
        }
        .notification-dropdown.active {
            display: block;
        }
        .notification-header {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        .notification-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .notification-item:hover {
            background-color: #f0f0f0;
        }
    </style>

    <div class="container">
        <x-sidebar />

        <main class="main-content">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Complaint Form -->
            <h1>Student Health Complaint</h1>
            <div class="form-container">
                <form method="POST" action="{{ route('complaint.add') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="year_and_section">Year and Section</label>
                        <input type="text" id="year_and_section" name="year_and_section" required>
                    </div>
                    <div class="form-group">
                        <label for="age">Age</label>
                        <input type="number" id="age" name="age" required>
                    </div>
                    <div class="form-group">
                        <label for="birthdate">Birthdate</label>
                        <input type="date" id="birthdate" name="birthdate" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input type="text" id="contact_number" name="contact_number" required>
                    </div>
                    <div class="form-group">
                        <label for="health_history">Health History</label>
                        <input type="text" id="health_history" name="health_history" required>
                    </div>

                    <div class="complaints-section">
                        <h2>Complaints</h2>
                        <table class="appointment-table">
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Complaints</th>
                                    <th>Management</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody id="complaints-table-body">
                                <tr>
                                    <td><input type="datetime-local" name="complaints[0][datetime]" required></td>
                                    <td><input type="text" name="complaints[0][complaint]" required></td>
                                    <td><input type="text" name="complaints[0][management]" required></td>
                                    <td><input type="text" name="complaints[0][remarks]" required></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="add-complaint-button" onclick="addComplaintRow()">Add Complaint</button>
                    </div>

                    <div class="form-group">
                        <button type="submit">Save</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        let complaintIndex = 1;
        function addComplaintRow() {
            const tableBody = document.getElementById('complaints-table-body');
            const newRow = document.createElement('tr');

            newRow.innerHTML = `
                <td><input type="datetime-local" name="complaints[${complaintIndex}][datetime]" required></td>
                <td><input type="text" name="complaints[${complaintIndex}][complaint]" required></td>
                <td><input type="text" name="complaints[${complaintIndex}][management]" required></td>
                <td><input type="text" name="complaints[${complaintIndex}][remarks]" required></td>
            `;

            tableBody.appendChild(newRow);
            complaintIndex++;
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
    </script>
</x-app-layout>
