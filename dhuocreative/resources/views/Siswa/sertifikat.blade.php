@extends('layouts.siswa')

@section('content')

<div class="sf-page">

    <header class="sf-header">
        <h1>Sertifikat Saya</h1>
    </header>

    <section class="sf-summary">

        <div class="sf-stat">
            <div class="sf-stat-head">
                <h3>Sertifikat Diperoleh</h3>
            </div>
            <strong class="sf-stat-num">1</strong>
        </div>

        <div class="sf-stat">
            <div class="sf-stat-head">
                <h3>Kursus Selesai</h3>
            </div>
            <strong class="sf-stat-num">1</strong>
        </div>

        <div class="sf-stat">
            <div class="sf-stat-head">
                <h3>Dalam Proses</h3>
            </div>
            <strong class="sf-stat-num">1</strong>
        </div>

    </section>

    <section class="sf-card">

        <div class="sf-preview">
            <img src="https://campus.w3schools.com/cdn/shop/files/Frame_1000005453_1500x1003.png?v=1712755541" alt="Sertifikat Web Development">
        </div>

        <div class="sf-detail">
            <span class="sf-badge">Sertifikat Diperoleh</span>

            <h2>Web Development</h2>

            <ul class="sf-list">
                <li>
                    <i class="bi bi-person-fill"></i>
                    <span class="sf-label">Nama</span>
                    <span class="sf-value">Nadhira Ameera</span>
                </li>
                <li>
                    <i class="bi bi-person-video3"></i>
                    <span class="sf-label">Tentor</span>
                    <span class="sf-value">Deyoan Salsabila</span>
                </li>
                <li>
                    <i class="bi bi-calendar3"></i>
                    <span class="sf-label">Tanggal Penyelesaian</span>
                    <span class="sf-value">20 Maret 2027</span>
                </li>
                <li>
                    <i class="bi bi-file-earmark"></i>
                    <span class="sf-label">Nomor Sertifikat</span>
                    <span class="sf-value">DHC - BKI - YHG</span>
                </li>
            </ul>

            <a href="files/sertifikat.pdf" class="sf-btn" download>
                <i class="bi bi-download"></i>
                Unduh PDF
            </a>
        </div>

    </section>

</div>

@endsection