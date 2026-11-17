@extends('layouts.siswa')

@section('content')

<style>
/* pagination siswa – rata tengah (inject di section biar pasti ke-load) */
.nl-page .tj-pagination,
.pt-page .tj-pagination,
.tj-pagination {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;    gap: 8px !important;
    margin: 20px auto !important;
    padding: 8px 0 !important;
    flex-wrap: wrap !important;
    width: 100% !important;
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
    box-shadow: 0 1px 2px rgba(0,0,0,.04);
}
.tj-pagination .tj-pg-btn:hover:not(:disabled):not(.is-active):not(.active) {
    background: #e3efed;
    border-color: #286c65;
}
.tj-pagination .tj-pg-btn.is-active,
.tj-pagination .tj-pg-btn.active {
    background: #f27640;
    border-color: #f27640;
    color: #fff;
    cursor: default;
    box-shadow: 0 2px 8px rgba(242,118,64,.35);
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
</style>


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
                <button type="button" class="tj-pg-btn" disabled aria-label="Sebelumnya">&lt;</button>
            @else
                <a href="{{ $daftar->previousPageUrl() }}" class="tj-pg-btn" aria-label="Sebelumnya">&lt;</a>
            @endif

            @php
                $__cur = $daftar->currentPage();
                $__last = $daftar->lastPage();
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
                    <a href="{{ $daftar->url($__i) }}" class="tj-pg-btn">{{ $__i }}</a>
                @endif
                @php $__prev = $__i; @endphp
            @endforeach

            @if ($daftar->hasMorePages())
                <a href="{{ $daftar->nextPageUrl() }}" class="tj-pg-btn" aria-label="Berikutnya">&gt;</a>
            @else
                <button type="button" class="tj-pg-btn" disabled aria-label="Berikutnya">&gt;</button>
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