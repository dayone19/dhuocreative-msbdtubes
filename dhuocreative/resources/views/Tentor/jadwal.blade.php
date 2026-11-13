@extends('layouts.Tentor')

@section('title', 'Jadwal')

@section('content')
<div class="t-page tj-page">
 <div class="t-page-header">
    <h1 class="t-page-title">Jadwal Saya</h1>
 </div>
 <div class="jadwal-mingguan" id="jadwalMingguan"></div>
 <div class="tj-section-head">
    <h2 class="tj-section-title">Daftar Jadwal</h2>

    <form method="GET" action="{{ route('tentor.jadwal') }}" class="tj-toolbar" id="formFilterJadwal">
        <input type="text" name="q" value="{{ $q }}" class="tj-input" placeholder="Cari kursus, kelas, atau siswa" aria-label="Cari jadwal">
        <input type="date" name="tanggal" value="{{ $tanggal }}" class="tj-input" aria-label="Filter tanggal">
        <select name="status" class="tj-input tj-select" aria-label="Filter status" onchange="this.form.submit()">
            <option value="" @selected($status === '')>Semua status</option>
            <option value="terjadwal" @selected($status === 'terjadwal')>Terjadwal</option>
            <option value="selesai" @selected($status === 'selesai')>Selesai</option>
            <option value="dibatalkan" @selected($status === 'dibatalkan')>Dibatalkan</option>
        </select>
        <button type="submit" class="tj-btn-detail" style="margin-left:4px">Cari</button>
    </form>
 </div>

 <div class="tj-table-wrap">
    <table class="tj-table" id="tabelJadwal">
        <thead>
            <tr>
                <th>Nama Kursus</th>
                <th>Kelas</th>
                <th>Siswa</th>
                <th>Tanggal</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="jadwalBody">
            @forelse ($jadwal as $j)
            <tr>
                <td>{{ $j->kursus }}</td>
                <td>{{ $j->kelas }}</td>
                <td>{{ $j->siswa }}</td>
                <td>{{ \Carbon\Carbon::parse($j->tanggal)->translatedFormat('d M Y') }}</td>
                <td>{{ $j->mulai }}</td>
                <td>{{ $j->selesai }}</td>
                <td>
                    <span class="tj-pill tj-{{ $j->status }}">{{ ucfirst($j->status) }}</span>
                </td>
                <td>
                    @if ($j->status === 'terjadwal')
                        <button type="button"
                                class="tj-btn-detail tj-btn-ajukan-perubahan"
                                data-id-jadwal="{{ $j->id_jadwal }}"
                                data-id-kelas="{{ $j->id_kelas }}"
                                data-kursus="{{ $j->kursus }}"
                                data-kelas="{{ $j->kelas }}"
                                data-siswa="{{ $j->siswa }}"
                                data-tanggal="{{ $j->tanggal }}"
                                data-mulai="{{ $j->mulai }}"
                                data-selesai="{{ $j->selesai }}">
                            Ubah Jadwal
                        </button>
                    @else
                        <span class="tj-ket">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="tj-empty">Tidak ada jadwal yang cocok.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination server-side (style project) --}}
    @if ($jadwal->hasPages())
    <nav class="tj-pagination" aria-label="Navigasi halaman">
        {{-- Previous --}}
        @if ($jadwal->onFirstPage())
            <button type="button" disabled aria-label="Sebelumnya">&lt;</button>
        @else
            <a href="{{ $jadwal->previousPageUrl() }}" style="text-decoration:none">
                <button type="button" aria-label="Sebelumnya">&lt;</button>
            </a>
        @endif

        @php
            $current = $jadwal->currentPage();
            $last    = $jadwal->lastPage();
            $pages   = collect([1, $last, $current]);
            for ($i = $current - 1; $i <= $current + 1; $i++) {
                if ($i >= 1 && $i <= $last) $pages->push($i);
            }
            if ($current <= 4) {
                foreach ([2, 3, 4] as $n) if ($n < $last) $pages->push($n);
            }
            if ($current >= $last - 3) {
                foreach ([$last - 1, $last - 2, $last - 3] as $n) if ($n > 1) $pages->push($n);
            }
            $pages = $pages->unique()->sort()->values();
            $prev = 0;
        @endphp

        @foreach ($pages as $n)
            @if ($prev && $n - $prev > 1)
                <span class="tj-page-ellipsis">…</span>
            @endif
            @if ($n == $current)
                <button type="button" class="is-active" aria-current="page">{{ $n }}</button>
            @else
                <a href="{{ $jadwal->url($n) }}" style="text-decoration:none">
                    <button type="button">{{ $n }}</button>
                </a>
            @endif
            @php $prev = $n; @endphp
        @endforeach

        {{-- Next --}}
        @if ($jadwal->hasMorePages())
            <a href="{{ $jadwal->nextPageUrl() }}" style="text-decoration:none">
                <button type="button" aria-label="Berikutnya">&gt;</button>
            </a>
        @else
            <button type="button" disabled aria-label="Berikutnya">&gt;</button>
        @endif
    </nav>
    @endif
 </div>

 <style>
 .tj-pagination {
     display: flex;
     justify-content: center;
     align-items: center;
     gap: 8px;
     margin-top: 20px;
     flex-wrap: wrap;
     padding: 4px 0;
 }
 .tj-pagination button {
     min-width: 40px;
     height: 40px;
     padding: 0 12px;
     border: 1.5px solid #cfe0dd;
     border-radius: 12px;
     background: #fffaf0;
     color: #14524b;
     font: inherit;
     font-size: 14px;
     font-weight: 600;
     cursor: pointer;
     transition: background .15s, color .15s, border-color .15s, transform .1s;
     box-shadow: 0 1px 2px rgba(0,0,0,.04);
 }
 .tj-pagination a button:hover:not(:disabled):not(.is-active) {
     background: #e3efed;
     border-color: #286c65;
     color: #0f3d39;
 }
 .tj-pagination button.is-active {
     background: #f27640;
     border-color: #f27640;
     color: #fff;
     box-shadow: 0 2px 8px rgba(242,118,64,.35);
 }
 .tj-pagination button:disabled {
     opacity: .45;
     cursor: not-allowed;
 }
 .tj-pagination .tj-page-ellipsis {
     min-width: 28px;
     height: 40px;
     display: inline-flex;
     align-items: center;
     justify-content: center;
     color: #5f7f7b;
     font-weight: 700;
     font-size: 15px;
     user-select: none;
 }
 </style>

 <div class="tj-section-head tj-section-gap">
    <h2 class="tj-section-title">Pengajuan Jadwal</h2>
</div>

 <div class="tj-table-wrap">
    <table class="tj-table">
        <thead>
            <tr>
                <th>Nama Kursus</th>
                <th>Siswa</th>
                <th>Jadwal Lama</th>
                <th>Jadwal Diajukan</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody id="pengajuanBody">
            @forelse ($pengajuan as $p)
            <tr>
                <td>{{ $p->kursus }}</td>
                <td>{{ $p->siswa }}</td>
                <td>
                    @if ($p->lamaTanggal)
                        {{ \Carbon\Carbon::parse($p->lamaTanggal)->translatedFormat('d M Y') }} · {{ $p->lamaMulai }}–{{ $p->lamaSelesai }}
                    @else
                        Jadwal baru
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d M Y') }} · {{ $p->mulai }}–{{ $p->selesai }}</td>
                <td><span class="tj-pill tj-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                <td class="tj-ket">{{ $p->ket }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="tj-empty">Belum ada pengajuan.</td></tr>
            @endforelse
        </tbody>
    </table>
 </div>
</div>

<!-- Modal Ubah Jadwal -->
<div class="t-modal" id="modalAjukan" hidden>
    <div class="t-modal-box">
        <div class="t-modal-heading">
            <button type="button"
                    class="tmt-back-btn"
                    data-tutup aria-label="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <h2>Ubah Jadwal</h2>
        </div>
        <form id="formAjukan">
            @csrf
            <div class="t-form-group">
                <label for="id_kelas">Kursus</label>
                <select id="id_kelas" disabled></select>
            </div>

            <div class="t-form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" id="tanggal" required>
            </div>
            <div class="t-form-grid">
                <div class="t-form-group">
                    <label for="jam_mulai">Jam Mulai</label>
                    <input type="time" id="jam_mulai" value="09:00" required>
                </div>
                <div class="t-form-group">
                    <label for="jam_selesai">Jam Selesai</label>
                    <input type="time" id="jam_selesai" value="10:30" required>
                </div>
            </div>
            <div class="t-form-group">
                <label for="catatan">Catatan (opsional)</label>
                <textarea id="catatan" rows="3" placeholder="Contoh: sudah disepakati dengan siswa"></textarea>
            </div>
            <button type="submit" class="t-btn t-btn-orange t-btn-full">Kirim Permintaan Perubahan</button>
        </form>
    </div>
</div>

<div id="pesanDemo" role="status" hidden style="position:fixed;right:20px;bottom:20px;z-index:11000;padding:14px 20px;border-radius:18px;background:var(--teal);color:white;box-shadow:0 8px 24px #0002"></div>

<script>
const offsetDate = n => { const d = new Date(); d.setHours(12,0,0,0); d.setDate(d.getDate()+n); return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`; };

// Data kalender mingguan (hanya minggu ini, dari controller)
const jadwalKalender = @json($jadwalKalender);
let jadwalDipilihUntukDiubah = null;

const el = id => document.getElementById(id);
const safe = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const formatTanggal = s => new Date(s+'T12:00:00').toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'});
const badge = s => `<span class="tj-pill tj-${safe(s)}">${safe(s.charAt(0).toUpperCase()+s.slice(1))}</span>`;

function renderMingguan(){
    const names=['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const today=new Date();
    today.setHours(12,0,0,0);
    const monday=new Date(today);
    monday.setDate(today.getDate()-((today.getDay()+6)%7));

    el('jadwalMingguan').innerHTML=names.map((name,i)=>{
        const d=new Date(monday);
        d.setDate(monday.getDate()+i);
        const key=`${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
        const items=jadwalKalender.filter(j=>j.tanggal===key&&j.status==='terjadwal');
        return `<div class="jadwal-hari"><h3>${name} · ${d.getDate()}/${d.getMonth()+1}</h3>${items.length?items.map(j=>`<div class="jadwal-item"><p>${safe(j.kursus)} · ${safe(j.kelas)}</p><p>${safe(j.mulai)}–${safe(j.selesai)}</p></div>`).join(''):'<p>—</p>'}</div>`;
    }).join('');
}

function toast(msg){
    const p=el('pesanDemo');
    p.textContent=msg;
    p.hidden=false;
    clearTimeout(p._t);
    p._t=setTimeout(()=>p.hidden=true,3200);
}

function bukaModalUbah(btn){
    const j = {
        id_jadwal: Number(btn.dataset.idJadwal),
        id_kelas:  Number(btn.dataset.idKelas),
        kursus:    btn.dataset.kursus,
        kelas:     btn.dataset.kelas,
        siswa:     btn.dataset.siswa,
        tanggal:   btn.dataset.tanggal,
        mulai:     btn.dataset.mulai,
        selesai:   btn.dataset.selesai,
    };
    jadwalDipilihUntukDiubah = j;

    const sel = el('id_kelas');
    sel.innerHTML = '';
    const opt = document.createElement('option');
    opt.value = j.id_kelas;
    opt.textContent = `${j.kursus} · ${j.kelas} · ${j.siswa}`;
    sel.appendChild(opt);
    sel.value = j.id_kelas;

    el('tanggal').value = '';
    el('jam_mulai').value = j.mulai;
    el('jam_selesai').value = j.selesai;
    el('catatan').value = 'Permintaan perubahan dari jadwal ' + formatTanggal(j.tanggal) + '.';

    el('tanggal').min = offsetDate(0);
    el('modalAjukan').hidden = false;
}

// Event listener tombol Ubah Jadwal
el('jadwalBody').addEventListener('click', e => {
    const b = e.target.closest('[data-id-jadwal]');
    if (b) bukaModalUbah(b);
});

document.querySelectorAll('[data-tutup]').forEach(b => b.addEventListener('click', () => b.closest('.t-modal').hidden = true));
document.querySelectorAll('.t-modal').forEach(m => m.addEventListener('click', e => { if (e.target === m) m.hidden = true; }));

el('tanggal').min = offsetDate(0);
el('formAjukan').addEventListener('submit', async e => {
    e.preventDefault();
    const lama = jadwalDipilihUntukDiubah;
    if (!lama) { toast('Pilih jadwal yang ingin diubah dari tabel.'); return; }
    const payload = {
        id_kelas: Number(lama.id_kelas),
        id_jadwal_lama: lama.id_jadwal,
        tanggal: el('tanggal').value,
        jam_mulai: el('jam_mulai').value,
        jam_selesai: el('jam_selesai').value,
        catatan: el('catatan').value.trim() || null
    };
    if (payload.tanggal < offsetDate(0)) { toast('Pilih tanggal hari ini atau setelahnya.'); return; }
    if (payload.jam_selesai <= payload.jam_mulai) { toast('Jam selesai harus setelah jam mulai.'); return; }

    const tombol = el('formAjukan').querySelector('[type=submit]');
    tombol.disabled = true;
    try {
        const res = await fetch("{{ route('tentor.jadwal.ajukan') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': el('formAjukan').querySelector('[name=_token]').value
            },
            body: JSON.stringify(payload)
        });
        const body = await res.json().catch(() => ({}));
        if (!res.ok) {
            const pesanPertama = body.errors ? Object.values(body.errors)[0][0] : null;
            toast(pesanPertama || body.message || 'Gagal mengirim permintaan.');
            return;
        }
        toast(body.message);
        setTimeout(() => location.reload(), 900);
    } catch (err) {
        toast('Tidak bisa terhubung ke server.');
    } finally {
        tombol.disabled = false;
    }
});

renderMingguan();
</script>
@endsection