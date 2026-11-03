@extends('layouts.Tentor')

@section('content')

<div class="dashboard">

    <section class="summary">

        <div class="summary-card">
            <h3>Kelas Saya</h3>
            <div class="summary-bottom">
                <div class="summary-number">
                    {{ $totalKelas }}
                    <span>Kelas</span>
                </div>
                <a href="{{ route('tentor.kursus') }}" class="arrow-button" aria-label="Lihat kelas">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                    <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="summary-card">
            <h3>Jumlah Siswa</h3>
            <div class="summary-bottom">
                <div class="summary-number">
                    {{ $totalSiswa }}
                    <span>Siswa</span>
                </div>
            </div>
        </div>

        <div class="summary-card">
            <h3>Tugas</h3>
            <div class="summary-bottom">
                <div class="summary-number">
                    {{ $tugasMenunggu }}
                    <span>
                        Menunggu<br>
                        penilaian
                    </span>
                </div>
                <a href="{{ route('tentor.tugas') }}" class="arrow-button" aria-label="Lihat tugas">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                    <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                    </svg>
                </a>
            </div>
        </div>

    </section>

    <section class="schedule-section">
        <h2>Jadwal Hari Ini</h2>

        @forelse ($jadwalHariIni as $j)
            <div class="schedule-row">
                <div class="schedule-name">{{ $j->nama_kursus }}</div>
                <div class="schedule-stud">{{ $j->nama_siswa }}</div>
                <div class="schedule-tentor">{{ $j->nama_kelas }}</div>
                <div class="schedule-time">{{ $j->jam }}</div>

                <div class="attendance">
                    <div class="attendance-number">{{ $j->hadir }} / {{ $j->total_siswa }}</div>
                    <div class="progress">
                        <div class="progress-fill" style="width: {{ $j->persen }}%;"></div>
                    </div>
                </div>
            </div>
        @empty
            <p>Tidak ada jadwal hari ini.</p>
        @endforelse

    </section>

</div>

@endsection