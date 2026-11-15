
@extends('layouts.Admin')

@section('title', 'Absensi Siswa')

@section('content')

<div class="tentor-absensi-page ta-page">

    {{-- HEADER --}}
    <div class="absensi-heading ta-heading">
        <a href="{{ route('admin.riwayatAbsen') }}" class="tmt-back-btn" aria-label="Kembali ke riwayat absensi">
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
            </svg>
        </a>

        <div class="ta-heading-info">
            <h1>Absensi Siswa</h1>
        </div>
    </div>

    {{-- PROFIL SISWA --}}
    <section class="ta-profile-card" aria-label="Informasi kursus">
        <div class="ta-profile-main">
            <div>
                <span class="ta-muted">ID {{ $siswa['id'] }}</span>
                <h2>{{ $siswa['nama'] }}</h2>
                <p>
                    {{ $siswa['kursus'] }}
                    <span>•</span>
                    {{ $siswa['kelas'] }}
                </p>
            </div>
        </div>

        <div class="ta-contract">
            <span class="ta-muted">Masa kontrak</span>

            @if ($siswa['mulai_kontrak'] && $siswa['akhir_kontrak'])
                <strong>
                    {{ \Carbon\Carbon::parse($siswa['mulai_kontrak'])->locale('id')->translatedFormat('d M Y') }}
                </strong>

                <span class="ta-contract-arrow">sampai</span>

                <strong>
                    {{ \Carbon\Carbon::parse($siswa['akhir_kontrak'])->locale('id')->translatedFormat('d M Y') }}
                </strong>
            @else
                <strong>Belum tersedia</strong>
            @endif
        </div>
    </section>

    {{-- RINGKASAN ABSENSI --}}
    <section class="ta-summary-grid" aria-label="Ringkasan absensi">

        <article class="ta-summary-card">
            <span>Total pertemuan</span>

            <strong>
                {{ $totalPertemuan }}
                <small>kali</small>
            </strong>

            <div class="ta-summary-track">
                <span style="width: 100%"></span>
            </div>
        </article>

        <article class="ta-summary-card">
            <span>Hadir tercatat</span>

            <strong id="taTotalHadir">
                {{ $totalHadir }}
                <small>/ {{ $jumlahJadwal }}</small>
            </strong>

            <div class="ta-summary-track">
                <span
                    id="taProgressHadir"
                    style="width: {{ $persentaseHadir }}%"
                ></span>
            </div>
        </article>

        <article class="ta-summary-card">
            <span>Sisa pertemuan</span>

            <strong id="taSisaPertemuan">
                {{ $sisaPertemuan }}
                <small>kali</small>
            </strong>

            <div class="ta-summary-track">
                <span
                    id="taProgressSisa"
                    style="width: {{ $totalPertemuan > 0 ? ($sisaPertemuan / $totalPertemuan) * 100 : 0 }}%"
                ></span>
            </div>
        </article>

    </section>

    {{-- HEADING DAN FILTER --}}
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
                <option value="alpha">Alpha</option>
                <option value="belum absen">Belum absen</option>
            </select>
        </div>
    </div>

    {{-- TABEL RIWAYAT PERTEMUAN --}}
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

                @forelse ($daftarPertemuan as $pertemuan)
                    <tr
                        data-meeting="{{ $pertemuan['ke'] }}"
                        data-date="{{ $pertemuan['tanggal'] }}"
                        data-status="{{ $pertemuan['status'] }}"
                        data-jadwal-id="{{ $pertemuan['id_jadwal'] }}"
                    >
                        <td>
                            <span class="ta-meeting-number">
                                {{ str_pad($pertemuan['ke'], 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>

                        <td>
                            <strong>
                                {{ \Carbon\Carbon::parse($pertemuan['tanggal'])->locale('id')->translatedFormat('d M Y') }}
                            </strong>

                            <small class="ta-cell-sub">
                                {{ $pertemuan['hari'] }}
                            </small>
                        </td>

                        <td>
                            {{ $pertemuan['jam'] }}
                        </td>

                        <td>
                            <span class="ta-schedule-pill">
                                {{ $pertemuan['jadwal'] }}
                            </span>
                        </td>

                        <td>
                            <span
                                class="ta-status-pill ta-status-{{ str_replace(' ', '-', $pertemuan['status']) }}"
                                data-status-label
                            >
                                {{ ucfirst($pertemuan['status']) }}
                            </span>
                        </td>

                        <td>
                            <button
                                type="button"
                                class="ta-edit-status"
                                data-edit-meeting="{{ $pertemuan['ke'] }}"
                            >
                                Ubah status
                            </button>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6">
                            Belum ada riwayat pertemuan untuk siswa ini.
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    <p
        id="pesanAbsensi"
        class="pesan-absensi"
        role="status"
        aria-live="polite"
        hidden
    ></p>

</div>

{{-- MODAL UBAH STATUS --}}
<div class="ta-modal-overlay" id="taStatusModal" hidden>
    <section
        class="ta-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="taModalTitle"
        aria-describedby="taModalDate"
    >

        <div class="ta-modal-header">

            <button
                type="button"
                class="tmt-back-btn"
                id="taCloseModal"
                aria-label="Tutup"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>

            <div>
                <h2 id="taModalTitle">
                    Pertemuan <span id="taModalMeetingNumber">1</span>
                </h2>

                <p class="ta-modal-date" id="taModalDate"></p>
            </div>

        </div>

        
<form
    id="taStatusForm"
    method="POST"
    data-url-template="{{ route('admin.riwayatPresensi.updateStatus', [
        'id_kelas' => $siswa['id_kelas'],
        'id_siswa' => $siswa['id'],
        'id_jadwal' => 'JADWAL_ID'
    ]) }}"
>
    @csrf
        @method('PATCH')

        <fieldset class="ta-status-options">
            <legend>Pilih status</legend>

            <label>
                <input type="radio" name="status" value="hadir">
                <span>Hadir</span>
            </label>

            <label>
                <input type="radio" name="status" value="sakit">
                <span>Sakit</span>
            </label>

            <label>
                <input type="radio" name="status" value="izin">
                <span>Izin</span>
            </label>

            <label>
                <input type="radio" name="status" value="alpha">
                <span>Alpha</span>
            </label>
        </fieldset>

        <div class="ta-modal-actions">
            <button type="button" class="ta-modal-cancel" id="taCancelModal">
                Batal
            </button>

            <button type="submit" class="ta-modal-save" id="taSaveStatus">
                Simpan status
            </button>
        </div>
    </form>
    </section>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const body = document.getElementById('taAttendanceBody');
    const filter = document.getElementById('taFilterStatus');
    const modal = document.getElementById('taStatusModal');
    const message = document.getElementById('pesanAbsensi');
    const statusForm = document.getElementById('taStatusForm');

    let activeRow = null;

    const totalPertemuan = {{ $totalPertemuan }};
    const jumlahJadwal = {{ $jumlahJadwal }};

    function showMessage(text, type = 'sukses') {
        message.hidden = false;
        message.textContent = text;
        message.className = 'pesan-absensi ' + type;
    }

    function updateRow(row, status) {
        row.dataset.status = status;

        const pill = row.querySelector('[data-status-label]');

        pill.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        pill.className = 'ta-status-pill ta-status-' + status.replaceAll(' ', '-');
    }

    function updateSummary() {
        const rows = [...body.querySelectorAll('tr[data-meeting]')];

        const hadir = rows.filter(row => row.dataset.status === 'hadir').length;

        const sisa = Math.max(0, totalPertemuan - jumlahJadwal);

        const persentase = jumlahJadwal > 0
            ? (hadir / jumlahJadwal) * 100
            : 0;

        document.getElementById('taTotalHadir').innerHTML =
            `${hadir} <small>/ ${jumlahJadwal}</small>`;

        document.getElementById('taSisaPertemuan').innerHTML =
            `${sisa} <small>kali</small>`;

        document.getElementById('taProgressHadir').style.width =
            `${persentase}%`;

        document.getElementById('taProgressSisa').style.width =
            `${totalPertemuan > 0 ? (sisa / totalPertemuan) * 100 : 0}%`;
    }

    function applyFilter() {
        body.querySelectorAll('tr[data-meeting]').forEach(row => {
            row.hidden =
                filter.value !== 'semua' &&
                row.dataset.status !== filter.value;
        });
    }

    function closeModal() {
        modal.hidden = true;
        document.body.style.overflow = '';
        activeRow = null;
    }

    // FILTER STATUS
    filter.addEventListener('change', applyFilter);

    // BUKA MODAL
    body.addEventListener('click', function (event) {

        const button = event.target.closest('[data-edit-meeting]');

        if (!button) return;

        activeRow = button.closest('tr');

        document.getElementById('taModalMeetingNumber').textContent =
            activeRow.dataset.meeting;

        document.getElementById('taModalDate').textContent =
            activeRow.querySelector('td:nth-child(2) strong').textContent;

        const currentStatus = activeRow.dataset.status;

        modal.querySelectorAll('input[name="status"]').forEach(input => {
            input.checked = input.value === currentStatus;
        });

        const urlTemplate = statusForm.dataset.urlTemplate;

        statusForm.action = urlTemplate.replace(
            'JADWAL_ID',
            activeRow.dataset.jadwalId
        );

        modal.hidden = false;
        document.body.style.overflow = 'hidden';

        const firstChecked = modal.querySelector(
            'input[name="taStatus"]:checked'
        );

        if (firstChecked) firstChecked.focus();
    });

        // VALIDASI SEBELUM SIMPAN
    statusForm.addEventListener('submit', function (event) {
        const selected = statusForm.querySelector(
            'input[name="status"]:checked'
        );

        if (!selected) {
            event.preventDefault();
            showMessage('Pilih status kehadiran terlebih dahulu.', 'error');
        }
    });

    // SIMPAN STATUS SEMENTARA DI TAMPILAN
    document.getElementById('taSaveStatus').addEventListener('click', function () {

        if (!activeRow) return;

        const selected = modal.querySelector(
            'input[name="taStatus"]:checked'
        );

        if (!selected) {
            showMessage('Pilih status kehadiran terlebih dahulu.', 'error');
            return;
        }

        showMessage(
            'Perubahan status belum disimpan ke database. Fitur penyimpanan perlu dihubungkan ke controller.'
        );

        closeModal();
    });

    // TUTUP MODAL
    document.getElementById('taCloseModal').addEventListener('click', closeModal);

    document.getElementById('taCancelModal').addEventListener('click', closeModal);

    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !modal.hidden) {
            closeModal();
        }
    });

});
</script>

@endsection
