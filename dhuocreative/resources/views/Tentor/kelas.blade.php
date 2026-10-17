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
                'nama' => 'Web Master 103 - Online',
                'jam' => '11.30 - 12.30',
                'siswa' => 13,
                'warna' => 'teal'
            ],
            [
                'id' => 104,
                'nama' => 'Web Master 104 - Offline',
                'jam' => '13.00 - 14.00',
                'siswa' => 15,
                'warna' => 'orange'
            ],
            [
                'id' => 105,
                'nama' => 'Web Master 105 - Online',
                'jam' => '11.30 - 12.30',
                'siswa' => 12,
                'warna' => 'orange'
            ],
            [
                'id' => 106,
                'nama' => 'Web Master 106 - Offline',
                'jam' => '15.00 - 16.00',
                'siswa' => 10,
                'warna' => 'teal'
            ],
        ];
    @endphp

    <div class="kelas-grid">
        @foreach ($kelasSaya as $kelasItem)
            <a href="{{ route('tentor.absensi', ['kelas' => $kelasItem['id']]) }}" class="kelas-card">

                <div class="kelas-card-info {{ $kelasItem['warna'] }}">
                    <h3>{{ $kelasItem['nama'] }}</h3>
                    <p>{{ $kelasItem['jam'] }}</p>
                </div>

                <div class="kelas-card-footer">
                    <span>{{ $kelasItem['siswa'] }} Siswa</span>
                    <span class="kelas-arrow">›</span>
                </div>

            </a>
        @endforeach
    </div>

</div>
@endsection
