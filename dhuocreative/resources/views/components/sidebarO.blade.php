<aside class="sidebar">

    <div class="profile">
        <div class="profile-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="white" class="bi bi-person-circle" viewBox="0 0 16 16">
            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
            </svg>
        </div>
        <div class="profile-name">
            Operator A
        </div>
    </div>


    <nav class="sidebar-menu">

        <a href="{{ route('operator.dashboard') }}" class="sidebar-item {{ request()->routeIs('operator.dashboard') ? 'active' : '' }}">
            <span>Dashboard</span>
        </a>

        <a href="{{ route('operator.siswa') }}" class="sidebar-item {{ request()->routeIs('operator.siswa') ? 'active' : '' }}">
            <span>Siswa</span>
        </a>

        <!-- sisa -->
        

        <!-- Logout -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-item">
                Logout
            </button>
        </form>

    </nav>

</aside>