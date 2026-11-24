@extends('layouts.siswa')

@section('content')

<main class="pk-page">
    <header class="pk-top">
        <div>
            <h1>Presensi Kelas</h1>
        </div>

        <div class="pk-date">{{ $tanggalHariIni }}</div>
    </header>

    <section class="pk-attandance pk-today">
        <h2>Jadwal Hari Ini</h2>

        @if (session('sukses'))
            <p style="background:#e3f6e8;color:#1d6b35;padding:10px 14px;border-radius:12px;margin:0 0 12px;">
                {{ session('sukses') }}
            </p>
        @endif

        @if ($errors->any())
            <p style="background:#fde8e8;color:#a12222;padding:10px 14px;border-radius:12px;margin:0 0 12px;">
                {{ $errors->first() }}
            </p>
        @endif

        @forelse ($jadwalHariIni as $j)
        <div class="pk-class {{ $j->berlangsung ? 'pk-active' : '' }}">
            <div>
                <h3>{{ $j->nama_kursus }}</h3>

                <div class="pk-meta">
                    <span>{{ $j->nama_tentor }}</span>
                    <span>{{ $j->jam }}</span>
                    <span class="pk-pill pk-pill-online">{{ $j->jenis }}</span>
                </div>
            </div>

            @if ($j->sudah_absen)
                <span class="pk-btn pk-done">Sudah: {{ ucfirst($j->status_absen) }}</span>
            @elseif ($j->bisa_absen)
                <!-- <form method="POST" action="{{ route('siswa.absensi.absen') }}" style="display:flex;flex-wrap:wrap;align-items:center;justify-content: space-evenly;">
                    @csrf
                    <input type="hidden" name="id_jadwal" value="{{ $j->id_jadwal }}">
                    <button type="submit" name="status" value="hadir" class="pk-btn pk-go">Hadir</button>
                    <button type="submit" name="status" value="izin" class="pk-btn pk-izin">Izin</button>
                    <button type="submit" name="status" value="sakit" class="pk-btn pk-sakit">Sakit</button> -->
                </form>
            @elseif ($j->berlangsung === false)
                <!-- <span class="pk-btn pk-done" style="opacity:.7;">Di luar jam kelas</span> -->
            @endif
        </div>
        @empty
            <p>Tidak ada jadwal kelas hari ini.</p>
        @endforelse
    </section>

    <section class="pk-attandance">
        <div class="pk-history-head">
            <div>
                <h2>Riwayat Presensi</h2>
            </div>

            @if ($semua)
                <a class="pk-link" href="{{ route('siswa.absensi') }}">Ringkas</a>
            @elseif ($totalRiwayat > $ringkas)
                <a class="pk-link" href="{{ route('siswa.absensi', ['semua' => 1]) }}">Lihat Semua</a>
            @endif
        </div>

        <div class="pk-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Kelas</th>
                        <th>Waktu</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($riwayat as $r)
                    <tr>
                        <td>{{ $r->tanggal_teks }}</td>
                        <td>{{ $r->nama_kursus }}</td>
                        <td>{{ $r->jam }}</td>
                        <td>
                            <span class="pk-pill pk-pill-{{ $r->status }}">
                                {{ $r->status_teks }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">Belum ada riwayat presensi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($semua && $riwayat->hasPages())
            <nav class="pk-pagination" aria-label="Navigasi halaman">
                @if ($riwayat->onFirstPage())
                    <button type="button" class="pk-pg-btn" disabled aria-label="Sebelumnya">&lt;</button>
                @else
                    <a href="{{ $riwayat->previousPageUrl() }}" class="pk-pg-btn" aria-label="Sebelumnya">&lt;</a>
                @endif

                @php
                    $__cur = $riwayat->currentPage();
                    $__last = $riwayat->lastPage();
                    if ($__last <= 7) {
                        $__pages = range(1, $__last);
                    } else {
                        $__pages = collect([1, $__last, $__cur, $__cur - 1, $__cur + 1, $__cur - 2, $__cur + 2])
                            ->filter(fn ($n) => $n >= 1 && $n <= $__last)
                            ->unique()->sort()->values()->all();
                    }
                    $__prev = 0;
                @endphp

                @foreach ($__pages as $__i)
                    @if ($__prev && $__i - $__prev > 1)
                        <span class="pk-pg-dots">…</span>
                    @endif

                    @if ($__i == $__cur)
                        <button type="button" class="pk-pg-btn pk-is-active" aria-current="page">{{ $__i }}</button>
                    @else
                        <a href="{{ $riwayat->url($__i) }}" class="pk-pg-btn">{{ $__i }}</a>
                    @endif

                    @php $__prev = $__i; @endphp
                @endforeach

                @if ($riwayat->hasMorePages())
                    <a href="{{ $riwayat->nextPageUrl() }}" class="pk-pg-btn" aria-label="Berikutnya">&gt;</a>
                @else
                    <button type="button" class="pk-pg-btn" disabled aria-label="Berikutnya">&gt;</button>
                @endif
            </nav>
        @endif
    </section>
</main>

@endsection