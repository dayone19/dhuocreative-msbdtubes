<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    @vite(['resources/css/login.css', 'resources/js/app.js'])
</head>

<body>

    <div class="login-page">
        <div class="main-section">

            <div class="forgot-card">

                <h2>Lupa Password?</h2>

                <form id="forgotForm">
                    <!-- STEP 1 -->
                    <div id="step1">
                        <div class="form-group">
                            <label for="no_registrasi">
                                No Registrasi
                            </label>
                            <input
                                type="text"
                                id="no_registrasi"
                                name="no_registrasi"
                                placeholder="Masukkan Nomor Registrasi"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="nama">
                                Nama
                            </label>
                            <input
                                type="text"
                                id="nama"
                                name="nama"
                                placeholder="Masukkan Nama"
                                required>
                        </div>

                        <button
                            type="button"
                            class="login-button"
                            id="sendReset">
                            Get Verification Code
                        </button>
                    </div>

                    <!-- STEP 2 -->
                    <div id="step2" style="display: none;">
                        <div class="form-group">
                            <label for="verification_code">
                                Verification Code
                            </label>
                            <input
                                type="text"
                                id="verification_code"
                                placeholder="Masukkan kode verifikasi"
                                maxlength="6">
                        </div>
                        <button
                            type="button"
                            class="login-button"
                            id="verifyCode">
                            Verify Code
                        </button>
                    </div>

                    <!-- STEP 3 -->
                    <div id="step3" style="display: none;">
                        <div class="form-group">
                            <label for="new_password">
                                Password Baru
                            </label>
                            <input
                                type="password"
                                id="new_password"
                                placeholder="Masukkan Password Baru">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">
                                Konfirmasi Password
                            </label>
                            <input
                                type="password"
                                id="confirm_password"
                                placeholder="Ulangi Password Baru">
                        </div>
                        <button
                            type="button"
                            class="login-button"
                            id="resetPassword">
                            Reset Password
                        </button>
                    </div>
                </form>

                <a
                    href="{{ route('login') }}"
                    class="back-login">

                    ← Kembali

                </a>

            </div>

        </div>
    </div>

    <script>
        const sendReset = document.getElementById('sendReset');
        const verifyCode = document.getElementById('verifyCode');
        const resetPassword = document.getElementById('resetPassword');

        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');

        let verificationCode = '';

        sendReset.addEventListener('click', function () {

            const noRegistrasi =
                document.getElementById('no_registrasi').value.trim();

            const nama =
                document.getElementById('nama').value.trim();

            if (noRegistrasi === '') {

                alert('Silakan masukkan nomor registrasi.');
                document.getElementById('no_registrasi').focus();
                return;
            }

            if (nama === '') {

                alert('Silakan masukkan nama.');
                document.getElementById('nama').focus();
                return;
            }

            verificationCode =
                Math.floor(100000 + Math.random() * 900000).toString();

            alert(
                'Kode verifikasi berhasil dikirim!\n\n' +
                'Kode kamu: ' + verificationCode
            );

            step1.style.display = 'none';
            step2.style.display = 'block';

        });


        verifyCode.addEventListener('click', function () {

            const inputCode =
                document.getElementById('verification_code').value.trim();

            if (inputCode === '') {

                alert('Silakan masukkan kode verifikasi.');
                document.getElementById('verification_code').focus();
                return;
            }

            if (inputCode !== verificationCode) {

                alert('Kode verifikasi salah.');
                document.getElementById('verification_code').focus();
                return;

            }

            alert('Kode verifikasi berhasil!');

            step2.style.display = 'none';
            step3.style.display = 'block';

        });


        resetPassword.addEventListener('click', function () {

            const newPassword =
                document.getElementById('new_password').value;

            const confirmPassword =
                document.getElementById('confirm_password').value;

            if (newPassword === '') {

                alert('Silakan masukkan password baru.');
                document.getElementById('new_password').focus();
                return;
            }

            if (newPassword.length < 6) {

                alert('Password minimal 6 karakter.');
                document.getElementById('new_password').focus();
                return;
            }

            if (confirmPassword === '') {

                alert('Silakan konfirmasi password.');
                document.getElementById('confirm_password').focus();
                return;
            }

            if (newPassword !== confirmPassword) {

                alert('Konfirmasi password tidak sama.');
                document.getElementById('confirm_password').focus();
                return;
            }

            alert('Password berhasil diubah!');

            window.location.href = "{{ route('login') }}";

        });

    </script>

</body>
</html>