
@extends('layouts.admin')

@section('content')
<div class="akun-form-page">

    <h1 class="akun-form-title">Buat Akun</h1>

    {{-- PESAN VALIDASI --}}
    @if ($errors->any())
        <div class="akun-alert">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.buatAkun.store') }}" method="POST">
        @csrf

        <div class="akun-form-card">
            <div class="akun-form-grid">

                <div class="akun-form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        value="{{ old('nama') }}"
                        placeholder="Masukkan nama lengkap"
                        required
                    >
                </div>

                <div class="akun-form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email"
                        required
                    >
                </div>

                <div class="akun-form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="">Pilih role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                        <option value="tentor" {{ old('role') == 'tentor' ? 'selected' : '' }}>Tentor</option>
                        <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                    </select>
                </div>

                <div class="akun-form-group">
                    <label for="telepon">No. Telepon</label>
                    <input
                        type="text"
                        id="telepon"
                        name="no_telp"
                        value="{{ old('no_telp') }}"
                        placeholder="Masukkan no. telepon"
                    >
                </div>

                <div class="akun-form-group">
                    <label for="tahun">Tahun Masuk</label>
                    <input
                        type="number"
                        id="tahun"
                        name="tahun_masuk"
                        value="{{ old('tahun_masuk') }}"
                        placeholder="Masukkan tahun masuk"
                        min="2000"
                        max="2100"
                        required
                    >
                </div>

            </div>
        </div>

        <div class="akun-form-actions">
            <a href="{{ route('admin.manajemenAkun') }}" class="akun-btn akun-btn-batal">
                Batal
            </a>

            <button type="submit" class="akun-btn akun-btn-simpan">
                Simpan Akun
            </button>
        </div>
    </form>

    {{-- POP-UP BERHASIL --}}
    @if (session('success'))
        <div class="akun-popup-overlay" id="popupBerhasil">
            <div class="akun-popup">
                <div class="akun-popup-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M5 12.5L10 17L19 7"/>
                    </svg>
                </div>

                <h2>Berhasil!</h2>
                <p>{{ session('success') }}</p>

                <button type="button" id="btnTutupPopup">Oke</button>
            </div>
        </div>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // POP-UP BERHASIL
    const popup = document.getElementById('popupBerhasil');
    const btnTutupPopup = document.getElementById('btnTutupPopup');

    if (popup) {
        btnTutupPopup.addEventListener('click', function () {
            popup.remove();
        });

        popup.addEventListener('click', function (e) {
            if (e.target === popup) {
                popup.remove();
            }
        });

        setTimeout(function () {
            if (popup) {
                popup.remove();
            }
        }, 3000);
    }

});
</script>
@endsection
