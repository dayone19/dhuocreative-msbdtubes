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
                <div id="form-message" class="error-message" style="display: none;"></div>

                <form id="forgotForm" method="POST"
                      action="{{route('password.email')}}">
                      @csrf
                    <!-- STEP 1 -->
                    <div id="step1">
                        <div class="form-group">
                            <label for="email">
                                Email
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="Masukkan Email Kamu"
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

        const formMessage = document.getElementById('form-message');
        function showMessage(message) {
            formMessage.textContent = message;
            formMessage.style.display = 'block';
        }

        sendReset.addEventListener('click', async function () {
            const email = document.getElementById('email').value.trim();
            const nama = document.getElementById('nama').value.trim();

            formMessage.style.display = 'none';

            if (email === '' || nama === '') {
                showMessage('Silahkan masukkan email dan nama.');
                return;
            }

            try {
                const response = await fetch("{{route('password.email')}}", {
                    method: "POST",
                    headers: {
                        'Content-Type' : 'application/json',
                        'Accept' : 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body:JSON.stringify({ email, nama })
                });

                const data = await response.json();
                console.log('Status:', response.status);
                console.log('Data:', data);

                if (!response.ok) {
                    const message = data.errors? Object.values(data.errors).flat()[0]
                    : (data.message || 'Gagal mengirim kode OTP.');

                    showMessage(message);
                    return;
                }

                showMessage('Kode OTP berhasil dikirim ke email kamu.');
                step1.style.display = 'none';
                step2.style.display = 'block';

            } catch (error) {
                showMessage('Terjadi kesalahan koneksi. Silahkan coba lagi.');
                console.error(error);
            }

        });


        verifyCode.addEventListener('click', async function () {
            const email = document.getElementById('email').value.trim();
            const inputCode = document.getElementById('verification_code').value.trim();

            formMessage.style.display = 'none';

            if (inputCode === '') {
                showMessage('Silakan masukkan kode verifikasi.');
                document.getElementById('verification_code').focus();
                return;
            }

            if (!/^\d{6}$/.test(inputCode)) {
                showMessage('Kode verifikasi harus terdiri dari 6 angka.');
                document.getElementById('verification_code').focus();
                return;
            }

            try {
                const response = await fetch("{{ route('password.verify') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: email,
                        verification_code: inputCode
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    const message = data.errors
                        ? Object.values(data.errors).flat()[0]
                        : (data.message || 'Verifikasi OTP gagal.');

                    showMessage(message);
                    document.getElementById('verification_code').focus();
                    return;
                }

                showMessage('Kode OTP berhasil diverifikasi!');
                step2.style.display = 'none';
                step3.style.display = 'block';

            } catch (error) {
                showMessage('Terjadi kesalahan koneksi. Silakan coba lagi.');
                console.error(error);
            }
        });


        resetPassword.addEventListener('click', async function () {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const email = document.getElementById('email').value.trim();

            formMessage.style.display = 'none';
            
            if (newPassword === '') {
                showMessage('Silakan masukkan password baru.');
                document.getElementById('new_password').focus();
                return;
            }

            if (newPassword.length < 7) {
                showMessage('Password minimal 7 karakter.');
                document.getElementById('new_password').focus();
                return;
            }

            if (!/[A-Z]/.test(newPassword)) {
                showMessage('Password harus memiliki minimal 1 huruf besar.');
                document.getElementById('new_password').focus();
                return;
            }

            if (!/[0-9]/.test(newPassword)) {
                showMessage('Password harus memiliki minimal 1 angka.');
                document.getElementById('new_password').focus();
                return;
            }

            if (!/^[a-zA-Z0-9]+$/.test(newPassword)) {
                showMessage('Password hanya boleh berisi huruf dan angka, tanpa simbol atau spasi.');
                document.getElementById('new_password').focus();
                return;
            }

            if (confirmPassword === '') {
                showMessage('Silakan konfirmasi password.');
                document.getElementById('confirm_password').focus();
                return;
            }

            if (newPassword !== confirmPassword) {
                showMessage('Konfirmasi password tidak sama.');
                document.getElementById('confirm_password').focus();
                return;
            }

            try {
                const response = await fetch("{{ route('password.reset') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: email,
                        new_password: newPassword,
                        confirm_password: confirmPassword
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    const message = data.errors
                        ? Object.values(data.errors).flat()[0]
                        : (data.message || 'Gagal mengubah password.');

                    showMessage(message);
                    return;
                }

                alert(data.message);
                window.location.href = "{{ route('login') }}";

            } catch (error) {
                showMessage('Terjadi kesalahan koneksi. Silakan coba lagi.');
                console.error(error);
            }
        });

    </script>

</body>
</html>