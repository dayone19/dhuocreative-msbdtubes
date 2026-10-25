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
        --amber-dk: #f3a92d;
        --yellow: #ffdc59;
        --gold: #b29458;
        --gold-dk: #987840;
        --olive: #b4a052;
        --cream: #f5e9cf;
        --ivory: #fffaf0;
        --sand: #fcf4e5;

        --mint: #e3efed;
        --mint-line: #cfe0dd;
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
        gap: 7px;
        padding: 6px 14px;
        border-radius: 999px;
        background: #fff0e5;
        color: var(--orange-dk);
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }
    .pill::before {
        content: "";
        width: 7px;
        height: 7px;
        flex-shrink: 0;
        border-radius: 50%;
        background: var(--orange);
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

</style>

<body>

@extends('layouts.siswa')

@section('content')

    <main class="page">
        <header class="top">
            <div>
                <h1>Presensi Kelas</h1>
            </div>

            <div class="date">Senin, 13 Oktober 2026</div>
        </header>

        <section class="attandance today">
            <h2>Jadwal Hari Ini</h2>
            <div class="class active">
                <div>
                    <h3>Pemrograman Web Lanjut</h3>
                    <div class="meta">
                        <span>Muhammad Azizi</span>
                        <span>10.00 – 12.00</span>
                    </div>
                </div>

                <span class="pill">Online</span>

                <button class="btn go" id="attandance-button type="button">
                    Presensi
                </button>
            </div>

            <div class="class">
                <div>
                    <h3>Basis Data</h3>
                    <div class="meta">
                        <span>Hapis Ahmad</span>
                        <span>15.30 – 17.30</span>
                    </div>
                </div>

                <span class="pill">Online</span>

                <button class="btn done" type="button" disabled>
                    Presensi Berhasil
                </button>
            </div>
        </section>

        <section class="attandance">
            <div class="history-head">
                <div>
                    <h2>Riwayat Presensi</h2>
                </div>

                <a class="link" href="#">Lihat Semua</a>
            </div>

               <div class="scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Mata Kuliah</th>
                            <th>Waktu</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>10 Oktober 2026</td>
                            <td>Web Development</td>
                            <td>10.00 – 12.00</td>
                            <td><span class="pill">Hadir</span></td>
                        </tr>

                        <tr>
                            <td>8 Oktober 2026</td>
                            <td>Web Development</td>
                            <td>10.00 – 12.00</td>
                            <td><span class="pill">Hadir</span></td>
                        </tr>

                        <tr>
                            <td>6 Oktober 2026</td>
                            <td>Basis Data</td>
                            <td>15.30 – 17.30</td>
                            <td><span class="pill">Hadir</span></td>
                        </tr>

                        <tr>
                            <td>4 Oktober 2026</td>
                            <td>Web Development</td>
                            <td>10.00 – 12.00</td>
                            <td><span class="pill">Hadir</span></td>
                        </tr>

                        <tr>
                            <td>2 Oktober 2026</td>
                            <td>Basis Data</td>
                            <td>15.30 – 17.30</td>
                            <td><span class="pill">Hadir</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
@endsection
</body>
</html>