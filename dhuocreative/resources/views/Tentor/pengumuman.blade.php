@extends('layouts.Tentor')
@section('title', 'Pengumuman')
@section('content')
<div class="t-page">
    <div class="t-page-header">
        <div>
            <h1 class="t-page-title">Pengumuman</h1>
        </div>
        <button type="button" class="t-btn t-btn-orange" id="btnTambahPengumuman">
            <span class="t-plus">+</span> Buat Pengumuman
        </button>
    </div>
    <div class="t-announcement-toolbar">
        <div class="t-search-wrap">
            <input type="search" id="cariPengumuman" class="t-search" placeholder="Cari judul, isi, atau pembuat..." aria-label="Cari pengumuman">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="10.8" cy="10.8" r="6.8"></circle><path d="m16 16 5 5"></path>
            </svg>
        </div>
        <input type="date" id="filterTanggalPengumuman" style="background-image: none" class="t-filter" aria-label="Filter tanggal pengumuman">
    </div>

    <div class="t-announcement-list" id="daftarPengumuman">
        <article class="t-announcement-card" data-tanggal="2026-10-18" data-pembuat="Admin">
            <div class="t-announcement-top">
                <span class="t-announcement-date">18 Oktober 2026</span>
            </div>
            <h3>Informasi Kegiatan Belajar</h3>
            <p class="t-announcement-body">Siswa dan tentor diharapkan mengikuti kegiatan belajar sesuai jadwal yang telah ditentukan.</p>
            <p class="t-announcement-author">Dibuat oleh: Admin</p>
            <div class="t-announcement-actions">
                <button type="button" class="t-action" data-delete>Hapus</button>
            </div>
        </article>
    </div>
    <p class="t-empty" id="pengumumanKosong" hidden>Pengumuman tidak ditemukan.</p>
</div>

<div class="t-modal" id="modalPengumuman" hidden>
    <div class="t-modal-box">
        <div class="t-modal-header">
            <button type="button" class="t-modal-close" data-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2 id="judulModalPengumuman">Buat Pengumuman</h2>
        </div>
        <form id="formPengumuman">
            <div class="t-form-group">
                <label for="judulPengumuman">Judul</label>
                <input id="judulPengumuman" type="text" maxlength="150" required placeholder="Masukkan judul pengumuman">
            </div>
            <div class="t-form-group">
                <label for="pembuatPengumuman">Dibuat oleh</label>
                <input id="pembuatPengumuman" type="text" required placeholder="Nama pengguna pembuat pengumuman">
            </div>
            <div class="t-form-group">
                <label for="isiPengumuman">Isi Pengumuman</label>
                <textarea id="isiPengumuman" rows="5" required placeholder="Tulis isi pengumuman..."></textarea>
            </div>
            <div class="t-form-group">
                <label for="tanggalPengumuman">Tanggal</label>
                <input id="tanggalPengumuman" type="date" required>
            </div>
            <button type="submit" class="t-btn t-btn-orange o-btn-full">Simpan Pengumuman</button>
        </form>
    </div>
</div>

<script>
(()=>{const el=id=>document.getElementById(id),modal=el('modalPengumuman'),list=el('daftarPengumuman');let editing=null;
function today(){const d=new Date(),offset=d.getTimezoneOffset();return new Date(d.getTime()-offset*60000).toISOString().slice(0,10)}
function fmt(v){return v?new Date(v+'T12:00:00').toLocaleDateString('id-ID',{day:'numeric',month:'long',year:'numeric'}):'-'}
function open(card=null){editing=card;el('formPengumuman').reset();el('judulModalPengumuman').textContent=card?'Edit Pengumuman':'Buat Pengumuman';if(card){el('judulPengumuman').value=card.querySelector('h2').textContent;el('isiPengumuman').value=card.querySelector('.o-announcement-body').textContent.trim();el('pembuatPengumuman').value=card.dataset.pembuat||'';el('tanggalPengumuman').value=card.dataset.tanggal||today()}else el('tanggalPengumuman').value=today();modal.hidden=false}
function render(card,title,body,author,date){card.dataset.tanggal=date;card.dataset.pembuat=author;card.replaceChildren();const top=document.createElement('div');top.className='o-announcement-top';const label=document.createElement('span');label.className='o-announcement-category';label.textContent='Pengumuman';const dt=document.createElement('span');dt.className='o-announcement-date';dt.textContent=fmt(date);top.append(label,dt);const h=document.createElement('h2');h.textContent=title;const p=document.createElement('p');p.className='o-announcement-body';p.textContent=body;const a=document.createElement('p');a.className='o-announcement-author';a.textContent='Dibuat oleh: '+author;const actions=document.createElement('div');actions.className='o-announcement-actions';const eb=document.createElement('button');eb.type='button';eb.className='o-action';eb.dataset.edit='';eb.textContent='Edit';const db=document.createElement('button');db.type='button';db.className='o-action o-action-delete';db.dataset.delete='';db.textContent='Hapus';actions.append(eb,db);card.append(top,h,p,a,actions)}
el('btnTambahPengumuman').addEventListener('click',()=>open());document.querySelectorAll('[data-close]').forEach(b=>b.addEventListener('click',()=>modal.hidden=true));
list.addEventListener('click',e=>{const edit=e.target.closest('[data-edit]'),del=e.target.closest('[data-delete]');if(edit)open(edit.closest('.o-announcement-card'));if(del&&confirm('Hapus pengumuman ini?')){del.closest('.o-announcement-card').remove();filter()}});
el('formPengumuman').addEventListener('submit',e=>{e.preventDefault();const title=el('judulPengumuman').value.trim(),body=el('isiPengumuman').value.trim(),author=el('pembuatPengumuman').value.trim(),date=el('tanggalPengumuman').value,card=editing||document.createElement('article');card.className='o-announcement-card';render(card,title,body,author,date);if(!editing)list.prepend(card);modal.hidden=true;filter()});
function filter(){const q=el('cariPengumuman').value.toLowerCase().trim(),date=el('filterTanggalPengumuman').value;let n=0;list.querySelectorAll('.o-announcement-card').forEach(c=>{const show=c.textContent.toLowerCase().includes(q)&&(!date||c.dataset.tanggal===date);c.hidden=!show;if(show)n++});el('pengumumanKosong').hidden=n>0}
el('cariPengumuman').addEventListener('input',filter);el('filterTanggalPengumuman').addEventListener('change',filter);modal.addEventListener('click',e=>{if(e.target===modal)modal.hidden=true});document.addEventListener('keydown',e=>{if(e.key==='Escape')modal.hidden=true});filter()})();
</script>
@endsection
