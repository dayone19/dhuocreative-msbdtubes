
@extends('layouts.siswa')

@section('title', 'Pengumuman')

@section('content')

<div class="t-page">

    <div class="t-page-header">
        <div>
            <h1 class="t-page-title">Pengumuman</h1>
        </div>
    </div>

    <div class="t-announcement-toolbar">

        <div class="t-search-wrap">
            <input
                type="search"
                id="cariPengumuman"
                class="t-search"
                placeholder="Cari judul, isi, atau pembuat..."
                aria-label="Cari pengumuman"
            >

            <svg viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="10.8" cy="10.8" r="6.8"></circle>
                <path d="m16 16 5 5"></path>
            </svg>
        </div>

        <input
            type="date"
            id="filterTanggalPengumuman"
            class="t-filter"
            style="background-image: none"
            aria-label="Filter tanggal pengumuman"
        >

    </div>

    <div class="t-announcement-list" id="daftarPengumuman">

        @foreach ($pengumuman as $p)
        <article class="t-announcement-card"
                 data-tanggal="{{ $p->tanggal_iso}}">

            <div class="t-announcement-top">
                <span class="t-announcement-category">
                    Pengumuman
                </span>

                <span class="t-announcement-date">
                    {{ $p->tanggal_teks }}
                </span>
            </div>

            <h3>{{ $p->judul}}</h3>

            <p class="t-announcement-body">
                {{ $p->isi }}
            </p>

            <p class="t-announcement-author">
                {{ $p->pembuat_teks }}
            </p>

        </article>
        @endforeach

    </div>

    <p class="t-empty" id="pengumumanKosong" hidden>
        {{ count($pengumuman) > 0 ? 'pengumuman tidak ditemukan.' : 'Belum ada pengumuman' }}
    </p>

</div>

<style>
.t-announcement-category {
    display: inline-block;
    font-size: .78rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 999px;
    background: #fff0df;
    color: #a95412;
}

.t-announcement-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}

.t-announcement-author {
    opacity: .8;
    font-size: .9rem;
}

.t-page-subtitle {
    margin-top: 6px;
    color: #777;
    font-size: .92rem;
}
</style>

<script>
(() => {

    const el = id => document.getElementById(id);

    const list = el('daftarPengumuman');

    function filterAnnouncements() {

        const search = el('cariPengumuman')
            .value.toLowerCase().trim();

        const date = el('filterTanggalPengumuman').value;

        let visibleCount = 0;

        list.querySelectorAll('.t-announcement-card')
            .forEach(card => {

                const matchesSearch =
                    card.textContent.toLowerCase().includes(search);

                const matchesDate =
                    !date || card.dataset.tanggal === date;

                card.hidden = !(matchesSearch && matchesDate);

                if (!card.hidden) {
                    visibleCount++;
                }
            });

        el('pengumumanKosong').hidden = visibleCount > 0;
    }

    el('cariPengumuman').addEventListener(
        'input',
        filterAnnouncements
    );

    el('filterTanggalPengumuman').addEventListener(
        'change',
        filterAnnouncements
    );

    filterAnnouncements();

})();
</script>

@endsection
