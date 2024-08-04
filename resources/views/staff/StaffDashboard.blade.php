<x-app-layout>
    <style>
        .container {
            display: flex;
            min-height: 100vh;
        }
        .main-content {
            margin-left: 80px; /* Collapsed sidebar width */
            width: 100%;
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
        }
        .sidebar:hover ~ .main-content {
            margin-left: 250px; /* Expanded sidebar width */
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
        .image-container-dashboard {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 80px); /* Adjust based on header height */
        }
        .image-container-dashboard img {
            max-width: 100%;
            height: auto;
            animation: fadeIn 1s ease-in-out;
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

    <div class="container">
        <x-staffsidebar />

        <main class="main-content">
            <!-- Header -->
            

            <!-- Welcome message -->
            <h1 class="h1-header">Welcome, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}!</h1>

            <!-- Image container -->
            <div class="image-container-dashboard">
                <img src="{{ asset('images/pilarLogo.png') }}" alt="Login Image">
            </div>
        </main>
    </div>

    
</x-app-layout>
