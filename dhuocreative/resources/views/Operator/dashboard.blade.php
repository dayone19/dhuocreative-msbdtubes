@extends('layouts.Operator')

@section('title', 'Dashboard Operator')

@section('content')

@php
    $ringkasan = [
        ['judul' => 'Jadwal Hari Ini',   'angka' => 8,  'satuan' => 'Kelas', 'lebar' => 195],
        ['judul' => 'Total Siswa Aktif', 'angka' => 15, 'satuan' => 'Siswa',       'lebar' => 205],
        ['judul' => 'Total Kursus Siswa Aktif','angka' => 17,  'satuan' => 'Kursus',  'lebar' => 195],
        ['judul' => 'Total Tentor',      'angka' => 6,  'satuan' => 'Tentor',      'lebar' => 195],
    ];

    $jadwalHariIni = [
        ['kursus' => 'Web Master', 'siswa' => 'Siswa A', 'tentor' => 'Tentor A', 'jam' => '11.30 - 12.30', 'hadir' => 1, 'total' => 1],
        ['kursus' => 'Web Master', 'siswa' => 'Siswa C', 'tentor' => 'Tentor B', 'jam' => '13.00 - 14.00', 'hadir' => 1, 'total' => 1],
        ['kursus' => 'Office Master', 'siswa' => 'Siswa E', 'tentor' => 'Tentor C', 'jam' => '14.30 - 15.30', 'hadir' => 0, 'total' => 1],
        ['kursus' => 'Office Master', 'siswa' => 'Siswa B', 'tentor' => 'Tentor D', 'jam' => '15.00 - 16.00', 'hadir' => 0, 'total' => 1],
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

    {{-- FITUR LAPORAN BULANAN PRIVAT (DI ATAS JADWAL HARI INI) --}}
    <section class="o-report-section" style="margin-bottom: 30px;">
        <div class="o-report-heading">
            <div>
                <h2>Laporan Bulanan</h2>
            </div>
            <div class="o-schedule-filters">
                <input type="month" id="pilihBulanLaporan" class="o-filter" value="{{ date('Y-m') }}" aria-label="Pilih Bulan Laporan" style="background-image: none">
            </div>
        </div>

        <div class="o-report-grid">
            {{-- Laporan 1: Absensi & Pertemuan Privat --}}
            <article class="o-report-card">
                <div style="display: flex; gap: 15px; align-items: flex-start;">
                    <div class="o-report-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                        <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
                        <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0M7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0"/>
                        </svg>
                    </div>
                    <div class="o-report-copy">
                        <h3>Laporan Absensi & Sesi Privat Bulanan</h3>
                        <p>Rekap kehadiran siswa, total sesi, persentase kehadiran, serta rincian kehadiran per bulan.</p>
                    </div>
                </div>
                <div class="o-report-actions">
                    <button type="button" style="font-size:13px" class="o-btn o-btn-orange" onclick="unduhLaporan('absensi')">
                        Unduh Excel (CSV)
                    </button>
                    <button type="button" style="font-size:13px" class="o-btn o-btn-outline" onclick="cetakLaporan('absensi')">
                        Cetak / PDF
                    </button>
                </div>
            </article>

            {{-- Laporan 2: Distribution Kursus & Tentor --}}
            <article class="o-report-card">
                <div style="display: flex; gap: 15px; align-items: flex-start;">
                    <div class="o-report-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-vcard" viewBox="0 0 16 16">
                        <path d="M5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4m4-2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5M9 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4A.5.5 0 0 1 9 8m1 2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5"/>
                        <path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H8.96q.04-.245.04-.5C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 1 1 12z"/>
                        </svg>
                    </div>
                    <div class="o-report-copy">
                        <h3>Laporan Distribusi Siswa & Kursus Privat</h3>
                        <p>Ringkasan total siswa aktif per kursus, jenis kelas, dan pendaftar baru.</p>
                    </div>
                </div>
                <div class="o-report-actions">
                    <button type="button" style="font-size:13px" class="o-btn o-btn-orange" onclick="unduhLaporan('siswa')">
                        Unduh Excel (CSV)
                    </button>
                    <button type="button" style="font-size:13px" class="o-btn o-btn-outline" onclick="cetakLaporan('siswa')">
                        Cetak / PDF
                    </button>
                </div>
            </article>
        </div>
    </section>

    {{-- JADWAL HARI INI --}}
    <section class="schedule-section o-mint-theme">
        <h2>Jadwal Hari Ini</h2>

        @forelse ($jadwalHariIni as $row)
            <div class="schedule-row">
                <div class="schedule-name">{{ $row['kursus'] }}</div>
                <div class="schedule-tentor">{{ $row['siswa'] }}</div>
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

<script>
// Fungsi Unduh Excel (CSV) Khusus Model Les Privat
function unduhLaporan(jenis) {
    const bulanInput = document.getElementById('pilihBulanLaporan').value;
    if (!bulanInput) {
        alert('Silakan pilih bulan laporan terlebih dahulu.');
        return;
    }

    let csvContent = "\uFEFF"; // UTF-8 BOM
    let namaFile = "";

    if (jenis === 'absensi') {
        namaFile = `Laporan_Absensi_Privat_${bulanInput}.csv`;
        csvContent += "LAPORAN REKAP BULANAN ABSENSI\n";
        csvContent += `Bulan: ${bulanInput}\n\n`;
        csvContent += "No,Nama Siswa,Kursus,Jenis Kelas,Tentor,Total Sesi Bulanan,Hadir,Izin/Sakit,Alpa,Persentase Kehadiran\n";
        csvContent += '1,"Siswa A","Web Master","Online","Tentor C",12,11,1,0,"91.7%"\n';
        csvContent += '2,"Siswa B","MS Office","Offline","Tentor D",8,8,0,0,"100%"\n';
        csvContent += '3,"Siswa C","Web Master","Offline Rumah","Tentor B",12,10,1,1,"83.3%"\n';
    } else {
        namaFile = `Laporan_Siswa_Kursus_Privat_${bulanInput}.csv`;
        csvContent += "LAPORAN REKAP SISWA & KURSUS\n";
        csvContent += `Bulan: ${bulanInput}\n\n`;
        csvContent += "No,Nama Kursus,Jumlah Siswa Privat,Siswa Baru Bulan Ini,Kelas Terbanyak,Status Kursus\n";
        csvContent += '1,"Web Master",14,3,"Online (8 Siswa)",Aktif\n';
        csvContent += '2,"MS Office",8,2,"Offline (5 Siswa)",Aktif\n';
    }

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement("a");
    link.setAttribute("href", url);
    link.setAttribute("download", namaFile);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Fungsi Cetak / Print PDF Khusus Les Privat
function cetakLaporan(jenis) {
    const bulanInput = document.getElementById('pilihBulanLaporan').value;
    if (!bulanInput) {
        alert('Silakan pilih bulan laporan terlebih dahulu.');
        return;
    }

    const judul = jenis === 'absensi' 
        ? 'Laporan Rekap Absensi Bulanan' 
        : 'Laporan Rekap Distribusi Siswa & Kursus';
        
    const printWindow = window.open('', '', 'width=950,height=650');

    let isiTabel = '';
    if (jenis === 'absensi') {
        isiTabel = `
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Siswa</th>
                    <th>Kursus</th>
                    <th>Jenis Kelas</th>
                    <th>Tentor</th>
                    <th>Total Pertemuan</th>
                    <th>Hadir</th>
                    <th>Izin</th>
                    <th>Sakit</th>
                    <th>Alpa</th>
                    <th>Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>1</td><td>Siswa A</td><td>Web Master</td><td>Online</td><td>Tentor C</td><td>12</td><td>11</td><td>1</td><td>0</td><td>0</td><td>91.7%</td></tr>
                <tr><td>2</td><td>Siswa B</td><td>MS Office</td><td>Offline</td><td>Tentor D</td><td>8</td><td>8</td><td>0</td><td>0</td><td>0</td><td>100%</td></tr>
                <tr><td>3</td><td>Siswa C</td><td>Web Master</td><td>Offline Rumah</td><td>Tentor B</td><td>12</td><td>10</td><td>1</td><td>0</td><td>1</td><td>83.3%</td></tr>
            </tbody>
        `;
    } else {
        isiTabel = `
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Kursus</th>
                    <th>Jumlah Siswa Privat</th>
                    <th>Siswa Baru Bulan Ini</th>
                    <th>Jenis Kelas Dominan</th>
                    <th>Status Kursus</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>1</td><td>Web Master</td><td>14 Siswa</td><td>3 Siswa</td><td>Online (8 Siswa)</td><td>Aktif</td></tr>
                <tr><td>2</td><td>MS Office</td><td>8 Siswa</td><td>2 Siswa</td><td>Offline (5 Siswa)</td><td>Aktif</td></tr>
            </tbody>
        `;
    }

    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>${judul} - ${bulanInput}</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 25px; color: #333; }
                h1 { color: #286c65; margin-bottom: 5px; font-size: 20px; }
                p { color: #555; margin-top: 0; font-size: 13px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ccc; padding: 8px 10px; text-align: left; font-size: 12px; }
                th { background-color: #ffdc59; color: #286c65; }
                .footer { margin-top: 30px; text-align: right; font-size: 11px; color: #777; }
            </style>
        </head>
        <body>
            <h1>${judul}</h1>
            <p><strong>Periode Bulan:</strong> ${bulanInput}</p>
            <table>${isiTabel}</table>
            <div class="footer">
                <p>Dicetak pada: ${new Date().toLocaleDateString('id-ID')}</p>
            </div>
        </body>
        </html>
    `);

    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 500);
}
</script>
@endsection