<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .container {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }

        .main-content {
            margin-left: 80px;
            margin-top: 20px;
            width: calc(100% - 80px);
            padding: 20px;
            transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
        }

        .profile-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }

        .profile-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 300px;
            animation: fadeInUp 0.5s ease-in-out;
            cursor: pointer;
        }

        .profile-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .profile-card-content {
            padding: 20px;
            text-align: center;
        }

        .profile-card-content h3 {
            margin: 10px 0;
            font-size: 1.5rem;
            color: #333;
        }

        .profile-card-content p {
            color: #666;
            margin: 5px 0;
        }

        .profile-card-content .social-icons {
            margin-top: 15px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .profile-card-content .social-icons a {
            color: #333;
            font-size: 1.2rem;
            transition: color 0.3s;
        }

        .profile-card-content .social-icons a:hover {
            color: #007bff;
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
        <h1>Profile View of {{ ucfirst(Auth::user()->role) }}</h1>

        <!-- Tab Navigation -->
        <div class="tabs">
            <button class="tab-btn" onclick="switchTab('students')">Students</button>
            <button class="tab-btn" onclick="switchTab('teachers')">Teachers</button>
            <button class="tab-btn" onclick="switchTab('staff')">Staff</button>
            <button class="tab-btn" onclick="switchTab('parents')">Parents</button>
        </div>

        <!-- Profile Cards -->
        <div class="profile-cards">
            @foreach($profiles as $profile)
                <div class="profile-card" onclick="openModal('{{ $profile->name }}', '{{ $profile->role }} | Born {{ $profile->birthdate }}', 'Activity Level: {{ rand(50, 100) }}%', 'Description about {{ $profile->name }}')">
                    <img src="{{ asset('images/profiles/' . $profile->profile_picture) }}" alt="Profile Image">
                    <div class="profile-card-content">
                        <h3>{{ $profile->name }}</h3>
                        <p>{{ $profile->role }} | Born {{ $profile->birthdate }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal -->
    <div id="profile-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title">Profile Details</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body" id="modal-body"></div>
        </div>
    </div>

    <script>
        // Open Modal Function
        function openModal(name, status, activity, description) {
            document.getElementById('modal-title').innerText = name;
            document.getElementById('modal-body').innerHTML = `
                <p><strong>Status:</strong> ${status}</p>
                <p><strong>Activity Level:</strong> ${activity}</p>
                <p><strong>Description:</strong> ${description}</p>
            `;
            document.getElementById('profile-modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('profile-modal').style.display = 'none';
        }

        // Handle switching tabs
        function switchTab(role) {
            window.location.href = `/admin/profiles?role=${role}`;
        }
    </script>
</x-app-layout>