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

        /* 4 kolom data + 1 kolom kecil untuk chevron */
        .nl-grid {
            display: grid;
            grid-template-columns: 1.25fr 1fr .7fr .95fr 28px;
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
            position: relative;
            padding-top: 16px;
            padding-bottom: 16px;
            border-top: 1px solid var(--mint-line);
        }

        .nl-thead + .nl-item .nl-row {
            border-top: none;
        }

        .nl-row.is-clickable {
            cursor: pointer;
            transition: background .15s ease;
        }

        .nl-row.is-clickable:hover {
            background: var(--sand);
        }

        .nl-row.is-clickable:focus-visible {
            outline: 2px solid var(--teal);
            outline-offset: -2px;
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
            background: var(--mint);
            color: var(--teal);
        }

        .nl-badge-good {
            background: var(--sand);
            color: var(--gold-dk);
        }

        .nl-badge-ok {
            background: #fff8e6;
            color: var(--olive);
        }

        .nl-badge-low {
            background: #fde8e4;
            color: var(--orange-dk);
        }

        .nl-badge-wait {
            background: #fff0cf;
            color: var(--orange-dk);
        }

        /* Chevron */
        .nl-chev {
            justify-self: end;
            font-size: 16px;
            color: var(--teal);
            transition: transform .2s ease;
        }

        .nl-item.is-open .nl-chev {
            transform: rotate(180deg);
        }

        .nl-panel {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows .25s ease;
        }

        .nl-item.is-open .nl-panel {
            grid-template-rows: 1fr;
        }

        .nl-panel-inner {
            overflow: hidden;
        }

        .nl-feedback {
            margin: 0 24px 18px;
            padding: 16px 18px;
            background: var(--mint);
            border-radius: 16px;
        }

        .nl-feedback-head {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .nl-avatar {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--teal);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
        }

        .nl-feedback-head strong {
            display: block;
            font-size: 13.5px;
            font-weight: 700;
            color: var(--teal);
        }

        .nl-feedback-head span {
            display: block;
            font-size: 12px;
            color: var(--teal-muted);
        }

        .nl-feedback p {
            margin: 0;
            font-size: 14.5px;
            line-height: 1.65;
            color: #2f5e5b;
            white-space: pre-line;
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

            .nl-chev {
                position: absolute;
                top: 18px;
                right: 16px;
            }

            .nl-feedback {
                margin: 0 16px 16px;
            }
        }

        @media (max-width: 420px) {
            .nl-scale-list {
                grid-template-columns: 1fr;
            }
        }

        .tj-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .tj-pagination button {
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
        }

        .tj-pagination button.is-active {
            background: #f27640;
            border-color: #f27640;
            color: #fff;
        }

        .tj-pagination button:disabled {
            opacity: .45;
            cursor: not-allowed;
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
                <strong class="nl-stat-value">{{ $rataRata }}</strong>
            </div>
        </div>

        <div class="nl-stat">
            <div>
                <span class="nl-stat-label">Nilai Tertinggi</span>
                <strong class="nl-stat-value">{{ $tertinggi }}</strong>
            </div>
        </div>

        <div class="nl-stat">
            <div>
                <span class="nl-stat-label">Tugas Dinilai</span>
                <strong class="nl-stat-value">{{ $dinilai }} <small>dari {{ $totalTugas }} tugas</small></strong>
            </div>
        </div>

    </section>

    <section class="nl-table" aria-label="Daftar nilai tugas">

        <div class="nl-grid nl-thead">
            <span>Nama Tugas</span>
            <span>Tanggal Dinilai</span>
            <span>Nilai</span>
            <span>Keterangan</span>
            <span></span>
        </div>

        @forelse ($daftar as $r)
        <div class="nl-item">
            @if ($r->feedback)
            <div class="nl-grid nl-row is-clickable" role="button" tabindex="0" aria-expanded="false">
                <div class="nl-name">
                    <strong>{{ $r->judul }}</strong>
                    <span>{{ $r->kursus }}</span>
                </div>
                <span class="nl-date" data-label="Tanggal Dinilai">{{ $r->tanggal }}</span>
                <span class="nl-score" data-label="Nilai">{{ $r->nilai }}</span>
                <span class="nl-badge {{ $r->css }}" data-label="Keterangan">{{ $r->label }}</span>
                <i class="bi bi-chevron-down nl-chev" aria-hidden="true"></i>
            </div>
            <div class="nl-panel">
                <div class="nl-panel-inner">
                    <div class="nl-feedback">
                        <div class="nl-feedback-head">
                            <div class="nl-avatar">{{ $r->inisial }}</div>
                            <div>
                                <strong>Feedback dari {{ $r->nama_tentor }}</strong>
                                <span>{{ $r->tanggal }}</span>
                            </div>
                        </div>
                        <p>{{ $r->feedback }}</p>
                    </div>
                </div>
            </div>
            @else
            <div class="nl-grid nl-row">
                <div class="nl-name">
                    <strong>{{ $r->judul }}</strong>
                    <span>{{ $r->kursus }}</span>
                </div>
                @if ($r->dinilai)
                    <span class="nl-date" data-label="Tanggal Dinilai">{{ $r->tanggal }}</span>
                    <span class="nl-score" data-label="Nilai">{{ $r->nilai }}</span>
                @else
                    <span class="nl-date is-empty" data-label="Tanggal Dinilai">Belum dinilai</span>
                    <span class="nl-score-empty" data-label="Nilai" aria-label="Belum ada nilai">&mdash;</span>
                @endif
                <span class="nl-badge {{ $r->css }}" data-label="Keterangan">{{ $r->label }}</span>
                <span></span>
            </div>
            @endif
        </div>
        @empty
        <p style="padding: 16px;">Belum ada tugas.</p>
        @endforelse

        @if ($daftar->hasPages())
            <nav class="tj-pagination" aria-label="Navigasi halaman">

                @if ($daftar->onFirstPage())
                    <button type="button" disabled>&lt;</button>
                @else
                    <a href="{{ $daftar->previousPageUrl() }}" style="text-decoration:none">
                        <button type="button">&lt;</button>
                    </a>
                @endif

                @php
                    $current = $daftar->currentPage();
                    $last = $daftar->lastPage();
                @endphp

                @for ($i = 1; $i <= $last; $i++)
                    @if ($i == $current)
                        <button type="button" class="is-active">{{ $i }}</button>
                    @else
                        <a href="{{ $daftar->url($i) }}" style="text-decoration:none">
                            <button type="button">{{ $i }}</button>
                        </a>
                    @endif
                @endfor

                @if ($daftar->hasMorePages())
                    <a href="{{ $daftar->nextPageUrl() }}" style="text-decoration:none">
                        <button type="button">&gt;</button>
                    </a>
                @else
                    <button type="button" disabled>&gt;</button>
                @endif

            </nav>
        @endif

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
            <li><b>Perlu Ditingkatkan</b> <span>di bawah 70</span></li>
        </ul>
    </section>
</div>

<script>
    document.querySelectorAll('.nl-row.is-clickable').forEach(function (row) {
        var item = row.closest('.nl-item');

        function toggle() {
            var open = item.classList.toggle('is-open');
            row.setAttribute('aria-expanded', open);
        }

        row.addEventListener('click', toggle);
        row.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggle();
            }
        });
    });
</script>
@endsection
</body>
</html>