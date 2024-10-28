<!-- resources/views/partials/admin-sidebar.blade.php -->
<div class="sidebar">
    <!-- Inline CSS (Consider Moving to an External Stylesheet) -->
    <style>
        /* Import Poppins Font */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

        /* General Sidebar Styles */
        .sidebar {
            width: 108px; /* Collapsed width */
            background-color: #00CFFF;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            transition: width 0.3s ease-in-out;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
            z-index: 1000;
            padding: 20px;
        }

        /* Hover Effect for Larger Screens */
        @media (min-width: 769px) {
            .sidebar:hover {
                width: 280px; /* Expanded width */
            }

            .sidebar:hover .logo-text,
            .sidebar:hover .menu-text,
            .sidebar:hover .submenu-toggle {
                opacity: 1;
                display: block;
            }

            /* Hide submenus and related text when not hovered and not active */
            .sidebar:not(:hover) .menu li:not(.active) .submenu {
                max-height: 0 !important;
                opacity: 0;
                display: none;
            }

            .sidebar:not(:hover) .menu li:not(.active) .menu-text,
            .sidebar:not(:hover) .menu li:not(.active) .submenu-toggle {
                opacity: 0;
                display: none;
            }

            /* Ensure active submenus are visible */
            .menu li.active .submenu {
                max-height: 500px; /* Adjust as needed */
                display: flex;
                opacity: 1;
            }

            .menu li.active .submenu-toggle {
                opacity: 1;
                display: block;
            }
        }

        /* Logo Styles */
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease-in-out;
            width: 100%;
            margin-bottom: 20px; /* Add space below the logo */
        }

        .logo-img {
            width: 40px;
            height: 40px;
            margin-right: 0;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: bold;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            display: none;
        }

        .sidebar:hover .logo-text {
            opacity: 1;
            display: block;
        }

        /* Menu Styles */
        .menu {
            flex-grow: 1;
            padding: 0;
        }

        .menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu > ul > li {
            display: flex;
            align-items: center;
            padding: 10px 0;
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
            cursor: pointer;
            flex-direction: column;
            align-items: center;
        }

        .menu > ul > li:hover {
            background-color: #1f1f90;
            color: white;
            transform: translateX(10px);
        }

        .menu > ul > li a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            width: 100%;
            flex-direction: column;
            align-items: center;
        }

        .menu > ul > li:hover a {
            color: white;
        }

        .menu > ul > li a .icon {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .menu-text {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            display: none;
            text-align: center;
        }

        /* Submenu Styles */
        .submenu {
            display: none;
            flex-direction: column;
            width: 100%;
            transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
            overflow: hidden;
        }

        .menu li.active .submenu {
            max-height: 500px; /* Adjust as needed */
            display: flex;
            opacity: 1;
        }

        .submenu li {
            padding: 10px 20px;
            margin: 0;
            width: 100%;
            color: #000;
            transition: background-color 0.3s ease-in-out;
            text-align: center;
        }

        .submenu li:hover {
            background-color: #e0e0e0;
        }

        .submenu li a {
            color: #000;
            text-decoration: none;
            display: block;
            width: 100%;
        }

        /* Submenu Toggle */
        .submenu-toggle {
            transition: transform 0.3s ease;
            opacity: 0;
            display: none;
            margin-top: 5px;
        }

        .menu li.active > a > .submenu-toggle {
            transform: rotate(90deg);
            opacity: 1;
            display: block;
        }

        /* Active Menu Item Styling */
        .menu > ul > li.active,
        .submenu > li.active {
            background-color: #1f1f90;
            color: white;
        }

        .menu > ul > li.active > a {
            color: white;
        }

        .submenu > li.active > a {
            color: #000;
        }

        /* Adjustments for hiding submenu and menu text when sidebar is not hovered */
        @media (min-width: 769px) {
            .sidebar:not(:hover) .submenu {
                max-height: 0 !important;
                opacity: 0;
                display: none;
            }

            .sidebar:not(:hover) .menu-text {
                opacity: 0;
                display: none;
            }

            .sidebar:not(:hover) .submenu-toggle {
                display: none;
            }

            .sidebar:hover .menu-text {
                display: block;
            }

            .sidebar:hover .submenu-toggle {
                display: block;
                opacity: 1;
            }
        }

        /* Toggle Button Styles */
        .sidebar-toggle-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #00CFFF;
            border: none;
            color: white;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1100;
            display: none;
            transition: background-color 0.3s ease;
        }

        .sidebar-toggle-btn:hover {
            background-color: #0056b3;
        }

        /* Show Toggle Button and Adjust Sidebar for Smaller Screens */
        @media (max-width: 768px) {
            .sidebar {
                width: 60px; /* Smaller sidebar for mobile */
                transition: width 0.3s ease-in-out;
            }

            /* Disable Hover Expansion on Smaller Screens */
            .sidebar:hover {
                width: 60px; /* No expansion */
            }

            .sidebar:hover .logo-text,
            .sidebar:hover .menu-text,
            .sidebar:hover .submenu-toggle {
                opacity: 0;
                display: none;
            }

            .sidebar.active {
                width: 200px; /* Expanded width when toggled */
            }

            .sidebar.active .logo-text,
            .sidebar.active .menu-text,
            .sidebar.active .submenu-toggle {
                opacity: 1;
                display: block;
            }

            /* Show Toggle Button */
            .sidebar-toggle-btn {
                display: block;
            }

            .main-content {
                margin-left: 60px;
                transition: margin-left 0.3s ease-in-out;
            }

            .sidebar.active ~ .main-content {
                margin-left: 200px;
            }
        }
    </style>
    <!-- Logo Section -->
    <div class="logo">
        <img src="{{ asset('images/pilarLogo.png') }}" alt="PilarCare Logo" class="logo-img">
        <span class="logo-text">PilarCare</span>
    </div>

    <!-- Navigation Menu -->
    <nav class="menu">
        <ul>
            <!-- Dashboard -->
            <li class="{{ Route::currentRouteName() == 'doctor.dashboard' ? 'active' : '' }}">
                <a href="{{ route('doctor.dashboard') }}">
                    <span class="icon"><i class="fas fa-tachometer-alt"></i></span>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            <!-- Complaints -->
            <li class="{{ Route::currentRouteName() == 'doctor.complaint' ? 'active' : '' }}">
                <a href="{{ route('doctor.complaint') }}">
                    <span class="icon"><i class="fas fa-comments"></i></span>
                    <span class="menu-text">Complaints</span>
                </a>
            </li>

            <!-- Records (with Submenu) -->
            <li class="has-submenu {{ Route::currentRouteName() == 'doctor.medical-record.index' || Route::currentRouteName() == 'doctor.dental-record.index' ? 'active' : '' }}">
                <a href="#">
                    <span class="icon"><i class="fas fa-notes-medical"></i></span>
                    <span class="menu-text">Records</span>
                    <span class="submenu-toggle"><i class="fas fa-chevron-down"></i></span>
                </a>
                <ul class="submenu">
                    <li class="{{ Route::currentRouteName() == 'doctor.medical-record.index' ? 'active' : '' }}">
                        <a href="{{ route('doctor.medical-record.index') }}">View Medical Record</a>
                    </li>
                    <li class="{{ Route::currentRouteName() == 'doctor.dental-record.index' ? 'active' : '' }}">
                        <a href="{{ route('doctor.dental-record.index') }}">View Dental Record</a>
                    </li>
                </ul>
            </li>

            <!-- Approvals (with Submenu) -->
            <li class="has-submenu {{ 
                Route::currentRouteName() == 'doctor.health-examinations' || 
                Route::currentRouteName() == 'doctor.uploadMedicalDocu' || 
                Route::currentRouteName() == 'doctor.uploadDentalDocu' ? 'active' : '' }}">
                <a href="#">
                    <span class="icon"><i class="fas fa-check-circle"></i></span>
                    <span class="menu-text">Approvals</span>
                    <span class="submenu-toggle"><i class="fas fa-chevron-down"></i></span>
                </a>
                <ul class="submenu">
                    <li class="{{ Route::currentRouteName() == 'doctor.health-examinations' ? 'active' : '' }}">
                        <a href="{{ route('doctor.health-examinations') }}">Health Approval</a>
                    </li>
                    <li class="{{ Route::currentRouteName() == 'doctor.uploadMedicalDocu' ? 'active' : '' }}">
                        <a href="{{ route('doctor.uploadMedicalDocu') }}">Medical Approval</a>
                    </li>
                    <li class="{{ Route::currentRouteName() == 'doctor.uploadDentalDocu' ? 'active' : '' }}">
                        <a href="{{ route('doctor.uploadDentalDocu') }}">Dental Approval</a>
                    </li>
                </ul>
            </li>


            <!-- Appointment -->
            <li class="{{ Route::currentRouteName() == 'doctor.appointment' ? 'active' : '' }}">
                <a href="{{ route('doctor.appointment') }}">
                    <span class="icon"><i class="fas fa-calendar-check"></i></span>
                    <span class="menu-text">Appointment</span>
                </a>
            </li>

            <!-- Inventory -->
            <li class="{{ Route::currentRouteName() == 'doctor.inventory' ? 'active' : '' }}">
                <a href="{{ route('doctor.inventory') }}">
                    <span class="icon"><i class="fas fa-boxes"></i></span>
                    <span class="menu-text">Inventory</span>
                </a>
            </li>

    

           

            <!-- Profile Management (Renamed from Settings) -->
            <li class="{{ Route::currentRouteName() == 'doctor.settings.edit' ? 'active' : '' }}">
                <a href="{{ route('doctor.settings.edit') }}">
                    <span class="icon"><i class="fas fa-user-cog"></i></span>
                    <span class="menu-text">Profile Management</span>
                </a>
            </li>

            <!-- Logout -->
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                    <span class="menu-text">Logout</span>
                </a>
                <!-- Logout Form -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>

    <!-- Sidebar Footer (Optional) -->
</div>

<!-- Toggle Button for Smaller Screens -->
<button class="sidebar-toggle-btn" aria-label="Toggle Sidebar">
    <i class="fas fa-bars"></i>
</button>

<!-- Include SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- JavaScript for Sidebar Functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle Submenu Toggles
        document.querySelectorAll('.has-submenu > a').forEach(menuItem => {
            menuItem.addEventListener('click', function (e) {
                e.preventDefault();
                const parentLi = this.parentElement;
                parentLi.classList.toggle('active');

                // Close other submenus
                document.querySelectorAll('.has-submenu').forEach(item => {
                    if (item !== parentLi) {
                        item.classList.remove('active');
                    }
                });
            });
        });

     

        // Toggle Sidebar on Button Click
        document.querySelector('.sidebar-toggle-btn').addEventListener('click', function (e) {
            e.stopPropagation(); // Prevent click from propagating to the document
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Close Submenus and Sidebar when Clicking Outside
        document.addEventListener('click', function (e) {
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.querySelector('.sidebar-toggle-btn');
            if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                // Remove active class from sidebar
                sidebar.classList.remove('active');

                // Remove active class from all submenus
                document.querySelectorAll('.has-submenu').forEach(item => {
                    item.classList.remove('active');
                });
            }
        });

        // Prevent Sidebar Clicks from Closing the Sidebar
        document.querySelector('.sidebar').addEventListener('click', function (e) {
            e.stopPropagation();
        });
    });
</script>
