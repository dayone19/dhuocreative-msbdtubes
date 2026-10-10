<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DhuoCreative Portal</title>
    @vite(['resources/css/login.css', 'resources/js/app.js'])
</head>

<body>

    <div class="login-page">

        <div class="main-section">

            <div class="brand">
                <svg class="brand-svg" viewBox="0 0 500 180">
                    <defs>
                        <path
                            id="titleCurve"
                            d="M 75,185 Q 400,15 1000,110"
                        />
                        <path
                            id="subtitleCurve"
                            d="M 180,230 Q 400,20 800,280"
                        />
                    </defs>

                    <text class="brand-title-svg">
                        <textPath href="#titleCurve" startOffset="30%">
                            DhuoCreative Portal
                        </textPath>
                    </text>

                    <text class="brand-subtitle-svg">
                        <textPath href="#subtitleCurve" startOffset="30%" dy="34">
                            KURSUS KOMPUTER
                        </textPath>
                    </text>
                </svg>
            </div>

            <div class="login-card">

                <div class="role-buttons">
                    <button type="button"
                            class="role-btn active"
                            data-role="siswa">
                        Siswa
                    </button>

                    <button type="button"
                            class="role-btn"
                            data-role="tentor">
                        Tentor
                    </button>

                    <button type="button"
                            class="role-btn"
                            data-role="operator">
                        Operator
                    </button>

                    <button type="button"
                            class="role-btn"
                            data-role="admin">
                        Admin
                    </button>
                </div>

                <form action="{{ route('login.process') }}" method="POST">
                    @csrf
                    
                @if ($errors->any())
                    <div class="error-message">
                        {{ $errors->first() }}
                    </div>
                @endif

                    <input type="hidden"
                           name="role"
                           id="selectedRole"
                           value="siswa">
                    <div class="form-group">
                        <label for="email">
                            Email
                        </label>
                        <input type="text"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               autocomplete="username"
                               required>
                    </div>

                    <div class="form-group password-group">

                    <label for="password">
                        Password
                    </label>

                    <div class="password-input-wrapper">
                        <input type="password"
                            id="password"
                            name="password"
                            autocomplete="current-password"
                            required>
                        <button type="button"
                                class="toggle-password"
                                id="togglePassword"
                                aria-label="Show password">
                            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg id="eyeClosed"
                                xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                                <path d="M3 3l18 18"/>
                                <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                                <path d="M9.9 4.2A10.5 10.5 0 0 1 12 4c6.5 0 10 8 10 8a17.5 17.5 0 0 1-3.1 4.2"/>
                                <path d="M6.6 6.6C3.8 8.5 2 12 2 12s3.5 8 10 8c1.5 0 2.8-.3 4-.8"/>
                            </svg>
                        </button>
                    </div>

                    <a href="{{ route('password.request') }}" class="forgot-password">
                        Lupa password
                    </a>

                </div>

                    <button type="submit" class="login-button">
                        Log In
                    </button>

                </form>

            </div>

        </div>

        <!-- FOOTER -->
        <footer class="footer">

            <div class="social-item">
                </span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#e94c9b" class="bi bi-instagram" viewBox="0 0 16 16">
                    <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/>
                    </svg>
                <span>
                    dhuocreative
                </span>
            </div>


            <div class="social-item">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#1877f2" class="bi bi-facebook" viewBox="0 0 16 16">
                    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
                    </svg>
                </span>
                <span>
                    Dhuo Creative
                </span>
            </div>

        </footer>

    </div>


    
<script>

    // ROLE BUTTON
    const roleButtons = document.querySelectorAll('.role-btn');
    const selectedRole = document.getElementById('selectedRole');

    roleButtons.forEach(button => {

        button.addEventListener('click', function () {

            roleButtons.forEach(btn => {
                btn.classList.remove('active');
            });

            this.classList.add('active');

            selectedRole.value = this.dataset.role;

        });

    });

    // SHOW / HIDE PASSWORD
    const password = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    togglePassword.addEventListener('click', function () {

        if (password.type === 'password') {

            // Tampilkan password
            password.type = 'text';

            eyeOpen.style.display = 'none';
            eyeClosed.style.display = 'block';

            this.setAttribute(
                'aria-label',
                'Hide password'
            );

        } else {

            // Sembunyikan password
            password.type = 'password';

            eyeOpen.style.display = 'block';
            eyeClosed.style.display = 'none';

            this.setAttribute(
                'aria-label',
                'Show password'
            );

        }

    });

</script>

</body>
</html>