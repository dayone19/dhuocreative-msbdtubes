@extends('layouts.Operator')
@section('title', 'Daftar Siswa')
@section('content')

<div class="o-page">
    <div class="o-page-header">
        <h1 class="o-page-title">Daftar Siswa</h1>
        <button type="button" class="o-btn o-btn-orange" id="btnTambahSiswa">
            <span class="o-plus">+</span>
            Tambah Siswa
        </button>
    </div>

    <div class="o-toolbar">
        <input class="o-search" id="cariSiswa" type="search" placeholder="Cari ID, nama siswa, kursus, atau kelas..." aria-label="Cari siswa">
        <div class="o-schedule-filters">
            <select class="o-filter" id="filterStatus" aria-label="Filter status">
                <option value="">Semua status</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>
    </div>

    <div class="s-table-wrap">
        <table class="s-table" id="tabelSiswa">
            <thead>
                <tr>
                    <th>ID Siswa</th>
                    <th>Nama</th>
                    <th>Tgl Daftar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="bodySiswa">
                <tr data-status="aktif">
                    <td>S-001</td>
                    <td>Siswa A</td>
                    <td>12 Jan 2026</td>
                    <td><span class="o-status aktif">Aktif</span></td>
                    <td>
                        <div class="o-row-actions">
                            <button type="button" class="o-action" data-detail>Detail</button>
                            <a href="{{ route('operator.tugas', ['siswa' => 'Siswa A']) }}" class="o-action" style="background:#286c65; text-decoration:none;">Lihat Tugas</a>
                        </div>
                    </td>
                </tr>
                <tr data-status="aktif">
                    <td>S-002</td>
                    <td>Siswa B</td>
                    <td>10 Feb 2026</td>
                    <td><span class="o-status aktif">Aktif</span></td>
                    <td>
                        <div class="o-row-actions">
                            <button type="button" class="o-action" data-detail>Detail</button>
                            <a href="{{ route('operator.tugas', ['siswa' => 'Siswa B']) }}" class="o-action" style="background:#286c65; text-decoration:none;">Lihat Tugas</a>
                        </div>
                    </td>
                </tr>
                <tr data-status="nonaktif">
                    <td>S-003</td>
                    <td>Siswa C</td>
                    <td>05 Okt 2026</td>
                    <td><span class="o-status nonaktif">Nonaktif</span></td>
                    <td>
                        <div class="o-row-actions">
                            <button type="button" class="o-action" data-detail>Detail</button>
                            <a href="{{ route('operator.tugas', ['siswa' => 'Siswa C']) }}" class="o-action" style="background:#286c65; text-decoration:none;">Lihat Tugas</a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <p class="o-empty" id="kosongSiswa" hidden>Pendaftaran siswa tidak ditemukan.</p>
    <nav class="tj-pagination" id="paginasiSiswa" aria-label="Halaman daftar siswa" style="margin-top:20px;" hidden></nav>
</div>

<!-- Modal tambah / edit pendaftaran -->
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
            <div id="fieldDataMaster">
                <div class="o-form-group">
                    <label for="namaSiswa">Nama Siswa</label>
                    <input id="namaSiswa" maxlength="100" required placeholder="Masukkan nama siswa">
                </div>
                <div class="o-form-group">
                    <label for="telpSiswa">No. Telp</label>
                    <input id="telpSiswa" maxlength="12" required placeholder="Masukkan nomor telepon">
                </div>
            </div>

            <div id="fieldDataKursus">
                <div class="o-form-group">
                    <label for="kursusSiswa">Nama Kursus</label>
                    <select id="kursusSiswa" required>
                        <option value="">Pilih kursus</option>
                        <option>Web Master</option>
                        <option>MS Office</option>
                    </select>
                </div>
                <div class="o-form-group">
                    <label for="kelasSiswa">Jenis Kelas</label>
                    <select id="kelasSiswa" required>
                        <option value="">Pilih kelas</option>
                        <option>Offline</option>
                        <option>Offline Rumah</option>
                        <option>Online</option>
                    </select>
                </div>
                <div class="o-form-group">
                    <label id="hariLabel">Hari Pembelajaran (1 minggu, maks. 3 kali pertemuan)</label>
                    <div class="o-day-picker" id="hariPicker" role="group" aria-labelledby="hariLabel">
                        <label><input type="checkbox" value="Senin">Senin</label>
                        <label><input type="checkbox" value="Selasa">Selasa</label>
                        <label><input type="checkbox" value="Rabu">Rabu</label>
                        <label><input type="checkbox" value="Kamis">Kamis</label>
                        <label><input type="checkbox" value="Jumat">Jumat</label>
                        <label><input type="checkbox" value="Sabtu">Sabtu</label>
                    </div>
                    <span class="o-hari-hint" id="hariCount" aria-live="polite">0/3 hari dipilih</span>
                </div>
                <div class="o-form-group">
                    <label for="jamMulai">Jam Mulai Pembelajaran</label>
                    <input type="time" id="jamMulai" required>
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
            </div>
            <button type="submit" class="o-btn o-btn-orange o-btn-full" id="submitSiswa">Simpan Pendaftaran</button>
        </form>
    </div>
</div>

<!-- Modal detail siswa -->
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

        <p class="o-course-title">Data Siswa</p>
        <div class="o-detail-list" id="detailDataMaster">
            <div><span>ID Siswa</span><strong>S-001</strong></div>
            <div><span>Nama Siswa</span><strong id="detailNama">Siswa A</strong></div>
            <div><span>No. Telp</span><strong id="detailTelp">08123456789</strong></div>
            <div><span>Tanggal Daftar</span><strong>12 Jan 2026</strong></div>
            <div><span>Status</span><strong>Aktif</strong></div>
        </div>
        <button type="button" class="o-action" id="editDataMaster" style="margin: 10px 0 18px;">Edit Data Siswa</button>

        <p class="o-course-title">Daftar Kursus</p>
        <div class="o-course-list">
            <div class="o-course-item">
                <div class="o-course-head">
                    <strong>Web Master - Online</strong>
                    <button type="button" class="o-action" data-edit-course>Edit</button>
                </div>
                <div class="o-course-row"><span>Hari</span><strong>Senin, Rabu, Jumat</strong></div>
                <div class="o-course-row"><span>Jam Mulai</span><strong class="course-jam">08:00</strong></div>
                <div class="o-course-row"><span>Tentor</span><strong>Tentor C</strong></div>
                <div class="o-course-row"><span>Status</span><strong>Aktif</strong></div>
            </div>
            <div class="o-course-item">
                <div class="o-course-head">
                    <strong>MS Office - Offline</strong>
                    <button type="button" class="o-action" data-edit-course>Edit</button>
                </div>
                <div class="o-course-row"><span>Hari</span><strong>Selasa</strong></div>
                <div class="o-course-row"><span>Tentor</span><strong>Tentor E</strong></div>
                <div class="o-course-row"><span>Status</span><strong>Aktif</strong></div>
            </div>
        </div>
        <button type="button" class="o-btn o-btn-orange o-btn-full" id="tambahKursusSiswa">Tambah Kursus</button>
    </div>
</div>

<script>
const PER_HALAMAN_SISWA = 10;
let halSiswa = 1;

function renderPaginasi(navId, total, halaman, pindah) {
    const nav = document.getElementById(navId);
    if (!nav) return;
    
    nav.replaceChildren();
    if (!total || total <= 1) {
        nav.hidden = true;
        return;
    }
    nav.hidden = false;
    
    const jumlah = Math.ceil(total / PER_HALAMAN_SISWA);
    const awal = (halaman - 1) * PER_HALAMAN_SISWA + 1;
    const akhir = Math.min(halaman * PER_HALAMAN_SISWA, total);

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
const modal=$('#modalSiswa'),detail=$('#modalDetailSiswa'),form=$('#formSiswa');
const masterFields=$('#fieldDataMaster'),courseFields=$('#fieldDataKursus');
const bodySiswa=$('#bodySiswa');
let formMode='new', returnToDetail=false;
let activeCourse = null;

function setGroup(group, show){
    group.hidden=!show;
    group.querySelectorAll('input, select, textarea').forEach(el=>{
        el.disabled=!show;
    });
}
function openForm(mode){
    formMode=mode;
    returnToDetail=mode==='add-course'||mode==='edit-master'||mode==='edit-course';
    setGroup(masterFields, mode==='new'||mode==='edit-master');
    setGroup(courseFields, mode==='new'||mode==='add-course' || mode === 'edit-course');
    $('#jamMulai').required = mode === 'new' || mode === 'add-course' || mode === 'edit-course';
    $('#namaSiswa').required=mode==='new'||mode==='edit-master';
    $('#telpSiswa').required=mode==='new'||mode==='edit-master';
    $('#kursusSiswa').required=mode==='new'||mode==='add-course'||mode==='edit-course';
    $('#kelasSiswa').required=mode==='new'||mode==='add-course' || mode === 'edit-course';
    $('#tanggalDaftar').required=mode==='new'||mode==='add-course';
    $('#statusSiswa').required=mode==='new'||mode==='add-course' || mode === 'edit-course';

    $('#namaSiswa').value=$('#detailNama').textContent.trim();
    $('#telpSiswa').value=$('#detailTelp').textContent.trim();

    if(mode==='edit-course' && activeCourse){
        const heading=activeCourse.querySelector('.o-course-head strong').textContent.trim().split(' - ');
        $('#kursusSiswa').value=heading[0] || '';
        $('#kelasSiswa').value=heading[1] || '';
        const rows=[...activeCourse.querySelectorAll('.o-course-row')];
        const valueFor=label=>rows.find(r=>r.querySelector('span')?.textContent.trim()===label)?.querySelector('strong')?.textContent.trim() || '';
        const selectedDays=valueFor('Hari').split(',').map(x=>x.trim()).filter(Boolean);
        days.forEach(d=>d.checked=selectedDays.includes(d.value));
        $('#jamMulai').value=valueFor('Jam Mulai');
        $('#statusSiswa').value=valueFor('Status').toLowerCase()==='nonaktif'?'nonaktif':'aktif';
        const count=selectedDays.length;
        days.forEach(d=>d.disabled=!d.checked&&count>=3);
        $('#hariCount').textContent=count+'/3 hari dipilih';
    } else if(mode==='new' || mode==='add-course'){
        $('#kursusSiswa').value='';
        $('#kelasSiswa').value='';
        days.forEach(d=>{d.checked=false;d.disabled=false;});
        $('#jamMulai').value='';
        $('#statusSiswa').value='aktif';
        $('#hariCount').textContent='0/3 hari dipilih';
    }

    const titles={
        'new':'Pendaftaran Siswa',
        'edit-master':'Edit Data Siswa',
        'add-course':'Tambah Kursus Siswa',
        'edit-course': 'Edit Kursus Siswa'
    };
    $('#modalSiswa h2').textContent=titles[mode];
    $('#submitSiswa').textContent =
    mode === 'edit-master' ? 'Simpan Perubahan' :
    mode === 'add-course' ? 'Tambah Kursus' :
    mode === 'edit-course' ? 'Simpan Perubahan' :
    'Simpan Pendaftaran';
    detail.hidden=true;
    modal.hidden=false;
}
$('#btnTambahSiswa').onclick=()=>openForm('new');
$('#editDataMaster').onclick=()=>openForm('edit-master');
$('#tambahKursusSiswa').onclick=()=>openForm('add-course');

detail.addEventListener('click',e=>{
    const button=e.target.closest('[data-edit-course]');
    if(!button)return;
    activeCourse=button.closest('.o-course-item');
    openForm('edit-course');
});

all('#tabelSiswa').forEach(tbl => {
    tbl.addEventListener('click', e => {
        const b = e.target.closest('[data-detail]');
        if (!b) return;
        const row = b.closest('tr');
        $('#detailNama').textContent = row.children[1].textContent.trim();
        detail.hidden = false;
    });
});

all('[data-close]').forEach(b=>b.onclick=()=>{
    const parent=b.closest('.o-modal');
    parent.hidden=true;
    if(parent===modal&&returnToDetail) detail.hidden=false;
});

form.onsubmit=e=>{
    e.preventDefault();
    if(formMode==='edit-master'){
        $('#detailNama').textContent=$('#namaSiswa').value.trim();
        $('#detailTelp').textContent=$('#telpSiswa').value.trim();
    }

    if(formMode==='edit-course' && activeCourse){
        activeCourse.querySelector('.o-course-head strong').textContent=
            $('#kursusSiswa').value+' - '+$('#kelasSiswa').value;

        const rows=[...activeCourse.querySelectorAll('.o-course-row')];
        const findRow=label=>rows.find(r=>r.querySelector('span')?.textContent.trim()===label);
        const hariRow=findRow('Hari');
        if(hariRow) hariRow.querySelector('strong').textContent=
            days.filter(d=>d.checked).map(d=>d.value).join(', ');

        let jamRow=findRow('Jam Mulai');
        if(!jamRow){
            jamRow=document.createElement('div');
            jamRow.className='o-course-row';
            jamRow.innerHTML='<span>Jam Mulai</span><strong></strong>';
            const tentorRow=findRow('Tentor');
            activeCourse.insertBefore(jamRow,tentorRow || null);
        }
        jamRow.querySelector('strong').textContent=$('#jamMulai').value;

        const statusRow=findRow('Status');
        if(statusRow) statusRow.querySelector('strong').textContent=
            $('#statusSiswa').value==='aktif'?'Aktif':'Nonaktif';
    }

    modal.hidden=true;
    if(returnToDetail) detail.hidden=false;
};

const days=all('#hariPicker input');
days.forEach(d=>d.onchange=()=>{
    const n=days.filter(x=>x.checked).length;
    days.forEach(x=>x.disabled=!x.checked&&n>=3);
    $('#hariCount').textContent=n+'/3 hari dipilih';
});

function filterTabelSiswa(){
    const q=$('#cariSiswa').value.toLowerCase().trim();
    const st=$('#filterStatus').value;
    const allRows=[...bodySiswa.querySelectorAll('tr')];

    const filteredRows = allRows.filter(r => {
        const ok = r.textContent.toLowerCase().includes(q) && (!st || r.dataset.status === st);
        return ok;
    });

    allRows.forEach(r => r.style.display = 'none');

    const totalFiltered = filteredRows.length;
    $('#kosongSiswa').hidden = totalFiltered > 0;

    if (totalFiltered === 0) {
        renderPaginasi('paginasiSiswa', 0, halSiswa, pindahHalamanSiswa);
        return;
    }

    const maxPage = Math.ceil(totalFiltered / PER_HALAMAN_SISWA);
    if (halSiswa > maxPage) halSiswa = maxPage || 1;

    const start = (halSiswa - 1) * PER_HALAMAN_SISWA;
    const end = start + PER_HALAMAN_SISWA;

    filteredRows.slice(start, end).forEach(r => {
        r.style.display = '';
    });

    renderPaginasi('paginasiSiswa', totalFiltered, halSiswa, pindahHalamanSiswa);
}

function pindahHalamanSiswa(tujuanBaru) {
    halSiswa = tujuanBaru;
    filterTabelSiswa();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

$('#cariSiswa').addEventListener('input', () => {
    halSiswa = 1;
    filterTabelSiswa();
});

$('#filterStatus').addEventListener('change', () => {
    halSiswa = 1;
    filterTabelSiswa();
});

filterTabelSiswa();
})();
</script>
@endsection