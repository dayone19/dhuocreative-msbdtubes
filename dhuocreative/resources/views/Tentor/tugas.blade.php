@extends('layouts.tentor')

@section('title', 'Daftar Tugas')

@section('content')
<div class="tmt-page">

    <h1 class="tmt-title">Daftar Tugas</h1>

    {{-- pesan sukses (hijau) atau error (merah) setelah menyimpan --}}
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
            <option value="">Kursus</option>
            @foreach ($namaKursus as $nk)
                <option value="{{ $nk }}">{{ $nk }}</option>
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
                    <th>Kursus</th>
                    <th>Siswa</th>
                    <th>Tanggal<br>Upload</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="tugasTable">
                {{-- satu baris per tugas (dari TugasController@index). data-* dipakai JavaScript untuk cari/filter/urut dan mengisi modal --}}
                @foreach ($tugas as $t)
                    <tr data-kelas="{{ $t->nama_kursus }}"
                        data-judul="{{ mb_strtolower($t->judul) }}"
                        data-tanggal="{{ date('Y-m-d', strtotime($t->created_at)) }}"
                        data-deadline="{{ date('Y-m-d\TH:i', strtotime($t->deadline)) }}"
                        data-status-pengumpulan="{{ str_replace('_', ' ', $t->status_pengumpulan ?? 'tidak kumpul') }}"
                        data-jenis="{{ $t->tipe }}"
                        data-deskripsi="{{ $t->deskripsi }}"
                        data-nilai="{{ $t->nilai !== null ? rtrim(rtrim(number_format((float) $t->nilai, 2, '.', ''), '0'), '.') : '' }}"
                        data-bisa-nilai="{{ in_array($t->status_pengumpulan, ['kumpul', 'terlambat']) ? '1' : '0' }}"
                        data-url-update="{{ route('tentor.tugas.update', ['tugas' => $t->id_tugas]) }}"
                        data-url-nilai="{{ route('tentor.tugas.nilai', ['tugas' => $t->id_tugas]) }}">
                        <td>{{ $t->judul }}</td>
                        <td>{{ $t->nama_kursus }}</td>
                        <td>{{ $t->nama_siswa }}</td>
                        <td>{{ date('n/j/y', strtotime($t->created_at)) }}</td>
                        <td class="tmt-action-group">
                            <button type="button" class="tmt-view-btn" onclick="bukaDetail(this)">Detail</button>
                            <button type="button" class="tmt-view-btn" onclick="lihatTugas(this)">Lihat</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="tmt-empty" id="tugasKosong" {{ count($tugas) > 0 ? 'hidden' : '' }}>
            {{ count($tugas) > 0 ? 'Tugas tidak ditemukan.' : 'Belum ada tugas. Klik tombol Upload Tugas untuk membuat.' }}
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

        {{-- form sungguhan: dikirim ke TugasController@simpan. form_asal dipakai untuk tahu modal mana yang dibuka lagi kalau validasi gagal --}}
        <form id="formUpload" class="tmt-upload-form" method="POST" action="{{ route('tentor.tugas.simpan') }}">
            @csrf
            <input type="hidden" name="form_asal" value="upload">
            <div class="tmt-upload-left">
                <div class="tmt-form-group">
                    <label for="judulBaru">Judul Tugas</label>
                    <input type="text"
                           id="judulBaru"
                           name="judul"
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
                    <select id="kelasBaru" name="id_kursus" required>
                        <option value="">Pilih kursus</option>
                        @foreach ($kursusTentor as $k)
                            <option value="{{ $k->id_kursus }}">{{ $k->nama_kursus }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="tmt-form-group">
                    <label for="siswa">Nama Siswa</label>
                    {{-- value = id kelas (kursus + siswa). data-kursus dipakai JavaScript untuk menyaring siswa sesuai kursus yang dipilih --}}
                    <select id="siswa" name="id_kelas" required>
                        <option value="">Pilih siswa</option>
                        @foreach ($kelasTentor as $k)
                            <option value="{{ $k->id_kelas }}" data-kursus="{{ $k->id_kursus }}">{{ $k->nama_siswa }}</option>
                        @endforeach
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

        {{-- form edit: action diisi JavaScript (alamat update tugas yang dipilih); selalu dikirim sebagai PUT --}}
        <form id="formEditTugas" class="tmt-upload-form" method="POST" action="">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="tmt-upload-left">
                <div class="tmt-form-group">
                    <label for="detailNamaTugas">Nama Tugas</label>
                    <input type="text" id="detailNamaTugas" name="judul" placeholder="Masukkan nama tugas" readonly required>
                </div>

                <div class="tmt-form-group">
                    <label for="detailJenisTugas">Jenis Tugas</label>
                    <select id="detailJenisTugas" name="tipe" disabled required>
                        <option value="tugas">Tugas</option>
                        <option value="projek">Projek</option>
                    </select>
                </div>

                <div class="tmt-form-group">
                    <label for="detailKursus">Kursus</label>
                    <select id="detailKursus" disabled required>
                        @foreach ($namaKursus as $nk)
                            <option value="{{ $nk }}">{{ $nk }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="tmt-form-group">
                    <label for="detailNamaSiswa">Nama Siswa</label>
                    <select id="detailNamaSiswa" disabled required>
                        @foreach ($namaSiswa as $ns)
                            <option value="{{ $ns }}">{{ $ns }}</option>
                        @endforeach
                    </select>
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

        {{-- form nilai: action diisi JavaScript (alamat simpan nilai tugas yang dipilih) --}}
        <form id="formNilai" class="tmt-upload-form" method="POST" action="">
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

                <p id="lihatPesan" hidden style="background:#fff4e0;color:#8a5a00;padding:8px 12px;border-radius:10px;margin:0 0 10px;">
                    Siswa belum mengumpulkan tugas ini, jadi nilai belum bisa diisi.
                </p>

                <div class="tmt-form-group">
                    <label for="lihatNilai">Nilai</label>
                    <input type="number" id="lihatNilai" name="nilai" min="0" max="100" step="1"
                           placeholder="Masukkan nilai (0-100)" required>
                </div>

                <div class="tmt-upload-actions">
                    <button type="button" class="tmt-cancel-btn" onclick="tutupLihat()">Batal</button>
                    <button type="submit" class="tmt-send-btn" id="btnSimpanNilai">Simpan</button>
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

// ===== MODAL UPLOAD TUGAS =====
const formUpload = document.getElementById('formUpload');
const kursusUpload = document.getElementById('kelasBaru');
const siswaUpload = document.getElementById('siswa');

// tampilkan hanya siswa yang mengambil kursus yang dipilih
function saringSiswa() {
    const kursus = kursusUpload.value;
    [...siswaUpload.options].forEach(opt => {
        if (!opt.value) return;
        const cocok = !!kursus && opt.dataset.kursus === kursus;
        opt.hidden = !cocok;
        opt.disabled = !cocok;
    });
    if (siswaUpload.selectedOptions[0] && siswaUpload.selectedOptions[0].disabled) {
        siswaUpload.value = '';
    }
}

kursusUpload.addEventListener('change', () => {
    siswaUpload.value = '';
    saringSiswa();
});

function bukaUpload() {
    formUpload.reset();
    saringSiswa();
    document.getElementById('modalUpload').hidden = false;
}

function tutupUpload() {
    document.getElementById('modalUpload').hidden = true;
}

// cek di browser dulu; kalau ada yang kosong batalkan. Kalau lengkap, form dikirim ke server (disimpan ke database)
formUpload.addEventListener('submit', function (e) {
    if (!document.getElementById('judulBaru').value.trim() ||
        !document.getElementById('jenisTgs').value ||
        !kursusUpload.value ||
        !siswaUpload.value ||
        !document.getElementById('deadlineBaru').value ||
        !document.getElementById('deskripsiBaru').value.trim()) {
        e.preventDefault();
        alert('Lengkapi semua data terlebih dahulu.');
    }
});

// ===== MODAL LIHAT TUGAS (status pengumpulan + nilai) =====
const formNilai = document.getElementById('formNilai');

function lihatTugas(btn) {
    const row = btn.closest('tr');
    const bisaNilai = row.dataset.bisaNilai === '1';

    document.getElementById('lihatNamaTugas').value = row.children[0].textContent.trim();
    document.getElementById('lihatJenisTugas').value = row.dataset.jenis || 'tugas';
    document.getElementById('lihatKursus').value = row.children[1].textContent.trim();
    document.getElementById('lihatNamaSiswa').value = row.children[2].textContent.trim();
    document.getElementById('lihatStatusPengumpulan').value = row.dataset.statusPengumpulan || 'tidak kumpul';
    document.getElementById('lihatDeskripsi').value = row.dataset.deskripsi || '-';
    document.getElementById('lihatNilai').value = row.dataset.nilai || '';

    // alamat simpan nilai untuk tugas ini
    formNilai.action = row.dataset.urlNilai;

    // nilai hanya bisa diisi kalau siswa sudah mengumpulkan
    document.getElementById('lihatNilai').disabled = !bisaNilai;
    document.getElementById('btnSimpanNilai').disabled = !bisaNilai;
    document.getElementById('lihatPesan').hidden = bisaNilai;

    document.getElementById('modalLihat').hidden = false;
}

function tutupLihat() {
    document.getElementById('modalLihat').hidden = true;
}

formNilai.addEventListener('submit', function (e) {
    const input = document.getElementById('lihatNilai');
    const nilai = Number(input.value);
    if (input.value === '' || nilai < 0 || nilai > 100) {
        e.preventDefault();
        alert('Nilai harus berada di antara 0 sampai 100.');
    }
});

// ===== MODAL DETAIL & EDIT TUGAS =====
let currentEditingRow = null;
const detailBox = document.getElementById('detailBox');
const formEditTugas = document.getElementById('formEditTugas');
// yang bisa diedit: judul, deskripsi, deadline, jenis. Kursus & siswa dikunci (tugas tidak dipindah ke siswa lain)
const detailTextFields = ['detailNamaTugas', 'detailDeskripsi', 'detailDeadline'];
const detailSelectFields = ['detailJenisTugas'];

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
    formEditTugas.action = currentEditingRow.dataset.urlUpdate;   // alamat update tugas ini
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

// cek di browser dulu; kalau lengkap, form dikirim ke server (PUT ke TugasController@update)
formEditTugas.addEventListener('submit', function (e) {
    if (!document.getElementById('detailNamaTugas').value.trim() ||
        !document.getElementById('detailDeadline').value ||
        !document.getElementById('detailDeskripsi').value.trim()) {
        e.preventDefault();
        alert('Judul, deadline, dan deskripsi wajib diisi.');
    }
});

// kalau server menolak form BUAT tugas (validasi gagal), buka lagi modal dengan isian sebelumnya
@if ($errors->any() && old('form_asal') === 'upload')
    bukaUpload();
    document.getElementById('judulBaru').value = {{ \Illuminate\Support\Js::from(old('judul', '')) }};
    document.getElementById('jenisTgs').value = {{ \Illuminate\Support\Js::from(old('tipe', '')) }};
    kursusUpload.value = {{ \Illuminate\Support\Js::from(old('id_kursus', '')) }};
    saringSiswa();
    siswaUpload.value = {{ \Illuminate\Support\Js::from(old('id_kelas', '')) }};
    document.getElementById('deadlineBaru').value = {{ \Illuminate\Support\Js::from(old('deadline', '')) }};
    document.getElementById('deskripsiBaru').value = {{ \Illuminate\Support\Js::from(old('deskripsi', '')) }};
@endif
</script>
@endsection