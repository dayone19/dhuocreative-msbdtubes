@extends('layouts.tentor')

@section('title', 'Daftar Tugas')

@section('content')
<div class="tmt-page">

    <h1 class="tmt-title">Daftar Tugas</h1>

    @if (session('sukses'))
        <p style="background:#e3f6e8;color:#1d6b35;padding:10px 14px;border-radius:10px;margin:10px 0;">{{ session('sukses') }}</p>
    @endif
    @if ($errors->any())
        <p style="background:#fde8e8;color:#a12222;padding:10px 14px;border-radius:10px;margin:10px 0;">{{ $errors->first() }}</p>
    @endif
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
            @foreach ($kelasTentor as $k)
                <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
            @endforeach
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
                    <th>Kelas</th>
                    <th>Tanggal<br>Upload</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="tugasTable">
                @foreach ($tugas as $item)
                    <tr data-kelas="{{ $item->nama_kelas }}"
                        data-judul="{{ mb_strtolower($item->judul) }}"
                        data-tanggal="{{ date('Y-m-d', strtotime($item->created_at)) }}"
                        data-judul-asli="{{ $item->judul }}"
                        data-id-kelas="{{ $item->id_kelas }}"
                        data-tipe="{{ $item->tipe }}"
                        data-deskripsi="{{ $item->deskripsi }}"
                        data-deadline="{{ date('Y-m-d\TH:i', strtotime($item->deadline)) }}"
                        data-file-nama="{{ preg_replace('/^\d+_/', '', $item->file_tugas) }}"
                        data-url-update="{{ route('tentor.tugas.update', ['tugas' => $item->id_tugas]) }}">
                        <td>{{ $item->judul }}</td>
                        <td>{{ $item->nama_kelas }}</td>
                        <td>{{ date('n/j/y', strtotime($item->created_at)) }}</td>
                        <td>
                            <div class="tmt-aksi">
                                <a href="{{ route('tentor.nilai', ['tugas' => $item->id_tugas]) }}"
                                class="tmt-view-btn">
                                    Lihat
                                </a>

                                <button type="button"
                                        class="tmt-view-btn"
                                        onclick="editTugas(this.closest('tr'))">
                                    Edit
                                </button>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="tmt-empty" id="tugasKosong" {{ count($tugas) > 0 ? 'hidden' : '' }}>
            {{ count($tugas) > 0 ? 'Tugas tidak ditemukan.' : 'Belum ada tugas.' }}
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

            <h2 id="judulModal">Upload<br>Tugas</h2>
        </div>

        {{-- form sungguhan: action & method diatur JavaScript (Upload = POST ke tugas.simpan, Edit = PUT ke tugas.update) --}}
        <form id="formUpload" class="tmt-upload-form" method="POST"
              action="{{ route('tentor.tugas.simpan') }}" enctype="multipart/form-data">
            @csrf
            {{-- hanya aktif saat Edit: membuat Laravel menganggap request ini PUT --}}
            <input type="hidden" name="_method" value="PUT" id="metodeForm" disabled>
            <div class="tmt-upload-left">
                <div class="tmt-form-group">
                    <label for="judulBaru">Judul Tugas</label>
                    <input type="text"
                           id="judulBaru"
                           name="judul"
                           value="{{ old('judul') }}"
                           placeholder="Masukkan judul tugas"
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

                {{-- pilihan baru: tipe tugas (kolom "tipe" di tabel tugas) --}}
                <div class="tmt-form-group">
                    <label for="tipeBaru">Tipe</label>
                    <select id="tipeBaru" name="tipe" required>
                        <option value="tugas" {{ old('tipe') === 'tugas' ? 'selected' : '' }}>Tugas</option>
                        <option value="projek" {{ old('tipe') === 'projek' ? 'selected' : '' }}>Projek</option>
                    </select>
                </div>

                {{-- isian baru: batas waktu pengumpulan (kolom "deadline" wajib diisi di database) --}}
                <div class="tmt-form-group">
                    <label for="deadlineBaru">Deadline</label>
                    <input type="datetime-local"
                           id="deadlineBaru"
                           name="deadline"
                           value="{{ old('deadline') }}"
                           required>
                </div>

                <div class="tmt-form-group">
                    <label for="deskripsiBaru">Deskripsi Tugas</label>
                    <textarea id="deskripsiBaru"
                              name="deskripsi"
                              placeholder="Tulis deskripsi tugas..."
                              required>{{ old('deskripsi') }}</textarea>
                </div>
            </div>

            <div class="tmt-upload-right">
                <label class="tmt-file-label" for="fileBaru">
                    File Pendukung
                </label>
                <label for="fileBaru" class="tmt-dropzone">
                    <input type="file"
                           id="fileBaru"
                           name="file_tugas"
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

const form = document.getElementById('formUpload');
const metodeForm = document.getElementById('metodeForm');

function bukaUpload() {
    form.reset();
    form.action = "{{ route('tentor.tugas.simpan') }}";
    metodeForm.disabled = true;
    document.getElementById('judulModal').innerHTML = 'Upload<br>Tugas';
    document.getElementById('fileBaru').required = true;
    document.getElementById('namaFile').textContent = 'Belum ada file dipilih';
    document.getElementById('modalUpload').hidden = false;
}

function editTugas(row) {
    form.reset();
    form.action = row.dataset.urlUpdate;
    metodeForm.disabled = false;
    document.getElementById('judulModal').innerHTML = 'Edit<br>Tugas';
    document.getElementById('judulBaru').value = row.dataset.judulAsli;
    document.getElementById('kelasBaru').value = row.dataset.idKelas;
    document.getElementById('tipeBaru').value = row.dataset.tipe;
    document.getElementById('deskripsiBaru').value = row.dataset.deskripsi;
    document.getElementById('deadlineBaru').value = row.dataset.deadline;
    document.getElementById('fileBaru').required = false;
    document.getElementById('namaFile').textContent =
        'File saat ini: ' + row.dataset.fileNama + ' (pilih file baru untuk mengganti)';
    document.getElementById('modalUpload').hidden = false;
}

function tutupUpload() {
    document.getElementById('modalUpload').hidden = true;
}

function lihatTugas(judul) {
    document.getElementById('judulDetail').textContent = judul;
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

<style>
.tmt-aksi{
    display:flex;
    justify-content:center;
    align-items:center;
    gap:6px;
    flex-wrap:nowrap;
}

.tmt-aksi .tmt-view-btn{
    width:auto;
    min-width:0;
    height:32px;
    padding:0 14px;
    font-size:13px;
    border-radius:16px;
    white-space:nowrap;
    display:inline-flex;
    align-items:center;
    justify-content:center;
}
</style>
@endsection