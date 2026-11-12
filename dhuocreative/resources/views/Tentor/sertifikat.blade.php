@extends('layouts.Tentor')

@section('title', 'Sertifikat Siswa')

@section('content')
<div class="ts-page">
    <div class="ts-header">
        <div>
            <h1 class="ts-title">Sertifikat Siswa</h1>
        </div>
        <button type="button" class="ts-btn ts-btn-primary" id="tsOpenUpload">
            <span class="ts-plus">+</span> Upload Sertifikat
        </button>
    </div>

    @if (session('sukses'))
        <p class="ts-flash ts-flash-sukses" role="status"
            style="padding:12px 16px;border-radius:12px;margin:0 0 16px;background:#e6f4f1;color:#14524b">
            {{ session('sukses') }}
        </p>
    @endif
    @if ($errors->any())
        <div class="ts-flash ts-flash-error" role="alert"
             style="padding:12px 16px;border-radius:12px;margin:0 0 16px;background:#fdeceb;color:#8a1f17">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="ts-toolbar">
        <label class="ts-search" for="tsSearch">
            <input id="tsSearch" type="search" placeholder="Cari nama siswa atau ujian..." aria-label="Cari sertifikat">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="7.3"></circle><path d="m16.2 16.2 5 5"></path></svg>
        </label>
        <select class="ts-filter" id="tsYearFilter" aria-label="Filter tahun ujian">
            <option value="">Semua tahun</option>
        </select>
    </div>

    <div class="ts-table-wrap">
        <table class="ts-table" id="tsTable">
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Nama Projek</th>
                    <th>Tanggal Pengumpulan</th>
                    <th>Tanggal Upload</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>    
    <p class="ts-empty-message" id="tsEmptyMessage" hidden>Sertifikat tidak ditemukan.</p>
</div>

<nav class="tj-pagination" id="paginasiSertifikat" aria-label="Halaman daftar sertifikat" hidden></nav>

{{-- Modal Upload --}}
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
        <form id="tsUploadForm" method="POST" action="{{ route('tentor.sertifikat.simpan') }}" enctype="multipart/form-data">
            @csrf
            <div class="ts-field">
                <label for="tsStudent">Nama Siswa</label>
                <select class="ts-nameUp" id="tsStudent" name="id_siswa" required>
                    <option value="">Pilih siswa</option>
                    @forelse ($siswa as $s)
                        <option value="{{ $s->id_user }}">{{ $s->nama }}</option>
                    @empty
                        <option value="" disabled>Belum ada siswa yang mengumpulkan tugas besar</option>
                    @endforelse
                </select>
                <small style="display:block;margin-top:6px;color:#5f7f7b;font-size:12px">
                    Hanya siswa yang sudah mengumpulkan <strong>tugas besar (projek)</strong> yang bisa dipilih.
                </small>
            </div>
            <div class="ts-field">
                <label for="tsExam">Nama Project</label>
                <input id="tsExam" name="nama_ujian" type="text" maxlength="150" required placeholder="Contoh: Ujian Web Master">
            </div>
            <div class="ts-field">
                <label for="tsExamDate">Tanggal Pengumpulan</label>
                <input id="tsExamDate" name="tanggal_ujian" type="date" required>
            </div>
            <div class="ts-field">
                <label for="tsFile">File Sertifikat</label>
                <label class="ts-dropzone">
                    <span class="ts-file-instruction">
                        <input id="tsFile" name="file_sertifikat" type="file" accept=".pdf,.jpg,.jpeg,.png" required>
                        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#5f7f7b">
                            <path d="M260-160q-91 0-155.5-63T40-377q0-78 47-139t123-78q25-92 100-149t170-57q117 0 198.5 81.5T760-520q69 8 114.5 59.5T920-340q0 75-52.5 127.5T740-160H520q-33 0-56.5-23.5T440-240v-206l-64 62-56-56 160-160 160 160-56 56-64-62v206h220q42 0 71-29t29-71q0-42-29-71t-71-29h-60v-80q0-83-58.5-141.5T480-720q-83 0-141.5 58.5T280-520h-20q-58 0-99 41t-41 99q0 58 41 99t99 41h100v80H260Zm220-280Z"/>
                        </svg>
                        <small class="ts-selected-file" id="tsFileName">Belum ada file dipilih</small>
                    </span>
                </label>
                <small>Format yang didukung: PDF, JPG, JPEG, atau PNG.</small>
            </div>
            <button class="ts-btn ts-btn-primary ts-btn-full" type="submit">Upload Sertifikat</button>
        </form>
    </section>
</div>

{{-- Modal Detail Sertifikat --}}
<div class="ts-overlay" id="tsDetailModal" hidden>
    <section class="ts-modal ts-detail-modal" role="dialog" aria-modal="true" aria-labelledby="tsDetailTitle">
        <div class="ts-modal-header">
            <button type="button" class="t-modal-close" data-ts-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2 id="tsDetailTitle">Detail Sertifikat</h2>
        </div>

        <dl class="ts-detail-list">
            <div>
                <dt>Nama Siswa</dt>
                <dd id="tsDetailStudent">-</dd>
            </div>
            <div>
                <dt>Nama Projek</dt>
                <dd id="tsDetailExam">-</dd>
            </div>
            <div>
                <dt>Tanggal Pengumpulan</dt>
                <dd id="tsDetailExamDate">-</dd>
            </div>
            <div>
                <dt>Tanggal Upload</dt>
                <dd id="tsDetailUploadDate">-</dd>
            </div>
        </dl>

        <div class="ts-preview-section">
            <h3>Preview Sertifikat</h3>
            <div id="tsCertificatePreview" class="ts-certificate-preview">
                <p>Preview sertifikat akan ditampilkan di sini.</p>
            </div>
        </div>

        <div class="ts-detail-actions">
            <button type="button" class="ts-btn ts-btn-primary ts-btn-full" id="tsEditButton">Edit Sertifikat</button>
        </div>

        <a class="ts-btn ts-btn-primary ts-btn-full" id="tsDetailFile" href="#" download hidden>
            Download Sertifikat
        </a>
    </section>
</div>

{{-- Modal Edit Sertifikat --}}
<div class="ts-overlay" id="tsEditModal" hidden>
    <section class="ts-modal" role="dialog" aria-modal="true" aria-labelledby="tsEditTitle">
        <div class="ts-modal-header">
            <button type="button" class="t-modal-close" data-ts-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2 id="tsEditTitle">Edit Sertifikat</h2>
        </div>
        <form id="tsEditForm" method="POST" action="#" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="ts-field">
                <label for="tsEditStudent">Nama Siswa</label>
                <select class="ts-nameUp" id="tsEditStudent" name="id_siswa" required disabled>
                    <option value="">Pilih siswa</option>
                     @foreach ($siswa as $s)
                        <option value="{{ $s->id_user }}">{{ $s->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="ts-field">
                <label for="tsEditExam">Nama Project</label>
                <input id="tsEditExam" name="nama_ujian" type="text" maxlength="150" required placeholder="Contoh: Ujian Web Master">
            </div>
            <div class="ts-field">
                <label for="tsEditDate">Tanggal Pengumpulan</label>
                <input id="tsEditDate" name="tanggal_ujian" type="date" required>
            </div>
            <div class="ts-field">
                <label for="tsEditFile">File Sertifikat (Opsional)</label>
                <label class="ts-dropzone">
                    <span class="ts-file-instruction">
                        <input id="tsEditFile" name="file_sertifikat" type="file" accept=".pdf,.jpg,.jpeg,.png">
                        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#5f7f7b">
                            <path d="M260-160q-91 0-155.5-63T40-377q0-78 47-139t123-78q25-92 100-149t170-57q117 0 198.5 81.5T760-520q69 8 114.5 59.5T920-340q0 75-52.5 127.5T740-160H520q-33 0-56.5-23.5T440-240v-206l-64 62-56-56 160-160 160 160-56 56-64-62v206h220q42 0 71-29t29-71q0-42-29-71t-71-29h-60v-80q0-83-58.5-141.5T480-720q-83 0-141.5 58.5T280-520h-20q-58 0-99 41t-41 99q0 58 41 99t99 41h100v80H260Zm220-280Z"/>
                        </svg>
                        <small class="ts-selected-file" id="tsEditFileName">Pilih file baru jika ingin mengganti</small>
                    </span>
                </label>
                <small>Format yang didukung: PDF, JPG, JPEG, atau PNG.</small>
            </div>
            <button class="ts-btn ts-btn-primary ts-btn-full" type="submit">Simpan Perubahan</button>
        </form>
    </section>
</div>

<script>
const PER_HALAMAN = 10;
let halSertifikat = 1;

function renderPaginasi(navId, total, halaman, pindah) {
    const nav = document.getElementById(navId);
    if (!nav) return;
    nav.replaceChildren();
    if (!total) { nav.hidden = true; return; }
    nav.hidden = false;
 
    const jumlah = Math.ceil(total / PER_HALAMAN);
    const awal = (halaman - 1) * PER_HALAMAN + 1;
    const akhir = Math.min(halaman * PER_HALAMAN, total);
 
    const info = document.createElement('span');
    info.className = 'tj-pg-info';
    info.textContent = `Menampilkan ${awal}–${akhir} dari ${total}`;
    nav.appendChild(info);
    if (jumlah <= 1) return;
 
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

(() => {
    const byId = (id) => document.getElementById(id);
 
    const search = byId('tsSearch');
    const yearFilter = byId('tsYearFilter');
    const table = byId('tsTable');
    const tbody = table.querySelector('tbody');
    const emptyMessage = byId('tsEmptyMessage');
    const uploadModal = byId('tsUploadModal');
    const detailModal = byId('tsDetailModal');
    const editModal = byId('tsEditModal');
    const form = byId('tsUploadForm');
    const editForm = byId('tsEditForm');
    const overlays = [uploadModal, detailModal, editModal];
 
    const updateUrlTemplate = "{{ route('tentor.sertifikat.update', ['sertifikat' => '__ID__']) }}";
    const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
    const maxSize = 5 * 1024 * 1024;
 
    const certificates = @json($sertifikat);
    const rowElements = [];

    function addYearOption(year) {
        if (!year) return;
        const exists = [...yearFilter.options].some(option => option.value === year);
        if (!exists) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearFilter.appendChild(option);
        }
    }
 
    // Buat elemen baris dari data sertifikat sekali saja di awal
    certificates.forEach(data => {
        const row = document.createElement('tr');
        row.dataset.year = data.year;
        row._certificateData = data;
 
        [data.student, data.exam, data.examDate, data.uploadDate].forEach(value => {
            const cell = document.createElement('td');
            cell.textContent = value;
            row.appendChild(cell);
        });
 
        const actionCell = document.createElement('td');
        const detailButton = document.createElement('button');
        detailButton.type = 'button';
        detailButton.className = 'ts-action';
        detailButton.textContent = 'Detail';
 
        detailButton.addEventListener('click', () => {
            byId('tsDetailStudent').textContent = data.student;
            byId('tsDetailExam').textContent = data.exam;
            byId('tsDetailExamDate').textContent = data.examDate;
            byId('tsDetailUploadDate').textContent = data.uploadDate;
 
            const preview = byId('tsCertificatePreview');
            const downloadLink = byId('tsDetailFile');
 
            preview.replaceChildren();
            downloadLink.hidden = true;
            downloadLink.removeAttribute('href');
            downloadLink.removeAttribute('download');
 
            if (data.fileUrl && data.fileType === 'application/pdf') {
                const pdf = document.createElement('iframe');
                pdf.src = data.fileUrl;
                pdf.title = 'Preview PDF Sertifikat';
                pdf.style.width = '100%';
                pdf.style.height = '480px';
                pdf.style.border = '0';
                preview.appendChild(pdf);
            } else if (data.fileUrl && ['image/jpeg', 'image/png'].includes(data.fileType)) {
                const img = document.createElement('img');
                img.src = data.fileUrl;
                img.alt = 'Preview Sertifikat';
                img.style.width = '100%';
                img.style.maxHeight = '480px';
                img.style.objectFit = 'contain';
                preview.appendChild(img);
            } else {
                const msg = document.createElement('p');
                msg.textContent = 'Preview tidak tersedia untuk file ini.';
                preview.appendChild(msg);
            }
 
            if (data.fileUrl) {
                downloadLink.href = data.fileUrl;
                downloadLink.download = data.fileName || 'sertifikat';
                downloadLink.hidden = false;
            }
 
            detailModal._activeRow = row;
            detailModal.hidden = false;
        });
 
        actionCell.appendChild(detailButton);
        row.appendChild(actionCell);
        rowElements.push(row);
        addYearOption(data.year);
    });
 
    function filterRows(resetPage = true) {
        if (resetPage) halSertifikat = 1;

        const query = search.value.toLowerCase().trim();
        const year = yearFilter.value;
        
        // Filter baris berdasarkan pencarian dan tahun
        const visibleRows = rowElements.filter(row => {
            const matches = row.textContent.toLowerCase().includes(query) &&
                (!year || row.dataset.year === year);
            return matches;
        });
 
        tbody.replaceChildren();

        const maxPage = Math.ceil(visibleRows.length / PER_HALAMAN) || 1;
        if (halSertifikat > maxPage) halSertifikat = maxPage;

        const start = (halSertifikat - 1) * PER_HALAMAN;
        const end = start + PER_HALAMAN;
        const paginatedRows = visibleRows.slice(start, end);

        paginatedRows.forEach(row => tbody.appendChild(row));
 
        emptyMessage.hidden = visibleRows.length > 0;
        emptyMessage.textContent = visibleRows.length
            ? ''
            : (certificates.length ? 'Sertifikat tidak ditemukan.' : 'Belum ada sertifikat yang diupload.');
 
        renderPaginasi('paginasiSertifikat', visibleRows.length, halSertifikat, h => { 
            halSertifikat = h; 
            filterRows(false); 
        });
    }
 
    search.addEventListener('input', () => filterRows(true));
    yearFilter.addEventListener('change', () => filterRows(true));
 
    byId('tsOpenUpload').addEventListener('click', () => {
        form.reset();
        byId('tsFileName').textContent = 'Belum ada file dipilih';
        uploadModal.hidden = false;
    });
 
    byId('tsFile').addEventListener('change', () => {
        byId('tsFileName').textContent = byId('tsFile').files[0]?.name || 'Belum ada file dipilih';
    });
 
    byId('tsEditFile').addEventListener('change', () => {
        byId('tsEditFileName').textContent = byId('tsEditFile').files[0]?.name || 'Pilih file baru jika ingin mengganti';
    });
 
    byId('tsEditButton').addEventListener('click', () => {
        const row = detailModal._activeRow;
        if (!row) return;
        const data = row._certificateData;
 
        editForm.action = updateUrlTemplate.replace('__ID__', data.id);
        byId('tsEditStudent').value = data.studentId || '';
        byId('tsEditExam').value = data.exam;
        byId('tsEditDate').value = data.dateISO || '';
        byId('tsEditFile').value = '';
        byId('tsEditFileName').textContent = data.fileName ? `File saat ini: ${data.fileName}` : 'Pilih file baru jika ingin mengganti';
 
        detailModal.hidden = true;
        editModal.hidden = false;
    });
 
    function cekFile(file) {
        if (!allowedTypes.includes(file.type)) {
            alert('Format file harus PDF, JPG, JPEG, atau PNG.');
            return false;
        }
        if (file.size > maxSize) {
            alert('Ukuran file maksimal 5 MB.');
            return false;
        }
        return true;
    }
 
    editForm.addEventListener('submit', event => {
        const exam = byId('tsEditExam').value.trim();
        const dateISO = byId('tsEditDate').value;
        const replacement = byId('tsEditFile').files[0];
 
        if (!exam || !dateISO) {
            event.preventDefault();
            alert('Nama projek dan tanggal pengumpulan wajib diisi.');
            return;
        }
        if (replacement && !cekFile(replacement)) {
            event.preventDefault();
            return;
        }
        editForm.querySelector('[type=submit]').disabled = true;
    });
 
    form.addEventListener('submit', event => {
        const studentId = byId('tsStudent').value;
        const exam = byId('tsExam').value.trim();
        const examDate = byId('tsExamDate').value;
        const file = byId('tsFile').files[0];
 
        if (!studentId || !exam || !examDate || !file) {
            event.preventDefault();
            alert('Lengkapi semua data terlebih dahulu.');
            return;
        }
        if (!cekFile(file)) {
            event.preventDefault();
            return;
        }
        form.querySelector('[type=submit]').disabled = true;
    });
 
    document.querySelectorAll('[data-ts-close]').forEach(button => {
        button.addEventListener('click', () => {
            button.closest('.ts-overlay').hidden = true;
        });
    });
 
    overlays.forEach(overlay => {
        overlay.addEventListener('click', event => {
            if (event.target === overlay) overlay.hidden = true;
        });
    });
 
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            overlays.forEach(overlay => { overlay.hidden = true; });
        }
    });
 
    filterRows(true);
})();
</script>
@endsection