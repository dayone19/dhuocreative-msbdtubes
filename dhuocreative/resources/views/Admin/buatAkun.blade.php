@extends('layouts.admin')

@section('content')
<div class="akun-form-page">
    <h1 class="akun-form-title">Buat Akun</h1>
    <form action = "#" method = "POST" >
        @csrf

        <div class="akun-form-card">
            <div class="akun-form-grid">

                <div class="akun-form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" value="Syalikhafaradhiya">
                </div>

                <div class="akun-form-group">
                    <label for="telepon">No.telepon</label>
                    <input type="text" id="telepon" name="telepon" value="085207125180">
                </div>

                <div class="akun-form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role">
                        <option value="">Pilih role</option>
                        <option value="admin">Admin</option>
                        <option value="operator">Operator</option>
                        <option value="tentor">Tentor</option>
                        <option value="siswa">Siswa</option>
                    </select>
                </div>

                <div class="akun-form-group">
                    <label for="tahun">Tahun Masuk</label>
                    <input type="number" id="tahun" name="tahun" value="2026">
                </div>

                <div class="akun-form-group">
                    <label for="password">Password</label>
                    <div class="akun-pass-wrap">
                        <input type="password" id="password" name="password" placeholder="Minimal 8 karakter">
                        <button type="button" class="akun-eye-btn" data-target="password" aria-label="Tampilkan password">
                            <svg viewBox="0 0 24 24"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <div class="akun-form-group">
                    <label for="password_confirmation">Konfirmasi password</label>
                    <div class="akun-pass-wrap">
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password">
                        <button type="button" class="akun-eye-btn" data-target="password_confirmation" aria-label="Tampilkan password">
                            <svg viewBox="0 0 24 24"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="akun-form-actions">
            <a href="#" class="akun-btn akun-btn-batal">Batal</a>
            <button type="submit" class="akun-btn akun-btn-simpan">Simpan Akun</button>
        </div>
    </form>
</div>
<script>
    document.querySelectorAll('.akun-eye-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.dataset.target);
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });
</script>
@endsection