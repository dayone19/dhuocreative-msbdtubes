@extends('layouts.admin')

@section('content')
<div class="akun-page">
    <div class="akun-header">
        <h1 class="akun-title">Manajemen Akun</h1>
        <a href="{{ route('admin.buatAkun') }}" class="t-btn t-btn-orange akun-btn-buat">
            <span class="t-plus">+</span> Buat Akun
        </a>
    </div>

    <div class="akun-toolbar">
        <div class="t-search-wrap">
            <input type="text" class="t-search" id="cariAkun" placeholder="Cari nama">
            <svg viewBox="0 0 24 24"><circle cx="10" cy="10" r="7"/><path d="M15.5 15.5 21 21"/></svg>
        </div>

        <select class="t-filter akun-select" id="filterRole">
            <option value="">Pilih role</option>
            <option value="Admin">Admin</option>
            <option value="Operator">Operator</option>
            <option value="Tentor">Tentor</option>
            <option value="Siswa">Siswa</option>
        </select>
    </div>

    <div class="akun-table-container">
        <table class="akun-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="akunBody">
                <tr>
                    <td>Syalikha</td>
                    <td>Operator</td>
                    <td>Aktif</td>
                    <td>
                        <div class="akun-aksi">
                            <a href="#"
                                class="akun-btn-detail"
                                data-detail-akun
                                data-nama="Syalikha Fara"
                                data-role="Operator"
                                data-status="Aktif"
                                data-telepon="081234567890"
                                data-tahun="2025">
                                    Lihat Detail
                            </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Syalikha</td>
                    <td>Operator</td>
                    <td>Aktif</td>
                    <td>
                        <div class="akun-aksi">
                            <a href="#"
                                class="akun-btn-detail"
                                data-detail-akun
                                data-nama="Syalikha Fara"
                                data-role="Operator"
                                data-status="Aktif"
                                data-telepon="081234567890"
                                data-tahun="2025">
                                    Lihat Detail
                            </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Syalikha</td>
                    <td>Operator</td>
                    <td>Aktif</td>
                    <td>
                        <div class="akun-aksi">
                            <a href="#"
                                class="akun-btn-detail"
                                data-detail-akun
                                data-nama="Syalikha Fara"
                                data-role="Operator"
                                data-status="Aktif"
                                data-telepon="081234567890"
                                data-tahun="2025">
                                    Lihat Detail
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- MODAL DETAIL AKUN -->
    <div class="o-modal" id="modalDetailAkun" hidden>
        <div class="o-modal-card">

            <div class="o-modal-header">
                <button type="button" class="o-modal-close" data-close aria-label="Kembali">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M15 5L7 12l8 7z"/>
                    </svg>
                </button>
                <h2>Detail Akun</h2>
            </div>

            <div class="o-detail-list">
                <div><span>Nama Lengkap</span><strong id="detailNamaLengkap">-</strong></div>
                <div><span>Role</span><strong id="detailRole">-</strong></div>
                <div><span>Status</span><strong id="detailStatus">-</strong></div>
                <div><span>No. Telepon</span><strong id="detailTelepon">-</strong></div>
                <div><span>Tahun Masuk</span><strong id="detailTahunMasuk">-</strong></div>

                <div class="o-detail-action">
                    <button type="button" class="o-btn-edit" id="btnEditAkun">Edit Akun</button>
                </div>
            </div>

        </div>
    </div>
    <!-- MODAL EDIT AKUN -->
    <div class="o-modal" id="modalEditAkun" hidden>
        <div class="o-modal-card">

            <div class="o-modal-header">
                <button type="button" class="o-modal-close" data-close-edit aria-label="Kembali">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M15 5L7 12l8 7z"/>
                    </svg>
                </button>
                <h2>Edit Akun</h2>
            </div>

            <form id="formEditAkun">

                <div class="o-form-list">

                    <div class="o-field">
                        <label for="editNamaLengkap">Nama Lengkap</label>
                        <input type="text" id="editNamaLengkap" placeholder="Masukkan nama lengkap" required>
                    </div>

                    <div class="o-field">
                        <label for="editRole">Role</label>
                        <select id="editRole" required>
                            <option value="Admin">Admin</option>
                            <option value="Operator">Operator</option>
                            <option value="Tentor">Tentor</option>
                            <option value="Siswa">Siswa</option>
                        </select>
                    </div>

                    <div class="o-field">
                        <label for="editStatus">Status</label>
                        <select id="editStatus" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="o-field">
                        <label for="editTelepon">No. Telepon</label>
                        <input type="tel" id="editTelepon" placeholder="Masukkan no. telepon" required>
                    </div>

                    <div class="o-field">
                        <label for="editTahunMasuk">Tahun Masuk</label>
                        <input type="number" id="editTahunMasuk" placeholder="Masukkan tahun masuk" required>
                    </div>

                </div>

                <div class="o-form-action">
                    <button type="submit" class="o-btn-simpan">Simpan Perubahan</button>
                </div>

            </form>

        </div>
    </div>
</div>
<script>

document.addEventListener('DOMContentLoaded', function () {

    const el = id => document.getElementById(id);
    const tabel = el('akunBody');
    const modal = el('modalDetailAkun');
    const modalEdit = el('modalEditAkun');
    const btnEdit = el('btnEditAkun');
    const formEdit = el('formEditAkun');

    let tombolAktif = null;
    tabel.addEventListener('click', function (e) {

        const tombol = e.target.closest('[data-detail-akun]');

        if (!tombol) return;

        e.preventDefault();
        tombolAktif = tombol;
        el('detailNamaLengkap').textContent = tombol.dataset.nama || '-';
        el('detailRole').textContent = tombol.dataset.role || '-';
        el('detailStatus').textContent = tombol.dataset.status || '-';
        el('detailTelepon').textContent = tombol.dataset.telepon || '-';
        el('detailTahunMasuk').textContent = tombol.dataset.tahun || '-';

        modal.hidden = false;

    });

    btnEdit.addEventListener('click', function () {

        if (!tombolAktif) return;
        el('editNamaLengkap').value = tombolAktif.dataset.nama || '';
        el('editRole').value = tombolAktif.dataset.role || 'Siswa';
        el('editStatus').value = tombolAktif.dataset.status || 'Aktif';
        el('editTelepon').value = tombolAktif.dataset.telepon || '';
        el('editTahunMasuk').value = tombolAktif.dataset.tahun || '';

        modal.hidden = true;
        modalEdit.hidden = false;

    });

    formEdit.addEventListener('submit', function (e) {

        e.preventDefault();

        if (!tombolAktif) return;

        const nama = el('editNamaLengkap').value.trim();
        const role = el('editRole').value;
        const status = el('editStatus').value;
        const telepon = el('editTelepon').value.trim();
        const tahun = el('editTahunMasuk').value;

        if (!nama || !telepon || !tahun) {
            alert('Semua data harus diisi!');
            return;
        }

        tombolAktif.dataset.nama = nama;
        tombolAktif.dataset.role = role;
        tombolAktif.dataset.status = status;
        tombolAktif.dataset.telepon = telepon;
        tombolAktif.dataset.tahun = tahun;

        const baris = tombolAktif.closest('tr');

        baris.cells[0].textContent = nama;
        baris.cells[1].textContent = role;
        baris.cells[2].textContent = status;

        modalEdit.hidden = true;

        alert('Data akun berhasil diperbarui!');

    });
    modal.querySelector('[data-close]')
        .addEventListener('click', function () {

            modal.hidden = true;

        });
    modal.addEventListener('click', function (e) {

        if (e.target === modal) {
            modal.hidden = true;
        }

    });

    modalEdit.querySelector('[data-close-edit]')
        .addEventListener('click', function () {

            modalEdit.hidden = true;

        });


    modalEdit.addEventListener('click', function (e) {

        if (e.target === modalEdit) {
            modalEdit.hidden = true;
        }

    });

    document.addEventListener('keydown', function (e) {

        if (e.key === 'Escape') {
            modal.hidden = true;
            modalEdit.hidden = true;
        }

    });

    const cariAkun = el('cariAkun');
    const filterRole = el('filterRole');

    function filterAkun() {

        const keyword = cariAkun.value.toLowerCase().trim();
        const role = filterRole.value.toLowerCase();

        const baris = tabel.querySelectorAll('tr');

        baris.forEach(tr => {

            const nama = tr.cells[0].textContent.toLowerCase();
            const roleAkun = tr.cells[1].textContent.toLowerCase();

            const cocokNama = nama.includes(keyword);
            const cocokRole = role === '' || roleAkun === role;

            tr.style.display = cocokNama && cocokRole ? '' : 'none';

        });

    }

    cariAkun.addEventListener('input', filterAkun);
    filterRole.addEventListener('change', filterAkun);

});

</script>
@endsection