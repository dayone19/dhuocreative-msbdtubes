<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DhuoCreative Portal</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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
        body {
            margin: 0;
            padding: 24px 40px;
            background: #fff;
        }

        .nl-page {
            width: 100%;
            max-width: 100%;
            font-family: 'Plus Jakarta Sans', 'Bricolage Grotesque', sans-serif;
            color: var(--teal);
            box-sizing: border-box;
        }

        .nl-page *,
        .nl-page *::before,
        .nl-page *::after {
            box-sizing: border-box;
        }

        .nl-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .nl-header i {
            font-size: 40px;
            line-height: 1;
            color: var(--teal);
        }

        .nl-header h1 {
            font-family: arial, helvetica, sans-serif;
            font-size: 36px;
            font-weight: 800;
            line-height: 1.1;
            color: var(--teal);
            margin: 0;
        }

        .nl-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .nl-stat {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
            padding: 18px 22px;
            background: var(--mint);
            border-radius: 22px;
        }

        .nl-stat-icon {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            border-radius: 14px;
            font-size: 22px;
            background: var(--ivory);
            color: var(--teal);
        }

        .nl-stat-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--teal-muted);
        }

        .nl-stat-value {
            display: block;
            margin-top: 2px;
            font-size: 24px;
            font-weight: 800;
            line-height: 1.2;
            color: var(--orange);
        }

        .nl-stat-value small {
            font-size: 14px;
            font-weight: 500;
            color: var(--teal-muted);
        }

        .nl-table {
            overflow: hidden;
            background: var(--ivory);
            border: 1px solid var(--mint-line);
            border-radius: 20px;
        }

        .nl-grid {
            display: grid;
            grid-template-columns: 1.25fr 1fr .7fr .95fr;
            align-items: center;
            column-gap: 16px;
            padding: 0 24px;
        }

        .nl-thead {
            min-height: 46px;
            font-size: 15px;
            font-weight: 700;
            background: var(--cream);
            color: var(--orange-dk);
        }

        .nl-row {
            padding-top: 16px;
            padding-bottom: 16px;
            border-top: 1px solid #ececec;
            border-color: var(--mint-line);
        }

        .nl-thead + .nl-row {
            border-top: none;
        }

        .nl-name strong {
            display: block;
            font-size: 15px;
            font-weight: 700;
            color: var(--teal);
        }

        .nl-name span {
            display: block;
            margin-top: 4px;
            font-size: 12px;
            color: var(--teal-muted);
        }

        .nl-date {
            font-size: 14.5px;
            color: var(--teal);
        }

        .nl-date.is-empty {
            color: var(--teal-muted);
        }

        .nl-score {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            justify-self: start;
            min-width: 50px;
            height: 28px;
            padding: 0 14px;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 800;
            background: var(--mint);
            color: var(--teal);
        }

        .nl-score-empty {
            justify-self: start;
            padding-left: 16px;
            font-size: 18px;
            font-weight: 700;
            color: var(--teal-muted);
        }

        .nl-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            justify-self: start;
            padding: 6px 14px 6px 10px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
        }

        .nl-badge::before {
            content: "";
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: currentColor;
        }

        .nl-badge-great {
            bbackground: var(--mint);
            color: var(--teal);
        }

        .nl-badge-good {
            background: var(--sand);
            color: var(--gold-dk);
        }

        .nl-badge-wait {
            background: #fff0cf;
            color: var(--orange-dk);
        }

        .nl-scale {
            margin-top: 20px;
            padding: 20px 24px 22px;
            background: var(--mint);
            border-radius: 24px;
        }

        .nl-scale h2 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 700;
            color: var(--teal);
            margin: 0 0 14px;
        }

        .nl-scale-list {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nl-scale-list li {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 12px 14px;
            background: var(--ivory);
            border-radius: 14px;
            font-size: 13px;
        }

        .nl-scale-list b {
            font-weight: 700;
            color: var(--teal);
        }

        .nl-scale-list span {
            color: var(--teal-muted);
            white-space: nowrap;
        }

        .nl-note {
            margin: 14px 0 0;
            font-size: 12.5px;
            color: var(--teal-muted);
        }

        @media (max-width: 900px) {
            .nl-scale-list {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 16px;
            }

            .nl-header i {
                font-size: 32px;
            }

            .nl-header h1 {
                font-size: 28px;
            }

            .nl-summary {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .nl-thead {
                display: none;
            }

            .nl-grid {
                padding: 0 16px;
            }

            .nl-row {
                grid-template-columns: 1fr;
                row-gap: 8px;
            }

            .nl-row [data-label]::before {
                content: attr(data-label) ": ";
                font-size: 12px;
                font-weight: 600;
                color: #667085;
            }

            .nl-score[data-label]::before,
            .nl-badge[data-label]::before {
                display: none;
            }
        }

        @media (max-width: 420px) {
            .nl-scale-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

@extends('layouts.siswa')

@section('content')

<div class="nl-page">

    <section class="nl-summary" aria-label="Ringkasan nilai">

        <div class="nl-stat">
            <div>
                <span class="nl-stat-label">Rata-rata Nilai</span>
                <strong class="nl-stat-value">91,5</strong>
            </div>
        </div>

        <div class="nl-stat">
            <div>
                <span class="nl-stat-label">Nilai Tertinggi</span>
                <strong class="nl-stat-value">95</strong>
            </div>
        </div>

        <div class="nl-stat">
            <div>
                <span class="nl-stat-label">Tugas Dinilai</span>
                <strong class="nl-stat-value">2 <small>dari 3 tugas</small></strong>
            </div>
        </div>

    </section>

    <!-- Tabel nilai -->
    <section class="nl-table" aria-label="Daftar nilai tugas">

        <div class="nl-grid nl-thead">
            <span>Nama Tugas</span>
            <span>Tanggal Dinilai</span>
            <span>Nilai</span>
            <span>Keterangan</span>
        </div>

        <div class="nl-grid nl-row">
            <div class="nl-name">
                <strong>Latihan HTML</strong>
                <span>Web Development</span>
            </div>
            <span class="nl-date" data-label="Tanggal Dinilai">15 Oktober 2026</span>
            <span class="nl-score" data-label="Nilai">95</span>
            <span class="nl-badge nl-badge-great" data-label="Keterangan">Sangat Baik</span>
        </div>

        <div class="nl-grid nl-row">
            <div class="nl-name">
                <strong>Membuat Halaman Profil</strong>
                <span>Web Development</span>
            </div>
            <span class="nl-date is-empty" data-label="Tanggal Dinilai">Belum dinilai</span>
            <span class="nl-score-empty" data-label="Nilai" aria-label="Belum ada nilai">&mdash;</span>
            <span class="nl-badge nl-badge-wait" data-label="Keterangan">Menunggu</span>
        </div>

        <div class="nl-grid nl-row">
            <div class="nl-name">
                <strong>Kuis Dasar HTML</strong>
                <span>Web Development</span>
            </div>
            <span class="nl-date" data-label="Tanggal Dinilai">10 Oktober 2026</span>
            <span class="nl-score" data-label="Nilai">88</span>
            <span class="nl-badge nl-badge-good" data-label="Keterangan">Baik</span>
        </div>

    </section>

    <section class="nl-scale" aria-labelledby="judul-skala">
        <h2 id="judul-skala">
            <i class="bi bi-info-circle"></i>
            Skala Penilaian
        </h2>

        <ul class="nl-scale-list">
            <li><b>Sangat Baik</b> <span>90 - 100</span></li>
            <li><b>Baik</b> <span>80 - 89</span></li>
            <li><b>Cukup Baik</b> <span>70 - 79</span></li>
        </ul>

        <p class="nl-note">
            Nilai akhir kursus dihitung dari rata-rata seluruh tugas yang sudah dinilai tentor.
        </p>
    </section>
</div>

@endsection
</body>
</html>