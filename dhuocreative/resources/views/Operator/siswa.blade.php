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
            <tbody>
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
            <div class="o-form-group">
                <label for="namaSiswa">Nama Siswa</label>
                <input id="namaSiswa" maxlength="100" required placeholder="Masukkan nama siswa">
            </div>
            <div class="o-form-group">
                <label for="telpSiswa">No. Telp</label>
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

<!-- Modal detail siswa (contoh: Siswa A ikut 2 kursus) -->
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
            <div><span>ID Siswa</span><strong>S-001</strong></div>
            <div><span>Nama Siswa</span><strong>Siswa A</strong></div>
            <div><span>No. Telp</span><strong>08123456789</strong></div>
            <div><span>Tanggal Daftar</span><strong>12 Jan 2026</strong></div>
            <div><span>Status</span><strong>Aktif</strong></div>
        </div>

        <p class="o-course-title">Daftar Kursus</p>
        <div class="o-course-list">
            <div class="o-course-item">
                <div class="o-course-head">
                    <strong>Web Master - Online</strong>
                    <button type="button" class="o-action o-action-sm" data-edit>Edit</button>
                </div>
                <div class="o-course-row"><span>Hari</span><strong>Senin, Rabu, Jumat</strong></div>
                <div class="o-course-row"><span>Tentor</span><strong>Tentor C</strong></div>
                <div class="o-course-row"><span>Status</span><strong>Aktif</strong></div>
            </div>
            <div class="o-course-item">
                <div class="o-course-head">
                    <strong>MS Office - Offline</strong>
                    <button type="button" class="o-action o-action-sm" data-edit>Edit</button>
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
(()=>{
const $=s=>document.querySelector(s),all=s=>[...document.querySelectorAll(s)];
const modal=$('#modalSiswa'),detail=$('#modalDetailSiswa');

// buka / tutup modal
$('#btnTambahSiswa').onclick=()=>modal.hidden=false;
$('#tambahKursusSiswa').onclick=()=>{detail.hidden=true;modal.hidden=false};
all('[data-detail]').forEach(b=>b.onclick=()=>detail.hidden=false);
all('[data-edit]').forEach(b=>b.onclick=()=>{detail.hidden=true;modal.hidden=false});
all('[data-close]').forEach(b=>b.onclick=()=>b.closest('.o-modal').hidden=true);
$('#formSiswa').onsubmit=e=>{e.preventDefault();modal.hidden=true};

// hari pembelajaran: maksimal 3 per minggu
const days=all('#hariPicker input');
days.forEach(d=>d.onchange=()=>{
  const n=days.filter(x=>x.checked).length;
  days.forEach(x=>x.disabled=!x.checked&&n>=3);
  $('#hariCount').textContent=n+'/3 hari dipilih';
});

// cari + filter status
function filter(){
  const q=$('#cariSiswa').value.toLowerCase(),st=$('#filterStatus').value;let n=0;
  all('#tabelSiswa tbody tr').forEach(r=>{
    const ok=r.textContent.toLowerCase().includes(q)&&(!st||r.dataset.status===st);
    r.hidden=!ok;if(ok)n++;
  });
  $('#kosongSiswa').hidden=n>0;
}
$('#cariSiswa').oninput=filter;$('#filterStatus').onchange=filter;
})();
</script>
@endsection