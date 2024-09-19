<div class="sidebar">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

        .sidebar {
            width: 100px;
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
            font-family: 'Poppins', sans-serif;
        }

        .sidebar:hover {
            width: 270px;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
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
            padding-top: 20px;
        }

        .menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu li {
            position: relative;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .menu li:hover {
            background-color: #1f1f90;
            transform: translateX(10px);
        }

        .menu li a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .menu li a .icon {
            min-width: 30px;
            margin-right: 10px;
            text-align: center;
            font-size: 1.5rem;
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
        }

        .menu li.active .submenu {
            display: flex;
            width: 100%;
        }

        .submenu li {
            padding: 10px 20px;
            color: #000;
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
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

        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
            }

            .sidebar:hover {
                width: 200px;
            }

            .menu li a {
                font-size: 1rem;
            }

            .menu li a .icon {
                font-size: 1.2rem;
            }
        }
    </style>

    <div class="logo">
        <img src="{{ asset('images/pilarLogo.png') }}" alt="PilarCare Logo" class="logo-img">
        <span class="logo-text">PilarCare</span>
    </div>

    <nav class="menu">
        <ul>
            <li><a href="{{ route('doctor.dashboard') }}"><span class="icon"><i class="fas fa-tachometer-alt"></i></span><span class="menu-text">Dashboard</span></a></li>
            <li><a href="{{ route('doctor.appointments') }}"><span class="icon"><i class="fas fa-calendar-check"></i></span><span class="menu-text">Appointments</span></a></li>
            <li><a href="{{ route('doctor.patients') }}"><span class="icon"><i class="fas fa-user-md"></i></span><span class="menu-text">Patients</span></a></li>
            <li class="has-submenu">
                <a href="#"><span class="icon"><i class="fas fa-notes-medical"></i></span><span class="menu-text">Records</span><span class="submenu-toggle"><i class="fas fa-chevron-down"></i></span></a>
                <ul class="submenu">
                    <li><a href="{{ route('doctor.medical-record.index') }}">Medical Records</a></li>
                    <li><a href="{{ route('doctor.dental-record.index') }}">Dental Records</a></li>
                </ul>
            </li>
            <li><a href="{{ route('doctor.settings') }}"><span class="icon"><i class="fas fa-cogs"></i></span><span class="menu-text">Settings</span></a></li>
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
