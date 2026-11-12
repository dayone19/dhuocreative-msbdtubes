@extends('layouts.tentor')

@section('title', 'Daftar Tugas')

@section('content')
<div class="tmt-page">

    <h1 class="tmt-title">Daftar Tugas</h1>

    @if (session('sukses'))
        <p class="tmt-flash tmt-flash-sukses" role="status"
           style="padding:12px 16px;border-radius:12px;margin:0 0 16px;background:#e6f4f1;color:#14524b">
            {{ session('sukses') }}
        </p>
    @endif
    @if ($errors->any())
        <div class="tmt-flash tmt-flash-error" role="alert"
             style="padding:12px 16px;border-radius:12px;margin:0 0 16px;background:#fdeceb;color:#8a1f17">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="GET" action="{{ route('tentor.tugas') }}" class="tmt-toolbar" id="formFilterTugas">

        <div class="tmt-search">
            <input type="text" name="q" value="{{ $q }}" id="searchTugas"
                   placeholder="Cari tugas...">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="#ae9154" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                </svg>
            </span>
        </div>

        <select name="kursus" id="filterKelas" class="tmt-select" onchange="this.form.submit()">
            <option value="">Semua Kursus</option>
            @foreach ($kursusFilter as $k)
                <option value="{{ $k }}" @selected($kursus === $k)>{{ $k }}</option>
            @endforeach
        </select>

        <select name="sort" id="sortTugas" class="tmt-select" onchange="this.form.submit()">
            <option value="terbaru" @selected($sort === 'terbaru')>Terbaru</option>
            <option value="terlama" @selected($sort === 'terlama')>Terlama</option>
            <option value="az" @selected($sort === 'az')>Nama A-Z</option>
        </select>

        <button type="submit" class="tmt-view-btn" style="align-self:center">Cari</button>

        <button type="button" class="tmt-upload-btn"
                onclick="bukaUpload()">
            <span class="t-plus">+</span>
            <span>Upload<br>Tugas</span>
        </button>

    </form>

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

            @forelse ($tugas as $t)
            <tr data-id="{{ $t['id'] }}"
                data-id-kelas="{{ $t['id_kelas'] }}"
                data-kelas="{{ $t['kursus'] }}"
                data-judul="{{ $t['judul_lower'] }}"
                data-tanggal="{{ $t['tanggal'] }}"
                data-deadline="{{ $t['deadline'] }}"
                data-status-pengumpulan="{{ $t['status'] }}"
                data-jenis="{{ $t['tipe'] }}"
                data-deskripsi="{{ $t['deskripsi'] }}"
                data-id-pengumpulan="{{ $t['id_pengumpulan'] }}"
                data-nilai="{{ $t['nilai'] }}"
                data-file-nama="{{ $t['file_nama'] }}"
                data-file-url="{{ $t['file_url'] }}">
                <td>{{ $t['judul'] }}</td>
                <td>{{ $t['kursus'] }}</td>
                <td>{{ $t['siswa'] }}</td>
                <td>{{ $t['tanggal_tampil'] }}</td>
                <td class="tmt-action-group">
                    <button type="button" class="tmt-view-btn" onclick="bukaDetail(this)">Detail</button>
                    <button type="button" class="tmt-view-btn" onclick="lihatTugas(this)">Lihat</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="tmt-empty" style="text-align:center;padding:24px">
                    {{ ($q || $kursus) ? 'Tugas tidak ditemukan.' : 'Belum ada tugas.' }}
                </td>
            </tr>
            @endforelse

          </tbody>
        </table>

        {{-- Pagination server-side (style project) --}}
        @if ($tugas->hasPages())
        <nav class="tmt-pagination" aria-label="Navigasi halaman">
            @if ($tugas->onFirstPage())
                <button type="button" disabled aria-label="Sebelumnya">&lt;</button>
            @else
                <a href="{{ $tugas->previousPageUrl() }}" style="text-decoration:none">
                    <button type="button" aria-label="Sebelumnya">&lt;</button>
                </a>
            @endif

            @php
                $current = $tugas->currentPage();
                $last    = $tugas->lastPage();
                $pages   = collect([1, $last, $current]);
                for ($i = $current - 1; $i <= $current + 1; $i++) {
                    if ($i >= 1 && $i <= $last) $pages->push($i);
                }
                if ($current <= 4) {
                    foreach ([2, 3, 4] as $n) if ($n < $last) $pages->push($n);
                }
                if ($current >= $last - 3) {
                    foreach ([$last - 1, $last - 2, $last - 3] as $n) if ($n > 1) $pages->push($n);
                }
                $pages = $pages->unique()->sort()->values();
                $prev = 0;
            @endphp

            @foreach ($pages as $n)
                @if ($prev && $n - $prev > 1)
                    <span class="tmt-page-ellipsis">…</span>
                @endif
                @if ($n == $current)
                    <button type="button" class="is-active" aria-current="page">{{ $n }}</button>
                @else
                    <a href="{{ $tugas->url($n) }}" style="text-decoration:none">
                        <button type="button">{{ $n }}</button>
                    </a>
                @endif
                @php $prev = $n; @endphp
            @endforeach

            @if ($tugas->hasMorePages())
                <a href="{{ $tugas->nextPageUrl() }}" style="text-decoration:none">
                    <button type="button" aria-label="Berikutnya">&gt;</button>
                </a>
            @else
                <button type="button" disabled aria-label="Berikutnya">&gt;</button>
            @endif
        </nav>
        @endif
    </div>

    <style>
    .tmt-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-top: 22px;
        flex-wrap: wrap;
        padding: 4px 0;
    }
    .tmt-pagination button {
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        border: 1.5px solid #e8d9b8;
        border-radius: 12px;
        background: #fffaf0;
        color: #5a4a2a;
        font: inherit;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s, color .15s, border-color .15s, transform .1s;
        box-shadow: 0 1px 2px rgba(0,0,0,.04);
    }
    .tmt-pagination button:hover:not(:disabled):not(.is-active) {
        background: #f5e9cf;
        border-color: #b29458;
        color: #3d321c;
    }
    .tmt-pagination button.is-active {
        background: #f27640;
        border-color: #f27640;
        color: #fff;
        box-shadow: 0 2px 8px rgba(242,118,64,.35);
    }
    .tmt-pagination button:disabled {
        opacity: .45;
        cursor: not-allowed;
    }
    .tmt-pagination .tmt-page-ellipsis {
        min-width: 28px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #b29458;
        font-weight: 700;
        font-size: 15px;
        user-select: none;
    }
    </style>

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

        <form id="formUpload" class="tmt-upload-form" method="POST" action="{{ route('tentor.tugas.simpan') }}">
            @csrf
            <div class="tmt-upload-left">
                <div class="tmt-form-group">
                    <label for="judulBaru">Judul Tugas</label>
                    <input type="text"
                           id="judulBaru"
                           name="judul"
                           maxlength="150"
                           placeholder="Masukkan judul tugas"
                           required>
                </div>

                <div class="tmt-form-group">
                    <label for="jenisTgs">Jenis Tugas</label>
                    <select id="jenisTgs" name="tipe" required>
                        <option value="">Pilih jenis tugas</option>
                        <option value="tugas">Tugas</option>
                        <option value="projek">Projek</option>
                    </select>
                </div>

                <div class="tmt-form-group">
                    <label for="kelasBaru">Kursus</label>
                    {{-- hanya untuk memfilter pilihan siswa, tidak ikut terkirim --}}
                    <select id="kelasBaru" required>
                        <option value="">Pilih kursus</option>
                        @foreach ($kelasOptions->pluck('kursus')->unique() as $kursus)
                            <option value="{{ $kursus }}">{{ $kursus }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="tmt-form-group">
                    <label for="siswa">Nama Siswa</label>
                    {{-- value = id_kelas; opsi diisi JS sesuai kursus yang dipilih --}}
                    <select id="siswa" name="id_kelas" required>
                        <option value="">Pilih kursus dulu</option>
                    </select>
                </div>
            </div>

            <div class="tmt-upload-right">
                <div class="tmt-form-group">
                    <label for="deadlineBaru">Deadline Tugas</label>
                    <input type="datetime-local" id="deadlineBaru" name="deadline" required>
                </div>

                <div class="tmt-form-group">
                    <label for="deskripsiBaru">Deskripsi Tugas</label>
                    <textarea id="deskripsiBaru"
                              name="deskripsi"
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

        {{-- action diisi JS (butuh id tugas) --}}
        <form id="formEditTugas" class="tmt-upload-form" method="POST" action="#">
            @csrf
            @method('PUT')
            <div class="tmt-upload-left">
                <div class="tmt-form-group">
                    <label for="detailNamaTugas">Nama Tugas</label>
                    <input type="text" id="detailNamaTugas" name="judul" maxlength="150" placeholder="Masukkan nama tugas" readonly required>
                </div>

                <div class="tmt-form-group">
                    <label for="detailJenisTugas">Jenis Tugas</label>
                    <select id="detailJenisTugas" name="tipe" disabled required>
                        <option value="tugas">Tugas</option>
                        <option value="projek">Projek</option>
                    </select>
                </div>

                {{-- Kursus & siswa tidak bisa diganti (tugas terikat ke kelas); opsi diisi JS --}}
                <div class="tmt-form-group">
                    <label for="detailKursus">Kursus</label>
                    <select id="detailKursus" disabled></select>
                </div>

                <div class="tmt-form-group">
                    <label for="detailNamaSiswa">Nama Siswa</label>
                    <select id="detailNamaSiswa" disabled></select>
                </div>
            </div>

            <div class="tmt-upload-right">
                <div class="tmt-form-group">
                    <label for="detailDeadline">Deadline Tugas</label>
                    <input type="datetime-local" id="detailDeadline" name="deadline" readonly required>
                </div>

                <div class="tmt-form-group">
                    <label for="detailDeskripsi">Deskripsi</label>
                    <textarea id="detailDeskripsi" name="deskripsi" placeholder="Tulis deskripsi tugas..." readonly></textarea>
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

        {{-- action diisi JS (butuh id pengumpulan) --}}
        <form id="formNilai" class="tmt-upload-form" method="POST" action="#">
            @csrf

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

                {{-- Tempat File Tugas Siswa yang Di-upload --}}
                <div class="tmt-form-group">
                    <label>File Tugas Siswa</label>
                    <div id="containerFileSiswa">
                        <span style="color: #666; font-style: italic;">Siswa belum mengunggah file.</span>
                    </div>
                </div>

                <div class="tmt-form-group">
                    <label for="lihatNilai">Nilai</label>
                    <input type="number" id="lihatNilai" name="nilai" min="0" max="100" step="1"
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
// data dari database (TugasController@index)
const kelasOptions = @json($kelasOptions);
const updateUrlTemplate = "{{ route('tentor.tugas.update', ['tugas' => '__ID__']) }}";
const nilaiUrlTemplate = "{{ route('tentor.tugas.nilai', ['pengumpulan' => '__ID__']) }}";

// Pagination & filter sudah di-handle controller (server-side).
// JS di bawah hanya untuk modal upload / detail / nilai.

function bukaUpload() {
    document.getElementById('modalUpload').hidden = false;
}

function tutupUpload() {
    document.getElementById('modalUpload').hidden = true;
}

document.getElementById('kelasBaru').addEventListener('change', function () {
    const sel = document.getElementById('siswa');
    const list = kelasOptions.filter(k => k.kursus === this.value);

    sel.innerHTML = '';
    sel.add(new Option(list.length ? 'Pilih siswa' : 'Pilih kursus dulu', ''));

    const hitung = {};
    list.forEach(k => { hitung[k.siswa] = (hitung[k.siswa] || 0) + 1; });
    list.forEach(k => {
        sel.add(new Option(hitung[k.siswa] > 1 ? `${k.siswa} (${k.jenis})` : k.siswa, k.id_kelas));
    });
});

// ===== MODAL LIHAT TUGAS =====
function lihatTugas(btn) {
    const row = btn.closest('tr');

    const judul = row.children[0].textContent.trim();
    const kursus = row.children[1].textContent.trim();
    const siswa = row.children[2].textContent.trim();
    const jenis = row.dataset.jenis || 'tugas';
    const deskripsi = row.dataset.deskripsi || '-';

    const namaFile = row.dataset.fileNama;
    const urlFile = row.dataset.fileUrl;
    const containerFile = document.getElementById('containerFileSiswa');
    containerFile.replaceChildren();

    if (namaFile && urlFile) {
        const a = document.createElement('a');
        a.href = urlFile;
        a.className = 'tmt-file-pill';
        a.target = '_blank';
        a.textContent = namaFile;
        containerFile.appendChild(a);
    } else {
        const span = document.createElement('span');
        span.style.cssText = 'color: #666; font-style: italic;';
        span.textContent = 'Siswa belum mengunggah file.';
        containerFile.appendChild(span);
    }

    document.getElementById('lihatNamaTugas').value = judul;
    document.getElementById('lihatJenisTugas').value = jenis;
    document.getElementById('lihatKursus').value = kursus;
    document.getElementById('lihatNamaSiswa').value = siswa;
    document.getElementById('lihatStatusPengumpulan').value = row.dataset.statusPengumpulan || 'tidak kumpul';
    document.getElementById('lihatDeskripsi').value = deskripsi;

    const form = document.getElementById('formNilai');
    const nilaiInput = document.getElementById('lihatNilai');
    const simpanBtn = form.querySelector('[type=submit]');
    const adaPengumpulan = !!row.dataset.idPengumpulan;

    if (adaPengumpulan) {
        form.action = nilaiUrlTemplate.replace('__ID__', row.dataset.idPengumpulan);
    } else {
        form.action = '#';
    }
    nilaiInput.disabled = !adaPengumpulan;
    simpanBtn.disabled = !adaPengumpulan;
    nilaiInput.placeholder = adaPengumpulan
        ? 'Masukkan nilai (0-100)'
        : 'Belum bisa dinilai (siswa belum mengumpulkan)';
    nilaiInput.value = row.dataset.nilai || '';

    document.getElementById('modalLihat').hidden = false;
}

function tutupLihat() {
    document.getElementById('modalLihat').hidden = true;
}

let currentEditingRow = null;

// --- FUNGSI MODAL DETAIL & EDIT ---
const detailBox = document.getElementById('detailBox');
const detailTextFields = ['detailNamaTugas', 'detailDeskripsi', 'detailDeadline'];
const detailSelectFields = ['detailJenisTugas']; // kursus & siswa tidak bisa diubah

function isiSelectTetap(id, value, label) {
    const sel = document.getElementById(id);
    sel.innerHTML = '';
    sel.add(new Option(label, value, true, true));
}

function isiDetail(row) {
    document.getElementById('detailNamaTugas').value = row.children[0].textContent.trim();
    document.getElementById('detailJenisTugas').value = row.dataset.jenis || 'tugas';
    isiSelectTetap('detailKursus', row.dataset.kelas, row.children[1].textContent.trim());
    isiSelectTetap('detailNamaSiswa', row.dataset.idKelas, row.children[2].textContent.trim());
    document.getElementById('detailDeadline').value = row.dataset.deadline || '';
    document.getElementById('detailDeskripsi').value = row.dataset.deskripsi || '';
}

function bukaDetail(btn) {
    currentEditingRow = btn.closest('tr');
    isiDetail(currentEditingRow);
    document.getElementById('formEditTugas').action =
        updateUrlTemplate.replace('__ID__', currentEditingRow.dataset.id);
    batalEditMode();
    document.getElementById('modalDetail').hidden = false;
}

function tutupDetail() {
    document.getElementById('modalDetail').hidden = true;
    batalEditMode();
}

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
    if (currentEditingRow) isiDetail(currentEditingRow);

    detailTextFields.forEach(id => document.getElementById(id).setAttribute('readonly', true));
    detailSelectFields.forEach(id => document.getElementById(id).setAttribute('disabled', true));

    detailBox.classList.remove('is-editing');
    document.getElementById('detailJudulModal').innerHTML = 'Detail<br>Tugas';
    document.getElementById('actionDetail').hidden = false;
    document.getElementById('actionEdit').hidden = true;
}


</script>
@endsection