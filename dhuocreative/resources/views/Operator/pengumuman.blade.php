{{-- Manajemen Pengumuman Operator - Dhuo Creative --}}
@extends('layouts.Operator')
@section('title', 'Pengumuman')

@php
    $rolePengguna = auth()->user()->role ?? 'Operator';
    $namaPengguna = auth()->user()->nama ?? 'Muhammad Azizih';
    $roleNormal = strtolower(trim($rolePengguna));
    $bolehPosting = in_array($roleNormal, ['admin', 'operator', 'tentor']);
    $daftarPengumuman = $daftarPengumuman ?? [];
@endphp

@section('content')
<div class="o-page">
    @if(session('success'))
        <div style="background: #286c65; color: white; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #df6833; color: white; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
            {{ session('error') }}
        </div>
    @endif

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
            <input type="search" id="cariPengumuman" class="o-search"
                   placeholder="Cari judul, isi, atau pembuat..." aria-label="Cari pengumuman">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="10.8" cy="10.8" r="6.8"></circle><path d="m16 16 5 5"></path>
            </svg>
        </div>
        <input type="date" id="filterTanggalPengumuman" style="background-image: none"
               class="o-filter" aria-label="Filter tanggal pengumuman">
    </div>

    <div class="o-announcement-list" id="daftarPengumuman">
        @forelse ($daftarPengumuman as $p)
            <article class="o-announcement-card"
                     data-id="{{ $p->id_pengumuman }}"
                     data-tanggal="{{ $p->tanggal_iso }}"
                     data-pembuat="{{ $p->nama_pembuat }}"
                     data-role-pembuat="{{ $p->role_pembuat }}"
                     data-owner="{{ strtolower($p->role_pembuat) }}">
                <div class="o-announcement-top">
                    <span class="o-announcement-category">Pengumuman</span>
                    <span class="o-announcement-date">{{ $p->tanggal_teks }}</span>
                </div>
                <h2>{{ $p->judul }}</h2>
                <p class="o-announcement-body">{{ $p->isi }}</p>
                <p class="o-announcement-author">Dibuat oleh: <strong style="color: var(--teal); font-weight: 700;">{{ $p->nama_pembuat }}</strong> ({{ ucfirst($p->role_pembuat) }})</p>
                <div class="o-announcement-actions"></div>
            </article>
        @empty
            <p class="t-empty" style="grid-column: span 2; text-align: center; padding: 25px;">Belum ada pengumuman.</p>
        @endforelse
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

        <form id="formPengumuman" method="POST" action="{{ route('operator.pengumuman.store') }}">
            @csrf
            <input type="hidden" name="_method" id="methodPengumuman" value="POST">

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
                <input id="tanggalPengumuman" name="tanggal" type="date" required value="{{ date('Y-m-d') }}">
            </div>

            <button type="submit" class="o-btn o-btn-orange o-btn-full" id="btnSubmitPengumuman">Simpan Pengumuman</button>
        </form>
    </div>
</div>

<form id="formHapusPengumuman" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="_method" value="DELETE">
</form>
@endif

<script>
(() => {
    const el = id => document.getElementById(id);
    const list = el('daftarPengumuman');
    const currentRole = @json($roleNormal);
    const currentName = @json($namaPengguna);
    const canPost = ['admin', 'operator', 'tentor'].includes(currentRole);
    const modal = el('modalPengumuman');
    const form = el('formPengumuman');
    const methodInput = el('methodPengumuman');
    const storeUrl = @json(route('operator.pengumuman.store'));
    let editing = null;

    function today() {
        const d = new Date();
        const offset = d.getTimezoneOffset();
        return new Date(d.getTime() - offset * 60000).toISOString().slice(0, 10);
    }

    function openModal(card = null) {
        if (!canPost || !modal || !form) return;
        editing = card;
        form.reset();
        el('judulModalPengumuman').textContent = card ? 'Edit Pengumuman' : 'Buat Pengumuman';
        el('btnSubmitPengumuman').textContent = card ? 'Perbarui Pengumuman' : 'Simpan Pengumuman';

        if (card) {
            form.action = storeUrl + '/' + card.dataset.id;
            methodInput.value = 'PUT';
            const titleEl = card.querySelector('h2') || card.querySelector('h3');
            el('judulPengumuman').value = titleEl ? titleEl.textContent : '';
            el('isiPengumuman').value = card.querySelector('.o-announcement-body').textContent.trim();
            el('tanggalPengumuman').value = card.dataset.tanggal || today();
        } else {
            form.action = storeUrl;
            methodInput.value = 'POST';
            el('tanggalPengumuman').value = today();
        }

        modal.hidden = false;
    }

    function renderActions(card) {
        const actions = card.querySelector('.o-announcement-actions');
        if (!actions) return;
        actions.replaceChildren();

        if (!canPost) return;

        const editButton = document.createElement('button');
        editButton.type = 'button';
        editButton.className = 't-action o-action';
        editButton.dataset.edit = '';
        editButton.textContent = 'Edit';

        const deleteButton = document.createElement('button');
        deleteButton.type = 'button';
        deleteButton.className = 't-action t-action-delete o-action o-action-delete';
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
                const card = editButton.closest('.o-announcement-card');
                openModal(card);
            }

            if (deleteButton) {
                const card = deleteButton.closest('.o-announcement-card');
                if (confirm('Hapus pengumuman ini?')) {
                    const deleteForm = el('formHapusPengumuman');
                    if (deleteForm && card.dataset.id) {
                        deleteForm.action = storeUrl + '/' + card.dataset.id;
                        deleteForm.submit();
                    }
                }
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

        list.querySelectorAll('.o-announcement-card').forEach(card => {
            const matchesSearch = card.textContent.toLowerCase().includes(search);
            const matchesDate = !date || card.dataset.tanggal === date;
            card.hidden = !(matchesSearch && matchesDate);
            if (!card.hidden) visibleCount++;
        });

        el('pengumumanKosong').hidden = visibleCount > 0;
    }

    list.querySelectorAll('.o-announcement-card').forEach(renderActions);
    el('cariPengumuman').addEventListener('input', filterAnnouncements);
    el('filterTanggalPengumuman').addEventListener('change', filterAnnouncements);
    filterAnnouncements();
})();
</script>
@endsection
