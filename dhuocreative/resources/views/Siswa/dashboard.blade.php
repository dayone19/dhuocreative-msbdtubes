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
            <a href="#" class="sd-arrow-btn">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>

        <div class="sd-card">
            <div class="sd-card-top">
                <i class="bi bi-check-circle-fill sd-card-icon"></i>
                <div>
                    <h3>Nilai</h3>
                    <p>Lihat Nilai Akhir</p>
                </div>
            </div>
            <a href="#" class="sd-arrow-btn">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>

        <div class="sd-card">
            <div class="sd-card-top">
                <i class="bi bi-clipboard-check sd-card-icon"></i>
                <div>
                    <h3>Absensi</h3>
                    <p>Lihat Kehadiran</p>
                </div>
            </div>
            <a href="#" class="sd-arrow-btn">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>

    </section>

    {{-- Jadwal & Pengumuman --}}
    <section class="sd-bottom">

        <div class="sd-panel">
            <div class="sd-panel-title">
                <i class="bi bi-calendar3"></i>
                <h2>Jadwal Hari Ini</h2>
            </div>

            <div class="sd-schedule-item">
                <i class="bi bi-code-slash sd-schedule-icon"></i>

                <div class="sd-schedule-info">
                    <h4>Web Development</h4>
                    <p class="sd-meta">
                        <i class="bi bi-person-fill"></i>
                        Deyoan Salsabila
                    </p>
                    <p class="sd-meta">
                        <i class="bi bi-clock"></i>
                        10.00 - 12.00
                    </p>
                </div>

                <a href="#" class="sd-detail-btn">
                    Lihat Detail
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="sd-panel sd-announcement">
            <div class="sd-panel-title">
                <i class="bi bi-megaphone"></i>
                <h2>Pengumuman</h2>
            </div>

            <div class="sd-announcement-item">
                <i class="bi bi-card-checklist"></i>
                <div>
                    <strong>Pengumpulan tugas minggu ini</strong>
                    <small>Deadline, 10-03-2026</small>
                </div>
            </div>

            <a href="#" class="sd-arrow-btn">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>

    </section>

</div>

@endsection