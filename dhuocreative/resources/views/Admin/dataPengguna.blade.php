@extends('layouts.admin')

@section('content')

<div class="dp-page">

    <nav class="dp-tabs" role="tablist">
        <button class="dp-tab is-active" role="tab" data-target="dp-siswa" aria-selected="true">Data Siswa</button>
        <button class="dp-tab" role="tab" data-target="dp-tentor" aria-selected="false">Data Tentor</button>
        <button class="dp-tab" role="tab" data-target="dp-operator" aria-selected="false">Data Operator</button>
    </nav>
 
    <!-- Siswa -->
    <section class="dp-panel is-active" id="dp-siswa" role="tabpanel">
        <div class="dp-stats">
            <div class="dp-stat"><h3>Total siswa</h3><p>120</p></div>
            <div class="dp-stat"><h3>Aktif</h3><p>109</p></div>
            <div class="dp-stat"><h3>Selesai</h3><p>19</p></div>
            <div class="dp-stat"><h3>Keluar</h3><p>3</p></div>
        </div>
 
        <div class="dp-search-row">
            <div class="t-search-wrap">
            <input type="text" class="t-search" placeholder="Cari nama" aria-label="Cari nama siswa">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.5" cy="10.5" r="7"/><line x1="15.5" y1="15.5" x2="21" y2="21"/></svg>
            </div>
        </div>
 
        <div class="dp-filters">
            <select class="akun-select" aria-label="Filter kursus">
            <option>Semua kursus</option>
            <option>Web Dev</option>
            </select>
            <select class="akun-select" aria-label="Filter status">
            <option>Semua status</option>
            <option>Aktif</option>
            <option>Selesai</option>
            <option>Keluar</option>
            </select>
        </div>
 
        <div class="dp-table-container">
            <table class="dp-table">
            <thead>
                <tr><th>Nama</th><th>Kursus</th><th>Jadwal</th><th>Pukul</th><th>Tentor</th><th>Status</th></tr>
            </thead>
            <tbody>
                <tr><td>Syalikha</td><td>Web Dev</td><td>Senin, Rabu, Jumat</td><td>19.00</td><td>Deyoan</td><td>Aktif</td></tr>
            </tbody>
            </table>
        </div>
    </section>
 
    <!-- Tentor -->
    <section class="dp-panel" id="dp-tentor" role="tabpanel">
        <div class="dp-stats">
            <div class="dp-stat"><h3>Total tentor</h3><p>120</p></div>
            <div class="dp-stat"><h3>Aktif</h3><p>109</p></div>
            <div class="dp-stat"><h3>Kelas berjalan</h3><p>19</p></div>
            <div class="dp-stat"><h3>Non aktif</h3><p>3</p></div>
        </div>
 
        <div class="dp-search-row">
            <div class="t-search-wrap">
            <input type="text" class="t-search" placeholder="Cari nama" aria-label="Cari nama tentor">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.5" cy="10.5" r="7"/><line x1="15.5" y1="15.5" x2="21" y2="21"/></svg>
            </div>
        </div>
 
        <div class="dp-filters">
            <select class="akun-select" aria-label="Filter keahlian">
            <option>Semua keahlian</option>
            <option>Web Dev</option>
            </select>
            <select class="akun-select" aria-label="Filter status">
            <option>Semua status</option>
            <option>Aktif</option>
            <option>Non aktif</option>
            </select>
        </div>
 
        <div class="dp-table-container">
            <table class="dp-table">
            <thead>
                <tr><th>Tentor</th><th>Keahlian</th><th>Jadwal</th><th>Pukul</th><th>Total siswa</th></tr>
            </thead>
            <tbody>
                <tr><td>Syalikha</td><td>Web Dev</td><td>Senin, Rabu, Jumat</td><td>19.00</td><td>18</td></tr>
            </tbody>
            </table>
        </div>
    </section>
 
    <!-- Operator -->
    <section class="dp-panel" id="dp-operator" role="tabpanel">
        <div class="dp-stats">
            <div class="dp-stat"><h3>Total operator</h3><p>120</p></div>
            <div class="dp-stat"><h3>Aktif</h3><p>109</p></div>
            <div class="dp-stat"><h3>Penundaan diproses</h3><p>19</p></div>
            <div class="dp-stat"><h3>Laporan kendala</h3><p>3</p></div>
        </div>
 
        <div class="dp-filters dp-filters--inline">
            <div class="t-search-wrap">
            <input type="text" class="t-search" placeholder="Cari nama" aria-label="Cari nama operator">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.5" cy="10.5" r="7"/><line x1="15.5" y1="15.5" x2="21" y2="21"/></svg>
            </div>
            <select class="akun-select" aria-label="Filter status">
            <option>Semua status</option>
            <option>Aktif</option>
            <option>Non aktif</option>
            </select>
        </div>
 
        <div class="dp-table-container">
            <table class="dp-table">
            <thead>
                <tr><th>Operator</th><th>No. telepon</th><th>Bergabung</th><th>Jam kerja</th><th>Status</th></tr>
            </thead>
            <tbody>
                <tr><td>Syalikha</td><td>085207125180</td><td>2024</td><td>08.00 - 16.00</td><td>Aktif</td></tr>
            </tbody>
            </table>
        </div>
    </section>
 
    <nav class="dp-pagination" aria-label="Halaman">
        <button class="dp-page-btn" aria-label="Sebelumnya">&lt;</button>
        <button class="dp-page-btn">1</button>
        <button class="dp-page-btn is-soft">2</button>
        <button class="dp-page-btn is-soft">3</button>
        <button class="dp-page-btn" aria-label="Berikutnya">&gt;</button>
    </nav>
</div>
 
<script>
    const dpTabs = document.querySelectorAll('.dp-tab');
    const dpPanels = document.querySelectorAll('.dp-panel');
 
    dpTabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            dpTabs.forEach((t) => {
            const active = t === tab;
            t.classList.toggle('is-active', active);
            t.setAttribute('aria-selected', active);
            });
            dpPanels.forEach((p) => p.classList.toggle('is-active', p.id === tab.dataset.target));
        });
    });
</script>
@endsection