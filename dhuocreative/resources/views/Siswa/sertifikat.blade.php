@extends('layouts.siswa')

@section('content')

<div class="sf-page">

    <section class="sf-summary">

        <div class="sf-stat">
            <div class="sf-stat-head">
                <h3>Sertifikat Diperoleh</h3>
            </div>
            <strong class="sf-stat-num">{{ $jumlahSertifikat }}</strong>
        </div>

        <div class="sf-stat">
            <div class="sf-stat-head">
                <h3>Kursus Selesai</h3>
            </div>
            <strong class="sf-stat-num">{{ $kursusSelesai }}</strong>
        </div>

        <div class="sf-stat">
            <div class="sf-stat-head">
                <h3>Dalam Proses</h3>
            </div>
            <strong class="sf-stat-num">{{ $dalamProses }}</strong>
        </div>

    </section>

    @forelse ($sertifikat as $s)
    <section class="sf-card">
        <div class="sf-preview">
            @if ($s->adalah_gambar)
                <img src="{{ $s->url }}" alt="Sertifikat {{ $s->nama_projek }}">
            @else
                <div style="display:flex;align-items:center;justify-content:center;height:100%;min-height:200px;">
                    <i class="bi bi-file-earmark-pdf" style="font-size:72px;"></i>
                </div>
            @endif
        </div>

        <div class="sf-detail">
            <span class="sf-badge">Sertifikat Diperoleh</span>
            <h2>{{ $s->nama_projek }}</h2>

            <ul class="sf-list">
                <li>
                    <i class="bi bi-person-fill"></i>
                    <span class="sf-label">Nama</span>
                    <span class="sf-value">{{ auth()->user()->nama }}</span>
                </li>

                <li>
                    <i class="bi bi-person-video3"></i>
                    <span class="sf-label">Tentor</span>
                    <span class="sf-value">{{ $s->nama_tentor }}</span>
                </li>

                <li>
                    <i class="bi bi-calendar3"></i>
                    <span class="sf-label">Tanggal Penyelesaian</span>
                    <span class="sf-value">{{ $s->tanggal_teks }}</span>
                </li>

                <li>
                    <i class="bi bi-file-earmark"></i>
                    <span class="sf-label">Nomor Sertifikat</span>
                    <span class="sf-value">{{ $s->nomor }}</span>
                </li>
            </ul>

            <a href="{{ $s->url_unduh }}" class="sf-btn">
                <i class="bi bi-download"></i>
                {{ $s->label_unduh }}
            </a>
        </div>
    </section>
    @empty
    <p>Belum ada sertifikat.</p>
    @endforelse

</div>

@endsection