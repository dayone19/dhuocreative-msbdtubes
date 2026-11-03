@extends('layouts.Tentor')

@section('title', 'Absensi Siswa')

@section('content')
@php
    $siswa = [
        'id' => 1208,
        'nama' => 'Siswa A',
        'kursus' => 'Web Master',
        'kelas' => 'Online',
        'mulai_kontrak' => '2026-10-05',
        'akhir_kontrak' => '2026-12-05',
    ];

    $daftarPertemuan = [
        ['ke' => 1,  'tanggal' => '2026-10-05', 'hari' => 'Senin', 'jam' => '16.00–17.30', 'status' => 'hadir', 'jadwal' => 'Jadwal tetap'],
        ['ke' => 2,  'tanggal' => '2026-10-07', 'hari' => 'Rabu',  'jam' => '16.00–17.30', 'status' => 'hadir', 'jadwal' => 'Jadwal tetap'],
        ['ke' => 3,  'tanggal' => '2026-10-09', 'hari' => 'Jumat', 'jam' => '16.00–17.30', 'status' => 'hadir', 'jadwal' => 'Jadwal tetap'],
        ['ke' => 4,  'tanggal' => '2026-10-12', 'hari' => 'Senin', 'jam' => '16.00–17.30', 'status' => 'hadir', 'jadwal' => 'Jadwal tetap'],
        ['ke' => 5,  'tanggal' => '2026-10-14', 'hari' => 'Rabu',  'jam' => '16.00–17.30', 'status' => 'hadir', 'jadwal' => 'Jadwal ganti'],
    ];
@endphp

<div class="tentor-absensi-page ta-page">
    <div class="absensi-heading ta-heading">
        <a href="{{ route('tentor.kursus') }}" class="tmt-back-btn" aria-label="Kembali ke daftar kursus">
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
            <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
            </svg>
        </a>
        <div class="ta-heading-info">
            <h1>Absensi Siswa</h1>
        </div>
    </div>

    <section class="ta-profile-card" aria-label="Informasi kursus">
        <div class="ta-profile-main">
            <div>
                <span class="ta-muted">ID {{ $siswa['id'] }}</span>
                <h2>{{ $siswa['nama'] }}</h2>
                <p>{{ $siswa['kursus'] }} <span>•</span> {{ $siswa['kelas'] }}</p>
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
            <strong>12 <small>kali</small></strong>
            <div class="ta-summary-track"><span style="width:100%"></span></div>
        </article>
        <article class="ta-summary-card">
            <span>Hadir tercatat</span>
            <strong id="taTotalHadir">12 <small>/ 12</small></strong>
            <div class="ta-summary-track"><span id="taProgressHadir" style="width:100%"></span></div>
        </article>
        <article class="ta-summary-card">
            <span>Sisa pertemuan</span>
            <strong id="taSisaPertemuan">0 <small>kali</small></strong>
            <div class="ta-summary-track"><span id="taProgressSisa" style="width:0%"></span></div>
        </article>
    </section>

    <div class="ta-section-heading">
        <div>
            <h2>Riwayat Pertemuan</h2>
        </div>
        <div class="ta-filter-wrap">
            <select id="taFilterStatus" class="select-pertemuan ta-filter">
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
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="taAttendanceBody">
                @foreach ($daftarPertemuan as $pertemuan)
                    <tr data-meeting="{{ $pertemuan['ke'] }}"
                        data-date="{{ $pertemuan['tanggal'] }}"
                        data-status="{{ $pertemuan['status'] }}">
                        <td><span class="ta-meeting-number">{{ str_pad($pertemuan['ke'], 2, '0', STR_PAD_LEFT) }}</span></td>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($pertemuan['tanggal'])->translatedFormat('d M Y') }}</strong>
                            <small class="ta-cell-sub">{{ $pertemuan['hari'] }}</small>
                        </td>
                        <td>{{ $pertemuan['jam'] }}</td>
                        <td><span class="ta-schedule-pill">{{ $pertemuan['jadwal'] }}</span></td>
                        <td><span class="ta-status-pill ta-status-{{ $pertemuan['status'] }}" data-status-label>{{ ucfirst($pertemuan['status']) }}</span></td>
                        <td>
                            <button type="button" class="ta-edit-status" data-edit-meeting="{{ $pertemuan['ke'] }}">
                                Ubah status
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <p id="pesanAbsensi" class="pesan-absensi" role="status" aria-live="polite" hidden></p>
</div>

<div class="ta-modal-overlay" id="taStatusModal" hidden>
    <section class="ta-modal" role="dialog" aria-modal="true" aria-labelledby="taModalTitle" aria-describedby="taModalDate">
        <div class="ta-modal-header">
            <button type="button" class="tmt-back-btn" id="taCloseModal" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <div>
                <h2 id="taModalTitle">Pertemuan <span id="taModalMeetingNumber">1</span></h2>
                <p class="ta-modal-date" id="taModalDate"></p>
            </div>
        </div>
        <fieldset class="ta-status-options">
            <legend>Pilih status</legend>
            <label><input type="radio" name="taStatus" value="hadir"><span>Hadir</span></label>
            <label><input type="radio" name="taStatus" value="sakit"><span>Sakit</span></label>
            <label><input type="radio" name="taStatus" value="izin"><span>Izin</span></label>
            <label><input type="radio" name="taStatus" value="alpa"><span>Alpa</span></label>
        </fieldset>
        <div class="ta-modal-actions">
            <button type="button" class="ta-modal-cancel" id="taCancelModal">Batal</button>
            <button type="button" class="ta-modal-save" id="taSaveStatus">Simpan status</button>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const body = document.getElementById('taAttendanceBody');
    const filter = document.getElementById('taFilterStatus');
    const modal = document.getElementById('taStatusModal');
    const message = document.getElementById('pesanAbsensi');
    const storageKey = 'demo_absensi_siswa_1208_webmaster';
    let activeRow = null;

    function readSavedStatuses() {
        try { return JSON.parse(localStorage.getItem(storageKey) || '{}'); }
        catch (error) { return {}; }
    }

    function showMessage(text, type = 'sukses') {
        message.hidden = false;
        message.textContent = text;
        message.className = 'pesan-absensi ' + type;
    }

    function updateRow(row, status) {
        row.dataset.status = status;
        const pill = row.querySelector('[data-status-label]');
        pill.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        pill.className = 'ta-status-pill ta-status-' + status;
    }

    function updateSummary() {
        const rows = [...body.querySelectorAll('tr')];
        const hadir = rows.filter(row => row.dataset.status === 'hadir').length;
        const sisa = rows.filter(row => !['hadir', 'sakit', 'izin', 'alpa'].includes(row.dataset.status)).length;
        document.getElementById('taTotalHadir').innerHTML = `${hadir} <small>/ 12</small>`;
        document.getElementById('taSisaPertemuan').innerHTML = `${sisa} <small>kali</small>`;
        document.getElementById('taProgressHadir').style.width = `${(hadir / 12) * 100}%`;
        document.getElementById('taProgressSisa').style.width = `${(sisa / 12) * 100}%`;
    }

    function applyFilter() {
        body.querySelectorAll('tr').forEach(row => {
            row.hidden = filter.value !== 'semua' && row.dataset.status !== filter.value;
        });
    }

    function closeModal() {
        modal.hidden = true;
        document.body.style.overflow = '';
        activeRow = null;
    }

    // Pulihkan perubahan demo dari browser, bila ada.
    const saved = readSavedStatuses();
    body.querySelectorAll('tr').forEach(row => {
        const meeting = row.dataset.meeting;
        if (saved[meeting]) updateRow(row, saved[meeting]);
    });
    updateSummary();

    body.addEventListener('click', function (event) {
        const button = event.target.closest('[data-edit-meeting]');
        if (!button) return;

        activeRow = button.closest('tr');
        document.getElementById('taModalMeetingNumber').textContent =
            String(activeRow.dataset.meeting);
        document.getElementById('taModalDate').textContent =
            activeRow.querySelector('td:nth-child(2) strong').textContent;

        const currentStatus = activeRow.dataset.status;
        modal.querySelectorAll('input[name="taStatus"]').forEach(input => {
            input.checked = input.value === currentStatus;
        });
        modal.hidden = false;
        document.body.style.overflow = 'hidden';
        const firstChecked = modal.querySelector('input[name="taStatus"]:checked');
        if (firstChecked) firstChecked.focus();
    });

    document.getElementById('taSaveStatus').addEventListener('click', function () {
        if (!activeRow) return;
        const selected = modal.querySelector('input[name="taStatus"]:checked');
        if (!selected) {
            showMessage('Pilih status kehadiran terlebih dahulu.', 'error');
            return;
        }

        const meeting = activeRow.dataset.meeting;
        updateRow(activeRow, selected.value);
        const statuses = readSavedStatuses();
        statuses[meeting] = selected.value;
        localStorage.setItem(storageKey, JSON.stringify(statuses));

        updateSummary();
        applyFilter();
        closeModal();
        showMessage(`Status pertemuan ${meeting} berhasil diperbarui di browser.`);
    });

    document.getElementById('taCloseModal').addEventListener('click', closeModal);
    document.getElementById('taCancelModal').addEventListener('click', closeModal);
    modal.addEventListener('click', event => {
        if (event.target === modal) closeModal();
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && !modal.hidden) closeModal();
    });
    filter.addEventListener('change', applyFilter);
});
</script>
@endsection
