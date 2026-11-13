@extends('layouts.Tentor')

@section('title', 'Jadwal')

@section('content')

<div class="t-page tj-page">
 <div class="t-page-header">
    <h1 class="t-page-title">Jadwal Saya</h1>
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

 <nav class="tj-pagination" id="paginasiJadwal" aria-label="Halaman daftar jadwal" hidden></nav>

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
 <nav class="tj-pagination" id="paginasiPengajuan" aria-label="Halaman pengajuan jadwal" hidden></nav>
</div>

<!-- Modal Ajukan Jadwal Baru sudah dihapus, namun modal ini tetap dipakai untuk Ubah Jadwal -->
<div class="t-modal" id="modalAjukan" hidden>
    <div class="t-modal-box">
        <div class="t-modal-heading">
            <button type="button"
                    class="tmt-back-btn"
                    data-tutup aria-label="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2>Ubah Jadwal</h2>
        </div>
        <form id="formAjukan">
            @csrf
            <div class="t-form-group">
                <label for="id_kelas">Kursus</label>
                <select id="id_kelas" disabled></select>
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
            <button type="submit" class="t-btn t-btn-orange t-btn-full">Kirim Permintaan Perubahan</button>
        </form>
    </div>
</div>

<div id="pesanDemo" role="status" hidden style="position:fixed;right:20px;bottom:20px;z-index:11000;padding:14px 20px;border-radius:18px;background:var(--teal);color:white;box-shadow:0 8px 24px #0002"></div>

<script>
const offsetDate = n => { const d = new Date(); d.setHours(12,0,0,0); d.setDate(d.getDate()+n); return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`; };

// data dari database (JadwalController@index)
let jadwal    = @json($jadwal);
let pengajuan = @json($pengajuan);
let jadwalDipilihUntukDiubah = null;

const PER_HALAMAN = 5;
let halJadwal = 1, halPengajuan = 1;

const el = id => document.getElementById(id);
const safe = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const formatTanggal = s => new Date(s+'T12:00:00').toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'});
const badge = s => `<span class="tj-pill tj-${safe(s)}">${safe(s.charAt(0).toUpperCase()+s.slice(1))}</span>`;

function renderPaginasi(navId,total,halaman,pindah){
    const nav=el(navId);
    nav.replaceChildren();
    if(!total){nav.hidden=true;return}
    nav.hidden=false;
    const jumlah=Math.ceil(total/PER_HALAMAN);
    const awal=(halaman-1)*PER_HALAMAN+1, akhir=Math.min(halaman*PER_HALAMAN,total);

    const info=document.createElement('span');
    info.className='tj-pg-info';
    info.textContent=`Menampilkan ${awal}–${akhir} dari ${total}`;
    nav.appendChild(info);
    if(jumlah<=1)return;

    const btn=(label,tujuan,{aktif=false,mati=false,aria}={})=>{
        const b=document.createElement('button');
        b.type='button';
        b.className='tj-pg-btn'+(aktif?' active':'');
        b.textContent=label;
        b.disabled=mati;
        if(aria)b.setAttribute('aria-label',aria);
        if(aktif)b.setAttribute('aria-current','page');
        b.addEventListener('click',()=>pindah(tujuan));
        return b;
    };

    const grup=document.createElement('div');
    grup.className='tj-pg-list';
    grup.appendChild(btn('‹',halaman-1,{mati:halaman===1,aria:'Halaman sebelumnya'}));

    let terakhir=0;
    for(let i=1;i<=jumlah;i++){
        if(i===1||i===jumlah||Math.abs(i-halaman)<=1){
            if(i-terakhir>1){
                const t=document.createElement('span');
                t.className='tj-pg-dots';
                t.textContent='…';
                grup.appendChild(t);
            }
            grup.appendChild(btn(String(i),i,{aktif:i===halaman,aria:`Halaman ${i}`}));
            terakhir=i;
        }
    }

    grup.appendChild(btn('›',halaman+1,{mati:halaman===jumlah,aria:'Halaman berikutnya'}));
    nav.appendChild(grup);
}

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
    
    halJadwal=Math.min(halJadwal,Math.max(1,Math.ceil(rows.length/PER_HALAMAN)));
    const tampil=rows.slice((halJadwal-1)*PER_HALAMAN,halJadwal*PER_HALAMAN);

    el('jadwalBody').innerHTML=rows.length?tampil.map(j=>{
        const idx=jadwal.indexOf(j);
        const bolehAjukan=j.status==='terjadwal';
        return `<tr>
            <td>${safe(j.kursus)}</td><td>${safe(j.kelas)}</td><td>${safe(j.siswa)}</td>
            <td>${formatTanggal(j.tanggal)}</td><td>${safe(j.mulai)}</td><td>${safe(j.selesai)}</td>
            <td>${badge(j.status)}</td>
            <td>${bolehAjukan?`<button type="button" class="tj-btn-detail tj-btn-ajukan-perubahan" data-ubah-jadwal="${idx}">Ubah Jadwal</button>`:'<span class="tj-ket">—</span>'}</td>
        </tr>`;
    }).join(''):'<tr><td colspan="8" class="tj-empty">Tidak ada jadwal yang cocok.</td></tr>';

    renderPaginasi('paginasiJadwal',rows.length,halJadwal,h=>{halJadwal=h;renderJadwal();});
}

function renderPengajuan(){
    halPengajuan=Math.min(halPengajuan,Math.max(1,Math.ceil(pengajuan.length/PER_HALAMAN)));
    const tampil=pengajuan.slice((halPengajuan-1)*PER_HALAMAN,halPengajuan*PER_HALAMAN);

    el('pengajuanBody').innerHTML=pengajuan.length?tampil.map(p=>`<tr><td>${safe(p.kursus)}</td><td>${safe(p.siswa)}</td><td>${p.lamaTanggal?`${formatTanggal(p.lamaTanggal)} · ${safe(p.lamaMulai)}–${safe(p.lamaSelesai)}`:'Jadwal baru'}</td><td>${formatTanggal(p.tanggal)} · ${safe(p.mulai)}–${safe(p.selesai)}</td><td>${badge(p.status)}</td><td class="tj-ket">${safe(p.ket)}</td></tr>`).join(''):'<tr><td colspan="6" class="tj-empty">Belum ada pengajuan.</td></tr>';

    renderPaginasi('paginasiPengajuan',pengajuan.length,halPengajuan,h=>{halPengajuan=h;renderPengajuan();});
}

function toast(msg){
    el('pesanDemo').textContent=msg;
    el('pesanDemo').hidden=false;
    setTimeout(()=>el('pesanDemo').hidden=true,2800);
}

function bukaModalUbah(index){
    jadwalDipilihUntukDiubah = jadwal[index];
    el('formAjukan').reset();
    el('modalAjukan').querySelector('h2').textContent='Ajukan Perubahan Jadwal';
    const submit=el('formAjukan').querySelector('[type=submit]');
    submit.textContent='Kirim Permintaan Perubahan';
    
    const j=jadwalDipilihUntukDiubah;
    el('id_kelas').replaceChildren(new Option(`${j.kursus} · ${j.kelas} (${j.siswa})`,j.id_kelas,true,true));
    el('tanggal').value=j.tanggal; 
    el('jam_mulai').value=j.mulai; 
    el('jam_selesai').value=j.selesai;
    el('catatan').value='Permintaan perubahan dari jadwal '+formatTanggal(j.tanggal)+'.';
    
    el('tanggal').min=offsetDate(0); 
    el('modalAjukan').hidden=false;
}

// Event listener tombol "Ajukan Jadwal" utama sudah dihapus di sini
el('jadwalBody').addEventListener('click',e=>{const b=e.target.closest('[data-ubah-jadwal]');if(b)bukaModalUbah(Number(b.dataset.ubahJadwal));});
document.querySelectorAll('[data-tutup]').forEach(b=>b.addEventListener('click',()=>b.closest('.t-modal').hidden=true));
document.querySelectorAll('.t-modal').forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.hidden=true}));
['cariJadwal','filterTanggal','filterStatus'].forEach(id=>el(id).addEventListener(id==='cariJadwal'?'input':'change',()=>{halJadwal=1;renderJadwal();}));

el('tanggal').min=offsetDate(0);
el('formAjukan').addEventListener('submit',async e=>{
    e.preventDefault();
    const lama=jadwalDipilihUntukDiubah;
    if(!lama){toast('Pilih jadwal yang ingin diubah dari tabel.');return}
    const payload={
        id_kelas:Number(lama.id_kelas),
        id_jadwal_lama:lama.id_jadwal,
        tanggal:el('tanggal').value,
        jam_mulai:el('jam_mulai').value,
        jam_selesai:el('jam_selesai').value,
        catatan:el('catatan').value.trim()||null
    };
    if(payload.tanggal<offsetDate(0)){toast('Pilih tanggal hari ini atau setelahnya.');return}
    if(payload.jam_selesai<=payload.jam_mulai){toast('Jam selesai harus setelah jam mulai.');return}

    const tombol=el('formAjukan').querySelector('[type=submit]');
    tombol.disabled=true;
    try{
        const res=await fetch("{{ route('tentor.jadwal.ajukan') }}",{
            method:'POST',
            headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':el('formAjukan').querySelector('[name=_token]').value},
            body:JSON.stringify(payload)
        });
        const body=await res.json().catch(()=>({}));
        if(!res.ok){
            const pesanPertama=body.errors?Object.values(body.errors)[0][0]:null;
            toast(pesanPertama||body.message||'Gagal mengirim permintaan.');
            return;
        }
        toast(body.message);
        setTimeout(()=>location.reload(),900); // muat ulang supaya tabel pengajuan ikut terbaru
    }catch(err){
        toast('Tidak bisa terhubung ke server.');
    }finally{
        tombol.disabled=false;
    }
});

renderMingguan();
renderJadwal();
renderPengajuan();
</script>
@endsection