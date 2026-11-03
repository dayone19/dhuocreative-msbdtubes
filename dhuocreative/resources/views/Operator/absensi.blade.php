@extends('layouts.Operator')

@section('title', 'Rekap Absensi')

@section('content')
@php
    $rekapAbsensi = $rekapAbsensi ?? [
        ['id' => 1208, 'kursus' => 'Web Master', 'nama' => 'Siswa A', 'tentor' => 'Tentor C', 'kelas' => 'Online', 'hadir' => 5, 'total' => 12],
        ['id' => 1209, 'kursus' => 'Web Master', 'nama' => 'Siswa B', 'tentor' => 'Tentor A', 'kelas' => 'Offline', 'hadir' => 10, 'total' => 12],
        ['id' => 1210, 'kursus' => 'Office Master', 'nama' => 'Siswa C', 'tentor' => 'Tentor B', 'kelas' => 'Online', 'hadir' => 8, 'total' => 10],
        ['id' => 1211, 'kursus' => 'Office Master', 'nama' => 'Siswa D', 'tentor' => 'Tentor C', 'kelas' => 'Offline Rumah', 'hadir' => 11, 'total' => 12],
    ];
@endphp

<div class="o-page o-attendance-page">
    <div class="o-page-header">
        <div>
            <h1 class="o-page-title">Rekap Absensi</h1>
        </div>
    </div>


    <div class="o-attendance-stats">
        <article class="o-attendance-stat">
            <span>Total siswa</span>
            <strong>{{ count($rekapAbsensi) }}</strong>
        </article>
        <article class="o-attendance-stat">
            <span>Total kehadiran</span>
            <strong>{{ collect($rekapAbsensi)->sum('hadir') }}</strong>
        </article>
        <article class="o-attendance-stat">
            <span>Total pertemuan</span>
            <strong>{{ collect($rekapAbsensi)->sum('total') }}</strong>
        </article>
        <article class="o-attendance-stat">
            <span>Rata-rata hadir</span>
            <strong>{{ collect($rekapAbsensi)->sum('total') ? round(collect($rekapAbsensi)->sum('hadir') / collect($rekapAbsensi)->sum('total') * 100) : 0 }}%</strong>
        </article>
    </div>

    <div class="o-toolbar">
        <input type="search" class="o-search" id="searchRekapAbsensi" placeholder="Cari siswa, kursus, atau tentor...">
    </div>

    <div class="j-table-wrap">
        <table class="j-table o-attendance-table" id="tableRekapAbsensi">
            <thead>
                <tr>
                    <th>Kursus</th>
                    <th>Nama Siswa</th>
                    <th>Tentor</th>
                    <th>Kelas</th>
                    <th>Kehadiran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rekapAbsensi as $row)
                    @php
                        $id = is_array($row) ? $row['id'] : $row->id;
                        $kursus = is_array($row) ? $row['kursus'] : ($row->nama_kursus ?? '-');
                        $nama = is_array($row) ? $row['nama'] : ($row->nama_siswa ?? '-');
                        $tentor = is_array($row) ? $row['tentor'] : ($row->nama_tentor ?? '-');
                        $kelas = is_array($row) ? $row['kelas'] : ($row->nama_kelas ?? '-');
                        $hadir = is_array($row) ? $row['hadir'] : ($row->jumlah_hadir ?? 0);
                        $total = is_array($row) ? $row['total'] : ($row->total_pertemuan ?? 0);

                        $persentase = $total > 0
                            ? round(($hadir / $total) * 100)
                            : 0;
                    @endphp

                    <tr>
                        <td>{{ $kursus }}</td>

                        <td>
                            <strong class="o-attendance-student">{{ $nama }}</strong>
                        </td>

                        <td>{{ $tentor }}</td>

                        <td>{{ $kelas }}</td>

                        <td>
                            <div class="o-attendance-rate">
                                <strong>{{ $hadir }} / {{ $total }}</strong>
                                <span>{{ $persentase }}%</span>
                            </div>

                            <div class="o-attendance-progress">
                                <span style="width: {{ $persentase }}%"></span>
                            </div>
                        </td>

                        <td>
                            <a href="{{ route('operator.absensiSiswa', ['id' => $id]) }}"
                            class="o-action o-attendance-view">
                                Lihat
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="o-empty">
                            Belum ada data absensi siswa.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
.o-attendance-page .o-page-subtitle{margin-top:7px;color:#71817a;font-size:14px}
.o-attendance-page .o-attendance-filters{grid-template-columns:repeat(4,minmax(0,1fr));gap:0 16px;padding:20px;margin-bottom:22px;border-radius:22px;background:#e3efed}
.o-attendance-page .o-attendance-filters .o-form-group{margin-bottom:12px}
.o-attendance-page .o-attendance-filter-actions{grid-column:1/-1;display:flex;gap:9px;flex-wrap:wrap}
.o-attendance-page .o-attendance-stats{grid-template-columns:repeat(4,minmax(0,1fr))}
.o-attendance-page .o-attendance-table{min-width:850px;table-layout:auto}
.o-attendance-page .o-attendance-table th,.o-attendance-page .o-attendance-table td{text-align:left}
.o-attendance-page .o-attendance-table th:last-child,.o-attendance-page .o-attendance-table td:last-child{text-align:center}
.o-attendance-student{display:block;color:#286c65;font-size:14px}
.o-attendance-id{display:block;margin-top:4px;color:#89948e;font-size:11px}
.o-attendance-rate{display:flex;justify-content:space-between;gap:12px;color:#286c65;font-size:13px}
.o-attendance-rate span{color:#f27640;font-weight:700}
.o-attendance-progress{height:7px;max-width:130px;margin-top:7px;overflow:hidden;border-radius:20px;background:#f1e5d2}
.o-attendance-progress span{display:block;height:100%;border-radius:20px;background:linear-gradient(90deg,#ffb83f,#f27640)}
.o-attendance-view{display:inline-flex;justify-content:center;min-width:72px;padding:8px 15px;text-decoration:none}
@media(max-width:950px){.o-attendance-page .o-attendance-filters{grid-template-columns:repeat(2,minmax(0,1fr))}.o-attendance-page .o-attendance-stats{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:600px){.o-attendance-page .o-attendance-filters,.o-attendance-page .o-attendance-stats{grid-template-columns:1fr}.o-attendance-page .o-attendance-filter-actions .o-btn{flex:1}}
</style>

<script>
document.getElementById('searchRekapAbsensi')?.addEventListener('input', function () {
    const q = this.value.toLocaleLowerCase('id');
    document.querySelectorAll('#tableRekapAbsensi tbody tr').forEach(row => {
        row.hidden = !row.innerText.toLocaleLowerCase('id').includes(q);
    });
});
</script>
@endsection
