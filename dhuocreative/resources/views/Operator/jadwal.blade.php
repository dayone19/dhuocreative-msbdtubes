{{-- Manajemen Jadwal Operator - Dhuo Creative --}}
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
            Tambah Jadwal
        </button>
    </div>

        <div class="o-schedule-panel">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
            <a href="{{ route('operator.jadwal', ['minggu' => $prevWeek]) }}" style="text-decoration: none; padding: 7px 16px; font-size: 13px; font-weight: 700; color: #286c65; background: #ffffff; border: 1.5px solid #286c65; border-radius: 20px;">‹ Minggu Lalu</a>
            <span style="font-weight: 800; color: #df6833; font-size: 16px;">Periode: {{ $labelMinggu }}</span>
            <a href="{{ route('operator.jadwal', ['minggu' => $nextWeek]) }}" style="text-decoration: none; padding: 7px 16px; font-size: 13px; font-weight: 700; color: #286c65; background: #ffffff; border: 1.5px solid #286c65; border-radius: 20px;">Minggu Depan ›</a>
        </div>
        <div class="o-week-grid" id="ringkasanMingguan">
            @foreach($jadwalMingguan as $hari => $data)
                <div class="o-week-day">
                    <h3>{{ $hari }} <span style="font-size: 12px; font-weight: normal; opacity: 0.9;">({{ $data['tanggal'] }})</span></h3>
                    @if($data['jadwal'])
                        <p>{{ $data['jadwal']->kelas->kursus->nama_kursus ?? '-' }} - {{ $data['jadwal']->kelas->kelas ?? '-' }}</p>
                        <span>{{ substr($data['jadwal']->jam_mulai, 0, 5) }}–{{ substr($data['jadwal']->jam_selesai, 0, 5) }} · {{ $data['jadwal']->kelas->siswa->nama ?? 'Siswa' }}</span>
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
                @forelse($jadwals as $j)
                <tr data-id="{{ $j->id_jadwal }}" data-tanggal="{{ $j->tanggal }}" data-status="{{ $j->status }}" data-pertemuan="{{ $j->pertemuan_ke }}">
                    <td>{{ $j->kelas->kursus->nama_kursus ?? '-' }}</td>
                    <td>{{ $j->kelas->kelas ?? '-' }}</td>
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

        <nav class="tj-pagination" id="paginasiJadwal" style="margin-top: 20px;" hidden></nav>

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
                    <label for="jadwalSiswa">Nama Siswa</label>
                    <select id="jadwalSiswa" name="siswa" required>
                        <option value="">Pilih siswa</option>
                        @foreach($siswaList as $siswa)
                            <option value="{{ $siswa->nama }}">{{ $siswa->nama }}</option>
                        @endforeach
                    </select>
                </div>
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
                        <option value="Online Class">Online Class</option>
                        <option value="Private Class">Private Class</option>
                        <option value="Home Class">Home Class</option>
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
                    <label for="jadwalTanggalBaru">Tanggal</label>
                    <input id="jadwalTanggalBaru" name="tanggal" type="date" required>
                </div>
                <div class="o-form-group">
                    <label for="jadwalMulai">Jam Mulai</label>
                    <input id="jadwalMulai" name="jam_mulai" type="time" min="07:00" max="20:00" required>
                </div>
                <div class="o-form-group">
                    <label for="jadwalSelesai">Jam Selesai</label>
                    <input id="jadwalSelesai" name="jam_selesai" type="time" min="09:00" max="22:00" required>
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
            <div id="bentrokAlert" style="display:none; margin: 14px 0; padding: 12px 16px; border-radius: 8px; background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; font-size: 13px; font-weight: 600;"></div>
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
            <div>
                <span>Pertemuan Ke</span>
                <strong id="detailPertemuan">-</strong>
            </div>
        </div>
        <button type="button" class="o-btn o-btn-orange o-btn-full" id="editDetailJadwal">Edit Jadwal</button>
    </div>
</div>

<script>
const PER_HALAMAN = 6;
let halJadwal = 1;

function renderPaginasi(kontainerId, total, halaman, pindah) {
    const nav = document.getElementById(kontainerId);
    if (!nav) return;
    nav.innerHTML = '';

    const jumlah = Math.ceil(total / PER_HALAMAN);
    if (jumlah <= 1) {
        nav.hidden = true;
        return;
    }
    nav.hidden = false;

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
const el = id => document.getElementById(id);
const modal = el('modalJadwal');
const detail = el('modalDetailJadwal');
const defaultClasses = ['Online Class', 'Private Class', 'Home Class'];
let selectedRow = null;

const siswaMapping = @json($siswaMapping ?? []);
const jadwalExisting = @json($jadwalExisting ?? []);

function cekBentrok() {
    const siswa = el('jadwalSiswa')?.value;
    const tentor = el('jadwalTentor')?.value;
    const tanggal = el('jadwalTanggalBaru')?.value;
    const mulai = el('jadwalMulai')?.value;
    const selesai = el('jadwalSelesai')?.value;
    const idEdit = el('jadwalId')?.value;
    const alertBox = el('bentrokAlert');
    const submitBtn = el('formJadwal')?.querySelector('[type=submit]');

    if (!alertBox) return false;

    if (!tanggal || !mulai || !selesai) {
        alertBox.style.display = 'none';
        if (submitBtn) submitBtn.disabled = false;
        return false;
    }

    if (mulai > '20:00') {
        alertBox.textContent = '⚠️ Jam mulai pembelajaran paling lambat pukul 20:00 (durasi kursus 2 jam, selesai maks. pukul 22:00)!';
        alertBox.style.display = 'block';
        if (submitBtn) submitBtn.disabled = true;
        return true;
    }

    if (selesai > '22:00') {
        alertBox.textContent = '⚠️ Jam selesai pembelajaran tidak boleh di atas pukul 22:00!';
        alertBox.style.display = 'block';
        if (submitBtn) submitBtn.disabled = true;
        return true;
    }

    if (selesai <= mulai && mulai <= '20:00') {
        const parts = mulai.split(':');
        const h = parseInt(parts[0], 10);
        const m = parts[1];
        const endH = String(h + 2).padStart(2, '0');
        el('jadwalSelesai').value = `${endH}:${m}`;
    }

    for (const j of jadwalExisting) {
        if (idEdit && String(j.id) === String(idEdit)) continue; 
        if (j.tanggal !== tanggal) continue;

        const overlap = (mulai < j.jam_selesai && selesai > j.jam_mulai);
        if (overlap) {
            if (tentor && j.tentor === tentor) {
                alertBox.textContent = `⚠️ Jadwal Bentrok! Tentor ${tentor} sudah memiliki jadwal lain pada tanggal ini pukul ${j.jam_mulai}–${j.jam_selesai}.`;
                alertBox.style.display = 'block';
                if (submitBtn) submitBtn.disabled = true;
                return true;
            }
            if (siswa && j.siswa === siswa) {
                alertBox.textContent = `⚠️ Jadwal Bentrok! Siswa ${siswa} sudah memiliki jadwal kelas lain pada tanggal ini pukul ${j.jam_mulai}–${j.jam_selesai}.`;
                alertBox.style.display = 'block';
                if (submitBtn) submitBtn.disabled = true;
                return true;
            }
        }
    }

    alertBox.style.display = 'none';
    if (submitBtn) submitBtn.disabled = false;
    return false;
}

el('jadwalMulai')?.addEventListener('change', () => {
    const val = el('jadwalMulai').value;
    if (!val) return;
    if (val > '20:00') {
        alert('⚠️ Jam mulai pembelajaran paling lambat pukul 20:00 karena durasi kursus 2 jam (selesai maks. pukul 22:00)!');
        el('jadwalMulai').value = '20:00';
        el('jadwalSelesai').value = '22:00';
        cekBentrok();
        return;
    }
    const parts = val.split(':');
    const h = parseInt(parts[0], 10);
    const m = parts[1];
    const endH = String(h + 2).padStart(2, '0');
    el('jadwalSelesai').value = `${endH}:${m}`;
    cekBentrok();
});

['jadwalTanggalBaru', 'jadwalSelesai', 'jadwalTentor'].forEach(id => {
    el(id)?.addEventListener('change', cekBentrok);
});

el('jadwalSiswa')?.addEventListener('change', () => {
    const sName = el('jadwalSiswa').value;
    const list = siswaMapping[sName] || [];

    if (list.length > 0) {
        const currentCourse = el('jadwalKursus').value;
        const match = list.find(x => x.kursus === currentCourse);
        
        if (match) {
            if (match.kelas) el('jadwalKelas').value = match.kelas;
            if (match.tentor) el('jadwalTentor').value = match.tentor;
        } else {
            const first = list[0];
            if (first.kursus) el('jadwalKursus').value = first.kursus;
            fillClasses();
            if (first.kelas) el('jadwalKelas').value = first.kelas;
            if (first.tentor) el('jadwalTentor').value = first.tentor;
        }
    }
    cekBentrok();
});

el('jadwalKursus')?.addEventListener('change', () => {
    fillClasses();
    const sName = el('jadwalSiswa')?.value;
    const course = el('jadwalKursus')?.value;
    const list = siswaMapping[sName] || [];
    const match = list.find(x => x.kursus === course);
    if (match) {
        if (match.kelas) el('jadwalKelas').value = match.kelas;
        if (match.tentor) el('jadwalTentor').value = match.tentor;
    }
    cekBentrok();
});

function fillClasses(){
    const s = el('jadwalKelas');
    const course = el('jadwalKursus').value;
    const old = s.value;
    s.replaceChildren(new Option(course ? 'Pilih kelas' : 'Pilih kursus terlebih dahulu', ''));
    if(course){
        defaultClasses.forEach(n => s.add(new Option(n, n)));
        if(defaultClasses.includes(old)) s.value = old;
    }
}

document.querySelectorAll('[data-close]').forEach(b => {
    b.addEventListener('click', () => { b.closest('.o-modal').hidden = true; });
});

el('btnTambahJadwal')?.addEventListener('click', () => {
    el('jadwalId').value = '';
    el('formJadwal').reset();
    el('modalJadwal').querySelector('h2').textContent = 'Tambah Jadwal';
    el('formJadwal').querySelector('[type=submit]').textContent = 'Simpan Jadwal';
    el('bentrokAlert').style.display = 'none';
    el('formJadwal').querySelector('[type=submit]').disabled = false;
    fillClasses();
    modal.hidden = false;
});

function fmt(v){
    return v ? new Date(v + 'T12:00:00').toLocaleDateString('id-ID', {day:'2-digit', month:'long', year:'numeric'}) : '-';
}

el('tabelJadwal')?.addEventListener('click', e => {
    const b = e.target.closest('[data-detail]');
    if (!b) return;
    const r = b.closest('tr');
    const c = r.cells;
    selectedRow = r;
    el('detailKursus').textContent = c[0].textContent.trim();
    el('detailKelas').textContent = c[1].textContent.trim();
    el('detailTentor').textContent = c[2].textContent.trim();
    el('detailSiswa').textContent = c[3].textContent.trim();
    el('detailTanggal').textContent = fmt(r.dataset.tanggal);
    el('detailMulai').textContent = c[5].textContent.trim();
    el('detailSelesai').textContent = c[6].textContent.trim();
    el('detailStatus').textContent = r.dataset.status.charAt(0).toUpperCase() + r.dataset.status.slice(1);
    el('detailPertemuan').textContent = r.dataset.pertemuan ? 'Pertemuan ' + r.dataset.pertemuan : '-';
    detail.hidden = false;
});

el('editDetailJadwal')?.addEventListener('click', () => {
    if (!selectedRow) return;
    el('jadwalId').value = selectedRow.dataset.id;
    const c = selectedRow.cells;
    el('jadwalSiswa').value = c[3].textContent.trim();
    el('jadwalKursus').value = c[0].textContent.trim();
    fillClasses();
    el('jadwalKelas').value = c[1].textContent.trim();
    el('jadwalTentor').value = c[2].textContent.trim();
    el('jadwalTanggalBaru').value = selectedRow.dataset.tanggal;
    el('jadwalMulai').value = c[5].textContent.trim();
    el('jadwalSelesai').value = c[6].textContent.trim();
    el('jadwalStatus').value = selectedRow.dataset.status;
    el('modalJadwal').querySelector('h2').textContent = 'Edit Jadwal';
    el('formJadwal').querySelector('[type=submit]').textContent = 'Simpan Perubahan';
    el('bentrokAlert').style.display = 'none';
    el('formJadwal').querySelector('[type=submit]').disabled = false;
    detail.hidden = true;
    modal.hidden = false;
    cekBentrok();
});

el('formJadwal')?.addEventListener('submit', e => {
    const start = el('jadwalMulai').value;
    const end = el('jadwalSelesai').value;
    if (start > '20:00') {
        e.preventDefault();
        alert('Jam mulai pembelajaran paling lambat pukul 20:00 (durasi kursus 2 jam, selesai maks. pukul 22:00).');
        return;
    }
    if (end > '22:00') {
        e.preventDefault();
        alert('Jam selesai pembelajaran tidak boleh di atas pukul 22:00.');
        return;
    }
    if (cekBentrok()) {
        e.preventDefault();
        alert('Tidak dapat menyimpan! Terdapat jadwal yang bentrok. Silakan sesuaikan tanggal atau jam.');
        return;
    }
});

function filter(){
    const q = el('cariJadwal').value.toLowerCase().trim();
    const month = el('filterTanggal').value;
    const status = el('filterStatus').value;
    const body = el('bodyJadwal');
    if (!body) return;

    const allRows = [...body.querySelectorAll('tr[data-id]')];
    const filteredRows = allRows.filter(r => {
        const matchText = r.textContent.toLowerCase().includes(q);
        const matchMonth = !month || (r.dataset.tanggal && r.dataset.tanggal.startsWith(month));
        const matchStatus = !status || r.dataset.status === status;
        return matchText && matchMonth && matchStatus;
    });

    allRows.forEach(r => r.style.display = 'none');
    const totalFiltered = filteredRows.length;
    el('jadwalKosong').hidden = totalFiltered > 0;

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

['cariJadwal', 'filterTanggal', 'filterStatus'].forEach(id => {
    el(id)?.addEventListener(id === 'cariJadwal' ? 'input' : 'change', () => {
        halJadwal = 1;
        filter();
    });
});

[modal, detail].forEach(m => m?.addEventListener('click', e => { if (e.target === m) m.hidden = true; }));
document.addEventListener('keydown', e => { if (e.key === 'Escape') [modal, detail].forEach(m => m.hidden = true); });

filter();
})();
</script>
@endsection
