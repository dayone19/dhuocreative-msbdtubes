@extends('layouts.Operator')

@section('title', 'Daftar Tugas Siswa')

@section('content')

<div class="o-page">
    <div class="o-page-header">
        <div class="o-modal-header" style="margin-bottom: 0;">
            <a href="{{ route('operator.siswa') }}" class="o-modal-close" aria-label="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </a>
            <h1 class="o-page-title" id="judulHalamanSiswa">Daftar Tugas Siswa A</h1>
        </div>
    </div>

    {{-- TOOLBAR FILTER & CARI --}}
    <div class="o-toolbar">
        <div class="tt-search" style="flex: 1; min-width: 250px;">
            <input type="search" id="cariTugas" placeholder="Cari judul tugas..." aria-label="Cari tugas">
        </div>
        <div class="o-schedule-filters" style="display: flex; gap: 10px; flex: 2; justify-content: flex-end;">
            <select class="tt-select" id="filterTentor" style="width: auto; min-width: 160px;" aria-label="Filter tentor">
                <option value="">Semua Tentor</option>
                <option value="Tentor C">Tentor C</option>
                <option value="Tentor D">Tentor D</option>
            </select>
            <select class="tt-select" id="filterKursus" style="width: auto; min-width: 160px;" aria-label="Filter kursus">
                <option value="">Semua Kursus</option>
                <option value="Web Master">Web Master</option>
                <option value="MS Office">MS Office</option>
            </select>
        </div>
    </div>

    {{-- TABEL TUGAS --}}
    <div class="tt-table-container">
        <table class="tt-table" id="tabelTugas">
            <thead>
                <tr>
                    <th>Judul Tugas</th>
                    <th>Kursus</th>
                    <th>Tentor</th>
                    <th>Deadline</th>
                    <th>Status Pengumpulan</th>
                    <th>Nilai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="bodyTugas">
                <tr data-kursus="Web Master" 
                    data-tentor="Tentor C" 
                    data-status="sudah"
                    data-jenis="Tugas"
                    data-deskripsi="Membuat struktur tabel HTML lengkap dengan CSS styling sederhana."
                    data-file="Tugas1_SiswaA.html"
                    data-nilai="85"
                    data-feedback="Hasil baik, tingkatkan lagi!"
                    data-status-pengumpulan="Terkumpul">
                    <td>Membuat Tabel HTML</td>
                    <td>Web Master</td>
                    <td>Tentor C</td>
                    <td>30 Sep 2026 · 23:59</td>
                    <td><span class="o-status kmpl">Terkumpul</span></td>
                    <td><span class="o-nilai-badge">85</span></td>
                    <td>
                        <button type="button" class="tt-view-btn" onclick="lihatDetailTugas(this)">Detail</button>
                    </td>
                </tr>
                <tr data-kursus="Web Master" 
                    data-tentor="Tentor D" 
                    data-status="belum"
                    data-jenis="Projek"
                    data-deskripsi="Membuat halaman portofolio pribadi menggunakan HTML & CSS."
                    data-file="-"
                    data-nilai="-"
                    data-feedback="-"
                    data-status-pengumpulan="Belum Mengumpulkan">
                    <td>Projek Portofolio</td>
                    <td>Web Master</td>
                    <td>Tentor D</td>
                    <td>10 Okt 2026 · 23:59</td>
                    <td><span class="o-status tdk">Tidak Kumpul</span></td>
                    <td><span class="o-nilai-empty">-</span></td>
                    <td>
                        <button type="button" class="tt-view-btn" onclick="lihatDetailTugas(this)">Detail</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <p class="o-empty" id="kosongTugas" hidden>Tugas tidak ditemukan.</p>
</div>

<nav class="tj-pagination" id="paginasiTugas" aria-label="Halaman daftar tugas" style="margin-top: 20px;"></nav>

{{-- MODAL DETAIL TUGAS & NILAI (READ ONLY FOR OPERATOR) --}}
<div class="tt-modal-overlay" id="modalDetailTugas" hidden>
    <div class="tt-modal-box tt-modal-wide">
        <div class="o-modal-header">
            <button type="button" class="o-modal-close" data-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2>Detail Tugas & Nilai</h2>
        </div>

        <div class="tt-detail-list">
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
            <div>
                <span>Feedback</span>
                <strong id="detFeedback">-</strong>
            </div>
        </div>
    </div>
</div>

<script>
const PER_HALAMAN = 5; 
let halTugas = 1;

function renderPaginasi(navId, total, halaman, pindah) {
    const nav = document.getElementById(navId);
    if (!nav) return;
    
    nav.replaceChildren();
    
    if (!total || total <= 1) {
        nav.style.display = 'none';
        return;
    }
    nav.style.display = 'flex';
    
    const jumlah = Math.ceil(total / PER_HALAMAN);
    const awal = (halaman - 1) * PER_HALAMAN + 1;
    const akhir = Math.min(halaman * PER_HALAMAN, total);

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

document.addEventListener('DOMContentLoaded', function () {
    const el = id => document.getElementById(id);
    const modalDetail = el('modalDetailTugas');
    const cariInput = el('cariTugas');
    const filterKursus = el('filterKursus');
    const filterTentor = el('filterTentor');
    const bodyTugas = el('bodyTugas');

    // Menangkap parameter query URL (misal: ?siswa=Siswa+A)
    const urlParams = new URLSearchParams(window.location.search);
    const siswaParam = urlParams.get('siswa');
    if (siswaParam) {
        el('judulHalamanSiswa').textContent = `Daftar Tugas ${siswaParam}`;
    }

    // Fungsi Filter & Paginasi Terpadu
    function filterTabel() {
        const q = cariInput.value.toLowerCase().trim();
        const kursus = filterKursus.value;
        const tentor = filterTentor.value;

        const allRows = [...bodyTugas.querySelectorAll('tr')];
        
        // Saring baris berdasarkan kriteria pencarian dan dropdown
        const filteredRows = allRows.filter(row => {
            const textMatch = row.textContent.toLowerCase().includes(q);
            const kursusMatch = !kursus || row.dataset.kursus === kursus;
            const tentorMatch = !tentor || row.dataset.tentor === tentor;
            return textMatch && kursusMatch && tentorMatch;
        });

        // Sembunyikan semua baris terlebih dahulu
        allRows.forEach(row => row.style.display = 'none');

        const totalFiltered = filteredRows.length;
        el('kosongTugas').hidden = totalFiltered > 0;

        if (totalFiltered === 0) {
            renderPaginasi('paginasiTugas', 0, halTugas, pindahHalaman);
            return;
        }

        const maxPage = Math.ceil(totalFiltered / PER_HALAMAN);
        if (halTugas > maxPage) halTugas = maxPage || 1;

        const start = (halTugas - 1) * PER_HALAMAN;
        const end = start + PER_HALAMAN;

        filteredRows.slice(start, end).forEach(row => {
            row.style.display = '';
        });

        renderPaginasi('paginasiTugas', totalFiltered, halTugas, pindahHalaman);
    }

    function pindahHalaman(tujuanBaru) {
        halTugas = tujuanBaru;
        filterTabel();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    [cariInput, filterKursus, filterTentor].forEach(item => {
        item.addEventListener(item === cariInput ? 'input' : 'change', () => {
            halTugas = 1; // Reset ke halaman 1 saat filter diubah
            filterTabel();
        });
    });

    window.lihatDetailTugas = function (btn) {
        const row = btn.closest('tr');
        el('detJudul').textContent = row.cells[0].textContent.trim();
        el('detKursus').textContent = row.cells[1].textContent.trim();
        el('detTentor').textContent = row.cells[2].textContent.trim();
        el('detDeadline').textContent = row.cells[3].textContent.trim();
        el('detStatus').textContent = row.dataset.statusPengumpulan || '-';
        el('detJenis').textContent = row.dataset.jenis || 'Tugas';
        el('detDeskripsi').textContent = row.dataset.deskripsi || '-';
        
        const fileName = row.dataset.file;
        if (fileName && fileName !== '-') {
            el('detFileWrap').innerHTML = `<a href="#" class="o-file-link" target="_blank">${fileName}</a>`;
        } else {
            el('detFileWrap').innerHTML = '<strong>Belum ada file</strong>';
        }

        const nilai = row.dataset.nilai;
        el('detNilai').textContent = (nilai && nilai !== '-') ? `${nilai} / 100` : 'Belum Dinilai';

        const feedback = row.dataset.feedback;
        el('detFeedback').textContent = feedback || 'Belum ada feedback.';

        modalDetail.hidden = false;
    };

    document.querySelectorAll('[data-close]').forEach(btn => {
        btn.addEventListener('click', () => modalDetail.hidden = true);
    });
    modalDetail.addEventListener('click', e => {
        if (e.target === modalDetail) modalDetail.hidden = true;
    });

    filterTabel();
});
</script>
@endsection