@extends('layouts.tentor')

@section('title', 'Daftar Tugas')

@section('content')
<div class="tmt-page">

    <h1 class="tmt-title">Daftar Tugas</h1>
    <div class="tmt-toolbar">

        <div class="tmt-search">
            <input type="text" id="searchTugas"
                   placeholder="Cari tugas...">
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

        <select id="sortTugas" class="tmt-select">
            <option value="terbaru">Terbaru</option>
            <option value="terlama">Terlama</option>
            <option value="az">Nama A-Z</option>
        </select>

        <button type="button" class="tmt-upload-btn"
                onclick="bukaUpload()">
            <span class="t-plus">+</span>
            <span>Upload<br>Tugas</span>
        </button>

    </div>

    <div class="tmt-table-container">
        <table class="tmt-table">
            <thead>
                <tr>
                    <th>Judul Tugas</th>
                    <th>Kursus</th>
                    <th>Siswa</th>
                    <th>Tanggal<br>Upload</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="tugasTable">

                <tr data-kelas="Web Master"
                    data-judul="membuat tabel html"
                    data-tanggal="2026-09-25"
                    data-deadline="2026-09-30T23:59"
                    data-status-pengumpulan="terlambat"
                    data-jenis="tugas"
                    data-deskripsi="-">
                    <td>Membuat tabel html</td>
                    <td>Web Master</td>
                    <td>Siswa A</td>
                    <td>9/25/26</td>
                    <td class="tmt-action-group">
                        <button type="button" class="tmt-view-btn" onclick="bukaDetail(this)">Detail</button>
                        <button type="button" class="tmt-view-btn" onclick="lihatTugas(this)">Lihat</button>
                    </td>
                </tr>

            </tbody>
        </table>

        <div class="tmt-empty" id="tugasKosong" hidden>
            Tugas tidak ditemukan.
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

            <h2>Upload<br>Tugas</h2>
        </div>

        <form id="formUpload" class="tmt-upload-form">
            <div class="tmt-upload-left">
                <div class="tmt-form-group">
                    <label for="judulBaru">Judul Tugas</label>
                    <input type="text"
                           id="judulBaru"
                           placeholder="Masukkan judul tugas"
                           required>
                </div>

                <div class="tmt-form-group">
                    <label for="jenisTgs">Jenis Tugas</label>
                    <select id="jenisTgs" required>
                        <option value="">Pilih jenis tugas</option>
                        <option value="tugas">Tugas</option>
                        <option value="projek">Projek</option>
                    </select>
                </div>

                <div class="tmt-form-group">
                    <label for="kelasBaru">Kursus</label>
                    <select id="kelasBaru" required>
                        <option value="">Pilih kursus</option>
                        <option value="Web Master">Web Master</option>
                        <option value="Desain Grafis">Desain Grafis</option>
                        <option value="Microsoft Office">Microsoft Office</option>
                        <option value="Pemrograman Web">Pemrograman Web</option>
                    </select>
                </div>

                <div class="tmt-form-group">
                    <label for="siswa">Nama Siswa</label>
                    <select id="siswa" required>
                        <option value="">Pilih kelas</option>
                        <option value="Siswa A">Siswa A</option>
                    </select>
                </div>
            </div>

            <div class="tmt-upload-right">
                <div class="tmt-form-group">
                    <label for="deadlineBaru">Deadline Tugas</label>
                    <input type="datetime-local" id="deadlineBaru" required>
                </div>

                <div class="tmt-form-group">
                    <label for="deskripsiBaru">Deskripsi Tugas</label>
                    <textarea id="deskripsiBaru"
                              placeholder="Tulis deskripsi tugas..."
                              required></textarea>
                </div>

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

{{-- Modal Detail / Edit Tugas --}}
<div class="tmt-modal-overlay" id="modalDetail" hidden>
    <div class="tmt-upload-modal tmt-modal-wide" id="detailBox">

        <div class="tmt-upload-header">
            <button type="button" class="tmt-back-btn" onclick="kembaliDetail()" aria-label="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2 id="detailJudulModal">Detail<br>Tugas</h2>
        </div>

        <form id="formEditTugas" class="tmt-upload-form">
            <div class="tmt-upload-left">
                <div class="tmt-form-group">
                    <label for="detailNamaTugas">Nama Tugas</label>
                    <input type="text" id="detailNamaTugas"  placeholder="Masukkan nama tugas" readonly required>
                </div>

                <div class="tmt-form-group">
                    <label for="detailJenisTugas">Jenis Tugas</label>
                    <select id="detailJenisTugas" disabled required>
                        <option value="tugas">Tugas</option>
                        <option value="projek">Projek</option>
                    </select>
                </div>

                <div class="tmt-form-group">
                    <label for="detailKursus">Kursus</label>
                    <select id="detailKursus" disabled required>
                        <option value="Web Master">Web Master</option>
                        <option value="Desain Grafis">Desain Grafis</option>
                        <option value="Microsoft Office">Microsoft Office</option>
                        <option value="Pemrograman Web">Pemrograman Web</option>
                    </select>
                </div>

                <div class="tmt-form-group">
                    <label for="detailNamaSiswa">Nama Siswa</label>
                    <select id="detailNamaSiswa" disabled required>
                        <option value="Siswa A">Siswa A</option>
                        <option value="Siswa B">Siswa B</option>
                    </select>
                </div>
            </div>

            <div class="tmt-upload-right">
                <div class="tmt-form-group">
                    <label for="detailDeadline">Deadline Tugas</label>
                    <input type="datetime-local" id="detailDeadline" readonly required>
                </div>
                
                <div class="tmt-form-group">
                    <label for="detailDeskripsi">Deskripsi</label>
                    <textarea id="detailDeskripsi" placeholder="Tulis deskripsi tugas..." readonly></textarea>
                </div>

                <div class="tmt-upload-actions" id="actionDetail">
                    <button type="button" class="tmt-cancel-btn" onclick="tutupDetail()">Tutup</button>
                    <button type="button" class="tmt-send-btn" onclick="aktifkanEditMode()">Edit</button>
                </div>

                <div class="tmt-upload-actions" id="actionEdit" hidden>
                    <button type="button" class="tmt-cancel-btn" onclick="batalEditMode()">Batal</button>
                    <button type="submit" class="tmt-send-btn">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>


{{-- Modal Lihat Tugas --}}
<div class="tmt-modal-overlay" id="modalLihat" hidden>
    <div class="tmt-upload-modal tmt-modal-wide">

        <div class="tmt-upload-header">
            <button type="button" class="tmt-back-btn" onclick="tutupLihat()" aria-label="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2>Lihat<br>Tugas</h2>
        </div>

        <form id="formNilai" class="tmt-upload-form">

            <div class="tmt-upload-left">
                <div class="tmt-form-group">
                    <label for="lihatNamaTugas">Judul Tugas</label>
                    <input type="text" id="lihatNamaTugas" readonly>
                </div>

                <div class="tmt-form-group">
                    <label for="lihatJenisTugas">Jenis Tugas</label>
                    <input type="text" id="lihatJenisTugas" readonly>
                </div>

                <div class="tmt-form-group">
                    <label for="lihatKursus">Kursus</label>
                    <input type="text" id="lihatKursus" readonly>
                </div>

                <div class="tmt-form-group">
                    <label for="lihatNamaSiswa">Nama Siswa</label>
                    <input type="text" id="lihatNamaSiswa" readonly>
                </div>
                <div class="tmt-form-group">
                    <label for="lihatStatusPengumpulan">Status Pengumpulan</label>
                    <input type="text" id="lihatStatusPengumpulan" readonly>
                </div>
            </div>

            <div class="tmt-upload-right">
                <div class="tmt-form-group">
                    <label for="lihatDeskripsi">Deskripsi</label>
                    <textarea id="lihatDeskripsi" readonly placeholder="-"></textarea>
                </div>

                <div class="tmt-form-group">
                    <label for="lihatNilai">Nilai</label>
                    <input type="number" id="lihatNilai" min="0" max="100" step="1"
                           placeholder="Masukkan nilai (0-100)" required>
                </div>

                <div class="tmt-upload-actions">
                    <button type="button" class="tmt-cancel-btn" onclick="tutupLihat()">Batal</button>
                    <button type="submit" class="tmt-send-btn">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
const searchTugas = document.getElementById('searchTugas');
const filterKelas = document.getElementById('filterKelas');
const sortTugas = document.getElementById('sortTugas');
const tugasTable = document.getElementById('tugasTable');
const tugasKosong = document.getElementById('tugasKosong');

function filterTugas() {
    const keyword = searchTugas.value.toLowerCase().trim();
    const kelas = filterKelas.value;
    const rows = [...tugasTable.querySelectorAll('tr')];

    rows.forEach(row => {
        row.hidden = !(
            row.dataset.judul.includes(keyword) &&
            (!kelas || row.dataset.kelas === kelas)
        );
    });

    const visibleRows = rows.filter(row => !row.hidden);

    visibleRows.sort((a, b) => {
        if (sortTugas.value === 'terbaru') {
            return b.dataset.tanggal.localeCompare(a.dataset.tanggal);
        }

        if (sortTugas.value === 'terlama') {
            return a.dataset.tanggal.localeCompare(b.dataset.tanggal);
        }

        return a.dataset.judul.localeCompare(b.dataset.judul);
    });

    visibleRows.forEach(row => tugasTable.appendChild(row));

    tugasKosong.hidden = visibleRows.length > 0;
}

searchTugas.addEventListener('input', filterTugas);
filterKelas.addEventListener('change', filterTugas);
sortTugas.addEventListener('change', filterTugas);

function bukaUpload() {
    document.getElementById('modalUpload').hidden = false;
}

function tutupUpload() {
    document.getElementById('modalUpload').hidden = true;
}


// ===== MODAL LIHAT TUGAS =====
let currentNilaiRow = null;

function lihatTugas(btn) {
    const row = btn.closest('tr');

    const judul = row.children[0].textContent.trim();
    const kursus = row.children[1].textContent.trim();
    const siswa = row.children[2].textContent.trim();
    const jenis = row.dataset.jenis || 'tugas';
    const deskripsi = row.dataset.deskripsi || '-';

    document.getElementById('lihatNamaTugas').value = judul;
    document.getElementById('lihatJenisTugas').value = jenis;
    document.getElementById('lihatKursus').value = kursus;
    document.getElementById('lihatNamaSiswa').value = siswa;
    document.getElementById('lihatStatusPengumpulan').value = row.dataset.statusPengumpulan || 'tidak kumpul';
    document.getElementById('lihatDeskripsi').value = deskripsi;

    currentNilaiRow = row;
    document.getElementById('lihatNilai').value = row.dataset.nilai || '';

    document.getElementById('modalLihat').hidden = false;
}

function tutupLihat() {
    document.getElementById('modalLihat').hidden = true;
}

// Simpan nilai (simulasi di browser, tanpa backend)
document.getElementById('formNilai').addEventListener('submit', function(e) {
    e.preventDefault();
    if (!currentNilaiRow) return;

    const nilai = parseInt(document.getElementById('lihatNilai').value, 10);
    if (isNaN(nilai) || nilai < 0 || nilai > 100) return;

    currentNilaiRow.dataset.nilai = nilai;
    alert('Nilai berhasil disimpan!');
    tutupLihat();
});

// Simulasi upload di browser, tanpa backend.
function buatBarisTugas(data) {
    const row = document.createElement('tr');
    row.dataset.kelas = data.kursus;
    row.dataset.judul = data.judul.toLowerCase();
    row.dataset.tanggal = data.tanggalISO;
    row.dataset.deadline = data.deadline || '';
    row.dataset.statusPengumpulan = 'tidak kumpul';
    row.dataset.jenis = data.jenis;
    row.dataset.deskripsi = data.deskripsi || '-';

    [data.judul, data.kursus, data.siswa, data.tanggalTampil].forEach(text => {
        const td = document.createElement('td');
        td.textContent = text;
        row.appendChild(td);
    });

    const action = document.createElement('td');
    action.className = 'tmt-action-group';

    [['Detail', 'bukaDetail'], ['Lihat', 'lihatTugas']].forEach(([label, fn]) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'tmt-view-btn';
        btn.textContent = label;
        btn.addEventListener('click', () => window[fn](btn));
        action.appendChild(btn);
    });

    row.appendChild(action);
    return row;
}

document.getElementById('formUpload').addEventListener('submit', function(e) {
    e.preventDefault();

    const judul = document.getElementById('judulBaru').value.trim();
    const kursus = document.getElementById('kelasBaru').value;
    const deadline = document.getElementById('deadlineBaru').value;

    if (!judul || !kursus || !deadline) return;
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

    tugasTable.prepend(buatBarisTugas({
        judul,
        kursus,
        siswa: document.getElementById('siswa').value,
        jenis: document.getElementById('jenisTgs').value,
        deskripsi: document.getElementById('deskripsiBaru').value.trim(),
        deadline,
        tanggalISO,
        tanggalTampil
    }));

    filterKelas.value = '';
    sortTugas.value = 'terbaru';
    searchTugas.value = '';

    filterTugas();
    tutupUpload();
    this.reset();

    alert('Tugas ditambahkan untuk pratinjau.');
});

let currentEditingRow = null;

// --- FUNGSI MODAL DETAIL & EDIT ---
const detailBox = document.getElementById('detailBox');
const detailTextFields = ['detailNamaTugas', 'detailDeskripsi', 'detailDeadline'];
const detailSelectFields = ['detailJenisTugas', 'detailKursus', 'detailNamaSiswa'];

function isiDetail(row) {
    document.getElementById('detailNamaTugas').value = row.children[0].textContent.trim();
    document.getElementById('detailJenisTugas').value = row.dataset.jenis || 'tugas';
    document.getElementById('detailKursus').value = row.children[1].textContent.trim();
    document.getElementById('detailNamaSiswa').value = row.children[2].textContent.trim();
    document.getElementById('detailDeadline').value = row.dataset.deadline || '';
    document.getElementById('detailDeskripsi').value = row.dataset.deskripsi || '';
}

function bukaDetail(btn) {
    currentEditingRow = btn.closest('tr');
    isiDetail(currentEditingRow);
    batalEditMode();
    document.getElementById('modalDetail').hidden = false;
}

function tutupDetail() {
    document.getElementById('modalDetail').hidden = true;
    batalEditMode();
}

// tombol panah kembali: saat edit -> keluar dari edit, saat detail -> tutup
function kembaliDetail() {
    if (detailBox.classList.contains('is-editing')) {
        batalEditMode();
    } else {
        tutupDetail();
    }
}

function aktifkanEditMode() {
    detailTextFields.forEach(id => document.getElementById(id).removeAttribute('readonly'));
    detailSelectFields.forEach(id => document.getElementById(id).removeAttribute('disabled'));

    detailBox.classList.add('is-editing');
    document.getElementById('detailJudulModal').innerHTML = 'Edit<br>Tugas';
    document.getElementById('actionDetail').hidden = true;
    document.getElementById('actionEdit').hidden = false;

    document.getElementById('detailNamaTugas').focus();
}

function batalEditMode() {
    // kembalikan isi ke data terakhir yang tersimpan
    if (currentEditingRow) isiDetail(currentEditingRow);

    detailTextFields.forEach(id => document.getElementById(id).setAttribute('readonly', true));
    detailSelectFields.forEach(id => document.getElementById(id).setAttribute('disabled', true));

    detailBox.classList.remove('is-editing');
    document.getElementById('detailJudulModal').innerHTML = 'Detail<br>Tugas';
    document.getElementById('actionDetail').hidden = false;
    document.getElementById('actionEdit').hidden = true;
}


// Handler Submit Form Edit
document.getElementById('formEditTugas').addEventListener('submit', function(e) {
    e.preventDefault();

    if (!currentEditingRow) return;

    const judulBaru = document.getElementById('detailNamaTugas').value.trim();
    const jenisBaru = document.getElementById('detailJenisTugas').value;
    const kursusBaru = document.getElementById('detailKursus').value;
    const siswaBaru = document.getElementById('detailNamaSiswa').value;
    const deskripsiBaru = document.getElementById('detailDeskripsi').value;
    const deadlineBaru = document.getElementById('detailDeadline').value;

    // Update kolom di tabel
    currentEditingRow.children[0].textContent = judulBaru;
    currentEditingRow.children[1].textContent = kursusBaru;
    currentEditingRow.children[2].textContent = siswaBaru;

    // Update dataset row
    currentEditingRow.dataset.judul = judulBaru.toLowerCase();
    currentEditingRow.dataset.kelas = kursusBaru;
    currentEditingRow.dataset.jenis = jenisBaru;
    currentEditingRow.dataset.deskripsi = deskripsiBaru;
    currentEditingRow.dataset.deadline = deadlineBaru;


    alert('Data tugas berhasil diperbarui!');
    batalEditMode(); // muat ulang tampilan dari data yang baru disimpan
    tutupDetail();
    filterTugas(); // Refresh filter tabel
});

</script>
@endsection