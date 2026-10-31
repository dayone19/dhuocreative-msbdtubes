@extends('layouts.tentor')

@section('title', 'Daftar Materi')

@section('content')
<div class="tmt-page">
    <h1 class="tmt-title">Daftar Materi</h1>

    @if (session('sukses'))
        <p style="background:#e3f6e8;color:#1d6b35;padding:10px 14px;border-radius:10px;margin:10px 0;">{{ session('sukses') }}</p>
    @endif
    @if ($errors->any())
        <p style="background:#fde8e8;color:#a12222;padding:10px 14px;border-radius:10px;margin:10px 0;">{{ $errors->first() }}</p>
    @endif

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
            @foreach ($kelasTentor as $k)
                <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
            @endforeach
        </select>

        <select id="sortMateri" class="tmt-select">
            <option value="terbaru">Terbaru</option>
            <option value="terlama">Terlama</option>
            <option value="az">Nama A-Z</option>
        </select>

        <button type="button" class="tmt-upload-btn"
                onclick="bukaUpload()">
            <span class="t-plus">+</span>
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
                @foreach ($materi as $item)
                    <tr data-kelas="{{ $item['nama_kelas'] }}"
                        data-judul="{{ mb_strtolower($item['judul']) }}"
                        data-tanggal="{{ date('Y-m-d', strtotime($item['dibuat'])) }}"
                        data-deskripsi="{{ $item['deskripsi'] ?: '-' }}"
                        data-file="{{ $item['nama_file'] }}"
                        data-url="{{ route('tentor.materi.file', ['kelas' => $item['id_kelas'], 'token' => $item['token']]) }}"
                        data-unduh="{{ route('tentor.materi.file', ['kelas' => $item['id_kelas'], 'token' => $item['token'], 'unduh' => 1]) }}"
                        data-judul-asli="{{ $item['judul'] }}"
                        data-deskripsi-asli="{{ $item['deskripsi'] }}"
                        data-id-kelas="{{ $item['id_kelas'] }}"
                        data-url-update="{{ route('tentor.materi.update', ['kelas' => $item['id_kelas'], 'token' => $item['token']]) }}"
                        data-url-hapus="{{ route('tentor.materi.hapus', ['kelas' => $item['id_kelas'], 'token' => $item['token']]) }}">
                        <td>{{ $item['judul'] }}</td>
                        <td>{{ $item['nama_kelas'] }}</td>
                        <td>{{ date('n/j/y', strtotime($item['dibuat'])) }}</td>
                        <td>
                            <div class="tmt-aksi">
                                <button class="tmt-view-btn" onclick="lihatMateri(this)">
                                    Lihat
                                </button>
                                @if ($item['milik_sendiri'])
                                    <button type="button" class="tmt-view-btn" onclick="editMateri(this.closest('tr'))">
                                        Edit
                                    </button>
                                    <button type="button" class="tmt-view-btn" onclick="hapusMateri(this.closest('tr'))">
                                        Hapus
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="tmt-empty" id="materiKosong" {{ count($materi) > 0 ? 'hidden' : '' }}>
            {{ count($materi) > 0 ? 'Materi tidak ditemukan.' : 'Belum ada materi. Klik tombol Upload Materi untuk menambah.' }}
        </div>
    </div>

</div>

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

            <h2 id="judulModal">Upload<br>Materi</h2>
        </div>

        <form id="formUpload" class="tmt-upload-form" method="POST"
              action="{{ route('tentor.materi.simpan') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT" id="metodeForm" disabled>
            <div class="tmt-upload-left">
                <div class="tmt-form-group">
                    <label for="judulBaru">Judul Materi</label>
                    <input type="text"
                           id="judulBaru"
                           name="judul"
                           value="{{ old('judul') }}"
                           placeholder="Masukkan judul materi"
                           required>
                </div>

                <div class="tmt-form-group">
                    <label for="kelasBaru">Kelas</label>
                    <select id="kelasBaru" name="id_kelas" required>
                        <option value="">Pilih kelas</option>
                        @foreach ($kelasTentor as $k)
                            <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="tmt-form-group">
                    <label for="deskripsiBaru">Deskripsi Materi (Opsional)</label>
                    <textarea id="deskripsiBaru"
                              name="deskripsi"
                              placeholder="Tulis deskripsi materi...">{{ old('deskripsi') }}</textarea>
                </div>
            </div>

            <div class="tmt-upload-right">
                <label class="tmt-file-label" for="fileBaru">
                    File Materi
                </label>
                <label for="fileBaru" class="tmt-dropzone">
                    <input type="file"
                           id="fileBaru"
                           name="file_materi"
                           accept=".pdf,.doc,.docx,.ppt,.pptx,.zip">
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
                <div class="tmt-file" style="background-color: #fafafa;">
                    <a class="tmt-file-name" id="fileLihatNama" href="#" target="_blank" style="text-decoration:none;">
                        Belum ada file
                    </a>
                </div>

                <div class="tmt-upload-actions">
                    <button type="button"
                            class="tmt-cancel-btn"
                            onclick="tutupLihat()">
                        Tutup
                    </button>
                    <a id="unduhLihat" href="#" class="tmt-send-btn"
                       style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">
                        Unduh
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Tombol aksi (Lihat / Edit / Hapus) dibuat kecil dan sejajar ke samping, tidak turun ke bawah --}}
<style>
    .tmt-aksi {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
        flex-wrap: nowrap;          /* jangan pernah turun ke baris berikutnya */
    }
    .tmt-aksi .tmt-view-btn {
        width: auto;                /* lebar mengikuti tulisan, bukan 106px */
        min-width: 0;
        height: 32px;
        padding: 0 14px;
        font-size: 13px;
        border-radius: 16px;
        white-space: nowrap;
    }
</style>

<form id="formHapus" method="POST" action="" hidden>
    @csrf
    @method('DELETE')
</form>

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

const form = document.getElementById('formUpload');
const metodeForm = document.getElementById('metodeForm');

function bukaUpload() {
    form.reset();
    form.action = "{{ route('tentor.materi.simpan') }}";
    metodeForm.disabled = true;
    document.getElementById('judulModal').innerHTML = 'Upload<br>Materi';
    document.getElementById('kelasBaru').disabled = false;
    document.getElementById('fileBaru').required = true;
    document.getElementById('namaFile').textContent = 'Belum ada file dipilih';
    document.getElementById('modalUpload').hidden = false;
}

function editMateri(row) {
    form.reset();
    form.action = row.dataset.urlUpdate;
    metodeForm.disabled = false;
    document.getElementById('judulModal').innerHTML = 'Edit<br>Materi';
    document.getElementById('judulBaru').value = row.dataset.judulAsli;
    document.getElementById('kelasBaru').value = row.dataset.idKelas;
    document.getElementById('kelasBaru').disabled = true;
    document.getElementById('deskripsiBaru').value = row.dataset.deskripsiAsli;
    document.getElementById('fileBaru').required = false;
    document.getElementById('namaFile').textContent =
        'File saat ini: ' + row.dataset.file + ' (pilih file baru untuk mengganti)';
    document.getElementById('modalUpload').hidden = false;
}

function hapusMateri(row) {
    if (!confirm('Hapus materi "' + row.dataset.judulAsli + '"? Materi yang sudah dihapus tidak bisa dikembalikan.')) {
        return;
    }
    const formHapus = document.getElementById('formHapus');
    formHapus.action = row.dataset.urlHapus;
    formHapus.submit();
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
    document.getElementById('fileLihatNama').href = row.dataset.url;   
    document.getElementById('unduhLihat').href = row.dataset.unduh;   

    document.getElementById('modalLihat').hidden = false;
}

function tutupLihat() {
    document.getElementById('modalLihat').hidden = true;
}

document.getElementById('fileBaru').addEventListener('change', function () {
    document.getElementById('namaFile').textContent =
        this.files[0] ? this.files[0].name : 'Belum ada file dipilih';
});

@if ($errors->any() && old('_method') !== 'PUT')
    document.getElementById('fileBaru').required = true;
    document.getElementById('modalUpload').hidden = false;
@endif
</script>
@endsection