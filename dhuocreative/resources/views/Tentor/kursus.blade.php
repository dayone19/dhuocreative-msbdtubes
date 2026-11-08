@extends('layouts.Tentor')

@section('title', 'Kelas Saya')

@section('content')
<div class="tentor-kelas-page">

    <h1 class="tmt-title">Daftar Kursus Aktif</h1>

    <div class="kelas-grid">
        @forelse ($kelasSaya as $kelasItem)
            <a href="{{ route('tentor.absensi', ['kursus' => $kelasItem->id]) }}" class="kelas-card">

                <div class="kelas-card-info {{ $kelasItem->warna }}">
                    <h3>{{ $kelasItem->kursus }}</h3>
                    <p>{{ $kelasItem->kelas }}</p>
                </div>

                <div class="kelas-card-footer">
                    <span>{{ $kelasItem->siswa }}</span>
                    <span class="kelas-arrow">›</span>
                </div>

            </a>
        @empty
            <p>Belum ada kelas aktif yang kamu ajar.</p>
        @endforelse
    </div>

</div>
@endsection