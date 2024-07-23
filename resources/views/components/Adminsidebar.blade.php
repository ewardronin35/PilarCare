<div class="sidebar">
    <style>
        .sidebar {
            width: 100px; /* Collapsed width */
            background-color: #00d2ff;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            transition: width 0.3s ease-in-out;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .sidebar:hover {
            width: 270px; /* Expanded width */
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 40px; /* Increased margin to lower menu items */
            transition: opacity 0.3s ease-in-out;
        }

        .logo-img {
            width: 40px;
            height: 40px;
            margin-right: 30px;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: bold;
            opacity: 0;
        }

        .sidebar:hover .logo-text {
            opacity: 1;
        }

        .menu {
            flex-grow: 1;
            padding: 0;
            padding-top: 20px; /* Added padding to top to lower the menu items */
        }

        .menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu li {
            position: relative;
        }

        .menu > ul > li {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
            cursor: pointer;
            flex-direction: column;
            align-items: flex-start;
        }

        .menu > ul > li:hover {
            background-color: #009ec3;
            transform: translateX(10px);
        }

        .menu > ul > li a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem; /* Adjusted font size */
            display: flex;
            align-items: center;
            width: 100%;
        }

        .menu > ul > li a .icon {
            min-width: 30px; /* Adjusted icon size */
            margin-right: 10px;
            text-align: center;
            font-size: 1.5rem; /* Increased icon size */
        }

        .menu-text {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .sidebar:hover .menu-text {
            opacity: 1;
        }

        .submenu {
            display: none;
            flex-direction: column;
            margin-left: 0;
            padding-left: 20px;
            width: 100%;
            box-shadow: none; /* Remove shadow */
        }

        .menu li.active .submenu {
            display: flex;
            width: 100%;
            box-shadow: none; /* Remove shadow */
        }

        .submenu li {
            padding: 5px 20px;
            margin: 0;
            background-color: #00b8e6;
            width: 100%;
        }

        .submenu li:hover {
            background-color: #009ec3;
            transform: none;
        }

        .submenu li a {
            color: white;
            text-decoration: none;
            display: block;
            width: 100%;
        }

        .submenu .submenu {
            display: none;
        }

        .submenu li.active .submenu {
            display: block;
        }

        .submenu-toggle {
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .menu li.active > a > .submenu-toggle {
            transform: rotate(90deg);
        }

        /* Media Queries */
        @media (max-width: 768px) {
            .sidebar {
                width: 60px; /* Smaller sidebar for mobile */
            }

            .sidebar:hover {
                width: 200px; /* Expanded width for mobile */
            }

            .logo-text {
                font-size: 1rem; /* Smaller logo text for mobile */
            }

            .menu > ul > li {
                padding: 5px 10px; /* Smaller padding for menu items */
            }

            .menu > ul > li a {
                font-size: 1rem; /* Smaller font size for menu items */
            }

            .menu > ul > li a .icon {
                font-size: 1.2rem; /* Smaller icon size */
            }
        }
    </style>

    <div class="logo">
        <img src="{{ asset('images/pilarLogo.png') }}" alt="PilarCare Logo" class="logo-img">
        <span class="logo-text hidden md:inline-block">PilarCare</span>
    </div>
    <nav class="menu">
        <ul>
            <li><a href="{{ route('admin.dashboard') }}" id="admin-dashboard-link"><span class="icon"><i class="fas fa-tachometer-alt"></i></span><span class="menu-text">Dashboard</span></a></li>
            <li class="has-submenu">
                <a href="#"><span class="icon"><i class="fas fa-comments"></i></span><span class="menu-text">Complaints</span><span class="submenu-toggle"><i class="fas fa-chevron-down"></i></span></a>
                <ul class="submenu">
                    <li><a href="{{ route('admin.complaint') }}">View Complaints</a></li>
                    <li><a href="{{ route('admin.complaint') }}">Add Complaints</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><span class="icon"><i class="fas fa-notes-medical"></i></span><span class="menu-text">Records</span><span class="submenu-toggle"><i class="fas fa-chevron-down"></i></span></a>
                <ul class="submenu">
                    <li><a href="{{ route('admin.medical-record.index') }}">Health Approval</a></li>
                    <li><a href="{{ route('admin.medical-record.index') }}">View Medical Record</a></li>
                    <li><a href="{{ route('admin.medical-record.index') }}">View Dental Record</a></li>
                </ul>
            </li>
            <li><a href="{{ route('admin.enrolledstudents') }}" id="admin-enrolled-students-link"><span class="icon"><i class="fas fa-user-graduate"></i></span><span class="menu-text">Enrolled Students</span></a></li>
            <li><a href="{{ route('admin.appointment') }}" id="admin-appointment-link"><span class="icon"><i class="fas fa-calendar-check"></i></span><span class="menu-text">Appointment</span></a></li>
            <li class="has-submenu">
                <a href="#"><span class="icon"><i class="fas fa-boxes"></i></span><span class="menu-text">Inventory</span><span class="submenu-toggle"><i class="fas fa-chevron-down"></i></span></a>
                <ul class="submenu">
                    <li><a href="{{ route('admin.inventory') }}">Add Item</a></li>
                    <li><a href="{{ route('admin.inventory') }}">Inventory List</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><span class="icon"><i class="fas fa-chart-line"></i></span><span class="menu-text">Monitoring and Report</span><span class="submenu-toggle"><i class="fas fa-chevron-down"></i></span></a>
                <ul class="submenu">
                    <li><a href="{{ route('admin.monitoring-report-log') }}">Release Notification</a></li>
                    <li><a href="{{ route('admin.monitoring-report-log') }}">View Logs and Daily Transactions</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><span class="icon"><i class="fas fa-user"></i></span><span class="menu-text">Profile</span><span class="submenu-toggle"><i class="fas fa-chevron-down"></i></span></a>
                <ul class="submenu">
                    <li><a href="{{ route('admin.monitoring-report-log') }}">View Profile of Student</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</div>

<script>
    document.querySelectorAll('.has-submenu > a').forEach(menuItem => {
        menuItem.addEventListener('click', function(e) {
            e.preventDefault();
            var parentLi = this.parentElement;
            parentLi.classList.toggle('active');
            document.querySelectorAll('.has-submenu').forEach(item => {
                if (item !== parentLi) {
                    item.classList.remove('active');
                }
            });
        });
    });

    document.addEventListener('mouseover', function(e) {
        var targetElement = e.target;
        var isHoverInside = targetElement.closest('.sidebar');
        if (!isHoverInside) {
            document.querySelectorAll('.has-submenu').forEach(item => {
                item.classList.remove('active');
            });
        }
    });
</script>
