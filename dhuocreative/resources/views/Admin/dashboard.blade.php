@extends('layouts.admin')

@section('content')

@php
    $ringkasan = [
        ['judul' => 'Jadwal Hari Ini', 'angka' => 8, 'satuan' => 'Kelas'],
        ['judul' => 'Total Siswa', 'angka' => 22, 'satuan' => 'Siswa'],
        ['judul' => 'Total Kursus Siswa Aktif', 'angka' => 17, 'satuan' => 'Kursus'],
        ['judul' => 'Total Tentor', 'angka' => 6, 'satuan' => 'Tentor'],
    ];

    $dataKursus = [
        ['id' => 1, 'kursus' => 'Web Master', 'siswa' => 'Siswa A', 'tentor' => 'Tentor C', 'kelas' => 'Offline'],
        ['id' => 2, 'kursus' => 'Web Master', 'siswa' => 'Siswa B', 'tentor' => 'Tentor A', 'kelas' => 'Offline Rumah'],
        ['id' => 3, 'kursus' => 'Office Master', 'siswa' => 'Siswa A', 'tentor' => 'Tentor A', 'kelas' => 'Online'],
        ['id' => 4, 'kursus' => 'Web Master', 'siswa' => 'Siswa C', 'tentor' => 'Tentor C', 'kelas' => 'Online'],
        ['id' => 5, 'kursus' => 'Web Master', 'siswa' => 'Siswa D', 'tentor' => 'Tentor B', 'kelas' => 'Offline'],
        ['id' => 6, 'kursus' => 'Office Master', 'siswa' => 'Siswa E', 'tentor' => 'Tentor A', 'kelas' => 'Online'],
        ['id' => 7, 'kursus' => 'Office Master', 'siswa' => 'Siswa F', 'tentor' => 'Tentor D', 'kelas' => 'Offline'],
        ['id' => 8, 'kursus' => 'Python Master', 'siswa' => 'Siswa G', 'tentor' => 'Tentor B', 'kelas' => 'Offline Rumah'],
        ['id' => 9, 'kursus' => 'Python Master', 'siswa' => 'Siswa H', 'tentor' => 'Tentor E', 'kelas' => 'Online'],
    ];

    $dataPertambahanSiswaBulanIni = [
        'Web Master'    => ['sekarang' => 4, 'lalu' => 3],
        'Office Master' => ['sekarang' => 3, 'lalu' => 2],
        'Python Master' => ['sekarang' => 2, 'lalu' => 1],
    ];

    $statistikPerKursus = [];
    foreach ($dataKursus as $d) {
        $k = $d['kursus'];
        if (!isset($statistikPerKursus[$k])) {
            $statistikPerKursus[$k] = [
                'nama' => $k,
                'siswa_set' => [],
                'tentor_set' => [],
            ];
        }
        $statistikPerKursus[$k]['siswa_set'][$d['siswa']] = true;
        $statistikPerKursus[$k]['tentor_set'][$d['tentor']] = true;
    }

    $grafikBatangData = [];
    foreach ($statistikPerKursus as $k => $info) {
        $grafikBatangData[$k] = [
            'nama' => $k,
            'siswa' => count($info['siswa_set']),
            'tentor' => count($info['tentor_set']),
        ];
    }

    // Konfigurasi Sumbu Y Grafik Batang dimulai dari 1 sampai 5
    $minY = 1;
    $maxY = 5;
    $rangeY = $maxY - $minY; // 4 tingkat (1, 2, 3, 4, 5)

    $totalPertambahanSiswa = 0;
    foreach ($dataPertambahanSiswaBulanIni as $val) {
        $totalPertambahanSiswa += max(0, $val['sekarang'] - $val['lalu']);
    }

    $distribusiKursus = [];
    $warnaDonut = ['#f27640', '#ffb83f', '#286c65', '#ffdc59', '#8aaea9'];
    $donutGradient = [];
    $akumulasiPersen = 0;
    $idx = 0;

    foreach ($dataPertambahanSiswaBulanIni as $namaKursus => $item) {
        $selisih = max(0, $item['sekarang'] - $item['lalu']);
        $persen = $totalPertambahanSiswa > 0 ? ($selisih / $totalPertambahanSiswa) * 100 : 0;
        $warna = $warnaDonut[$idx % count($warnaDonut)];

        $distribusiKursus[] = [
            'nama' => $namaKursus,
            'jumlah' => $selisih,
            'persen' => round($persen, 1),
            'warna' => $warna
        ];

        $batasAkhir = $akumulasiPersen + $persen;
        $donutGradient[] = "{$warna} {$akumulasiPersen}% {$batasAkhir}%";
        $akumulasiPersen = $batasAkhir;
        $idx++;
    }

    $donutBackground = count($donutGradient) > 0
        ? 'conic-gradient(' . implode(', ', $donutGradient) . ')'
        : '#e4e8e4';
@endphp

<div class="dashboard admin-dashboard">

    {{-- RINGKASAN DAN LAPORAN --}}
    <section class="summary admin-summary">
        @foreach ($ringkasan as $item)
            <div class="summary-card">
                <h3>{{ $item['judul'] }}</h3>
                <div class="summary-bottom">
                    <div class="summary-number">
                        {{ $item['angka'] }}
                        <span>{{ $item['satuan'] }}</span>
                    </div>
                    <button type="button" class="arrow-button" aria-label="Lihat {{ $item['judul'] }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endforeach

        {{-- CARD LAPORAN ABSENSI --}}
        <article class="admin-report-card">
            <div class="admin-report-top">
                <div class="admin-report-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
                        <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0"/>
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

                <div class="admin-bar-chart">
                    {{-- SUMBU Y (Mulai dari 5 turun ke 1) --}}
                    <div class="admin-chart-y-labels">
                        @for ($i = $maxY; $i >= $minY; $i--)
                            <span>{{ $i }}</span>
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
                        <div class="admin-chart-bars">
                            @forelse ($grafikBatangData as $item)
                                @php
                                    $valSiswa = max($minY, min($maxY, $item['siswa']));
                                    $valTentor = max($minY, min($maxY, $item['tentor']));

                                    $hSiswa = (($valSiswa - $minY) / $rangeY) * 100;
                                    $hTentor = (($valTentor - $minY) / $rangeY) * 100;
                                @endphp
                                <div class="admin-chart-month">
                                    <div class="admin-bar-pair">
                                        <span class="admin-bar admin-bar-siswa"
                                              style="height: {{ max(6, $hSiswa) }}%;"
                                              title="{{ $item['nama'] }} - Siswa: {{ $item['siswa'] }}">
                                        </span>
                                        <span class="admin-bar admin-bar-tentor"
                                              style="height: {{ max(6, $hTentor) }}%;"
                                              title="{{ $item['nama'] }} - Tentor: {{ $item['tentor'] }}">
                                        </span>
                                    </div>
                                    <small>{{ $item['nama'] }}</small>
                                </div>
                            @empty
                                <p>Belum ada data kursus.</p>
                            @endforelse
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

                    <div class="admin-donut-legend">
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
                </div>
            </article>

        </div>
    </section>

</div>

<script>
const dataLaporanAdmin = {
    absensi: {
        nama: 'Laporan_Absensi_Bulanan',
        judul: 'Laporan Rekap Absensi Bulanan',
        header: ['No', 'Nama Siswa', 'Kursus', 'Jenis Kelas', 'Tentor', 'Total Pertemuan', 'Hadir', 'Izin', 'Sakit', 'Alpa', 'Persentase Kehadiran'],
        rows: [
            [1, 'Siswa A', 'Web Master', 'Online', 'Tentor C', 12, 11, 1, 0, 0, '91.7%'],
            [2, 'Siswa B', 'MS Office', 'Offline', 'Tentor D', 8, 8, 0, 0, 0, '100%'],
            [3, 'Siswa C', 'Web Master', 'Offline Rumah', 'Tentor B', 12, 10, 1, 0, 1, '83.3%']
        ]
    },
    siswa: {
        nama: 'Laporan_Siswa_dan_Kursus',
        judul: 'Laporan Rekap Siswa & Kursus',
        header: ['No', 'Nama Kursus', 'Jumlah Siswa Aktif', 'Siswa Baru Bulan Ini', 'Jenis Kelas Dominan', 'Status Kursus'],
        rows: [
            [1, 'Web Master', 14, 3, 'Online (8 Siswa)', 'Aktif'],
            [2, 'MS Office', 8, 2, 'Offline (5 Siswa)', 'Aktif']
        ]
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
            <p style="text-align:right;margin-top:28px">Dicetak pada: ${new Date().toLocaleDateString('id-ID')}</p>
        </body>
        </html>
    `);
    popup.document.close();
    popup.focus();
    popup.print();
}
</script>

@endsection