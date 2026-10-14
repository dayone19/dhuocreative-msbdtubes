@extends('layouts.Operator')

@section('content')

<div class="dashboard">

    <section class="o-summary">

        <div class="o-summary-card">
            <h3>Jadwal Hari Ini</h3>

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

        <div class="o-summary-card" style="width: 205px;">
            <h3>Total Siswa Aktif</h3>

            <div class="summary-bottom">
                <div class="summary-number">
                    22
                    <span>Siswa</span>
                </div>
                <button class="arrow-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                    <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="o-summary-card">
            <h3>Total kelas Aktif</h3>

            <div class="summary-bottom">
                <div class="summary-number">
                    7
                    <span>Kelas</span>
                </div>
                <button class="arrow-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                    <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="o-summary-card">
            <h3>Total Tentor</h3>

            <div class="summary-bottom">
                <div class="summary-number">
                    6
                    <span>Tentor</span>
                </div>
                <button class="arrow-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                    <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                    </svg>
                </button>
            </div>
        </div>

    </section>

</div>

@endsection