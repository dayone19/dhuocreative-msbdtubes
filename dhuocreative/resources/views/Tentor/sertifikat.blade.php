@extends('layouts.Tentor')
@section('title', 'Sertifikat Siswa')
@section('content')
<div class="ts-page">
    <div class="ts-header">
        <div>
            <h1 class="ts-title">Sertifikat Siswa</h1>
            <p class="ts-subtitle">Daftar sertifikat ujian yang telah diunggah.</p>
        </div>
        <button type="button" class="ts-btn ts-btn-primary" id="tsOpenUpload">
            <span class="ts-plus">+</span> Upload Sertifikat
        </button>
    </div>

    <div class="ts-toolbar">
        <label class="ts-search" for="tsSearch">
            <input id="tsSearch" type="search" placeholder="Cari nama siswa atau ujian..." aria-label="Cari sertifikat">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="7.3"></circle><path d="m16.2 16.2 5 5"></path></svg>
        </label>
        <select class="ts-filter" id="tsYearFilter" aria-label="Filter tahun ujian">
            <option value="">Semua tahun</option>
            @foreach(($tahunUjian ?? collect()) as $tahun)
                <option value="{{ $tahun }}">{{ $tahun }}</option>
            @endforeach
        </select>
    </div>

    <div class="ts-table-wrap">
        <table class="ts-table" id="tsTable">
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Nama Ujian</th>
                    <th>Tanggal Ujian</th>
                    <th>Tanggal Upload</th>
                    <th>File Sertifikat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($sertifikats ?? collect()) as $sertifikat)
                    <tr data-year="{{ \Illuminate\Support\Carbon::parse($sertifikat->tanggal_ujian)->format('Y') }}">
                        <td>{{ $sertifikat->nama_siswa ?? 'Nama siswa' }}</td>
                        <td>{{ $sertifikat->nama_ujian }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($sertifikat->tanggal_ujian)->format('d/m/Y') }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($sertifikat->tanggal_upload)->format('d/m/Y H:i') }}</td>
                        <td>
                            <a class="ts-file-link" href="{{ asset('storage/' . $sertifikat->file_sertifikat) }}" target="_blank" rel="noopener">
                                Lihat File
                            </a>
                        </td>
                        <td>
                            <button type="button" class="ts-action" data-ts-detail
                                data-student="{{ $sertifikat->nama_siswa ?? 'Nama siswa' }}"
                                data-exam="{{ $sertifikat->nama_ujian }}"
                                data-exam-date="{{ \Illuminate\Support\Carbon::parse($sertifikat->tanggal_ujian)->format('d/m/Y') }}"
                                data-upload-date="{{ \Illuminate\Support\Carbon::parse($sertifikat->tanggal_upload)->format('d/m/Y H:i') }}"
                                data-file="{{ asset('storage/' . $sertifikat->file_sertifikat) }}">
                                Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="ts-empty-cell">Belum ada sertifikat yang diunggah.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <p class="ts-empty-message" id="tsEmptyMessage" hidden>Sertifikat tidak ditemukan.</p>
</div>

{{-- Modal upload --}}
<div class="ts-overlay" id="tsUploadModal" hidden>
    <section class="ts-modal" role="dialog" aria-modal="true" aria-labelledby="tsUploadTitle">
        <div class="ts-modal-header">
            <button type="button" class="t-modal-close" data-ts-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2 id="tsUploadTitle">Upload Sertifikat</h2>
        </div>
        {{-- Sesuaikan action dengan route upload sertifikat di aplikasi --}}
        <form method="POST" action="{{ $uploadUrl ?? '#' }}" enctype="multipart/form-data" id="tsUploadForm">
            @csrf
            <div class="ts-field">
                <label for="tsStudent">Nama Siswa</label>
                <select id="tsStudent" name="id_siswa" required>
                    <option value="">Pilih siswa</option>
                    @foreach(($siswa ?? collect()) as $item)
                        <option value="{{ $item->id_siswa }}">{{ $item->nama_siswa }}</option>
                    @endforeach
                </select>
            </div>
            <div class="ts-field">
                <label for="tsExam">Nama Ujian</label>
                <input id="tsExam" name="nama_ujian" type="text" maxlength="150" required placeholder="Contoh: Ujian Web Master">
            </div>
            <div class="ts-field">
                <label for="tsExamDate">Tanggal Ujian</label>
                <input id="tsExamDate" name="tanggal_ujian" type="date" required>
            </div>
            <div class="ts-field">
                <label for="tsFile">File Sertifikat</label>
                <input id="tsFile" name="file_sertifikat" type="file" accept=".pdf,.jpg,.jpeg,.png" required>
                <small>Format yang didukung: PDF, JPG, JPEG, atau PNG.</small>
            </div>
            <button class="ts-btn ts-btn-primary ts-btn-full" type="submit">Simpan Sertifikat</button>
        </form>
    </section>
</div>

{{-- Modal detail --}}
<div class="ts-overlay" id="tsDetailModal" hidden>
    <section class="ts-modal" role="dialog" aria-modal="true" aria-labelledby="tsDetailTitle">
        <div class="ts-modal-header">
            <button type="button" class="ts-back" data-ts-close aria-label="Tutup">‹</button>
            <h2 id="tsDetailTitle">Detail Sertifikat</h2>
        </div>
        <dl class="ts-detail-list">
            <div><dt>Nama Siswa</dt><dd id="tsDetailStudent">-</dd></div>
            <div><dt>Nama Ujian</dt><dd id="tsDetailExam">-</dd></div>
            <div><dt>Tanggal Ujian</dt><dd id="tsDetailExamDate">-</dd></div>
            <div><dt>Tanggal Upload</dt><dd id="tsDetailUploadDate">-</dd></div>
        </dl>
        <a class="ts-btn ts-btn-primary ts-btn-full" id="tsDetailFile" href="#" target="_blank" rel="noopener">Buka Sertifikat</a>
    </section>
</div>

<script>
(() => {
    const byId = (id) => document.getElementById(id);
    const search = byId('tsSearch');
    const yearFilter = byId('tsYearFilter');
    const table = byId('tsTable');
    const emptyMessage = byId('tsEmptyMessage');
    const uploadModal = byId('tsUploadModal');
    const detailModal = byId('tsDetailModal');
    const overlays = [uploadModal, detailModal];

    function filterRows() {
        const query = search.value.toLowerCase().trim();
        const year = yearFilter.value;
        let visible = 0;
        table.querySelectorAll('tbody tr[data-year]').forEach((row) => {
            const matches = row.textContent.toLowerCase().includes(query) && (!year || row.dataset.year === year);
            row.hidden = !matches;
            if (matches) visible++;
        });
        emptyMessage.hidden = visible > 0;
    }

    search.addEventListener('input', filterRows);
    yearFilter.addEventListener('change', filterRows);
    byId('tsOpenUpload').addEventListener('click', () => { uploadModal.hidden = false; });

    table.addEventListener('click', (event) => {
        const button = event.target.closest('[data-ts-detail]');
        if (!button) return;
        byId('tsDetailStudent').textContent = button.dataset.student;
        byId('tsDetailExam').textContent = button.dataset.exam;
        byId('tsDetailExamDate').textContent = button.dataset.examDate;
        byId('tsDetailUploadDate').textContent = button.dataset.uploadDate;
        byId('tsDetailFile').href = button.dataset.file;
        detailModal.hidden = false;
    });

    document.querySelectorAll('[data-ts-close]').forEach((button) => {
        button.addEventListener('click', () => { button.closest('.ts-overlay').hidden = true; });
    });
    overlays.forEach((overlay) => overlay.addEventListener('click', (event) => {
        if (event.target === overlay) overlay.hidden = true;
    }));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') overlays.forEach((overlay) => { overlay.hidden = true; });
    });
    filterRows();
})();
</script>
@endsection
