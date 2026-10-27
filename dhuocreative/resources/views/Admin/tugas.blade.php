@extends('layouts.Admin')

@section('title', 'Daftar Tugas Siswa')

@section('content')
<div class="at-page">
    <!-- PAGE HEADER -->
    <div class="at-page-header">
        <div class="at-modal-header" style="margin-bottom: 0;">
            <h1 class="at-page-title" id="judulHalamanSiswa">Daftar Tugas</h1>
        </div>
    </div>

    <!-- TOOLBAR FILTER & CARI -->
    <div class="at-toolbar">
        <!-- 1. Search Utama (Judul Tugas) -->
        <div class="at-search at-search-main">
            <input type="search" id="cariTugas" placeholder="Cari judul tugas..." aria-label="Cari tugas">
        </div>

        <!-- 2. Dropdown Filter Siswa -->
        <div class="at-siswa-filter" id="siswaFilter">
            <button type="button" class="at-select at-siswa-trigger" id="siswaTrigger"
                    aria-haspopup="listbox" aria-expanded="false" aria-controls="siswaDropdown">
                <span id="siswaTerpilih">Semua Siswa</span>
            </button>

            <div class="at-siswa-dropdown" id="siswaDropdown" hidden>
                <div class="at-search at-siswa-searchbox">
                    <input type="search" id="cariSiswa" placeholder="Cari nama siswa..." aria-label="Cari nama siswa" autocomplete="off">
                </div>
                <div class="at-siswa-options" id="siswaOptions" role="listbox"></div>
                <p class="at-siswa-no-result" id="siswaNoResult" hidden>Siswa tidak ditemukan.</p>
            </div>
        </div>

        <!-- 3. Select Tentor (Menggantikan Status) -->
        <select class="at-select" id="filterTentor" aria-label="Filter tentor">
            <option value="">Semua Tentor</option>
            <option value="Tentor C">Tentor C</option>
            <option value="Tentor D">Tentor D</option>
        </select>

        <!-- 4. Select Kursus -->
        <select class="at-select" id="filterKursus" aria-label="Filter kursus">
            <option value="">Semua Kursus</option>
            <option value="Web Master">Web Master</option>
            <option value="MS Office">MS Office</option>
        </select>

    </div>

    <!-- TABEL TUGAS -->
    <div class="at-table-container">
        <table class="at-table" id="tabelTugas">
            <thead>
                <tr>
                    <th>Judul Tugas</th>
                    <th>Kursus</th>
                    <th>Siswa</th>
                    <th>Tentor</th>
                    <th>Deadline</th>
                    <th>Status Pengumpulan</th>
                    <th>Nilai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="bodyTugas">
                <tr data-kursus="Web Master"
                    data-siswa="Siswa A"  
                    data-tentor="Tentor C" 
                    data-status="sudah"
                    data-jenis="Tugas"
                    data-deskripsi="Membuat struktur tabel HTML lengkap dengan CSS styling sederhana."
                    data-file="Tugas1_SiswaA.html"
                    data-nilai="85"
                    data-status-pengumpulan="Tepat Waktu">
                    <td>Membuat Tabel HTML</td>
                    <td>Web Master</td>
                    <td>Siswa A</td>
                    <td>Tentor C</td>
                    <td>30 Sep 2026 · 23:59</td>
                    <td><span class="at-status kmpl">Terkumpul</span></td>
                    <td><span class="at-nilai-badge">85</span></td>
                    <td>
                        <button type="button" class="at-view-btn" onclick="lihatDetailTugas(this)">Detail</button>
                    </td>
                </tr>
                <tr data-kursus="Web Master" 
                    data-siswa="Siswa B" 
                    data-tentor="Tentor C" 
                    data-status="belum"
                    data-jenis="Projek"
                    data-deskripsi="Membuat halaman portofolio pribadi menggunakan HTML & CSS."
                    data-file="-"
                    data-nilai="-"
                    data-status-pengumpulan="Belum Mengumpulkan">
                    <td>Projek Portofolio</td>
                    <td>Web Master</td>
                    <td>Siswa B</td>
                    <td>Tentor C</td>
                    <td>10 Okt 2026 · 23:59</td>
                    <td><span class="at-status tdk">Tidak Kumpul</span></td>
                    <td><span class="at-nilai-empty">-</span></td>
                    <td>
                        <button type="button" class="at-view-btn" onclick="lihatDetailTugas(this)">Detail</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <p class="at-empty" id="kosongTugas" hidden>Tugas tidak ditemukan.</p>
</div>

<!-- MODAL DETAIL TUGAS & NILAI -->
<div class="at-modal-overlay" id="modalDetailTugas" hidden>
    <div class="at-modal-box at-modal-wide">
        <div class="at-modal-header">
            <button type="button" class="at-modal-close" data-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2>Detail Tugas & Nilai</h2>
        </div>

        <div class="at-detail-list">
            <div>
                <span>Judul Tugas</span>
                <strong id="detJudul">-</strong>
            </div>
            <div>
                <span>Jenis Tugas</span>
                <strong id="detJenis">-</strong>
            </div>
            <div>
                <span>Kursus</span>
                <strong id="detKursus">-</strong>
            </div>
            <div>
                <span>Nama Siswa</span>
                <strong id="detSiswa">-</strong>
            </div>
            <div>
                <span>Nama Tentor</span>
                <strong id="detTentor">-</strong>
            </div>
            <div>
                <span>Deadline</span>
                <strong id="detDeadline">-</strong>
            </div>
            <div>
                <span>Status Pengumpulan</span>
                <strong id="detStatus">-</strong>
            </div>
            <div>
                <span>Deskripsi</span>
                <strong id="detDeskripsi" style="font-weight:400; text-align:right;">-</strong>
            </div>
            <div>
                <span>File Tugas Siswa</span>
                <span id="detFileWrap">
                    <strong id="detFile">-</strong>
                </span>
            </div>
            <div>
                <span>Nilai Akhir</span>
                <strong id="detNilai" style="font-size:18px;">-</strong>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = id => document.getElementById(id);
    const modalDetail = el('modalDetailTugas');
    const cariInput = el('cariTugas');
    const filterKursus = el('filterKursus');
    const filterTentor = el('filterTentor'); // Diubah dari filterStatus menjadi filterTentor
    const judulHalaman = el('judulHalamanSiswa');

    // Elemen Dropdown Siswa
    const siswaFilter   = el('siswaFilter');
    const siswaTrigger  = el('siswaTrigger');
    const siswaDropdown = el('siswaDropdown');
    const siswaTerpilih = el('siswaTerpilih');
    const siswaOptions  = el('siswaOptions');
    const siswaNoResult = el('siswaNoResult');
    const cariSiswa     = el('cariSiswa');

    // Ambil data baris tabel
    const semuaBaris = () => [...document.querySelectorAll('#bodyTugas tr')];

    // Siswa aktif: dari URL (?siswa=...) atau default kosong (Semua Siswa)
    let siswaAktif = new URLSearchParams(window.location.search).get('siswa') || '';

    /* ---------- FUNGSI DROPDOWN SISWA ---------- */
    function updateLabelSiswa() {
        siswaTerpilih.textContent = siswaAktif || 'Semua Siswa';
        judulHalaman.textContent = siswaAktif ? `Daftar Tugas ${siswaAktif}` : 'Daftar Tugas';
    }

    function buatOpsi(label, nilai) {
        const btn = document.createElement('button');
        const aktif = siswaAktif === nilai;
        btn.type = 'button';
        btn.className = 'at-siswa-option' + (aktif ? ' selected' : '');
        btn.setAttribute('role', 'option');
        btn.setAttribute('aria-selected', String(aktif));
        btn.textContent = label;
        btn.addEventListener('click', () => pilihSiswa(nilai));
        return btn;
    }

    function renderOpsiSiswa() {
        const keyword = cariSiswa.value.toLowerCase().trim();
        const daftarSiswa = [...new Set(semuaBaris().map(r => r.dataset.siswa).filter(Boolean))]
            .sort((a, b) => a.localeCompare(b))
            .filter(nama => nama.toLowerCase().includes(keyword));

        siswaOptions.replaceChildren();

        if (!keyword || "semua siswa".includes(keyword)) {
            siswaOptions.appendChild(buatOpsi('Semua Siswa', ''));
        }

        daftarSiswa.forEach(nama => siswaOptions.appendChild(buatOpsi(nama, nama)));
        siswaNoResult.hidden = (siswaOptions.children.length > 0);
    }

    function bukaDropdown() {
        siswaDropdown.hidden = false;
        siswaTrigger.setAttribute('aria-expanded', 'true');
        cariSiswa.value = '';
        renderOpsiSiswa();
        cariSiswa.focus();
    }

    function tutupDropdown() {
        siswaDropdown.hidden = true;
        siswaTrigger.setAttribute('aria-expanded', 'false');
    }

    function pilihSiswa(nama) {
        siswaAktif = nama;
        updateLabelSiswa();
        tutupDropdown();
        siswaTrigger.focus();
        filter();
    }

    siswaTrigger.addEventListener('click', () => {
        siswaDropdown.hidden ? bukaDropdown() : tutupDropdown();
    });

    cariSiswa.addEventListener('input', renderOpsiSiswa);
    
    cariSiswa.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const pertama = siswaOptions.querySelector('.at-siswa-option');
            if (pertama) pertama.click();
        }
    });

    document.addEventListener('click', e => {
        if (!siswaFilter.contains(e.target)) {
            tutupDropdown();
        }
    });

    /* ---------- FUNGSI FILTER UTAMA TABEL ---------- */
    function filter() {
        const q = cariInput.value.toLowerCase().trim();
        const kursus = filterKursus.value;
        const tentor = filterTentor.value; // Mengambil nilai dari filter tentor
        let count = 0;

        semuaBaris().forEach(row => {
            const textMatch = row.cells[0].textContent.toLowerCase().includes(q); 
            const kursusMatch = !kursus || row.dataset.kursus === kursus;
            const tentorMatch = !tentor || row.dataset.tentor === tentor; // Mencocokkan data-tentor
            
            const siswaMatch = !siswaAktif || row.dataset.siswa.toLowerCase() === siswaAktif.toLowerCase();

            const show = textMatch && kursusMatch && tentorMatch && siswaMatch;
            row.hidden = !show;
            if (show) count++;
        });

        el('kosongTugas').hidden = count > 0;
    }

    // Event listener untuk pencarian utama & filter select baru
    cariInput.addEventListener('input', filter);
    filterKursus.addEventListener('change', filter);
    filterTentor.addEventListener('change', filter); // Menggantikan filterStatus

    /* ---------- FUNGSI MODAL DETAIL ---------- */
    window.lihatDetailTugas = function (btn) {
        const row = btn.closest('tr');
        el('detJudul').textContent = row.cells[0].textContent.trim();
        el('detKursus').textContent = row.cells[1].textContent.trim();
        el('detSiswa').textContent = row.cells[2].textContent.trim();
        el('detTentor').textContent = row.cells[3].textContent.trim();
        el('detDeadline').textContent = row.cells[4].textContent.trim();
        el('detStatus').textContent = row.dataset.statusPengumpulan || '-';
        el('detJenis').textContent = row.dataset.jenis || 'Tugas';
        el('detDeskripsi').textContent = row.dataset.deskripsi || '-';
        
        const fileName = row.dataset.file;
        if (fileName && fileName !== '-') {
            el('detFileWrap').innerHTML = `<a href="#" class="at-file-link" target="_blank">${fileName}</a>`;
        } else {
            el('detFileWrap').innerHTML = '<strong>Belum ada file</strong>';
        }

        const nilai = row.dataset.nilai;
        el('detNilai').textContent = (nilai && nilai !== '-') ? `${nilai} / 100` : 'Belum Dinilai';

        modalDetail.hidden = false;
        document.body.style.overflow = 'hidden';
    };

    function tutupModal() {
        modalDetail.hidden = true;
        document.body.style.overflow = '';
    }

    document.querySelectorAll('[data-close]').forEach(btn => {
        btn.addEventListener('click', tutupModal);
    });
    
    modalDetail.addEventListener('click', e => {
        if (e.target === modalDetail) tutupModal();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            if (!modalDetail.hidden) tutupModal();
            else if (!siswaDropdown.hidden) tutupDropdown();
        }
    });

    /* ---------- Inisialisasi Awal ---------- */
    updateLabelSiswa();
    renderOpsiSiswa();
    filter();
});
</script>

@endsection