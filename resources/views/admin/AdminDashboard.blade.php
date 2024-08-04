<x-app-layout>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            margin-top: 40px;
            margin-left: 75px;
            transition: margin-left 0.3s ease-in-out;
            overflow-y: auto;
            max-height: auto;
            
        }

        .sidebar:hover ~ .main-content {
            margin-left: 250px;
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
                <a href="{{ route('admin.appointment') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/appointment-reminders.png" alt="Appointments Icon">
                    <h2>{{ $appointmentCount }}</h2>
                    <p>Appointments</p>
                </a>
            </div>
            <div class="stat-box">
                <a href="{{ route('admin.inventory') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/warehouse.png" alt="Inventory Icon">
                    <h2>{{ $inventoryCount }}</h2>
                    <p>Inventory Items</p>
                </a>
            </div>
            <div class="stat-box">
                <a href="{{ route('admin.complaint') }}">
                    <img src="https://img.icons8.com/ios-filled/50/000000/complaint.png" alt="Complaints Icon">
                    <h2>{{ $complaintCount }}</h2>
                    <p>Complaints</p>
                </a>
            </div>
            <div class="stat-box">
                <a href="{{ route('admin.pendingApproval') }}">
                    <img src="https://img.icons8.com/?size=100&id=10247&format=png&color=000000" alt="Pending Approval Icon">
                    <h2>{{ $pendingApprovalCount }}</h2>
                    <p>Pending Approvals</p>
                </a>
            </div>
        </div>

        <div class="content-row">
            <div class="chart-container">
                <h3>Monthly Registered Users</h3>
                <canvas id="monthlyUsersChart"></canvas>
            </div>

            <div class="data-table-wrapper">
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
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="student" role="tabpanel" aria-labelledby="student-tab">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Sl.No</th>
                                    <th>Name</th>
                                    <th>Degree</th>
                                    <th>Contact No</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                        <td>{{ $student->degree }}</td>
                                        <td>{{ $student->contact_no }}</td>
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
                                    <th>Sl.No</th>
                                    <th>Name</th>
                                    <th>Degree</th>
                                    <th>Contact No</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($staff as $staffMember)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $staffMember->first_name }} {{ $staffMember->last_name }}</td>
                                        <td>{{ $staffMember->degree }}</td>
                                        <td>{{ $staffMember->contact_no }}</td>
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
                                    <th>Sl.No</th>
                                    <th>Name</th>
                                    <th>Degree</th>
                                    <th>Contact No</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($parents as $parent)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $parent->first_name }} {{ $parent->last_name }}</td>
                                        <td>{{ $parent->degree }}</td>
                                        <td>{{ $parent->contact_no }}</td>
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
                                    <th>Sl.No</th>
                                    <th>Name</th>
                                    <th>Degree</th>
                                    <th>Contact No</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teachers as $teacher)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $teacher->first_name }} {{ $teacher->last_name }}</td>
                                        <td>{{ $teacher->degree }}</td>
                                        <td>{{ $teacher->contact_no }}</td>
                                        <td>{{ $teacher->email }}</td>
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
        const ctx = document.getElementById('monthlyUsersChart').getContext('2d');
        const monthlyUsersChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Students',
                    data: [12, 19, 3, 5, 2, 3, 10, 15, 8, 9, 10, 11],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }, {
                    label: 'Teachers',
                    data: [8, 15, 5, 10, 6, 7, 9, 12, 5, 6, 8, 10],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'Staff',
                    data: [5, 10, 2, 4, 3, 5, 6, 8, 4, 5, 6, 7],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }, {
                    label: 'Parents',
                    data: [7, 12, 4, 8, 5, 6, 7, 10, 6, 7, 9, 11],
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderColor: 'rgba(255, 206, 86, 1)',
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
