<x-app-layout>
    <style>
body {
    background-color: #f8f9fa;
    font-family: 'Arial', sans-serif;
}

.main-content {
    flex-grow: 1;
    padding: 20px;
    margin-left: 75px; /* Adjusted to match sidebar width */
    transition: margin-left 0.3s ease-in-out;
}

.sidebar:hover ~ .main-content {
    margin-left: 250px; /* Adjusted to match expanded sidebar width */
}

.profile-box {
    display: flex;
    align-items: center;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
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
    color: #333;
}

.profile-info p {
    margin: 0;
    color: #666;
}

.statistics {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    margin-top: 20px;
}

.statistics .stat-box {
    background-color: #ffffff;
    color: #333;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    width: 23%;
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

.chart-container {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    width: 100%;
}

.data-table-wrapper {
    margin-top: 20px;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th, .data-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
}

.data-table th {
    background-color: #f9f9f9;
}

.data-table tr:hover {
    background-color: #f1f1f1;
}

.edit-btn {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;
}

.edit-btn:hover {
    background-color: #0056b3;
}

.nav-tabs {
    border-bottom: 1px solid #ddd;
}

.nav-tabs .nav-item {
    margin-bottom: -1px;
}

.nav-tabs .nav-link {
    border: 1px solid transparent;
    border-top-left-radius: 0.25rem;
    border-top-right-radius: 0.25rem;
}

.nav-tabs .nav-link:hover {
    border-color: #e9ecef #e9ecef #ddd;
}

.nav-tabs .nav-link.active {
    color: #495057;
    background-color: #fff;
    border-color: #ddd #ddd #fff;
}

.tab-content {
    padding: 20px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-top: none;
}


    </style>

    <div class="main-content">
        <div class="profile-box">
            <img src="{{ asset('images/pilarLogo.jpg') }}" alt="Profile Image">
            <div class="profile-info">
                <h2>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h2>
                <p>{{ Auth::user()->role }}</p>
                <a href="{{ route('profile.edit') }}" class="btn btn-primary mt-2">Edit Profile</a>
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
                    <img src="https://img.icons8.com/ios-filled/50/000000/pending.png" alt="Pending Approval Icon">
                    <h2>{{ $pendingApprovalCount }}</h2>
                    <p>Pending Approvals</p>
                </a>
            </div>
        </div>

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
                                <th>Action</th>
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
                                    <td>
                                        <a href="#" class="edit-btn">Edit</a>
                                    </td>
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
                                <th>Action</th>
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
                                    <td>
                                        <a href="#" class="edit-btn">Edit</a>
                                    </td>
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
                                <th>Action</th>
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
                                    <td>
                                        <a href="#" class="edit-btn">Edit</a>
                                    </td>
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
                                <th>Action</th>
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
                                    <td>
                                        <a href="#" class="edit-btn">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                    label: 'Patients',
                    data: [12, 19, 3, 5, 2, 3, 10, 15, 8, 9, 10, 11],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }, {
                    label: 'Revenue',
                    data: [1200, 1900, 300, 500, 200, 300, 1000, 1500, 800, 900, 1000, 1100],
                    type: 'line',
                    fill: false,
                    borderColor: 'rgba(153, 102, 255, 1)'
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
