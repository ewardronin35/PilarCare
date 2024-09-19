<x-app-layout>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f8fc;
        }

        .container {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .main-content {
            margin-top: 40px;
            padding: 20px;
        }

        .tabs {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
            justify-content: space-around;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 10px 10px 0 0;
        }

        .tab:hover {
            background-color: #c9d1d9;
        }

        .tab.active {
            background-color: #007bff;
            color: white;
        }

        .tab-content {
            display: none;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in-out;
        }

        .tab-content.active {
            display: block;
        }

        .table-container {
            margin-top: 20px;
            overflow-x: auto;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            margin-top: 10px;
            border-radius: 10px;
            overflow: hidden;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
        }

        table th {
            background-color: #007bff;
            color: white;
            font-weight: 600;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .form-container {
            background-color: #f7f8fc;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }

        .form-group input, 
        .form-group select, 
        .form-group textarea {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
        }

        .form-group button {
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .form-group input[type="radio"] {
            width: auto;
            margin-right: 10px;
        }

        .form-group label.inline {
            display: inline;
            margin-right: 10px;
        }

        .chart-container {
            margin-top: 20px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        @keyframes fadeIn {
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

    <main class="main-content">
        <!-- Tabs for different sections -->
        <div class="tabs">
            <div class="tab active" onclick="showTab('emergency-notifications')"><i class="fas fa-bell"></i> Emergency Notifications</div>
            <div class="tab" onclick="showTab('logs')"><i class="fas fa-clipboard-list"></i> All Logs</div>
            <div class="tab" onclick="showTab('statistics')"><i class="fas fa-chart-bar"></i> Statistics</div>
        </div>

        <!-- Emergency Notifications Tab Content -->
        <div id="emergency-notifications" class="tab-content active">
            <h2><i class="fas fa-exclamation-triangle"></i> Emergency Notifications</h2>
            <div class="form-container">
                <h3>Release Notification</h3>
                <form method="POST" action="{{ route('admin.notifications.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="school_id"><i class="fas fa-id-card"></i> School ID/Staff ID</label>
                        <input type="text" id="school_id" name="school_id" required>
                    </div>
                    <div class="form-group">
                        <label for="name"><i class="fas fa-user"></i> Full Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="notification_info"><i class="fas fa-info-circle"></i> Notification Information</label>
                        <input type="text" id="notification_info" name="notification_info" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-bell"></i> Notification for:</label>
                        <div>
                            <input type="radio" id="student" name="notification_for" value="student">
                            <label for="student" class="inline">Student</label>
                            <input type="radio" id="parent" name="notification_for" value="parent">
                            <label for="parent" class="inline">Parent</label>
                            <input type="radio" id="staff" name="notification_for" value="staff">
                            <label for="staff" class="inline">Staff/Teachers</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit"><i class="fas fa-paper-plane"></i> Notify</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Logs Tab Content -->
        <div id="logs" class="tab-content">
            <h2><i class="fas fa-clipboard-list"></i> Logs</h2>

            <!-- Logs Table -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Module</th>
                            <th>Details</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Appointments</td>
                            <td>Appointment created for John Doe</td>
                            <td>May 20, 2024 10:00 AM</td>
                            <td>Completed</td>
                        </tr>
                        <tr>
                            <td>Inventory</td>
                            <td>Stethoscope added to inventory</td>
                            <td>May 10, 2024 09:00 AM</td>
                            <td>Available</td>
                        </tr>
                        <tr>
                            <td>Complaints</td>
                            <td>Complaint filed by Jane Doe</td>
                            <td>May 15, 2024 11:30 AM</td>
                            <td>Resolved</td>
                        </tr>
                        <!-- Add more log rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Statistics Tab Content -->
        <div id="statistics" class="tab-content">
            <h2><i class="fas fa-chart-bar"></i> Statistics</h2>

            <!-- Chart for Log Statistics -->
            <div class="chart-container">
                <canvas id="logStatisticsChart"></canvas>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function showTab(tabId) {
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(tabContent => {
                tabContent.classList.remove('active');
            });

            const selectedTabContent = document.getElementById(tabId);
            selectedTabContent.classList.add('active');

            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            document.querySelector(`.tab[onclick="showTab('${tabId}')"]`).classList.add('active');
        }

        // Statistics chart for logs
        const ctx = document.getElementById('logStatisticsChart').getContext('2d');
        const logStatisticsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Accounts', 'Complaints', 'Appointments', 'Dental Records', 'Medical Records'],
                datasets: [{
                    label: 'Number of Logs',
                    data: [{{ $accountLogsCount }}, {{ $complaintLogsCount }}, {{ $appointmentLogsCount }}, {{ $dentalRecordLogsCount }}, {{ $medicalRecordLogsCount }}],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</x-app-layout>
