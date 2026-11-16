
@extends('layouts.admin')

@section('content')

@php
    $activeTab = request()->hasAny([
        'tentor_search',
        'tentor_keahlian',
        'tentor_status',
        'tentor_page'
    ]) ? 'dp-tentor' : (
        request()->hasAny([
            'operator_search',
            'operator_status',
            'operator_page'
        ]) ? 'dp-operator' : 'dp-siswa'
    );
@endphp

<div class="dp-page">

    {{-- TAB NAVIGATION --}}
    <nav class="dp-tabs" role="tablist">
        <button class="dp-tab {{ $activeTab === 'dp-siswa' ? 'is-active' : '' }}"
            role="tab" data-target="dp-siswa"
            aria-selected="{{ $activeTab === 'dp-siswa' ? 'true' : 'false' }}">
            Data Siswa
        </button>

        <button class="dp-tab {{ $activeTab === 'dp-tentor' ? 'is-active' : '' }}"
            role="tab" data-target="dp-tentor"
            aria-selected="{{ $activeTab === 'dp-tentor' ? 'true' : 'false' }}">
            Data Tentor
        </button>

        <button class="dp-tab {{ $activeTab === 'dp-operator' ? 'is-active' : '' }}"
            role="tab" data-target="dp-operator"
            aria-selected="{{ $activeTab === 'dp-operator' ? 'true' : 'false' }}">
            Data Operator
        </button>
    </nav>

<!-- data siswa -->
    <section class="dp-panel {{ $activeTab === 'dp-siswa' ? 'is-active' : '' }}"
        id="dp-siswa" role="tabpanel">

        <div class="dp-stats">
            <div class="dp-stat">
                <h3>Total siswa</h3>
                <p>{{ $totalSiswa }}</p>
            </div>

            <div class="dp-stat">
                <h3>Aktif</h3>
                <p>{{ $siswaAktif }}</p>
            </div>

            <div class="dp-stat">
                <h3>Selesai</h3>
                <p>{{ $siswaSelesai }}</p>
            </div>

            <div class="dp-stat">
                <h3>Keluar</h3>
                <p>{{ $siswaKeluar }}</p>
            </div>
        </div>

        <form action="{{ route('admin.dataPengguna') }}" method="GET">
            <div class="dp-search-row">
                <div class="t-search-wrap">
                    <input type="text"
                        name="siswa_search"
                        class="t-search"
                        placeholder="Cari nama"
                        aria-label="Cari nama siswa"
                        value="{{ request('siswa_search') }}">

                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="10.5" cy="10.5" r="7"/>
                        <line x1="15.5" y1="15.5" x2="21" y2="21"/>
                    </svg>
                </div>
            </div>

            <div class="dp-filters">
                <select class="akun-select" name="siswa_kursus" onchange="this.form.submit()">
                    <option value="">Semua kursus</option>
                    @foreach ($daftarKursusSiswa as $kursus)
                        <option value="{{ $kursus->id_kursus }}"
                            {{ request('siswa_kursus') == $kursus->id_kursus ? 'selected' : '' }}>
                            {{ $kursus->nama_kursus }}
                        </option>
                    @endforeach
                </select>

                <select class="akun-select" name="siswa_status" onchange="this.form.submit()">
                    <option value="">Semua status</option>
                    <option value="aktif" {{ request('siswa_status') == 'aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>
                    <option value="selesai" {{ request('siswa_status') == 'selesai' ? 'selected' : '' }}>
                        Selesai
                    </option>
                    <option value="keluar" {{ request('siswa_status') == 'keluar' ? 'selected' : '' }}>
                        Keluar
                    </option>
                </select>
            </div>
        </form>

        <div class="dp-table-container">
            <table class="dp-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kursus</th>
                        <th>Jadwal</th>
                        <th>Pukul</th>
                        <th>Tentor</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($dataSiswa as $siswa)
                        <tr>
                            <td>{{ $siswa->nama }}</td>
                            <td>{{ $siswa->nama_kursus }}</td>
                            <td>{{ $siswa->jadwal ?: '-' }}</td>
                            <td>{{ $siswa->pukul ?: '-' }}</td>
                            <td>{{ $siswa->nama_tentor ?: '-' }}</td>
                            <td>{{ ucfirst($siswa->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center;">
                                Data siswa tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION SISWA --}}
        @if ($dataSiswa->lastPage() > 1)
            @php
                $current = $dataSiswa->currentPage();
                $last = $dataSiswa->lastPage();
                $start = max(1, min($current - 2, $last - 4));
                $end = min($last, $start + 4);
            @endphp

            <nav class="dp-pagination" aria-label="Halaman siswa">
                @if ($dataSiswa->onFirstPage())
                    <button class="dp-page-btn" disabled>&lt;</button>
                @else
                    <a class="dp-page-btn" href="{{ $dataSiswa->previousPageUrl() }}">&lt;</a>
                @endif

                @if ($start > 1)
                    <a class="dp-page-btn" href="{{ $dataSiswa->url(1) }}">1</a>
                    <span class="dp-page-btn">...</span>
                @endif

                @for ($page = $start; $page <= $end; $page++)
                    <a class="dp-page-btn {{ $page == $current ? 'is-active' : 'is-soft' }}"
                        href="{{ $dataSiswa->url($page) }}">
                        {{ $page }}
                    </a>
                @endfor

                @if ($end < $last)
                    <span class="dp-page-btn">...</span>
                    <a class="dp-page-btn" href="{{ $dataSiswa->url($last) }}">
                        {{ $last }}
                    </a>
                @endif

                @if ($dataSiswa->hasMorePages())
                    <a class="dp-page-btn" href="{{ $dataSiswa->nextPageUrl() }}">&gt;</a>
                @else
                    <button class="dp-page-btn" disabled>&gt;</button>
                @endif
            </nav>
        @endif
    </section>
 
    <!-- data tentor -->
    <section class="dp-panel {{ $activeTab === 'dp-tentor' ? 'is-active' : '' }}"
        id="dp-tentor" role="tabpanel">

        <div class="dp-stats">
            <div class="dp-stat">
                <h3>Total tentor</h3>
                <p>{{ $totalTentor }}</p>
            </div>

            <div class="dp-stat">
                <h3>Aktif</h3>
                <p>{{ $tentorAktif }}</p>
            </div>

            <div class="dp-stat">
                <h3>Kelas berjalan</h3>
                <p>{{ $kelasBerjalan }}</p>
            </div>

            <div class="dp-stat">
                <h3>Non aktif</h3>
                <p>{{ $tentorNonaktif }}</p>
            </div>
        </div>

        <form action="{{ route('admin.dataPengguna') }}" method="GET">
            <div class="dp-search-row">
                <div class="t-search-wrap">
                    <input type="text"
                        name="tentor_search"
                        class="t-search"
                        placeholder="Cari nama"
                        aria-label="Cari nama tentor"
                        value="{{ request('tentor_search') }}">

                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="10.5" cy="10.5" r="7"/>
                        <line x1="15.5" y1="15.5" x2="21" y2="21"/>
                    </svg>
                </div>
            </div>

            <div class="dp-filters">
                <select class="akun-select" name="tentor_keahlian" onchange="this.form.submit()">
                    <option value="">Semua keahlian</option>
                    @foreach ($daftarKeahlian as $kursus)
                        <option value="{{ $kursus->id_kursus }}"
                            {{ request('tentor_keahlian') == $kursus->id_kursus ? 'selected' : '' }}>
                            {{ $kursus->nama_kursus }}
                        </option>
                    @endforeach
                </select>

                <select class="akun-select" name="tentor_status" onchange="this.form.submit()">
                    <option value="">Semua status</option>
                    <option value="aktif" {{ request('tentor_status') == 'aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>
                    <option value="nonaktif" {{ request('tentor_status') == 'nonaktif' ? 'selected' : '' }}>
                        Non aktif
                    </option>
                </select>
            </div>
        </form>

        <div class="dp-table-container">
            <table class="dp-table">
                <thead>
                    <tr>
                        <th>Tentor</th>
                        <th>Keahlian</th>
                        <th>Jadwal</th>
                        <th>Pukul</th>
                        <th>Total siswa</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($dataTentor as $tentor)
                        <tr>
                            <td>{{ $tentor->nama }}</td>
                            <td>{{ $tentor->nama_kursus }}</td>
                            <td>{{ $tentor->jadwal ?: '-' }}</td>
                            <td>{{ $tentor->pukul ?: '-' }}</td>
                            <td>{{ $tentor->total_siswa }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center;">
                                Data tentor tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION TENTOR --}}
        @if ($dataTentor->lastPage() > 1)
            @php
                $current = $dataTentor->currentPage();
                $last = $dataTentor->lastPage();
                $start = max(1, min($current - 2, $last - 4));
                $end = min($last, $start + 4);
            @endphp

            <nav class="dp-pagination" aria-label="Halaman tentor">
                @if ($dataTentor->onFirstPage())
                    <button class="dp-page-btn" disabled>&lt;</button>
                @else
                    <a class="dp-page-btn" href="{{ $dataTentor->previousPageUrl() }}">&lt;</a>
                @endif

                @if ($start > 1)
                    <a class="dp-page-btn" href="{{ $dataTentor->url(1) }}">1</a>
                    <span class="dp-page-btn">...</span>
                @endif

                @for ($page = $start; $page <= $end; $page++)
                    <a class="dp-page-btn {{ $page == $current ? 'is-active' : 'is-soft' }}"
                        href="{{ $dataTentor->url($page) }}">
                        {{ $page }}
                    </a>
                @endfor

                @if ($end < $last)
                    <span class="dp-page-btn">...</span>
                    <a class="dp-page-btn" href="{{ $dataTentor->url($last) }}">
                        {{ $last }}
                    </a>
                @endif

                @if ($dataTentor->hasMorePages())
                    <a class="dp-page-btn" href="{{ $dataTentor->nextPageUrl() }}">&gt;</a>
                @else
                    <button class="dp-page-btn" disabled>&gt;</button>
                @endif
            </nav>
        @endif
    </section>

<!-- data operator -->
    <section class="dp-panel {{ $activeTab === 'dp-operator' ? 'is-active' : '' }}"
        id="dp-operator" role="tabpanel">

        <div class="dp-stats">
            <div class="dp-stat">
                <h3>Total operator</h3>
                <p>{{ $totalOperator }}</p>
            </div>

            <div class="dp-stat">
                <h3>Aktif</h3>
                <p>{{ $operatorAktif }}</p>
            </div>

            <div class="dp-stat">
                <h3>Pengajuan menunggu</h3>
                <p>{{ $pengajuanMenunggu }}</p>
            </div>

            <div class="dp-stat">
                <h3>Pengajuan diproses</h3>
                <p>{{ $pengajuanDiproses }}</p>
            </div>
        </div>

        <form action="{{ route('admin.dataPengguna') }}" method="GET">
            <div class="dp-filters dp-filters--inline">
                <div class="t-search-wrap">
                    <input type="text"
                        name="operator_search"
                        class="t-search"
                        placeholder="Cari nama"
                        aria-label="Cari nama operator"
                        value="{{ request('operator_search') }}">

                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="10.5" cy="10.5" r="7"/>
                        <line x1="15.5" y1="15.5" x2="21" y2="21"/>
                    </svg>
                </div>

                <select class="akun-select" name="operator_status" onchange="this.form.submit()">
                    <option value="">Semua status</option>
                    <option value="aktif" {{ request('operator_status') == 'aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>
                    <option value="nonaktif" {{ request('operator_status') == 'nonaktif' ? 'selected' : '' }}>
                        Non aktif
                    </option>
                </select>
            </div>
        </form>

        <div class="dp-table-container">
            <table class="dp-table">
                <thead>
                    <tr>
                        <th>Operator</th>
                        <th>No. telepon</th>
                        <th>Bergabung</th>
                        <th>Email</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($dataOperator as $operator)
                        <tr>
                            <td>{{ $operator->nama }}</td>
                            <td>{{ $operator->no_telp ?: '-' }}</td>
                            <td>{{ $operator->tahun_masuk ?: '-' }}</td>
                            <td>{{ $operator->email }}</td>
                            <td>{{ ucfirst($operator->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center;">
                                Data operator tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION OPERATOR --}}
        @if ($dataOperator->lastPage() > 1)
            @php
                $current = $dataOperator->currentPage();
                $last = $dataOperator->lastPage();
                $start = max(1, min($current - 2, $last - 4));
                $end = min($last, $start + 4);
            @endphp

            <nav class="dp-pagination" aria-label="Halaman operator">
                @if ($dataOperator->onFirstPage())
                    <button class="dp-page-btn" disabled>&lt;</button>
                @else
                    <a class="dp-page-btn" href="{{ $dataOperator->previousPageUrl() }}">&lt;</a>
                @endif

                @if ($start > 1)
                    <a class="dp-page-btn" href="{{ $dataOperator->url(1) }}">1</a>
                    <span class="dp-page-btn">...</span>
                @endif

                @for ($page = $start; $page <= $end; $page++)
                    <a class="dp-page-btn {{ $page == $current ? 'is-active' : 'is-soft' }}"
                        href="{{ $dataOperator->url($page) }}">
                        {{ $page }}
                    </a>
                @endfor

                @if ($end < $last)
                    <span class="dp-page-btn">...</span>
                    <a class="dp-page-btn" href="{{ $dataOperator->url($last) }}">
                        {{ $last }}
                    </a>
                @endif

                @if ($dataOperator->hasMorePages())
                    <a class="dp-page-btn" href="{{ $dataOperator->nextPageUrl() }}">&gt;</a>
                @else
                    <button class="dp-page-btn" disabled>&gt;</button>
                @endif
            </nav>
        @endif
    </section>

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

            dpPanels.forEach((p) => {
                p.classList.toggle('is-active', p.id === tab.dataset.target);
            });
        });
    });
</script>

@endsection
