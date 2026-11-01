
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

        <article class="t-announcement-card"
                 data-tanggal="2026-10-18">

            <div class="t-announcement-top">
                <span class="t-announcement-category">
                    Pengumuman
                </span>

                <span class="t-announcement-date">
                    18 Oktober 2026
                </span>
            </div>

            <h3>Informasi Kegiatan Belajar</h3>

            <p class="t-announcement-body">
                Siswa dan tentor diharapkan mengikuti kegiatan
                belajar sesuai jadwal yang telah ditentukan.
            </p>

            <p class="t-announcement-author">
                Dibuat oleh: Admin (Admin)
            </p>

        </article>


        <article class="t-announcement-card"
                 data-tanggal="2026-10-17">

            <div class="t-announcement-top">
                <span class="t-announcement-category">
                    Pengumuman
                </span>

                <span class="t-announcement-date">
                    17 Oktober 2026
                </span>
            </div>

            <h3>Pengingat Pembayaran</h3>

            <p class="t-announcement-body">
                Mohon periksa status pembayaran kursus melalui
                bagian administrasi. Jika sudah membayar,
                abaikan pengingat ini.
            </p>

            <p class="t-announcement-author">
                Dibuat oleh: Rina (Operator)
            </p>

        </article>


        <article class="t-announcement-card"
                 data-tanggal="2026-10-16">

            <div class="t-announcement-top">
                <span class="t-announcement-category">
                    Pengumuman
                </span>

                <span class="t-announcement-date">
                    16 Oktober 2026
                </span>
            </div>

            <h3>Materi Pertemuan Berikutnya</h3>

            <p class="t-announcement-body">
                Untuk pertemuan berikutnya, siswa diminta
                mempersiapkan materi latihan yang telah
                dibagikan di kelas.
            </p>

            <p class="t-announcement-author">
                Dibuat oleh: Dewi (Tentor)
            </p>

        </article>

    </div>

    <p class="t-empty" id="pengumumanKosong" hidden>
        Pengumuman tidak ditemukan.
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
