@extends('layouts.Tentor')

@section('title', 'Absensi Siswa')

@section('content')
<div class="tentor-absensi-page">

    <div class="absensi-heading">
        <a href="{{ route('tentor.kelas') }}" class="tmt-back-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
            <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
        </a>

        <div>
            <h1>Web Master {{ $kelas }} - Offline</h1>
            <p>Senin, 10.30 - 11.30</p>
        </div>
    </div>


    <div class="absensi-controls">

        <a href="{{ route('tentor.materi') }}" class="btn-halaman-kelas btn-mt">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-journal-bookmark" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M6 8V1h1v6.117L8.743 6.07a.5.5 0 0 1 .514 0L11 7.117V1h1v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8"/>
                <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                </svg>
            </span>
             Materi
        </a>

        <a href="{{ route('tentor.tugas') }}" class="btn-halaman-kelas btn-mt">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-journal-code" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8.646 5.646a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L10.293 8 8.646 6.354a.5.5 0 0 1 0-.708m-1.292 0a.5.5 0 0 0-.708 0l-2 2a.5.5 0 0 0 0 .708l2 2a.5.5 0 0 0 .708-.708L5.707 8l1.647-1.646a.5.5 0 0 0 0-.708"/>
                <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                </svg>
            </span>
             Tugas
        </a>

        <select id="pilihPertemuan" class="select-pertemuan">
            @for ($i = 1; $i <= 12; $i++)
                <option value="{{ $i }}"
                    {{ $i == 3 ? 'selected' : '' }}>
                    Pertemuan {{ $i }}
                </option>
            @endfor
        </select>

        <button type="button"
                id="btnSimpanAbsensi"
                class="btn-simpan">
            Simpan
        </button>

    </div>


    @php
        $daftarSiswa = [
            ['id' => 1208, 'nama' => 'Belerick Arbiter of Star', 'status' => 'hadir'],
            ['id' => 1209, 'nama' => 'Belerick Arbiter of Star', 'status' => 'absen'],
            ['id' => 1210, 'nama' => 'Belerick Arbiter of Star', 'status' => 'sakit'],
        ];
    @endphp

    <div class="absensi-table-container">
        <table class="absensi-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Hadir</th>
                    <th>Sakit</th>
                    <th>Izin</th>
                    <th>Absen</th>
                    <th>Kehadiran</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($daftarSiswa as $siswa)
                    <tr data-siswa-id="{{ $siswa['id'] }}">
                        <td>{{ $siswa['id'] }}</td>
                        <td class="nama-siswa">{{ $siswa['nama'] }}</td>

                        @foreach (['hadir', 'sakit', 'izin', 'absen'] as $status)
                            <td>
                                <label class="radio-absensi">
                                    <input
                                        type="radio"
                                        name="absensi[{{ $siswa['id'] }}]"
                                        value="{{ $status }}"
                                        {{ $siswa['status'] === $status ? 'checked' : '' }}
                                    >
                                    <span></span>
                                </label>
                            </td>
                        @endforeach

                        <td class="jumlah-kehadiran">0/12</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <p id="pesanAbsensi" class="pesan-absensi" hidden></p>

</div>

<script>
    const tombolSimpan = document.getElementById('btnSimpanAbsensi');
    const pilihPertemuan = document.getElementById('pilihPertemuan');
    const pesanAbsensi = document.getElementById('pesanAbsensi');

    function hitungKehadiran() {
        document.querySelectorAll('.absensi-table tbody tr').forEach(row => {
            const jumlahHadir = Number(
                row.dataset.jumlahHadir || 0
            );

            row.querySelector('.jumlah-kehadiran').textContent =
                `${jumlahHadir}/12`;
        });
    }

    tombolSimpan.addEventListener('click', function () {
        const pertemuan = pilihPertemuan.value;
        let semuaTerisi = true;

        document.querySelectorAll('.absensi-table tbody tr').forEach(row => {
            const id = row.dataset.siswaId;
            const pilihan = row.querySelector(
                `input[name="absensi[${id}]"]:checked`
            );

            if (!pilihan) {
                semuaTerisi = false;
                return;
            }

            let riwayat = JSON.parse(
                localStorage.getItem(`absensi_${id}`) || '{}'
            );

            riwayat[pertemuan] = pilihan.value;

            localStorage.setItem(
                `absensi_${id}`,
                JSON.stringify(riwayat)
            );

            const jumlahHadir = Object.values(riwayat)
                .filter(status => status === 'hadir').length;

            row.dataset.jumlahHadir = jumlahHadir;
            row.querySelector('.jumlah-kehadiran').textContent =
                `${jumlahHadir}/12`;
        });

        pesanAbsensi.hidden = false;

        if (!semuaTerisi) {
            pesanAbsensi.textContent =
                'Pilih status absensi untuk setiap siswa terlebih dahulu.';
            pesanAbsensi.className = 'pesan-absensi error';
            return;
        }

        hitungKehadiran();

        pesanAbsensi.textContent =
            `Absensi pertemuan ${pertemuan} tersimpan di browser.`;
        pesanAbsensi.className = 'pesan-absensi sukses';
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.absensi-table tbody tr').forEach(row => {
            const id = row.dataset.siswaId;
            const riwayat = JSON.parse(
                localStorage.getItem(`absensi_${id}`) || '{}'
            );

            row.dataset.jumlahHadir = Object.values(riwayat)
                .filter(status => status === 'hadir').length;

            const pertemuan = pilihPertemuan.value;

            if (riwayat[pertemuan]) {
                const radio = row.querySelector(
                    `input[value="${riwayat[pertemuan]}"]`
                );

                if (radio) radio.checked = true;
            }

            row.querySelector('.jumlah-kehadiran').textContent =
                `${row.dataset.jumlahHadir}/12`;
        });
    });

    pilihPertemuan.addEventListener('change', function () {
        const pertemuan = this.value;

        document.querySelectorAll('.absensi-table tbody tr').forEach(row => {
            const riwayat = JSON.parse(
                localStorage.getItem(`absensi_${row.dataset.siswaId}`) || '{}'
            );

            row.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.checked = riwayat[pertemuan] === radio.value;
            });
        });

        pesanAbsensi.hidden = true;
    });
</script>
@endsection