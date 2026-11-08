@extends('layouts.Admin')
@section('title', 'Sertifikat Siswa')
@section('content')
<div class="ts-page">
    <div class="ts-header">
        <div>
            <h1 class="ts-title">Sertifikat Siswa</h1>
        </div>
    </div>

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

{{-- Modal Detail Sertifikat (Hanya Lihat & Download) --}}
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

        <div style="margin-top: 20px;">
            <a class="ts-btn ts-btn-primary ts-btn-full" id="tsDetailFile" href="#" download hidden>
                Download Sertifikat
            </a>
        </div>
    </section>
</div>

<script>
(() => {
    const byId = (id) => document.getElementById(id);

    const search = byId('tsSearch');
    const yearFilter = byId('tsYearFilter');
    const table = byId('tsTable');
    const tbody = table.querySelector('tbody');
    const emptyMessage = byId('tsEmptyMessage');
    const detailModal = byId('tsDetailModal');
    const overlays = [detailModal];

    const sampleCertificates = [
        {
            studentId: '1',
            student: 'Andi Pratama',
            exam: 'Ujian Web Master',
            examDate: '12/08/2026',
            dateISO: '2026-08-12',
            uploadDate: '13/08/2026 10:30',
            year: '2026',
            fileName: '',
            fileUrl: null,
            fileType: null
        },
        {
            studentId: '2',
            student: 'Siti Aulia',
            exam: 'Ujian UI/UX Design',
            examDate: '20/07/2026',
            dateISO: '2026-07-20',
            uploadDate: '21/07/2026 14:15',
            year: '2026',
            fileName: '',
            fileUrl: null,
            fileType: null
        },
        {
            studentId: '3',
            student: 'Budi Santoso',
            exam: 'Ujian Database',
            examDate: '15/12/2025',
            dateISO: '2025-12-15',
            uploadDate: '16/12/2025 09:00',
            year: '2025',
            fileName: '',
            fileUrl: null,
            fileType: null
        }
    ];

    function addYearOption(year) {
        const exists = [...yearFilter.options].some(option => option.value === year);
        if (!exists) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearFilter.appendChild(option);
        }
    }

    function addRow(data) {
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
                const message = document.createElement('p');
                message.textContent = 'File sertifikat belum tersedia untuk pratinjau.';
                preview.appendChild(message);
            }

            if (data.fileUrl) {
                downloadLink.href = data.fileUrl;
                downloadLink.download = data.fileName || 'sertifikat';
                downloadLink.hidden = false;
            }

            detailModal.hidden = false;
        });

        actionCell.appendChild(detailButton);
        row.appendChild(actionCell);
        tbody.appendChild(row);
        addYearOption(data.year);
    }

    sampleCertificates.forEach(addRow);

    function filterRows() {
        const query = search.value.toLowerCase().trim();
        const year = yearFilter.value;
        let visible = 0;

        table.querySelectorAll('tbody tr[data-year]').forEach(row => {
            const matches = row.textContent.toLowerCase().includes(query) &&
                (!year || row.dataset.year === year);
            row.hidden = !matches;
            if (matches) visible++;
        });

        emptyMessage.hidden = visible > 0;
        emptyMessage.textContent = visible ? '' : 'Sertifikat tidak ditemukan.';
    }

    search.addEventListener('input', filterRows);
    yearFilter.addEventListener('change', filterRows);

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

    filterRows();
})();
</script>
@endsection