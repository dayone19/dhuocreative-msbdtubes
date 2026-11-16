@extends('layouts.Tentor')

@section('title', 'Absensi Siswa')

@section('content')

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
                <p>{{ $siswa['kursus'] }} · {{ $siswa['mode'] }}</p>
            </div>
        </div>
        @if ($siswa['mulai_kontrak'] && $siswa['akhir_kontrak'])
        <div class="ta-contract">
            <span class="ta-muted">Masa kelas</span>
            <strong>{{ \Carbon\Carbon::parse($siswa['mulai_kontrak'])->locale('id')->translatedFormat('d M Y') }}</strong>
            <span class="ta-contract-arrow">sampai</span>
            <strong>{{ \Carbon\Carbon::parse($siswa['akhir_kontrak'])->locale('id')->translatedFormat('d M Y') }}</strong>
        </div>
        @endif
    </section>

    <section class="ta-summary-grid" aria-label="Ringkasan absensi" data-total="{{ $total }}">
        <article class="ta-summary-card">
            <span>Total pertemuan</span>
            <strong>{{ $total }} <small>kali</small></strong>
            <div class="ta-summary-track"><span style="width:100%"></span></div>
        </article>
        <article class="ta-summary-card">
            <span>Hadir tercatat</span>
            <strong id="taTotalHadir">{{ $hadir }} <small>/ {{ $total }}</small></strong>
            <div class="ta-summary-track"><span id="taProgressHadir" style="width:{{ $total ? ($hadir / $total) * 100 : 0 }}%"></span></div>
        </article>
        <article class="ta-summary-card">
            <span>Belum diisi</span>
            <strong id="taSisaPertemuan">{{ $sisa }} <small>kali</small></strong>
            <div class="ta-summary-track"><span id="taProgressSisa" style="width:{{ $total ? ($sisa / $total) * 100 : 0 }}%"></span></div>
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
                <option value="belum">Belum diisi</option>
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
                @forelse ($daftarPertemuan as $pertemuan)
                    <tr data-meeting="{{ $pertemuan['ke'] }}"
                        data-jadwal="{{ $pertemuan['id_jadwal'] }}"
                        data-date="{{ $pertemuan['tanggal'] }}"
                        data-status="{{ $pertemuan['status'] }}">
                        <td><span class="ta-meeting-number">{{ str_pad($pertemuan['ke'], 2, '0', STR_PAD_LEFT) }}</span></td>
                        <td>
                            <strong>{{ $pertemuan['tanggal_fmt'] }}</strong>
                            <small class="ta-cell-sub">{{ $pertemuan['hari'] }}</small>
                        </td>
                        <td>{{ $pertemuan['jam'] }}</td>
                        <td><span class="ta-schedule-pill">{{ $pertemuan['status_jadwal'] }}</span></td>
                        <td>
                            <span class="ta-status-pill ta-status-{{ $pertemuan['status'] }}" data-status-label>
                                {{ $pertemuan['status'] === 'belum' ? 'Belum diisi' : ucfirst($pertemuan['status']) }}
                            </span>
                        </td>
                        <td>
                            @if ($pertemuan['bisa_diubah'])
                                <button type="button" class="ta-edit-status" data-edit-meeting="{{ $pertemuan['ke'] }}">
                                    {{ $pertemuan['status'] === 'belum' ? 'Isi status' : 'Ubah status' }}
                                </button>
                            @else
                                <span class="ta-muted">Belum berlangsung</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">Belum ada jadwal untuk kelas ini.</td></tr>
                @endforelse
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
    const total = Number(document.querySelector('.ta-summary-grid').dataset.total) || 0;
    const csrf = document.querySelector('meta[name="csrf-token"]').content; // pastikan ada di layouts.Tentor
    const urlTemplate = "{{ route('tentor.absensi.status', ['jadwal' => '__ID__']) }}";
    let activeRow = null;

    function showMessage(text, type = 'sukses') {
        message.hidden = false;
        message.textContent = text;
        message.className = 'pesan-absensi ' + type;
    }

    function updateRow(row, status) {
        row.dataset.status = status;
        const pill = row.querySelector('[data-status-label]');
        pill.textContent = status === 'belum' ? 'Belum diisi' : status.charAt(0).toUpperCase() + status.slice(1);
        pill.className = 'ta-status-pill ta-status-' + status;
        const btn = row.querySelector('[data-edit-meeting]');
        if (btn) btn.textContent = status === 'belum' ? 'Isi status' : 'Ubah status';
    }

    function updateSummary() {
        const rows = [...body.querySelectorAll('tr[data-status]')];
        const hadir = rows.filter(row => row.dataset.status === 'hadir').length;
        const sisa = rows.filter(row => row.dataset.status === 'belum').length;
        document.getElementById('taTotalHadir').innerHTML = `${hadir} <small>/ ${total}</small>`;
        document.getElementById('taSisaPertemuan').innerHTML = `${sisa} <small>kali</small>`;
        document.getElementById('taProgressHadir').style.width = `${total ? (hadir / total) * 100 : 0}%`;
        document.getElementById('taProgressSisa').style.width = `${total ? (sisa / total) * 100 : 0}%`;
    }

    function applyFilter() {
        body.querySelectorAll('tr[data-status]').forEach(row => {
            row.hidden = filter.value !== 'semua' && row.dataset.status !== filter.value;
        });
    }

    function closeModal() {
        modal.hidden = true;
        document.body.style.overflow = '';
        activeRow = null;
    }

    body.addEventListener('click', function (event) {
        const button = event.target.closest('[data-edit-meeting]');
        if (!button) return;

        activeRow = button.closest('tr');
        document.getElementById('taModalMeetingNumber').textContent = String(activeRow.dataset.meeting);
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

    document.getElementById('taSaveStatus').addEventListener('click', async function () {
        if (!activeRow) return;
        const selected = modal.querySelector('input[name="taStatus"]:checked');
        if (!selected) {
            showMessage('Pilih status kehadiran terlebih dahulu.', 'error');
            return;
        }

        const row = activeRow;
        const meeting = row.dataset.meeting;
        const saveBtn = this;
        saveBtn.disabled = true;

        try {
            const res = await fetch(urlTemplate.replace('__ID__', row.dataset.jadwal), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({ status: selected.value }),
            });
            const data = await res.json().catch(() => ({}));
            if (!res.ok) {
                const firstErr = data.errors ? Object.values(data.errors)[0][0] : null;
                throw new Error(firstErr || data.message || 'Gagal menyimpan status.');
            }

            updateRow(row, data.status);
            updateSummary();
            applyFilter();
            closeModal();
            showMessage(`Status pertemuan ${meeting} berhasil disimpan.`);
        } catch (err) {
            showMessage(err.message, 'error');
        } finally {
            saveBtn.disabled = false;
        }
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