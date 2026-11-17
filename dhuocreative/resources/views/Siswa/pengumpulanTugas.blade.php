@extends('layouts.siswa')
@section('content')

<div class="pt-page">

    @if (session('sukses'))
        <p style="background:#e3f6e8;color:#1d6b35;padding:10px 14px;border-radius:10px;margin:0 0 12px;">
            {{ session('sukses') }}
        </p>
    @endif

    @if ($errors->any())
        <p style="background:#fde8e8;color:#a12222;padding:10px 14px;border-radius:10px;margin:0 0 12px;">
            {{ $errors->first() }}
        </p>
    @endif

    <!-- Daftar tugas -->
    <section aria-label="Daftar tugas">
        <div class="pt-grid pt-thead">
            <span>Nama Tugas</span>
            <span>Deskripsi</span>
            <span>Deadline</span>
            <span>Status</span>
        </div>

        <div class="pt-list">
            @forelse ($tugas as $t)
                <div class="pt-grid pt-row">
                    <div class="pt-name">
                        <strong>{{ $t->judul }}</strong>
                        <span>{{ $t->nama_kursus }}</span>
                    </div>

                    <div class="pt-description">
                        {{ $t->deskripsi_teks }}
                    </div>

                    <div class="pt-deadline">
                        {{ $t->deadline_tanggal }}<br>
                        {{ $t->deadline_jam }}
                    </div>

                    <span class="pt-status {{ $t->status_css }}">
                        {{ $t->status_teks }}
                    </span>
                </div>
            @empty
                <p style="padding: 16px;">Belum ada tugas.</p>
            @endforelse
        </div>

        @if ($tugas->hasPages())
            <nav class="tj-pagination" aria-label="Navigasi halaman">

                @if ($tugas->onFirstPage())
                    <button type="button" class="tj-pg-btn" disabled aria-label="Sebelumnya">&lt;</button>
                @else
                    <a href="{{ $tugas->previousPageUrl() }}" class="tj-pg-btn" aria-label="Sebelumnya">&lt;</a>
                @endif

                @php
                    $__cur = $tugas->currentPage();
                    $__last = $tugas->lastPage();

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
                        <button type="button" class="tj-pg-btn is-active" aria-current="page">
                            {{ $__i }}
                        </button>
                    @else
                        <a href="{{ $tugas->url($__i) }}" class="tj-pg-btn">
                            {{ $__i }}
                        </a>
                    @endif

                    @php $__prev = $__i; @endphp
                @endforeach

                @if ($tugas->hasMorePages())
                    <a href="{{ $tugas->nextPageUrl() }}" class="tj-pg-btn" aria-label="Berikutnya">&gt;</a>
                @else
                    <button type="button" class="tj-pg-btn" disabled aria-label="Berikutnya">&gt;</button>
                @endif

            </nav>
        @endif
    </section>

    <!-- Pengumpulan tugas -->
    <section class="pt-submit" aria-labelledby="judul-pengumpulan">
        <h2 id="judul-pengumpulan">Pengumpulan Tugas</h2>

        <form id="form-tugas" method="POST" action="{{ route('siswa.pengumpulanTugas.kumpulkan') }}" enctype="multipart/form-data">
            @csrf

            <div class="pt-field">
                <label for="kelas">Kelas</label>

                <select id="kelas" name="id_kelas" class="pt-select">
                    @forelse ($kelasSiswa as $k)
                        <option value="{{ $k->id_kelas }}">
                            {{ $k->nama_kursus }}
                        </option>
                    @empty
                        <option value="">Belum ada kelas aktif</option>
                    @endforelse
                </select>
            </div>

            <div class="pt-field">
                <label for="tugas">Tugas</label>

                <select id="tugas" name="id_tugas" class="pt-select" required>
                    <option value="">Pilih kelas dulu</option>

                    @foreach ($semuaTugas as $t)
                        <option value="{{ $t->id_tugas }}"
                                data-kelas="{{ $t->id_kelas }}"
                                {{ $t->sudah_dinilai ? 'disabled' : '' }}>
                            {{ $t->judul }}{{ $t->sudah_dinilai ? ' (sudah dinilai)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="pt-upload-row">
                <span class="pt-upload-label">Upload File Tugas</span>

                <label class="pt-drop" id="dropzone">
                    <input type="file" id="file-tugas" name="file" class="pt-file-input"
                           accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.jpg,.jpeg,.png">

                    <i class="bi bi-cloud-upload" id="drop-icon"></i>

                    <span class="pt-drop-title" id="drop-title">
                        Klik untuk mengunggah
                    </span>

                    <span class="pt-drop-note" id="drop-note">
                        (file max 50MB)
                    </span>
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
    var MAX_SIZE = 50 * 1024 * 1024;
    var input = document.getElementById('file-tugas');
    var zone = document.getElementById('dropzone');
    var title = document.getElementById('drop-title');
    var note = document.getElementById('drop-note');
    var form = document.getElementById('form-tugas');
    var selKelas = document.getElementById('kelas');
    var selTugas = document.getElementById('tugas');

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

    function saringTugas() {
        var kelasId = selKelas.value;
        var first = null;

        Array.prototype.forEach.call(selTugas.options, function (opt) {
            if (!opt.value) {
                opt.hidden = false;
                return;
            }

            var cocok = String(opt.getAttribute('data-kelas')) === String(kelasId);
            opt.hidden = !cocok;

            if (cocok && !opt.disabled && !first) {
                first = opt;
            }
        });

        selTugas.value = first ? first.value : '';
    }

    selKelas.addEventListener('change', saringTugas);
    saringTugas();

    form.addEventListener('submit', function (e) {
        if (!selTugas.value) {
            e.preventDefault();
            note.textContent = 'Pilih tugas terlebih dahulu.';
            return;
        }

        if (!input.files.length) {
            e.preventDefault();
            note.textContent = 'Pilih file tugas terlebih dahulu.';
        }
    });
})();
</script>

@endsection
