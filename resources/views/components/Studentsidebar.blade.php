<div class="sidebar">
    <style>
        /* Import Poppins Font */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

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
            font-family: 'Poppins', sans-serif; /* Apply Poppins font */
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
            font-family: 'Poppins', sans-serif; /* Apply Poppins font */
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
            font-family: 'Poppins', sans-serif; /* Apply Poppins font */
        }

        .menu > ul > li:hover {
            background-color: #1f1f90;
            color: white;
            transform: translateX(10px);
        }

        .menu > ul > li a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem; /* Adjusted font size */
            display: flex;
            align-items: center;
            width: 100%;
            font-family: 'Poppins', sans-serif; /* Apply Poppins font */
            font-weight: 500; /* Medium weight for better readability */
        }

        .menu > ul > li:hover a {
            color: white;
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
            padding-left: 0;
            width: 100%;
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .menu li.active .submenu {
            display: flex;
            width: 100%;
        }

        .submenu li {
            padding: 10px 20px;
            margin: 0;
            width: calc(100% - 40px); /* Adjusting width to prevent overflow */
            color: #000;
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
            font-family: 'Poppins', sans-serif; /* Apply Poppins font */
            font-weight: 400; /* Regular weight for submenu items */
        }

        .submenu li:hover {
            transform: translateX(10px);
        }

        .submenu li a {
            color: #000;
            text-decoration: none;
            display: block;
            width: 100%;
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
            <li><a href="{{ route('student.dashboard') }}" id="student-dashboard-link"><span class="icon"><i class="fas fa-tachometer-alt"></i></span><span class="menu-text">Dashboard</span></a></li>
            <li><a href="{{ route('student.complaint') }}"><span class="icon"><i class="fas fa-comments"></i></span><span class="menu-text">Complaints</span><span class="submenu-toggle"></span></a>
            <li><a href="{{ route('student.appointment') }}" id="admin-appointment-link"><span class="icon"><i class="fas fa-calendar-check"></i></span><span class="menu-text">Appointment</span></a></li>

    
            </li>
            <li class="has-submenu">
                <a href="#"><span class="icon"><i class="fas fa-notes-medical"></i></span><span class="menu-text">Records</span><span class="submenu-toggle"><i class="fas fa-chevron-down"></i></span></a>
                <ul class="submenu">
                @if (!$healthExamination)
            <li><a href="{{ route('student.upload-pictures') }}">Health Approval</a></li>
        @endif
        @if ($healthExamination)

                            <li><a href="{{ route('student.medical-record') }}">View Medical Record</a></li>
                    <li><a href="{{ route('student.dental-record') }}">View Dental Record</a></li>
                    @endif

                </ul>
    </li>  
    <li class="has-submenu">
            <a href="#"><span class="icon"><i class="fas fa-user-circle"></i></span><span class="menu-text">Profile</span><span class="submenu-toggle"><i class="fas fa-chevron-down"></i></span></a>
            <ul class="submenu">
                <li><a href="{{ route('student.settings.edit') }}">Profile Management</a></li>
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
