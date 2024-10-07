<x-app-layout>
    <style>
        /* Existing styles remain unchanged */
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

        /* Additional styles for sub-tabs */
        .sub-tabs {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .sub-tab {
            padding: 8px 16px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            background-color: #e0e0e0;
            border-radius: 8px 8px 0 0;
            margin-right: 5px;
            flex: 1;
            margin-bottom: 5px;
        }

        .sub-tab:hover {
            background-color: #c9d1d9;
        }

        .sub-tab.active {
            background-color: #28a745;
            color: white;
        }

        .sub-tab-content {
            display: none;
            padding: 15px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in-out;
        }

        .sub-tab-content.active {
            display: block;
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
                            <input type="radio" id="student" name="notification_for" value="student" required>
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

        <!-- All Logs Tab Content with Sub-Tabs -->
        <div id="logs" class="tab-content">
            <h2><i class="fas fa-clipboard-list"></i> All Logs</h2>

            <!-- Sub-Tabs for different log modules -->
            <div class="sub-tabs">
                <div class="sub-tab active" onclick="showSubTab('medical-record-log')">Medical Record Log</div>
                <div class="sub-tab" onclick="showSubTab('appointment-log')">Appointment Log</div>
                <div class="sub-tab" onclick="showSubTab('complaint-log')">Complaint Log</div>
                <div class="sub-tab" onclick="showSubTab('registration-log')">Registration Log</div>
                <div class="sub-tab" onclick="showSubTab('login-log')">Login Log</div>
                <div class="sub-tab" onclick="showSubTab('dental-record-log')">Dental Record Log</div>
                <div class="sub-tab" onclick="showSubTab('physical-dental-exam-log')">Physical & Dental Exam Log</div>
            </div>

            <!-- Sub-Tab Content Sections -->
            <div id="medical-record-log" class="sub-tab-content active">
                <h3>Medical Record Logs</h3>
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
                            @foreach($medicalRecordLogs as $log)
                                <tr>
                                    <td>Medical Records</td>
                                    <td>Medical Record updated for {{ $log->user->first_name }} {{ $log->user->last_name }}</td>
                                    <td>{{ $log->updated_at->format('M d, Y h:i A') }}</td>
                                    <td>{{ $log->is_approved ? 'Approved' : 'Pending' }}</td>
                                </tr>
                            @endforeach
                            @if($medicalRecordLogs->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center">No Medical Record Logs Found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="appointment-log" class="sub-tab-content">
                <h3>Appointment Logs</h3>
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
                            @foreach($appointmentLogs as $log)
                                <tr>
                                    <td>Appointments</td>
                                    <td>Appointment with {{ $log->user->first_name }} {{ $log->user->last_name }}</td>
                                    <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                                    <td>{{ $log->status }}</td>
                                </tr>
                            @endforeach
                            @if($appointmentLogs->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center">No Appointment Logs Found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="complaint-log" class="sub-tab-content">
                <h3>Complaint Logs</h3>
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
                            @foreach($complaintLogs as $log)
                                <tr>
                                    <td>Complaints</td>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                                    <td>{{ $log->status }}</td>
                                </tr>
                            @endforeach
                            @if($complaintLogs->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center">No Complaint Logs Found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="registration-log" class="sub-tab-content">
    <h3>Registration Logs</h3>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Details</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrationLogs as $user)
                    <tr>
                        <td>Registrations</td>
                        <td>{{ $user->first_name }} {{ $user->last_name }} registered</td>
                        <td>{{ $user->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @endforeach
                @if($registrationLogs->isEmpty())
                    <tr>
                        <td colspan="3" class="text-center">No Registration Logs Found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
            <div id="login-log" class="sub-tab-content">
                <h3>Login Logs</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Module</th>
                                <th>User</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loginLogs as $log)
                                <tr>
                                    <td>Login</td>
                                    <td>{{ $log->user->first_name }} {{ $log->user->last_name }}</td>
                                    <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                                    <td>Success</td>
                                </tr>
                            @endforeach
                            @if($loginLogs->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center">No Login Logs Found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="dental-record-log" class="sub-tab-content">
                <h3>Dental Record Logs</h3>
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
                            @foreach($dentalRecordLogs as $log)
                                <tr>
                                    <td>Dental Records</td>
                                    <td>Dental Record updated for {{ $log->user->first_name }} {{ $log->user->last_name }}</td>
                                    <td>{{ $log->updated_at->format('M d, Y h:i A') }}</td>
                                    <td>{{ $log->is_approved ? 'Approved' : 'Pending' }}</td>
                                </tr>
                            @endforeach
                            @if($dentalRecordLogs->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center">No Dental Record Logs Found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="physical-dental-exam-log" class="sub-tab-content">
                <h3>Physical Exam Logs</h3>
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
                            @foreach($physicalDentalExamLogs as $log)
                                <tr>
                                    <td>Physical & Dental Exams</td>
                                    <td>Exam conducted for {{ $log->user->first_name }} {{ $log->user->last_name }}</td>
                                    <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                                    <td>{{ $log->status }}</td>
                                </tr>
                            @endforeach
                            @if($physicalDentalExamLogs->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center">No Physical & Dental Exam Logs Found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
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

    <!-- Include Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Function to handle main tab switching
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

        // Function to handle sub-tab switching within All Logs
        function showSubTab(subTabId) {
            const subTabContents = document.querySelectorAll('.sub-tab-content');
            subTabContents.forEach(subTabContent => {
                subTabContent.classList.remove('active');
            });

            const selectedSubTabContent = document.getElementById(subTabId);
            selectedSubTabContent.classList.add('active');

            const subTabs = document.querySelectorAll('.sub-tab');
            subTabs.forEach(subTab => {
                subTab.classList.remove('active');
            });

            document.querySelector(`.sub-tab[onclick="showSubTab('${subTabId}')"]`).classList.add('active');
        }

        // Statistics chart for logs
        const chartData = {
            labels: {!! json_encode([
                'Accounts',
                'Registrations',
                'Logins',
                'Appointments',
                'Complaints',
                'Dental Records',
                'Medical Records',
                'Physical & Dental Exams',
            ]) !!},
            datasets: [{
                label: 'Number of Logs',
                data: {!! json_encode([
                    $accountLogsCount ?? 0,
                    $registrationLogsCount ?? 0,
                    $loginLogsCount ?? 0,
                    $appointmentLogsCount ?? 0,
                    $complaintLogsCount ?? 0,
                    $dentalRecordLogsCount ?? 0,
                    $medicalRecordLogsCount ?? 0,
                    $physicalDentalExamLogsCount ?? 0,
                ]) !!},
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',    // Accounts
                    'rgba(54, 162, 235, 0.2)',    // Registrations
                    'rgba(255, 206, 86, 0.2)',    // Logins
                    'rgba(153, 102, 255, 0.2)',   // Appointments
                    'rgba(255, 99, 132, 0.2)',    // Complaints
                    'rgba(255, 159, 64, 0.2)',    // Dental Records
                    'rgba(255, 205, 86, 0.2)',    // Medical Records
                    'rgba(201, 203, 207, 0.2)'    // Physical & Dental Exams
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',      // Accounts
                    'rgba(54, 162, 235, 1)',      // Registrations
                    'rgba(255, 206, 86, 1)',      // Logins
                    'rgba(153, 102, 255, 1)',     // Appointments
                    'rgba(255, 99, 132, 1)',      // Complaints
                    'rgba(255, 159, 64, 1)',      // Dental Records
                    'rgba(255, 205, 86, 1)',      // Medical Records
                    'rgba(201, 203, 207, 1)'      // Physical & Dental Exams
                ],
                borderWidth: 1
            }]
        };

        const ctx = document.getElementById('logStatisticsChart').getContext('2d');
        const logStatisticsChart = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
