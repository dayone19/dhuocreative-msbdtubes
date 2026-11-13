@extends('layouts.tentor')

@section('content')

<style>
    a.summary-card { text-decoration: none; color: inherit; cursor: pointer; }
</style>

<div class="dashboard">

    <section class="summary">

        <a href="{{ route('tentor.kursus') }}" class="summary-card">
            <h3>Kelas Kursus Saya</h3>

            <div class="summary-bottom">
                <div class="summary-number">
                    {{ $totalKelas }}
                    <span>Kursus</span>
                </div>
                <span class="arrow-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                    <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                    </svg>
                </span>
            </div>
        </a>

        <a href="{{ route('tentor.kursus') }}" class="summary-card">
            <h3>Total Siswa Saya</h3>

            <div class="summary-bottom">
                <div class="summary-number">
                    {{ $totalSiswa }}
                    <span>Siswa</span>
                </div>
            </div>
        </a>

        <a href="{{ route('tentor.tugas') }}" class="summary-card">
            <h3>Tugas</h3>

            <div class="summary-bottom">
                <div class="summary-number">
                    {{ $tugasMenunggu }}
                    <span>
                        Menunggu<br>
                        penilaian
                    </span>
                </div>
                <span class="arrow-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                    <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                    </svg>
                </span>
            </div>
        </a>

    </section>


    <section class="schedule-section">
        <h2>Jadwal Hari Ini</h2>

        @forelse ($jadwalHariIni as $j)
        <div class="schedule-row">
            <div class="schedule-name">
                {{ $j->nama_kursus }}
            </div>
            <div class="schedule-stud">
                {{ $j->nama_siswa }}
            </div>
            <div class="schedule-class">
                {{ $j->nama_kelas }}
            </div>
            <div class="schedule-time">
                {{ $j->jam }}
            </div>
            <div class="attendance">
                <div class="attendance-number">
                    {{ $j->hadir }} / {{ $j->total }}
                </div>
                <div class="progress">
                    <div
                        class="progress-fill"
                        style="width: {{ $j->persen }}%;">
                    </div>
                </div>
            </div>
        </div>
        @empty
        <p>Belum ada jadwal mengajar untuk hari ini.</p>
        @endforelse
    </section>

</div>

@endsection