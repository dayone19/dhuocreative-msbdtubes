@extends('layouts.siswa')

@section('content')

<div class="kc-page">
    <header class="kc-header">
        <h1>Kursus Saya</h1>
    </header>

    @forelse ($kursus as $k)
    <section class="kc-course">

        <div class="kc-info">
            <h2>{{ $k->nama_kursus }}</h2>
            <p class="kc-instructor">
                <i class="bi bi-person-fill"></i>
                Tentor : {{ $k->nama_tentor ?? '-'}}
            </p>
            <div class="online-class-logo">
                <span>{{ $k->jenis }}</span>
            </div>

            <div class="kc-progress-wrap">
                <div class="kc-progress">
                    <div class="kc-progress-bar" style="width:{{ $k->persen }}%;"></div>
                </div>
                <span class="kc-progress-text">{{ $k->persen}}</span>
            </div>
        </div>
    </section>
    @empty
    <p> Kamu belum terdaftar di kursus mana pun.</p>
    @endforelse
</div>
@endsection