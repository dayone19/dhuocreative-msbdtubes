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
            <tbody id="bodyRekapAbsensi">
                @forelse ($rekapAbsensi as $row)
                    @php
                        $id = is_array($row) ? $row['id'] : $row->id;
                        $kursus = is_array($row) ? $row['kursus'] : ($row->nama_kursus ?? '-');
                        $nama = is_array($row) ? $row['nama'] : ($row->nama_siswa ?? '-');
                        $tentor = is_array($row) ? $row['tentor'] : ($row->nama_tentor ?? '-');
                        $kelas = is_array($row) ? $row['kelas'] : ($row->nama_kelas ?? '-');
                        $hadir = is_array($row) ? $row['hadir'] : ($row->jumlah_hadir ?? 0);
                        $total = is_array($row) ? $row['total'] : ($row->total_pertemuan ?? 0);

                        $persentase = $total > 0 ? round(($hadir / $total) * 100) : 0;
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
                            <a href="{{ route('operator.absensiSiswa', ['id' => $id]) }}" class="o-action o-attendance-view">
                                Lihat
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="o-empty">
                            Belum ada data absensi siswa.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <p class="o-empty" id="kosongAbsensi" hidden style="margin-top:15px;">Data absensi tidak ditemukan.</p>
    <nav class="tj-pagination" id="paginasiAbsensi" aria-label="Halaman rekap absensi" style="margin-top:20px;" hidden></nav>
</div>

<script>
const PER_HALAMAN_ABSEN = 10;
let halAbsen = 1;

function renderPaginasi(navId, total, halaman, pindah) {
    const nav = document.getElementById(navId);
    if (!nav) return;
    
    nav.replaceChildren();
    if (!total || total <= 1) {
        nav.hidden = true;
        return;
    }
    nav.hidden = false;
    
    const jumlah = Math.ceil(total / PER_HALAMAN_ABSEN);
    const awal = (halaman - 1) * PER_HALAMAN_ABSEN + 1;
    const akhir = Math.min(halaman * PER_HALAMAN_ABSEN, total);

    const info = document.createElement('span');
    info.className = 'tj-pg-info';
    info.textContent = `Menampilkan ${awal}–${akhir} dari ${total}`;
    nav.appendChild(info);

    const btn = (label, tujuan, { aktif = false, mati = false, aria } = {}) => {
        const b = document.createElement('button');
        b.type = 'button';
        b.className = 'tj-pg-btn' + (aktif ? ' active' : '');
        b.textContent = label;
        b.disabled = mati;
        if (aria) b.setAttribute('aria-label', aria);
        if (aktif) b.setAttribute('aria-current', 'page');
        b.addEventListener('click', () => pindah(tujuan));
        return b;
    };

    const grup = document.createElement('div');
    grup.className = 'tj-pg-list';
    grup.appendChild(btn('‹', halaman - 1, { mati: halaman === 1, aria: 'Halaman sebelumnya' }));

    let terakhir = 0;
    for (let i = 1; i <= jumlah; i++) {
        if (i === 1 || i === jumlah || Math.abs(i - halaman) <= 1) {
            if (i - terakhir > 1) {
                const t = document.createElement('span');
                t.className = 'tj-pg-dots';
                t.textContent = '…';
                grup.appendChild(t);
            }
            grup.appendChild(btn(String(i), i, { aktif: i === halaman, aria: `Halaman ${i}` }));
            terakhir = i;
        }
    }

    grup.appendChild(btn('›', halaman + 1, { mati: halaman === jumlah, aria: 'Halaman berikutnya' }));
    nav.appendChild(grup);
}

document.addEventListener('DOMContentLoaded', function () {
    const body = document.getElementById('bodyRekapAbsensi');
    const searchInput = document.getElementById('searchRekapAbsensi');
    const emptyMsg = document.getElementById('kosongAbsensi');

    function filterTabelAbsensi() {
        const q = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const allRows = [...body.querySelectorAll('tr')];

        const filteredRows = allRows.filter(r => r.textContent.toLowerCase().includes(q));

        allRows.forEach(r => r.style.display = 'none');

        const totalFiltered = filteredRows.length;
        if (emptyMsg) emptyMsg.hidden = totalFiltered > 0;

        if (totalFiltered === 0) {
            renderPaginasi('paginasiAbsensi', 0, halAbsen, pindahHalamanAbsen);
            return;
        }

        const maxPage = Math.ceil(totalFiltered / PER_HALAMAN_ABSEN);
        if (halAbsen > maxPage) halAbsen = maxPage || 1;

        const start = (halAbsen - 1) * PER_HALAMAN_ABSEN;
        const end = start + PER_HALAMAN_ABSEN;

        filteredRows.slice(start, end).forEach(r => {
            r.style.display = '';
        });

        renderPaginasi('paginasiAbsensi', totalFiltered, halAbsen, pindahHalamanAbsen);
    }

    function pindahHalamanAbsen(tujuanBaru) {
        halAbsen = tujuanBaru;
        filterTabelAbsensi();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            halAbsen = 1;
            filterTabelAbsensi();
        });
    }

    filterTabelAbsensi();
});
</script>
@endsection