@extends('layouts.siswa')

@section('content')

<div class="sd-dashboard">
    <section class="sd-welcome">
        <h1>Halo, {{ $namaSiswa}}</h1>
        <p>Selamat datang di DhuoCreative Portal!</p>
    </section>

    <section class="sd-summary">

        <div class="sd-card">
            <div class="sd-card-top">
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
                <div>
                    <h3>Presensi Kelas</h3>
                    <p>Lakukan Absensi</p>
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
                <h2>Jadwal Kelas</h2>
            </div>

            <div class="sd-schedule-list">

                @forelse ($jadwalHariIni as $j)
                <div class="sd-schedule-row">

                    <div class="sd-schedule-info">
                        <h4>{{ $j->nama_kursus}}</h4>

                        <p class="sd-meta">
                            <i class="bi bi-person-fill"></i>
                            Tentor : {{ $j->nama_tentor}}
                        </p>

                        <p class="sd-meta">
                            <i class="bi bi-clock"></i>
                            {{ $j->jam}}
                        </p>
                    </div>

                    <div class="sd-schedule-side">

                        <span class="sd-badge {{ $j->badge_class}}">
                            {{ $j->badge_text}}
                        </span>
                    </div>
                </div>
                @empty

                <div class="sd-schedule-row">

                    <div class="sd-schedule-info">
                        <p class="sd-meta">
                            Tidak ada jadwal kelas untuk hari ini.
                        </p>
                    </div>
                </div>
                @endforelse

            </div>
        </div>

        <div class="sd-panel sd-announcement">
 
            <div class="sd-panel-title">
                <h2>Pengumuman Hari Ini</h2>
            </div>
 
            @if ($pengumumanTerbaru)
            <div class="sd-announcement-item">
                <span class="sd-dot"></span>
 
                <div>
                    <strong>{{ $pengumumanTerbaru->judul }}</strong>
 
                    <small>
                        {{ $pengumumanTerbaru->isi }}
                        <br>
                        <div class="sd-color-{{ $pengumumanTerbaru->role }}">Dibuat oleh: {{ $pengumumanTerbaru->nama }} ({{ ucfirst($pengumumanTerbaru->role) }})</div>
                    </small>
                </div>
            </div>
            @else
            <p>Belum ada pengumuman hari ini.</p>
            @endif
 
        </div>

    </section>

    
    @if ($kursusUtama)
    <section class="sd-course">

        <div class="sd-course-info">

            <span class="sd-badge sd-badge-active">
                <i class="bi bi-check-circle-fill"></i>
                Kursus Aktif
            </span>

            <h4>{{ $kursusUtama->nama_kursus }}</h4>

            <p class="sd-meta">
                <i class="bi bi-person-fill"></i>
                Tentor : {{ $kursusUtama->nama_tentor }}
            </p>

            <div class="sd-progress-wrap">
                <div class="sd-progress">
                    <div class="sd-progress-bar" style="width: {{ $kursusUtama->persen }}%;"></div>
                </div>

                <span class="sd-progress-text">{{ $kursusUtama->persen }}%</span>
            </div>

        </div>

        <a href="{{ route('siswa.kursus') }}" class="sd-btn-orange">
            Lihat Semua
            <i class="bi bi-arrow-right-short"></i>
        </a>

    </section>
    @endif
</div>

@endsection