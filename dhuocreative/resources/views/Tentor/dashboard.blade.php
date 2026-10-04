@extends('layouts.Tentor')

@section('content')

<div class="dashboard">

    <section class="summary">

        <div class="summary-card">
            <h3>Kelas Saya</h3>

            <div class="summary-bottom">
                <div class="summary-number">
                    4
                    <span>Kelas</span>
                </div>
                <button class="arrow-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                    <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="summary-card">
            <h3>Jumlah Siswa</h3>

            <div class="summary-bottom">
                <div class="summary-number">
                    22
                    <span>Siswa</span>
                </div>
            </div>
        </div>

        <div class="summary-card">
            <h3>Tugas</h3>

            <div class="summary-bottom">
                <div class="summary-number">
                    1
                    <span>
                        Menunggu<br>
                        penilaian
                    </span>
                </div>
                <button class="arrow-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                    <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                    </svg>
                </button>
            </div>
        </div>

    </section>


    <section class="schedule-section">
        <h2>Jadwal Hari Ini</h2>

        <div class="schedule-row">
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
        </div>


        <div class="schedule-row">
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
        </div>

    </section>

</div>

@endsection