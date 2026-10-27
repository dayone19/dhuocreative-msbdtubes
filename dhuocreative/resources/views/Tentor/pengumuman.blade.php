{{-- 
    Halaman Pengumuman dengan hak akses role.
    Sesuaikan kolom role dan nama pengguna dengan struktur tabel users di proyekmu.
    Contoh di bawah mengasumsikan auth()->user()->role dan auth()->user()->name.
--}}
@extends('layouts.Tentor')
@section('title', 'Pengumuman')

@php
    $rolePengguna = auth()->user()->role ?? 'Tentor';
    $namaPengguna = auth()->user()->name ?? 'Tentor Demo';
    $roleNormal = strtolower(trim($rolePengguna));
    $bolehPosting = in_array($roleNormal, ['admin', 'operator', 'tentor']);
    $penggunaSiswa = $roleNormal === 'siswa';
@endphp

@section('content')
<div class="t-page">
    <div class="t-page-header">
        <div>
            <h1 class="t-page-title">Pengumuman</h1>
        </div>

        @if ($bolehPosting)
        <button type="button" class="t-btn t-btn-orange" id="btnTambahPengumuman">
            <span class="t-plus">+</span> Buat Pengumuman
        </button>
        @endif
    </div>

    <div class="t-announcement-toolbar">
        <div class="t-search-wrap">
            <input type="search" id="cariPengumuman" class="t-search"
                   placeholder="Cari judul, isi, atau pembuat..." aria-label="Cari pengumuman">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="10.8" cy="10.8" r="6.8"></circle><path d="m16 16 5 5"></path>
            </svg>
        </div>
        <input type="date" id="filterTanggalPengumuman" style="background-image: none"
               class="t-filter" aria-label="Filter tanggal pengumuman">
    </div>

    <div class="t-announcement-list" id="daftarPengumuman">
        {{-- Data contoh. Nantinya ganti dengan data dari database. --}}
        <article class="t-announcement-card"
                 data-tanggal="2026-10-18"
                 data-pembuat="Admin"
                 data-role-pembuat="admin"
                 data-owner="admin">
            <div class="t-announcement-top">
                <span class="t-announcement-date">18 Oktober 2026</span>
            </div>
            <h3>Informasi Kegiatan Belajar</h3>
            <p class="t-announcement-body">Siswa dan tentor diharapkan mengikuti kegiatan belajar sesuai jadwal yang telah ditentukan.</p>
            <p class="t-announcement-author">Dibuat oleh: Admin (Admin)</p>
            <div class="t-announcement-actions"></div>
        </article>

        <article class="t-announcement-card"
                 data-tanggal="2026-10-17"
                 data-pembuat="Rina"
                 data-role-pembuat="operator"
                 data-owner="operator">
            <div class="t-announcement-top">
                <span class="t-announcement-date">17 Oktober 2026</span>
            </div>
            <h3>Pengingat Pembayaran</h3>
            <p class="t-announcement-body">Mohon periksa status pembayaran kursus melalui bagian administrasi. Jika sudah membayar, abaikan pengingat ini.</p>
            <p class="t-announcement-author">Dibuat oleh: Rina (Operator)</p>
            <div class="t-announcement-actions"></div>
        </article>

        <article class="t-announcement-card"
                 data-tanggal="2026-10-16"
                 data-pembuat="Dewi"
                 data-role-pembuat="tentor"
                 data-owner="tentor">
            <div class="t-announcement-top">
                <span class="t-announcement-date">16 Oktober 2026</span>
            </div>
            <h3>Materi Pertemuan Berikutnya</h3>
            <p class="t-announcement-body">Untuk pertemuan berikutnya, siswa diminta mempersiapkan materi latihan yang telah dibagikan di kelas.</p>
            <p class="t-announcement-author">Dibuat oleh: Dewi (Tentor)</p>
            <div class="t-announcement-actions"></div>
        </article>
    </div>

    <p class="t-empty" id="pengumumanKosong" hidden>Pengumuman tidak ditemukan.</p>
</div>

@if ($bolehPosting)
<div class="t-modal" id="modalPengumuman" hidden>
    <div class="t-modal-box">
        <div class="t-modal-header">
            <button type="button" class="t-modal-close" data-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor"
                     class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2 id="judulModalPengumuman">Buat Pengumuman</h2>
        </div>

        <form id="formPengumuman">
            <div class="t-form-group">
                <label for="judulPengumuman">Judul</label>
                <input id="judulPengumuman" type="text" maxlength="150" required
                       placeholder="Masukkan judul pengumuman">
            </div>

            <div class="t-form-group">
                <label for="pembuatPengumuman">Dibuat oleh</label>
                {{-- Otomatis mengikuti akun login, tidak dapat diubah dari form. --}}
                <input id="pembuatPengumuman" type="text" value="{{ $namaPengguna }} ({{ $rolePengguna }})" readonly>
            </div>

            <div class="t-form-group">
                <label for="isiPengumuman">Isi Pengumuman</label>
                <textarea id="isiPengumuman" rows="5" required
                          placeholder="Tulis isi pengumuman..."></textarea>
            </div>

            <div class="t-form-group">
                <label for="tanggalPengumuman">Tanggal</label>
                <input id="tanggalPengumuman" type="date" required>
            </div>

            <button type="submit" class="t-btn t-btn-orange o-btn-full">Simpan Pengumuman</button>
        </form>
    </div>
</div>
@endif

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
.t-announcement-actions {
    display: flex;
    gap: 8px;
    margin-top: 12px;
}
.t-announcement-actions button {
    cursor: pointer;
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
    const currentRole = @json($roleNormal);
    const currentName = @json($namaPengguna);
    const canPost = ['admin', 'operator', 'tentor'].includes(currentRole);
    const modal = el('modalPengumuman');
    const form = el('formPengumuman');
    let editing = null;

    function today() {
        const d = new Date();
        const offset = d.getTimezoneOffset();
        return new Date(d.getTime() - offset * 60000).toISOString().slice(0, 10);
    }

    function fmt(value) {
        return value
            ? new Date(value + 'T12:00:00').toLocaleDateString('id-ID', {
                day: 'numeric', month: 'long', year: 'numeric'
            })
            : '-';
    }

    function openModal(card = null) {
        if (!canPost || !modal || !form) return;
        editing = card;
        form.reset();
        el('judulModalPengumuman').textContent = card ? 'Edit Pengumuman' : 'Buat Pengumuman';

        if (card) {
            el('judulPengumuman').value = card.querySelector('h3').textContent;
            el('isiPengumuman').value = card.querySelector('.t-announcement-body').textContent.trim();
            el('tanggalPengumuman').value = card.dataset.tanggal || today();
        } else {
            el('tanggalPengumuman').value = today();
        }

        modal.hidden = false;
    }

    function renderActions(card) {
        const actions = card.querySelector('.t-announcement-actions');
        if (!actions) return;
        actions.replaceChildren();

        // Pada contoh ini, pengguna hanya boleh mengubah pengumuman yang ia buat.
        const isOwner = (card.dataset.owner || '').toLowerCase() === currentRole;
        if (!canPost || !isOwner) return;

        const editButton = document.createElement('button');
        editButton.type = 'button';
        editButton.className = 't-action';
        editButton.dataset.edit = '';
        editButton.textContent = 'Edit';

        const deleteButton = document.createElement('button');
        deleteButton.type = 'button';
        deleteButton.className = 't-action';
        deleteButton.dataset.delete = '';
        deleteButton.textContent = 'Hapus';

        actions.append(editButton, deleteButton);
    }

    function renderCard(card, title, body, author, authorRole, date, ownerRole) {
        card.className = 't-announcement-card';
        card.dataset.tanggal = date;
        card.dataset.pembuat = author;
        card.dataset.rolePembuat = authorRole;
        card.dataset.owner = ownerRole;
        card.replaceChildren();

        const top = document.createElement('div');
        top.className = 't-announcement-top';

        const category = document.createElement('span');
        category.className = 't-announcement-category';
        category.textContent = 'Pengumuman';

        const dateLabel = document.createElement('span');
        dateLabel.className = 't-announcement-date';
        dateLabel.textContent = fmt(date);
        top.append(category, dateLabel);

        const heading = document.createElement('h3');
        heading.textContent = title;

        const paragraph = document.createElement('p');
        paragraph.className = 't-announcement-body';
        paragraph.textContent = body;

        const authorLabel = document.createElement('p');
        authorLabel.className = 't-announcement-author';
        authorLabel.textContent = `Dibuat oleh: ${author} (${authorRole})`;

        const actions = document.createElement('div');
        actions.className = 't-announcement-actions';

        card.append(top, heading, paragraph, authorLabel, actions);
        renderActions(card);
    }

    if (canPost) {
        el('btnTambahPengumuman')?.addEventListener('click', () => openModal());

        document.querySelectorAll('[data-close]').forEach(button => {
            button.addEventListener('click', () => { modal.hidden = true; });
        });

        list.addEventListener('click', event => {
            const editButton = event.target.closest('[data-edit]');
            const deleteButton = event.target.closest('[data-delete]');

            if (editButton) {
                const card = editButton.closest('.t-announcement-card');
                if ((card.dataset.owner || '').toLowerCase() === currentRole) openModal(card);
            }

            if (deleteButton) {
                const card = deleteButton.closest('.t-announcement-card');
                if ((card.dataset.owner || '').toLowerCase() === currentRole &&
                    confirm('Hapus pengumuman ini?')) {
                    card.remove();
                    filterAnnouncements();
                }
            }
        });

        form?.addEventListener('submit', event => {
            event.preventDefault();

            const title = el('judulPengumuman').value.trim();
            const body = el('isiPengumuman').value.trim();
            const date = el('tanggalPengumuman').value;

            if (!title || !body || !date) return;

            const card = editing || document.createElement('article');
            renderCard(card, title, body, currentName, currentRole, date, currentRole);

            if (!editing) list.prepend(card);
            modal.hidden = true;
            filterAnnouncements();
        });

        modal?.addEventListener('click', event => {
            if (event.target === modal) modal.hidden = true;
        });

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape' && modal) modal.hidden = true;
        });
    }

    function filterAnnouncements() {
        const search = el('cariPengumuman').value.toLowerCase().trim();
        const date = el('filterTanggalPengumuman').value;
        let visibleCount = 0;

        list.querySelectorAll('.t-announcement-card').forEach(card => {
            const matchesSearch = card.textContent.toLowerCase().includes(search);
            const matchesDate = !date || card.dataset.tanggal === date;
            card.hidden = !(matchesSearch && matchesDate);
            if (!card.hidden) visibleCount++;
        });

        el('pengumumanKosong').hidden = visibleCount > 0;
    }

    list.querySelectorAll('.t-announcement-card').forEach(renderActions);
    el('cariPengumuman').addEventListener('input', filterAnnouncements);
    el('filterTanggalPengumuman').addEventListener('change', filterAnnouncements);
    filterAnnouncements();
})();
</script>
@endsection
