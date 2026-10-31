@extends('layouts.tentor')

@section('content')

<style>
    a.summary-card { text-decoration: none; color: inherit; cursor: pointer; }
</style>

<div class="dashboard">

    <section class="summary">

        <a href="{{ route('tentor.kelas') }}" class="summary-card">
            <h3>Kelas Saya</h3>

            <div class="summary-bottom">
                <div class="summary-number">
                    {{ $totalKelas }}
                    <span>Kelas</span>
                </div>
                <span class="arrow-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                    <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                    </svg>
                </span>
            </div>
        </a>

        <a href="{{ route('tentor.kelas') }}" class="summary-card">
            <h3>Jumlah Siswa</h3>

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

        @forelse ($jadwalHariIni as $jadwal)
        <div class="schedule-row">
            <div class="schedule-name">
                {{ $jadwal->nama_kursus }}
            </div>

            <div class="schedule-type">
                {{ $jadwal->nama_kelas }}
            </div>

            <div class="schedule-time">
                {{ date('H.i', strtotime($jadwal->jam_mulai)) }} - {{ date('H.i', strtotime($jadwal->jam_selesai)) }}
            </div>

            <div class="attendance">
                <div class="attendance-number">
                    {{ $jadwal->hadir }} / {{ $jadwal->total_siswa }}
                </div>
                <div class="progress">
                    <div class="progress-fill" style="width: {{ $jadwal->persen }}%;">
                        
                    </div>
                </div>
            </div>
        </div>
        @empty
            <p style="color: #666; margin-top: 10px;">Belum ada jadwal mengajar untuk hari ini.</p>
        @endforelse


        <!-- <div class="schedule-row">
            <div class="schedule-name">
                Web Master
            </div>

            <div class="schedule-type">
                Offline
            </div>

            <div class="schedule-time">
                10.30 - 12.00
            </div>

            <div class="attendance">
                <div class="attendance-number">
                    9 / 12
                </div>
                <div class="progress">
                    <div
                        class="progress-fill"
                        style="width: 75%;">
                    </div>
                </div>
            </div>
        </div> -->

    </section>

</div>

@endsection