<!-- resources/views/components/students/sidebar.blade.php -->
<div class="sidebar">
    <style>
        .sidebar {
            width: 80px; /* Collapsed width */
            background-color: #00d2ff;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            transition: width 0.3s ease-in-out;
            overflow: hidden;
        }

        .sidebar:hover {
            width: 250px; /* Expanded width */
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            transition: opacity 0.3s ease-in-out;
        }

        .logo-img {
            width: 40px;
            height: 40px;
            margin-right: 32px;
        }

        .logo-text {
            font-size: 1.25rem;
            font-weight: bold;
            opacity: 0;
        }

        .sidebar:hover .logo-text {
            opacity: 1;
        }

        .menu ul {
            list-style: none;
            padding: 0;
        }

        .menu li {
            display: flex;
            align-items: center;
            margin: 10px 0;
            padding: 10px 20px;
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .menu li:hover {
            background-color: #009ec3;
            transform: translateX(10px);
        }

        .menu li a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            display: flex;
            align-items: center;
        }

        .menu li a .icon {
            min-width: 20px;
            margin-right: 10px;
            text-align: center;
        }

        .menu-text {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .sidebar:hover .menu-text {
            opacity: 1;
        }

        .sidebar-footer ul {
            list-style: none;
            padding: 0;
        }

        .sidebar-footer li {
            display: flex;
            align-items: center;
            margin: 10px 0;
            padding: 10px 20px;
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .sidebar-footer li:hover {
            background-color: #009ec3;
            transform: translateX(10px);
        }

        .sidebar-footer li a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            display: flex;
            align-items: center;
        }

        .sidebar-footer li a .icon {
            min-width: 20px;
            margin-right: 10px;
            text-align: center;
        }
    </style>

    <div class="logo">
        <img src="{{ asset('images/pilarLogo.png') }}" alt="PilarCare Logo" class="logo-img">
        <span class="logo-text hidden md:inline-block">PilarCare</span>
    </div>
    <nav class="menu">
        <ul>
            <li><a href="{{ route('parent.dashboard') }}"><span class="icon"><i class="fas fa-tachometer-alt"></i></span><span class="menu-text">Dashboard</span></a></li>
            <li><a href="{{ route('parent.medical-record') }}"><span class="icon"><i class="fas fa-notes-medical"></i></span><span class="menu-text">Records</span></a></li>
        </ul>
    </nav>
    <div class="sidebar-footer">
        <ul>
            <li><a href="#"><span class="icon"><i class="fas fa-cogs"></i></span><span class="menu-text">Settings</span></a></li>
            <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="icon"><i class="fas fa-sign-out-alt"></i></span><span class="menu-text">Logout</span></a></li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </ul>
    </div>
</div>
