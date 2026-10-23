<aside class="sidebar">

    <div class="profile" id="profileTrigger">
        <button type="button" class="profile-icon" id="profileBtn" aria-haspopup="true" aria-expanded="false" aria-label="Buka profil">
            <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="white" class="bi bi-person-circle" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
            </svg>
        </button>

        <div class="profile-name">
            {{ Auth::user()->name ?? 'Tentor A' }}
        </div>

        <!-- Popup profil -->
        <div class="profile-popup" id="profilePopup" role="menu">
            <div class="popup-row">
                <span class="popup-label">Nama</span>
                <span class="popup-value">{{ Auth::user()->name ?? 'Tentor A' }}</span>
            </div>
            <div class="popup-row">
                <span class="popup-label">Email</span>
                <span class="popup-value">{{ Auth::user()->email ?? '-' }}</span>
            </div>
            <div class="popup-row">
                <span class="popup-label">No. Telp</span>
                <span class="popup-value">{{ Auth::user()->no_telp ?? '-' }}</span>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="popup-logout">Logout</button>
            </form>
        </div>
    </div>

    <nav class="sidebar-menu">
        <a href="{{ route('tentor.dashboard') }}" class="sidebar-item {{ request()->routeIs('tentor.dashboard') ? 'active' : '' }}">
            <span>Dashboard</span>
        </a>
        <a href="{{ route('tentor.kelas') }}" class="sidebar-item {{ request()->routeIs('tentor.kelas') ? 'active' : '' }}">
            <span>Kelas</span>
        </a>
        <a href="{{ route('tentor.tugas') }}" class="sidebar-item {{ request()->routeIs('tentor.tugas') ? 'active' : '' }}">
            <span>Tugas & Nilai</span>
        </a>
        <a href="{{ route('tentor.sertifikat') }}" class="sidebar-item {{ request()->routeIs('tentor.sertifikat') ? 'active' : '' }}">
            <span>Sertifikat</span>
        </a>
        <a href="{{ route('tentor.pengumuman') }}" class="sidebar-item {{ request()->routeIs('tentor.pengumuman') ? 'active' : '' }}">
            <span>Pengumuman</span>
        </a>
    </nav>

</aside>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('profileBtn');
    const popup = document.getElementById('profilePopup');

    function closePopup() {
        popup.classList.remove('show');
        btn.setAttribute('aria-expanded', 'false');
    }

    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        const open = popup.classList.toggle('show');
        btn.setAttribute('aria-expanded', open);
    });

    popup.addEventListener('click', e => e.stopPropagation());
    document.addEventListener('click', closePopup);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closePopup(); });
});
</script>