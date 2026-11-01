@extends('layouts.admin')

@section('content')
<div class="akun-page">
    <div class="akun-header">
        <button type="button" class="t-btn t-btn-orange akun-btn-buat">
            <span class="t-plus">+</span> Buat Akun
        </button>
    </div>

    <div class="akun-toolbar">
        <div class="t-search-wrap">
            <input type="text" class="t-search" id="cariAkun" placeholder="Cari nama">
            <svg viewBox="0 0 24 24"><circle cx="10" cy="10" r="7"/><path d="M15.5 15.5 21 21"/></svg>
        </div>

        <select class="t-filter akun-select" id="filterRole">
            <option value="">Pilih role</option>
            <option value="Admin">Admin</option>
            <option value="Operator">Operator</option>
            <option value="Tentor">Tentor</option>
            <option value="Siswa">Siswa</option>
        </select>
    </div>

    <div class="akun-table-container">
        <table class="akun-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Syalikha</td>
                    <td>Operator</td>
                    <td>Aktif</td>
                    <td>
                        <div class="akun-aksi">
                            <a href= "{{ route('admin.buatAkun') }}{{ request()->routeIs('admin.buatAkun') ? 'active' : '' }}" class="akun-icon-btn" title="Edit">
                                <svg viewBox="0 0 24 24">
                                    <path d="M11 4H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/>
                                    <path d="M18.4 2.6a2 2 0 0 1 2.8 2.8L12 14.6 8 15.5l.9-4z"/>
                                </svg>
                            </a>
                            <button type="button" class="akun-icon-btn" title="Ubah status">
                                <svg viewBox="0 0 24 24"><path d="M20 12a8 8 0 0 1-14 5.3"/><path d="M4 12a8 8 0 0 1 14-5.3"/><path d="M18 3v4h-4"/><path d="M6 21v-4h4"/></svg>
                            </button>
                            <button type="button" class="akun-icon-btn" title="Lihat">
                                <svg viewBox="0 0 24 24"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/><path d="M4 20 20 4"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Syalikha</td>
                    <td>Operator</td>
                    <td>Aktif</td>
                    <td>
                        <div class="akun-aksi">
                            <button type="button" class="akun-icon-btn" title="Edit">
                                <svg viewBox="0 0 24 24"><path d="M11 4H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><path d="M18.4 2.6a2 2 0 0 1 2.8 2.8L12 14.6 8 15.5l.9-4z"/></svg>
                            </button>
                            <button type="button" class="akun-icon-btn" title="Ubah status">
                                <svg viewBox="0 0 24 24"><path d="M20 12a8 8 0 0 1-14 5.3"/><path d="M4 12a8 8 0 0 1 14-5.3"/><path d="M18 3v4h-4"/><path d="M6 21v-4h4"/></svg>
                            </button>
                            <button type="button" class="akun-icon-btn" title="Lihat">
                                <svg viewBox="0 0 24 24"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/><path d="M4 20 20 4"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Syalikha</td>
                    <td>Operator</td>
                    <td>Aktif</td>
                    <td>
                        <div class="akun-aksi">
                            <button type="button" class="akun-icon-btn" title="Edit">
                                <svg viewBox="0 0 24 24"><path d="M11 4H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><path d="M18.4 2.6a2 2 0 0 1 2.8 2.8L12 14.6 8 15.5l.9-4z"/></svg>
                            </button>
                            <button type="button" class="akun-icon-btn" title="Ubah status">
                                <svg viewBox="0 0 24 24"><path d="M20 12a8 8 0 0 1-14 5.3"/><path d="M4 12a8 8 0 0 1 14-5.3"/><path d="M18 3v4h-4"/><path d="M6 21v-4h4"/></svg>
                            </button>
                            <button type="button" class="akun-icon-btn" title="Lihat">
                                <svg viewBox="0 0 24 24"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/><path d="M4 20 20 4"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection