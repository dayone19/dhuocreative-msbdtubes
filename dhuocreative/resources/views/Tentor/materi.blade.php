
@extends('layouts.tentor')

@section('title', 'Daftar Materi')

@section('content')
<div class="tmt-page">
    <h1 class="tmt-title">Daftar Materi</h1>

    <div class="tmt-toolbar">

        <div class="tmt-search">
            <input type="text" id="searchMateri"
                   placeholder="Cari materi...">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="#ae9154" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                </svg>
            </span>
        </div>

        <select id="filterKelas" class="tmt-select">
            <option value="">Kelas</option>
            <option value="Web Master">Web Master</option>
            <option value="Desain Grafis">Desain Grafis</option>
            <option value="Microsoft Office">Microsoft Office</option>
            <option value="Pemrograman Web">Pemrograman Web</option>
        </select>

        <select id="sortMateri" class="tmt-select">
            <option value="terbaru">Terbaru</option>
            <option value="terlama">Terlama</option>
            <option value="az">Nama A-Z</option>
        </select>

        <button type="button" class="tmt-upload-btn"
                onclick="bukaUpload()">
            <span class="tmt-plus">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                </svg>
            </span>
            <span>Upload<br>Materi</span>
        </button>

    </div>

    <div class="tmt-table-container">
        <table class="tmt-table">
            <thead>
                <tr>
                    <th>Judul Materi</th>
                    <th>Kelas</th>
                    <th>Tanggal<br>Upload</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="materiTable">
                <tr data-kelas="Web Master"
                    data-judul="tabel html"
                    data-tanggal="2026-09-25"
                    data-deskripsi="Materi ini membahas cara membuat struktur tabel dasar dengan tag table, tr, th, dan td di HTML."
                    data-file="tabel-html.pdf">
                    <td>Tabel HTML</td>
                    <td>Web Master</td>
                    <td>9/25/26</td>
                    <td>
                        <button class="tmt-view-btn"
                                onclick="lihatMateri(this)">
                            Lihat
                        </button>
                    </td>
                </tr>

                <tr data-kelas="Web Master"
                    data-judul="dasar css"
                    data-tanggal="2026-09-24"
                    data-deskripsi="Pengenalan styling dasar menggunakan CSS selectors, warna, dan font."
                    data-file="dasar-css.pdf">
                    <td>Dasar CSS</td>
                    <td>Web Master</td>
                    <td>9/24/26</td>
                    <td>
                        <button class="tmt-view-btn"
                                onclick="lihatMateri(this)">
                            Lihat
                        </button>
                    </td>
                </tr>

                <tr data-kelas="Desain Grafis"
                    data-judul="pengenalan desain grafis"
                    data-tanggal="2026-09-23"
                    data-deskripsi="-"
                    data-file="dasar-dg.pdf">
                    <td>Pengenalan Desain Grafis</td>
                    <td>Desain Grafis</td>
                    <td>9/23/26</td>
                    <td>
                        <button class="tmt-view-btn"
                                onclick="lihatMateri(this)">
                            Lihat
                        </button>
                    </td>
                </tr>

                <tr data-kelas="Microsoft Office"
                    data-judul="dasar microsoft word"
                    data-tanggal="2026-09-22"
                    data-deskripsi="-"
                    data-file="dasar-office.pdf">
                    <td>Dasar Microsoft Word</td>
                    <td>Microsoft Office</td>
                    <td>9/22/26</td>
                    <td>
                        <button class="tmt-view-btn"
                                onclick="lihatMateri(this)">
                            Lihat
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="tmt-empty" id="materiKosong" hidden>
            Materi tidak ditemukan.
        </div>
    </div>

</div>

{{-- modal upload tgs --}}
<div class="tmt-modal-overlay" id="modalUpload" hidden>
    <div class="tmt-upload-modal">

        <div class="tmt-upload-header">
            <button type="button"
                    class="tmt-back-btn"
                    onclick="tutupUpload()"
                    aria-label="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>

            <h2>Upload<br>Materi</h2>
        </div>

        <form id="formUpload" class="tmt-upload-form">
            <div class="tmt-upload-left">
                <div class="tmt-form-group">
                    <label for="judulBaru">Judul Materi</label>
                    <input type="text"
                           id="judulBaru"
                           placeholder="Masukkan judul materi"
                           required>
                </div>

                <div class="tmt-form-group">
                    <label for="kelasBaru">Kelas</label>
                    <select id="kelasBaru" required>
                        <option value="">Pilih kelas</option>
                        <option value="Web Master">Web Master</option>
                        <option value="Desain Grafis">Desain Grafis</option>
                        <option value="Microsoft Office">Microsoft Office</option>
                        <option value="Pemrograman Web">Pemrograman Web</option>
                    </select>
                </div>

                <div class="tmt-form-group">
                    <label for="deskripsiBaru">Deskripsi Materi (Opsional)</label>
                    <textarea id="deskripsiBaru"
                              placeholder="Tulis deskripsi materi..."
                              required></textarea>
                </div>
            </div>

            <div class="tmt-upload-right">
                <label class="tmt-file-label" for="fileBaru">
                    File Materi
                </label>
                <label for="fileBaru" class="tmt-dropzone">
                    <input type="file"
                           id="fileBaru"
                           accept=".pdf,.doc,.docx,.ppt,.pptx,.zip"
                           required>
                    <svg xmlns="http://www.w3.org/2000/svg" height="55px" viewBox="0 -960 960 960" width="55px" fill="#b29458">
                        <path d="M260-160q-91 0-155.5-63T40-377q0-78 47-139t123-78q25-92 100-149t170-57q117 0 198.5 81.5T760-520q69 8 114.5 59.5T920-340q0 75-52.5 127.5T740-160H520q-33 0-56.5-23.5T440-240v-206l-64 62-56-56 160-160 160 160-56 56-64-62v206h220q42 0 71-29t29-71q0-42-29-71t-71-29h-60v-80q0-83-58.5-141.5T480-720q-83 0-141.5 58.5T280-520h-20q-58 0-99 41t-41 99q0 58 41 99t99 41h100v80H260Zm220-280Z"/>
                    </svg>
                    <span class="tmt-file-instruction">
                        Klik untuk mengunggah
                    </span>
                    <span class="tmt-file-limit">
                        (file max 50mb)
                    </span>
                    <span class="tmt-file-name" id="namaFile">
                        Belum ada file dipilih
                    </span>
                </label>

                <div class="tmt-upload-actions">
                    <button type="button"
                            class="tmt-cancel-btn"
                            onclick="tutupUpload()">
                        Batal
                    </button>
                    <button type="submit" class="tmt-send-btn">
                        Kirim
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="tmt-modal-overlay" id="modalLihat" hidden>
    <div class="tmt-upload-modal">

        <div class="tmt-upload-header">
            <button type="button"
                    class="tmt-back-btn"
                    onclick="tutupLihat()"
                    aria-label="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>

            <h2>Detail<br>Materi</h2>
        </div>

        <div class="tmt-upload-form">
            <div class="tmt-upload-left">
                <div class="tmt-form-group">
                    <label for="judulLihat">Judul Materi</label>
                    <input type="text"
                           id="judulLihat"
                           readonly>
                </div>

                <div class="tmt-form-group">
                    <label for="kelasLihat">Kelas</label>
                    <input type="text"
                           id="kelasLihat"
                           readonly>
                </div>

                <div class="tmt-form-group">
                    <label for="deskripsiLihat">Deskripsi Materi</label>
                    <textarea id="deskripsiLihat"
                              readonly></textarea>
                </div>
            </div>

            <div class="tmt-upload-right">
                <label class="tmt-file-label">
                    File Materi
                </label>
                <div class="tmt-dropzone" style="pointer-events: none; background-color: #fafafa;">
                    <svg xmlns="http://www.w3.org/2000/svg" height="55px" viewBox="0 -960 960 960" width="55px" fill="#b29458">
                        <path d="M260-160q-91 0-155.5-63T40-377q0-78 47-139t123-78q25-92 100-149t170-57q117 0 198.5 81.5T760-520q69 8 114.5 59.5T920-340q0 75-52.5 127.5T740-160H520q-33 0-56.5-23.5T440-240v-206l-64 62-56-56 160-160 160 160-56 56-64-62v206h220q42 0 71-29t29-71q0-42-29-71t-71-29h-60v-80q0-83-58.5-141.5T480-720q-83 0-141.5 58.5T280-520h-20q-58 0-99 41t-41 99q0 58 41 99t99 41h100v80H260Zm220-280Z"/>
                    </svg>
                    <span class="tmt-file-name" id="fileLihatNama">
                        Belum ada file
                    </span>
                </div>

                <div class="tmt-upload-actions">
                    <button type="button"
                            class="tmt-cancel-btn"
                            onclick="tutupLihat()"
                            style="width: 100%;">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
const searchMateri = document.getElementById('searchMateri');
const filterKelas = document.getElementById('filterKelas');
const sortMateri = document.getElementById('sortMateri');
const materiTable = document.getElementById('materiTable');
const materiKosong = document.getElementById('materiKosong');

function filterMateri() {
    const keyword = searchMateri.value.toLowerCase().trim();
    const kelas = filterKelas.value;
    const rows = [...materiTable.querySelectorAll('tr')];

    rows.forEach(row => {
        row.hidden = !(
            row.dataset.judul.includes(keyword) &&
            (!kelas || row.dataset.kelas === kelas)
        );
    });

    const visibleRows = rows.filter(row => !row.hidden);

    visibleRows.sort((a, b) => {
        if (sortMateri.value === 'terbaru') {
            return b.dataset.tanggal.localeCompare(a.dataset.tanggal);
        }

        if (sortMateri.value === 'terlama') {
            return a.dataset.tanggal.localeCompare(b.dataset.tanggal);
        }

        return a.dataset.judul.localeCompare(b.dataset.judul);
    });

    visibleRows.forEach(row => materiTable.appendChild(row));

    materiKosong.hidden = visibleRows.length > 0;
}

searchMateri.addEventListener('input', filterMateri);
filterKelas.addEventListener('change', filterMateri);
sortMateri.addEventListener('change', filterMateri);

// ... (kode filter tetap sama) ...

function bukaUpload() {
    document.getElementById('modalUpload').hidden = false;
}

function tutupUpload() {
    document.getElementById('modalUpload').hidden = true;
}

function lihatMateri(button) {
    const row = button.closest('tr');
    
    const judul = row.cells[0].textContent;
    const kelas = row.dataset.kelas;
    const deskripsi = row.dataset.deskripsi || 'Tidak ada deskripsi.';
    const namaFile = row.dataset.file || 'file_materi.pdf';

    document.getElementById('judulLihat').value = judul;
    document.getElementById('kelasLihat').value = kelas;
    document.getElementById('deskripsiLihat').value = deskripsi;
    document.getElementById('fileLihatNama').textContent = namaFile;

    document.getElementById('modalLihat').hidden = false;
}

function tutupLihat() {
    document.getElementById('modalLihat').hidden = true;
}

// Simulasi upload di browser
document.getElementById('formUpload').addEventListener('submit', function(e) {
    e.preventDefault();

    const judul = document.getElementById('judulBaru').value.trim();
    const kelas = document.getElementById('kelasBaru').value;
    const deskripsi = document.getElementById('deskripsiBaru').value.trim();
    const file = document.getElementById('fileBaru').files[0];

    if (!judul || !kelas || !file) return;

    const tanggal = new Date();
    const tanggalISO = [
        tanggal.getFullYear(),
        String(tanggal.getMonth() + 1).padStart(2, '0'),
        String(tanggal.getDate()).padStart(2, '0')
    ].join('-');

    const tanggalTampil =
        (tanggal.getMonth() + 1) + '/' +
        tanggal.getDate() + '/' +
        String(tanggal.getFullYear()).slice(-2);

    const row = document.createElement('tr');
    row.dataset.kelas = kelas;
    row.dataset.judul = judul.toLowerCase();
    row.dataset.tanggal = tanggalISO;
    row.dataset.deskripsi = deskripsi || '-';
    row.dataset.file = file.name; // Menyimpan nama file asli

    const cells = [judul, kelas, tanggalTampil];

    cells.forEach(text => {
        const td = document.createElement('td');
        td.textContent = text;
        row.appendChild(td);
    });

    const action = document.createElement('td');
    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'tmt-view-btn';
    button.textContent = 'Lihat';
    // Gunakan arrow function yang mengirimkan (this)
    button.addEventListener('click', function() {
        lihatMateri(this);
    });
    action.appendChild(button);
    row.appendChild(action);

    materiTable.prepend(row);

    filterKelas.value = '';
    sortMateri.value = 'terbaru';
    searchMateri.value = '';

    filterMateri();
    tutupUpload();
    this.reset();
    
    // Reset teks nama file upload
    document.getElementById('namaFile').textContent = 'Belum ada file dipilih';

    alert('Materi ditambahkan untuk pratinjau.');
});
</script>
@endsection