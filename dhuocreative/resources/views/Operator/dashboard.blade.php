@extends('layouts.Operator')

@section('title', 'Dashboard')

@section('content')

@php
    $ringkasan = [
        ['judul' => 'Jadwal Hari Ini',   'angka' => 4,  'satuan' => 'Kelas',  'lebar' => 195],
        ['judul' => 'Total Siswa Aktif', 'angka' => 22, 'satuan' => 'Siswa',  'lebar' => 205],
        ['judul' => 'Total kelas Aktif', 'angka' => 7,  'satuan' => 'Kelas',  'lebar' => 195],
        ['judul' => 'Total Tentor',      'angka' => 6,  'satuan' => 'Tentor', 'lebar' => 195],
    ];

    $jadwalHariIni = [
        ['kelas' => 'Web Master 103', 'tipe' => 'Online',  'tentor' => 'Tentor A', 'jam' => '11.30 - 12.30', 'hadir' => 13, 'total' => 13],
        ['kelas' => 'Web Master 104', 'tipe' => 'Offline', 'tentor' => 'Tentor B', 'jam' => '13.00 - 14.00', 'hadir' => 9,  'total' => 15],
        ['kelas' => 'Web Master 105', 'tipe' => 'Online',  'tentor' => 'Tentor C', 'jam' => '14.30 - 15.30', 'hadir' => 0,  'total' => 12],
        ['kelas' => 'Web Master 106', 'tipe' => 'Offline', 'tentor' => 'Tentor D', 'jam' => '15.00 - 16.00', 'hadir' => 0,  'total' => 10],
    ];
@endphp

<div class="dashboard">

    {{-- RINGKASAN --}}
    <section class="o-summary">
        @foreach ($ringkasan as $item)
            <div class="o-summary-card" @if ($item['lebar'] !== 195) style="width: {{ $item['lebar'] }}px;" @endif>
                <h3>{{ $item['judul'] }}</h3>

                <div class="summary-bottom">
                    <div class="summary-number">
                        {{ $item['angka'] }}
                        <span>{{ $item['satuan'] }}</span>
                    </div>
                    <button type="button" class="arrow-button" aria-label="Lihat {{ $item['judul'] }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                            <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endforeach
    </section>

    {{-- JADWAL HARI INI --}}
    <section class="schedule-section o-mint-theme">
        <h2>Jadwal Hari Ini</h2>

        @forelse ($jadwalHariIni as $row)
            <div class="schedule-row">
                <div class="schedule-name">{{ $row['kelas'] }}</div>
                <div class="schedule-type">{{ $row['tipe'] }}</div>
                <div class="schedule-tentor">{{ $row['tentor'] }}</div>
                <div class="schedule-time">{{ $row['jam'] }}</div>
                <div class="attendance">
                    <span class="attendance-number">{{ $row['hadir'] }} / {{ $row['total'] }}</span>
                    <div class="progress">
                        <div class="progress-fill" style="width: {{ $row['total'] ? round($row['hadir'] / $row['total'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        @empty
            <div class="schedule-row"><div>Tidak ada jadwal hari ini.</div></div>
        @endforelse
    </section>

</div>

@endsection