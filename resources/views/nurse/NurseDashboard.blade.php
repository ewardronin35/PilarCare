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
    position: relative;
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
    overflow: hidden;
}

.profile-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1;
}

.profile-box img {
    border-radius: 50%;
    width: 80px;
    height: 80px;
    margin-right: 20px;
    z-index: 2;
}

.profile-info {
    display: flex;
    flex-direction: column;
    z-index: 2;
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
    flex-wrap: wrap; /* Allow items to wrap to the next line */
    justify-content: center; /* Center items horizontally */
    margin-top: 20px;
    gap: 20px; /* Adjust gap between stat boxes */
}

.statistics .stat-box {
    background-color: #ffffff;
    color: #333;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    width: calc(47% - 20px); /* Adjusted for 2 per row */
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
    list-style: none;
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

/* Media Queries for Responsiveness */

/* For mobile devices */
@media (max-width: 768px) {
    .statistics .stat-box {
        width: 100%; /* 1 per row */
    }
}

    </style>

<div class="main-content">
        <!-- Profile Box -->
        <div class="profile-box">
            <img src="{{ asset('images/pilarLogo.jpg') }}" alt="Profile Image">
            <div class="profile-info">
            <h2>{{ $nurseName }}</h2>
            <p>{{ ucfirst(Auth::user()->role) }}</p>
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
       

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
     
    </script>
</x-app-layout>
