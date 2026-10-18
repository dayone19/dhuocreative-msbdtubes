@extends('layouts.Operator')
@section('title', 'Manajemen Jadwal')
@section('content')
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
                    <p>Web Master · Kelas 001</p>
                    <span>09:00–10:30</span>
                </div>
                <div class="o-week-day">
                    <h3>Selasa</h3>
                    <p>Web Master · Kelas 002</p>
                    <span>13:00–14:30</span>
                </div>
                <div class="o-week-day">
                    <h3>Rabu</h3>
                    <p>MS Office · Kelas 001</p>
                    <span>09:00–10:00</span>
                </div>
                <div class="o-week-day">
                    <h3>Kamis</h3>
                    <p>Belum ada jadwal</p>
                    <span>—</span>
                </div>
                <div class="o-week-day">
                    <h3>Jumat</h3>
                    <p>Web Master · Kelas 001</p>
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
                    <option value="">Semua status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="o-table-wrap">
            <table class="o-table" id="tabelJadwal">
                <thead>
                    <tr>
                        <th>Nama Kursus</th>
                        <th>Nama Kelas</th>
                        <th>Nama Tentor</th>
                        <th>Tanggal</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-tanggal="2026-10-19" data-status="aktif">
                        <td>Web Master</td>
                        <td>001</td>
                        <td>Andi</td>
                        <td>2026-10-19</td>
                        <td>09:00</td>
                        <td>10:30</td>
                        <td><span class="o-status aktif">Aktif</span></td>
                        <td><button type="button" class="o-action" data-detail>Detail</button></td>
                    </tr>
                    <tr data-tanggal="2026-10-20" data-status="aktif">
                        <td>Web Master</td>
                        <td>002</td>
                        <td>Rina</td>
                        <td>2026-10-20</td>
                        <td>13:00</td>
                        <td>14:30</td>
                        <td><span class="o-status aktif">Aktif</span></td>
                        <td><button type="button" class="o-action" data-detail>Detail</button></td>
                    </tr>
                    <tr data-tanggal="2026-10-21" data-status="aktif">
                        <td>MS Office</td>
                        <td>001</td>
                        <td>Budi</td>
                        <td>2026-10-21</td>
                        <td>09:00</td>
                        <td>10:00</td>
                        <td><span class="o-status aktif">Aktif</span></td>
                        <td><button type="button" class="o-action" data-detail>Detail</button></td>
                    </tr>
                    <tr data-tanggal="2026-10-23" data-status="nonaktif">
                        <td>Web Master</td>
                        <td>001</td>
                        <td>Andi</td>
                        <td>2026-10-23</td>
                        <td>13:00</td>
                        <td>14:30</td>
                        <td><span class="o-status nonaktif">Nonaktif</span></td>
                        <td><button type="button" class="o-action" data-detail>Detail</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="o-empty" id="jadwalKosong" hidden>Jadwal tidak ditemukan.</p>
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
                        <select  id="jadwalStatus" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
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
                <span>Tanggal</span>
                <strong id="detailTanggal">-</strong>
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
                <span>Status</span>
                <strong id="detailStatus">-</strong>
            </div>
        </div>
        <button type="button" class="o-btn o-btn-orange o-btn-full" id="editDetailJadwal">Edit Jadwal</button>
    </div>
</div>

<script>
(()=>{const el=id=>document.getElementById(id),modal=el('modalJadwal'),detail=el('modalDetailJadwal'),map={'Web Master':['001','002'],'MS Office':['001']};
let editingRow=null,selectedRow=null;function fillClasses(){const s=el('jadwalKelas'),course=el('jadwalKursus').value,old=s.value;s.replaceChildren(new Option('Pilih kelas',''));(map[course]||[]).forEach(n=>s.add(new Option(n,n)));if((map[course]||[]).includes(old))s.value=old}
el('jadwalKursus').addEventListener('change',fillClasses);
function filter(){const q=el('cariJadwal').value.toLowerCase().trim(),month=el('filterTanggal').value,status=el('filterStatus').value;let n=0;el('tabelJadwal').querySelectorAll('tbody tr').forEach(r=>{const show=r.textContent.toLowerCase().includes(q)&&(!month||r.dataset.tanggal.startsWith(month))&&(!status||r.dataset.status===status);r.hidden=!show;if(show)n++});el('jadwalKosong').hidden=n>0}
['cariJadwal','filterTanggal','filterStatus'].forEach(id=>el(id).addEventListener(id==='cariJadwal'?'input':'change',filter));
el('btnTambahJadwal').addEventListener('click',()=>{editingRow=null;el('formJadwal').reset();el('modalJadwal').querySelector('h2').textContent='Tambah Jadwal';el('formJadwal').querySelector('[type=submit]').textContent='Simpan Jadwal';fillClasses();modal.hidden=false});document.querySelectorAll('[data-close]').forEach(b=>b.addEventListener('click',()=>b.closest('.o-modal').hidden=true));
function fmt(v){return v?new Date(v+'T12:00:00').toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'}):'-'}
el('tabelJadwal').addEventListener('click',e=>{const b=e.target.closest('[data-detail]');if(!b)return;const r=b.closest('tr'),c=r.cells;selectedRow=r;['Kursus','Kelas','Tentor'].forEach((k,i)=>el('detail'+k).textContent=c[i].textContent.trim());el('detailTanggal').textContent=fmt(r.dataset.tanggal);el('detailMulai').textContent=c[4].textContent;el('detailSelesai').textContent=c[5].textContent;el('detailStatus').textContent=r.dataset.status==='aktif'?'Aktif':'Nonaktif';detail.hidden=false});
el('editDetailJadwal').addEventListener('click',()=>{if(!selectedRow)return;editingRow=selectedRow;const c=selectedRow.cells;el('jadwalKursus').value=c[0].textContent.trim();fillClasses();el('jadwalKelas').value=c[1].textContent.trim();el('jadwalTentor').value=c[2].textContent.trim();el('jadwalTanggalBaru').value=selectedRow.dataset.tanggal;el('jadwalMulai').value=c[4].textContent.trim();el('jadwalSelesai').value=c[5].textContent.trim();el('jadwalStatus').value=selectedRow.dataset.status;el('modalJadwal').querySelector('h2').textContent='Edit Jadwal';el('formJadwal').querySelector('[type=submit]').textContent='Simpan Perubahan';detail.hidden=true;modal.hidden=false});
el('formJadwal').addEventListener('submit',e=>{e.preventDefault();const start=el('jadwalMulai').value,end=el('jadwalSelesai').value;if(end<=start){alert('Jam selesai harus setelah jam mulai.');return}const course=el('jadwalKursus').value,cls=el('jadwalKelas').value,tentor=el('jadwalTentor').value.trim(),date=el('jadwalTanggalBaru').value,status=el('jadwalStatus').value,r=editingRow||el('tabelJadwal').tBodies[0].insertRow();r.dataset.tanggal=date;r.dataset.status=status;[course,cls,tentor,date,start,end].forEach((v,i)=>{if(r.cells[i])r.cells[i].textContent=v;else{const c=r.insertCell();c.textContent=v}});const c=r.cells[6]||r.insertCell();c.replaceChildren();const badge=document.createElement('span');badge.className='o-status '+status;badge.textContent=status==='aktif'?'Aktif':'Nonaktif';c.appendChild(badge);if(!r.cells[7]){const ac=r.insertCell(),b=document.createElement('button');b.type='button';b.className='o-action';b.dataset.detail='';b.textContent='Detail';ac.appendChild(b)}editingRow=null;modal.hidden=true;filter()});
[modal,detail].forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.hidden=true}));document.addEventListener('keydown',e=>{if(e.key==='Escape')[modal,detail].forEach(m=>m.hidden=true)});filter()})();
</script>
@endsection
