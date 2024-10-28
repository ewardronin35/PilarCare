<x-app-layout :pageTitle="' Dashboard'">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .main-content {
            
            margin-top: 40px;
            transition: margin-left 0.3s ease-in-out;
            overflow-y: auto;
            
        }

        .profile-box {
            display: flex;
            align-items: center;
            padding: 20px;
            background-image: url('{{ asset('images/bg.jpg') }}');
            background-size: cover;
            background-position: center;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            color: white;
        }

        .profile-box img {
            border-radius: 50%;
            width: 80px;
            height: 80px;
            margin-right: 20px;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
        }

        .profile-info h2 {
            margin: 0;
            font-size: 1.5em;
        }

        .profile-info p {
            margin: 0;
        }

        .edit-profile-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            text-align: center;
            text-decoration: none;
            margin-top: 10px;
        }

        .edit-profile-btn:hover {
            background-color: #0056b3;
        }

        .statistics {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            gap: 10px;
        }

        .statistics .stat-box {
            background-color: #ffffff;
            color: #333;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: calc(25% - 10px);
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease-in-out;
        }

        .statistics .stat-box:hover {
            background-color: #f0f0f0;
        }

        .stat-box img {
            width: 50px;
            height: 50px;
            display: block;
            margin: 0 auto 10px;
        }

        .stat-box a {
            color: #007bff;
            text-decoration: none;
        }

        .stat-box a:visited {
            color: #007bff;
        }

        .stat-box a:hover {
            text-decoration: none;
        }

        .content-row {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            gap: 10px;
        }

        .chart-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 49%;
        }

        .data-table-wrapper {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 49%;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .data-table th, .data-table td {
            padding: 10px;
            text-align: left;
        }

        .data-table th {
            background-color: #f5f5f5;
            color: #333;
            font-weight: 600;
            border-bottom: 1px solid #ddd;
        }

        .data-table td {
            border-bottom: 1px solid #eee;
        }

        .nav-tabs {
            border-bottom: 1px solid #ddd;
            display: flex;
            gap: 10px;
            list-style: none; /* Remove default list-style */
            padding-left: 0;
        }

        .nav-tabs .nav-item {
            margin-bottom: -1px;
        }

        .nav-tabs .nav-link {
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease-in-out;
        }

        .nav-tabs .nav-link:hover {
            background-color: #0056b3;
        }

        .nav-tabs .nav-link.active {
            background-color: #0056b3;
        }

        .tab-content {
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-top: none;
        }

        .tab-pane {
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .tab-pane.active {
            display: block;
            opacity: 1;
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
        <!-- Profile Box -->
        <div class="profile-box">
            <img src="{{ asset('images/pilarLogo.jpg') }}" alt="Profile Image">
            <div class="profile-info">
                <h2>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h2>
                <p>{{ ucfirst(Auth::user()->role) }}</p>
                <a href="{{ route('profile.edit') }}" class="edit-profile-btn">Edit Profile</a>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="statistics">
            <div class="stat-box">
                <a href="{{ route('nurse.appointment') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/appointment-reminders.png" alt="Appointments Icon">
                    <h2>{{ $appointmentCount }}</h2>
                    <p>Appointments</p>
                </a>
            </div>
            <div class="stat-box">
                <a href="{{ route('nurse.inventory') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/warehouse.png" alt="Inventory Icon">
                    <h2>{{ $inventoryCount }}</h2>
                    <p>Inventory Items</p>
                </a>
            </div>
            <div class="stat-box">
                <a href="{{ route('nurse.complaint') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/complaint.png" alt="Complaints Icon">
                    <h2>{{ $complaintCount }}</h2>
                    <p>Complaints</p>
                </a>
            </div>
            <div class="stat-box">
                <a href="{{ route('nurse.pendingApproval') }}">
                    <img src="https://img.icons8.com/?size=100&id=10247&format=png&color=000000" alt="Pending Approval Icon">
                    <h2>{{ $pendingApprovalCount }}</h2>
                    <p>Pending Approvals</p>
                </a>
            </div>
            <div class="stat-box">
                <a href="{{ route('nurse.uploadMedicalDocu') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/medical-doctor.png" alt="Medical Records Icon">
                    <h2>{{ $medicalRecordCount }}</h2>
                    <p>Medical Records</p>
                </a>
            </div>
            <div class="stat-box">
                <a href="{{ route('nurse.uploadDentalDocu') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/tooth.png" alt="Dental Records Icon">
                    <h2>{{ $dentalRecordCount }}</h2>
                    <p>Dental Records</p>
                </a>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="content-row">
            <!-- Submissions per Role Chart -->
            <div class="chart-container">
                <h3>Submissions per Role</h3>
                <canvas id="submissionsChart"></canvas>
            </div>

          <!-- Complaints by Confine Status and Go Home Chart -->
          <div class="chart-container">
                <h3>Complaints by Confine Status and Go Home</h3>
                <canvas id="complaintsChart" aria-label="Complaints by Confine Status and Go Home Chart" role="img"></canvas>
            </div>

            <!-- Inventory by Category Chart -->
            <div class="chart-container">
                <h3>Inventory by Category</h3>
                <canvas id="inventoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Submissions per Role Chart
        const submissionsData = @json($submissionsPerRole);
        const roles = Object.keys(submissionsData);
        const healthExams = roles.map(role => submissionsData[role].health_examinations);
        const dentalRecords = roles.map(role => submissionsData[role].dental_records);
        const medicalRecords = roles.map(role => submissionsData[role].medical_records);

        const ctxSubmissions = document.getElementById('submissionsChart').getContext('2d');

        const submissionsChart = new Chart(ctxSubmissions, {
            type: 'bar',
            data: {
                labels: roles,
                datasets: [
                    {
                        label: 'Health Examinations',
                        data: healthExams,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Teal
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Dental Records',
                        data: dentalRecords,
                        backgroundColor: 'rgba(255, 206, 86, 0.2)', // Yellow
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Medical Records',
                        data: medicalRecords,
                        backgroundColor: 'rgba(153, 102, 255, 0.2)', // Purple
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Submissions per Role'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    },
                    legend: {
                        position: 'top',
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Submissions'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Roles'
                        }
                    }
                }
            }
        });

        // Complaints by Confine Status and Go Home Chart (Grouped Bar Chart)
        const complaintsConfineData = @json($complaintsByConfineStatus);
        const confineStatuses = Object.keys(complaintsConfineData);
        const confineCounts = Object.values(complaintsConfineData);

        const complaintsGoHomeData = @json($complaintsByGoHome);
        const goHomeStatuses = Object.keys(complaintsGoHomeData);
        const goHomeCounts = Object.values(complaintsGoHomeData);

        // Combine the labels (unique)
        const combinedLabels = [...new Set([...confineStatuses, ...goHomeStatuses])];

        // Map counts to labels, defaulting to 0 if the category doesn't exist
        const confineData = combinedLabels.map(label => confineCounts[confineStatuses.indexOf(label)] || 0);
        const goHomeData = combinedLabels.map(label => goHomeCounts[goHomeStatuses.indexOf(label)] || 0);

        const ctxComplaints = document.getElementById('complaintsChart').getContext('2d');

        const complaintsChart = new Chart(ctxComplaints, {
            type: 'bar',
            data: {
                labels: combinedLabels,
                datasets: [
                    {
                        label: 'Confine Status',
                        data: confineData,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)', // Red
                        borderColor: 'rgba(255, 99, 132, 1)',       // Red
                        borderWidth: 1
                    },
                    {
                        label: 'Go Home',
                        data: goHomeData,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)', // Blue
                        borderColor: 'rgba(54, 162, 235, 1)',       // Blue
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Complaints by Confine Status and Go Home'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    },
                    legend: {
                        position: 'top',
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Complaints'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Categories'
                        }
                    }
                }
            }
        });

        // Inventory by Category Chart (Bar Chart)
        const inventoryData = @json($inventoryByCategory);
        const inventoryCategories = Object.keys(inventoryData);
        const inventoryCounts = Object.values(inventoryData);

        const ctxInventory = document.getElementById('inventoryChart').getContext('2d');

        const inventoryChart = new Chart(ctxInventory, {
            type: 'bar',
            data: {
                labels: inventoryCategories,
                datasets: [{
                    label: 'Inventory Items',
                    data: inventoryCounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)', // Blue
                    borderColor: 'rgba(54, 162, 235, 1)',       // Blue
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Inventory by Category'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    },
                    legend: {
                        display: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Items'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Categories'
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
