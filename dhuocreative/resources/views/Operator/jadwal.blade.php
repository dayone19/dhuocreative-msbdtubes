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
        <button type="button" class="o-btn o-btn-orange" id="btnTambahJadwal">
            <span class="o-plus">+</span>
            Tambah Jadwal</button>
    </div>

    <!-- Ringkasan 1 minggu (contoh: minggu 26–31 Okt 2026) -->
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

    <!-- 1 tentor = 1 siswa + 1 kursus, kelasnya tetap sama -->
    <div class="j-table-wrap">
        <table class="j-table" id="tabelJadwal">
            <thead>
                <tr>
                    <th>Nama Kursus</th>
                    <th>Kelas</th>
                    <th>Tentor</th>
                    <th>Siswa</th>
                    <th>Tanggal</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
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

    <!-- Pengajuan tentor: berlaku hanya untuk 1 pertemuan.
         Disetujui -> jadwal lama jadi "Dibatalkan" + baris baru "Terjadwal". Minggu berikutnya tetap normal. -->
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

<!-- Modal tambah / edit jadwal -->
<div class="o-modal" id="modalJadwal" hidden>
    <div class="o-modal-box o-modal-wide">
        <div class="o-modal-header">
            <button type="button" class="o-modal-close" data-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2>Tambah Jadwal</h2>
        </div>

        <form id="formJadwal">
            <div class="o-form-grid">
                <div class="o-form-group">
                    <label for="jadwalSiswa">Nama Siswa</label>
                    <select id="jadwalSiswa" required>
                        <option value="">Pilih siswa</option>
                        <option>Siswa A</option>
                        <option>Siswa B</option>
                    </select>
                </div>
                <div class="o-form-group">
                    <label for="jadwalKursus">Kursus - Kelas</label>
                    <!-- isinya hanya kursus yang didaftarkan siswa tsb; kelas ikut pendaftaran -->
                    <select id="jadwalKursus" required>
                        <option value="">Pilih kursus</option>
                        <option>Web Master - Online</option>
                        <option>MS Office - Offline</option>
                    </select>
                </div>
                <div class="o-form-group o-form-full">
                    <label for="jadwalTentor">Nama Tentor</label>
                    <input id="jadwalTentor" maxlength="100" required placeholder="Masukkan nama tentor">
                </div>
                <div class="o-form-group">
                    <label for="jadwalTanggal">Tanggal</label>
                    <input id="jadwalTanggal" type="date" required>
                </div>
                <div class="o-form-group">
                    <label for="jadwalStatus">Status</label>
                    <select id="jadwalStatus" required>
                        <option value="terjadwal">Terjadwal</option>
                        <option value="selesai">Selesai</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>
                <div class="o-form-group">
                    <label for="jadwalMulai">Jam Mulai</label>
                    <input id="jadwalMulai" type="time" required>
                </div>
                <div class="o-form-group">
                    <label for="jadwalSelesai">Jam Selesai</label>
                    <input id="jadwalSelesai" type="time" required>
                </div>
            </div>
            <button class="o-btn o-btn-orange o-btn-submit" type="submit">Simpan Jadwal</button>
        </form>
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
        <button type="button" class="o-btn o-btn-orange o-btn-full" id="editDetailJadwal">Edit Jadwal</button>
    </div>
</div>

<script>
(()=>{
const $=s=>document.querySelector(s),all=s=>[...document.querySelectorAll(s)];
const modal=$('#modalJadwal'),detail=$('#modalDetailJadwal'),cap=s=>s[0].toUpperCase()+s.slice(1);
let selected=null;

// badge status (enum: terjadwal | selesai | dibatalkan)
function setStatus(tr,st){
  tr.dataset.status=st;
  const b=tr.cells[7].querySelector('span');b.className='o-status '+st;b.textContent=cap(st);
}

// buka / tutup modal
$('#btnTambahJadwal').onclick=()=>{$('#formJadwal').reset();selected=null;modal.hidden=false};
all('[data-close]').forEach(b=>b.onclick=()=>b.closest('.o-modal').hidden=true);
$('#formJadwal').onsubmit=e=>{e.preventDefault();modal.hidden=true};

// detail + edit
$('#tabelJadwal').onclick=e=>{
  const b=e.target.closest('[data-detail]');if(!b)return;
  selected=b.closest('tr');const c=selected.cells;
  $('#detailSiswa').textContent=c[3].textContent;
  $('#detailKursus').textContent=c[0].textContent+' - '+c[1].textContent;
  $('#detailTentor').textContent=c[2].textContent;
  $('#detailTanggal').textContent=c[4].textContent;
  $('#detailJam').textContent=c[5].textContent+'–'+c[6].textContent;
  $('#detailStatus').textContent=cap(selected.dataset.status);
  detail.hidden=false;
};
$('#editDetailJadwal').onclick=()=>{
  const c=selected.cells;
  $('#jadwalSiswa').value=c[3].textContent;
  $('#jadwalKursus').value=c[0].textContent+' - '+c[1].textContent;
  $('#jadwalTentor').value=c[2].textContent;
  $('#jadwalTanggal').value=c[4].textContent;
  $('#jadwalMulai').value=c[5].textContent;
  $('#jadwalSelesai').value=c[6].textContent;
  $('#jadwalStatus').value=selected.dataset.status;
  detail.hidden=true;modal.hidden=false;
};

// persetujuan: setuju -> jadwal lama "dibatalkan" + baris baru "terjadwal" (hanya untuk pertemuan itu)
$('#persetujuanBody').onclick=e=>{
  const b=e.target.closest('[data-approval]');if(!b)return;
  const row=b.closest('tr'),setuju=b.dataset.approval==='setuju';
  const badge=row.cells[7].querySelector('span');
  badge.className='o-status '+(setuju?'aktif':'nonaktif');badge.textContent=setuju?'Disetujui':'Ditolak';
  row.cells[8].textContent=setuju?'Jadwal disetujui':'Pengajuan ditolak';
  if(!setuju)return;
  const lama=$('#tabelJadwal tr[data-id="'+row.dataset.jadwal+'"]'),baru=lama.cloneNode(true);
  setStatus(lama,'dibatalkan');
  baru.removeAttribute('data-id');baru.dataset.tanggal=row.dataset.tanggal;
  baru.cells[4].textContent=row.dataset.tanggal;
  baru.cells[5].textContent=row.dataset.mulai;baru.cells[6].textContent=row.dataset.selesai;
  setStatus(baru,'terjadwal');lama.after(baru);
};

// cari + filter bulan + filter status
function filter(){
  const q=$('#cariJadwal').value.toLowerCase(),m=$('#filterTanggal').value,st=$('#filterStatus').value;let n=0;
  all('#tabelJadwal tbody tr').forEach(r=>{
    const ok=r.textContent.toLowerCase().includes(q)&&(!m||r.dataset.tanggal.startsWith(m))&&(!st||r.dataset.status===st);
    r.hidden=!ok;if(ok)n++;
  });
  $('#jadwalKosong').hidden=n>0;
}
['#cariJadwal','#filterTanggal','#filterStatus'].forEach(s=>$(s).addEventListener(s==='#cariJadwal'?'input':'change',filter));
})();
</script>
@endsection