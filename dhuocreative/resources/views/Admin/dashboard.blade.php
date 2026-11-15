@extends('layouts.admin')

@section('content')

<div class="dashboard admin-dashboard">

    {{-- RINGKASAN DAN LAPORAN --}}
    <section class="summary admin-summary">
                <div class="summary-card">
            <h3>Jadwal Hari Ini</h3>
            <div class="summary-bottom">
                <div class="summary-number">
                    {{ $totalJadwalHariIni }}
                    <span>Kelas</span>
                </div>
            </div>
        </div>

        <div class="summary-card">
            <h3>Total Siswa</h3>
            <div class="summary-bottom">
                <div class="summary-number">
                    {{ $totalSiswa }}
                    <span>Siswa</span>
                </div>
            </div>
        </div>

        <div class="summary-card">
            <h3>Total Kursus Siswa Aktif</h3>
            <div class="summary-bottom">
                <div class="summary-number">
                    {{ $totalKursusAktif }}
                    <span>Kursus</span>
                </div>
            </div>
        </div>

        <div class="summary-card">
            <h3>Total Tentor</h3>
            <div class="summary-bottom">
                <div class="summary-number">
                    {{ $totalTentor }}
                    <span>Tentor</span>
                </div>
            </div>
        </div>

        {{-- CARD LAPORAN ABSENSI --}}
        <article class="admin-report-card">
            <div class="admin-report-top">
                <div class="admin-report-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
                    <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0M7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0"/>
                    </svg>
                </div>
                <div class="admin-report-copy">
                    <h3>Laporan Absensi</h3>
                </div>
            </div>
            <div class="admin-report-actions">
                <button type="button" class="admin-report-download" onclick="unduhLaporanAdmin('absensi')">Unduh Excel (CSV)</button>
                <button type="button" class="admin-report-print" onclick="cetakLaporanAdmin('absensi')">Cetak / PDF</button>
            </div>
        </article>

        {{-- CARD LAPORAN SISWA DAN KURSUS --}}
        <article class="admin-report-card">
            <div class="admin-report-top">
                <div class="admin-report-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4m4-2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5M9 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4A.5.5 0 0 1 9 8"/>
                        <path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H8.96q.04-.245.04-.5C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 1 1 12z"/>
                    </svg>
                </div>
                <div class="admin-report-copy">
                    <h3>Laporan Siswa & Kursus</h3>
                </div>
            </div>
            <div class="admin-report-actions">
                <button type="button" class="admin-report-download" onclick="unduhLaporanAdmin('siswa')">Unduh Excel (CSV)</button>
                <button type="button" class="admin-report-print" onclick="cetakLaporanAdmin('siswa')">Cetak / PDF</button>
            </div>
        </article>
    </section>

    {{-- GRAFIK DASHBOARD --}}
    <section class="admin-chart-section">
        <div class="admin-chart-heading">
            <div>
                <h2>Statistik Operasional</h2>
            </div>
        </div>

        <div class="admin-chart-grid">

            {{-- 1. GRAFIK BATANG (Mulai dari Y=1) --}}
            <article class="admin-chart-card">
                <div class="admin-chart-title">
                    <div>
                        <h3>Jumlah Siswa & Tentor per Kursus</h3>
                    </div>

                    <div class="admin-chart-legend">
                        <span><i class="legend-siswa"></i> Siswa</span>
                        <span><i class="legend-tentor"></i> Tentor</span>
                    </div>
                </div>
                
                <div class="admin-chart-area">
                    <div class="admin-bar-chart">
                        <div class="admin-chart-y-labels">
                            @for ($i = $rangeY; $i >= 0; $i--)
                                <span>{{ $i * $intervalY }}</span>
                            @endfor
                        </div>

                        <div class="admin-chart-plot">
                            {{-- GRID LINES --}}
                            <div class="admin-chart-gridlines">
                                @for ($i = 0; $i <= $rangeY; $i++)
                                    <i></i>
                                @endfor
                            </div>

                            {{-- BATANG GRAFIK --}}
                            <div class="admin-chart-viewport" id="kursusChartViewport">
                                <div class="admin-chart-bars">
                                    @forelse ($statistikKursus as $item)
                                        <div class="admin-chart-month">
                                            <div class="admin-bar-pair">
                                            <span class="admin-bar admin-bar-siswa"
                                                    style="height: {{ $maxY > 0 ? ($item->total_siswa / $maxY) * 100 : 0 }}%;"
                                                    title="{{ $item->nama_kursus }} - Siswa: {{ $item->total_siswa }}">
                                                </span>
                                                <span class="admin-bar admin-bar-tentor"
                                                    style="height: {{ $maxY > 0 ? ($item->total_tentor / $maxY) * 100 : 0 }}%;"
                                                    title="{{ $item->nama_kursus }} - Tentor: {{ $item->total_tentor }}">
                                                </span>
                                            </div>
                                            <small>{{ $item->nama_kursus }}</small>
                                        </div>
                                    @empty
                                        <p>Belum ada data kursus.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                            <div class="admin-chart-controls">
                                <button type="button" id="kursusChartPrev" disabled>&lsaquo;</button>
                                <button type="button" id="kursusChartNext">&rsaquo;</button>
                            </div>
                    </div>
                </div>
            </article>

            {{-- 2. GRAFIK BULAT / DONUT --}}
            <article class="admin-chart-card">
                <div class="admin-chart-title">
                    <div>
                        <h3>Pertumbuhan Siswa Baru</h3>
                    </div>
                </div>

                <div class="admin-donut-wrap">
                    <div class="admin-donut"
                         role="img"
                         aria-label="Pertumbuhan siswa baru dari bulan sebelumnya"
                         style="background: {{ $donutBackground }}">
                        <div class="admin-donut-center">
                            <strong>+{{ $totalPertambahanSiswa }}</strong>
                            <span>Siswa Baru</span>
                        </div>
                    </div>

                    <div class="admin-donut-legend" id="adminDonutLegend">
                            @forelse ($distribusiKursus as $item)
                                <div class="admin-donut-item">
                                    <span class="admin-donut-name">
                                        <i style="background: {{ $item['warna'] }}"></i>
                                        {{ $item['nama'] }}
                                    </span>
                                    <strong>
                                        +{{ $item['jumlah'] }} Siswa
                                        <small>({{ $item['persen'] }}%)</small>
                                    </strong>
                                </div>
                            @empty
                                <p>Belum ada data pertumbuhan siswa.</p>
                            @endforelse
                        </div>

                        <div class="admin-donut-controls">
                            <button type="button" id="donutPrev" aria-label="Sebelumnya">&lsaquo;</button>
                            <button type="button" id="donutNext" aria-label="Berikutnya">&rsaquo;</button>
                        </div>
                    </div>
                </div>
            </article>

        </div>
    </section>

</div>

@php
    $dataAbsensi = $laporanAbsensi->values()->map(function ($item, $index) {
        return [
            $index + 1,
            $item->nama_siswa,
            $item->nama_kursus,
            $item->jenis_kelas,
            $item->nama_tentor,
            $item->total_pertemuan,
            $item->hadir,
            $item->izin,
            $item->sakit,
            $item->alpa,
            rtrim(rtrim(number_format((float) $item->persentase_kehadiran, 1, '.', ''), '0'), '.') . '%'
        ];
    });

    $dataSiswa = $laporanSiswa->values()->map(function ($item, $index) {
        return [
            $index + 1,
            $item->nama_kursus,
            $item->total_siswa_aktif,
            $item->siswa_baru,
            ucfirst($item->status_kursus)
        ];
    });
@endphp

<script>
const dataLaporanAdmin = {
    absensi: {
        nama: 'Laporan_Rekap_Absensi',
        judul: 'Laporan Rekap Absensi Bulanan',
        periode: @json($periodeAwal->locale('id')->translatedFormat('F Y')),
        header: [
            'No',
            'Nama Siswa',
            'Kursus',
            'Jenis Kelas',
            'Tentor',
            'Total Pertemuan',
            'Hadir',
            'Izin',
            'Sakit',
            'Alpa',
            'Persentase Kehadiran'
        ],
        rows: @json($dataAbsensi)
    },

    siswa: {
        nama: 'Laporan_Siswa_dan_Kursus',
        judul: 'Laporan Rekap Siswa & Kursus',
        periode: @json($periodeAwal->locale('id')->translatedFormat('F Y')),
        header: [
            'No',
            'Nama Kursus',
            'Jumlah Siswa Aktif',
            'Siswa Baru Bulan Ini',
            'Status Kursus'
        ],
        rows: @json($dataSiswa)
    }
};

function escapeCSVAdmin(value) {
    return '"' + String(value).replace(/"/g, '""') + '"';
}

function unduhLaporanAdmin(jenis) {
    const data = dataLaporanAdmin[jenis];
    if (!data) return;

    const bulan = new Date().toISOString().slice(0, 7);
    let csv = '\uFEFF' + data.judul + '\nPeriode: ' + bulan + '\n\n';
    csv += data.header.map(escapeCSVAdmin).join(',') + '\n';
    data.rows.forEach(row => csv += row.map(escapeCSVAdmin).join(',') + '\n');

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = data.nama + '_' + bulan + '.csv';
    document.body.appendChild(link);
    link.click();
    link.remove();
    URL.revokeObjectURL(url);
}

function cetakLaporanAdmin(jenis) {
    const data = dataLaporanAdmin[jenis];
    if (!data) return;

    const bulan = new Date().toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
    const popup = window.open('', '_blank', 'width=1000,height=700');
    if (!popup) {
        alert('Popup diblokir browser. Izinkan popup untuk mencetak laporan.');
        return;
    }

    const head = data.header.map(item => `<th>${item}</th>`).join('');
    const body = data.rows.map(row => `<tr>${row.map(item => `<td>${item}</td>`).join('')}</tr>`).join('');

    popup.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>${data.judul}</title>
            <style>
                body{font-family:Arial,sans-serif;padding:28px;color:#333}
                h1{font-size:21px;color:#286c65;margin-bottom:6px}
                p{font-size:13px;color:#666}
                table{width:100%;border-collapse:collapse;margin-top:22px}
                th,td{border:1px solid #ccc;padding:9px;text-align:left;font-size:11px}
                th{background:#ffdc59;color:#286c65}
            </style>
        </head>
        <body>
            <h1>${data.judul}</h1>
            <p>Periode: ${bulan}</p>
            <table><thead><tr>${head}</tr></thead><tbody>${body}</tbody></table>
            <p style="text-align:right;margin-top:28px">Dicetak pada: ${new Date().toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            })}</p>
        </body>
        </html>
    `);
    popup.document.close();
    popup.focus();
    popup.print();
}

// statistik diagram
const kursusViewport = document.getElementById('kursusChartViewport');
const kursusPrev = document.getElementById('kursusChartPrev');
const kursusNext = document.getElementById('kursusChartNext');

function updateKursusChart() {
    kursusPrev.disabled = kursusViewport.scrollLeft <= 0;

    kursusNext.disabled =
        kursusViewport.scrollLeft + kursusViewport.clientWidth >=
        kursusViewport.scrollWidth - 1;
}

kursusNext.addEventListener('click', () => {
    kursusViewport.scrollBy({
        left: kursusViewport.clientWidth,
        behavior: 'smooth'
    });
});

kursusPrev.addEventListener('click', () => {
    kursusViewport.scrollBy({
        left: -kursusViewport.clientWidth,
        behavior: 'smooth'
    });
});

kursusViewport.addEventListener('scroll', updateKursusChart);

updateKursusChart();
// panah di statistik donat
const donutItems = document.querySelectorAll('#adminDonutLegend .admin-donut-item');
const donutPrev = document.getElementById('donutPrev');
const donutNext = document.getElementById('donutNext');

let donutPage = 0;
const donutPerPage = 5;

function tampilkanDonut() {
    const totalPage = Math.ceil(donutItems.length / donutPerPage);

    donutItems.forEach((item, index) => {
        item.hidden = index < donutPage * donutPerPage ||
                      index >= (donutPage + 1) * donutPerPage;
    });

    donutPrev.disabled = donutPage === 0;
    donutNext.disabled = donutPage >= totalPage - 1;

    donutPrev.style.display = totalPage > 1 ? 'grid' : 'none';
    donutNext.style.display = totalPage > 1 ? 'grid' : 'none';
}

donutPrev.addEventListener('click', () => {
    if (donutPage > 0) {
        donutPage--;
        tampilkanDonut();
    }
});

donutNext.addEventListener('click', () => {
    if ((donutPage + 1) * donutPerPage < donutItems.length) {
        donutPage++;
        tampilkanDonut();
    }
});

tampilkanDonut();
</script>

@endsection