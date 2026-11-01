@extends('layouts.Operator')

@section('title', 'Detail Absensi Siswa')

@section('content')
@php
    // Data contoh tampilan. Nantinya dapat diganti dengan data dari controller.
    $siswa = $siswa ?? [
        'id' => 1208,
        'nama' => 'Siswa A',
        'kursus' => 'Web Master',
        'kelas' => 'Online',
        'tentor' => 'Tentor C',
        'mulai_kontrak' => '2026-10-05',
        'akhir_kontrak' => '2026-12-05',
    ];

    $daftarPertemuan = $daftarPertemuan ?? [
        ['ke' => 1, 'tanggal' => '2026-10-05', 'hari' => 'Senin', 'jam' => '16.00–17.30', 'status' => 'hadir', 'jadwal' => 'Jadwal tetap'],
        ['ke' => 2, 'tanggal' => '2026-10-07', 'hari' => 'Rabu', 'jam' => '16.00–17.30', 'status' => 'hadir', 'jadwal' => 'Jadwal tetap'],
        ['ke' => 3, 'tanggal' => '2026-10-09', 'hari' => 'Jumat', 'jam' => '16.00–17.30', 'status' => 'hadir', 'jadwal' => 'Jadwal tetap'],
        ['ke' => 4, 'tanggal' => '2026-10-12', 'hari' => 'Senin', 'jam' => '16.00–17.30', 'status' => 'hadir', 'jadwal' => 'Jadwal tetap'],
        ['ke' => 5, 'tanggal' => '2026-10-14', 'hari' => 'Rabu', 'jam' => '16.00–17.30', 'status' => 'hadir', 'jadwal' => 'Jadwal ganti'],
    ];

    $totalPertemuan = 12;
    $totalHadir = collect($daftarPertemuan)->where('status', 'hadir')->count();
    $totalTercatat = count($daftarPertemuan);
    $sisaPertemuan = max(0, $totalPertemuan - $totalTercatat);
    $persentaseHadir = $totalPertemuan ? ($totalHadir / $totalPertemuan) * 100 : 0;
    $persentaseSisa = $totalPertemuan ? ($sisaPertemuan / $totalPertemuan) * 100 : 0;
@endphp

<div class="tentor-absensi-page ta-page o-operator-absensi-detail">
    <div class="absensi-heading ta-heading">
        <a href="{{ route('operator.absensi') }}" class="tmt-back-btn" aria-label="Kembali ke rekap absensi">
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
            </svg>
        </a>
        <div class="ta-heading-info">
            <h1>Detail Absensi Siswa</h1>
        </div>
    </div>

    <section class="ta-profile-card" aria-label="Informasi siswa">
        <div class="ta-profile-main">
            <div>
                <span class="ta-muted">ID {{ $siswa['id'] }}</span>
                <h2>{{ $siswa['nama'] }}</h2>
                <p>{{ $siswa['kursus'] }} <span>•</span> {{ $siswa['kelas'] }}</p>
                <p class="o-detail-tentor">Tentor: {{ $siswa['tentor'] }}</p>
            </div>
        </div>
        <div class="ta-contract">
            <span class="ta-muted">Masa kontrak</span>
            <strong>{{ \Carbon\Carbon::parse($siswa['mulai_kontrak'])->translatedFormat('d M Y') }}</strong>
            <span class="ta-contract-arrow">sampai</span>
            <strong>{{ \Carbon\Carbon::parse($siswa['akhir_kontrak'])->translatedFormat('d M Y') }}</strong>
        </div>
    </section>

    <section class="ta-summary-grid" aria-label="Ringkasan absensi">
        <article class="ta-summary-card">
            <span>Total pertemuan</span>
            <strong>{{ $totalPertemuan }} <small>kali</small></strong>
            <div class="ta-summary-track"><span style="width:100%"></span></div>
        </article>
        <article class="ta-summary-card">
            <span>Hadir tercatat</span>
            <strong>{{ $totalHadir }} <small>/ {{ $totalPertemuan }}</small></strong>
            <div class="ta-summary-track"><span style="width:{{ $persentaseHadir }}%"></span></div>
        </article>
        <article class="ta-summary-card">
            <span>Sisa pertemuan</span>
            <strong>{{ $sisaPertemuan }} <small>kali</small></strong>
            <div class="ta-summary-track"><span style="width:{{ $persentaseSisa }}%"></span></div>
        </article>
    </section>

    <div class="ta-section-heading">
        <div><h2>Riwayat Pertemuan</h2></div>
        <div class="ta-filter-wrap">
            <select id="taFilterStatus" class="select-pertemuan ta-filter" aria-label="Filter status kehadiran">
                <option value="semua">Semua status</option>
                <option value="hadir">Hadir</option>
                <option value="sakit">Sakit</option>
                <option value="izin">Izin</option>
                <option value="alpa">Alpa</option>
            </select>
        </div>
    </div>

    <div class="absensi-table-container ta-table-container">
        <table class="absensi-table ta-table">
            <thead>
                <tr>
                    <th>Pertemuan</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Jadwal</th>
                    <th>Status Kehadiran</th>
                </tr>
            </thead>
            <tbody id="taAttendanceBody">
                @forelse ($daftarPertemuan as $pertemuan)
                    <tr data-status="{{ $pertemuan['status'] }}">
                        <td><span class="ta-meeting-number">{{ str_pad($pertemuan['ke'], 2, '0', STR_PAD_LEFT) }}</span></td>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($pertemuan['tanggal'])->translatedFormat('d M Y') }}</strong>
                            <small class="ta-cell-sub">{{ $pertemuan['hari'] }}</small>
                        </td>
                        <td>{{ $pertemuan['jam'] }}</td>
                        <td><span class="ta-schedule-pill">{{ $pertemuan['jadwal'] }}</span></td>
                        <td><span class="ta-status-pill ta-status-{{ $pertemuan['status'] }}">{{ ucfirst($pertemuan['status']) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="o-empty">Belum ada riwayat pertemuan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
.o-operator-absensi-detail .ta-heading-info h1{margin:0}
.o-operator-absensi-detail .ta-heading-info .o-page-subtitle{margin:5px 0 0}
.o-operator-absensi-detail .o-detail-tentor{margin-top:7px;font-size:13px;opacity:.8}
.o-operator-absensi-detail .o-detail-tentor span{padding:0 5px}
.o-operator-absensi-detail .ta-table th:last-child,.o-operator-absensi-detail .ta-table td:last-child{text-align:center}
.o-operator-absensi-detail .ta-table{min-width:720px}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const body = document.getElementById('taAttendanceBody');
    const filter = document.getElementById('taFilterStatus');

    filter?.addEventListener('change', function () {
        body.querySelectorAll('tr[data-status]').forEach(row => {
            row.hidden = this.value !== 'semua' && row.dataset.status !== this.value;
        });
    });
});
</script>
@endsection
