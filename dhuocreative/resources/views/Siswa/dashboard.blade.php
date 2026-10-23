@extends('layouts.siswa')

@section('content')

<div class="sd-dashboard">
    <section class="sd-welcome">
        <h1>Halo, Nadhira Ameera 👋</h1>
        <p>Selamat datang di DhuoCreative Portal!</p>
    </section>

    <section class="sd-summary">

        <div class="sd-card">
            <div class="sd-card-top">
                <i class="bi bi-journal-check sd-card-icon"></i>
                <div>
                    <h3>Tugas</h3>
                    <p>Lihat &amp; Kumpulkan</p>
                </div>
            </div>

            <a href="{{ route('siswa.pengumpulanTugas') }}"
               class="sd-arrow-btn {{ request()->routeIs('siswa.pengumpulanTugas') ? 'active' : '' }}">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>

        <div class="sd-card">
            <div class="sd-card-top">
                <i class="bi bi-check-circle-fill sd-card-icon"></i>
                <div>
                    <h3>Nilai</h3>
                    <p>Lihat Nilai</p>
                </div>
            </div>

            <a href="{{ route('siswa.nilai') }}"
               class="sd-arrow-btn {{ request()->routeIs('siswa.nilai') ? 'active' : '' }}">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>

        <div class="sd-card">
            <div class="sd-card-top">
                <i class="bi bi-clipboard-check sd-card-icon"></i>
                <div>
                    <h3>Absensi</h3>
                    <p>Lihat Absensi</p>
                </div>
            </div>

            <a href="{{ route('siswa.absensi') }}"
               class="sd-arrow-btn {{ request()->routeIs('siswa.absensi') ? 'active' : '' }}">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>

    </section>

    <section class="sd-bottom">

        <div class="sd-panel">

            <div class="sd-panel-title">
                <i class="bi bi-calendar3"></i>
                <h2>Jadwal Kelas</h2>
            </div>

            <div class="sd-schedule-list">

                <div class="sd-schedule-row">

                    <div class="sd-schedule-info">
                        <h4>Web Development</h4>

                        <p class="sd-meta">
                            <i class="bi bi-person-fill"></i>
                            Tentor : Deyoan Salsabila
                        </p>

                        <p class="sd-meta">
                            <i class="bi bi-clock"></i>
                            10:00 - 12:00
                        </p>
                    </div>

                    <div class="sd-schedule-side">

                        <span class="sd-badge sd-badge-online">
                            Online
                        </span>

                        <div class="sd-schedule-actions">
                            <a href="{{ route('siswa.absensi') }}?ajukan=1"
                               class="sd-btn-tunda">
                                Tunda Kelas
                            </a>

                            <button type="button"
                                    class="sd-btn-setuju js-setujui-kelas">
                                Setujui Kelas
                            </button>
                        </div>

                        <span class="sd-badge-disetujui" hidden>
                            <i class="bi bi-check-circle-fill"></i>
                            Kelas Disetujui
                        </span>

                    </div>
                </div>

                <div class="sd-schedule-row">

                    <div class="sd-schedule-info">
                        <h4>Web Development</h4>

                        <p class="sd-meta">
                            <i class="bi bi-person-fill"></i>
                            Tentor : Deyoan Salsabila
                        </p>

                        <p class="sd-meta">
                            <i class="bi bi-clock"></i>
                            10:00 - 12:00
                        </p>
                    </div>

                    <div class="sd-schedule-side">

                        <span class="sd-badge sd-badge-online">
                            Online
                        </span>

                        <div class="sd-schedule-actions">
                            <a href="{{ route('siswa.absensi') }}"
                               class="sd-btn-tunda">
                                Tunda Kelas
                            </a>

                            <button type="button"
                                    class="sd-btn-setuju js-setujui-kelas">
                                Setujui Kelas
                            </button>
                        </div>

                        <span class="sd-badge-disetujui" hidden>
                            <i class="bi bi-check-circle-fill"></i>
                            Kelas Disetujui
                        </span>

                    </div>
                </div>

            </div>
        </div>

        <div class="sd-panel sd-announcement">

            <div class="sd-panel-title">
                <i class="bi bi-megaphone-fill"></i>
                <h2>Pengumuman</h2>
            </div>

            <div class="sd-announcement-item">
                <span class="sd-dot"></span>

                <div>
                    <strong>Pengumpulan tugas minggu ini</strong>

                    <small>
                        <i class="bi bi-calendar3"></i>
                        Deadline: 10 Oktober 2026
                    </small>
                </div>
            </div>

        </div>

    </section>

    <section class="sd-course">

        <div class="sd-course-info">

            <span class="sd-badge sd-badge-active">
                <i class="bi bi-check-circle-fill"></i>
                Kursus Aktif
            </span>

            <h4>Web Development</h4>

            <p class="sd-meta">
                <i class="bi bi-person-fill"></i>
                Tentor : Deyoan Salsabila
            </p>

            <div class="sd-progress-wrap">
                <div class="sd-progress">
                    <div class="sd-progress-bar" style="width: 60%;"></div>
                </div>

                <span class="sd-progress-text">60%</span>
            </div>

        </div>

        <a href="{{ route('siswa.kursus') }}" class="sd-btn-orange">
            Lihat Semua
            <i class="bi bi-arrow-right-short"></i>
        </a>

    </section>

</div>

<script>
document.querySelectorAll('.js-setujui-kelas').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const side = btn.closest('.sd-schedule-side');

        if (!side) return;

        const actions = side.querySelector('.sd-schedule-actions');
        const badge = side.querySelector('.sd-badge-disetujui');

        if (actions) {
            actions.hidden = true;
        }

        if (badge) {
            badge.hidden = false;
        }
    });
});
</script>

@endsection