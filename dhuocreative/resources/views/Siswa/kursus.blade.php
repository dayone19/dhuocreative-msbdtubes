@extends('layouts.siswa')

@section('content')

<div class="kc-page">
    <header class="kc-header">
        <h1>Kursus Saya</h1>
    </header>

    <section class="kc-course">

        <div class="kc-info">
            <h2>Web Development</h2>
            <p class="kc-instructor">
                <i class="bi bi-person-fill"></i>
                Tentor : Deyoan Salsabila
            </p>
            <div class="online-class-logo">
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
</div>
@endsection