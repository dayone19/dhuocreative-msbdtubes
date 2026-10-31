@extends('layouts.tentor')

@section('title', 'Tugas & Nilai')

@section('content')
<div class="tn-page">

    <div class="tn-heading">
        <a href="{{ route('tentor.tugas') }}" class="tmt-back-btn" aria-label="Kembali">
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
            <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
            </svg>
        </a>

        <div class="tn-heading-info">
            <h1>{{ $tugas->judul}}</h1>
            <p>{{ $tugas->nama_kelas}}</p>
        </div>
    </div>

    @if (session('sukses'))
        <p style="background:#e3f6e8;color:#1d6b35;padding:10px 14px;border-radius:10px;margin:10px 0;">{{ session('sukses') }}</p>
    @endif
    @if ($errors->any())
        <p style="background:#fde8e8;color:#a12222;padding:10px 14px;border-radius:10px;margin:10px 0;">{{ $errors->first() }}</p>
    @endif

    <div class="tn-toolbar">
        <div class="tn-search">
            <input type="text"
                   id="cariSiswaNilai"
                   placeholder="Cari Siswa..."
                   autocomplete="off">

            <svg viewBox="0 0 24 24" width="34" height="34"
                 fill="none" stroke="currentColor"
                 stroke-width="1.7">
                <circle cx="10.5" cy="10.5" r="7.5"/>
                <path d="M16 16L22 22"/>
            </svg>
        </div>
    </div>

    <div class="tn-table-container">
        <table class="tn-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>File</th>
                    <th>Tanggal<br>Upload</th>
                    <th>Nilai</th>
                </tr>
            </thead>

            <tbody id="daftarNilaiBody">
                @foreach ($daftarNilai as $siswa)
                    <tr data-id="{{ $siswa['id'] }}">
                        <td class="tn-nama">
                            {{ $siswa['nama'] }}
                        </td>

                        {{-- FILE TUGAS BERUBAH JADI LINK (BUKA TAB BARU) --}}
                        <td>
                            <a href="{{ asset('storage/tugas/' . $siswa['file']) }}" 
                               target="_blank" 
                               class="tn-file-btn" 
                               style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                                {{ $siswa['file'] }}
                            </a>
                        </td>

                        <td>{{ $siswa['tanggal'] }}</td>

                        <td class="tn-nilai-action-cell">
                            <span class="tn-nilai">
                                {{ $siswa['nilai'] ?? '-' }}
                            </span>
                            <button type="button"
                                    class="tn-edit-btn"
                                    aria-label="Edit nilai"
                                    onclick="bukaEditNilai(this)"
                                    data-nama="{{ $siswa['nama'] }}"
                                    data-nilai="{{ $siswa['nilai'] }}"
                                    data-url="{{ $siswa['url']}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                @endforeach
                @if (count($daftarNilai) === 0)
                    <tr>
                        <td colspan="4" class="tn-empty">Belum ada siswa yang mengumpulkan tugas ini.</td>
                    </tr>
                @endif

                <tr id="tnTidakAda" hidden>
                    <td colspan="4" class="tn-empty">
                        Siswa tidak ditemukan.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<div class="tn-overlay" id="modalEditNilai" hidden>
    <div class="tn-modal">
        <div class="tn-modal-header">
            <button type="button" class="tmt-back-btn" onclick="tutupEditNilai()">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
            </button>
            <h2>Edit Nilai Siswa</h2>
        </div>

        <form id="formEditNilai" method="POST" action="">
            @csrf
            <label for="namaNilai">Nama Siswa</label>
            <input type="text" id="namaNilai" readonly>

            <label for="inputNilai">Nilai</label>
            <input type="number"
                   id="inputNilai"
                   name="nilai"
                   step="0.01"
                   min="0"
                   max="100"
                   required>

            <p class="tn-modal-note">
                Masukkan nilai antara 0 sampai 100.
            </p>

            <div class="tn-modal-actions">
                <button type="button"
                        class="tn-cancel"
                        onclick="tutupEditNilai()">
                    Batal
                </button>

                <button type="submit" class="tn-save">
                    Simpan Nilai
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    // PENCARIAN SISWA
    const cariSiswaNilai = document.getElementById('cariSiswaNilai');
    const barisNilai = document.querySelectorAll(
        '#daftarNilaiBody tr[data-id]'
    );
    const tnTidakAda = document.getElementById('tnTidakAda');

    cariSiswaNilai.addEventListener('input', function () {
        const kataKunci = this.value.toLowerCase().trim();
        let jumlahTampil = 0;

        barisNilai.forEach(function (baris) {
            const nama = baris.querySelector('.tn-nama')
                .textContent.toLowerCase();

            const cocok = nama.includes(kataKunci);

            baris.hidden = !cocok;

            if (cocok) jumlahTampil++;
        });

        tnTidakAda.hidden = jumlahTampil > 0;
    });

    // MODAL EDIT NILAI
    let barisAktif = null;

    function bukaEditNilai(tombol) {
        barisAktif = tombol.closest('tr');

        document.getElementById('namaNilai').value =
            tombol.dataset.nama;

        document.getElementById('inputNilai').value =
            tombol.dataset.nilai;

        
        document.getElementById('formEditNilai').action = tombol.dataset.url;

        document.getElementById('modalEditNilai').hidden = false;
    }

    function tutupEditNilai() {
        document.getElementById('modalEditNilai').hidden = true;
        barisAktif = null;
    }

    document.getElementById('formEditNilai')
    .addEventListener('submit', function (event) {
        const inputNilai = document.getElementById('inputNilai');
        const nilai = Number(inputNilai.value);

        // hanya batalkan kalau tidak valid; kalau valid, form dikirim ke server
        if (inputNilai.value === '' || nilai < 0 || nilai > 100) {
            event.preventDefault();
            alert('Nilai harus berada di antara 0 sampai 100.');
        }
    });

    // TUTUP MODAL DENGAN KLIK AREA LUAR
    document.querySelectorAll('.tn-overlay').forEach(function (overlay) {
        overlay.addEventListener('click', function (event) {
            if (event.target === overlay) {
                overlay.hidden = true;
                barisAktif = null;
            }
        });
    });
</script>

@endsection