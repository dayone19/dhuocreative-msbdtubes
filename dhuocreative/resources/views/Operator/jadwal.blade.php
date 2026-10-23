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
        <div class="o-schedule-panel">
            <div class="o-week-grid" id="ringkasanMingguan">
                <div class="o-week-day">
                    <h3>Senin</h3>
                    <p>Web Master · online</p>
                    <span>09:00–10:30</span>
                </div>
                <div class="o-week-day">
                    <h3>Selasa</h3>
                    <p>Web Master · offline</p>
                    <span>13:00–14:30</span>
                </div>
                <div class="o-week-day">
                    <h3>Rabu</h3>
                    <p>MS Office · online</p>
                    <span>09:00–10:00</span>
                </div>
                <div class="o-week-day">
                    <h3>Kamis</h3>
                    <p>Belum ada jadwal</p>
                    <span>—</span>
                </div>
                <div class="o-week-day">
                    <h3>Jumat</h3>
                    <p>Web Master · offline</p>
                    <span>13:00–14:30</span>
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
                <input class="o-search" id="cariJadwal" type="search" placeholder="Cari kursus, kelas, atau tentor..." aria-label="Cari jadwal">
                <input class="o-filter" style="background-image:none" id="filterTanggal" type="month" aria-label="Filter bulan">
                <select class="o-filter" id="filterStatus" aria-label="Filter status">
                    <option value="">Semua Status</option>
                    <option value="terjadwal">Terjadwal</option>
                    <option value="selesai">Selesai</option>
                    <option value="dibatalkan">Dibatalkan</option>
                </select>
            </div>
        </div>

        <div class="o-table-wrap">
            <table class="o-table" id="tabelJadwal">
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
                    <tr data-tanggal="2026-10-19" data-status="terjadwal">
                        <td>Web Master</td>
                        <td>Online</td>
                        <td>Tentor C</td>
                        <td>Siswa A</td>
                        <td>2026-10-19</td>
                        <td>09:00</td>
                        <td>10:30</td>
                        <td><span class="o-status terjadwal">Terjadwal</span></td>
                        <td><button type="button" class="o-action" data-detail>Detail</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="o-empty" id="jadwalKosong" hidden>Jadwal tidak ditemukan.</p>

        <div class="o-toolbar o-approval-heading" style="margin-top:42px">
            <h2>Persetujuan Pergantian Jadwal dari Tentor</h2>
        </div>
        <div class="o-table-wrap">
            <table class="o-table" id="tabelPersetujuan">
                <thead><tr>
                    <th>Nama Kursus</th><th>Kelas</th><th>Tentor</th><th>Siswa</th>
                    <th>Jadwal Lama</th><th>Jadwal Diajukan</th><th>Alasan</th><th>Status</th><th>Aksi</th>
                </tr></thead>
                <tbody id="persetujuanBody">
                    <tr data-status="menunggu">
                        <td>Web Master</td><td>Online</td><td>Tentor C</td><td>Siswa A</td>
                        <td>19 Okt 2026 · 09:00–10:30</td><td>21 Okt 2026 · 13:00–14:30</td>
                        <td>Berhalangan pada jadwal semula</td><td><span class="o-status menunggu">Menunggu</span></td>
                        <td><div class="o-approval-actions"><button type="button" class="o-action o-approve" data-approval="setuju">Setujui</button><button type="button" class="o-action o-reject" data-approval="tolak">Tolak</button></div></td>
                    </tr>
                    <tr data-status="menunggu">
                        <td>MS Office</td><td>offline</td><td>Tentor D</td><td>Siswa B</td>
                        <td>20 Okt 2026 · 10:00–11:30</td><td>22 Okt 2026 · 14:00–15:30</td>
                        <td>Ada keperluan mendadak</td><td><span class="o-status menunggu">Menunggu</span></td>
                        <td><div class="o-approval-actions"><button type="button" class="o-action o-approve" data-approval="setuju">Setujui</button><button type="button" class="o-action o-reject" data-approval="tolak">Tolak</button></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="o-empty" id="persetujuanKosong" hidden>Tidak ada pengajuan pergantian jadwal.</p>
    </div>

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
                        <label for="jadwalKursus">Nama Kursus</label>
                        <select id="jadwalKursus" required>
                            <option value="">Pilih kursus</option>
                            <option>Web Master</option>
                            <option>MS Office</option>
                        </select>
                    </div>
                    <div class="o-form-group">
                        <label for="jadwalKelas">Nama Kelas</label>
                        <select id="jadwalKelas" required>
                            <option value="">Pilih kelas</option>
                        </select>
                    </div>
                    <div class="o-form-group">
                        <label for="jadwalTentor">Nama Tentor</label>
                        <input id="jadwalTentor" maxlength="100" required placeholder="Masukkan nama tentor">
                    </div>
                    <div class="o-form-group">
                        <label for="jadwalSiswa">Nama Siswa</label>
                        <select id="jadwalSiswa" required>
                            <option value="">Pilih siswa</option>
                            <option>Siswa A</option>
                            <option>Siswa B</option>
                            <option>Siswa C</option>
                        </select>
                    </div>
                    <div class="o-form-group">
                        <label for="jadwalTanggalBaru">Tanggal</label>
                        <input id="jadwalTanggalBaru" type="date" required></div>
                    <div class="o-form-group">
                        <label for="jadwalMulai">Jam Mulai</label>
                        <input id="jadwalMulai" type="time" required></div>
                        <div class="o-form-group">
                            <label for="jadwalSelesai">Jam Selesai</label>
                            <input id="jadwalSelesai" type="time" required>
                        </div>
                    <div class="o-form-group o-form-full">
                        <label for="jadwalStatus">Status</label>
                        <select id="jadwalStatus" required>
                            <option value="terjadwal">Terjadwal</option>
                            <option value="selesai">Selesai</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                </div>
                <button class="o-btn o-btn-orange o-btn-submit" type="submit">Simpan Jadwal</button>
            </form>
        </div>
    </div>

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
            <div>
                <span>Nama Kursus</span>
                <strong id="detailKursus">-</strong>
            </div>
            <div>
                <span>Nama Kelas</span>
                <strong id="detailKelas">-</strong>
            </div>
            <div>
                <span>Nama Tentor</span>
                <strong id="detailTentor">-</strong>
            </div>
            <div>
                <span>Nama Siswa</span>
                <strong id="detailSiswa">-</strong>
            </div>
            <div>
                <span>Jam Mulai</span>
                <strong id="detailMulai">-</strong>
            </div>
            <div>
                <span>Jam Selesai</span>
                <strong id="detailSelesai">-</strong>
            </div>
            <div>
                <span>Tanggal</span>
                <strong id="detailTanggal">-</strong>
            </div>
            <div>
                <span>Status</span>
                <strong id="detailStatus">-</strong>
            </div>
        </div>
        <button type="button" class="o-btn o-btn-orange o-btn-full" id="editDetailJadwal">Edit Jadwal</button>
    </div>
</div>

<script>
(()=>{const el=id=>document.getElementById(id),modal=el('modalJadwal'),detail=el('modalDetailJadwal'),map={'Web Master':['online','offline','offline rumah'],'MS Office':['online','offline','offline rumah']};
let editingRow=null,selectedRow=null;function fillClasses(){const s=el('jadwalKelas'),course=el('jadwalKursus').value,old=s.value;s.replaceChildren(new Option('Pilih kelas',''));(map[course]||[]).forEach(n=>s.add(new Option(n,n)));if((map[course]||[]).includes(old))s.value=old}
el('jadwalKursus').addEventListener('change',fillClasses);
function filter(){const q=el('cariJadwal').value.toLowerCase().trim(),month=el('filterTanggal').value,status=el('filterStatus').value;let n=0;el('tabelJadwal').querySelectorAll('tbody tr').forEach(r=>{const show=r.textContent.toLowerCase().includes(q)&&(!month||r.dataset.tanggal.startsWith(month))&&(!status||r.dataset.status===status);r.hidden=!show;if(show)n++});el('jadwalKosong').hidden=n>0}
['cariJadwal','filterTanggal','filterStatus'].forEach(id=>el(id).addEventListener(id==='cariJadwal'?'input':'change',filter));
el('btnTambahJadwal').addEventListener('click',()=>{editingRow=null;el('formJadwal').reset();el('modalJadwal').querySelector('h2').textContent='Tambah Jadwal';el('formJadwal').querySelector('[type=submit]').textContent='Simpan Jadwal';fillClasses();modal.hidden=false});document.querySelectorAll('[data-close]').forEach(b=>b.addEventListener('click',()=>b.closest('.t-modal').hidden=true));
function fmt(v){return v?new Date(v+'T12:00:00').toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'}):'-'}
el('tabelJadwal').addEventListener('click',e=>{const b=e.target.closest('[data-detail]');if(!b)return;const r=b.closest('tr'),c=r.cells;selectedRow=r;['Kursus','Kelas','Tentor','Siswa'].forEach((k,i)=>el('detail'+k).textContent=c[i].textContent.trim());el('detailTanggal').textContent=fmt(r.dataset.tanggal);el('detailMulai').textContent=c[5].textContent;el('detailSelesai').textContent=c[6].textContent;el('detailStatus').textContent=r.dataset.status.charAt(0).toUpperCase()+r.dataset.status.slice(1);detail.hidden=false});
el('editDetailJadwal').addEventListener('click',()=>{if(!selectedRow)return;editingRow=selectedRow;const c=selectedRow.cells;el('jadwalKursus').value=c[0].textContent.trim();fillClasses();el('jadwalKelas').value=c[1].textContent.trim();el('jadwalTentor').value=c[2].textContent.trim();el('jadwalSiswa').value=c[3].textContent.trim();el('jadwalTanggalBaru').value=selectedRow.dataset.tanggal;el('jadwalMulai').value=c[5].textContent.trim();el('jadwalSelesai').value=c[6].textContent.trim();el('jadwalStatus').value=selectedRow.dataset.status;el('modalJadwal').querySelector('h2').textContent='Edit Jadwal';el('formJadwal').querySelector('[type=submit]').textContent='Simpan Perubahan';detail.hidden=true;modal.hidden=false});

function tambahBarisJadwal(course, cls, tentor, siswa, date, start, end, status){
    const body=el('tabelJadwal').tBodies[0],r=body.insertRow();
    r.dataset.tanggal=date;
    r.dataset.status=status;
    [course,cls,tentor,siswa,date,start,end].forEach((v,i)=>{const c=r.insertCell(i);c.textContent=v});
    const sc=r.insertCell(7);
    const badge=document.createElement('span');badge.className='o-status '+status;badge.textContent=status.charAt(0).toUpperCase()+status.slice(1);sc.appendChild(badge);
    const ac=r.insertCell(8);
    const b=document.createElement('button');b.type='button';b.className='o-action';b.dataset.detail='';b.textContent='Detail';ac.appendChild(b);
    filter();
}

el('formJadwal').addEventListener('submit',e=>{
    e.preventDefault();
    const start=el('jadwalMulai').value,end=el('jadwalSelesai').value;
    if(end<=start){alert('Jam selesai harus setelah jam mulai.');return}
    const course=el('jadwalKursus').value,cls=el('jadwalKelas').value,tentor=el('jadwalTentor').value.trim(),siswa=el('jadwalSiswa').value,date=el('jadwalTanggalBaru').value,status=el('jadwalStatus').value;
    
    if(editingRow){
        const c=editingRow.cells;
        editingRow.dataset.tanggal=date;
        editingRow.dataset.status=status;
        c[0].textContent=course;
        c[1].textContent=cls;
        c[2].textContent=tentor;
        c[3].textContent=siswa;
        c[4].textContent=date;
        c[5].textContent=start;
        c[6].textContent=end;
        const badge=c[7].querySelector('span');
        badge.className='o-status '+status;
        badge.textContent=status.charAt(0).toUpperCase()+status.slice(1);
        editingRow=null;
    } else {
        tambahBarisJadwal(course, cls, tentor, siswa, date, start, end, status);
    }
    modal.hidden=true;
    filter();
});

el('persetujuanBody').addEventListener('click',e=>{
 const b=e.target.closest('[data-approval]'); if(!b)return;
 const row=b.closest('tr'); if(row.dataset.status!=='menunggu')return;
 const approve=b.dataset.approval==='setuju';
 row.dataset.status=approve?'disetujui':'ditolak';
 const statusCell=row.cells[7]; statusCell.innerHTML='';
 const badge=document.createElement('span'); badge.className='o-status '+(approve?'aktif':'nonaktif'); badge.textContent=approve?'Disetujui':'Ditolak'; statusCell.appendChild(badge);
 row.cells[8].textContent=approve?'Jadwal disetujui':'Pengajuan ditolak';
 row.querySelectorAll('button').forEach(btn=>btn.disabled=true);

 if(approve){
    const course = row.cells[0].textContent.trim();
    const cls = row.cells[1].textContent.trim();
    const tentor = row.cells[2].textContent.trim();
    const siswa = row.cells[3].textContent.trim();
    
    const textDiajukan = row.cells[5].textContent.trim(); // contoh: "21 Okt 2026 · 13:00–14:30"
    const parts = textDiajukan.split('·');
    const jamParts = parts[1] ? parts[1].trim().split('–') : ['13:00', '14:30'];
    const jamMulaiBaru = jamParts[0] ? jamParts[0].trim() : '13:00';
    const jamSelesaiBaru = jamParts[1] ? jamParts[1].trim() : '14:30';

    let tanggalBaru = '2026-10-21'; 
    if(textDiajukan.includes('21 Okt')) tanggalBaru = '2026-10-21';
    else if(textDiajukan.includes('22 Okt')) tanggalBaru = '2026-10-22';
    else if(textDiajukan.includes('19 Okt')) tanggalBaru = '2026-10-19';

    // 1. Ubah jadwal lama di tabel utama menjadi 'dibatalkan'
    const rowsUtama = el('tabelJadwal').querySelectorAll('tbody tr');
    rowsUtama.forEach(r => {
        const c = r.cells;
        if(c[0].textContent.trim() === course && c[2].textContent.trim() === tentor && c[3].textContent.trim() === siswa && r.dataset.status === 'terjadwal'){
            r.dataset.status = 'dibatalkan';
            const badgeLama = c[7].querySelector('span');
            badgeLama.className = 'o-status dibatalkan';
            badgeLama.textContent = 'Dibatalkan';
        }
    });

    // 2. Tambahkan baris jadwal baru dengan status 'terjadwal'
    tambahBarisJadwal(course, cls, tentor, siswa, tanggalBaru, jamMulaiBaru, jamSelesaiBaru, 'terjadwal');
    filter();
 }
});

[modal,detail].forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.hidden=true}));document.addEventListener('keydown',e=>{if(e.key==='Escape')[modal,detail].forEach(m=>m.hidden=true)});filter()})();
</script>
@endsection