```blade
@extends('layouts.Operator')

@section('title', 'Daftar Siswa')

@section('content')
<div class="operator-siswa">

    <div class="siswa-toolbar">
        <div class="siswa-toolbar-content">
            <div class="search-box">
                <h1>Daftar Siswa</h1>
                <input
                    type="text"
                    id="searchSiswa"
                    placeholder="Cari siswa..."
                >
                <span class="search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#f27640" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </span>
            </div>

            <div class="filter-area">
                <select id="filterStatus">
                    <option value="">Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="tidak aktif">Tidak aktif</option>
                </select>

                <select id="filterTahun">
                    <option value="">Tahun</option>
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                </select>

                <select id="filterKelas">
                    <option value="">Kelas</option>
                    <option value="web master">Web Master</option>
                    <option value="ui/ux design">UI/UX Design</option>
                    <option value="digital marketing">Digital Marketing</option>
                </select>
            </div>

            <button type="button" class="btn-tambah-siswa"
                onclick="bukaFormTambah()">
                <span>Tambah<br>Siswa</span>
                <span class="tmt-plus">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                    </svg>
                </span>
            </button>
        </div>
    </div>

    <div class="siswa-table-container">
        <table class="siswa-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nama</th>
                    <th>Status Siswa</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="daftarSiswa">
                <tr data-status="aktif" data-tahun="2026"
                    data-kelas="web master" data-nama="belerick arbiter of star">
                    <td>1208</td>
                    <td>Belerick Arbiter of Star</td>
                    <td>Aktif</td>
                    <td><button class="btn-detail" onclick="lihatDetail('1208','Belerick Arbiter of Star','Aktif')">Detail</button></td>
                </tr>

                <tr data-status="tidak aktif" data-tahun="2025"
                    data-kelas="ui/ux design" data-nama="belerick arbiter of star">
                    <td>1208</td>
                    <td>Belerick Arbiter of Star</td>
                    <td>Tidak aktif</td>
                    <td><button class="btn-detail" onclick="lihatDetail('1208','Belerick Arbiter of Star','Tidak aktif')">Detail</button></td>
                </tr>

                <tr data-status="aktif" data-tahun="2026"
                    data-kelas="digital marketing" data-nama="belerick arbiter of star">
                    <td>1208</td>
                    <td>Belerick Arbiter of Star</td>
                    <td>Aktif</td>
                    <td><button class="btn-detail" onclick="lihatDetail('1208','Belerick Arbiter of Star','Aktif')">Detail</button></td>
                </tr>

                <tr data-status="tidak aktif" data-tahun="2024"
                    data-kelas="web master" data-nama="belerick arbiter of star">
                    <td>1208</td>
                    <td>Belerick Arbiter of Star</td>
                    <td>Tidak aktif</td>
                    <td><button class="btn-detail" onclick="lihatDetail('1208','Belerick Arbiter of Star','Tidak aktif')">Detail</button></td>
                </tr>

                <tr data-status="aktif" data-tahun="2025"
                    data-kelas="ui/ux design" data-nama="belerick arbiter of star">
                    <td>1208</td>
                    <td>Belerick Arbiter of Star</td>
                    <td>Aktif</td>
                    <td><button class="btn-detail" onclick="lihatDetail('1208','Belerick Arbiter of Star','Aktif')">Detail</button></td>
                </tr>
            </tbody>
        </table>

        <p id="tidakAdaSiswa" class="empty-state" hidden>
            Siswa tidak ditemukan.
        </p>
    </div>

    <div id="modalSiswa" class="modal-siswa" hidden>
        <div class="modal-siswa-content">
            <button type="button" class="modal-close"
                onclick="tutupModal()">×</button>

            <h2 id="modalJudul">Tambah Siswa</h2>

            <form id="formSiswa">
                <div class="form-group">
                    <label for="siswaId">ID Siswa</label>
                    <input class="form-control" id="siswaId"
                        placeholder="Masukkan ID siswa" required>
                </div>

                <div class="form-group">
                    <label for="siswaNama">Nama Siswa</label>
                    <input class="form-control" id="siswaNama"
                        placeholder="Masukkan nama siswa" required>
                </div>

                <div class="form-group">
                    <label for="siswaStatus">Status</label>
                    <select class="form-control" id="siswaStatus">
                        <option value="aktif">Aktif</option>
                        <option value="tidak aktif">Tidak aktif</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="siswaTahun">Tahun</label>
                    <select class="form-control" id="siswaTahun">
                        <option value="2026">2026</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="siswaKelas">Kelas</label>
                    <select class="form-control" id="siswaKelas">
                        <option value="web master">Web Master</option>
                        <option value="ui/ux design">UI/UX Design</option>
                        <option value="digital marketing">Digital Marketing</option>
                    </select>
                </div>

                <button type="submit" class="btn-simpan-siswa">
                    Simpan Siswa
                </button>
            </form>
        </div>
    </div>

</div>

<script>
    const searchSiswa = document.getElementById('searchSiswa');
    const filterStatus = document.getElementById('filterStatus');
    const filterTahun = document.getElementById('filterTahun');
    const filterKelas = document.getElementById('filterKelas');
    const daftarSiswa = document.getElementById('daftarSiswa');
    const tidakAdaSiswa = document.getElementById('tidakAdaSiswa');
    const modalSiswa = document.getElementById('modalSiswa');

    function filterDaftarSiswa() {
        const keyword = searchSiswa.value.toLowerCase().trim();
        let jumlahTampil = 0;

        daftarSiswa.querySelectorAll('tr').forEach(row => {
            const cocokNama = row.dataset.nama.includes(keyword);
            const cocokStatus = !filterStatus.value ||
                row.dataset.status === filterStatus.value;
            const cocokTahun = !filterTahun.value ||
                row.dataset.tahun === filterTahun.value;
            const cocokKelas = !filterKelas.value ||
                row.dataset.kelas === filterKelas.value;

            const tampil = cocokNama && cocokStatus &&
                cocokTahun && cocokKelas;

            row.hidden = !tampil;
            if (tampil) jumlahTampil++;
        });

        tidakAdaSiswa.hidden = jumlahTampil > 0;
    }

    [searchSiswa, filterStatus, filterTahun, filterKelas]
        .forEach(input => input.addEventListener(
            input.tagName === 'INPUT' ? 'input' : 'change',
            filterDaftarSiswa
        ));

    function bukaFormTambah() {
        document.getElementById('formSiswa').reset();
        document.getElementById('modalJudul').textContent = 'Tambah Siswa';
        document.getElementById('siswaId').readOnly = false;
        modalSiswa.hidden = false;
    }

    function tutupModal() {
        modalSiswa.hidden = true;
    }

    function lihatDetail(id, nama, status) {
        alert(
            'Detail Siswa\\n\\n' +
            'ID: ' + id + '\\n' +
            'Nama: ' + nama + '\\n' +
            'Status: ' + status
        );
    }

    document.getElementById('formSiswa').addEventListener('submit', function(e) {
        e.preventDefault();

        const id = document.getElementById('siswaId').value.trim();
        const nama = document.getElementById('siswaNama').value.trim();
        const status = document.getElementById('siswaStatus').value;
        const tahun = document.getElementById('siswaTahun').value;
        const kelas = document.getElementById('siswaKelas').value;

        if (!id || !nama) return;

        const sudahAda = [...daftarSiswa.querySelectorAll('tr')]
            .some(row => row.cells[0].textContent.trim() === id);

        if (sudahAda) {
            alert('ID siswa sudah digunakan.');
            return;
        }

        const row = document.createElement('tr');
        row.dataset.status = status;
        row.dataset.tahun = tahun;
        row.dataset.kelas = kelas;
        row.dataset.nama = nama.toLowerCase();

        const tdId = document.createElement('td');
        tdId.textContent = id;

        const tdNama = document.createElement('td');
        tdNama.textContent = nama;

        const tdStatus = document.createElement('td');
        tdStatus.textContent = status === 'aktif' ? 'Aktif' : 'Tidak aktif';

        const tdAksi = document.createElement('td');
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn-detail';
        btn.textContent = 'Detail';
        btn.addEventListener('click', () =>
            lihatDetail(id, nama, tdStatus.textContent)
        );

        tdAksi.appendChild(btn);
        row.append(tdId, tdNama, tdStatus, tdAksi);
        daftarSiswa.appendChild(row);

        tutupModal();
        filterDaftarSiswa();
        alert('Siswa berhasil ditambahkan ke tampilan.');
    });

    modalSiswa.addEventListener('click', function(e) {
        if (e.target === modalSiswa) tutupModal();
    });
</script>
@endsection
```