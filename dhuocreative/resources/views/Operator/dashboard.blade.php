{{-- Dashboard Operator - Dhuo Creative --}}
@extends('layouts.Operator')

@section('title', 'Dashboard Operator')

@section('content')

@php
    $ringkasan = $ringkasan ?? [];
    $jadwalHariIni = $jadwalHariIni ?? [];
@endphp

<div class="dashboard">

    <section class="o-summary">
        @foreach ($ringkasan as $item)
            <div class="o-summary-card" @if ($item['lebar'] !== 195) style="width: {{ $item['lebar'] }}px;" @endif>
                <h3>{{ $item['judul'] }}</h3>

                <div class="summary-bottom">
                    <div class="summary-number">
                        {{ $item['angka'] }}
                        <span>{{ $item['satuan'] }}</span>
                    </div>
                    @if ($item['arrow'])
                        <a href="{{ $item['url'] }}" class="arrow-button" aria-label="Lihat {{ $item['judul'] }}" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                                <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </section>

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
                        <p>Ringkasan total siswa per kursus, jumlah kelas, dan status kursus aktif.</p>
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

    <section class="schedule-section o-mint-theme" id="sectionJadwalHariIni">
        <div class="schedule-section-head">
            <h2>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16" style="color: #f27640;">
                    <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                </svg>
                Jadwal Hari Ini
            </h2>
            <span class="schedule-date-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
                    <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857z"/>
                    <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                </svg>
                {{ \Carbon\Carbon::now()->format('d M Y') }}
            </span>
        </div>

        <div class="schedule-table-wrap">
            <div class="schedule-row schedule-row-head">
                <div>Kursus</div>
                <div>Siswa</div>
                <div>Tentor</div>
                <div>Waktu</div>
                <div>Status Hadir</div>
            </div>

            @forelse ($jadwalHariIni as $row)
                <div class="schedule-row">
                    <div class="schedule-name" title="{{ $row['kursus'] }}">{{ $row['kursus'] }}</div>
                    <div class="schedule-siswa" title="{{ $row['siswa'] }}">{{ $row['siswa'] }}</div>
                    <div class="schedule-tentor" title="{{ $row['tentor'] }}">{{ $row['tentor'] }}</div>
                    <div class="schedule-time">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16" style="color: #f27640;">
                            <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0"/>
                        </svg>
                        {{ $row['jam'] }}
                    </div>
                    <div class="attendance">
                        <span class="attendance-number">
                            <span style="color: {{ $row['hadir'] ? '#16a34a' : '#64748b' }};">{{ $row['hadir'] ? 'Hadir' : 'Belum Hadir' }}</span>
                        </span>
                        <div class="progress">
                            <div class="progress-fill" style="width: {{ $row['hadir'] ? '100%' : '0%' }}; background: {{ $row['hadir'] ? 'linear-gradient(90deg, #22c55e, #16a34a)' : '#e2e8f0' }};"></div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="schedule-row-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-calendar-x" viewBox="0 0 16 16" style="color: #a0aec0; margin-bottom: 4px;">
                        <path d="M6.146 7.146a.5.5 0 0 1 .708 0L8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 0 1 0-.708"/>
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                    </svg>
                    <div>Tidak ada jadwal kelas untuk hari ini.</div>
                </div>
            @endforelse
        </div>
    </section>

</div>

<script>
function unduhLaporan(jenis) {
    const bulanInput = document.getElementById('pilihBulanLaporan').value;
    if (!bulanInput) {
        alert('Silakan pilih bulan laporan terlebih dahulu.');
        return;
    }
    window.location.href = `{{ url('/operator/dashboard/laporan') }}/${jenis}?bulan=${bulanInput}`;
}

function cetakLaporan(jenis) {
    const bulanInput = document.getElementById('pilihBulanLaporan').value;
    if (!bulanInput) {
        alert('Silakan pilih bulan laporan terlebih dahulu.');
        return;
    }

    const judul = jenis === 'absensi' 
        ? 'Laporan Rekap Absensi Bulanan' 
        : 'Laporan Rekap Distribusi Siswa & Kursus';

    fetch(`{{ url('/operator/dashboard/laporan-data') }}?jenis=${jenis}&bulan=${bulanInput}`)
        .then(res => res.json())
        .then(data => {
            let isiTabel = '';

            if (jenis === 'absensi') {
                let baris = '';
                if (!data || data.length === 0) {
                    baris = '<tr><td colspan="10" style="text-align:center;">Tidak ada data absensi untuk periode bulan ini.</td></tr>';
                } else {
                    data.forEach((row, idx) => {
                        baris += `
                            <tr>
                                <td>${idx + 1}</td>
                                <td><strong>${row.nama_siswa}</strong></td>
                                <td>${row.nama_kursus}</td>
                                <td>${row.jenis_kelas}</td>
                                <td>${row.nama_tentor}</td>
                                <td>${row.total_sesi}</td>
                                <td>${row.hadir}</td>
                                <td>${row.izin}</td>
                                <td>${row.alpa}</td>
                                <td><strong>${row.kehadiran_pct}</strong></td>
                            </tr>
                        `;
                    });
                }

                isiTabel = `
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Siswa</th>
                            <th>Kursus</th>
                            <th>Jenis Kelas</th>
                            <th>Tentor</th>
                            <th>Total Sesi</th>
                            <th>Hadir</th>
                            <th>Izin/Sakit</th>
                            <th>Alpa</th>
                            <th>Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>${baris}</tbody>
                `;
            } else {
                let baris = '';
                if (!data || data.length === 0) {
                    baris = '<tr><td colspan="5" style="text-align:center;">Tidak ada data kursus.</td></tr>';
                } else {
                    data.forEach((row, idx) => {
                        baris += `
                            <tr>
                                <td>${idx + 1}</td>
                                <td><strong>${row.nama_kursus}</strong></td>
                                <td>${row.total_siswa} Siswa</td>
                                <td>${row.total_kelas} Kelas</td>
                                <td>Aktif</td>
                            </tr>
                        `;
                    });
                }

                isiTabel = `
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Kursus</th>
                            <th>Total Siswa</th>
                            <th>Total Kelas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>${baris}</tbody>
                `;
            }

            const printWindow = window.open('', '', 'width=950,height=650');
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
        })
        .catch(err => {
            alert('Gagal memuat data laporan: ' + err.message);
        });
}

document.addEventListener('DOMContentLoaded', function () {
    const arrowJadwal = document.querySelector('a[href="#sectionJadwalHariIni"]');
    if (arrowJadwal) {
        arrowJadwal.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.getElementById('sectionJadwalHariIni');
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                target.classList.remove('highlight-smooth');
                void target.offsetWidth;
                target.classList.add('highlight-smooth');
                setTimeout(() => target.classList.remove('highlight-smooth'), 1600);
            }
        });
    }
});
</script>
@endsection
