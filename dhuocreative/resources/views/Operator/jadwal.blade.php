@extends('layouts.Operator')

@section('title', 'Manajemen Jadwal')

@section('content')
<style>
.o-approval-actions { display:flex; justify-content:center; gap:7px; flex-wrap:wrap; }
.o-approval-actions .o-action { padding:9px 12px; border:0; border-radius:18px; cursor:pointer; font:inherit; font-size:13px; font-weight:700; color:#fff; background:#286c65; }
.o-approval-actions .o-reject { background:#df6833; }
.o-approval-actions .o-action:disabled { opacity:.6; cursor:not-allowed; }
#tabelPersetujuan { min-width:1150px; }
.o-approval-heading h2 { color:#286c65; }
</style>
<div class="o-page">
    <div class="o-page-header">
        <h1 class="o-page-title">Manajemen Jadwal</h1>
    </div>

    <!-- Ringkasan 1 minggu -->
    <div class="o-schedule-panel">
        <div class="o-week-grid">
            <div class="o-week-day">
                <h3>Senin</h3>
                <p>Web Master - Online</p>
                <span>09:00–10:30 · Siswa A</span>
            </div>
            <div class="o-week-day">
                <h3>Selasa</h3>
                <p>MS Office - Offline</p>
                <span>10:00–11:00 · Siswa A</span>
                <p>MS Office - Offline</p>
                <span>10:00–11:30 · Siswa B</span>
            </div>
            <div class="o-week-day">
                <h3>Rabu</h3>
                <p>Web Master - Online</p>
                <span>09:00–10:30 · Siswa A</span>
            </div>
            <div class="o-week-day">
                <h3>Kamis</h3>
                <p>Belum ada jadwal</p>
                <span>—</span>
            </div>
            <div class="o-week-day">
                <h3>Jumat</h3>
                <p>Web Master - Online</p>
                <span>09:00–10:30 · Siswa A</span>
            </div>
            <div class="o-week-day">
                <h3>Sabtu</h3>
                <p>Belum ada jadwal</p>
                <span>—</span>
            </div>
        </div>
    </div>

    <div class="o-toolbar">
        <h2>Daftar Jadwal</h2>
        <div class="o-schedule-filters">
            <input class="o-search" id="cariJadwal" type="search" placeholder="Cari kursus, kelas, tentor, atau siswa..." aria-label="Cari jadwal">
            <input class="o-filter" style="background-image:none" id="filterTanggal" type="month" aria-label="Filter bulan">
            <select class="o-filter" id="filterStatus" aria-label="Filter status">
                <option value="">Semua Status</option>
                <option value="terjadwal">Terjadwal</option>
                <option value="selesai">Selesai</option>
                <option value="dibatalkan">Dibatalkan</option>
            </select>
        </div>
    </div>

    <div class="j-table-wrap">
        <table class="j-table" id="tabelJadwal">
            <thead>
                <tr>
                    <th>Nama Kursus</th>
                    <th>Jenis Kelas</th>
                    <th>Tentor</th>
                    <th>Siswa</th>
                    <th>Tanggal</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="bodyJadwal">
                <tr data-id="j1" data-tanggal="2026-10-26" data-status="terjadwal">
                    <td>Web Master</td><td>Online</td><td>Tentor C</td><td>Siswa A</td>
                    <td>2026-10-26</td><td>09:00</td><td>10:30</td>
                    <td><span class="o-status terjadwal">Terjadwal</span></td>
                    <td><button type="button" class="o-action" data-detail>Detail</button></td>
                </tr>
                <tr data-id="j2" data-tanggal="2026-10-28" data-status="terjadwal">
                    <td>Web Master</td><td>Online</td><td>Tentor C</td><td>Siswa A</td>
                    <td>2026-10-28</td><td>09:00</td><td>10:30</td>
                    <td><span class="o-status terjadwal">Terjadwal</span></td>
                    <td><button type="button" class="o-action" data-detail>Detail</button></td>
                </tr>
                <tr data-id="j3" data-tanggal="2026-10-30" data-status="terjadwal">
                    <td>Web Master</td><td>Online</td><td>Tentor C</td><td>Siswa A</td>
                    <td>2026-10-30</td><td>09:00</td><td>10:30</td>
                    <td><span class="o-status terjadwal">Terjadwal</span></td>
                    <td><button type="button" class="o-action" data-detail>Detail</button></td>
                </tr>
                <tr data-id="j4" data-tanggal="2026-10-27" data-status="terjadwal">
                    <td>MS Office</td><td>Offline</td><td>Tentor E</td><td>Siswa A</td>
                    <td>2026-10-27</td><td>10:00</td><td>11:00</td>
                    <td><span class="o-status terjadwal">Terjadwal</span></td>
                    <td><button type="button" class="o-action" data-detail>Detail</button></td>
                </tr>
                <tr data-id="j5" data-tanggal="2026-10-27" data-status="terjadwal">
                    <td>MS Office</td><td>Offline</td><td>Tentor D</td><td>Siswa B</td>
                    <td>2026-10-27</td><td>10:00</td><td>11:30</td>
                    <td><span class="o-status terjadwal">Terjadwal</span></td>
                    <td><button type="button" class="o-action" data-detail>Detail</button></td>
                </tr>
                <tr data-id="j6" data-tanggal="2026-10-20" data-status="selesai">
                    <td>MS Office</td><td>Offline</td><td>Tentor D</td><td>Siswa B</td>
                    <td>2026-10-20</td><td>10:00</td><td>11:30</td>
                    <td><span class="o-status selesai">Selesai</span></td>
                    <td><button type="button" class="o-action" data-detail>Detail</button></td>
                </tr>
            </tbody>
        </table>
    </div>
    <p class="o-empty" id="jadwalKosong" hidden>Jadwal tidak ditemukan.</p>

    <!-- Komponen Paginasi Tabel Jadwal -->
    <nav class="tj-pagination" id="paginasiJadwal" aria-label="Halaman daftar jadwal" style="margin-top: 20px;" hidden></nav>

    <!-- Pengajuan tentor -->
    <div class="o-toolbar o-approval-heading" style="margin-top:42px">
        <h2>Persetujuan Pergantian Jadwal dari Tentor</h2>
    </div>
    <div class="j-table-wrap">
        <table class="j-table" id="tabelPersetujuan">
            <thead><tr>
                <th>Nama Kursus</th>
                <th>Kelas</th>
                <th>Tentor</th>
                <th>Siswa</th>
                <th>Jadwal Lama</th>
                <th>Jadwal Diajukan</th>
                <th>Alasan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr></thead>
            <tbody id="persetujuanBody">
                <tr data-status="menunggu" data-jadwal="j3" data-tanggal="2026-10-31" data-mulai="13:00" data-selesai="14:30">
                    <td>Web Master</td>
                    <td>Online</td>
                    <td>Tentor C</td>
                    <td>Siswa A</td>
                    <td>30 Okt 2026 (Jumat) · 09:00–10:30</td>
                    <td>31 Okt 2026 (Sabtu) · 13:00–14:30</td>
                    <td>Berhalangan hadir pada hari Jumat</td>
                    <td><span class="o-status menunggu">Menunggu</span></td>
                    <td><div class="o-approval-actions"><button type="button" class="o-action o-approve" data-approval="setuju">Setujui</button><button type="button" class="o-action o-reject" data-approval="tolak">Tolak</button></div></td>
                </tr>
                <tr data-status="menunggu" data-jadwal="j5" data-tanggal="2026-10-28" data-mulai="14:00" data-selesai="15:30">
                    <td>MS Office</td>
                    <td>Offline</td>
                    <td>Tentor D</td>
                    <td>Siswa B</td>
                    <td>27 Okt 2026 (Selasa) · 10:00–11:30</td>
                    <td>28 Okt 2026 (Rabu) · 14:00–15:30</td>
                    <td>Ada keperluan mendadak</td>
                    <td><span class="o-status menunggu">Menunggu</span></td>
                    <td><div class="o-approval-actions"><button type="button" class="o-action o-approve" data-approval="setuju">Setujui</button><button type="button" class="o-action o-reject" data-approval="tolak">Tolak</button></div></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal detail jadwal -->
<div class="o-modal" id="modalDetailJadwal" hidden>
    <div class="o-modal-box">
        <div class="o-modal-header">
            <button type="button" class="o-modal-close" data-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2>Detail Jadwal</h2>
        </div>
        <div class="o-detail-list">
            <div><span>Nama Siswa</span><strong id="detailSiswa">-</strong></div>
            <div><span>Kursus - Kelas</span><strong id="detailKursus">-</strong></div>
            <div><span>Nama Tentor</span><strong id="detailTentor">-</strong></div>
            <div><span>Tanggal</span><strong id="detailTanggal">-</strong></div>
            <div><span>Jam</span><strong id="detailJam">-</strong></div>
            <div><span>Status</span><strong id="detailStatus">-</strong></div>
        </div>
    </div>
</div>

<script>
const PER_HALAMAN = 10; 
let halJadwal = 1;

function renderPaginasi(navId, total, halaman, pindah) {
    const nav = document.getElementById(navId);
    if (!nav) return;
    
    nav.replaceChildren();
    if (!total || total <= 1) {
        nav.hidden = true;
        return;
    }
    nav.hidden = false;
    
    const jumlah = Math.ceil(total / PER_HALAMAN);
    const awal = (halaman - 1) * PER_HALAMAN + 1;
    const akhir = Math.min(halaman * PER_HALAMAN, total);

    const info = document.createElement('span');
    info.className = 'tj-pg-info';
    info.textContent = `Menampilkan ${awal}–${akhir} dari ${total}`;
    nav.appendChild(info);

    const btn = (label, tujuan, { aktif = false, mati = false, aria } = {}) => {
        const b = document.createElement('button');
        b.type = 'button';
        b.className = 'tj-pg-btn' + (aktif ? ' active' : '');
        b.textContent = label;
        b.disabled = mati;
        if (aria) b.setAttribute('aria-label', aria);
        if (aktif) b.setAttribute('aria-current', 'page');
        b.addEventListener('click', () => pindah(tujuan));
        return b;
    };

    const grup = document.createElement('div');
    grup.className = 'tj-pg-list';
    grup.appendChild(btn('‹', halaman - 1, { mati: halaman === 1, aria: 'Halaman sebelumnya' }));

    let terakhir = 0;
    for (let i = 1; i <= jumlah; i++) {
        if (i === 1 || i === jumlah || Math.abs(i - halaman) <= 1) {
            if (i - terakhir > 1) {
                const t = document.createElement('span');
                t.className = 'tj-pg-dots';
                t.textContent = '…';
                grup.appendChild(t);
            }
            grup.appendChild(btn(String(i), i, { aktif: i === halaman, aria: `Halaman ${i}` }));
            terakhir = i;
        }
    }

    grup.appendChild(btn('›', halaman + 1, { mati: halaman === jumlah, aria: 'Halaman berikutnya' }));
    nav.appendChild(grup);
}

(()=>{
const $=s=>document.querySelector(s),all=s=>[...document.querySelectorAll(s)];
const detail=$('#modalDetailJadwal'),cap=s=>s[0].toUpperCase()+s.slice(1);
const bodyJadwal=$('#bodyJadwal');

function setStatus(tr,st){
  tr.dataset.status=st;
  const b=tr.cells[7].querySelector('span');b.className='o-status '+st;b.textContent=cap(st);
}

// Tutup modal
all('[data-close]').forEach(b=>b.onclick=()=>b.closest('.o-modal').hidden=true);

// detail jadwal
$('#tabelJadwal').onclick=e=>{
  const b=e.target.closest('[data-detail]');if(!b)return;
  const tr=b.closest('tr');const c=tr.cells;
  $('#detailSiswa').textContent=c[3].textContent;
  $('#detailKursus').textContent=c[0].textContent+' - '+c[1].textContent;
  $('#detailTentor').textContent=c[2].textContent;
  $('#detailTanggal').textContent=c[4].textContent;
  $('#detailJam').textContent=c[5].textContent+'–'+c[6].textContent;
  $('#detailStatus').textContent=cap(tr.dataset.status);
  detail.hidden=false;
};

// persetujuan: setuju -> jadwal lama "dibatalkan" + baris baru "terjadwal"
$('#persetujuanBody').onclick=e=>{
  const b=e.target.closest('[data-approval]');if(!b)return;
  const row=b.closest('tr'),setuju=b.dataset.approval==='setuju';
  const badge=row.cells[7].querySelector('span');
  badge.className='o-status '+(setuju?'aktif':'nonaktif');badge.textContent=setuju?'Disetujui':'Ditolak';
  row.cells[8].textContent=setuju?'Jadwal disetujui':'Pengajuan ditolak';
  if(!setuju)return;
  const lama=$('#tabelJadwal tr[data-id="'+row.dataset.jadwal+'"]');
  if (lama) {
      const baru=lama.cloneNode(true);
      setStatus(lama,'dibatalkan');
      baru.removeAttribute('data-id');baru.dataset.tanggal=row.dataset.tanggal;
      baru.cells[4].textContent=row.dataset.tanggal;
      baru.cells[5].textContent=row.dataset.mulai;baru.cells[6].textContent=row.dataset.selesai;
      setStatus(baru,'terjadwal');lama.after(baru);
      filter(); // Refresh filter/paginasi setelah tambah baris baru
  }
};

// Fungsi Filter & Paginasi Jadwal
function filter(){
  const q=$('#cariJadwal').value.toLowerCase(),m=$('#filterTanggal').value,st=$('#filterStatus').value;
  const allRows=[...bodyJadwal.querySelectorAll('tr')];

  const filteredRows = allRows.filter(r=>{
    const ok=r.textContent.toLowerCase().includes(q)&&(!m||r.dataset.tanggal.startsWith(m))&&(!st||r.dataset.status===st);
    return ok;
  });

  allRows.forEach(r => r.style.display = 'none');

  const totalFiltered = filteredRows.length;
  $('#jadwalKosong').hidden = totalFiltered > 0;

  if (totalFiltered === 0) {
      renderPaginasi('paginasiJadwal', 0, halJadwal, pindahHalaman);
      return;
  }

  const maxPage = Math.ceil(totalFiltered / PER_HALAMAN);
  if (halJadwal > maxPage) halJadwal = maxPage || 1;

  const start = (halJadwal - 1) * PER_HALAMAN;
  const end = start + PER_HALAMAN;

  filteredRows.slice(start, end).forEach(r => {
      r.style.display = '';
  });

  renderPaginasi('paginasiJadwal', totalFiltered, halJadwal, pindahHalaman);
}

function pindahHalaman(tujuanBaru) {
    halJadwal = tujuanBaru;
    filter();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

['#cariJadwal','#filterTanggal','#filterStatus'].forEach(s=>$(s).addEventListener(s==='#cariJadwal'?'input':'change',()=>{
    halJadwal = 1; // Reset ke halaman 1 saat pencarian/filter diubah
    filter();
}));

// Inisialisasi awal
filter();
})();
</script>
@endsection