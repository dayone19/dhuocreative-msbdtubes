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
    @if(session('success'))
        <div style="background: #286c65; color: white; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #df6833; color: white; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
            {{ session('error') }}
        </div>
    @endif
    <div class="o-page-header">
        <h1 class="o-page-title">Manajemen Jadwal</h1>
        <button type="button" class="o-btn o-btn-orange" id="btnTambahJadwal">
            <span class="o-plus">+</span> 
            Tambah Jadwal</button>
        </div>
        <div class="o-schedule-panel">
            <div class="o-week-grid" id="ringkasanMingguan">
                @foreach($jadwalMingguan as $hari => $j)
                    <div class="o-week-day">
                        <h3>{{ $hari }}</h3>
                        @if($j)
                            <p>{{ $j->kelas->kursus->nama_kursus ?? '-' }} · {{ $j->kelas->kelas ?? '-' }}</p>
                            <span>{{ substr($j->jam_mulai, 0, 5) }}–{{ substr($j->jam_selesai, 0, 5) }}</span>
                        @else
                            <p>Belum ada jadwal</p>
                            <span>—</span>
                        @endif
                    </div>
                @endforeach
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
                    @forelse($jadwals as $j)
                    <tr data-id="{{ $j->id_jadwal }}" data-tanggal="{{ $j->tanggal }}" data-status="{{ $j->status }}">
                        <td>{{ $j->kelas->kursus->nama_kursus ?? '-' }}</td>
                        <td>{{ ucfirst($j->kelas->kelas ?? '-') }}</td>
                        <td>{{ $j->tentor->nama ?? '-' }}</td>
                        <td>{{ $j->kelas->siswa->nama ?? '-' }}</td>
                        <td>{{ $j->tanggal }}</td>
                        <td>{{ substr($j->jam_mulai, 0, 5) }}</td>
                        <td>{{ substr($j->jam_selesai, 0, 5) }}</td>
                        <td><span class="o-status {{ $j->status }}">{{ ucfirst($j->status) }}</span></td>
                        <td><button type="button" class="o-action" data-detail>Detail</button></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align:center; padding: 20px;">Belum ada jadwal tersimpan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <p class="o-empty" id="jadwalKosong" hidden>Jadwal tidak ditemukan.</p>

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
                    @forelse($pengajuanList as $p)
                    <tr data-status="{{ $p->status }}" data-id="{{ $p->id_pengajuan }}">
                        <td>{{ $p->kelas->kursus->nama_kursus ?? '-' }}</td>
                        <td>{{ ucfirst($p->kelas->kelas ?? '-') }}</td>
                        <td>{{ $p->tentor->nama ?? '-' }}</td>
                        <td>{{ $p->kelas->siswa->nama ?? '-' }}</td>
                        <td>
                            @if($p->jadwalLama)
                                {{ \Carbon\Carbon::parse($p->jadwalLama->tanggal)->format('d M Y') }} · {{ substr($p->jadwalLama->jam_mulai, 0, 5) }}–{{ substr($p->jadwalLama->jam_selesai, 0, 5) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($p->tanggal_baru)->format('d M Y') }} · {{ substr($p->jam_mulai, 0, 5) }}–{{ substr($p->jam_selesai, 0, 5) }}
                        </td>
                        <td>{{ $p->alasan ?? '-' }}</td>
                        <td><span class="o-status {{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                        <td>
                            @if($p->status === 'menunggu')
                            <div class="o-approval-actions">
                                <form method="POST" action="{{ route('operator.jadwal.approve') }}" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="id_pengajuan" value="{{ $p->id_pengajuan }}">
                                    <input type="hidden" name="aksi" value="setuju">
                                    <button type="submit" class="o-action o-approve">Setujui</button>
                                </form>
                                <form method="POST" action="{{ route('operator.jadwal.approve') }}" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="id_pengajuan" value="{{ $p->id_pengajuan }}">
                                    <input type="hidden" name="aksi" value="tolak">
                                    <button type="submit" class="o-action o-reject">Tolak</button>
                                </form>
                            </div>
                            @else
                            <span style="color: #666; font-size: 13px;">{{ ucfirst($p->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align:center; padding: 20px;">Tidak ada pengajuan pergantian jadwal.</td>
                    </tr>
                    @endforelse
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

            <form id="formJadwal" action="{{route('operator.jadwal.store')}}" method="POST">
                @csrf
                <input type="hidden" name="id_jadwal" id="jadwalId">
                <div class="o-form-grid">
                    <div class="o-form-group">
                        <label for="jadwalKursus">Nama Kursus</label>
                        <select id="jadwalKursus" name="kursus" required>
                            <option value="">Pilih kursus</option>
                            @foreach($kursusList as $kursus)
                                <option value="{{ $kursus->nama_kursus }}">{{ $kursus->nama_kursus }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="o-form-group">
                        <label for="jadwalKelas">Nama Kelas</label>
                        <select id="jadwalKelas" name="kelas" required>
                            <option value="">Pilih kelas</option>
                            <option value="Online">Online</option>
                            <option value="Offline">Offline</option>
                            <option value="Offline Rumah">Offline Rumah</option>
                        </select>
                    </div>
                    <div class="o-form-group">
                        <label for="jadwalTentor">Nama Tentor</label>
                        <select id="jadwalTentor" name="tentor" required>
                            <option value="">Pilih tentor</option>
                            @foreach($tentorList as $tentor)
                                <option value="{{ $tentor->nama }}">{{ $tentor->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="o-form-group">
                        <label for="jadwalSiswa">Nama Siswa</label>
                        <select id="jadwalSiswa" name="siswa" required>
                            <option value="">Pilih siswa</option>
                            @foreach($siswaList as $siswa)
                                <option value="{{ $siswa->nama }}">{{ $siswa->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="o-form-group">
                        <label for="jadwalTanggalBaru">Tanggal</label>
                        <input id="jadwalTanggalBaru" name="tanggal" type="date" required></div>
                    <div class="o-form-group">
                        <label for="jadwalMulai">Jam Mulai</label>
                        <input id="jadwalMulai" name="jam_mulai" type="time" required></div>
                        <div class="o-form-group">
                            <label for="jadwalSelesai">Jam Selesai</label>
                            <input id="jadwalSelesai" name="jam_selesai" type="time" required>
                        </div>
                    <div class="o-form-group o-form-full">
                        <label for="jadwalStatus">Status</label>
                        <select id="jadwalStatus" name="status" required>
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
(()=>{const el=id=>document.getElementById(id),modal=el('modalJadwal'),detail=el('modalDetailJadwal');
const defaultClasses = ['Online', 'Offline', 'Offline Rumah'];
let editingRow=null,selectedRow=null;
function fillClasses(){
    const s=el('jadwalKelas'),course=el('jadwalKursus').value,old=s.value;
    s.replaceChildren(new Option(course ? 'Pilih kelas' : 'Pilih kursus terlebih dahulu', ''));
    if(course){
        defaultClasses.forEach(n=>s.add(new Option(n,n)));
        if(defaultClasses.includes(old))s.value=old;
    }
}
el('jadwalKursus').addEventListener('change',fillClasses);
function filter(){const q=el('cariJadwal').value.toLowerCase().trim(),month=el('filterTanggal').value,status=el('filterStatus').value;let n=0;el('tabelJadwal').querySelectorAll('tbody tr').forEach(r=>{const show=r.textContent.toLowerCase().includes(q)&&(!month||r.dataset.tanggal.startsWith(month))&&(!status||r.dataset.status===status);r.hidden=!show;if(show)n++});el('jadwalKosong').hidden=n>0}
['cariJadwal','filterTanggal','filterStatus'].forEach(id=>el(id).addEventListener(id==='cariJadwal'?'input':'change',filter));
el('btnTambahJadwal').addEventListener('click',()=>{el('jadwalId').value='';editingRow=null;el('formJadwal').reset();el('modalJadwal').querySelector('h2').textContent='Tambah Jadwal';el('formJadwal').querySelector('[type=submit]').textContent='Simpan Jadwal';fillClasses();modal.hidden=false});document.querySelectorAll('[data-close]').forEach(b => {b.addEventListener('click', () => {b.closest('.o-modal').hidden = true;});});
function fmt(v){return v?new Date(v+'T12:00:00').toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'}):'-'}
el('tabelJadwal').addEventListener('click',e=>{const b=e.target.closest('[data-detail]');if(!b)return;const r=b.closest('tr'),c=r.cells;selectedRow=r;['Kursus','Kelas','Tentor','Siswa'].forEach((k,i)=>el('detail'+k).textContent=c[i].textContent.trim());el('detailTanggal').textContent=fmt(r.dataset.tanggal);el('detailMulai').textContent=c[5].textContent;el('detailSelesai').textContent=c[6].textContent;el('detailStatus').textContent=r.dataset.status.charAt(0).toUpperCase()+r.dataset.status.slice(1);detail.hidden=false});
el('editDetailJadwal').addEventListener('click',()=>{if(!selectedRow)return;el('jadwalId').value=selectedRow.dataset.id;editingRow=selectedRow;const c=selectedRow.cells;el('jadwalKursus').value=c[0].textContent.trim();fillClasses();el('jadwalKelas').value=c[1].textContent.trim();el('jadwalTentor').value=c[2].textContent.trim();el('jadwalSiswa').value=c[3].textContent.trim();el('jadwalTanggalBaru').value=selectedRow.dataset.tanggal;el('jadwalMulai').value=c[5].textContent.trim();el('jadwalSelesai').value=c[6].textContent.trim();el('jadwalStatus').value=selectedRow.dataset.status;el('modalJadwal').querySelector('h2').textContent='Edit Jadwal';el('formJadwal').querySelector('[type=submit]').textContent='Simpan Perubahan';detail.hidden=true;modal.hidden=false});

el('formJadwal').addEventListener('submit',e=>{
    const start=el('jadwalMulai').value,end=el('jadwalSelesai').value;
    if(end<=start){
        e.preventDefault();
        alert('Jam selesai harus setelah jam mulai.');
        return;
    }
});

[modal,detail].forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.hidden=true}));document.addEventListener('keydown',e=>{if(e.key==='Escape')[modal,detail].forEach(m=>m.hidden=true)});filter()})();
</script>
@endsection