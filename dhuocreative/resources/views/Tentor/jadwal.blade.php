@extends('layouts.Tentor')

@section('title', 'Jadwal')

@section('content')
<div class="t-page tj-page">

    <div class="t-page-header">
        <h1 class="t-page-title">Jadwal Saya</h1>
    </div>

    {{-- Kalender Mingguan --}}
    <div class="o-schedule-panel">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:10px;">
            <a href="{{ route('tentor.jadwal', ['minggu' => $prevWeek]) }}"
               style="text-decoration:none; padding:7px 16px; font-size:13px; font-weight:700; color:#286c65; background:#fff; border:1.5px solid #286c65; border-radius:20px;">
                ‹ Minggu Lalu
            </a>

            <span style="font-weight:800; color:#df6833; font-size:16px;">
                Periode: {{ $labelMinggu }}
            </span>

            <a href="{{ route('tentor.jadwal', ['minggu' => $nextWeek]) }}"
               style="text-decoration:none; padding:7px 16px; font-size:13px; font-weight:700; color:#286c65; background:#fff; border:1.5px solid #286c65; border-radius:20px;">
                Minggu Depan ›
            </a>
        </div>

        <div class="jadwal-mingguan" id="jadwalMingguan">
            @foreach($jadwalMingguan as $hari => $data)
                <div class="jadwal-hari">
                    <h3>{{ $hari }} · {{ $data['tanggal'] }}</h3>

                    <div class="jadwal-list-scroll">
                        @if($data['jadwals']->isNotEmpty())
                            @foreach($data['jadwals'] as $j)
                                <div class="jadwal-item">
                                    <p>{{ $j->kursus }} · {{ $j->kelas }}</p>
                                    <p>{{ $j->mulai }}–{{ $j->selesai }}</p>
                                    <p>{{ $j->siswa }}</p>
                                </div>
                            @endforeach
                        @else
                            <p class="jadwal-kosong">—</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Daftar Jadwal --}}
    <div class="tj-section-head">
        <h2 class="tj-section-title">Daftar Jadwal</h2>

        <div class="tj-toolbar" id="formFilterJadwal">
            <input
                type="search"
                id="cariJadwal"
                class="tj-input"
                placeholder="Cari kursus, kelas, atau siswa..."
                aria-label="Cari jadwal"
                style="width:300px;"
            >

            <input
                type="month"
                id="filterTanggal"
                class="tj-input"
                aria-label="Filter bulan"
            >

            <select id="filterStatus" class="tj-input tj-select" aria-label="Filter status">
                <option value="">Semua Status</option>
                <option value="terjadwal">Terjadwal</option>
                <option value="selesai">Selesai</option>
                <option value="dibatalkan">Dibatalkan</option>
            </select>
        </div>
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
                @forelse($jadwal as $j)
                    <tr
                        data-id="{{ $j->id_jadwal }}"
                        data-id-kelas="{{ $j->id_kelas }}"
                        data-tanggal="{{ $j->tanggal }}"
                        data-status="{{ $j->status }}"
                    >
                        <td>{{ $j->kursus }}</td>
                        <td>{{ $j->kelas }}</td>
                        <td>{{ $j->siswa }}</td>
                        <td>{{ \Carbon\Carbon::parse($j->tanggal)->translatedFormat('d M Y') }}</td>
                        <td>{{ $j->mulai }}</td>
                        <td>{{ $j->selesai }}</td>
                        <td>
                            <span class="tj-pill tj-{{ $j->status }}">
                                {{ ucfirst($j->status) }}
                            </span>
                        </td>
                        <td>
                            @if($j->status === 'terjadwal')
                                <button
                                    type="button"
                                    class="tj-btn-detail tj-btn-ajukan-perubahan"
                                    data-id-jadwal="{{ $j->id_jadwal }}"
                                    data-id-kelas="{{ $j->id_kelas }}"
                                    data-kursus="{{ $j->kursus }}"
                                    data-kelas="{{ $j->kelas }}"
                                    data-siswa="{{ $j->siswa }}"
                                    data-tanggal="{{ $j->tanggal }}"
                                    data-mulai="{{ $j->mulai }}"
                                    data-selesai="{{ $j->selesai }}"
                                >
                                    Ubah Jadwal
                                </button>
                            @else
                                <span class="tj-ket">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr id="jadwalAwalKosong">
                        <td colspan="8" class="tj-empty">
                            Belum ada jadwal tersimpan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <p class="tj-empty" id="jadwalKosong" hidden>
            Jadwal tidak ditemukan.
        </p>

        <nav
            class="tj-pagination"
            id="paginasiJadwal"
            aria-label="Navigasi halaman"
            hidden
        ></nav>
    </div>

    {{-- Riwayat Pengajuan --}}
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
                @forelse($pengajuan as $p)
                    <tr>
                        <td>{{ $p->kursus }}</td>
                        <td>{{ $p->siswa }}</td>
                        <td>
                            @if($p->lamaTanggal)
                                {{ \Carbon\Carbon::parse($p->lamaTanggal)->translatedFormat('d M Y') }}
                                · {{ $p->lamaMulai }}–{{ $p->lamaSelesai }}
                            @else
                                Jadwal baru
                            @endif
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d M Y') }}
                            · {{ $p->mulai }}–{{ $p->selesai }}
                        </td>
                        <td>
                            <span class="tj-pill tj-{{ $p->status }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td class="tj-ket">{{ $p->ket }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="tj-empty">
                            Belum ada pengajuan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- Modal Ubah Jadwal --}}
<div class="t-modal" id="modalAjukan" hidden>
    <div class="t-modal-box">
        <div class="t-modal-heading">
            <button
                type="button"
                class="tmt-back-btn"
                data-tutup
                aria-label="Kembali"
            >
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
                    <input type="time" id="jam_selesai" value="11:00" readonly>
                </div>
            </div>

            <div class="t-form-group">
                <label for="catatan">Catatan (opsional)</label>
                <textarea id="catatan" rows="3" placeholder="Contoh: sudah disepakati dengan siswa"></textarea>
            </div>

            <button type="submit" class="t-btn t-btn-orange t-btn-full">
                Kirim Permintaan Perubahan
            </button>
        </form>
    </div>
</div>

<div
    id="pesanDemo"
    role="status"
    hidden
    style="position:fixed;right:20px;bottom:20px;z-index:11000;padding:14px 20px;border-radius:18px;background:var(--teal);color:white;box-shadow:0 8px 24px #0002"
></div>

<script>
const PER_HALAMAN = 6;
let halJadwal = 1;
let jadwalDipilihUntukDiubah = null;

const el = id => document.getElementById(id);

const offsetDate = n => {
    const d = new Date();
    d.setHours(12, 0, 0, 0);
    d.setDate(d.getDate() + n);

    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
};

const formatTanggal = s =>
    new Date(s + 'T12:00:00').toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });

// Pagination mengikuti pola Admin
function renderPaginasi(kontainerId, total, halaman, pindah) {
    const nav = el(kontainerId);
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

    const buatTombol = (label, tujuan, { aktif = false, mati = false, aria } = {}) => {
        const b = document.createElement('button');

        b.type = 'button';
        b.className = aktif ? 'is-active' : '';
        b.textContent = label;
        b.disabled = mati;

        if (aria) b.setAttribute('aria-label', aria);
        if (aktif) b.setAttribute('aria-current', 'page');

        b.addEventListener('click', () => pindah(tujuan));

        return b;
    };

    nav.appendChild(
        buatTombol('<', halaman - 1, {
            mati: halaman === 1,
            aria: 'Halaman sebelumnya'
        })
    );

    let terakhir = 0;

    for (let i = 1; i <= jumlah; i++) {
        if (i === 1 || i === jumlah || Math.abs(i - halaman) <= 1) {
            if (i - terakhir > 1) {
                const titik = document.createElement('span');
                titik.className = 'tj-page-ellipsis';
                titik.textContent = '…';
                nav.appendChild(titik);
            }

            nav.appendChild(
                buatTombol(String(i), i, {
                    aktif: i === halaman,
                    aria: `Halaman ${i}`
                })
            );

            terakhir = i;
        }
    }

    nav.appendChild(
        buatTombol('>', halaman + 1, {
            mati: halaman === jumlah,
            aria: 'Halaman berikutnya'
        })
    );
}

// Filter dan pencarian jadwal
function filterJadwal() {
    const q = el('cariJadwal').value.toLowerCase().trim();
    const bulan = el('filterTanggal').value;
    const status = el('filterStatus').value;

    const body = el('jadwalBody');
    if (!body) return;

    const semuaBaris = [...body.querySelectorAll('tr[data-id]')];

    const barisFilter = semuaBaris.filter(r => {
        const cocokTeks = r.textContent.toLowerCase().includes(q);

        const cocokBulan =
            !bulan ||
            (r.dataset.tanggal && r.dataset.tanggal.startsWith(bulan));

        const cocokStatus =
            !status || r.dataset.status === status;

        return cocokTeks && cocokBulan && cocokStatus;
    });

    semuaBaris.forEach(r => r.style.display = 'none');

    const total = barisFilter.length;

    el('jadwalKosong').hidden = total > 0;

    const barisAwalKosong = el('jadwalAwalKosong');

    if (barisAwalKosong) {
        barisAwalKosong.style.display = total === 0 ? '' : 'none';
    }

    if (total === 0) {
        renderPaginasi('paginasiJadwal', 0, halJadwal, pindahHalaman);
        return;
    }

    const halamanMaksimal = Math.ceil(total / PER_HALAMAN);

    if (halJadwal > halamanMaksimal) {
        halJadwal = halamanMaksimal;
    }

    const awal = (halJadwal - 1) * PER_HALAMAN;
    const akhir = awal + PER_HALAMAN;

    barisFilter.slice(awal, akhir).forEach(r => {
        r.style.display = '';
    });

    renderPaginasi(
        'paginasiJadwal',
        total,
        halJadwal,
        pindahHalaman
    );
}

function pindahHalaman(tujuanBaru) {
    halJadwal = tujuanBaru;
    filterJadwal();

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

['cariJadwal', 'filterTanggal', 'filterStatus'].forEach(id => {
    el(id)?.addEventListener(
        id === 'cariJadwal' ? 'input' : 'change',
        () => {
            halJadwal = 1;
            filterJadwal();
        }
    );
});

// Toast pemberitahuan
function toast(msg) {
    const p = el('pesanDemo');

    p.textContent = msg;
    p.hidden = false;

    clearTimeout(p._t);

    p._t = setTimeout(() => {
        p.hidden = true;
    }, 3200);
}

// Menghitung jam selesai otomatis 2 jam setelah jam mulai
function hitungJamSelesai() {
    const mulai = el('jam_mulai').value;

    if (!mulai) return;

    const [jam, menit] = mulai.split(':').map(Number);
    const totalMenit = jam * 60 + menit + 120;

    const jamSelesai = Math.floor(totalMenit / 60);
    const menitSelesai = totalMenit % 60;

    el('jam_selesai').value =
        `${String(jamSelesai).padStart(2, '0')}:${String(menitSelesai).padStart(2, '0')}`;
}

el('jam_mulai').addEventListener('input', hitungJamSelesai);

// Membuka modal perubahan jadwal
function bukaModalUbah(btn) {
    const j = {
        id_jadwal: Number(btn.dataset.idJadwal),
        id_kelas: Number(btn.dataset.idKelas),
        kursus: btn.dataset.kursus,
        kelas: btn.dataset.kelas,
        siswa: btn.dataset.siswa,
        tanggal: btn.dataset.tanggal,
        mulai: btn.dataset.mulai,
        selesai: btn.dataset.selesai,
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

    hitungJamSelesai();

    el('catatan').value =
        'Permintaan perubahan dari jadwal ' + formatTanggal(j.tanggal) + '.';

    el('tanggal').min = offsetDate(0);
    el('modalAjukan').hidden = false;
}

// Event tombol Ubah Jadwal
el('jadwalBody').addEventListener('click', e => {
    const b = e.target.closest('[data-id-jadwal]');

    if (b) {
        bukaModalUbah(b);
    }
});

// Menutup modal
document.querySelectorAll('[data-tutup]').forEach(b => {
    b.addEventListener('click', () => {
        b.closest('.t-modal').hidden = true;
    });
});

document.querySelectorAll('.t-modal').forEach(m => {
    m.addEventListener('click', e => {
        if (e.target === m) {
            m.hidden = true;
        }
    });
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        el('modalAjukan').hidden = true;
    }
});

el('tanggal').min = offsetDate(0);

// Mengirim pengajuan perubahan jadwal
el('formAjukan').addEventListener('submit', async e => {
    e.preventDefault();

    const lama = jadwalDipilihUntukDiubah;

    if (!lama) {
        toast('Pilih jadwal yang ingin diubah dari tabel.');
        return;
    }

    const payload = {
        id_kelas: Number(lama.id_kelas),
        id_jadwal_lama: lama.id_jadwal,
        tanggal: el('tanggal').value,
        jam_mulai: el('jam_mulai').value,
        catatan: el('catatan').value.trim() || null
    };

    if (!payload.tanggal || !payload.jam_mulai) {
        toast('Tanggal dan jam mulai wajib diisi.');
        return;
    }

    if (payload.tanggal < offsetDate(0)) {
        toast('Pilih tanggal hari ini atau setelahnya.');
        return;
    }

    if (payload.jam_mulai > '21:59') {
        toast('Jam mulai maksimal pukul 21:59.');
        return;
    }

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
            const pesanPertama = body.errors
                ? Object.values(body.errors)[0][0]
                : null;

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

// Jalankan filter dan pagination saat halaman dibuka
filterJadwal();
</script>
@endsection
