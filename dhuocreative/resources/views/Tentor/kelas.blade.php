@extends('layouts.Tentor')

@section('title', 'Kelas Saya')

@section('content')
<div class="tentor-kelas-page">

    @php
        $jadwal = [
            ['hari' => 'Senin', 'detail' => 'Web Master, 11.30 - 12.30'],
            ['hari' => 'Selasa', 'detail' => '-'],
            ['hari' => 'Rabu', 'detail' => 'Web Master, 11.30 - 12.30'],
            ['hari' => 'Kamis', 'detail' => '-'],
            ['hari' => 'Jumat', 'detail' => 'Web Master, 11.30 - 12.30'],
            ['hari' => 'Sabtu', 'detail' => '-'],
        ];
    @endphp

    <div class="jadwal-mingguan">
        @foreach ($jadwal as $item)
            <div class="jadwal-hari">
                <h3>{{ $item['hari'] }}</h3>
                <p>{{ $item['detail'] }}</p>
            </div>
        @endforeach
    </div>

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

    <div class="kelas-grid">
        @foreach ($kelasSaya as $kelasItem)
            <a href="{{ route('tentor.absensi', ['kelas' => $kelasItem['id']]) }}" class="kelas-card">

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
