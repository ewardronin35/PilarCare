<x-app-layout>
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
        <div class="profile-box">
            <img src="{{ asset('images/pilarLogo.jpg') }}" alt="Profile Image">
            <div class="profile-info">
                <h2>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h2>
                <p>{{ Auth::user()->role }}</p>
                <a href="{{ route('profile.edit') }}" class="edit-profile-btn">Edit Profile</a>
            </div>
        </div>

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
  
    <!-- New Stat Boxes -->
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

        <div class="content-row">
            <div class="chart-container">
                <h3>Monthly Registered Users</h3>
                <canvas id="monthlyUsersChart"></canvas>
            </div>

            <div class="data-table-wrapper">
            <h1>Registered Users</h1>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="student-tab" data-toggle="tab" href="#student" role="tab" aria-controls="student" aria-selected="true">Student</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="staff-tab" data-toggle="tab" href="#staff" role="tab" aria-controls="staff" aria-selected="false">Staff</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="parent-tab" data-toggle="tab" href="#parent" role="tab" aria-controls="parent" aria-selected="false">Parent</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="teacher-tab" data-toggle="tab" href="#teacher" role="tab" aria-controls="teacher" aria-selected="false">Teacher</a>
                    </li>
                    <li class="nav-item">
    <a class="nav-link" id="doctor-tab" data-toggle="tab" href="#doctor" role="tab" aria-controls="doctor" aria-selected="false">Doctor</a>
</li>
<li class="nav-item">
    <a class="nav-link" id="nurse-tab" data-toggle="tab" href="#nurse" role="tab" aria-controls="nurse" aria-selected="false">Nurse</a>
</li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="student" role="tabpanel" aria-labelledby="student-tab">
                        
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID No.</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    <tr>
                                        <td>{{ $student->id_number }}</td>
                                        <td>{{ $student->first_name }}</td>
                                        <td>{{ $student->last_name }}</td>
                                        <td>{{ $student->email }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="staff" role="tabpanel" aria-labelledby="staff-tab">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID No.</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($staff as $staffMember)
                                    <tr>
                                    <td>{{ $staffMember->id_number }}</td>
                                        <td>{{ $staffMember->first_name }}</td>
                                        <td>{{ $staffMember->last_name }}</td>
                                        <td>{{ $staffMember->email }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="parent" role="tabpanel" aria-labelledby="parent-tab">
                        <table class="data-table">
                            <thead>
                                <tr>
                                <th>ID No.</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($parents as $parent)
                                    <tr>
                                        <td>{{ $parent->id_number }}</td>
                                        <td>{{ $parent->first_name }}</td>
                                        <td>{{ $parent->last_name }}</td>
                                        <td>{{ $parent->email }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="teacher" role="tabpanel" aria-labelledby="teacher-tab">
                        <table class="data-table">
                            <thead>
                                <tr>
                                <th>ID No.</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teachers as $teacher)
                                    <tr>
                                    <td>{{ $teacher->id_number }}</td>
                                        <td>{{ $teacher->first_name }}</td>
                                        <td>{{ $teacher->last_name }}</td>
                                        <td>{{ $teacher->email }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="doctor" role="tabpanel" aria-labelledby="doctor-tab">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID No.</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($doctors as $doctor)
                                    <tr>
                                        <td>{{ $doctor->id_number }}</td>
                                        <td>{{ $doctor->first_name }}</td>
                                        <td>{{ $doctor->last_name }}</td>
                                        <td>{{ $doctor->email }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Nurse Tab -->
                    <div class="tab-pane fade" id="nurse" role="tabpanel" aria-labelledby="nurse-tab">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID No.</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nurses as $nurse)
                                    <tr>
                                        <td>{{ $nurse->id_number }}</td>
                                        <td>{{ $nurse->first_name }}</td>
                                        <td>{{ $nurse->last_name }}</td>
                                        <td>{{ $nurse->email }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
            const monthlyUserData = @json($monthlyUserData);
        const ctx = document.getElementById('monthlyUsersChart').getContext('2d');
        const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const roles = ['Student', 'Teacher', 'Staff', 'Parent', 'Doctor', 'Nurse'];
        const colors = {
            'Student': 'rgba(75, 192, 192, 0.2)',
            'Teacher': 'rgba(255, 99, 132, 0.2)',
            'Staff': 'rgba(54, 162, 235, 0.2)',
            'Parent': 'rgba(255, 206, 86, 0.2)',
            'Doctor': 'rgba(153, 102, 255, 0.2)',
            'Nurse': 'rgba(255, 159, 64, 0.2)'
        };

        const borderColors = {
            'Student': 'rgba(75, 192, 192, 1)',
            'Teacher': 'rgba(255, 99, 132, 1)',
            'Staff': 'rgba(54, 162, 235, 1)',
            'Parent': 'rgba(255, 206, 86, 1)',
            'Doctor': 'rgba(153, 102, 255, 1)',
            'Nurse': 'rgba(255, 159, 64, 1)'
        };

        const datasets = roles.map(role => ({
            label: role + 's',
            data: monthlyUserData[role],
            backgroundColor: colors[role],
            borderColor: borderColors[role],
            borderWidth: 1
        }));
        const monthlyUsersChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(tab => tab.classList.remove('active', 'show'));

                this.classList.add('active');
                const tabId = this.getAttribute('href').substring(1);
                document.getElementById(tabId).classList.add('active', 'show');
            });
        });
    </script>
</x-app-layout>
