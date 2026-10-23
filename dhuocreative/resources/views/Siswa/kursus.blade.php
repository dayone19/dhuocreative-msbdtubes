@extends('layouts.siswa')

@section('content')

<div class="kc-page">
    <header class="kc-header">
        <i class="bi bi-award"></i>
        <h1>Kursus Saya</h1>
    </header>

    <section class="kc-course">
        <div class="kc-thumb">
            <img src="https://th.bing.com/th/id/OIP.rYfk_nixRSBKHPScLjPEDwHaEK?w=319&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3" alt="web.dev">
        </div>

        <div class="kc-info">
            <h2>Web Development</h2>
            <p class="kc-instructor">
                <i class="bi bi-person-fill"></i>
                Tentor : Deyoan Salsabila
            </p>
            <div class="online-class-logo">
                <i class="bi bi-laptop"></i>
                <span>Online Class</span>
            </div>

            <div class="kc-progress-wrap">
                <div class="kc-progress">
                    <div class="kc-progress-bar" style="width: 60%;"></div>
                </div>
                <span class="kc-progress-text">60%</span>
            </div>
        </div>
    </section>

    <section class="kc-summary">
        <div class="kc-summary-head">
            <i class="bi bi-book"></i>
            <h3>Ringkasan</h3>
        </div>

        <div class="kc-summary-body">
            <p>
                Web development (pengembangan web) adalah proses pembuatan,
                pembangunan, dan pemeliharaan situs web atau aplikasi web agar
                dapat diakses melalui internet menggunakan peramban (browser).
            </p>
        </div>
    </section>

</div>
@endsection