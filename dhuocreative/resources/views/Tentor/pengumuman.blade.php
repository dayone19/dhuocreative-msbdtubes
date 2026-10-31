@extends('layouts.tentor')
@section('title', 'Pengumuman')

@php
    $rolePengguna = auth()->user()->role ?? 'Tentor';
    $namaPengguna = auth()->user()->nama ?? 'Tentor Demo';
    $roleNormal = strtolower(trim($rolePengguna));
    $bolehPosting = in_array($roleNormal, ['admin', 'operator', 'tentor']);
    $penggunaSiswa = $roleNormal === 'siswa';
@endphp

@section('content')
<div class="t-page">
    <div class="t-page-header">
        <div>
            <h1 class="t-page-title">Pengumuman</h1>
            <p class="t-page-subtitle">Pengumuman dapat dilihat oleh seluruh pengguna.</p>
        </div>

        @if ($bolehPosting)
        <button type="button" class="t-btn t-btn-orange" id="btnTambahPengumuman">
            <span class="t-plus">+</span> Buat Pengumuman
        </button>
        @endif
    </div>

    @if (session('sukses'))
        <p style="background:#e3f6e8;color:#1d6b35;padding:10px 14px;border-radius:10px;margin:10px 0;">{{ session('sukses') }}</p>
    @endif
    @if ($errors->any())
        <p style="background:#fde8e8;color:#a12222;padding:10px 14px;border-radius:10px;margin:10px 0;">{{ $errors->first() }}</p>
    @endif

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
        @foreach ($pengumuman as $p)
        <article class="t-announcement-card"
                 data-tanggal="{{ date('Y-m-d', strtotime($p->tanggal)) }}"
                 data-pembuat="{{ $p->nama }}"
                 data-role-pembuat="{{ $p->role }}"
                 data-owner="{{ (int) $p->id_user === (int) auth()->id() ? $roleNormal : 'bukan-pemilik' }}"
                 data-url-update="{{ route('tentor.pengumuman.update', ['pengumuman' => $p->id_pengumuman]) }}"
                 data-url-hapus="{{ route('tentor.pengumuman.hapus', ['pengumuman' => $p->id_pengumuman]) }}">
            <div class="t-announcement-top">
                <span class="t-announcement-date">{{ \Carbon\Carbon::parse($p->tanggal)->locale('id')->translatedFormat('j F Y') }}</span>
            </div>
            <h3>{{ $p->judul }}</h3>
            <p class="t-announcement-body">{{ $p->isi }}</p>
            <p class="t-announcement-author">Dibuat oleh: {{ $p->nama }} ({{ ucfirst($p->role) }})</p>
            <div class="t-announcement-actions"></div>
        </article>
        @endforeach
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

        <form id="formPengumuman" method="POST" action="{{ route('tentor.pengumuman.simpan') }}">
            @csrf
            <input type="hidden" name="_method" value="PUT" id="metodeForm" disabled>
            <div class="t-form-group">
                <label for="judulPengumuman">Judul</label>
                <input id="judulPengumuman" name="judul" type="text" maxlength="150" required
                       placeholder="Masukkan judul pengumuman">
            </div>

            <div class="t-form-group">
                <label for="pembuatPengumuman">Dibuat oleh</label>
                <input id="pembuatPengumuman" type="text" value="{{ $namaPengguna }} ({{ $rolePengguna }})" readonly>
            </div>

            <div class="t-form-group">
                <label for="isiPengumuman">Isi Pengumuman</label>
                <textarea id="isiPengumuman" name="isi" rows="5" required
                          placeholder="Tulis isi pengumuman..."></textarea>
            </div>

            <div class="t-form-group">
                <label for="tanggalPengumuman">Tanggal</label>
                <input id="tanggalPengumuman" name="tanggal" type="date" required>
            </div>

            <button type="submit" class="t-btn t-btn-orange o-btn-full">Simpan Pengumuman</button>
        </form>
    </div>
</div>
@endif

<form id="formHapusPengumuman" method="POST" action="" hidden>
    @csrf
    @method('DELETE')
</form>

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
    const metodeForm = el('metodeForm');

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
        form.action = card ? card.dataset.urlUpdate : "{{ route('tentor.pengumuman.simpan') }}";
        metodeForm.disabled = !card;
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
                    const formHapus = el('formHapusPengumuman');
                    formHapus.action = card.dataset.urlHapus;
                    formHapus.submit();
                }
            }
        });

        form?.addEventListener('submit', event => {
            if (!el('judulPengumuman').value.trim() ||
                !el('isiPengumuman').value.trim() ||
                !el('tanggalPengumuman').value) {
                event.preventDefault();
            }
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

    @if ($errors->any() && old('_method') !== 'PUT')
        openModal();
        el('judulPengumuman').value = {{ \Illuminate\Support\Js::from(old('judul', '')) }};
        el('isiPengumuman').value = {{ \Illuminate\Support\Js::from(old('isi', '')) }};
        el('tanggalPengumuman').value = {{ \Illuminate\Support\Js::from(old('tanggal', '')) }} || today();
    @endif

    list.querySelectorAll('.t-announcement-card').forEach(renderActions);
    el('cariPengumuman').addEventListener('input', filterAnnouncements);
    el('filterTanggalPengumuman').addEventListener('change', filterAnnouncements);
    filterAnnouncements();
})();
</script>
@endsection