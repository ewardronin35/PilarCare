<x-app-layout :pageTitle="' Profiles'">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
 
        /* Tab Navigation Styling */
        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
        }

        .tab-btn {
            background: none;
            border: none;
            padding: 15px 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            color: #555;
            transition: color 0.3s, border-bottom 0.3s;
        }

        .tab-btn:hover {
            color: #007bff;
        }

        .tab-btn.active {
            color: #007bff;
            border-bottom: 3px solid #007bff;
        }

        /* Profile Cards Container */
        .profile-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            opacity: 1;
            transition: opacity 0.5s ease-in-out;
        }

        .profile-cards.fade-out {
            opacity: 0;
        }

        /* Individual Profile Card Styling */
        .profile-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 280px;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .profile-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            background-color: #f0f0f0;
        }

        .profile-card-content {
            padding: 15px;
            text-align: center;
        }

        .profile-card-content h3 {
            margin: 10px 0 5px 0;
            font-size: 1.3rem;
            color: #333;
        }

        .profile-card-content p {
            margin: 5px 0;
            color: #666;
            font-size: 0.95rem;
        }

        /* Social Icons Styling (Optional) */
        .social-icons {
            margin-top: 10px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .social-icons a {
            color: #555;
            font-size: 1.1rem;
            transition: color 0.3s;
        }

        .social-icons a:hover {
            color: #007bff;
        }

        /* Modal Styling */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6); /* Dark background with opacity */
            animation: fadeIn 0.3s ease-in-out;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 25px;
            border: 1px solid #888;
            width: 90%;
            max-width: 600px;
            border-radius: 10px;
            animation: slideIn 0.3s ease-in-out;
            position: relative;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.8rem;
            color: #007bff;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
        }

        .modal-body p {
            margin: 10px 0;
            color: #555;
            font-size: 1rem;
        }

        /* Animations */
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .tabs {
                flex-direction: column;
                align-items: center;
            }

            .tab-btn {
                width: 100%;
                text-align: center;
                border-bottom: 1px solid #ddd;
            }

            .tab-btn:last-child {
                border-bottom: none;
            }

            .profile-cards {
                flex-direction: column;
                align-items: center;
            }

            .profile-card {
                width: 90%;
            }
        }

        /* Search Bar Styling */
        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-bar input {
            width: 300px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px 0 0 5px;
            outline: none;
            font-size: 1rem;
        }

        .search-bar button {
            padding: 10px 15px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 1rem;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }
    </style>

    <div class="main-content">
        <h1 class="text-3xl font-bold text-center mb-6">Profile View of {{ ucfirst(Auth::user()->role) }}</h1>

        <!-- Search Bar -->
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Search by name or ID number..." onkeyup="searchProfiles()" />
            <button onclick="searchProfiles()">Search</button>
        </div>

        <!-- Tab Navigation -->
        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('students')">Students</button>
            <button class="tab-btn" onclick="switchTab('teachers')">Teachers</button>
            <button class="tab-btn" onclick="switchTab('nurses')">Nurses</button>
            <button class="tab-btn" onclick="switchTab('doctors')">Doctors</button>
            <button class="tab-btn" onclick="switchTab('staff')">Staff</button>
            <button class="tab-btn" onclick="switchTab('parents')">Parents</button>
        </div>

        <!-- Profile Cards -->
        <div class="profile-cards" id="profile-cards">
            @forelse($profiles as $profile)
                <div class="profile-card" onclick="openModal('{{ addslashes($profile->first_name) }} {{ addslashes($profile->last_name) }}', '{{ addslashes($profile->role) }}', '{{ addslashes($profile->birthdate) }}', '{{ addslashes($profile->description) }}', '{{ addslashes($profile->id_number) }}')">
                    <img src="{{ $profile->profile_picture 
                        ? asset('images/profiles/' . $profile->profile_picture) 
                        : asset('images/pilarLogo.png') }}" 
                        alt="{{ $profile->first_name }} {{ $profile->last_name }} Profile Image" 
                        loading="lazy">
                    <div class="profile-card-content">
                        <h3>{{ $profile->first_name }} {{ $profile->last_name }}</h3>
                        <p>{{ ucfirst($profile->role) }} | ID: {{ $profile->id_number }}</p>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500">No profiles available for this category.</p>
            @endforelse
        </div>
    </div>

    <!-- Modal -->
    <div id="profile-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title">Profile Details</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body" id="modal-body">
                <!-- Dynamic Content -->
            </div>
        </div>
    </div>

    <script>
        // Open Modal Function
        function openModal(name, role, birthdate, description, id_number) {
            document.getElementById('modal-title').innerText = name;
            document.getElementById('modal-body').innerHTML = `
                <p><strong>Role:</strong> ${capitalizeFirstLetter(role)}</p>
                <p><strong>Birthdate:</strong> ${formatDate(birthdate)}</p>
                <p><strong>ID Number:</strong> ${id_number}</p>
                <p><strong>Description:</strong> ${description}</p>
            `;
            document.getElementById('profile-modal').style.display = 'block';
        }

        // Close Modal Function
        function closeModal() {
            document.getElementById('profile-modal').style.display = 'none';
        }

        // Close modal when clicking outside of the modal content
        window.onclick = function(event) {
            const modal = document.getElementById('profile-modal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Function to switch tabs and filter profiles
        function switchTab(role) {
            // Remove 'active' class from all buttons
            const tabs = document.querySelectorAll('.tab-btn');
            tabs.forEach(tab => tab.classList.remove('active'));

            // Add 'active' class to the clicked button
            event.currentTarget.classList.add('active');

            // Show loading indicator or fade out existing profiles
            const profileCards = document.getElementById('profile-cards');
            profileCards.classList.add('fade-out');

            // After fade-out transition, fetch and display new profiles
            setTimeout(() => {
                fetchProfiles(role, document.getElementById('search-input').value);
            }, 300);
        }

        // Function to fetch profiles based on role and search query
        function fetchProfiles(role, searchQuery = '') {
            fetch(`/nurse/profiles?role=${role}&search=${encodeURIComponent(searchQuery)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const profileCards = document.getElementById('profile-cards');
                profileCards.classList.remove('fade-out');
                profileCards.innerHTML = '';

                if(data.length > 0){
                    data.forEach(profile => {
                        profileCards.innerHTML += `
                            <div class="profile-card" onclick="openModal('${escapeHtml(profile.first_name)} ${escapeHtml(profile.last_name)}', '${escapeHtml(profile.role)}', '${escapeHtml(profile.birthdate)}', '${escapeHtml(profile.description)}', '${escapeHtml(profile.id_number)}')">
                                <img src="${profile.profile_picture 
                                    ? '${'{{ asset('images/profiles') }}/' + profile.profile_picture}' 
                                    : '{{ asset('images/pilarLogo.png') }}'}" 
                                    alt="${escapeHtml(profile.first_name)} ${escapeHtml(profile.last_name)} Profile Image" 
                                    loading="lazy">
                                <div class="profile-card-content">
                                    <h3>${escapeHtml(profile.first_name)} ${escapeHtml(profile.last_name)}</h3>
                                    <p>${capitalizeFirstLetter(profile.role)} | ID: ${profile.id_number}</p>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    profileCards.innerHTML = `<p class="text-center text-gray-500">No profiles available for this category.</p>`;
                }
            })
            .catch(error => {
                console.error('Error fetching profiles:', error);
                const profileCards = document.getElementById('profile-cards');
                profileCards.classList.remove('fade-out');
                profileCards.innerHTML = `<p class="text-center text-red-500">Failed to load profiles. Please try again later.</p>`;
            });
        }

        // Function to handle search
        function searchProfiles() {
            const searchQuery = document.getElementById('search-input').value;
            const activeTab = document.querySelector('.tab-btn.active');
            const role = activeTab ? activeTab.textContent.toLowerCase() : 'students';
            fetchProfiles(role, searchQuery);
        }

        // Helper function to format date
        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString(undefined, options);
        }

        // Helper function to capitalize the first letter
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        // Helper function to escape HTML to prevent XSS
        function escapeHtml(text) {
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        // Initialize with default tab (Students)
        document.addEventListener('DOMContentLoaded', function () {
            fetchProfiles('students');
        });
    </script>
</x-app-layout>
