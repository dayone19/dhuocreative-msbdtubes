@extends('layouts.Tentor')

@section('title', 'Jadwal')

@section('content')
<div class="t-page tj-page">
 <div class="t-page-header">
    <h1 class="t-page-title">Jadwal Saya</h1>
    <button type="button" class="t-btn t-btn-orange" id="btnAjukan">
        <span class="t-plus">+</span> 
        Ajukan Jadwal
    </button>
 </div>
 <div class="jadwal-mingguan" id="jadwalMingguan"></div>
 <div class="tj-section-head">
    <h2 class="tj-section-title">Daftar Jadwal</h2>

    <div class="tj-toolbar">
        <input type="text" id="cariJadwal" class="tj-input" placeholder="Cari kursus, kelas, atau siswa" aria-label="Cari jadwal">
        <input type="date" id="filterTanggal" class="tj-input" aria-label="Filter tanggal">
        <select id="filterStatus" class="tj-input tj-select" aria-label="Filter status">
            <option value="">Semua status</option>
            <option value="terjadwal" selected>Terjadwal</option>
            <option value="selesai">Selesai</option>
            <option value="dibatalkan">Dibatalkan</option>
        </select>
    </div>
 </div>

 <div class="tj-table-wrap">
    <table class="tj-table" id="tabelJadwal">
        <thead>
            <tr>
                <th>Nama Kursus</th>
                <th>Kelas</th>
                <th>Siswa</th>
                <th>Tanggal</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="jadwalBody">
        </tbody>
    </table>
 </div>

 <div class="tj-section-head tj-section-gap">
    <h2 class="tj-section-title">Pengajuan Jadwal</h2>
</div>

 <div class="tj-table-wrap">
    <table class="tj-table">
        <thead>
            <tr>
                <th>Nama Kursus</th>
                <th>Siswa</th>
                <th>Jadwal Lama</th>
                <th>Jadwal Diajukan</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody id="pengajuanBody">

        </tbody>
    </table>
 </div>
</div>

 <div class="t-modal" id="modalAjukan" hidden>
    <div class="t-modal-box">
        <div class="t-modal-heading">
            <button type="button"
                    class="tmt-back-btn"
                    data-tutup aria-label="Kembali"
                    aria-label="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2>Ajukan Jadwal Baru</h2>
        </div>
        <form id="formAjukan">
            <div class="t-form-group">
                <label for="id_kelas">Kursus</label>
                <select id="id_kelas" required>
                    <option value="Web Master|online|Alya Putri">Web Master · online (Alya Putri)</option>
                    <option value="Web Master|offline rumah|Rafi Pratama">Web Master · offline rumah (Rafi Pratama)</option>
                    <option value="MS Office|offline|Nadia Safitri">MS Office · offline (Nadia Safitri)</option>
                </select>
            </div>

            <div class="t-form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" id="tanggal" required>
            </div>
            <div class="t-form-grid">
                <div class="t-form-group">
                    <label for="jam_mulai">Jam Mulai</label>
                    <input type="time" id="jam_mulai" value="09:00" required>
                </div>
                <div class="t-form-group">
                    <label for="jam_selesai">Jam Selesai</label>
                    <input type="time" id="jam_selesai" value="10:30" required>
                </div>
            </div>
            <div class="t-form-group">
                <label for="catatan">Catatan (opsional)</label>
                <textarea id="catatan" rows="3" placeholder="Contoh: sudah disepakati dengan siswa"></textarea>
            </div>
            <button type="submit" class="t-btn t-btn-orange t-btn-full">Kirim Pengajuan</button>
        </form>
    </div>
</div>

<div id="pesanDemo" role="status" hidden style="position:fixed;right:20px;bottom:20px;z-index:11000;padding:14px 20px;border-radius:18px;background:var(--teal);color:white;box-shadow:0 8px 24px #0002"></div>

<script>
const offsetDate = n => { const d = new Date(); d.setHours(12,0,0,0); d.setDate(d.getDate()+n); return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`; };

let jadwal = [
 {kursus:'Web Master',kelas:'online',siswa:'Alya Putri',tanggal:offsetDate(0),mulai:'09:00',selesai:'10:30',status:'terjadwal'},
 {kursus:'Web Master',kelas:'offline rumah',siswa:'Rafi Pratama',tanggal:offsetDate(1),mulai:'13:00',selesai:'14:30',status:'terjadwal'},
 {kursus:'MS Office',kelas:'offline',siswa:'Nadia Safitri',tanggal:offsetDate(2),mulai:'10:00',selesai:'11:30',status:'terjadwal'},
 {kursus:'Web Master',kelas:'online',siswa:'—',tanggal:offsetDate(-2),mulai:'15:00',selesai:'16:30',status:'selesai'},
 {kursus:'MS Office',kelas:'offline',siswa:'—',tanggal:offsetDate(4),mulai:'08:30',selesai:'10:00',status:'dibatalkan'}
];

let pengajuan = [
 {kursus:'Web Master',siswa:'Alya Putri',tanggal:offsetDate(3),mulai:'09:00',selesai:'10:30',status:'menunggu',ket:'Menunggu verifikasi operator'},
 {kursus:'MS Office',siswa:'Nadia Safitri',tanggal:offsetDate(5),mulai:'13:00',selesai:'14:00',status:'ditolak',ket:'Jadwal bentrok dengan kelas lain'}
];

const el = id => document.getElementById(id);
const safe = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const formatTanggal = s => new Date(s+'T12:00:00').toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'});
const badge = s => `<span class="tj-pill tj-${safe(s)}">${safe(s.charAt(0).toUpperCase()+s.slice(1))}</span>`;

function renderMingguan(){
    const names=['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const today=new Date();
    today.setHours(12,0,0,0);
    const monday=new Date(today);
    monday.setDate(today.getDate()-((today.getDay()+6)%7));
    
    el('jadwalMingguan').innerHTML=names.map((name,i)=>{
        const d=new Date(monday);
        d.setDate(monday.getDate()+i);
        const key=`${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
        const items=jadwal.filter(j=>j.tanggal===key&&j.status==='terjadwal');
        return `<div class="jadwal-hari"><h3>${name} · ${d.getDate()}/${d.getMonth()+1}</h3>${items.length?items.map(j=>`<div class="jadwal-item"><p>${safe(j.kursus)} · ${safe(j.kelas)}</p><p>${safe(j.mulai)}–${safe(j.selesai)}</p></div>`).join(''):'<p>—</p>'}</div>`;
    }).join('');
}

function renderJadwal(){
    const q=el('cariJadwal').value.trim().toLowerCase(),tgl=el('filterTanggal').value,sts=el('filterStatus').value;
    const rows=jadwal.filter(j=>(`${j.kursus} ${j.kelas} ${j.siswa}`.toLowerCase().includes(q))&&(!tgl||j.tanggal===tgl)&&(!sts||j.status===sts));
    
    el('jadwalBody').innerHTML=rows.length?rows.map(j=>{
        const idx=jadwal.indexOf(j);
        const bolehAjukan=j.status==='terjadwal';
        return `<tr>
            <td>${safe(j.kursus)}</td><td>${safe(j.kelas)}</td><td>${safe(j.siswa)}</td>
            <td>${formatTanggal(j.tanggal)}</td><td>${safe(j.mulai)}</td><td>${safe(j.selesai)}</td>
            <td>${badge(j.status)}</td>
            <td>${bolehAjukan?`<button type="button" class="tj-btn-detail tj-btn-ajukan-perubahan" data-ubah-jadwal="${idx}">Ubah Jadwal</button>`:'<span class="tj-ket">—</span>'}</td>
        </tr>`;
    }).join(''):'<tr><td colspan="8" class="tj-empty">Tidak ada jadwal yang cocok.</td></tr>';
}

function renderPengajuan(){
    el('pengajuanBody').innerHTML=pengajuan.length?pengajuan.map(p=>`<tr><td>${safe(p.kursus)}</td><td>${safe(p.siswa)}</td><td>${p.lamaTanggal?`${formatTanggal(p.lamaTanggal)} · ${safe(p.lamaMulai)}–${safe(p.lamaSelesai)}`:'Jadwal baru'}</td><td>${formatTanggal(p.tanggal)} · ${safe(p.mulai)}–${safe(p.selesai)}</td><td>${badge(p.status)}</td><td class="tj-ket">${safe(p.ket)}</td></tr>`).join(''):'<tr><td colspan="6" class="tj-empty">Belum ada pengajuan.</td></tr>';
}

function toast(msg){
    el('pesanDemo').textContent=msg;
    el('pesanDemo').hidden=false;
    setTimeout(()=>el('pesanDemo').hidden=true,2800);
}

function bukaModalAjukan(index=null){
    jadwalDipilihUntukDiubah = index===null ? null : jadwal[index];
    el('formAjukan').reset();
    el('modalAjukan').querySelector('h2').textContent=jadwalDipilihUntukDiubah?'Ajukan Perubahan Jadwal':'Ajukan Jadwal Baru';
    const submit=el('formAjukan').querySelector('[type=submit]');
    submit.textContent=jadwalDipilihUntukDiubah?'Kirim Permintaan Perubahan':'Kirim Pengajuan';
    if(jadwalDipilihUntukDiubah){
        const j=jadwalDipilihUntukDiubah;
        const option=[...el('id_kelas').options].find(o=>o.value.startsWith(j.kursus+'|'+j.kelas+'|'));
        if(option)el('id_kelas').value=option.value;
        el('tanggal').value=j.tanggal; el('jam_mulai').value=j.mulai; el('jam_selesai').value=j.selesai;
        el('catatan').value='Permintaan perubahan dari jadwal '+formatTanggal(j.tanggal)+'.';
    } else { el('tanggal').value=''; el('jam_mulai').value='09:00'; el('jam_selesai').value='10:30'; }
    el('tanggal').min=offsetDate(0); el('modalAjukan').hidden=false;
}
el('btnAjukan').addEventListener('click',()=>bukaModalAjukan());
el('jadwalBody').addEventListener('click',e=>{const b=e.target.closest('[data-ubah-jadwal]');if(b)bukaModalAjukan(Number(b.dataset.ubahJadwal));});
document.querySelectorAll('[data-tutup]').forEach(b=>b.addEventListener('click',()=>b.closest('.t-modal').hidden=true));
document.querySelectorAll('.t-modal').forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.hidden=true}));
['cariJadwal','filterTanggal','filterStatus'].forEach(id=>el(id).addEventListener(id==='cariJadwal'?'input':'change',renderJadwal));

el('tanggal').min=offsetDate(0);
el('formAjukan').addEventListener('submit',e=>{
    e.preventDefault();
    const [kursus,kelas,siswa]=el('id_kelas').value.split('|'),tanggal=el('tanggal').value,mulai=el('jam_mulai').value,selesai=el('jam_selesai').value;
    if(tanggal<offsetDate(0)){toast('Pilih tanggal hari ini atau setelahnya.');return}
    if(selesai<=mulai){toast('Jam selesai harus setelah jam mulai.');return}
    const lama=jadwalDipilihUntukDiubah;
    pengajuan.unshift({kursus,siswa,lamaTanggal:lama?.tanggal||'',lamaMulai:lama?.mulai||'',lamaSelesai:lama?.selesai||'',tanggal,mulai,selesai,status:'menunggu',ket:el('catatan').value.trim()||'Menunggu verifikasi operator'});
    renderPengajuan();
    el('modalAjukan').hidden=true;
    el('formAjukan').reset();
    el('tanggal').min=offsetDate(0);
    jadwalDipilihUntukDiubah=null;
    toast(lama?'Permintaan perubahan jadwal dikirim (demo frontend saja).':'Pengajuan ditambahkan (demo frontend saja).');
});

renderMingguan();
renderJadwal();
renderPengajuan();
</script>
@endsection