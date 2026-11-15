
@extends('layouts.Admin')

@section('title', 'Absensi Siswa')

@section('content')
<main class="ra-page">

    {{-- HEADER --}}
    <header class="ra-page-head">
        <div>
            <h1 class="ra-page-title">Riwayat Absensi</h1>
        </div>

        {{-- FILTER KELAS --}}
        <form action="{{ route('admin.riwayatAbsen') }}" method="GET">
            <select
                name="id_kursus"
                class="ra-select"
                aria-label="Pilih kursus"
                onchange="this.form.submit()"
            >
                <option value="">Semua Kursus</option>

                @foreach ($daftarKelas as $kelas)
                    <option
                        value="{{ $kelas->id_kursus }}"
                        {{ request('id_kursus') == $kelas->id_kursus ? 'selected' : '' }}
                    >
                        {{ $kelas->nama_kursus }}
                    </option>
                @endforeach
            </select>
        </form>
    </header>

    {{-- TABEL RIWAYAT ABSENSI --}}
    <div class="ra-table-wrap">
        <table class="ra-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th class="ra-left">Nama Siswa</th>
                    <th class="ra-left">Nama Kursus</th>
                    <th>Hadir</th>
                    <th>Persentase</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($daftarSiswa as $index => $siswa)
                    <tr>
                        <td class="ra-no">
                            {{ str_pad($daftarSiswa->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}
                        </td>

                        <td class="ra-left">
                            <strong>{{ $siswa->nama_siswa }}</strong>
                        </td>

                        <td class="ra-left">
                            {{ $siswa->nama_kursus }}
                        </td>

                        <td>
                            {{ $siswa->total_hadir }}/{{ $siswa->total_pertemuan }}
                        </td>

                        <td class="ra-percent {{ $siswa->persentase < 75 ? 'ra-percent-low' : '' }}">
                            {{ $siswa->persentase }}%
                        </td>

                        <td>
                            <a
                                href="{{ route('admin.riwayatPresensi', [
                                    'id_kelas' => $siswa->id_kelas,
                                    'id_siswa' => $siswa->id_siswa
                                ]) }}"
                                class="ra-btn"
                            >
                                Lihat detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            Belum ada data riwayat absensi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    @if ($daftarSiswa->hasPages())
        <div class="ak-pagination">

            {{-- SEBELUMNYA --}}
            @if ($daftarSiswa->onFirstPage())
                <span class="disabled">‹</span>
            @else
                <a href="{{ $daftarSiswa->appends(request()->query())->previousPageUrl() }}">‹</a>
            @endif

            @php
                $current = $daftarSiswa->currentPage();
                $last = $daftarSiswa->lastPage();

                $start = max(1, $current - 2);
                $end = min($last, $current + 2);
            @endphp

            @if ($start > 1)
                <a href="{{ $daftarSiswa->appends(request()->query())->url(1) }}">1</a>

                @if ($start > 2)
                    <span>...</span>
                @endif
            @endif

            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $current)
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $daftarSiswa->appends(request()->query())->url($page) }}">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            @if ($end < $last)
                @if ($end < $last - 1)
                    <span>...</span>
                @endif

                <a href="{{ $daftarSiswa->appends(request()->query())->url($last) }}">
                    {{ $last }}
                </a>
            @endif

            {{-- BERIKUTNYA --}}
            @if ($daftarSiswa->hasMorePages())
                <a href="{{ $daftarSiswa->appends(request()->query())->nextPageUrl() }}">›</a>
            @else
                <span class="disabled">›</span>
            @endif

        </div>
    @endif

</main>
@endsection
