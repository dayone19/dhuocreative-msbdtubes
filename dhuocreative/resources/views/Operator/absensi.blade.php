@extends('layouts.Operator')

@section('title', 'Rekap Absensi')

@section('content')
<div class="o-page o-attendance-page">
    <div class="o-page-header">
        <div>
            <h1 class="o-page-title">Rekap Absensi</h1>
        </div>
    </div>

    <form method="GET" action="{{ route('operator.absensi') }}" class="o-attendance-filters">
        <div class="o-form-group">
            <label for="tanggal_dari">Dari tanggal</label>
            <input type="date" id="tanggal_dari" name="tanggal_dari" value="{{ request('tanggal_dari') }}">
        </div>
        <div class="o-form-group">
            <label for="tanggal_sampai">Sampai tanggal</label>
            <input type="date" id="tanggal_sampai" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}">
        </div>
        <div class="o-form-group">
            <label for="id_kelas">Kelas</label>
            <select id="id_kelas" name="id_kelas">
                <option value="">Semua kelas</option>
                @foreach (($daftarKelas ?? []) as $kelas)
                    <option value="{{ $kelas->id_kelas }}" @selected((string) request('id_kelas') === (string) $kelas->id_kelas)>{{ $kelas->nama_kelas }}</option>
                @endforeach
            </select>
        </div>
        <div class="o-form-group">
            <label for="status">Status absensi</label>
            <select id="status" name="status">
                <option value="">Semua status</option>
                @foreach (($daftarStatusAbsensi ?? []) as $statusAbsensi)
                    <option value="{{ $statusAbsensi }}" @selected(request('status') === $statusAbsensi)>{{ ucfirst(str_replace('_', ' ', $statusAbsensi)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="o-attendance-filter-actions">
            <button class="o-btn o-btn-orange" type="submit">Terapkan Filter</button>
            <a class="o-btn o-btn-outline" href="{{ route('operator.absensi') }}">Reset</a>
        </div>
    </form>

    <div class="o-attendance-stats">
        <article class="o-attendance-stat"><span>Total catatan absensi</span><strong>{{ $totalCatatan ?? count($rekapAbsensi ?? []) }}</strong></article>
        <article class="o-attendance-stat"><span>Hadir</span><strong>{{ $jumlahHadir ?? '—' }}</strong></article>
        <article class="o-attendance-stat"><span>Izin / Sakit</span><strong>{{ $jumlahIzinSakit ?? '—' }}</strong></article>
        <article class="o-attendance-stat"><span>Alpa</span><strong>{{ $jumlahAlpa ?? '—' }}</strong></article>
    </div>

    <div class="o-toolbar">
        <h2>Data Absensi Siswa</h2>
        <input type="search" class="o-search" id="searchRekapAbsensi" placeholder="Cari siswa, kelas, atau tentor...">
    </div>

    <div class="j-table-wrap">
        <table class="j-table" id="tableRekapAbsensi">
            <thead><tr><th>No.</th><th>Tanggal Jadwal</th><th>Kelas</th><th>Tentor</th><th>Siswa</th><th>Waktu Absen</th><th>Status</th></tr></thead>
            <tbody>
            @forelse (($rekapAbsensi ?? []) as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row->tanggal ? \Carbon\Carbon::parse($row->tanggal)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $row->nama_kelas ?? '-' }}</td>
                    <td>{{ $row->nama_tentor ?? '-' }}</td>
                    <td>{{ $row->nama_siswa ?? ('Siswa #' . ($row->id_siswa ?? '-')) }}</td>
                    <td>{{ $row->waktu_absen ? \Carbon\Carbon::parse($row->waktu_absen)->format('d-m-Y H:i') : '-' }}</td>
                    <td><span class="o-status {{ strtolower(str_replace(' ', '-', (string) ($row->status ?? ''))) }}">{{ ucfirst(str_replace('_', ' ', (string) ($row->status ?? '-'))) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="7" class="o-empty">Belum ada data absensi sesuai filter.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('searchRekapAbsensi')?.addEventListener('input', function () {
    const q = this.value.toLocaleLowerCase('id');
    document.querySelectorAll('#tableRekapAbsensi tbody tr').forEach(row => {
        row.hidden = !row.innerText.toLocaleLowerCase('id').includes(q);
    });
});
</script>
@endsection
