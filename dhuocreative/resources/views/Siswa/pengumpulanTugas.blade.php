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

        .pt-page {
            width: 100%;
            max-width: 100%;
            font-family: 'Plus Jakarta Sans', 'Bricolage Grotesque', sans-serif;
            color: var(--teal);
            box-sizing: border-box;
        }

        .pt-page *,
        .pt-page *::before,
        .pt-page *::after {
            box-sizing: border-box;
        }

        .pt-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .pt-header i {
            font-size: 40px;
            line-height: 1;
            color: var(--teal);
        }

        .pt-header h1 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 36px;
            font-weight: 800;
            line-height: 1.1;
            color: var(--teal);
            margin: 0;
        }

        .pt-grid {
            display: grid;
            grid-template-columns: 1.3fr 1fr .75fr;
            align-items: center;
            column-gap: 16px;
        }

        .pt-thead {
            padding: 0 34px;
            min-height: 52px;
            border-radius: 14px;
            font-size: 17px;
            font-weight: 700;
            background: var(--cream);
            color: var(--orange-dk);
        }

        .pt-list {
            margin-top: 14px;
            padding: 0 22px;
            background: var(--ivory);
            border: 1px solid var(--mint-line);
            border: 1.5px solid var(--ivory);
            border-radius: 18px;
        }

        .pt-row {
            padding: 18px 12px;
        }

        .pt-row + .pt-row {
            border-top: 1px solid var(--mint-line);
        }

        .pt-name strong {
            display: block;
            font-size: 16px;
            font-weight: 700;
            color: var(--teal);
        }

        .pt-name span {
            display: block;
            margin-top: 6px;
            font-size: 14px;
            color: var(--teal-muted);
        }

        .pt-deadline {
            font-size: 14.5px;
            line-height: 1.65;
            color: var(--teal);
        }

        .pt-status {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            justify-self: start;
            padding: 9px 18px 9px 12px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
            white-space: nowrap;
        }

        .pt-status::before {
            content: "";
            width: 11px;
            height: 11px;
            border-radius: 50%;
            background: currentColor;
        }

        .pt-status-done {
            background: var(--mint);
            color: var(--teal);
        }

        .pt-status-pending {
            background: #fff0c9;
            color: var(--gold-dk);
        }

        .pt-submit {
            margin-top: 16px;
            padding: 22px 36px 26px;
            background: var(--mint);
            border-radius: 28px;
        }

        .pt-submit h2 {
            font-size: 21px;
            font-weight: 700;
            color: var(--teal);
            margin: 0 0 14px;
        }

        .pt-field {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .pt-field label,
        .pt-upload-label {
            font-size: 15px;
            font-weight: 700;
            color: var(--teal);
        }

        .pt-select {
            width: 295px;
            max-width: 100%;
            height: 42px;
            padding: 0 40px 0 14px;
            border: none;
            border-radius: 14px;
            background: var(--ivory) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%23222' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' viewBox='0 0 16 16'%3E%3Cpath d='m3 6 5 5 5-5'/%3E%3C/svg%3E") no-repeat right 14px center;
            font-family: inherit;
            font-size: 14px;
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
            background-color: var(--ivory);
            color: var(--teal);
        }

        .pt-upload-row {
            display: grid;
            grid-template-columns: 154px 395px 1fr;
            align-items: start;
            column-gap: 0;
        }

        .pt-upload-label {
            padding-top: 0;
        }

        .pt-drop {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            width: 100%;
            height: 142px;
            padding: 16px;
            border: 2px dashed var(--gold);
            border-radius: 18px;
            text-align: center;
            cursor: pointer;
            background: var(--cream);
            border-color: var(--gold);
            color: var(--teal);
            transition: background .2s ease, border-color .2s ease;
        }

        .pt-drop:hover,
        .pt-drop.is-over {
            background: var(--sand);
            border-color: var(--orange);
        }

        .pt-drop:focus-within {
            outline-color: var(--orange);
            outline: 3px solid var(--orange);
            outline-offset: 2px;
        }

        .pt-drop i {
            font-size: 54px;
            line-height: 1;
            color: #b98a4a;
        }

        .pt-drop-title {
            font-size: 14px;
            font-weight: 500;
        }

        .pt-drop-note {
            font-size: 11px;
            color: var(--teal-muted);
        }

        .pt-file-input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }

        .pt-file-name {
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 14px;
            font-weight: 700;
            color: var(--teal);
        }

        .pt-btn {
            justify-self: end;
            align-self: center;
            margin-top: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-width: 228px;
            height: 53px;
            padding: 0 32px;
            border: none;
            border-radius: 999px;
            background: var(--orange-dk);
            color: var(--ivory);
            font-family: inherit;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s ease, transform .2s ease;
        }

        .pt-btn:hover {
            background: #d95f2a;
            transform: scale(1.03);
        }

        .pt-btn:focus-visible {
            outline: 3px solid #1f6b5f;
            outline-offset: 2px;
        }

        @media (max-width: 1100px) {
            .pt-upload-row {
                grid-template-columns: 130px minmax(0, 1fr) auto;
                column-gap: 20px;
            }

            .pt-btn {
                min-width: 0;
                padding: 0 24px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 16px;
            }

            .pt-header i {
                font-size: 32px;
            }

            .pt-header h1 {
                font-size: 28px;
            }

            .pt-thead {
                display: none;
            }

            .pt-list {
                margin-top: 0;
                padding: 0 14px;
            }

            .pt-grid.pt-row {
                grid-template-columns: 1fr;
                row-gap: 8px;
            }

            .pt-submit {
                padding: 20px 16px 22px;
                border-radius: 22px;
            }

            .pt-field {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .pt-select {
                width: 100%;
            }

            .pt-upload-row {
                grid-template-columns: 1fr;
                row-gap: 10px;
            }

            .pt-btn {
                justify-self: stretch;
                margin-top: 10px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .pt-page * {
                transition: none !important;
            }
        }
    </style>
</head>
<body>

<div class="pt-page">

    <!-- Judul -->
    <header class="pt-header">
        <h1>Tugas</h1>
    </header>

    <!-- Daftar tugas -->
    <section aria-label="Daftar tugas">
        <div class="pt-grid pt-thead">
            <span>Nama Tugas</span>
            <span>Deadline</span>
            <span>Status</span>
        </div>

        <div class="pt-list">

            <div class="pt-grid pt-row">
                <div class="pt-name">
                    <strong>Latihan HTML</strong>
                    <span>Web Development</span>
                </div>
                <div class="pt-deadline">
                    12 Oktober 2026<br>23:59
                </div>
                <span class="pt-status pt-status-done">Dikumpulkan</span>
            </div>

            <div class="pt-grid pt-row">
                <div class="pt-name">
                    <strong>Membuat Halaman Profil</strong>
                    <span>Web Development</span>
                </div>
                <div class="pt-deadline">
                    19 Oktober 2026<br>23:59
                </div>
                <span class="pt-status pt-status-pending">Belum Dikumpulkan</span>
            </div>

        </div>
    </section>

    <!-- Pengumpulan tugas -->
    <section class="pt-submit" aria-labelledby="judul-pengumpulan">
        <h2 id="judul-pengumpulan">Pengumpulan Tugas</h2>

        <form id="form-tugas">

            <div class="pt-field">
                <label for="kelas">Kelas</label>
                <select id="kelas" name="kelas" class="pt-select">
                    <option>Web Development</option>
                </select>
            </div>

            <div class="pt-field">
                <label for="kelas">Tugas</label>
                <select id="kelas" name="kelas" class="pt-select">
                    <option>Latihan HTML</option>
                    <option>Membuat Halaman Profil</option>
                </select>
            </div>


            <div class="pt-upload-row">
                <span class="pt-upload-label">Upload File Tugas</span>

                <label class="pt-drop" id="dropzone">
                    <input type="file" id="file-tugas" name="file" class="pt-file-input">
                    <i class="bi bi-cloud-upload" id="drop-icon"></i>
                    <span class="pt-drop-title" id="drop-title">Klik untuk mengunggah</span>
                    <span class="pt-drop-note" id="drop-note">(file max 50MB)</span>
                </label>

                <button type="submit" class="pt-btn">
                    Kumpulkan Tugas
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>

        </form>
    </section>

</div>

<script>
    (function () {
        var MAX_SIZE = 50 * 1024 * 1024; // 50MB
        var input = document.getElementById('file-tugas');
        var zone  = document.getElementById('dropzone');
        var title = document.getElementById('drop-title');
        var note  = document.getElementById('drop-note');
        var form  = document.getElementById('form-tugas');

        function showFile(file) {
            if (!file) return;

            if (file.size > MAX_SIZE) {
                input.value = '';
                title.className = 'pt-drop-title';
                title.textContent = 'Klik untuk mengunggah';
                note.textContent = 'File terlalu besar. Maksimal 50MB.';
                return;
            }

            title.className = 'pt-file-name';
            title.textContent = file.name;
            note.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
        }

        input.addEventListener('change', function () {
            showFile(input.files[0]);
        });

        ['dragenter', 'dragover'].forEach(function (evt) {
            zone.addEventListener(evt, function (e) {
                e.preventDefault();
                zone.classList.add('is-over');
            });
        });

        ['dragleave', 'drop'].forEach(function (evt) {
            zone.addEventListener(evt, function (e) {
                e.preventDefault();
                zone.classList.remove('is-over');
            });
        });

        zone.addEventListener('drop', function (e) {
            if (e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                showFile(input.files[0]);
            }
        });

        form.addEventListener('submit', function (e) {
            if (!input.files.length) {
                e.preventDefault();
                note.textContent = 'Pilih file tugas terlebih dahulu.';
            }
        });
    })();
</script>

</body>
</html>
