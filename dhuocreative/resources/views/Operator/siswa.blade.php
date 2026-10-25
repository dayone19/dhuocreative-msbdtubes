@extends('layouts.Operator')
@section('title', 'Daftar Siswa')
@section('content')

<div class="o-page">
    <div class="o-page-header">
        <h1 class="o-page-title">Daftar Siswa</h1>
        <button type="button" class="o-btn o-btn-orange" id="btnTambahSiswa">
            <span class="o-plus">+</span>
            Tambah Pendaftaran
        </button>
    </div>

    <div class="o-toolbar">
        <input class="o-search" id="cariSiswa" type="search" placeholder="Cari siswa, kursus, atau kelas..." aria-label="Cari siswa">
        <div class="o-schedule-filters">
            <select class="o-filter" id="filterStatus" aria-label="Filter status">
                <option value="">Semua status</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
            <select class="o-filter" id="filterKursus" aria-label="Filter kursus">
                <option value="">Semua kursus</option>
                <option>Web Master</option>
                <option>MS Office</option>
            </select>
        </div>
    </div>

    <div class="s-table-wrap">
        <table class="s-table" id="tabelSiswa">
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Kursus</th>
                    <th>Kelas</th>
                    <th>Tanggal Daftar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr data-status="aktif" data-kursus="Web Master" data-kelas="Online" data-telp="08123456789" data-tanggal="2026-01-12">
                    <td>Siswa A</td>
                    <td>Web Master</td>
                    <td>Online</td>
                    <td>2026-01-12</td>
                    <td><span class="o-status aktif">Aktif</span></td>
                    <td>
                        <button type="button" class="o-action" data-detail>Detail</button>
                        <a href="{{ route('operator.tugas', ['siswa' => 'Siswa A']) }}" class="o-action" style="background:#286c65; text-decoration:none;">Lihat Tugas</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <p class="o-empty" id="kosongSiswa" hidden>Pendaftaran siswa tidak ditemukan.</p>
</div>

<div class="o-modal" id="modalSiswa" hidden>
    <div class="o-modal-box">
        <div class="o-modal-header">
            <button type="button" class="o-modal-close" data-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2>Pendaftaran Siswa</h2>
        </div>

        <form id="formSiswa">
            <div class="o-form-group">
                <label for="namaSiswa">Nama Siswa</label>
                <input id="namaSiswa" maxlength="100" required placeholder="Masukkan nama siswa">
            </div>
            <div class="o-form-group">
                <label for="telpSiswa">No. Telp</label> <!-- Diperbaiki dari 'telp' agar unik -->
                <input id="telpSiswa" maxlength="12" required placeholder="Masukkan nomor telepon">
            </div>
            <div class="o-form-group">
                <label for="kursusSiswa">Nama Kursus</label>
                <select id="kursusSiswa" required>
                    <option value="">Pilih kursus</option>
                    <option>Web Master</option>
                    <option>MS Office</option>
                </select>
            </div>
            <div class="o-form-group">
                <label for="kelasSiswa">Kelas</label>
                <select id="kelasSiswa" required>
                    <option value="">Pilih kelas</option>
                    <option>Offline</option>
                    <option>Offline Rumah</option>
                    <option>Online</option>
                </select>
            </div>
            <div class="o-form-group">
                <label for="tanggalDaftar">Tanggal Daftar</label>
                <input id="tanggalDaftar" type="date" required>
            </div>
            <div class="o-form-group">
                <label for="statusSiswa">Status Pendaftaran</label>
                <select id="statusSiswa" required>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
            <button type="submit" class="o-btn o-btn-orange o-btn-full">Simpan Pendaftaran</button>
        </form>
    </div>
</div>

<div class="o-modal" id="modalDetailSiswa" hidden>
    <div class="o-modal-box">
        <div class="o-modal-header">
            <button type="button" class="o-modal-close" data-close aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2>Detail Pendaftaran</h2>
        </div>

        <div class="o-detail-list">
            <div>
                <span>Nama Siswa</span>
                <strong id="detailNama">-</strong>
            </div>
            <div>
                <span>No. Telp</span>
                <strong id="detailTelp">-</strong> <!-- Diperbaiki dari 'telp' -->
            </div>
            <div>
                <span>Daftar Kursus</span>
                <strong id="detailKursus">-</strong>
            </div>
            <div>
                <span>Kelas</span>
                <strong id="detailKelas">-</strong>
            </div>
            <div>
                <span>Tanggal Daftar</span>
                <strong id="detailTanggal">-</strong>
            </div>
            <div>
                <span>Status</span>
                <strong id="detailStatus">-</strong>
            </div>
        </div>
        <button type="button" class="o-btn o-btn-orange o-btn-full" id="editDetailSiswa">Edit Data</button>
    </div>
</div>

<script>
(()=>{
const el=id=>document.getElementById(id),modal=el('modalSiswa'),detail=el('modalDetailSiswa'),map={'Web Master':['Offline','Offline Rumah','Online'],'MS Office':['Offline','Offline Rumah','Online']};
let editingRow=null,selectedRow=null;

function fillClasses(){
    const s=el('kelasSiswa'),course=el('kursusSiswa').value,old=s.value;
    s.replaceChildren(new Option('Pilih kelas',''));
    (map[course]||[]).forEach(n=>s.add(new Option(n,n)));
    if((map[course]||[]).includes(old))s.value=old;
}

el('kursusSiswa').addEventListener('change',fillClasses);

function filter(){
    const q=el('cariSiswa').value.toLowerCase().trim(),status=el('filterStatus').value,course=el('filterKursus').value;
    let n=0;
    el('tabelSiswa').querySelectorAll('tbody tr').forEach(r=>{
        const show=r.textContent.toLowerCase().includes(q)&&(!status||r.dataset.status===status)&&(!course||r.dataset.kursus===course);
        r.hidden=!show;
        if(show)n++;
    });
    el('kosongSiswa').hidden=n>0;
}

['cariSiswa','filterStatus','filterKursus'].forEach(id=>el(id).addEventListener(id==='cariSiswa'?'input':'change',filter));

el('btnTambahSiswa').addEventListener('click',()=>{
    editingRow=null;
    el('formSiswa').reset();
    el('modalSiswa').querySelector('h2').textContent='Pendaftaran Siswa';
    el('formSiswa').querySelector('[type=submit]').textContent='Simpan Pendaftaran';
    fillClasses();
    modal.hidden=false;
});

document.querySelectorAll('[data-close]').forEach(b=>b.addEventListener('click',()=>b.closest('.o-modal').hidden=true));

el('tabelSiswa').addEventListener('click',e=>{
    const b=e.target.closest('[data-detail]');
    if(!b)return;
    const r=b.closest('tr');
    selectedRow=r;
    el('detailNama').textContent = r.cells[0].textContent.trim();
    el('detailKursus').textContent = r.cells[1].textContent.trim();
    el('detailKelas').textContent = r.cells[2].textContent.trim();
    el('detailTelp').textContent = r.dataset.telp || '-';
    el('detailTanggal').textContent = r.cells[3].textContent.trim();
    el('detailStatus').textContent = r.dataset.status==='aktif'?'Aktif':'Nonaktif';
    detail.hidden=false;
});

el('editDetailSiswa').addEventListener('click',()=>{
    if(!selectedRow)return;
    editingRow=selectedRow;
    const c=selectedRow.cells;
    el('namaSiswa').value=c[0].textContent.trim();
    el('telpSiswa').value=selectedRow.dataset.telp || '';
    el('kursusSiswa').value=c[1].textContent.trim();
    fillClasses();
    el('kelasSiswa').value=c[2].textContent.trim();
    el('tanggalDaftar').value=selectedRow.dataset.tanggal;
    el('statusSiswa').value=selectedRow.dataset.status;
    el('modalSiswa').querySelector('h2').textContent='Edit Pendaftaran Siswa';
    el('formSiswa').querySelector('[type=submit]').textContent='Simpan Perubahan';
    detail.hidden=true;
    modal.hidden=false;
});

el('formSiswa').addEventListener('submit',e=>{
    e.preventDefault();
    const name=el('namaSiswa').value.trim(),
          telp=el('telpSiswa').value.trim(),
          course=el('kursusSiswa').value,
          cls=el('kelasSiswa').value,
          date=el('tanggalDaftar').value,
          status=el('statusSiswa').value,
          body=el('tabelSiswa').tBodies[0],
          r=editingRow||body.insertRow();
          
    r.dataset.status=status;
    r.dataset.kursus=course;
    r.dataset.kelas=cls;
    r.dataset.telp=telp;
    r.dataset.tanggal=date;

    const values = [name, course, cls, date];
    values.forEach((v,i)=>{
        if(r.cells[i]) r.cells[i].textContent=v;
        else { const c=r.insertCell(i); c.textContent=v; }
    });

    // Kolom Status (indeks 4)
    let sc = r.cells[4];
    if(!sc) sc = r.insertCell(4);
    sc.replaceChildren();
    const badge=document.createElement('span');
    badge.className='o-status '+status;
    badge.textContent=status==='aktif'?'Aktif':'Nonaktif';
    sc.appendChild(badge);

    // Kolom Aksi (indeks 5)
    let ac = r.cells[5];
    if(!ac) ac = r.insertCell(5);
    ac.replaceChildren();

    const btnDetail = document.createElement('button');
    btnDetail.type = 'button';
    btnDetail.className = 'o-action';
    btnDetail.dataset.detail = '';
    btnDetail.textContent = 'Detail';

    const btnTugas = document.createElement('a');
    btnTugas.href = `/operator/tugas?siswa=${encodeURIComponent(name)}`;
    btnTugas.className = 'o-action';
    btnTugas.style.cssText = 'background:#286c65; text-decoration:none;';
    btnTugas.textContent = 'Lihat Tugas';

    const wrapBtn = document.createElement('div');
    wrapBtn.style.cssText = 'display:flex; gap:6px;';
    wrapBtn.appendChild(btnDetail);
    wrapBtn.appendChild(btnTugas);

    ac.appendChild(wrapBtn);

    if(!ac.querySelector('[data-detail]')){
        ac.replaceChildren();
        const btn=document.createElement('button');
        btn.type='button';
        btn.className='o-action';
        btn.dataset.detail='';
        btn.textContent='Detail';
        ac.appendChild(btn);
    }

    editingRow=null;
    modal.hidden=true;
    filter();
});

[modal,detail].forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.hidden=true}));
document.addEventListener('keydown',e=>{if(e.key==='Escape')[modal,detail].forEach(m=>m.hidden=true)});
filter();
})();
</script>
@endsection