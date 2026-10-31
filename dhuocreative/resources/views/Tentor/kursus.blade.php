@extends('layouts.Tentor')

@section('title', 'Kelas Saya')

@section('content')
<div class="tentor-kelas-page">

    {{-- DAFTAR KELAS STATIS --}}
    @php
        $kelasSaya = [
            [
                'id' => 103,
                'kursus' => 'Web Master',
                'kelas' => 'online',
                'siswa' => 'Siswa A',
                'warna' => 'teal'
            ],
            [
                'id' => 104,
                'kursus' => 'Web Master',
                'kelas' => 'Offline Rumah',
                'siswa' => 'Siswa B',
                'warna' => 'orange'
            ],
            [
                'id' => 105,
                'kursus' => 'Office Master',
                'kelas' => 'Offline',
                'siswa' => 'Siswa F',
                'warna' => 'orange'
            ],
            [
                'id' => 106,
                'kursus' => 'Office Master',
                'kelas' => 'Online',
                'siswa' => 'Siswa D',
                'warna' => 'teal'
            ],
        ];
    @endphp

    <h1 class="tmt-title">Daftar Kursus Aktif</h1>

    <div class="kelas-grid">
        @foreach ($kelasSaya as $kelasItem)
            <a href="{{ route('tentor.absensi', ['kursus' => $kelasItem['id']]) }}" class="kelas-card">

                <div class="kelas-card-info {{ $kelasItem['warna'] }}">
                    <h3>{{ $kelasItem['kursus'] }}</h3>
                    <p>{{ $kelasItem['kelas'] }}</p>
                </div>

                <div class="kelas-card-footer">
                    <span>{{ $kelasItem['siswa'] }}</span>
                    <span class="kelas-arrow">›</span>
                </div>

            </a>
        @endforeach
    </div>

</div>
@endsection
