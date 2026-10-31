@extends('layouts.tentor')

@section('title', 'Kelas Saya')

@section('content')
<div class="tentor-kelas-page">

    <div class="jadwal-mingguan">
        @foreach ($jadwal as $item)
            <div class="jadwal-hari">
                <h3>{{ $item['hari'] }}</h3>
                <p>{{ $item['detail'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="kelas-grid">
        @if (count($kelasSaya) > 0)
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

        @else
            <p>Belum ada kelas yang tersedia.</p>
        @endif
    </div>

</div>
@endsection