
{{-- Manajemen Siswa Operator - Dhuo Creative --}}
@extends('layouts.Operator')
@section('title', 'Daftar Siswa')
@section('content')

<div class="o-page">
    <div class="o-page-header">
        <h1 class="o-page-title">Daftar Siswa</h1>
        <button type="button" class="o-btn o-btn-orange" id="btnTambahSiswa">
            <span class="o-plus">+</span>
            Tambah Siswa
        </button>
    </div>

    <div class="o-toolbar">
        <input class="o-search" id="cariSiswa" type="search" placeholder="Cari ID, nama siswa, kursus, atau kelas..." aria-label="Cari siswa">
        <div class="o-schedule-filters">
            <select class="o-filter" id="filterStatus" aria-label="Filter status">
                <option value="">Semua status</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>
    </div>

    <div class="s-table-wrap">
        <table class="s-table" id="tabelSiswa">
            <thead>
                <tr>
                    <th>ID Siswa</th>
                    <th>Nama</th>
                    <th>Tgl Daftar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="bodySiswa">
                @forelse($siswaList as $s)
                    <tr
                        data-id="{{ $s->id_user }}"
                        data-id-str="{{ $s->id_str }}"
                        data-nama="{{ $s->nama }}"
                        data-telp="{{ $s->no_telp }}"
                        data-tanggal="{{ $s->tanggal_daftar_teks }}"
                        data-status="{{ strtolower($s->status ?? 'aktif') }}"
                        data-kursus='@json($s->daftar_kursus)'
                    >
                        <td>{{ $s->id_str }}</td>
                        <td>{{ $s->nama }}</td>
                        <td>{{ $s->tanggal_daftar_teks }}</td>
                        <td>
                            <span class="o-status {{ strtolower($s->status ?? 'aktif') }}">
                                {{ ucfirst($s->status ?? 'aktif') }}
                            </span>
                        </td>
                        <td>
                            <div class="o-row-actions">
                                <button type="button" class="o-action" data-detail>Detail</button>
                                <a href="{{ route('operator.tugas', ['id_siswa' => $s->id_user]) }}" class="o-action" style="background:#286c65; text-decoration:none;">Lihat Tugas</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:20px;">
                            Belum ada data siswa tersimpan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <p class="o-empty" id="kosongSiswa" hidden>Pendaftaran siswa tidak ditemukan.</p>

    <nav class="tj-pagination" id="paginasiSiswa" aria-label="Halaman daftar siswa" style="margin-top:20px;" hidden></nav>
</div>

<!-- Modal Pendaftaran / Edit Siswa -->
<div class="o-modal" id="modalSiswa" hidden>
    <div class="o-modal-box">
        <div class="o-modal-header">
            <button type="button" class="o-modal-close" data-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" viewBox="0 0 16 16">
                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2>Pendaftaran Siswa</h2>
        </div>

        <form id="formSiswa">
            @csrf

            <!-- Edit Data Master Siswa -->
            <div id="fieldDataMaster">
                <div class="o-form-group">
                    <label for="namaSiswa">Nama Siswa</label>
                    <input id="namaSiswa" maxlength="100" required placeholder="Masukkan nama siswa">
                </div>

                <div class="o-form-group">
                    <label for="telpSiswa">No. Telp</label>
                    <input id="telpSiswa" maxlength="20" required placeholder="Masukkan nomor telepon">
                </div>
            </div>

            <!-- Data Pendaftaran Kursus -->
            <div id="fieldDataKursus">
                <div class="o-form-group">
                    <label for="akunSiswa">Akun Siswa</label>
                    <select id="akunSiswa" required>
                        <option value="">Pilih akun siswa</option>
                        @foreach($akunSiswaList as $akun)
                            <option value="{{ $akun->id_user }}">
                                {{ $akun->nama }} - {{ $akun->no_telp }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="o-form-group">
                    <label for="kursusSiswa">Nama Kursus</label>
                    <select id="kursusSiswa" required>
                        <option value="">Pilih kursus</option>
                        @foreach($kursusList as $k)
                            <option value="{{ $k->id_kursus }}">{{ $k->nama_kursus }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="o-form-group">
                    <label for="kelasSiswa">Jenis Kelas</label>
                    <select id="kelasSiswa" required>
                        <option value="">Pilih kelas</option>
                        <option value="Online Class">Online</option>
                        <option value="Private Class">Offline</option>
                        <option value="Home Class">Offline Rumah</option>
                    </select>
                </div>

                <div class="o-form-group">
                    <label id="hariLabel">Hari Pembelajaran (maksimal 3 hari per minggu)</label>

                    <div class="o-day-picker" id="hariPicker" role="group" aria-labelledby="hariLabel">
                        <label><input type="checkbox" value="Senin">Senin</label>
                        <label><input type="checkbox" value="Selasa">Selasa</label>
                        <label><input type="checkbox" value="Rabu">Rabu</label>
                        <label><input type="checkbox" value="Kamis">Kamis</label>
                        <label><input type="checkbox" value="Jumat">Jumat</label>
                        <label><input type="checkbox" value="Sabtu">Sabtu</label>
                    </div>

                    <span class="o-hari-hint" id="hariCount" aria-live="polite">0/3 hari dipilih</span>
                </div>

                <div class="o-form-group">
                    <label for="jamMulai">Jam Mulai Pembelajaran</label>
                    <input type="time" id="jamMulai" min="07:00" max="20:00" required>
                </div>

                <div class="o-form-group">
                    <label for="jamSelesai">Jam Selesai Pembelajaran</label>
                    <input type="time" id="jamSelesai" readonly>
                    <small>Jam selesai dihitung otomatis dengan durasi 2 jam.</small>
                </div>

                <div class="o-form-group">
                    <label for="tanggalDaftar">Tanggal Daftar</label>
                    <input id="tanggalDaftar" type="date" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="o-form-group">
                    <label for="statusSiswa">Status Pendaftaran</label>
                    <select id="statusSiswa" required>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

                <div class="o-form-group">
                    <label for="tentorSiswa">Tentor Tersedia</label>
                    <select id="tentorSiswa" required disabled>
                        <option value="">Lengkapi data jadwal terlebih dahulu</option>
                    </select>
                    <small id="tentorHint" aria-live="polite">
                        Pilih kursus, hari, tanggal, dan jam untuk mengecek tentor.
                    </small>
                </div>
            </div>

            <button type="submit" class="o-btn o-btn-orange o-btn-full" id="submitSiswa">
                Simpan Pendaftaran
            </button>
        </form>
    </div>
</div>


<!-- Modal Detail Siswa -->
<div class="o-modal" id="modalDetailSiswa" hidden>
    <div class="o-modal-box">
        <div class="o-modal-header">
            <button type="button" class="o-modal-close" data-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" viewBox="0 0 16 16">
                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2>Detail Pendaftaran</h2>
        </div>

        <p class="o-course-title">Data Siswa</p>

        <div class="o-detail-list" id="detailDataMaster">
            <div><span>ID Siswa</span><strong id="detailId">S-001</strong></div>
            <div><span>Nama Siswa</span><strong id="detailNama">Siswa A</strong></div>
            <div><span>No. Telp</span><strong id="detailTelp">08123456789</strong></div>
            <div><span>Tanggal Daftar</span><strong id="detailTanggal">12 Jan 2026</strong></div>
            <div><span>Status</span><strong id="detailStatus">Aktif</strong></div>
        </div>

        <button type="button" class="o-action" id="editDataMaster" style="margin:10px 0 18px;">
            Edit Data Siswa
        </button>

        <p class="o-course-title">Daftar Kursus</p>

        <div class="o-course-list" id="detailCourseList">
            <div class="o-course-item">
                <div class="o-course-head">
                    <strong>Belum Mengikuti Kursus</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const PER_HALAMAN_SISWA = 10;
let halSiswa = 1;

function renderPaginasi(navId, total, halaman, pindah) {
    const nav = document.getElementById(navId);
    if (!nav) return;

    nav.replaceChildren();

    if (!total || total <= 1) {
        nav.hidden = true;
        return;
    }

    nav.hidden = false;

    const jumlah = Math.ceil(total / PER_HALAMAN_SISWA);
    const awal = (halaman - 1) * PER_HALAMAN_SISWA + 1;
    const akhir = Math.min(halaman * PER_HALAMAN_SISWA, total);

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

(() => {
    const $ = s => document.querySelector(s);
    const all = s => [...document.querySelectorAll(s)];

    const modal = $('#modalSiswa');
    const detail = $('#modalDetailSiswa');
    const form = $('#formSiswa');

    const masterFields = $('#fieldDataMaster');
    const courseFields = $('#fieldDataKursus');
    const bodySiswa = $('#bodySiswa');

    let formMode = 'new';
    let returnToDetail = false;
    let selectedStudentId = null;
    let selectedRow = null;
    let sedangCekTentor = false;
    let nomorCekTentor = 0;

    const days = all('#hariPicker input');

    function setGroup(group, show) {
        group.hidden = !show;

        group.querySelectorAll('input, select, textarea').forEach(el => {
            el.disabled = !show;
        });
    }

    function resetHari() {
        days.forEach(d => {
            d.checked = false;
            d.disabled = false;
        });

        $('#hariCount').textContent = '0/3 hari dipilih';
    }

    function updateHari() {
        const jumlah = days.filter(d => d.checked).length;

        days.forEach(d => {
            d.disabled = !d.checked && jumlah >= 3;
        });

        $('#hariCount').textContent = jumlah + '/3 hari dipilih';

        cekTentor();
    }

    function hitungJamSelesai() {
        const mulai = $('#jamMulai').value;
        const selesai = $('#jamSelesai');

        if (!mulai) {
            selesai.value = '';
            return;
        }

        const [jam, menit] = mulai.split(':').map(Number);
        const totalMenit = jam * 60 + menit + 120;

        const jamAkhir = Math.floor(totalMenit / 60);
        const menitAkhir = totalMenit % 60;

        selesai.value =
            String(jamAkhir).padStart(2, '0') + ':' +
            String(menitAkhir).padStart(2, '0');
    }

    function resetHasilTentor(pesan = 'Pilih kursus, hari, tanggal, dan jam untuk mengecek tentor.') {
        const select = $('#tentorSiswa');

        select.replaceChildren();

        const option = document.createElement('option');
        option.value = '';
        option.textContent = pesan;

        select.appendChild(option);
        select.value = '';
        select.disabled = true;

        $('#tentorHint').textContent = pesan;
    }

    function dataJadwalLengkap() {
        const hari = days.filter(d => d.checked).map(d => d.value);

        return Boolean(
            $('#kursusSiswa').value &&
            hari.length >= 1 &&
            hari.length <= 3 &&
            $('#jamMulai').value &&
            $('#jamSelesai').value &&
            $('#tanggalDaftar').value
        );
    }

    async function cekTentor() {
        resetHasilTentor('Sedang memeriksa ketersediaan tentor...');

        const nomorSekarang = ++nomorCekTentor;

        if (!dataJadwalLengkap()) return;

        if ($('#jamMulai').value < '07:00' || $('#jamMulai').value > '20:00') {
            resetHasilTentor();
            return;
        }
        

        sedangCekTentor = true;

        const select = $('#tentorSiswa');
        const token = form.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';

        try {
            const res = await fetch("{{ route('operator.siswa.cek-tentor') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    id_kursus: $('#kursusSiswa').value,
                    hari: days.filter(d => d.checked).map(d => d.value),
                    jam_mulai: $('#jamMulai').value,
                    tanggal_daftar: $('#tanggalDaftar').value
                })
            });

            const data = await res.json();

            if (nomorSekarang !== nomorCekTentor) return;

            if (!res.ok || !data.success) {
                resetHasilTentor(data.message || 'Gagal memeriksa ketersediaan tentor.');
                return;
            }

            select.replaceChildren();

            const tersedia = data.tentor.filter(t => t.tersedia);

            data.tentor.forEach(t => {
                const option = document.createElement('option');

                option.value = t.id_tentor;
                option.disabled = !t.tersedia;

                option.textContent = t.nama + (
                    t.bentrok
                        ? ' - Bentrok'
                        : ' - Tidak Bentrok'
                );

                select.appendChild(option);
            });

            select.disabled = tersedia.length === 0;

            if (tersedia.length > 0) {
                select.value = String(tersedia[0].id_tentor);

                $('#tentorHint').textContent =
                    tersedia.length + ' tentor tersedia. Tentor dengan jadwal aktif paling sedikit diprioritaskan.';
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Tidak ada tentor tersedia';
                select.insertBefore(option, select.firstChild);
                select.value = '';

                $('#tentorHint').textContent =
                    'Tidak ada tentor yang tersedia pada jadwal tersebut.';
            }

        } catch (err) {
            console.error(err);
            resetHasilTentor('Terjadi kesalahan saat memeriksa tentor.');
        } finally {
            sedangCekTentor = false;
        }
    }

    function openForm(mode) {
        formMode = mode;
        returnToDetail = mode === 'edit-master';

        setGroup(masterFields, mode === 'edit-master');
        setGroup(courseFields, mode === 'new');

        $('#namaSiswa').required = mode === 'edit-master';
        $('#telpSiswa').required = mode === 'edit-master';

        $('#akunSiswa').required = mode === 'new';
        $('#kursusSiswa').required = mode === 'new';
        $('#kelasSiswa').required = mode === 'new';
        $('#jamMulai').required = mode === 'new';
        $('#tanggalDaftar').required = mode === 'new';
        $('#statusSiswa').required = mode === 'new';
        $('#tentorSiswa').required = mode === 'new';

        days.forEach(d => {
            d.required = false;
        });

        if (mode === 'new') {
            form.reset();

            $('#akunSiswa').value = '';
            $('#kursusSiswa').value = '';
            $('#kelasSiswa').value = '';
            $('#jamMulai').value = '';
            $('#jamSelesai').value = '';
            $('#tanggalDaftar').value = '{{ date("Y-m-d") }}';
            $('#statusSiswa').value = 'aktif';

            resetHari();
            resetHasilTentor();
        }

        if (mode === 'edit-master') {
            $('#namaSiswa').value = $('#detailNama').textContent.trim();
            $('#telpSiswa').value = $('#detailTelp').textContent.trim();
        }

        $('#modalSiswa h2').textContent =
            mode === 'edit-master' ? 'Edit Data Siswa' : 'Pendaftaran Siswa';

        $('#submitSiswa').textContent =
            mode === 'edit-master' ? 'Simpan Perubahan' : 'Simpan Pendaftaran';

        detail.hidden = true;
        modal.hidden = false;
    }

    $('#btnTambahSiswa').onclick = () => openForm('new');

    $('#editDataMaster').onclick = () => openForm('edit-master');

    $('#tabelSiswa').addEventListener('click', e => {
        const button = e.target.closest('[data-detail]');
        if (!button) return;

        const row = button.closest('tr');

        selectedRow = row;
        selectedStudentId = row.dataset.id;

        $('#detailId').textContent = row.dataset.idStr || row.children[0].textContent.trim();
        $('#detailNama').textContent = row.dataset.nama || row.children[1].textContent.trim();
        $('#detailTelp').textContent = row.dataset.telp || '-';
        $('#detailTanggal').textContent = row.dataset.tanggal || row.children[2].textContent.trim();

        const status = row.dataset.status || 'aktif';

        $('#detailStatus').textContent =
            status.charAt(0).toUpperCase() + status.slice(1);

        const courseList = $('#detailCourseList');
        courseList.replaceChildren();

        let courses = [];

        try {
            courses = JSON.parse(row.dataset.kursus || '[]');
        } catch (err) {
            courses = [];
        }

        if (courses.length > 0) {
            courses.forEach(c => {
                const item = document.createElement('div');
                item.className = 'o-course-item';

                const head = document.createElement('div');
                head.className = 'o-course-head';

                const title = document.createElement('strong');
                title.textContent =
                    `${c.nama_kursus || 'Kursus'} - ${c.jenis_kelas || 'Reguler'}`;

                head.appendChild(title);
                item.appendChild(head);

                const tambahBaris = (label, value) => {
                    const row = document.createElement('div');
                    row.className = 'o-course-row';

                    const span = document.createElement('span');
                    span.textContent = label;

                    const strong = document.createElement('strong');
                    strong.textContent = value || '-';

                    row.appendChild(span);
                    row.appendChild(strong);

                    item.appendChild(row);
                };

                tambahBaris('Status', c.status || 'Aktif');
                tambahBaris('Tentor', c.nama_tentor || 'Belum ditentukan');

                courseList.appendChild(item);
            });
        } else {
            const item = document.createElement('div');
            item.className = 'o-course-item';

            const head = document.createElement('div');
            head.className = 'o-course-head';

            const title = document.createElement('strong');
            title.textContent = 'Belum Mengikuti Kursus';

            head.appendChild(title);
            item.appendChild(head);

            courseList.appendChild(item);
        }

        detail.hidden = false;
    });

    all('[data-close]').forEach(button => {
        button.onclick = () => {
            const parent = button.closest('.o-modal');
            parent.hidden = true;

            if (parent === modal && returnToDetail) {
                detail.hidden = false;
            }
        };
    });

    form.onsubmit = async e => {
        e.preventDefault();

        const token =
            form.querySelector('input[name="_token"]')?.value ||
            '{{ csrf_token() }}';

        const btn = $('#submitSiswa');

        if (formMode === 'edit-master') {
            const nama = $('#namaSiswa').value.trim();
            const telp = $('#telpSiswa').value.trim();

            if (!nama || !telp) return;

            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            try {
                const res = await fetch(`/operator/siswa/${selectedStudentId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        nama: nama,
                        no_telp: telp
                    })
                });

                const data = await res.json();

                if (res.ok && data.success) {
                    $('#detailNama').textContent = nama;
                    $('#detailTelp').textContent = telp;

                    if (selectedRow) {
                        selectedRow.dataset.nama = nama;
                        selectedRow.dataset.telp = telp;
                        selectedRow.children[1].textContent = nama;
                    }

                    modal.hidden = true;
                    detail.hidden = false;
                } else {
                    alert('Gagal menyimpan: ' + (data.message || 'Terjadi kesalahan'));
                }
            } catch (err) {
                alert('Terjadi kesalahan jaringan.');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Simpan Perubahan';
            }

            return;
        }

        const idSiswa = $('#akunSiswa').value;
        const idKursus = $('#kursusSiswa').value;
        const kelas = $('#kelasSiswa').value;
        const jamMulai = $('#jamMulai').value;
        const jamSelesai = $('#jamSelesai').value;
        const tglDaftar = $('#tanggalDaftar').value;
        const status = $('#statusSiswa').value;
        const idTentor = $('#tentorSiswa').value;

        const hari = days
            .filter(d => d.checked)
            .map(d => d.value);

        if (!idSiswa || !idKursus || !kelas || !jamMulai || !tglDaftar || !idTentor) {
            alert('Mohon lengkapi seluruh data pendaftaran dan pilih tentor tersedia.');
            return;
        }

        if (hari.length < 1 || hari.length > 3) {
            alert('Pilih minimal satu dan maksimal tiga hari pembelajaran.');
            return;
        }

        if (jamMulai < '07:00' || jamMulai > '20:00') {
            alert('Jam mulai harus antara pukul 07:00 sampai 20:00.');
            return;
        }

        if (!jamSelesai || jamSelesai > '22:00') {
            alert('Jam selesai pembelajaran tidak boleh melewati pukul 22:00.');
            return;
        }

        btn.disabled = true;
        btn.textContent = 'Menyimpan Pendaftaran...';

        try {
            const res = await fetch("{{ route('operator.siswa.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    id_siswa: idSiswa,
                    id_kursus: idKursus,
                    kelas: kelas,
                    hari: hari,
                    jam_mulai: jamMulai,
                    tanggal_daftar: tglDaftar,
                    status: status,
                    id_tentor: idTentor
                })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                alert(
                    'Pendaftaran berhasil!\n' +
                    'Tentor: ' + data.tentor + '\n' +
                    'Jumlah pertemuan: ' + data.jumlah_pertemuan
                );

                window.location.reload();
            } else {
                alert('Gagal mendaftar: ' + (data.message || 'Terjadi kesalahan'));
            }
        } catch (err) {
            alert('Terjadi kesalahan jaringan.');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Simpan Pendaftaran';
        }
    };

    days.forEach(d => {
        d.addEventListener('change', updateHari);
    });

    $('#kursusSiswa').addEventListener('change', cekTentor);
    $('#tanggalDaftar').addEventListener('change', cekTentor);

    $('#jamMulai').addEventListener('input', () => {
        const val = $('#jamMulai').value;

        if (val && (val < '07:00' || val > '20:00')) {
            alert('Jam mulai harus antara pukul 07:00 sampai 20:00.');
            $('#jamMulai').value = '';
        }

        hitungJamSelesai();
        cekTentor();
    });

    function filterTabelSiswa() {
        const q = $('#cariSiswa').value.toLowerCase().trim();
        const st = $('#filterStatus').value;

        const allRows = [...bodySiswa.querySelectorAll('tr[data-id]')];

        const filteredRows = allRows.filter(r => {
            return r.textContent.toLowerCase().includes(q) &&
                (!st || r.dataset.status.toLowerCase() === st.toLowerCase());
        });

        allRows.forEach(r => r.style.display = 'none');

        const totalFiltered = filteredRows.length;

        $('#kosongSiswa').hidden = totalFiltered > 0;

        if (totalFiltered === 0) {
            renderPaginasi('paginasiSiswa', 0, halSiswa, pindahHalamanSiswa);
            return;
        }

        const maxPage = Math.ceil(totalFiltered / PER_HALAMAN_SISWA);

        if (halSiswa > maxPage) {
            halSiswa = maxPage || 1;
        }

        const start = (halSiswa - 1) * PER_HALAMAN_SISWA;
        const end = start + PER_HALAMAN_SISWA;

        filteredRows.slice(start, end).forEach(r => {
            r.style.display = '';
        });

        renderPaginasi('paginasiSiswa', totalFiltered, halSiswa, pindahHalamanSiswa);
    }

    function pindahHalamanSiswa(tujuanBaru) {
        halSiswa = tujuanBaru;
        filterTabelSiswa();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    $('#cariSiswa').addEventListener('input', () => {
        halSiswa = 1;
        filterTabelSiswa();
    });

    $('#filterStatus').addEventListener('change', () => {
        halSiswa = 1;
        filterTabelSiswa();
    });

    filterTabelSiswa();
})();
</script>


@endsection
