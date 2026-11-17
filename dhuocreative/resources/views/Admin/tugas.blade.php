
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
        <div class="at-search at-search-main">
            <input type="search" id="cariTugas" placeholder="Cari judul tugas..." aria-label="Cari tugas">
        </div>

        <!-- DROPDOWN FILTER SISWA -->
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

        <!-- FILTER TENTOR -->
        <select class="at-select" id="filterTentor" aria-label="Filter tentor">
            <option value="">Semua Tentor</option>
            @foreach ($daftarTentor as $tentor)
                <option value="{{ $tentor }}">{{ $tentor }}</option>
            @endforeach
        </select>

        <!-- FILTER KURSUS -->
        <select class="at-select" id="filterKursus" aria-label="Filter kursus">
            <option value="">Semua Kursus</option>
            @foreach ($daftarKursus as $kursus)
                <option value="{{ $kursus }}">{{ $kursus }}</option>
            @endforeach
        </select>
    </div>

    <!-- TABEL TUGAS -->
    <div class="at-table-wrap">
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
                @forelse ($daftarTugas as $item)
                    <tr
                        data-kursus="{{ $item->nama_kursus }}"
                        data-siswa="{{ $item->nama_siswa ?? '-' }}"
                        data-tentor="{{ $item->nama_tentor }}"
                        data-jenis="{{ $item->jenis_label }}"
                        data-deskripsi="{{ $item->deskripsi ?? '-' }}"
                        data-file="{{ $item->file_jawaban ?? '' }}"
                        data-file-url="{{ $item->file_url ?? '' }}"
                        data-nilai="{{ $item->nilai ?? '' }}"
                        data-feedback="{{ $item->feedback ?? '' }}"
                        data-status-pengumpulan="{{ $item->status_label }}"
                    >
                        <td>{{ $item->judul }}</td>
                        <td>{{ $item->nama_kursus }}</td>
                        <td>{{ $item->nama_siswa ?? '-' }}</td>
                        <td>{{ $item->nama_tentor }}</td>
                        <td>{{ $item->deadline_label }}</td>

                        <td>
                            @if (!$item->id_pengumpulan)
                                <span class="at-status tdk">Belum Mengumpulkan</span>
                            @elseif ($item->status_pengumpulan === 'terlambat')
                                <span class="at-status tdk">Terlambat</span>
                            @else
                                <span class="at-status kmpl">{{ $item->status_label }}</span>
                            @endif
                        </td>

                        <td>
                            @if (!is_null($item->nilai))
                                <span class="at-nilai-badge">{{ $item->nilai }}</span>
                            @else
                                <span class="at-nilai-empty">-</span>
                            @endif
                        </td>

                        <td>
                            <button type="button" class="at-view-btn" onclick="lihatDetailTugas(this)">
                                Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center;">
                            Belum ada data tugas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <p class="at-empty" id="kosongTugas" hidden>Tugas tidak ditemukan.</p>

    <!-- PAGINASI -->
    <nav class="tj-pagination" id="paginasiTugasAdmin"
         aria-label="Halaman daftar tugas"
         style="margin-top: 20px;" hidden>
    </nav>
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

            <div>
                <span>Feedback</span>
                <strong id="detFeedback">-</strong>
            </div>
        </div>
    </div>
</div>

<script>
const PER_HALAMAN_TUGAS_ADMIN = 10;
let halTugasAdmin = 1;

function renderPaginasiTugasAdmin(navId, total, halaman, pindah) {
    const nav = document.getElementById(navId);
    if (!nav) return;

    nav.replaceChildren();

    if (!total || total <= 1) {
        nav.hidden = true;
        return;
    }

    nav.hidden = false;

    const jumlah = Math.ceil(total / PER_HALAMAN_TUGAS_ADMIN);
    const awal = (halaman - 1) * PER_HALAMAN_TUGAS_ADMIN + 1;
    const akhir = Math.min(halaman * PER_HALAMAN_TUGAS_ADMIN, total);

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
    const el = id => document.getElementById(id);

    const modalDetail = el('modalDetailTugas');
    const cariInput = el('cariTugas');
    const filterKursus = el('filterKursus');
    const filterTentor = el('filterTentor');
    const judulHalaman = el('judulHalamanSiswa');
    const bodyTugas = el('bodyTugas');

    // DROPDOWN SISWA
    const siswaFilter = el('siswaFilter');
    const siswaTrigger = el('siswaTrigger');
    const siswaDropdown = el('siswaDropdown');
    const siswaTerpilih = el('siswaTerpilih');
    const siswaOptions = el('siswaOptions');
    const siswaNoResult = el('siswaNoResult');
    const cariSiswa = el('cariSiswa');

    const semuaBaris = () => [
        ...bodyTugas.querySelectorAll('tr[data-kursus]')
    ];

    let siswaAktif = new URLSearchParams(window.location.search).get('siswa') || '';

    function updateLabelSiswa() {
        siswaTerpilih.textContent = siswaAktif || 'Semua Siswa';

        judulHalaman.textContent = siswaAktif
            ? `Daftar Tugas ${siswaAktif}`
            : 'Daftar Tugas';
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

        const daftarSiswa = [
            ...new Set(
                semuaBaris()
                    .map(r => r.dataset.siswa)
                    .filter(nama => nama && nama !== '-')
            )
        ]
        .sort((a, b) => a.localeCompare(b))
        .filter(nama => nama.toLowerCase().includes(keyword));

        siswaOptions.replaceChildren();

        if (!keyword || 'semua siswa'.includes(keyword)) {
            siswaOptions.appendChild(buatOpsi('Semua Siswa', ''));
        }

        daftarSiswa.forEach(nama => {
            siswaOptions.appendChild(buatOpsi(nama, nama));
        });

        siswaNoResult.hidden = siswaOptions.children.length > 0;
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

        halTugasAdmin = 1;
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

    // FILTER DAN PENCARIAN
    function filter() {
        const q = cariInput.value.toLowerCase().trim();
        const kursus = filterKursus.value;
        const tentor = filterTentor.value;

        const allRows = semuaBaris();

        const filteredRows = allRows.filter(row => {
            const judul = row.cells[0].textContent.toLowerCase();

            const textMatch = judul.includes(q);
            const kursusMatch = !kursus || row.dataset.kursus === kursus;
            const tentorMatch = !tentor || row.dataset.tentor === tentor;

            const siswaMatch = !siswaAktif ||
                row.dataset.siswa.toLowerCase() === siswaAktif.toLowerCase();

            return textMatch && kursusMatch && tentorMatch && siswaMatch;
        });

        allRows.forEach(row => row.style.display = 'none');

        const totalFiltered = filteredRows.length;

        el('kosongTugas').hidden = totalFiltered > 0;

        if (totalFiltered === 0) {
            renderPaginasiTugasAdmin(
                'paginasiTugasAdmin',
                0,
                halTugasAdmin,
                pindahHalaman
            );
            return;
        }

        const maxPage = Math.ceil(totalFiltered / PER_HALAMAN_TUGAS_ADMIN);

        if (halTugasAdmin > maxPage) {
            halTugasAdmin = maxPage || 1;
        }

        const start = (halTugasAdmin - 1) * PER_HALAMAN_TUGAS_ADMIN;
        const end = start + PER_HALAMAN_TUGAS_ADMIN;

        filteredRows.slice(start, end).forEach(row => {
            row.style.display = '';
        });

        renderPaginasiTugasAdmin(
            'paginasiTugasAdmin',
            totalFiltered,
            halTugasAdmin,
            pindahHalaman
        );
    }

    function pindahHalaman(tujuanBaru) {
        halTugasAdmin = tujuanBaru;
        filter();

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    cariInput.addEventListener('input', () => {
        halTugasAdmin = 1;
        filter();
    });

    filterKursus.addEventListener('change', () => {
        halTugasAdmin = 1;
        filter();
    });

    filterTentor.addEventListener('change', () => {
        halTugasAdmin = 1;
        filter();
    });

    // MODAL DETAIL TUGAS
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

        // FILE JAWABAN SISWA
        const fileName = row.dataset.file;
        const fileUrl = row.dataset.fileUrl;

        const fileWrap = el('detFileWrap');
        fileWrap.replaceChildren();

        if (fileName && fileUrl) {
            const link = document.createElement('a');

            link.href = fileUrl;
            link.className = 'at-file-link';
            link.target = '_blank';
            link.rel = 'noopener noreferrer';
            link.textContent = fileName;

            fileWrap.appendChild(link);
        } else {
            const strong = document.createElement('strong');

            strong.textContent = 'Belum ada file';
            fileWrap.appendChild(strong);
        }

        // NILAI
        const nilai = row.dataset.nilai;

        el('detNilai').textContent =
            nilai !== '' ? `${nilai} / 100` : 'Belum Dinilai';

        // FEEDBACK
        const feedback = row.dataset.feedback;

        el('detFeedback').textContent =
            feedback || 'Belum ada feedback.';

        modalDetail.hidden = false;
        document.body.style.overflow = 'hidden';
    };

    // TUTUP MODAL
    function tutupModal() {
        modalDetail.hidden = true;
        document.body.style.overflow = '';
    }

    document.querySelectorAll('[data-close]').forEach(btn => {
        btn.addEventListener('click', tutupModal);
    });

    modalDetail.addEventListener('click', e => {
        if (e.target === modalDetail) {
            tutupModal();
        }
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            if (!modalDetail.hidden) {
                tutupModal();
            } else if (!siswaDropdown.hidden) {
                tutupDropdown();
            }
        }
    });

    updateLabelSiswa();
    renderOpsiSiswa();
    filter();
});
</script>
@endsection
