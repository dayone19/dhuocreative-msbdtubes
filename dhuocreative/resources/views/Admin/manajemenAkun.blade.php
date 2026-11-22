@extends('layouts.admin')

@section('content')
<div class="akun-page">

    {{-- HEADER --}}
    <div class="akun-header">
        <h1 class="akun-title">Manajemen Akun</h1>
        <a href="{{ route('admin.buatAkun.store') }}" class="t-btn t-btn-orange akun-btn-buat">
            <span class="t-plus">+</span> Buat Akun
        </a>
    </div>

    {{-- PESAN VALIDASI --}}
    @if ($errors->any())
        <div class="akun-alert">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- SEARCH DAN FILTER --}}
    <form action="{{ route('admin.manajemenAkun') }}" method="GET" class="akun-toolbar">
        <div class="t-search-wrap">
            <input
                type="text"
                class="t-search"
                name="search"
                id="cariAkun"
                placeholder="Cari nama"
                value="{{ request('search') }}"
            >
            <svg viewBox="0 0 24 24">
                <circle cx="10" cy="10" r="7"/>
                <path d="M15.5 15.5 21 21"/>
            </svg>
        </div>

        <select class="t-filter akun-select" name="role" id="filterRole">
            <option value=""><b>Semua Role</b></option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="operator" {{ request('role') == 'operator' ? 'selected' : '' }}>Operator</option>
            <option value="tentor" {{ request('role') == 'tentor' ? 'selected' : '' }}>Tentor</option>
            <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
        </select>
    </form>

    {{-- TABEL AKUN --}}
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
                @forelse ($akun as $item)
                    <tr>
                        <td>{{ $item->nama }}</td>
                        <td>{{ ucfirst($item->role) }}</td>
                        <td>{{ ucfirst($item->status) }}</td>
                        <td>
                            <div class="akun-aksi">
                                <a href="#"
                                    class="akun-btn-detail"
                                    data-detail-akun
                                    data-id="{{ $item->id_user }}"
                                    data-nama="{{ $item->nama }}"
                                    data-role="{{ $item->role }}"
                                    data-status="{{ $item->status }}"
                                    data-telepon="{{ $item->no_telp }}"
                                    data-tahun="{{ $item->tahun_masuk }}"
                                    data-update-url="{{ route('admin.manajemenAkun.update', $item->id_user) }}"
                                    data-nonaktif-url="{{ route('admin.manajemenAkun.nonaktifkan', $item->id_user) }}"
                                    data-aktifkan-url="{{ route('admin.manajemenAkun.aktifkan', $item->id_user) }}">
                                    Lihat Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">
                            Belum ada akun ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="akun-pagination">

        @php
            $halamanAktif = $akun->currentPage();
            $halamanTerakhir = $akun->lastPage();

            if ($halamanTerakhir <= 5) {
                $halaman = range(1, $halamanTerakhir);
            } elseif ($halamanAktif <= 3) {
                $halaman = [1, 2, 3, 4, $halamanTerakhir];
            } elseif ($halamanAktif >= $halamanTerakhir - 2) {
                $halaman = [
                    1,
                    $halamanTerakhir - 3,
                    $halamanTerakhir - 2,
                    $halamanTerakhir - 1,
                    $halamanTerakhir
                ];
            } else {
                $halaman = [
                    1,
                    $halamanAktif - 1,
                    $halamanAktif,
                    $halamanAktif + 1,
                    $halamanTerakhir
                ];
            }
        @endphp

        {{-- SEBELUMNYA --}}
        @if ($akun->onFirstPage())
            <span class="page-disabled">‹</span>
        @else
            <a href="{{ $akun->previousPageUrl() }}" class="page-btn">‹</a>
        @endif

        {{-- NOMOR HALAMAN --}}
        @foreach ($halaman as $index => $nomor)

            @if ($index == 1 && $nomor - $halaman[$index - 1] > 1)
                <span class="page-dots">…</span>
            @endif

            @if ($nomor == $halamanAktif)
                <span class="page-btn active">{{ $nomor }}</span>
            @else
                <a href="{{ $akun->url($nomor) }}" class="page-btn">
                    {{ $nomor }}
                </a>
            @endif

        @endforeach

        {{-- SELANJUTNYA --}}
        @if ($akun->hasMorePages())
            <a href="{{ $akun->nextPageUrl() }}" class="page-btn">›</a>
        @else
            <span class="page-disabled">›</span>
        @endif

    </div>

    {{-- MODAL DETAIL AKUN --}}
    <div class="o-modal" id="modalDetailAkun" hidden>
        <div class="o-modal-card">

            <div class="o-modal-header">
                <button type="button" class="ak-modal-close" data-close aria-label="Kembali">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
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

                

                    {{-- EDIT AKUN --}}
                    <button type="button" class="o-btn-edit" id="btnEditAkun">
                        Edit Akun
                    </button>

                    {{-- AKTIFKAN / NONAKTIFKAN AKUN --}}
                    <form id="formNonaktifkanAkun" method="POST">
                        @csrf
                        @method('PATCH')

                        <button type="submit"
                            class="o-btn-edit"
                            id="btnUbahStatusAkun">
                            Nonaktifkan Akun
                        </button>
                    </form>

                
            </div>

        </div>
    </div>

    {{-- MODAL EDIT AKUN --}}
    <div class="o-modal" id="modalEditAkun" hidden>
        <div class="o-modal-card">

            <div class="o-modal-header">
                <button type="button" class="ak-modal-close" data-close-edit aria-label="Kembali">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                    </svg>
                </button>
                <h2>Edit Akun</h2>
            </div>

            <form id="formEditAkun" method="POST">
                @csrf
                @method('PUT')

                <div class="o-form-list">

                    <div class="o-field">
                        <label for="editNamaLengkap">Nama Lengkap</label>
                        <input
                            type="text"
                            id="editNamaLengkap"
                            name="nama"
                            placeholder="Masukkan nama lengkap"
                            required
                        >
                    </div>

                    <div class="o-field">
                        <label for="editRole">Role</label>
                        <select id="editRole" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="operator">Operator</option>
                            <option value="tentor">Tentor</option>
                            <option value="siswa">Siswa</option>
                        </select>
                    </div>

                    <div class="o-field">
                        <label for="editStatus">Status</label>
                        <select id="editStatus" name="status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="o-field">
                        <label for="editTelepon">No. Telepon</label>
                        <input
                            type="tel"
                            id="editTelepon"
                            name="no_telp"
                            placeholder="Masukkan no. telepon"
                        >
                    </div>

                    <div class="o-field">
                        <label for="editTahunMasuk">Tahun Masuk</label>
                        <input
                            type="number"
                            id="editTahunMasuk"
                            name="tahun_masuk"
                            placeholder="Masukkan tahun masuk"
                            required
                        >
                    </div>

                </div>

                <div class="o-form-action">
                    <button type="submit" class="o-btn-simpan">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- POP-UP BERHASIL --}}
    @if (session('success'))
        <div class="akun-popup-overlay" id="popupBerhasil">
            <div class="akun-popup">
                <div class="akun-popup-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M5 12.5L10 17L19 7"/>
                    </svg>
                </div>

                <h2>Berhasil!</h2>
                <p>{{ session('success') }}</p>

                <button type="button" id="btnTutupPopup">
                    Oke
                </button>
            </div>
        </div>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const el = id => document.getElementById(id);

    const tabel = el('akunBody');
    const modal = el('modalDetailAkun');
    const modalEdit = el('modalEditAkun');

    const btnEdit = el('btnEditAkun');
    const formEdit = el('formEditAkun');
    const formNonaktifkan = el('formNonaktifkanAkun');
    const btnUbahStatus = el('btnUbahStatusAkun');

    let tombolAktif = null;

    // MEMBUKA DETAIL AKUN
    tabel.addEventListener('click', function (e) {

        const tombol = e.target.closest('[data-detail-akun]');

        if (!tombol) return;

        e.preventDefault();

        tombolAktif = tombol;

        el('detailNamaLengkap').textContent = tombol.dataset.nama || '-';
        el('detailRole').textContent = tombol.dataset.role || '-';
        el('detailStatus').textContent =
            tombol.dataset.status === 'nonaktif' ? 'Tidak Aktif' : 'Aktif';
        el('detailTelepon').textContent = tombol.dataset.telepon || '-';
        el('detailTahunMasuk').textContent = tombol.dataset.tahun || '-';

        // MENENTUKAN AKSI BERDASARKAN STATUS AKUN
        if (tombol.dataset.status === 'nonaktif') {
            formNonaktifkan.action = tombol.dataset.aktifkanUrl;
            btnUbahStatus.textContent = 'Aktifkan Akun';
            btnUbahStatus.onclick = function () {
                return confirm('Yakin ingin mengaktifkan akun ini?');
            };
        } else {
            formNonaktifkan.action = tombol.dataset.nonaktifUrl;
            btnUbahStatus.textContent = 'Nonaktifkan Akun';
            btnUbahStatus.onclick = function () {
                return confirm('Yakin ingin menonaktifkan akun ini?');
            };
        }

        modal.hidden = false;

    });

    // MEMBUKA EDIT AKUN
    btnEdit.addEventListener('click', function () {

        if (!tombolAktif) return;

        el('editNamaLengkap').value = tombolAktif.dataset.nama || '';
        el('editRole').value = tombolAktif.dataset.role || 'siswa';
        el('editStatus').value = tombolAktif.dataset.status || 'aktif';
        el('editTelepon').value = tombolAktif.dataset.telepon || '';
        el('editTahunMasuk').value = tombolAktif.dataset.tahun || '';

        // MENENTUKAN URL UPDATE
        formEdit.action = tombolAktif.dataset.updateUrl;

        modal.hidden = true;
        modalEdit.hidden = false;

    });

    // MENUTUP MODAL DETAIL
    modal.querySelector('[data-close]').addEventListener('click', function () {
        modal.hidden = true;
    });

    modal.addEventListener('click', function (e) {
        if (e.target === modal) {
            modal.hidden = true;
        }
    });

    // MENUTUP MODAL EDIT
    modalEdit.querySelector('[data-close-edit]').addEventListener('click', function () {
        modalEdit.hidden = true;
    });

    modalEdit.addEventListener('click', function (e) {
        if (e.target === modalEdit) {
            modalEdit.hidden = true;
        }
    });

    // TOMBOL ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            modal.hidden = true;
            modalEdit.hidden = true;
        }
    });

    // PENCARIAN OTOMATIS
    const formCari = document.querySelector('.akun-toolbar');
    const inputCari = el('cariAkun');
    const filterRole = el('filterRole');

    let waktuCari;

    inputCari.addEventListener('input', function () {
        clearTimeout(waktuCari);

        waktuCari = setTimeout(function () {
            formCari.submit();
        }, 500);
    });

    filterRole.addEventListener('change', function () {
        formCari.submit();
    });

    // POP-UP BERHASIL
    const popup = el('popupBerhasil');
    const btnTutupPopup = el('btnTutupPopup');

    if (popup) {

        btnTutupPopup.addEventListener('click', function () {
            popup.remove();
        });

        popup.addEventListener('click', function (e) {
            if (e.target === popup) {
                popup.remove();
            }
        });

        // OTOMATIS TERTUTUP SETELAH 3 DETIK
        setTimeout(function () {
            if (popup) {
                popup.remove();
            }
        }, 3000);

    }
});
</script>
@endsection