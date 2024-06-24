<x-app-layout>
    <style>
        .main-content {
            flex-grow: 1;
            padding: 20px;
            margin-left: 80px; /* Adjust margin to accommodate the sidebar */
            transition: margin-left 0.3s ease-in-out;
        }
        .sidebar:hover + .main-content {
            margin-left: 250px; /* Adjust margin when sidebar is expanded */
        }
        .image-container-dashboard {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(80vh - 80px); /* Adjust based on header height */
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

    <div class="main-content">
        <!-- Welcome message -->
        <h1 class="h1-header" style="margin-top: 80px;">Welcome, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}!</h1>

        <!-- Image container -->
        <div class="image-container-dashboard">
            <img src="{{ asset('images/pilarLogo.png') }}" alt="Login Image">
        </div>
    </div>
</x-app-layout>
