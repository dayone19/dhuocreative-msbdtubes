<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DhuoCreative Portal</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;500;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            margin: 0;
            padding: 24px 40px;
            background: #fff;
        }

        .as-page {
            width: 100%;
            max-width: 100%;
            font-family: arial, helvetica, sans-serif;
            color: #111;
            box-sizing: border-box;
        }

        .as-page *,
        .as-page *::before,
        .as-page *::after {
            box-sizing: border-box;
        }

        .as-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .as-header i.as-header-icon {
            font-size: 34px;
            line-height: 1;
            color: #222;
        }

        .as-header h1 {
            font-size: 36px;
            font-weight: 800;
            line-height: 1.1;
            color: #000;
            margin: 0;
        }

        .as-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 46px;
            padding: 0 24px;
            border: none;
            border-radius: 999px;
            background: #f4713a;
            color: #fff;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s ease, transform .2s ease;
        }

        .as-btn:hover {
            background: #d95f2a;
            transform: scale(1.03);
        }

        .as-btn:focus-visible,
        .as-btn-ghost:focus-visible,
        .as-link-btn:focus-visible {
            outline: 3px solid #1f6b5f;
            outline-offset: 2px;
        }

        .as-btn-ghost {
            height: 46px;
            padding: 0 22px;
            border: 1.5px solid #d3d3d3;
            border-radius: 999px;
            background: #fff;
            color: #111;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s ease;
        }

        .as-btn-ghost:hover {
            background: #f5f5f5;
        }

        .as-header .as-btn {
            margin-left: auto;
        }

        .as-summary {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .as-card {
            min-width: 0;
            padding: 18px 22px;
            background: #faf3e3;
            border-radius: 22px;
        }

        .as-card-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            font-weight: 700;
            color: #f27640;
        }

        .as-card-label i {
            font-size: 18px;
            color: #333;
        }

        .as-card-value {
            display: block;
            margin-top: 6px;
            font-size: 30px;
            font-weight: 800;
            line-height: 1.15;
            color: #000;
        }

        .as-card-value small {
            font-size: 14px;
            font-weight: 500;
            color: #444;
        }

        .as-bar {
            position: relative;
            height: 12px;
            margin-top: 12px;
            background: #b3b3b3;
            border-radius: 999px;
        }

        .as-bar-fill {
            height: 100%;
            background: #34b53a;
            border-radius: 999px;
        }

        .as-bar-note {
            margin: 8px 0 0;
            font-size: 12px;
            color: #444;
        }

        .as-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 14px;
        }

        .as-toolbar h2 {
            font-size: 20px;
            font-weight: 700;
            color: #f27640;
            margin: 0;
        }

        .as-filter {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 500;
        }

        .as-select {
            height: 38px;
            padding: 0 38px 0 14px;
            border: 1.5px solid #d3d3d3;
            border-radius: 12px;
            background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%23222' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' viewBox='0 0 16 16'%3E%3Cpath d='m3 6 5 5 5-5'/%3E%3C/svg%3E") no-repeat right 12px center;
            font-family: inherit;
            font-size: 14px;
            color: #111;
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
        }

        .as-select:focus-visible {
            outline: 3px solid #f27640;
            outline-offset: 2px;
        }

        .as-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr .9fr;
            align-items: center;
            column-gap: 16px;
            padding: 0 38px;
        }

        .as-thead {
            min-height: 70px;
            background: #faf3e3;
            border-radius: 18px;
            font-size: 19px;
            font-weight: 700;
            color: #f27640;
        }

        .as-thead span:nth-child(2),
        .as-row span:nth-child(2) {
            text-align: center;
        }

        .as-thead span:nth-child(3) {
            text-align: right;
        }

        .as-list {
            padding: 0 24px;
            background: #fff;
            border: 1.5px solid #d3d3d3;
            border-radius: 18px;
        }

        .as-row {
            padding: 18px 14px;
            border-top: 1px solid #e8e8e8;
        }

        .as-row:first-child {
            border-top: none;
        }

        .as-row[hidden] {
            display: none;
        }

        .as-date strong {
            display: inline;
            font-size: 14px;
            font-weight: 500;
            color: #000;
        }

        .as-date > span:not(.as-swapnote) {
            display: inline;
            margin: 0;
            font-size: 14px;
            font-weight: 500;
            color: #000;
        }

        .as-date .as-swapnote {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #d95f2a;
            line-height: 1.5;
        }

        .as-time {
            font-size: 15px;
            color: #111;
        }

        .as-badge {
            display: inline-block;
            justify-self: end;
            min-width: 128px;
            padding: 6px 14px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
        }

        .as-badge-hadir {
            background: #dcf0dc;
            color: #34a853;
        }

        .as-badge-izin {
            background: #fdeaa8;
            color: #f08c00;
        }

        .as-badge-sakit {
            background: #dcd8ec;
            color: #6a1be0;
        }

        .as-badge-alpha {
            background: #f0d5cc;
            color: #e5383b;
        }

        .as-empty {
            display: none;
            margin: 0;
            padding: 36px 14px;
            text-align: center;
            font-size: 15px;
            color: #667085;
        }

        .as-empty.is-visible {
            display: block;
        }

        .as-legend {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px 18px;
            margin-top: 16px;
            padding: 14px 22px;
            background: #e3ede9;
            border-radius: 20px;
            font-size: 13px;
        }

        .as-legend b {
            font-weight: 700;
            color: #1f6b5f;
        }

        .as-legend span {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #333;
        }

        .as-legend span::before {
            content: "";
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .as-legend .l-hadir::before { background: #34a853; }
        .as-legend .l-izin::before  { background: #f08c00; }
        .as-legend .l-sakit::before { background: #6a1be0; }
        .as-legend .l-alpha::before { background: #e5383b; }

        .as-swap {
            margin-top: 28px;
        }

        .as-swap-info {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 0 0 14px;
            padding: 12px 18px;
            background: #e3ede9;
            border-radius: 16px;
            font-size: 13.5px;
            line-height: 1.5;
            color: #26463f;
        }

        .as-swap-info i {
            margin-top: 2px;
            color: #1f6b5f;
        }

        .as-req-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .as-req {
            padding: 18px 22px 16px;
            background: #fff;
            border: 1.5px solid #d3d3d3;
            border-radius: 20px;
        }

        .as-req-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .as-tag {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
        }

        .as-tag-izin {
            background: #fdeaa8;
            color: #f08c00;
        }

        .as-tag-sakit {
            background: #dcd8ec;
            color: #6a1be0;
        }

        .as-state {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px 6px 10px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
        }

        .as-state::before {
            content: "";
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: currentColor;
        }

        .as-state-menunggu {
            background: #fdeaa8;
            color: #e58a00;
        }

        .as-state-disetujui {
            background: #dff3e3;
            color: #1f9d3a;
        }

        .as-state-ditolak {
            background: #f6d8d2;
            color: #d62f33;
        }

        .as-req-plan {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 16px;
        }

        .as-slot {
            min-width: 0;
            padding: 12px 16px;
            border-radius: 16px;
        }

        .as-slot-from {
            background: #f5f5f5;
        }

        .as-slot-to {
            background: #faf3e3;
        }

        .as-slot small {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #667085;
        }

        .as-slot-to small {
            color: #d95f2a;
        }

        .as-slot strong {
            display: block;
            margin-top: 2px;
            font-size: 16px;
            font-weight: 800;
            color: #000;
        }

        .as-slot span {
            display: block;
            margin-top: 2px;
            font-size: 13px;
            color: #333;
        }

        .as-req-plan > i {
            font-size: 22px;
            color: #f27640;
        }

        .as-req-meta {
            margin: 14px 0 0;
            font-size: 13.5px;
            line-height: 1.55;
            color: #222;
        }

        .as-req-meta + .as-req-meta {
            margin-top: 4px;
        }

        .as-req-meta b {
            font-weight: 700;
        }

        .as-req-foot {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }

        .as-link-btn {
            padding: 4px 2px;
            border: none;
            background: none;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            color: #d62f33;
            cursor: pointer;
        }

        .as-link-btn:hover {
            text-decoration: underline;
        }

        .as-dialog {
            width: min(560px, calc(100% - 32px));
            max-height: calc(100% - 32px);
            padding: 0;
            border: none;
            border-radius: 26px;
            background: #fff;
            color: #111;
            font-family: inherit;
        }

        .as-dialog::backdrop {
            background: rgba(17, 40, 36, .5);
        }

        .as-form {
            padding: 26px 28px 24px;
        }

        .as-form h3 {
            margin: 0 0 4px;
            font-size: 22px;
            font-weight: 800;
            color: #1f6b5f;
        }

        .as-form-sub {
            margin: 0 0 18px;
            font-size: 13.5px;
            color: #555;
        }

        .as-field {
            margin-bottom: 14px;
            border: none;
            padding: 0;
        }

        .as-field > label,
        .as-field > legend {
            display: block;
            margin-bottom: 6px;
            padding: 0;
            font-size: 14px;
            font-weight: 700;
            color: #111;
        }

        .as-input,
        .as-textarea {
            width: 100%;
            padding: 0 14px;
            border: 1.5px solid #d3d3d3;
            border-radius: 12px;
            background: #fff;
            font-family: inherit;
            font-size: 14px;
            color: #111;
        }

        .as-input {
            height: 42px;
        }

        .as-textarea {
            min-height: 84px;
            padding: 10px 14px;
            resize: vertical;
            line-height: 1.5;
        }

        .as-input:focus-visible,
        .as-textarea:focus-visible {
            outline: 3px solid #f27640;
            outline-offset: 1px;
            border-color: #f27640;
        }

        .as-seg {
            display: flex;
            gap: 10px;
        }

        .as-seg label {
            flex: 1;
            position: relative;
            cursor: pointer;
        }

        .as-seg input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .as-seg span {
            display: block;
            padding: 10px 14px;
            border: 1.5px solid #d3d3d3;
            border-radius: 12px;
            text-align: center;
            font-size: 14px;
            font-weight: 700;
            transition: background .2s ease, border-color .2s ease;
        }

        .as-seg input:checked + span {
            border-color: #f27640;
            background: #fdeee5;
            color: #d95f2a;
        }

        .as-seg input:focus-visible + span {
            outline: 3px solid #1f6b5f;
            outline-offset: 2px;
        }

        .as-replace {
            margin: 4px 0 14px;
            padding: 14px 16px 4px;
            background: #faf3e3;
            border-radius: 16px;
        }

        .as-replace > p {
            margin: 0 0 10px;
            font-size: 13px;
            line-height: 1.5;
            color: #555;
        }

        .as-replace .as-field > label {
            font-size: 13px;
        }

        .as-replace-row {
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr;
            gap: 10px;
        }

        .as-file-hint {
            margin: 6px 0 0;
            font-size: 12px;
            color: #667085;
        }

        .as-error {
            display: none;
            margin: 0 0 12px;
            padding: 10px 14px;
            background: #f6d8d2;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            color: #b4232a;
        }

        .as-error.is-visible {
            display: block;
        }

        .as-form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 6px;
        }

        @media (max-width: 900px) {
            .as-summary {
                grid-template-columns: 1fr 1fr;
            }

            .as-card-main {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 16px;
            }

            .as-header {
                flex-wrap: wrap;
            }

            .as-header i.as-header-icon {
                font-size: 28px;
            }

            .as-header h1 {
                font-size: 28px;
            }

            .as-header .as-btn {
                width: 100%;
                margin-left: 0;
            }

            .as-toolbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .as-thead {
                display: none;
            }

            .as-list {
                padding: 0 14px;
            }

            .as-grid {
                padding: 0 14px;
            }

            .as-row {
                grid-template-columns: 1fr;
                row-gap: 8px;
            }

            .as-row span:nth-child(2) {
                text-align: left;
            }

            .as-badge {
                justify-self: start;
                min-width: 110px;
            }

            .as-req {
                padding: 16px;
            }

            .as-req-top {
                flex-wrap: wrap;
            }

            .as-req-plan {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .as-req-plan > i {
                justify-self: center;
                transform: rotate(90deg);
            }

            .as-form {
                padding: 22px 18px 20px;
            }

            .as-replace-row {
                grid-template-columns: 1fr 1fr;
            }

            .as-replace-row .as-field:first-child {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 420px) {
            .as-summary {
                grid-template-columns: 1fr;
            }

            .as-form-actions {
                flex-direction: column-reverse;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .as-page * {
                transition: none !important;
            }
        }
    </style>
</head>
<body>

<div class="as-page">

    <header class="as-header">
        <i class="bi bi-clipboard2-check as-header-icon"></i>
        <h1>Absensi</h1>

        <button type="button" class="as-btn" id="btn-ajukan">
            <i class="bi bi-envelope-paper"></i>
            Ajukan Izin / Sakit
        </button>
    </header>

    <section class="as-summary" aria-label="Ringkasan kehadiran">

        <div class="as-card as-card-main">
            <span class="as-card-label"><i class="bi bi-calendar-check"></i> Kehadiran</span>
            <strong class="as-card-value">90 % <small>9 dari 10 sesi</small></strong>
            <div class="as-bar" role="img" aria-label="Kehadiran 90 persen, batas minimal 80 persen">
                <div class="as-bar-fill" style="width: 90%;"></div>
                <span class="as-bar-mark" style="left: 80%;"></span>
            </div>
        </div>

        <div class="as-card">
            <span class="as-card-label"><i class="bi bi-check-circle"></i> Hadir</span>
            <strong class="as-card-value">9 <small>sesi</small></strong>
        </div>

        <div class="as-card">
            <span class="as-card-label"><i class="bi bi-x-circle"></i> Tidak Hadir</span>
            <strong class="as-card-value">1 <small>sesi</small></strong>
        </div>

    </section>

    <div class="as-toolbar">
        <h2>Riwayat Absensi</h2>

        <div class="as-filter">
            <label for="filter-status">Tampilkan</label>
            <select id="filter-status" class="as-select">
                <option value="semua">Semua status</option>
                <option value="hadir">Hadir</option>
                <option value="izin">Izin</option>
                <option value="sakit">Sakit</option>
                <option value="alpha">Alpha</option>
            </select>
        </div>
    </div>

    <section aria-label="Riwayat absensi">
        <div class="as-grid as-thead">
            <span>Tanggal</span>
            <span>Jam Belajar</span>
            <span>Keterangan</span>
        </div>

        <div class="as-list" id="as-list">

            <div class="as-grid as-row" data-status="hadir">
                <div class="as-date">
                    <strong>10 Oktober 2026</strong>
                    <span>(Sabtu)</span>
                </div>
                <span class="as-time">10:00 - 12:00</span>
                <span class="as-badge as-badge-hadir">Hadir</span>
            </div>

            <div class="as-grid as-row" data-status="hadir">
                <div class="as-date">
                    <strong>3 Oktober 2026</strong>
                    <span>(Sabtu)</span>
                </div>
                <span class="as-time">10:00 - 12:00</span>
                <span class="as-badge as-badge-hadir">Hadir</span>
            </div>

            <div class="as-grid as-row" data-status="izin">
                <div class="as-date">
                    <strong>26 September 2026</strong>
                    <span>(Sabtu)</span>
                    <span class="as-swapnote">
                        <i class="bi bi-arrow-repeat"></i>
                        Jadwal ganti: Senin, 28 September 2026
                    </span>
                </div>
                <span class="as-time">10:00 - 12:00</span>
                <span class="as-badge as-badge-izin">Izin</span>
            </div>

            <div class="as-grid as-row" data-status="hadir">
                <div class="as-date">
                    <strong>19 September 2026</strong>
                    <span>Sabtu</span>
                </div>
                <span class="as-time">10:00 - 12:00</span>
                <span class="as-badge as-badge-hadir">Hadir</span>
            </div>

            <div class="as-grid as-row" data-status="hadir">
                <div class="as-date">
                    <strong>12 September 2026</strong>
                    <span>Sabtu</span>
                </div>
                <span class="as-time">10:00 - 12:00</span>
                <span class="as-badge as-badge-hadir">Hadir</span>
            </div>

            <div class="as-grid as-row" data-status="hadir">
                <div class="as-date">
                    <strong>5 September 2026</strong>
                    <span>Sabtu</span>
                </div>
                <span class="as-time">10:00 - 12:00</span>
                <span class="as-badge as-badge-hadir">Hadir</span>
            </div>

            <p class="as-empty" id="as-empty">Belum ada sesi dengan status ini.</p>

        </div>
    </section>

    <div class="as-legend">
        <b>Keterangan:</b>
        <span class="l-hadir">Hadir mengikuti sesi</span>
        <span class="l-izin">Izin dengan pemberitahuan</span>
        <span class="l-sakit">Sakit</span>
        <span class="l-alpha">Alpha tanpa keterangan</span>
    </div>

    <section class="as-swap" aria-labelledby="judul-pengganti">
        <div class="as-toolbar">
            <h2 id="judul-pengganti">Izin &amp; Jadwal Pengganti</h2>
        </div>

        <p class="as-swap-info">
            <i class="bi bi-info-circle"></i>
            <span>
                Jika izin atau sakit, ajukan jadwal pengganti ke tentor. Jadwal berlaku setelah disetujui tentor.
            </span>
        </p>

        <div class="as-req-list" id="req-list">

            <article class="as-req" data-session="2026-10-24">
                <div class="as-req-top">
                    <span class="as-tag as-tag-sakit">Sakit</span>
                    <span class="as-state as-state-menunggu">Menunggu konfirmasi tentor</span>
                </div>

                <div class="as-req-plan">
                    <div class="as-slot as-slot-from">
                        <small>Tidak hadir</small>
                        <strong>Sabtu, 24 Oktober 2026</strong>
                        <span>10:00 - 12:00</span>
                    </div>
                    <i class="bi bi-arrow-right"></i>
                    <div class="as-slot as-slot-to">
                        <small>Jadwal pengganti</small>
                        <strong>Senin, 26 Oktober 2026</strong>
                        <span>16:00 - 18:00</span>
                    </div>
                </div>

                <p class="as-req-meta"><b>Alasan:</b> Demam dan harus istirahat di rumah.</p>
                <p class="as-req-meta"><b>Tentor:</b> Deyoan Salsabila</p>

                <div class="as-req-foot">
                    <button type="button" class="as-link-btn" data-cancel>Batalkan pengajuan</button>
                </div>
            </article>

            <article class="as-req" data-session="2026-09-26">
                <div class="as-req-top">
                    <span class="as-tag as-tag-izin">Izin</span>
                    <span class="as-state as-state-disetujui">Disetujui tentor</span>
                </div>

                <div class="as-req-plan">
                    <div class="as-slot as-slot-from">
                        <small>Tidak hadir</small>
                        <strong>Sabtu, 26 September 2026</strong>
                        <span>10:00 - 12:00</span>
                    </div>
                    <i class="bi bi-arrow-right"></i>
                    <div class="as-slot as-slot-to">
                        <small>Jadwal pengganti</small>
                        <strong>Senin, 28 September 2026</strong>
                        <span>16:00 - 18:00</span>
                    </div>
                </div>

                <p class="as-req-meta"><b>Alasan:</b> Ada acara keluarga di luar kota.</p>
                <p class="as-req-meta"><b>Tentor:</b> Deyoan Salsabila. Oke, sampai ketemu di kelas pengganti.</p>
            </article>

        </div>

        <p class="as-empty" id="req-empty">Belum ada pengajuan izin atau sakit.</p>
    </section>

    <dialog class="as-dialog" id="dialog-ajukan" aria-labelledby="judul-form">
        <form class="as-form" id="form-ajukan" method="dialog" novalidate>
            <h3 id="judul-form">Ajukan Izin / Sakit</h3>
            <p class="as-form-sub">Pengajuan akan dikirim ke operator untuk dikonfirmasi.</p>

            <fieldset class="as-field">
                <legend>Jenis pengajuan</legend>
                <div class="as-seg">
                    <label>
                        <input type="radio" name="jenis" value="izin" checked>
                        <span>Izin</span>
                    </label>
                    <label>
                        <input type="radio" name="jenis" value="sakit">
                        <span>Sakit</span>
                    </label>
                </div>
            </fieldset>

            <div class="as-field">
                <label for="f-sesi">Sesi yang tidak bisa diikuti</label>
                <select id="f-sesi" name="sesi" class="as-input" required>
                    <option value="">Pilih sesi</option>
                    <option value="2026-10-24" data-jam="10:00 - 12:00">Sabtu, 24 Oktober 2026 (10:00 - 12:00)</option>
                    <option value="2026-10-31" data-jam="10:00 - 12:00">Sabtu, 31 Oktober 2026 (10:00 - 12:00)</option>
                </select>
            </div>

            <div class="as-field">
                <label for="f-alasan">Alasan</label>
                <textarea id="f-alasan" name="alasan" class="as-textarea" maxlength="300"
                          placeholder="Alasan tidak bisa hadir" required></textarea>
            </div>

            <div class="as-field">
                <label for="f-lampiran">Lampiran (opsional)</label>
                <input type="file" id="f-lampiran" name="lampiran" class="as-input" accept=".pdf,.jpg,.jpeg,.png" style="padding-top: 9px;">
                <p class="as-file-hint">Misalnya surat dokter. Format PDF, JPG, atau PNG.</p>
            </div>

            <div class="as-replace">
                <p>Pilih jadwal kelas pengganti sesuai waktu luangmu.</p>
                <div class="as-replace-row">
                    <div class="as-field">
                        <label for="f-tgl">Tanggal pengganti</label>
                        <input type="date" id="f-tgl" name="tgl_ganti" class="as-input" required>
                    </div>
                    <div class="as-field">
                        <label for="f-mulai">Mulai</label>
                        <input type="time" id="f-mulai" name="mulai" class="as-input" value="16:00" required>
                    </div>
                    <div class="as-field">
                        <label for="f-selesai">Selesai</label>
                        <input type="time" id="f-selesai" name="selesai" class="as-input" value="18:00" required>
                    </div>
                </div>
            </div>

            <p class="as-error" id="form-error" role="alert"></p>

            <div class="as-form-actions">
                <button type="button" class="as-btn-ghost" id="btn-batal">Batal</button>
                <button type="submit" class="as-btn">
                    Kirim ke Operator
                    <i class="bi bi-send"></i>
                </button>
            </div>
        </form>
    </dialog>

</div>

<script>
    (function () {
        var select = document.getElementById('filter-status');
        var rows   = document.querySelectorAll('#as-list .as-row');
        var empty  = document.getElementById('as-empty');

        select.addEventListener('change', function () {
            var value = select.value;
            var shown = 0;

            rows.forEach(function (row) {
                var match = value === 'semua' || row.dataset.status === value;
                row.hidden = !match;
                if (match) shown++;
            });

            empty.classList.toggle('is-visible', shown === 0);
        });

        var dialog   = document.getElementById('dialog-ajukan');
        var form     = document.getElementById('form-ajukan');
        var errorBox = document.getElementById('form-error');
        var reqList  = document.getElementById('req-list');
        var reqEmpty = document.getElementById('req-empty');
        var sesiSel  = document.getElementById('f-sesi');
        var tglInput = document.getElementById('f-tgl');

        var TENTOR = 'Deyoan Salsabila';

        function pad(n) { return n < 10 ? '0' + n : '' + n; }

        function todayISO() {
            var d = new Date();
            return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate());
        }

        function formatTanggal(iso) {
            var d = new Date(iso + 'T00:00:00');
            return d.toLocaleDateString('id-ID', {
                weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
            });
        }

        function escapeHtml(text) {
            var div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function showError(message) {
            errorBox.textContent = message;
            errorBox.classList.add('is-visible');
        }

        function clearError() {
            errorBox.textContent = '';
            errorBox.classList.remove('is-visible');
        }

        function refreshEmpty() {
            reqEmpty.classList.toggle('is-visible', reqList.children.length === 0);
        }

        function sessionTaken(iso) {
            return !!reqList.querySelector('[data-session="' + iso + '"]');
        }

        function syncSessionOptions() {
            Array.prototype.forEach.call(sesiSel.options, function (opt) {
                if (!opt.value) return;
                var taken = sessionTaken(opt.value);
                opt.disabled = taken;
                opt.textContent = opt.textContent.replace(' - sudah diajukan', '') + (taken ? ' - sudah diajukan' : '');
            });
        }

        function openDialog() {
            form.reset();
            clearError();
            tglInput.min = todayISO();
            syncSessionOptions();
            dialog.showModal();
        }

        function closeDialog() {
            dialog.close();
        }

        document.getElementById('btn-ajukan').addEventListener('click', openDialog);
       
        const params = new URLSearchParams(window.location.search);

        if (params.get('ajukan') === '1') {
            openDialog();
        }
        document.getElementById('btn-batal').addEventListener('click', closeDialog);

        dialog.addEventListener('click', function (e) {
            if (e.target === dialog) closeDialog();
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            clearError();

            var jenis    = form.elements.jenis.value;
            var sesi     = sesiSel.value;
            var alasan   = form.elements.alasan.value.trim();
            var tglGanti = tglInput.value;
            var mulai    = form.elements.mulai.value;
            var selesai  = form.elements.selesai.value;
            var file     = form.elements.lampiran.files[0];

            if (!sesi)     { showError('Pilih sesi yang tidak bisa kamu ikuti.'); return; }
            if (!alasan)   { showError('Isi alasan izin atau sakitmu.'); return; }
            if (!tglGanti) { showError('Pilih tanggal untuk kelas pengganti.'); return; }
            if (tglGanti < todayISO()) { showError('Tanggal pengganti tidak boleh sebelum hari ini.'); return; }
            if (tglGanti === sesi)     { showError('Tanggal pengganti harus berbeda dari tanggal sesi yang ditinggalkan.'); return; }
            if (!mulai || !selesai)    { showError('Isi jam mulai dan jam selesai.'); return; }
            if (selesai <= mulai)      { showError('Jam selesai harus lebih akhir dari jam mulai.'); return; }
            if (file && file.size > 5 * 1024 * 1024) { showError('Ukuran lampiran maksimal 5MB.'); return; }
            if (sessionTaken(sesi))    { showError('Sesi ini sudah punya pengajuan.'); return; }

            var jam = sesiSel.options[sesiSel.selectedIndex].dataset.jam;
            var jenisLabel = jenis === 'sakit' ? 'Sakit' : 'Izin';

            var card = document.createElement('article');
            card.className = 'as-req';
            card.dataset.session = sesi;
            card.innerHTML =
                '<div class="as-req-top">' +
                    '<span class="as-tag as-tag-' + jenis + '">' + jenisLabel + '</span>' +
                    '<span class="as-state as-state-menunggu">Menunggu konfirmasi tentor</span>' +
                '</div>' +
                '<div class="as-req-plan">' +
                    '<div class="as-slot as-slot-from">' +
                        '<small>Tidak hadir</small>' +
                        '<strong>' + formatTanggal(sesi) + '</strong>' +
                        '<span>' + jam + '</span>' +
                    '</div>' +
                    '<i class="bi bi-arrow-right"></i>' +
                    '<div class="as-slot as-slot-to">' +
                        '<small>Jadwal pengganti</small>' +
                        '<strong>' + formatTanggal(tglGanti) + '</strong>' +
                        '<span>' + mulai + ' - ' + selesai + '</span>' +
                    '</div>' +
                '</div>' +
                '<p class="as-req-meta"><b>Alasan:</b> ' + escapeHtml(alasan) + '</p>' +
                (file ? '<p class="as-req-meta"><b>Lampiran:</b> ' + escapeHtml(file.name) + '</p>' : '') +
                '<p class="as-req-meta"><b>Tentor:</b> ' + TENTOR + '</p>' +
                '<div class="as-req-foot">' +
                    '<button type="button" class="as-link-btn" data-cancel>Batalkan pengajuan</button>' +
                '</div>';

            reqList.insertBefore(card, reqList.firstChild);
            refreshEmpty();
            closeDialog();
            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });

        reqList.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-cancel]');
            if (!btn) return;

            var card = btn.closest('.as-req');
            if (card && window.confirm('Batalkan pengajuan ini?')) {
                card.remove();
                refreshEmpty();
            }
        });

        refreshEmpty();
    })();
</script>

</body>
</html>