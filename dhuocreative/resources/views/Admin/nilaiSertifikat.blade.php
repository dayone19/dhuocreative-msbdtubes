
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
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="10.8" cy="10.8" r="7.3"></circle>
                <path d="m16.2 16.2 5 5"></path>
            </svg>
        </label>

        <select class="ts-filter" id="tsYearFilter" aria-label="Filter tahun ujian">
            <option value="">Semua tahun</option>
            @foreach ($daftarTahun as $tahun)
                <option value="{{ $tahun }}">{{ $tahun }}</option>
            @endforeach
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

            <tbody id="tsBody">
                @foreach ($daftarSertifikat as $item)
                    <tr
                        data-year="{{ $item->tahun }}"
                        data-student="{{ $item->nama_siswa }}"
                        data-exam="{{ $item->nama_projek }}"
                        data-exam-date="{{ $item->tanggal_pengumpulan_label }}"
                        data-upload-date="{{ $item->tanggal_upload_label }}"
                        data-file-name="{{ $item->file_name ?? '' }}"
                        data-file-url="{{ $item->file_url ?? '' }}"
                        data-file-type="{{ $item->file_type ?? '' }}"
                    >
                        <td>{{ $item->nama_siswa }}</td>
                        <td>{{ $item->nama_projek }}</td>
                        <td>{{ $item->tanggal_pengumpulan_label }}</td>
                        <td>{{ $item->tanggal_upload_label }}</td>
                        <td>
                            <button type="button" class="ts-action" onclick="lihatDetailSertifikat(this)">
                                Detail
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <p class="ts-empty-message" id="tsEmptyMessage" hidden>
        Sertifikat tidak ditemukan.
    </p>
</div>

<!-- PAGINASI -->
<nav class="tj-pagination"
     id="paginasiSertifikat"
     aria-label="Halaman daftar sertifikat"
     style="margin-top: 20px;"
     hidden>
</nav>

<!-- MODAL DETAIL SERTIFIKAT -->
<div class="ts-overlay" id="tsDetailModal" hidden>
    <section class="ts-modal ts-detail-modal"
             role="dialog"
             aria-modal="true"
             aria-labelledby="tsDetailTitle">

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
            <a class="ts-btn ts-btn-primary ts-btn-full"
               id="tsDetailFile"
               href="#"
               download
               hidden>
                Download Sertifikat
            </a>
        </div>
    </section>
</div>

<script>
const PER_HALAMAN_SERTIFIKAT = 10;
let halSertifikat = 1;

function renderPaginasiSertifikat(navId, total, halaman, pindah) {
    const nav = document.getElementById(navId);

    if (!nav) return;

    nav.replaceChildren();

    if (!total || total <= 1) {
        nav.hidden = true;
        return;
    }

    nav.hidden = false;

    const jumlah = Math.ceil(total / PER_HALAMAN_SERTIFIKAT);
    const awal = (halaman - 1) * PER_HALAMAN_SERTIFIKAT + 1;
    const akhir = Math.min(halaman * PER_HALAMAN_SERTIFIKAT, total);

    const info = document.createElement('span');
    info.className = 'tj-pg-info';
    info.textContent = `Menampilkan ${awal}–${akhir} dari ${total}`;
    nav.appendChild(info);

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

    grup.appendChild(btn('‹', halaman - 1, {
        mati: halaman === 1,
        aria: 'Halaman sebelumnya'
    }));

    let terakhir = 0;

    for (let i = 1; i <= jumlah; i++) {
        if (i === 1 || i === jumlah || Math.abs(i - halaman) <= 1) {
            if (i - terakhir > 1) {
                const t = document.createElement('span');
                t.className = 'tj-pg-dots';
                t.textContent = '…';
                grup.appendChild(t);
            }

            grup.appendChild(btn(String(i), i, {
                aktif: i === halaman,
                aria: `Halaman ${i}`
            }));

            terakhir = i;
        }
    }

    grup.appendChild(btn('›', halaman + 1, {
        mati: halaman === jumlah,
        aria: 'Halaman berikutnya'
    }));

    nav.appendChild(grup);
}

document.addEventListener('DOMContentLoaded', function () {
    const byId = id => document.getElementById(id);

    const search = byId('tsSearch');
    const yearFilter = byId('tsYearFilter');
    const tbody = byId('tsBody');
    const emptyMessage = byId('tsEmptyMessage');
    const detailModal = byId('tsDetailModal');

    const semuaBaris = () => [
        ...tbody.querySelectorAll('tr[data-year]')
    ];

    // MODAL DETAIL SERTIFIKAT
    window.lihatDetailSertifikat = function (button) {
        const row = button.closest('tr');

        const data = row.dataset;

        byId('tsDetailStudent').textContent = data.student || '-';
        byId('tsDetailExam').textContent = data.exam || '-';
        byId('tsDetailExamDate').textContent = data.examDate || '-';
        byId('tsDetailUploadDate').textContent = data.uploadDate || '-';

        const preview = byId('tsCertificatePreview');
        const downloadLink = byId('tsDetailFile');

        preview.replaceChildren();

        downloadLink.hidden = true;
        downloadLink.removeAttribute('href');
        downloadLink.removeAttribute('download');

        const fileExtension = data.fileName ? data.fileName.split('.').pop().toLowerCase() : '';
        if (data.fileUrl && data.fileType === 'application/pdf') {
            const pdf = document.createElement('iframe');

            pdf.src = data.fileUrl;
            pdf.title = 'Preview PDF Sertifikat';
            pdf.style.width = '100%';
            pdf.style.height = '480px';
            pdf.style.border = '0';

            preview.appendChild(pdf);

        } else if (
            data.fileUrl &&
            ['image/jpeg', 'image/png'].includes(data.fileType)
        ) {
            const img = document.createElement('img');

            img.src = data.fileUrl;
            img.alt = 'Preview Sertifikat';
            img.style.width = '100%';
            img.style.maxHeight = '480px';
            img.style.objectFit = 'contain';

            preview.appendChild(img);

        } else {
            const message = document.createElement('p');

            message.textContent = data.fileUrl
                ? 'Format file belum mendukung preview.'
                : 'File sertifikat belum tersedia untuk pratinjau.';

            preview.appendChild(message);
        }

        if (data.fileUrl) {
            downloadLink.href = data.fileUrl;
            downloadLink.download = data.fileName || 'sertifikat';
            downloadLink.hidden = false;
        }

        detailModal.hidden = false;
        document.body.style.overflow = 'hidden';
    };

    // FILTER DAN PENCARIAN
    function filterRows() {
        const query = search.value.toLowerCase().trim();
        const year = yearFilter.value;

        const allRows = semuaBaris();

        const visibleRows = allRows.filter(row => {
            const textMatch = row.textContent.toLowerCase().includes(query);
            const yearMatch = !year || row.dataset.year === year;

            return textMatch && yearMatch;
        });

        allRows.forEach(row => {
            row.style.display = 'none';
        });

        const totalFiltered = visibleRows.length;

        emptyMessage.hidden = totalFiltered > 0;

        if (totalFiltered === 0) {
            renderPaginasiSertifikat(
                'paginasiSertifikat',
                0,
                halSertifikat,
                pindahHalaman
            );

            return;
        }

        const maxPage = Math.ceil(
            totalFiltered / PER_HALAMAN_SERTIFIKAT
        );

        if (halSertifikat > maxPage) {
            halSertifikat = maxPage || 1;
        }

        const start = (halSertifikat - 1) * PER_HALAMAN_SERTIFIKAT;
        const end = start + PER_HALAMAN_SERTIFIKAT;

        visibleRows.slice(start, end).forEach(row => {
            row.style.display = '';
        });

        renderPaginasiSertifikat(
            'paginasiSertifikat',
            totalFiltered,
            halSertifikat,
            pindahHalaman
        );
    }

    function pindahHalaman(tujuan) {
        halSertifikat = tujuan;

        filterRows();

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    search.addEventListener('input', () => {
        halSertifikat = 1;
        filterRows();
    });

    yearFilter.addEventListener('change', () => {
        halSertifikat = 1;
        filterRows();
    });

    // TUTUP MODAL
    document.querySelectorAll('[data-ts-close]').forEach(button => {
        button.addEventListener('click', () => {
            detailModal.hidden = true;
            document.body.style.overflow = '';
        });
    });

    detailModal.addEventListener('click', event => {
        if (event.target === detailModal) {
            detailModal.hidden = true;
            document.body.style.overflow = '';
        }
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && !detailModal.hidden) {
            detailModal.hidden = true;
            document.body.style.overflow = '';
        }
    });

    filterRows();
});
</script>
@endsection
