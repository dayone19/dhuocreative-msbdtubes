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
                    <tr data-status="{{ strtolower($s->status ?? 'aktif') }}"
                        data-id="{{ $s->id_user }}"
                        data-id-str="{{ $s->id_str }}"
                        data-nama="{{ $s->nama }}"
                        data-telp="{{ $s->no_telp }}"
                        data-tanggal="{{ $s->tanggal_daftar_teks }}"
                        data-status="{{ ucfirst($s->status ?? 'aktif') }}"
                        data-kursus='@json($s->daftar_kursus)'>
                        <td>{{ $s->id_str }}</td>
                        <td>{{ $s->nama }}</td>
                        <td>{{ $s->tanggal_daftar_teks }}</td>
                        <td><span class="o-status {{ strtolower($s->status ?? 'aktif') }}">{{ ucfirst($s->status ?? 'aktif') }}</span></td>
                        <td>
                            <div class="o-row-actions">
                                <button type="button" class="o-action" data-detail>Detail</button>
                                <a href="{{ route('operator.tugas', ['id_siswa' => $s->id_user]) }}" class="o-action" style="background:#286c65; text-decoration:none;">Lihat Tugas</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding: 20px;">Belum ada data siswa tersimpan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <p class="o-empty" id="kosongSiswa" hidden>Pendaftaran siswa tidak ditemukan.</p>
    <nav class="tj-pagination" id="paginasiSiswa" aria-label="Halaman daftar siswa" style="margin-top:20px;" hidden></nav>
</div>

<!-- Modal tambah / edit pendaftaran -->
<div class="o-modal" id="modalSiswa" hidden>
    <div class="o-modal-box">
        <div class="o-modal-header">
            <button type="button" class="o-modal-close" data-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2>Pendaftaran Siswa</h2>
        </div>

        <form id="formSiswa">
            @csrf
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

            <div id="fieldDataKursus">
                <div class="o-form-group">
                    <label for="kursusSiswa">Nama Kursus</label>
                    <select id="kursusSiswa">
                        <option value="">Pilih kursus</option>
                        @foreach($kursusList as $k)
                            <option value="{{ $k->id_kursus }}">{{ $k->nama_kursus }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="o-form-group">
                    <label for="kelasSiswa">Jenis Kelas</label>
                    <select id="kelasSiswa">
                        <option value="">Pilih kelas</option>
                        <option value="Online Class">Online</option>
                        <option value="Private Class">Offline</option>
                        <option value="Home Class">Offline Rumah</option>
                    </select>
                </div>
                <div class="o-form-group">
                    <label id="hariLabel">Hari Pembelajaran (1 minggu, maks. 3 kali pertemuan)</label>
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
                    <input type="time" id="jamMulai" min="07:00" max="20:00">
                </div>
                <div class="o-form-group">
                    <label for="tanggalDaftar">Tanggal Daftar</label>
                    <input id="tanggalDaftar" type="date">
                </div>
                <div class="o-form-group">
                    <label for="statusSiswa">Status Pendaftaran</label>
                    <select id="statusSiswa">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="o-btn o-btn-orange o-btn-full" id="submitSiswa">Simpan Pendaftaran</button>
        </form>
    </div>
</div>

<!-- Modal detail siswa -->
<div class="o-modal" id="modalDetailSiswa" hidden>
    <div class="o-modal-box">
        <div class="o-modal-header">
            <button type="button" class="o-modal-close" data-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
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
        <button type="button" class="o-action" id="editDataMaster" style="margin: 10px 0 18px;">Edit Data Siswa</button>

        <p class="o-course-title">Daftar Kursus</p>
        <div class="o-course-list" id="detailCourseList">
            <div class="o-course-item">
                <div class="o-course-head">
                    <strong>Web Master - Online</strong>
                    <button type="button" class="o-action" data-edit-course>Edit</button>
                </div>
                <div class="o-course-row"><span>Hari</span><strong>Senin, Rabu, Jumat</strong></div>
                <div class="o-course-row"><span>Jam Mulai</span><strong class="course-jam">08:00</strong></div>
                <div class="o-course-row"><span>Tentor</span><strong>Tentor C</strong></div>
                <div class="o-course-row"><span>Status</span><strong>Aktif</strong></div>
            </div>
        </div>
        <button type="button" class="o-btn o-btn-orange o-btn-full" id="tambahKursusSiswa">Tambah Kursus</button>
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

(()=>{
const $ = s => document.querySelector(s), all = s => [...document.querySelectorAll(s)];
const modal = $('#modalSiswa'), detail = $('#modalDetailSiswa'), form = $('#formSiswa');
const masterFields = $('#fieldDataMaster'), courseFields = $('#fieldDataKursus');
const bodySiswa = $('#bodySiswa');
let formMode = 'new', returnToDetail = false;
let activeCourse = null;
let selectedStudentId = null;
let selectedRow = null;

function setGroup(group, show){
    group.hidden = !show;
    group.querySelectorAll('input, select, textarea').forEach(el => {
        el.disabled = !show;
    });
}

function openForm(mode){
    formMode = mode;
    returnToDetail = mode === 'add-course' || mode === 'edit-master' || mode === 'edit-course';
    setGroup(masterFields, mode === 'new' || mode === 'edit-master');
    setGroup(courseFields, mode === 'new' || mode === 'add-course' || mode === 'edit-course');
    $('#jamMulai').required = false;
    $('#namaSiswa').required = mode === 'new' || mode === 'edit-master';
    $('#telpSiswa').required = mode === 'new' || mode === 'edit-master';
    $('#kursusSiswa').required = mode === 'new' || mode === 'add-course' || mode === 'edit-course';
    $('#kelasSiswa').required = false;
    $('#tanggalDaftar').required = false;
    $('#statusSiswa').required = false;

    if (mode === 'edit-master') {
        $('#namaSiswa').value = $('#detailNama').textContent.trim();
        $('#telpSiswa').value = $('#detailTelp').textContent.trim();
    } else if (mode === 'new' || mode === 'add-course') {
        if (mode === 'new') {
            $('#namaSiswa').value = '';
            $('#telpSiswa').value = '';
        }
        $('#kursusSiswa').value = '';
        $('#kelasSiswa').value = '';
        days.forEach(d => { d.checked = false; d.disabled = false; });
        $('#jamMulai').value = '';
        $('#tanggalDaftar').value = '{{ date("Y-m-d") }}';
        $('#statusSiswa').value = 'aktif';
        $('#hariCount').textContent = '0/3 hari dipilih';
    }

    if (mode === 'edit-course' && activeCourse) {
        const idKursus = activeCourse.dataset.idKursus;
        const namaKursus = activeCourse.dataset.namaKursus;
        const jenisKelas = activeCourse.dataset.kelas;
        const status = activeCourse.dataset.status;
        const tanggal = activeCourse.dataset.tanggal;

        if (idKursus && $('#kursusSiswa').querySelector(`option[value="${idKursus}"]`)) {
            $('#kursusSiswa').value = idKursus;
        } else if (namaKursus) {
            const opt = [...$('#kursusSiswa').options].find(o => o.text.trim().toLowerCase() === namaKursus.trim().toLowerCase());
            if (opt) $('#kursusSiswa').value = opt.value;
        } else {
            const heading = activeCourse.querySelector('.o-course-head strong')?.textContent.trim().split(' - ') || [];
            const opt = [...$('#kursusSiswa').options].find(o => o.text.trim().toLowerCase() === (heading[0] || '').trim().toLowerCase());
            if (opt) $('#kursusSiswa').value = opt.value;
        }

        if (jenisKelas) {
            const opt = [...$('#kelasSiswa').options].find(o => o.value.toLowerCase() === jenisKelas.toLowerCase() || o.text.trim().toLowerCase() === jenisKelas.trim().toLowerCase());
            if (opt) $('#kelasSiswa').value = opt.value;
            else $('#kelasSiswa').value = jenisKelas;
        } else {
            const heading = activeCourse.querySelector('.o-course-head strong')?.textContent.trim().split(' - ') || [];
            const cls = (heading[1] || '').trim().toLowerCase();
            const opt = [...$('#kelasSiswa').options].find(o => o.value.toLowerCase() === cls || o.text.trim().toLowerCase() === cls);
            if (opt) $('#kelasSiswa').value = opt.value;
        }

        if (status) {
            $('#statusSiswa').value = status.toLowerCase() === 'nonaktif' ? 'nonaktif' : 'aktif';
        } else {
            const rows = [...activeCourse.querySelectorAll('.o-course-row')];
            const statusRow = rows.find(r => r.querySelector('span')?.textContent.trim() === 'Status');
            const stText = statusRow?.querySelector('strong')?.textContent.trim().toLowerCase();
            $('#statusSiswa').value = stText === 'nonaktif' ? 'nonaktif' : 'aktif';
        }

        if (tanggal) {
            $('#tanggalDaftar').value = tanggal.split('T')[0];
        }

        days.forEach(d => { d.checked = false; d.disabled = false; });
        $('#jamMulai').value = '';
        $('#hariCount').textContent = '0/3 hari dipilih';
    }

    const titles = {
        'new': 'Pendaftaran Siswa',
        'edit-master': 'Edit Data Siswa',
        'add-course': 'Tambah Kursus Siswa',
        'edit-course': 'Edit Kursus Siswa'
    };
    $('#modalSiswa h2').textContent = titles[mode];
    $('#submitSiswa').textContent =
        mode === 'edit-master' ? 'Simpan Perubahan' :
        mode === 'add-course' ? 'Tambah Kursus' :
        mode === 'edit-course' ? 'Simpan Perubahan' :
        'Simpan Pendaftaran';
    detail.hidden = true;
    modal.hidden = false;
}

$('#btnTambahSiswa').onclick = () => openForm('new');
$('#editDataMaster').onclick = () => openForm('edit-master');
$('#tambahKursusSiswa').onclick = () => openForm('add-course');

detail.addEventListener('click', e => {
    const button = e.target.closest('[data-edit-course]');
    if (!button) return;
    activeCourse = button.closest('.o-course-item');
    openForm('edit-course');
});

all('#tabelSiswa').forEach(tbl => {
    tbl.addEventListener('click', e => {
        const b = e.target.closest('[data-detail]');
        if (!b) return;
        const row = b.closest('tr');
        selectedRow = row;
        selectedStudentId = row.dataset.id;

        $('#detailId').textContent = row.dataset.idStr || row.children[0].textContent.trim();
        $('#detailNama').textContent = row.dataset.nama || row.children[1].textContent.trim();
        $('#detailTelp').textContent = row.dataset.telp || '-';
        $('#detailTanggal').textContent = row.dataset.tanggal || row.children[2].textContent.trim();
        $('#detailStatus').textContent = row.dataset.status || row.children[3].textContent.trim();

        const courseList = $('#detailCourseList');
        if (courseList) {
            courseList.replaceChildren();
            let courses = [];
            try {
                courses = JSON.parse(row.dataset.kursus || '[]');
            } catch (err) {}

            if (courses && courses.length > 0) {
                courses.forEach(c => {
                    const item = document.createElement('div');
                    item.className = 'o-course-item';
                    item.dataset.idKursus = c.id_kursus || '';
                    item.dataset.namaKursus = c.nama_kursus || '';
                    item.dataset.kelas = c.jenis_kelas || '';
                    item.dataset.status = c.status || 'aktif';
                    item.dataset.tanggal = c.tanggal_daftar || '';
                    item.innerHTML = `
                        <div class="o-course-head">
                            <strong>${c.nama_kursus || 'Kursus'} - ${c.jenis_kelas || 'Reguler'}</strong>
                            <button type="button" class="o-action" data-edit-course>Edit</button>
                        </div>
                        <div class="o-course-row"><span>Status</span><strong>${c.status ? (c.status.charAt(0).toUpperCase() + c.status.slice(1)) : 'Aktif'}</strong></div>
                    `;
                    courseList.appendChild(item);
                });
            } else {
                const item = document.createElement('div');
                item.className = 'o-course-item';
                item.innerHTML = `
                    <div class="o-course-head">
                        <strong>Belum Mengikuti Kursus</strong>
                    </div>
                    <div class="o-course-row"><span>Status</span><strong>-</strong></div>
                `;
                courseList.appendChild(item);
            }
        }

        detail.hidden = false;
    });
});

all('[data-close]').forEach(b => b.onclick = () => {
    const parent = b.closest('.o-modal');
    parent.hidden = true;
    if (parent === modal && returnToDetail) detail.hidden = false;
});

form.onsubmit = async e => {
    e.preventDefault();

    const jamMulai = $('#jamMulai')?.value;
    if (jamMulai && jamMulai > '20:00') {
        alert('⚠️ Jam mulai pembelajaran paling lambat pukul 20:00 (durasi kursus 2 jam, selesai maks. pukul 22:00)!');
        return;
    }

    const token = form.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';

    if (formMode === 'edit-master') {
        const nama = $('#namaSiswa').value.trim();
        const telp = $('#telpSiswa').value.trim();

        if (!nama || !telp) return;

        const btn = $('#submitSiswa');
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
                body: JSON.stringify({ nama: nama, no_telp: telp })
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
                if (returnToDetail) detail.hidden = false;
            } else {
                alert('Gagal menyimpan: ' + (data.message || 'Terjadi kesalahan'));
            }
        } catch (err) {
            alert('Terjadi kesalahan jaringan.');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Simpan Perubahan';
        }
    } else if (formMode === 'new') {
        const nama = $('#namaSiswa').value.trim();
        const telp = $('#telpSiswa').value.trim();
        const idKursus = $('#kursusSiswa').value;
        const kelas = $('#kelasSiswa').value;
        const tglDaftar = $('#tanggalDaftar').value;
        const status = $('#statusSiswa').value;

        if (!nama || !telp) return;

        const btn = $('#submitSiswa');
        btn.disabled = true;
        btn.textContent = 'Menyimpan...';

        try {
            const res = await fetch("{{ route('operator.siswa.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    nama: nama,
                    no_telp: telp,
                    id_kursus: idKursus,
                    kelas: kelas,
                    tanggal_daftar: tglDaftar,
                    status: status
                })
            });

            const data = await res.json();
            if (res.ok && data.success) {
                window.location.reload();
            } else {
                alert('Gagal mendaftar: ' + (data.message || 'Terjadi kesalahan'));
                btn.disabled = false;
                btn.textContent = 'Simpan Pendaftaran';
            }
        } catch (err) {
            alert('Terjadi kesalahan jaringan.');
            btn.disabled = false;
            btn.textContent = 'Simpan Pendaftaran';
        }
    } else if (formMode === 'add-course') {
        const idKursus = $('#kursusSiswa').value;
        const kelas = $('#kelasSiswa').value;
        const tglDaftar = $('#tanggalDaftar').value;
        const status = $('#statusSiswa').value;

        if (!idKursus) {
            alert('Silakan pilih kursus.');
            return;
        }

        const btn = $('#submitSiswa');
        btn.disabled = true;
        btn.textContent = 'Menambahkan...';

        try {
            const res = await fetch(`/operator/siswa/${selectedStudentId}/kursus`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    id_kursus: idKursus,
                    kelas: kelas,
                    tanggal_daftar: tglDaftar,
                    status: status
                })
            });

            const data = await res.json();
            if (res.ok && data.success) {
                const courseList = $('#detailCourseList');
                if (courseList) {
                    if (courseList.querySelector('.o-course-head strong')?.textContent.includes('Belum Mengikuti Kursus')) {
                        courseList.replaceChildren();
                    }

                    const item = document.createElement('div');
                    item.className = 'o-course-item';
                    item.dataset.idKursus = data.data.id_kursus;
                    item.dataset.namaKursus = data.data.nama_kursus;
                    item.dataset.kelas = data.data.jenis_kelas;
                    item.dataset.status = data.data.status;
                    item.dataset.tanggal = data.data.tanggal_daftar;
                    item.innerHTML = `
                        <div class="o-course-head">
                            <strong>${data.data.nama_kursus} - ${data.data.jenis_kelas}</strong>
                            <button type="button" class="o-action" data-edit-course>Edit</button>
                        </div>
                        <div class="o-course-row"><span>Status</span><strong>${data.data.status.charAt(0).toUpperCase() + data.data.status.slice(1)}</strong></div>
                    `;
                    courseList.appendChild(item);
                }

                if (selectedRow) {
                    let currentCourses = [];
                    try {
                        currentCourses = JSON.parse(selectedRow.dataset.kursus || '[]');
                    } catch (err) {}
                    currentCourses.push(data.data);
                    selectedRow.dataset.kursus = JSON.stringify(currentCourses);
                }

                modal.hidden = true;
                if (returnToDetail) detail.hidden = false;
            } else {
                alert('Gagal menambahkan kursus: ' + (data.message || 'Terjadi kesalahan'));
            }
        } catch (err) {
            alert('Terjadi kesalahan jaringan.');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Tambah Kursus';
        }
    } else if (formMode === 'edit-course') {
        const idKursus = $('#kursusSiswa').value;
        const kelas = $('#kelasSiswa').value;
        const tglDaftar = $('#tanggalDaftar').value;
        const status = $('#statusSiswa').value;
        const oldIdKursus = activeCourse?.dataset?.idKursus || idKursus;

        if (!idKursus) {
            alert('Silakan pilih kursus.');
            return;
        }

        const btn = $('#submitSiswa');
        btn.disabled = true;
        btn.textContent = 'Menyimpan...';

        try {
            const res = await fetch(`/operator/siswa/${selectedStudentId}/kursus/${oldIdKursus}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    id_kursus: idKursus,
                    kelas: kelas,
                    tanggal_daftar: tglDaftar,
                    status: status
                })
            });

            const data = await res.json();
            if (res.ok && data.success) {
                if (activeCourse) {
                    activeCourse.dataset.idKursus = data.data.id_kursus;
                    activeCourse.dataset.namaKursus = data.data.nama_kursus;
                    activeCourse.dataset.kelas = data.data.jenis_kelas;
                    activeCourse.dataset.status = data.data.status;
                    activeCourse.dataset.tanggal = data.data.tanggal_daftar;

                    activeCourse.querySelector('.o-course-head strong').textContent =
                        `${data.data.nama_kursus} - ${data.data.jenis_kelas}`;

                    const rows = [...activeCourse.querySelectorAll('.o-course-row')];
                    const statusRow = rows.find(r => r.querySelector('span')?.textContent.trim() === 'Status');
                    if (statusRow) {
                        statusRow.querySelector('strong').textContent =
                            data.data.status.charAt(0).toUpperCase() + data.data.status.slice(1);
                    }
                }

                if (selectedRow) {
                    let currentCourses = [];
                    try {
                        currentCourses = JSON.parse(selectedRow.dataset.kursus || '[]');
                    } catch (err) {}

                    const idx = currentCourses.findIndex(c => String(c.id_kursus) === String(oldIdKursus));
                    if (idx !== -1) {
                        currentCourses[idx] = data.data;
                    } else {
                        currentCourses.push(data.data);
                    }
                    selectedRow.dataset.kursus = JSON.stringify(currentCourses);
                }

                modal.hidden = true;
                if (returnToDetail) detail.hidden = false;
            } else {
                alert('Gagal memperbarui kursus: ' + (data.message || 'Terjadi kesalahan'));
            }
        } catch (err) {
            alert('Terjadi kesalahan jaringan.');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Simpan Perubahan';
        }
    } else {
        modal.hidden = true;
        if (returnToDetail) detail.hidden = false;
    }
};

const days = all('#hariPicker input');
days.forEach(d => d.onchange = () => {
    const n = days.filter(x => x.checked).length;
    days.forEach(x => x.disabled = !x.checked && n >= 3);
    $('#hariCount').textContent = n + '/3 hari dipilih';
});

$('#jamMulai')?.addEventListener('change', () => {
    const val = $('#jamMulai').value;
    if (val && val > '20:00') {
        alert('⚠️ Jam mulai pembelajaran paling lambat pukul 20:00 (durasi kursus 2 jam, selesai maks. pukul 22:00)!');
        $('#jamMulai').value = '20:00';
    }
});

function filterTabelSiswa(){
    const q = $('#cariSiswa').value.toLowerCase().trim();
    const st = $('#filterStatus').value;
    const allRows = [...bodySiswa.querySelectorAll('tr[data-id]')];

    const filteredRows = allRows.filter(r => {
        const ok = r.textContent.toLowerCase().includes(q) && (!st || r.dataset.status.toLowerCase() === st.toLowerCase());
        return ok;
    });

    allRows.forEach(r => r.style.display = 'none');

    const totalFiltered = filteredRows.length;
    $('#kosongSiswa').hidden = totalFiltered > 0;

    if (totalFiltered === 0) {
        renderPaginasi('paginasiSiswa', 0, halSiswa, pindahHalamanSiswa);
        return;
    }

    const maxPage = Math.ceil(totalFiltered / PER_HALAMAN_SISWA);
    if (halSiswa > maxPage) halSiswa = maxPage || 1;

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
