@extends('layouts.Operator')
@section('title', 'Manajemen Kursus')
@section('content')
<div class="o-page">
    <section class="o-tab-content active" id="tab-kursus">
        <div class="o-toolbar">
            <h1 class="o-page-title">Daftar Kursus</h1>

            <input
                class="o-search"
                id="cariKursus"
                type="search"
                placeholder="Cari nama kursus..."
                aria-label="Cari kursus"
            >

            <button type="button" class="o-btn o-btn-orange" id="btnTambahKursus">
                <span class="o-plus">+</span>
                Tambah Kursus
            </button>
        </div>
        <div class="k-table-wrap">
            <table class="k-table" id="tabelKursus">
                <thead>
                    <tr>
                        <th>Nama Kursus</th>
                        <th>Deskripsi</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-status="aktif">
                        <td>Web Master</td>
                        <td>Pembuatan dan pengelolaan website</td>
                        <td>12 pertemuan</td>
                        <td><span class="o-status aktif">Aktif</span></td>
                        <td><button type="button" class="o-action" data-detail-kursus>Detail</button></td>
                    </tr>
                    <tr data-status="aktif">
                        <td>MS Office</td>
                        <td>Penggunaan aplikasi Microsoft Office</td>
                        <td>8 pertemuan</td>
                        <td><span class="o-status aktif">Aktif</span></td>
                        <td><button type="button" class="o-action" data-detail-kursus>Detail</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="o-empty" id="kosongKursus" hidden>Kursus tidak ditemukan.</p>
    </section>

    <section class="o-tab-content" id="tab-kelas">
        <div class="o-toolbar">
            <h2>Daftar Kelas</h2>

            <input
                class="o-search"
                id="cariKelas"
                type="search"
                placeholder="Cari nama kursus atau kelas..."
                aria-label="Cari kelas"
            >

            <button type="button" class="o-btn o-btn-orange" id="btnTambahKelas">
                <span class="o-plus">+</span>
                Tambah Kelas
            </button>
        </div>
        <div class="o-table-wrap">
            <table class="o-table" id="tabelKelas">
                <thead>
                    <tr>
                        <th>Nama Kursus</th>
                        <th>Nama Kelas</th>
                        <th>Kapasitas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-kursus="Web Master">
                        <td>Web Master</td>
                        <td>001</td>
                        <td>20</td>
                        <td><span class="o-status aktif">Aktif</span></td>
                        <td><button type="button" class="o-action" data-detail-kelas>Detail</button></td>
                    </tr>
                    <tr data-kursus="Web Master">
                        <td>Web Master</td>
                        <td>002</td>
                        <td>20</td>
                        <td><span class="o-status aktif">Aktif</span></td>
                        <td><button type="button" class="o-action" data-detail-kelas>Detail</button></td>
                    </tr>
                    <tr data-kursus="MS Office">
                        <td>MS Office</td>
                        <td>001</td>
                        <td>15</td>
                        <td><span class="o-status aktif">Aktif</span></td>
                        <td><button type="button" class="o-action" data-detail-kelas>Detail</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="o-empty" id="kosongKelas" hidden>Kelas tidak ditemukan.</p>
    </section>
</div>

<div class="o-modal" id="modalKursus" hidden>
    <div class="o-modal-box">
        <div class="o-modal-header">
            <button type="button" class="o-modal-close" data-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2>Tambah Kursus</h2>
        </div>
        <form id="formKursus">
            <div class="o-form-group">
                <label for="namaKursus">Nama Kursus</label>
                <input id="namaKursus" maxlength="100" required placeholder="Contoh: Web Master">
            </div>
            <div class="o-form-group">
                <label for="deskripsiKursus">Deskripsi</label>
                <textarea id="deskripsiKursus" rows="3" placeholder="Deskripsi kursus"></textarea>
            </div>
            <div class="o-form-group">
                <label for="durasiKursus">Durasi</label>
                <input id="durasiKursus" type="number" min="1" required placeholder="Masukkan durasi">
            </div>
            <div class="o-form-group">
                <label for="statusKursus">Status</label>
                <select id="statusKursus" required>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
            <button class="o-btn o-btn-orange o-btn-full" type="submit">Simpan Kursus</button>
        </form>
    </div>
</div>

<div class="o-modal" id="modalKelas" hidden>
    <div class="o-modal-box">
        <div class="o-modal-header">
            <button type="button" class="o-modal-close" data-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2>Tambah Kelas</h2>
        </div>

        <form id="formKelas">
            <div class="o-form-group">
                <label for="kursusKelas">Nama Kursus</label>
                <select id="kursusKelas" required>
                    <option value="">Pilih kursus</option>
                </select>
            </div>
            <div class="o-form-group">
                <label for="namaKelas">Nama Kelas</label>
                <input id="namaKelas" maxlength="100" required placeholder="Contoh: 001">
            </div>
            <div class="o-form-group">
                <label for="kapasitasKelas">Kapasitas</label>
                <input id="kapasitasKelas" type="number" min="1" required>
            </div>
            <div class="o-form-group">
                <label for="statusKelas">Status</label>
                <select id="statusKelas" required>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
            <button class="o-btn o-btn-orange o-btn-full" type="submit">Simpan Kelas</button>
        </form>
    </div>
</div>

<div class="o-modal" id="detailModalKursus" hidden>
    <div class="o-modal-box">
        <div class="o-modal-header">
            <button type="button" class="o-modal-close" data-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2>Detail Kursus</h2>
        </div>
        <div class="o-detail-list">
            <div>
                <span>Nama Kursus</span>
                <strong id="dNamaKursus">-</strong>
            </div>
            <div>
                <span>Deskripsi</span>
                <strong id="dDeskripsiKursus">-</strong>
            </div>
            <div>
                <span>Durasi</span>
                <strong id="dDurasiKursus">-</strong>
            </div>
            <div>
                <span>Status</span>
                <strong id="dStatusKursus">-</strong>
            </div>
        </div>
        <button type="button" class="o-btn o-btn-orange o-btn-full" id="editDetailKursus">Edit Kursus</button>
    </div>
</div>

<div class="o-modal" id="detailModalKelas" hidden>
    <div class="o-modal-box">
        <div class="o-modal-header">
            <button type="button" class="o-modal-close" data-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2>Detail Kelas</h2>
        </div>
        <div class="o-detail-list">
            <div>
                <span>Nama Kursus</span>
                <strong id="dKursusKelas">-</strong>
            </div>
            <div>
                <span>Nama Kelas</span>
                <strong id="dNamaKelas">-</strong>
            </div>
            <div>
                <span>Kapasitas</span>
                <strong id="dKapasitasKelas">-</strong>
            </div>
            <div>
                <span>Status</span>
                <strong id="dStatusKelas">-</strong>
            </div>
        </div>
        <button type="button" class="o-btn o-btn-orange o-btn-full" id="editDetailKelas">Edit Kelas</button>
    </div>
</div>
<script>
(() => {
 const el=id=>document.getElementById(id), mk=el('modalKursus'), mc=el('modalKelas'), dk=el('detailModalKursus'), dc=el('detailModalKelas');let editingCourse=null,editingClass=null,selectedCourse=null,selectedClass=null;
 function courseNames(){return [...new Set([...el('tabelKursus').tBodies[0].rows].map(r=>r.cells[0].textContent.trim()))]}
 function fillCourses(){const s=el('kursusKelas'),old=s.value;s.replaceChildren(new Option('Pilih kursus',''));courseNames().forEach(n=>s.add(new Option(n,n)));if(courseNames().includes(old))s.value=old}
 document.querySelectorAll('.o-tab').forEach(b=>b.addEventListener('click',()=>{document.querySelectorAll('.o-tab,.o-tab-content').forEach(n=>n.classList.remove('active'));b.classList.add('active');el('tab-'+b.dataset.tab).classList.add('active')}));
 function filter(input,table,empty){const q=el(input).value.toLowerCase().trim();let n=0;el(table).querySelectorAll('tbody tr').forEach(r=>{r.hidden=!r.textContent.toLowerCase().includes(q);if(!r.hidden)n++});el(empty).hidden=n>0}
 el('cariKursus').addEventListener('input',()=>filter('cariKursus','tabelKursus','kosongKursus'));el('cariKelas').addEventListener('input',()=>filter('cariKelas','tabelKelas','kosongKelas'));
 el('btnTambahKursus').addEventListener('click',()=>{editingCourse=null;el('formKursus').reset();el('modalKursus').querySelector('h2').textContent='Tambah Kursus';el('formKursus').querySelector('[type=submit]').textContent='Simpan Kursus';mk.hidden=false});el('btnTambahKelas').addEventListener('click',()=>{editingClass=null;el('formKelas').reset();el('modalKelas').querySelector('h2').textContent='Tambah Kelas';el('formKelas').querySelector('[type=submit]').textContent='Simpan Kelas';fillCourses();mc.hidden=false});
 document.querySelectorAll('[data-close]').forEach(b=>b.addEventListener('click',()=>b.closest('.o-modal').hidden=true));
 el('tabelKursus').addEventListener('click',e=>{const b=e.target.closest('[data-detail-kursus]');if(!b)return;selectedCourse=b.closest('tr');const c=selectedCourse.cells;el('dNamaKursus').textContent=c[0].textContent.trim();el('dDeskripsiKursus').textContent=c[1].textContent.trim();el('dDurasiKursus').textContent=c[2].textContent.trim();el('dStatusKursus').textContent=selectedCourse.dataset.status==='aktif'?'Aktif':'Nonaktif';dk.hidden=false});
 el('tabelKelas').addEventListener('click',e=>{const b=e.target.closest('[data-detail-kelas]');if(!b)return;selectedClass=b.closest('tr');const c=selectedClass.cells;el('dKursusKelas').textContent=c[0].textContent.trim();el('dNamaKelas').textContent=c[1].textContent.trim();el('dKapasitasKelas').textContent=c[2].textContent.trim();el('dStatusKelas').textContent=c[3].textContent.includes('Aktif')?'Aktif':'Nonaktif';dc.hidden=false});
 el('editDetailKursus').addEventListener('click',()=>{if(!selectedCourse)return;editingCourse=selectedCourse;const c=selectedCourse.cells;el('namaKursus').value=c[0].textContent.trim();el('deskripsiKursus').value=c[1].textContent.trim()==='-'?'':c[1].textContent.trim();el('durasiKursus').value=c[2].textContent.trim();el('statusKursus').value=selectedCourse.dataset.status;el('modalKursus').querySelector('h2').textContent='Edit Kursus';el('formKursus').querySelector('[type=submit]').textContent='Simpan Perubahan';dk.hidden=true;mk.hidden=false});
 el('editDetailKelas').addEventListener('click',()=>{if(!selectedClass)return;editingClass=selectedClass;fillCourses();el('kursusKelas').value=selectedClass.cells[0].textContent.trim();el('namaKelas').value=selectedClass.cells[1].textContent.trim();el('kapasitasKelas').value=selectedClass.cells[2].textContent.trim();el('statusKelas').value=selectedClass.cells[3].textContent.includes('Aktif')?'aktif':'nonaktif';el('modalKelas').querySelector('h2').textContent='Edit Kelas';el('formKelas').querySelector('[type=submit]').textContent='Simpan Perubahan';dc.hidden=true;mc.hidden=false});
 function statusBadge(s){const b=document.createElement('span');b.className='o-status '+s;b.textContent=s==='aktif'?'Aktif':'Nonaktif';return b}
 el('formKursus').addEventListener('submit',e=>{e.preventDefault();const body=el('tabelKursus').tBodies[0],name=el('namaKursus').value.trim(),target=editingCourse;if([...body.rows].some(r=>r!==target&&r.cells[0].textContent.trim().toLowerCase()===name.toLowerCase())){alert('Nama kursus sudah ada.');return}const r=target||body.insertRow(),vals=[name,el('deskripsiKursus').value.trim()||'-',el('durasiKursus').value];vals.forEach((v,i)=>{if(r.cells[i])r.cells[i].textContent=v;else{const c=r.insertCell();c.textContent=v}});const status=el('statusKursus').value;r.dataset.status=status;const sc=r.cells[3]||r.insertCell();sc.replaceChildren(statusBadge(status));if(!r.cells[4]){const ac=r.insertCell(),b=document.createElement('button');b.type='button';b.className='o-action';b.dataset.detailKursus='';b.textContent='Detail';ac.appendChild(b)}editingCourse=null;mk.hidden=true;fillCourses();filter('cariKursus','tabelKursus','kosongKursus')});
 el('formKelas').addEventListener('submit',e=>{e.preventDefault();const name=el('kursusKelas').value,cls=el('namaKelas').value.trim(),body=el('tabelKelas').tBodies[0],target=editingClass;if([...body.rows].some(r=>r!==target&&r.cells[0].textContent.trim()===name&&r.cells[1].textContent.trim().toLowerCase()===cls.toLowerCase())){alert('Nama kelas tersebut sudah digunakan untuk kursus ini.');return}const r=target||body.insertRow(),vals=[name,cls,el('kapasitasKelas').value];vals.forEach((v,i)=>{if(r.cells[i])r.cells[i].textContent=v;else{const c=r.insertCell();c.textContent=v}});r.dataset.kursus=name;const status=el('statusKelas').value;const sc=r.cells[3]||r.insertCell();sc.replaceChildren(statusBadge(status));if(!r.cells[4]){const ac=r.insertCell(),b=document.createElement('button');b.type='button';b.className='o-action';b.dataset.detailKelas='';b.textContent='Detail';ac.appendChild(b)}editingClass=null;mc.hidden=true;filter('cariKelas','tabelKelas','kosongKelas')});
 [mk,mc,dk,dc].forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.hidden=true}));document.addEventListener('keydown',e=>{if(e.key==='Escape')[mk,mc,dk,dc].forEach(m=>m.hidden=true)});
})();
</script>
@endsection
