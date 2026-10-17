@extends('layouts.siswa')

@section('content')

<div class="ab-page">

    <header class="ab-header">
        <i class="bi bi-award"></i>
        <h1>Aktivitas Belajar</h1>
    </header>

    <section class="ab-summary">

        <div class="ab-stat">
            <div class="ab-stat-head">
                <i class="bi bi-calendar-check"></i>
                <h3>Kehadiran</h3>
            </div>
            <div class="ab-stat-body">
                <strong class="ab-stat-big">90 %</strong>
                <span class="ab-stat-sub">9 dari 10 sesi</span>
            </div>
        </div>

        <div class="ab-stat">
            <div class="ab-stat-head">
                <i class="bi bi-card-checklist"></i>
                <h3>Tugas Dikumpulkan</h3>
            </div>
            <div class="ab-stat-body">
                <span class="ab-stat-sub ab-stat-sub-lg">4/5 Tugas</span>
            </div>
        </div>

    </section>

    <section class="ab-tables">

        <div class="ab-panel">
            <div class="ab-panel-title">
                <i class="bi bi-clipboard-check"></i>
                <h2>Absensi</h2>
            </div>

            <div class="ab-table">
                <div class="ab-row ab-row-head">
                    <span>Tanggal</span>
                    <span>Jam Belajar</span>
                    <span>Status</span>
                </div>

                <div class="ab-row">
                    <span class="ab-bold">10 Oktober 2026</span>
                    <span>10:00 - 12:00</span>
                    <span><em class="ab-badge ab-badge-hadir">Hadir</em></span>
                </div>

                <div class="ab-row">
                    <span class="ab-bold">10 Oktober 2026</span>
                    <span>10:00 - 12:00</span>
                    <span><em class="ab-badge ab-badge-alpha">Alpha</em></span>
                </div>

                <div class="ab-row">
                    <span class="ab-bold">10 Oktober 2026</span>
                    <span>10:00 - 12:00</span>
                    <span><em class="ab-badge ab-badge-izin">Izin</em></span>
                </div>

                <div class="ab-row">
                    <span class="ab-bold">10 Oktober 2026</span>
                    <span>10:00 - 12:00</span>
                    <span><em class="ab-badge ab-badge-sakit">Sakit</em></span>
                </div>

                <div class="ab-row">
                    <span class="ab-bold">10 Oktober 2026</span>
                    <span>10:00 - 12:00</span>
                    <span></span>
                </div>

                <div class="ab-row">
                    <span class="ab-bold">10 Oktober 2026</span>
                    <span>10:00 - 12:00</span>
                    <span></span>
                </div>

                <div class="ab-row">
                    <span class="ab-bold">10 Oktober 2026</span>
                    <span>10:00 - 12:00</span>
                    <span></span>
                </div>
            </div>
        </div>

        <div class="ab-panel ab-panel-tugas">
            <div class="ab-panel-title">
                <i class="bi bi-journal-check"></i>
                <h2>Tugas</h2>
            </div>

            <div class="ab-table">
                <div class="ab-row ab-row-head">
                    <span>Nama Tugas</span>
                    <span>Deadline</span>
                    <span>Status</span>
                </div>

                <div class="ab-row">
                    <span class="ab-bold">Latihan HTML</span>
                    <span class="ab-bold">10 Oktober 2026</span>
                    <span><em class="ab-badge ab-badge-terkumpul">Terkumpul</em></span>
                </div>

                <div class="ab-row">
                    <span></span>
                    <span></span>
                    <span><em class="ab-badge ab-badge-terlambat">Terlambat</em></span>
                </div>

                <div class="ab-row">
                    <span></span>
                    <span></span>
                    <span><em class="ab-badge ab-badge-belum">Belum Dikumpulkan</em></span>
                </div>

                <div class="ab-row"><span></span><span></span><span></span></div>
                <div class="ab-row"><span></span><span></span><span></span></div>
                <div class="ab-row"><span></span><span></span><span></span></div>
            </div>
        </div>

    </section>

</div>

@endsection