@extends('layouts.Admin')

@section('title', 'Pengumuman')

@php
    $user = auth()->user();
    $rolePengguna = strtolower(trim($user->role ?? ''));
    $namaPengguna = $user->nama ?? '';
    $idPengguna = $user->id_user;

    $bolehPosting = in_array($rolePengguna, ['admin', 'operator', 'tentor']);
@endphp

@section('content')
<div class="o-page">
    <div class="o-page-header">
        <div>
            <h1 class="o-page-title">Pengumuman</h1>
        </div>

        @if ($bolehPosting)
            <button type="button" class="o-btn o-btn-orange" id="btnTambahPengumuman">
                <span class="o-plus">+</span> Buat Pengumuman
            </button>
        @endif
    </div>

    <div class="o-announcement-toolbar">
        <div class="o-search-wrap">
            <input
                type="search"
                id="cariPengumuman"
                class="o-search"
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
            style="background-image: none"
            class="o-filter"
            aria-label="Filter tanggal pengumuman"
        >
    </div>

    <div class="o-announcement-list" id="daftarPengumuman">

        @forelse ($daftarPengumuman as $pengumuman)
            <article
                class="o-announcement-card"
                data-id="{{ $pengumuman->id_pengumuman }}"
                data-owner="{{ $pengumuman->id_user }}"
                data-tanggal="{{ $pengumuman->tanggal }}"
            >
                <div class="o-announcement-top">
                    <span class="o-announcement-date">
                        {{ \Carbon\Carbon::parse($pengumuman->tanggal)->translatedFormat('d F Y') }}
                    </span>
                </div>

                <h3>{{ $pengumuman->judul }}</h3>

                <p class="o-announcement-body">
                    {{ $pengumuman->isi }}
                </p>

                <p class="o-announcement-author">
                    Dibuat oleh:
                    {{ $pengumuman->nama_pembuat }}
                    ({{ ucfirst($pengumuman->role_pembuat) }})
                </p>

                @if ($bolehPosting && ($rolePengguna === 'admin' || $pengumuman->id_user == $idPengguna))
                    <div class="o-announcement-actions">
                        <button
                            type="button"
                            class="t-action btn-edit-pengumuman"
                            data-id="{{ $pengumuman->id_pengumuman }}"
                            data-judul="{{ $pengumuman->judul }}"
                            data-isi="{{ $pengumuman->isi }}"
                            data-tanggal="{{ $pengumuman->tanggal }}"
                            data-pembuat="{{ $pengumuman->nama_pembuat }}"
                            data-role-pembuat="{{ $pengumuman->role_pembuat }}"
                        >
                            Edit
                        </button>

                        <form
                            action="{{ route('admin.pengumuman.destroy', $pengumuman->id_pengumuman) }}"
                            method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="t-action">
                                Hapus
                            </button>
                        </form>
                    </div>
                @endif
            </article>
        @empty
            <p class="t-empty">Belum ada pengumuman.</p>
        @endforelse

    </div>

    <p class="t-empty" id="pengumumanKosong" hidden>
        Pengumuman tidak ditemukan.
    </p>
</div>

@if ($bolehPosting)
    <div class="t-modal" id="modalPengumuman" hidden>
        <div class="t-modal-box">

            <div class="t-modal-header">
                <button
                    type="button"
                    class="t-modal-close"
                    data-close
                    aria-label="Tutup"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="35"
                        height="35"
                        fill="currentColor"
                        class="bi bi-caret-left-fill"
                        viewBox="0 0 16 16"
                    >
                        <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                    </svg>
                </button>

                <h2 id="judulModalPengumuman">Buat Pengumuman</h2>
            </div>

            <form
                id="formPengumuman"
                action="{{ route('admin.pengumuman.store') }}"
                method="POST"
            >
                @csrf

                <input type="hidden" name="_method" id="methodPengumuman" value="POST">

                <div class="t-form-group">
                    <label for="judulPengumuman">Judul</label>
                    <input
                        id="judulPengumuman"
                        name="judul"
                        type="text"
                        maxlength="150"
                        required
                        placeholder="Masukkan judul pengumuman"
                    >
                </div>

                <div class="t-form-group">
                    <label for="pembuatPengumuman">Dibuat oleh</label>
                    <input
                        id="pembuatPengumuman"
                        type="text"
                        value="{{ $namaPengguna }} ({{ ucfirst($rolePengguna) }})"
                        readonly
                    >
                </div>

                <div class="t-form-group">
                    <label for="isiPengumuman">Isi Pengumuman</label>
                    <textarea
                        id="isiPengumuman"
                        name="isi"
                        rows="5"
                        required
                        placeholder="Tulis isi pengumuman..."
                    ></textarea>
                </div>

                <div class="t-form-group">
                    <label for="tanggalPengumuman">Tanggal</label>
                    <input
                        id="tanggalPengumuman"
                        name="tanggal"
                        type="date"
                        required
                    >
                </div>

                <button type="submit" class="ak-btn ak-btn-orange ak-btn-full">
                    Simpan Pengumuman
                </button>
            </form>
        </div>
    </div>
@endif

<script>
(() => {
    const el = id => document.getElementById(id);

    const list = el('daftarPengumuman');
    const searchInput = el('cariPengumuman');
    const dateInput = el('filterTanggalPengumuman');
    const emptyMessage = el('pengumumanKosong');

    const modal = el('modalPengumuman');
    const form = el('formPengumuman');

    const currentRole = @json($rolePengguna);
    const currentUserId = @json($idPengguna);

    const canPost = ['admin', 'operator', 'tentor'].includes(currentRole);

    function openModal() {
        if (!modal || !form) return;

        form.reset();

        form.action = @json(route('admin.pengumuman.store'));
        el('methodPengumuman').value = 'POST';
        el('judulModalPengumuman').textContent = 'Buat Pengumuman';

        const today = new Date();
        const offset = today.getTimezoneOffset();
        const localDate = new Date(today.getTime() - offset * 60000)
            .toISOString()
            .slice(0, 10);

        el('tanggalPengumuman').value = localDate;

        modal.hidden = false;
    }

    function editPengumuman(button) {
        if (!modal || !form) return;

        const id = button.dataset.id;

        form.reset();

        form.action = @json(route('admin.pengumuman.update', ['id' => '__ID__']))
            .replace('__ID__', id);

        el('methodPengumuman').value = 'PUT';
        el('judulModalPengumuman').textContent = 'Edit Pengumuman';

        el('judulPengumuman').value = button.dataset.judul;
        el('isiPengumuman').value = button.dataset.isi;
        el('tanggalPengumuman').value = button.dataset.tanggal;

        el('pembuatPengumuman').value =
            button.dataset.pembuat + ' (' + button.dataset.rolePembuat + ')';

        modal.hidden = false;
    }

    function filterAnnouncements() {
        const search = searchInput.value.toLowerCase().trim();
        const date = dateInput.value;

        let visibleCount = 0;

        list.querySelectorAll('.o-announcement-card').forEach(card => {
            const matchesSearch = card.textContent.toLowerCase().includes(search);
            const matchesDate = !date || card.dataset.tanggal === date;

            card.hidden = !(matchesSearch && matchesDate);

            if (!card.hidden) {
                visibleCount++;
            }
        });

        emptyMessage.hidden = visibleCount > 0;
    }

    if (canPost) {
        el('btnTambahPengumuman')?.addEventListener('click', openModal);

        document.querySelectorAll('.btn-edit-pengumuman').forEach(button => {
            button.addEventListener('click', () => editPengumuman(button));
        });

        document.querySelectorAll('[data-close]').forEach(button => {
            button.addEventListener('click', () => {
                modal.hidden = true;
            });
        });

        modal?.addEventListener('click', event => {
            if (event.target === modal) {
                modal.hidden = true;
            }
        });

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape' && modal) {
                modal.hidden = true;
            }
        });
    }

    searchInput.addEventListener('input', filterAnnouncements);
    dateInput.addEventListener('change', filterAnnouncements);

    filterAnnouncements();
})();
</script>
@endsection
