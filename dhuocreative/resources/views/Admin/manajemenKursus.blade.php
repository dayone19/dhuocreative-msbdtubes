@extends('layouts.admin')

@section('title', 'Manajemen Kursus')

@section('content')
@php
    $currentPage = $dataKursus->currentPage();
    $lastPage = $dataKursus->lastPage();
    $startPage = max(1, min($currentPage - 2, $lastPage - 4));
    $endPage = min($lastPage, $startPage + 4);
@endphp

<div class="ak-page">
    <section class="ak-tab-content active" id="tab-kursus">
        <div class="ak-toolbar">
            <h1 class="ak-page-title">Daftar Kursus</h1>

            <form method="GET" action="{{ route('admin.manajemenKursus') }}" style="display: contents;">
                <input
                    class="ak-search"
                    id="cariKursus"
                    name="cari_kursus"
                    type="search"
                    value="{{ request('cari_kursus') }}"
                    placeholder="Cari nama kursus..."
                    aria-label="Cari kursus"
                >
            </form>

            <button type="button" class="ak-btn ak-btn-orange" id="btnTambahKursus">
                <span class="ak-plus">+</span>
                Tambah Kursus
            </button>
        </div>

        <div class="ak-table-wrap">
            <table class="ak-table" id="tabelKursus">
                <thead>
                    <tr>
                        <th>Nama Kursus</th>
                        <th>Deskripsi</th>
                        <th>Durasi</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($dataKursus as $kursus)
                        <tr
                            data-id="{{ $kursus->id_kursus }}"
                            data-status="{{ $kursus->status }}"
                            data-nama="{{ $kursus->nama_kursus }}"
                            data-deskripsi="{{ $kursus->deskripsi }}"
                            data-durasi="{{ $kursus->durasi }}"
                            data-harga="{{ $kursus->harga }}"
                        >
                            <td>{{ $kursus->nama_kursus }}</td>
                            <td>{{ $kursus->deskripsi ?: '-' }}</td>
                            <td>{{ $kursus->durasi }} pertemuan</td>
                            <td>Rp {{ number_format($kursus->harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="ak-status {{ $kursus->status }}">
                                    {{ ucfirst($kursus->status) }}
                                </span>
                            </td>
                            <td>
                                <button
                                    type="button"
                                    class="ak-action"
                                    data-detail-kursus
                                >
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center;">
                                Belum ada data kursus.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($dataKursus->hasPages())
            <div class="ak-pagination">
                @if ($dataKursus->onFirstPage())
                    <button type="button" disabled>&laquo;</button>
                @else
                    <a href="{{ $dataKursus->previousPageUrl() }}">&laquo;</a>
                @endif

                @if ($startPage > 1)
                    <a href="{{ $dataKursus->url(1) }}">1</a>

                    @if ($startPage > 2)
                        <span>...</span>
                    @endif
                @endif

                @for ($page = $startPage; $page <= $endPage; $page++)
                    @if ($page == $currentPage)
                        <button type="button" class="active" disabled>
                            {{ $page }}
                        </button>
                    @else
                        <a href="{{ $dataKursus->url($page) }}">
                            {{ $page }}
                        </a>
                    @endif
                @endfor

                @if ($endPage < $lastPage)
                    @if ($endPage < $lastPage - 1)
                        <span>...</span>
                    @endif

                    <a href="{{ $dataKursus->url($lastPage) }}">
                        {{ $lastPage }}
                    </a>
                @endif

                @if ($dataKursus->hasMorePages())
                    <a href="{{ $dataKursus->nextPageUrl() }}">&raquo;</a>
                @else
                    <button type="button" disabled>&raquo;</button>
                @endif
            </div>
        @endif
    </section>

    {{-- DAFTAR KELAS --}}
    <section class="ak-tab-content" id="tab-kelas">
        <div class="ak-toolbar">
            <h2>Daftar Kelas</h2>

            <input
                class="ak-search"
                id="cariKelas"
                type="search"
                placeholder="Cari nama kursus atau kelas..."
                aria-label="Cari kelas"
            >

            <button type="button" class="ak-btn ak-btn-orange" id="btnTambahKelas">
                <span class="ak-plus">+</span>
                Tambah Kelas
            </button>
        </div>

        <div class="ak-table-wrap">
            <table class="ak-table" id="tabelKelas">
                <thead>
                    <tr>
                        <th>Nama Kursus</th>
                        <th>Nama Kelas</th>
                        <th>Kapasitas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <tr data-kursus="Web Master">
                        <td>Web Master</td>
                        <td>001</td>
                        <td>20</td>
                        <td><span class="ak-status aktif">Aktif</span></td>
                        <td>
                            <button type="button" class="ak-action" data-detail-kelas>
                                Detail
                            </button>
                        </td>
                    </tr>

                    <tr data-kursus="Web Master">
                        <td>Web Master</td>
                        <td>002</td>
                        <td>20</td>
                        <td><span class="ak-status aktif">Aktif</span></td>
                        <td>
                            <button type="button" class="ak-action" data-detail-kelas>
                                Detail
                            </button>
                        </td>
                    </tr>

                    <tr data-kursus="MS Office">
                        <td>MS Office</td>
                        <td>001</td>
                        <td>15</td>
                        <td><span class="ak-status aktif">Aktif</span></td>
                        <td>
                            <button type="button" class="ak-action" data-detail-kelas>
                                Detail
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p class="ak-empty" id="kosongKelas" hidden>
            Kelas tidak ditemukan.
        </p>
    </section>
</div>

{{-- POPUP BERHASIL --}}
@if (session('success'))
    <div class="ak-success-popup" id="successPopup">
        <div class="ak-success-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>

        <div class="ak-success-message">
            <strong>Berhasil!</strong>
            <span>{{ session('success') }}</span>
        </div>

        <button type="button" onclick="closeSuccessPopup()">&times;</button>
    </div>
@endif

{{-- MODAL TAMBAH / EDIT KURSUS --}}
<div class="ak-modal" id="modalKursus" hidden>
    <div class="ak-modal-box">
        <div class="ak-modal-header">
            <button type="button" class="ak-modal-close" data-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>

            <h2>Tambah Kursus</h2>
        </div>

        <form id="formKursus" method="POST" action="{{ route('admin.manajemenKursus.store') }}">
            @csrf

            <input type="hidden" name="_method" id="methodKursus" value="POST">

            <div class="ak-form-group">
                <label for="namaKursus">Nama Kursus</label>
                <input
                    id="namaKursus"
                    name="nama_kursus"
                    maxlength="100"
                    required
                    placeholder="Contoh: Web Master"
                >
            </div>

            <div class="ak-form-group">
                <label for="deskripsiKursus">Deskripsi</label>
                <textarea
                    id="deskripsiKursus"
                    name="deskripsi"
                    rows="3"
                    placeholder="Deskripsi kursus"
                ></textarea>
            </div>

            <div class="ak-form-group">
                <label for="durasiKursus">Durasi</label>
                <input
                    id="durasiKursus"
                    name="durasi"
                    type="number"
                    min="1"
                    required
                    placeholder="Masukkan durasi"
                >
            </div>

            <div class="ak-form-group">
                <label for="hargaKursus">Harga Kursus (Rp)</label>
                <input
                    id="hargaKursus"
                    name="harga"
                    type="number"
                    min="0"
                    required
                    placeholder="Masukkan harga kursus"
                >
            </div>

            <div class="ak-form-group">
                <label for="statusKursus">Status</label>
                <select id="statusKursus" name="status" required>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>

            <button class="ak-btn ak-btn-orange ak-btn-full" type="submit">
                Simpan Kursus
            </button>
        </form>
    </div>
</div>

{{-- MODAL TAMBAH / EDIT KELAS --}}
<div class="ak-modal" id="modalKelas" hidden>
    <div class="ak-modal-box">
        <div class="ak-modal-header">
            <button type="button" class="ak-modal-close" data-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>

            <h2>Tambah Kelas</h2>
        </div>

        <form id="formKelas">
            <div class="ak-form-group">
                <label for="kursusKelas">Nama Kursus</label>
                <select id="kursusKelas" required>
                    <option value="">Pilih kursus</option>
                </select>
            </div>

            <div class="ak-form-group">
                <label for="namaKelas">Nama Kelas</label>
                <input id="namaKelas" maxlength="100" required placeholder="Contoh: 001">
            </div>

            <div class="ak-form-group">
                <label for="kapasitasKelas">Kapasitas</label>
                <input id="kapasitasKelas" type="number" min="1" required>
            </div>

            <div class="ak-form-group">
                <label for="statusKelas">Status</label>
                <select id="statusKelas" required>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>

            <button class="ak-btn ak-btn-orange ak-btn-full" type="submit">
                Simpan Kelas
            </button>
        </form>
    </div>
</div>

{{-- MODAL DETAIL KURSUS --}}
<div class="ak-modal" id="detailModalKursus" hidden>
    <div class="ak-modal-box">
        <div class="ak-modal-header">
            <button type="button" class="ak-modal-close" data-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>

            <h2>Detail Kursus</h2>
        </div>

        <div class="ak-detail-list">
            <div>
                <span>Nama Kursus</span>
                <strong id="dNamaKursus">-</strong>
            </div>

            <div>
                <span>Deskripsi</span>
                <strong id="dDeskripsiKursus">-</strong>
            </div>

            <div>
                <span>Durasi</span>
                <strong id="dDurasiKursus">-</strong>
            </div>

            <div>
                <span>Harga</span>
                <strong id="dHargaKursus">-</strong>
            </div>

            <div>
                <span>Status</span>
                <strong id="dStatusKursus">-</strong>
            </div>
        </div>

        <button type="button" class="ak-btn ak-btn-orange ak-btn-full" id="editDetailKursus">
            Edit Kursus
        </button>

        <form
            id="formNonaktifkanKursus"
            method="POST"
            action=""
            style="margin-top: 10px;"
        >
            @csrf
            @method('PATCH')

            <button
                type="submit"
                class="ak-btn ak-btn-full"
                id="btnNonaktifkanKursus"
                style="background: #dc3545; color: white; border: none;"
            >
                Nonaktifkan Kursus
            </button>
        </form>
    </div>
</div>

{{-- MODAL DETAIL KELAS --}}
<div class="ak-modal" id="detailModalKelas" hidden>
    <div class="ak-modal-box">
        <div class="ak-modal-header">
            <button type="button" class="ak-modal-close" data-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>

            <h2>Detail Kelas</h2>
        </div>

        <div class="ak-detail-list">
            <div>
                <span>Nama Kursus</span>
                <strong id="dKursusKelas">-</strong>
            </div>

            <div>
                <span>Nama Kelas</span>
                <strong id="dNamaKelas">-</strong>
            </div>

            <div>
                <span>Kapasitas</span>
                <strong id="dKapasitasKelas">-</strong>
            </div>

            <div>
                <span>Status</span>
                <strong id="dStatusKelas">-</strong>
            </div>
        </div>

        <button type="button" class="ak-btn ak-btn-orange ak-btn-full" id="editDetailKelas">
            Edit Kelas
        </button>
    </div>
</div>

<script>
(() => {
    const el = id => document.getElementById(id);

    const mk = el('modalKursus');
    const mc = el('modalKelas');
    const dk = el('detailModalKursus');
    const dc = el('detailModalKelas');

    let editingClass = null;
    let selectedCourse = null;
    let selectedClass = null;

    function courseNames() {
        return [...new Set(
            [...el('tabelKursus').tBodies[0].rows]
                .filter(row => row.dataset.nama)
                .map(row => row.dataset.nama)
        )];
    }

    function fillCourses() {
        const select = el('kursusKelas');
        const old = select.value;

        select.replaceChildren(new Option('Pilih kursus', ''));

        courseNames().forEach(name => {
            select.add(new Option(name, name));
        });

        if (courseNames().includes(old)) {
            select.value = old;
        }
    }

    document.querySelectorAll('.ak-tab').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.ak-tab, .ak-tab-content')
                .forEach(item => item.classList.remove('active'));

            button.classList.add('active');
            el('tab-' + button.dataset.tab).classList.add('active');
        });
    });

    function filter(input, table, empty) {
        const query = el(input).value.toLowerCase().trim();
        let count = 0;

        el(table).querySelectorAll('tbody tr').forEach(row => {
            row.hidden = !row.textContent.toLowerCase().includes(query);

            if (!row.hidden) count++;
        });

        el(empty).hidden = count > 0;
    }

    el('cariKelas').addEventListener('input', () => {
        filter('cariKelas', 'tabelKelas', 'kosongKelas');
    });

    el('btnTambahKursus').addEventListener('click', () => {
        el('formKursus').reset();
        el('formKursus').action = "{{ route('admin.manajemenKursus.store') }}";
        el('methodKursus').value = 'POST';

        mk.querySelector('h2').textContent = 'Tambah Kursus';
        el('formKursus').querySelector('[type=submit]').textContent = 'Simpan Kursus';

        mk.hidden = false;
    });

    el('btnTambahKelas').addEventListener('click', () => {
        editingClass = null;
        el('formKelas').reset();

        mc.querySelector('h2').textContent = 'Tambah Kelas';
        el('formKelas').querySelector('[type=submit]').textContent = 'Simpan Kelas';

        fillCourses();
        mc.hidden = false;
    });

    document.querySelectorAll('[data-close]').forEach(button => {
        button.addEventListener('click', () => {
            button.closest('.ak-modal').hidden = true;
        });
    });

    // DETAIL KURSUS
    el('tabelKursus').addEventListener('click', event => {
        const button = event.target.closest('[data-detail-kursus]');
        if (!button) return;

        selectedCourse = button.closest('tr');

        el('dNamaKursus').textContent = selectedCourse.dataset.nama;
        el('dDeskripsiKursus').textContent = selectedCourse.dataset.deskripsi || '-';
        el('dDurasiKursus').textContent = selectedCourse.dataset.durasi + ' pertemuan';

        el('dHargaKursus').textContent =
            'Rp ' + Number(selectedCourse.dataset.harga).toLocaleString('id-ID');

        el('dStatusKursus').textContent =
            selectedCourse.dataset.status === 'aktif' ? 'Aktif' : 'Nonaktif';

        const formNonaktif = el('formNonaktifkanKursus');
        const buttonNonaktif = el('btnNonaktifkanKursus');

        if (selectedCourse.dataset.status === 'nonaktif') {
            formNonaktif.action =
                "{{ url('/admin/manajemenKursus') }}/" +
                selectedCourse.dataset.id + "/aktifkan";

            formNonaktif.onsubmit = () =>
                confirm('Yakin ingin mengaktifkan kembali kursus ini?');

            buttonNonaktif.textContent = 'Aktifkan Kembali';
            buttonNonaktif.style.background = '#286c65';
        } else {
            formNonaktif.action =
                "{{ url('/admin/manajemenKursus') }}/" +
                selectedCourse.dataset.id + "/nonaktifkan";

            formNonaktif.onsubmit = () =>
                confirm('Yakin ingin menonaktifkan kursus ini?');

            buttonNonaktif.textContent = 'Nonaktifkan Kursus';
            buttonNonaktif.style.background = '#dc3545';
        }

        dk.hidden = false;
    });

    // DETAIL KELAS
    el('tabelKelas').addEventListener('click', event => {
        const button = event.target.closest('[data-detail-kelas]');
        if (!button) return;

        selectedClass = button.closest('tr');

        const cells = selectedClass.cells;

        el('dKursusKelas').textContent = cells[0].textContent.trim();
        el('dNamaKelas').textContent = cells[1].textContent.trim();
        el('dKapasitasKelas').textContent = cells[2].textContent.trim();

        el('dStatusKelas').textContent =
            cells[3].textContent.includes('Aktif') ? 'Aktif' : 'Nonaktif';

        dc.hidden = false;
    });

    // EDIT KURSUS
    el('editDetailKursus').addEventListener('click', () => {
        if (!selectedCourse) return;

        el('namaKursus').value = selectedCourse.dataset.nama;
        el('deskripsiKursus').value = selectedCourse.dataset.deskripsi || '';
        el('durasiKursus').value = selectedCourse.dataset.durasi;
        el('hargaKursus').value = selectedCourse.dataset.harga;
        el('statusKursus').value = selectedCourse.dataset.status;

        el('formKursus').action =
            "{{ url('/admin/manajemenKursus') }}/" +
            selectedCourse.dataset.id;

        el('methodKursus').value = 'PUT';

        mk.querySelector('h2').textContent = 'Edit Kursus';
        el('formKursus').querySelector('[type=submit]').textContent = 'Simpan Perubahan';

        dk.hidden = true;
        mk.hidden = false;
    });

    // EDIT KELAS
    el('editDetailKelas').addEventListener('click', () => {
        if (!selectedClass) return;

        editingClass = selectedClass;
        fillCourses();

        el('kursusKelas').value = selectedClass.cells[0].textContent.trim();
        el('namaKelas').value = selectedClass.cells[1].textContent.trim();
        el('kapasitasKelas').value = selectedClass.cells[2].textContent.trim();

        el('statusKelas').value =
            selectedClass.cells[3].textContent.includes('Aktif') ? 'aktif' : 'nonaktif';

        mc.querySelector('h2').textContent = 'Edit Kelas';
        el('formKelas').querySelector('[type=submit]').textContent = 'Simpan Perubahan';

        dc.hidden = true;
        mc.hidden = false;
    });

    function statusBadge(status) {
        const badge = document.createElement('span');

        badge.className = 'ak-status ' + status;
        badge.textContent = status === 'aktif' ? 'Aktif' : 'Nonaktif';

        return badge;
    }

    // FORM KELAS SEMENTARA (BELUM TERHUBUNG DATABASE)
    el('formKelas').addEventListener('submit', event => {
        event.preventDefault();

        const name = el('kursusKelas').value;
        const className = el('namaKelas').value.trim();
        const body = el('tabelKelas').tBodies[0];
        const target = editingClass;

        if ([...body.rows].some(row =>
            row !== target &&
            row.cells[0].textContent.trim() === name &&
            row.cells[1].textContent.trim().toLowerCase() === className.toLowerCase()
        )) {
            alert('Nama kelas tersebut sudah digunakan untuk kursus ini.');
            return;
        }

        const row = target || body.insertRow();
        const values = [
            name,
            className,
            el('kapasitasKelas').value
        ];

        values.forEach((value, index) => {
            if (row.cells[index]) {
                row.cells[index].textContent = value;
            } else {
                const cell = row.insertCell();
                cell.textContent = value;
            }
        });

        row.dataset.kursus = name;

        const status = el('statusKelas').value;
        const statusCell = row.cells[3] || row.insertCell();

        statusCell.replaceChildren(statusBadge(status));

        if (!row.cells[4]) {
            const actionCell = row.insertCell();
            const button = document.createElement('button');

            button.type = 'button';
            button.className = 'ak-action';
            button.dataset.detailKelas = '';
            button.textContent = 'Detail';

            actionCell.appendChild(button);
        }

        editingClass = null;
        mc.hidden = true;

        filter('cariKelas', 'tabelKelas', 'kosongKelas');
    });

    // TUTUP MODAL
    [mk, mc, dk, dc].forEach(modal => {
        modal.addEventListener('click', event => {
            if (event.target === modal) {
                modal.hidden = true;
            }
        });
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            [mk, mc, dk, dc].forEach(modal => {
                modal.hidden = true;
            });
        }
    });

    // POPUP BERHASIL
    const successPopup = el('successPopup');

    window.closeSuccessPopup = function () {
        const popup = el('successPopup');

        if (popup) {
            popup.style.opacity = '0';
            popup.style.transform = 'translateX(30px)';

            setTimeout(() => popup.remove(), 300);
        }
    };

    if (successPopup) {
        setTimeout(() => {
            window.closeSuccessPopup();
        }, 3000);
    }
})();
</script>
@endsection
