@extends('layouts.Admin')

@section('title', 'Jadwal')

@section('content')

<div class="t-page tj-page">
    <div class="t-page-header">
        <h1 class="t-page-title">Jadwal Siswa</h1>
    </div>
    <div class="jadwal-mingguan" id="jadwalMingguan"></div>
    <div class="o-toolbar">
        <h2>Daftar Jadwal</h2>

        <div class="o-schedule-filters">
            <input class="o-search"id="cariJadwal"type="search"placeholder="Cari kursus, kelas, atau siswa..."aria-label="Cari jadwal">
            <input class="o-filter"style="background-image:none"id="filterTanggal"type="month"aria-label="Filter bulan">
            <select class="o-filter" id="filterStatus">
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
                    <th>Tentor</th>
                    <th>Siswa</th>
                    <th>Tanggal</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="jadwalBody"></tbody>
        </table>
    </div>

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

            <tbody id="pengajuanBody"></tbody>
        </table>
    </div>

</div>

<div class="o-modal" id="modalDetailJadwal" hidden>
    <div class="o-modal-box">

        <div class="o-modal-header">
            <button type="button"
                class="o-modal-close"
                data-close-detail
                aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg"width="35"height="35"fill="currentColor"viewBox="0 0 16 16">
                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>

            <h2>Detail Jadwal</h2>
        </div>
        
        <div class="o-detail-list">
            <div><span>Nama Siswa</span><strong id="detailSiswa">-</strong></div>
            <div><span>Kursus - Kelas</span><strong id="detailKursus">-</strong></div>
            <div><span>Nama Tentor</span><strong id="detailTentor">-</strong></div>
            <div><span>Tanggal</span><strong id="detailTanggal">-</strong></div>
            <div><span>Jam</span><strong id="detailJam">-</strong></div>
            <div><span>Status</span><strong id="detailStatus">-</strong></div>
        </div>
    </div>
</div>

<div class="t-modal" id="modalAjukan" hidden>
    <div class="t-modal-box">
        <div class="t-modal-heading">
            <button type="button"
                class="tmt-back-btn"
                data-tutup
                aria-label="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg"width="35"height="35"fill="currentColor"viewBox="0 0 16 16">
                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>

            <h2>Ajukan Perubahan Jadwal</h2>
        </div>

        <form id="formAjukan">

            <div class="t-form-group">
                <label for="id_kelas">Kursus</label>

                <select id="id_kelas" required>
                    <option value="Web Master|online|Alya Putri">
                        Web Master · online (Alya Putri)
                    </option>

                    <option value="Web Master|offline rumah|Rafi Pratama">
                        Web Master · offline rumah (Rafi Pratama)
                    </option>

                    <option value="MS Office|offline|Nadia Safitri">
                        MS Office · offline (Nadia Safitri)
                    </option>
                </select>
            </div>

            <div class="t-form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" id="tanggal" required>
            </div>

            <div class="t-form-grid">
                <div class="t-form-group">
                    <label for="jam_mulai">Jam Mulai</label>
                    <input type="time"id="jam_mulai"value="09:00"required>
                </div>

                <div class="t-form-group">
                    <label for="jam_selesai">Jam Selesai</label>
                    <input type="time"id="jam_selesai"value="10:30"required>
                </div>
            </div>

            <div class="t-form-group">
                <label for="catatan">Catatan (opsional)</label>
                <textarea id="catatan"
                    rows="3"
                    placeholder="Contoh: sudah disepakati dengan siswa"></textarea>
            </div>

            <button type="submit"
                class="t-btn t-btn-orange t-btn-full">
                Kirim Permintaan Perubahan
            </button>

        </form>
    </div>
</div>

<div id="pesanDemo"
    role="status"
    hidden
    style="position:fixed;right:20px;bottom:20px;z-index:11000;padding:14px 20px;border-radius:18px;background:var(--teal);color:white;box-shadow:0 8px 24px #0002">
</div>

<script>
(() => {
    const offsetDate = n => {
        const d = new Date();
        d.setHours(12, 0, 0, 0);
        d.setDate(d.getDate() + n);

        return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
    };

    let jadwal = [
        {
            kursus:'Web Master',
            kelas:'online',
            tentor:'Budi Santoso',
            siswa:'Alya Putri',
            tanggal:offsetDate(0),
            mulai:'09:00',
            selesai:'10:30',
            status:'terjadwal'
        },
        {
            kursus:'Web Master',
            kelas:'offline rumah',
            tentor:'Budi Santoso',
            siswa:'Rafi Pratama',
            tanggal:offsetDate(1),
            mulai:'13:00',
            selesai:'14:30',
            status:'terjadwal'
        },
        {
            kursus:'MS Office',
            kelas:'offline',
            tentor:'Budi Santoso',
            siswa:'Nadia Safitri',
            tanggal:offsetDate(2),
            mulai:'10:00',
            selesai:'11:30',
            status:'terjadwal'
        },
        {
            kursus:'Web Master',
            kelas:'online',
            tentor:'Budi Santoso',
            siswa:'Alya Putri',
            tanggal:offsetDate(-2),
            mulai:'15:00',
            selesai:'16:30',
            status:'selesai'
        },
        {
            kursus:'MS Office',
            kelas:'offline',
            tentor:'Budi Santoso',
            siswa:'Nadia Safitri',
            tanggal:offsetDate(4),
            mulai:'08:30',
            selesai:'10:00',
            status:'dibatalkan'
        }
    ];

    let pengajuan = [
        {
            kursus:'Web Master',
            siswa:'Alya Putri',
            tanggal:offsetDate(3),
            mulai:'09:00',
            selesai:'10:30',
            status:'menunggu',
            ket:'Menunggu verifikasi operator'
        },
        {
            kursus:'MS Office',
            siswa:'Nadia Safitri',
            tanggal:offsetDate(5),
            mulai:'13:00',
            selesai:'14:00',
            status:'ditolak',
            ket:'Jadwal bentrok dengan kelas lain'
        }
    ];

    let jadwalDipilihUntukDiubah = null;

    const el = id => document.getElementById(id);

    const safe = s => String(s ?? '').replace(/[&<>"']/g, c => ({
        '&':'&amp;',
        '<':'&lt;',
        '>':'&gt;',
        '"':'&quot;',
        "'":'&#39;'
    }[c]));

    const formatTanggal = s => new Date(s+'T12:00:00')
        .toLocaleDateString('id-ID', {
            day:'2-digit',
            month:'short',
            year:'numeric'
        });

    const badge = s =>
        `<span class="tj-pill tj-${safe(s)}">${safe(s.charAt(0).toUpperCase()+s.slice(1))}</span>`;

    // JADWAL MINGGUAN
    function renderMingguan() {

        const names = [
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu'
        ];

        const today = new Date();
        today.setHours(12, 0, 0, 0);

        const monday = new Date(today);
        monday.setDate(
            today.getDate() - ((today.getDay()+6)%7)
        );

        el('jadwalMingguan').innerHTML = names.map((name, i) => {

            const d = new Date(monday);
            d.setDate(monday.getDate() + i);

            const key = `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;

            const items = jadwal.filter(j =>
                j.tanggal === key && j.status === 'terjadwal'
            );

            return `
                <div class="jadwal-hari">
                    <h3>${name} · ${d.getDate()}/${d.getMonth()+1}</h3>

                    ${items.length
                        ? items.map(j => `
                            <div class="jadwal-item">
                                <p>${safe(j.kursus)} · ${safe(j.kelas)}</p>
                                <p>${safe(j.mulai)}–${safe(j.selesai)}</p>
                            </div>
                        `).join('')
                        : '<p>—</p>'
                    }
                </div>
            `;

        }).join('');
    }

    // DAFTAR JADWAL
    function renderJadwal() {

        const q = el('cariJadwal').value.trim().toLowerCase();
        const tgl = el('filterTanggal').value;
        const sts = el('filterStatus').value;

        const rows = jadwal.filter(j =>
            `${j.kursus} ${j.kelas} ${j.siswa}`.toLowerCase().includes(q) &&
            (!tgl || j.tanggal.startsWith(tgl)) &&
            (!sts || j.status === sts)
        );

        el('jadwalBody').innerHTML = rows.length
            ? rows.map(j => {

                const idx = jadwal.indexOf(j);

                return `
                    <tr>
                        <td>${safe(j.kursus)}</td>
                        <td>${safe(j.kelas)}</td>
                        <td>${safe(j.tentor)}</td>
                        <td>${safe(j.siswa)}</td>
                        <td>${formatTanggal(j.tanggal)}</td>
                        <td>${safe(j.mulai)}</td>
                        <td>${safe(j.selesai)}</td>
                        <td>${badge(j.status)}</td>
                        <td>
                            <button type="button"
                                class="tj-btn-detail"
                                data-detail-jadwal="${idx}">
                                Detail
                            </button>
                        </td>
                    </tr>
                `;

            }).join('')

            : `
                <tr>
                    <td colspan="8" class="tj-empty">
                        Tidak ada jadwal yang cocok.
                    </td>
                </tr>
            `;
    }

    // PENGAJUAN JADWAL
    function renderPengajuan() {
        el('pengajuanBody').innerHTML = pengajuan.length
            ? pengajuan.map(p => `
                <tr>
                    <td>${safe(p.kursus)}</td>
                    <td>${safe(p.siswa)}</td>
                    <td>
                        ${p.lamaTanggal
                            ? `${formatTanggal(p.lamaTanggal)} · ${safe(p.lamaMulai)}–${safe(p.lamaSelesai)}`
                            : 'Jadwal baru'
                        }
                    </td>
                    <td>
                        ${formatTanggal(p.tanggal)} ·
                        ${safe(p.mulai)}–${safe(p.selesai)}
                    </td>
                    <td>${badge(p.status)}</td>
                    <td class="tj-ket">${safe(p.ket)}</td>
                </tr>
            `).join('')

            : `
                <tr>
                    <td colspan="6" class="tj-empty">
                        Belum ada pengajuan.
                    </td>
                </tr>
            `;
    }
    // NOTIFIKASI
    function toast(msg) {
        el('pesanDemo').textContent = msg;
        el('pesanDemo').hidden = false;
        setTimeout(() => {
            el('pesanDemo').hidden = true;
        }, 2800);
    }

    // MODAL DETAIL JADWAL
    function bukaModalDetail(index) {
        const j = jadwal[index];
        if (!j) return;

        el('detailSiswa').textContent = j.siswa;
        el('detailKursus').textContent = `${j.kursus} - ${j.kelas}`;
        el('detailTentor').textContent = j.tentor || '-';
        el('detailTanggal').textContent = formatTanggal(j.tanggal);
        el('detailJam').textContent = `${j.mulai} - ${j.selesai}`;
        el('detailStatus').textContent =
            j.status.charAt(0).toUpperCase() + j.status.slice(1);

        el('modalDetailJadwal').hidden = false;
    }

    // MODAL UBAH JADWAL
    function bukaModalUbah(index) {
        jadwalDipilihUntukDiubah = jadwal[index];
        el('formAjukan').reset();
        const j = jadwalDipilihUntukDiubah;
        const option = [...el('id_kelas').options].find(o =>
            o.value.startsWith(j.kursus+'|'+j.kelas+'|')
        );
        if (option) {
            el('id_kelas').value = option.value;
        }
        el('tanggal').value = j.tanggal;
        el('jam_mulai').value = j.mulai;
        el('jam_selesai').value = j.selesai;
        el('catatan').value =
            'Permintaan perubahan dari jadwal '+formatTanggal(j.tanggal)+'.';
        el('tanggal').min = offsetDate(0);
        el('modalAjukan').hidden = false;
    }

    // EVENT TOMBOL DETAIL
    el('jadwalBody').addEventListener('click', e => {
        const b = e.target.closest('[data-detail-jadwal]');
        if (!b) return;
        bukaModalDetail(Number(b.dataset.detailJadwal));
    });

    // TUTUP MODAL DETAIL
    document.querySelectorAll('[data-close-detail]').forEach(b => {
        b.addEventListener('click', () => {
            el('modalDetailJadwal').hidden = true;
        });
    });

    el('modalDetailJadwal').addEventListener('click', e => {
        if (e.target === el('modalDetailJadwal')) {
            el('modalDetailJadwal').hidden = true;
        }
    });

    // TUTUP MODAL UBAH JADWAL
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

    // FILTER JADWAL
    ['cariJadwal', 'filterTanggal', 'filterStatus'].forEach(id => {
        el(id).addEventListener(
            id === 'cariJadwal' ? 'input' : 'change',
            renderJadwal
        );
    });

    // FORM UBAH JADWAL
    el('tanggal').min = offsetDate(0);
    el('formAjukan').addEventListener('submit', e => {
        e.preventDefault();
        const [kursus, kelas, siswa] =
            el('id_kelas').value.split('|');
        const tanggal = el('tanggal').value;
        const mulai = el('jam_mulai').value;
        const selesai = el('jam_selesai').value;
        if (tanggal < offsetDate(0)) {
            toast('Pilih tanggal hari ini atau setelahnya.');
            return;
        }
        if (selesai <= mulai) {
            toast('Jam selesai harus setelah jam mulai.');
            return;
        }
        const lama = jadwalDipilihUntukDiubah;
        pengajuan.unshift({
            kursus,
            siswa,
            lamaTanggal: lama?.tanggal || '',
            lamaMulai: lama?.mulai || '',
            lamaSelesai: lama?.selesai || '',
            tanggal,
            mulai,
            selesai,
            status:'menunggu',
            ket:el('catatan').value.trim() ||
                'Menunggu verifikasi operator'
        });
        renderPengajuan();
        el('modalAjukan').hidden = true;
        el('formAjukan').reset();
        el('tanggal').min = offsetDate(0);
        jadwalDipilihUntukDiubah = null;
        toast('Permintaan perubahan jadwal dikirim (demo frontend saja).');
    });
    renderMingguan();
    renderJadwal();
    renderPengajuan();

})();
</script>

@endsection
