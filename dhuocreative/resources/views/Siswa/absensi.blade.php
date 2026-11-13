<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DhuoCreative Portal!</title>
</head>

<style>
    :root {
        --teal: #286c65;
        --orange: #f27640;
        --orange-dk: #df6833;
        --amber: #ffb83f;
        --yellow: #ffdc59;
        --cream: #f5e9cf;
        --ivory: #fffaf0;
        --sand: #fcf4e5;
        --red: #c44747;
        --red-light: #fde8e7;
        --teal-muted: #5f7f7b;
    }
    * {
        box-sizing: border-box;
    }
    html, body {
        min-height: 100%;
        margin: 0;
    }
    body {
        background: var(--ivory);
        color: var(--teal);
        font-family: Arial, Helvetica, sans-serif;
        line-height: 1.5;
    }
    .page {
        width: 100%;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        gap: 28px;
        padding: 36px clamp(20px, 4vw, 64px) 40px;
    }
    .top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        flex-wrap: wrap;
    }
    h1 {
        margin: 0 0 8px;
        color: var(--teal);
        font-size: 36px;
        line-height: 1.2;
        letter-spacing: -0.5px;
    }
    .date {
        padding: 10px 20px;
        border-radius: 999px;
        background: #fff0e5;
        color: var(--orange-dk);
        border: 1px solid #f8d5bd;
        font-size: 14px;
        font-weight: 500;
    }
    .attandance {
        padding: 28px 32px;
        border-radius: 22px;
        background: var(--sand);
        border: 1px solid var(--cream);
    }
    .attandance h2 {
        margin: 0 0 6px;
        font-size: 22px;
        margin-bottom: 18px;
        color: var(--teal);
    }
    .class {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto auto;
        align-items: center;
        gap: 24px;
        margin-top: 16px;
        padding: 24px 28px;
        border-radius: 18px;
        background: var(--ivory);
        border: 1px solid var(--cream);
    }
    .class.active {
        background: #fff0cf;
        border-color: var(--amber);
    }
    .class h3 {
        margin: 0 0 6px;
        color: var(--teal);
        font-size: 19px;
        font-weight: 700;
    }
    .meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 20px;
        color: var(--teal-muted);
        font-size: 14px;
    }
    .pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 80px;
        height: 32px;
        padding: 0 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }
    .pill-online {
        background: var(--mint);
        color: var(--teal);
    }
    .pill-hadir {
        background: var(--mint);
        color: var(--teal);
    }
    .pill-izin {
        background: var(--yellow);
        color: var(--gold-dk);
    }
    .pill-sakit {
        background: var(--red-light);
        color: var(--red);
    }
    .pill-alpha {
        background: var(--red-light);
        color: var(--red);
    }
    .btn {
        padding: 14px 26px;
        border: none;
        border-radius: 999px;
        font: inherit;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
    }
    .btn.go {
        background: var(--orange);
        color: var(--ivory);
        cursor: pointer;
        transition: background 0.2s ease;
    }
    .btn.go:hover {
        background: var(--orange-dk);;
    }
    .btn.done {
        background: var(--mint);
        color: var(--teal);
        cursor: not-allowed;
        opacity: 1;
    }
    .class .btn {
        width: 190px;
        height: 48px;
        padding: 0 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 999px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
    }
    .btn:focus-visible,
    .link:focus-visible {
        outline: 3px solid #1b5240;
        outline-offset: 3px;
    }
    .history-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
    }
    .link {
        padding-top: 5px;
        color: var(--orange-dk);
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
    }
    .link:hover {
        text-decoration: underline;
    }
    .scroll {
        width: 100%;
        overflow-x: auto;
    }
    table {
        width: 100%;
        min-width: 560px;
        border-collapse: collapse;
        font-size: 14px;
    }
    th {
        padding: 14px 18px;
        background: var(--yellow);
        color: var(--teal);
        font-weight: 600;
        text-align: left;
    }
    th:first-child {
        border-radius: 12px 0 0 12px;
    }
    th:last-child {
        border-radius: 0 12px 12px 0;
    }
    td {
        padding: 16px 18px;
        border-bottom: 1px solid var(--cream);
        color: var(--teal-muted);
    }
    td:first-child {
        color: #17392f;
        white-space: nowrap;
    }
    tbody tr:last-child td {
        border-bottom: none;
    }
    tbody tr:hover {
        background: var(--sand);
    }
    @media (max-width: 900px) {
        .class {
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 16px;
        }

        .class > div:first-child {
            grid-column: 1 / -1;
        }

        .class .btn {
            justify-self: end;
        }
    }

    .tj-pagination {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 8px !important;
        margin: 20px auto !important;
        padding: 8px 0 !important;
        flex-wrap: wrap !important;
        width: 100% !important;
        text-align: center;
    }

    .tj-pagination .tj-pg-btn {
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        border: 1.5px solid #cfe0dd;
        border-radius: 12px;
        background: #fffaf0;
        color: #14524b;
        font: inherit;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        box-sizing: border-box;
    }

    .tj-pagination .tj-pg-btn.is-active,
    .tj-pagination .tj-pg-btn.active {
        background: #f27640;
        border-color: #f27640;
        color: #fff;
        cursor: default;
    }

    .tj-pagination .tj-pg-btn:disabled {
        opacity: .45;
        cursor: not-allowed;
    }

    .tj-pagination .tj-pg-dots {
        min-width: 28px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #5f7f7b;
        font-weight: 700;
        font-size: 15px;
        user-select: none;
    }

@media (max-width: 640px) {

    @media (max-width: 640px) {
        .page {
            gap: 20px;
            padding: 24px 16px;
        }
        h1 {
            font-size: 28px;
        }
        .sub {
            font-size: 14px;
        }
        .attandance,
        .attendance {
            padding: 20px 16px;
            border-radius: 18px;
        }
        .attandance h2,
        .attendance h2 {
            font-size: 20px;
        }
        .class {
            grid-template-columns: 1fr;
            gap: 14px;
            padding: 18px;
        }
        .class > div:first-child {
            grid-column: auto;
        }
        .class .pill {
            justify-self: start;
        }
        .class .btn {
            width: 100%;
            justify-self: stretch;
        }
        .history-head {
            gap: 8px;
        }
        .link {
            font-size: 13px;
        }
        th,
        td {
            padding: 13px 14px;
        }
}
}

</style>

<body>

@extends('layouts.siswa')

@section('content')

    <main class="page">
        <header class="top">
            <div>
                <h1>Presensi Kelas</h1>
            </div>

            <div class="date">{{ $tanggalHariIni }}</div>
        </header>

        <section class="attandance today">
            <h2>Jadwal Hari Ini</h2>
            @forelse ($jadwalHariIni as $j)
            <div class="class {{ $j->berlangsung ? 'active' : '' }}">
                <div>
                    <h3>{{ $j->nama_kursus }}</h3>
                    <div class="meta">
                        <span>{{ $j->nama_tentor }}</span>
                        <span>{{ $j->jam }}</span>
                    </div>
                </div>
                <span class="pill pill-online">{{ $j->jenis }}</span>
            </div>
            @empty
            <p>Tidak ada jadwal kelas hari ini.</p>
            @endforelse
        </section>

        <section class="attandance">
            <div class="history-head">
                <div>
                    <h2>Riwayat Presensi</h2>
                </div>

                @if ($semua)
                    <a class="link" href="{{ route('siswa.absensi') }}">Ringkas</a>
                @elseif ($totalRiwayat > $ringkas)
                    <a class="link" href="{{ route('siswa.absensi', ['semua' => 1]) }}">Lihat Semua</a>
                @endif
            </div>

               <div class="scroll">
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
                            <td><span class="pill pill-{{ $r->status }}">{{ $r->status_teks }}</span></td>
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
                    <nav class="tj-pagination" aria-label="Navigasi halaman">
                        @if ($riwayat->onFirstPage())
                            <button type="button" class="tj-pg-btn" disabled aria-label="Sebelumnya">&lt;</button>
                        @else
                            <a href="{{ $riwayat->previousPageUrl() }}" class="tj-pg-btn" aria-label="Sebelumnya">&lt;</a>
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
                                <span class="tj-pg-dots">…</span>
                            @endif
                            @if ($__i == $__cur)
                                <button type="button" class="tj-pg-btn is-active" aria-current="page">{{ $__i }}</button>
                            @else
                                <a href="{{ $riwayat->url($__i) }}" class="tj-pg-btn">{{ $__i }}</a>
                            @endif
                            @php $__prev = $__i; @endphp
                        @endforeach

                        @if ($riwayat->hasMorePages())
                            <a href="{{ $riwayat->nextPageUrl() }}" class="tj-pg-btn" aria-label="Berikutnya">&gt;</a>
                        @else
                            <button type="button" class="tj-pg-btn" disabled aria-label="Berikutnya">&gt;</button>
                        @endif
                    </nav>
                @endif
        </section>
    </main>
@endsection
</body>
</html>