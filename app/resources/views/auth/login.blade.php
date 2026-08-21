<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang</title>
    <meta name="description" content="Masuk ke Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang.">

    {{-- Fonts: Plus Jakarta Sans & JetBrains Mono --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --login-bg-from: #002d5c;
            --login-bg-mid: #0057a8;
            --login-bg-to: #004a9e;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            margin: 0;
            background: radial-gradient(circle at 20% 20%, #003b78 0%, var(--login-bg-from) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
        }

        .login-container {
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            max-width: 960px;
            width: 100%;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.1);
            background: #ffffff;
        }

        /* ── Brand Panel (Left) ── */
        .login-brand {
            position: relative;
            background: linear-gradient(145deg, rgba(0, 87, 168, 0.95), rgba(0, 45, 92, 0.98));
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        /* Background Grid Overlay */
        .login-brand::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: 0.6;
            pointer-events: none;
        }

        .brand-header {
            position: relative;
            z-index: 1;
        }

        .login-logo {
            width: 56px;
            height: 56px;
            background: #ffffff;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            padding: 8px;
        }

        .login-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .login-brand h1 {
            font-size: 22px;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.35;
            margin: 0 0 12px;
            letter-spacing: -0.02em;
        }

        .login-brand p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.75);
            line-height: 1.6;
            margin: 0;
        }

        .feature-list {
            position: relative;
            z-index: 1;
            margin: 32px 0;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }

        .feature-icon-wrapper {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #ffffff;
        }

        .brand-footer {
            position: relative;
            z-index: 1;
            font-size: 11px;
            color: rgba(255, 255, 255, 0.45);
            letter-spacing: 0.02em;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 16px;
        }

        /* ── Form Card (Right) ── */
        .login-form-card {
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        .form-title {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 6px;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-sub {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }

        .input-relative {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-left {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            pointer-events: none;
            display: flex;
            align-items: center;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: all 0.2s ease;
            color: #1e293b;
            background: #f8fafc;
            box-sizing: border-box;
        }

        .form-input:focus {
            background: #ffffff;
            border-color: #0057a8;
            box-shadow: 0 0 0 4px rgba(0, 87, 168, 0.12);
        }

        .btn-toggle-pw {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            border-radius: 6px;
            transition: color 0.2s;
        }

        .btn-toggle-pw:hover {
            color: #475569;
        }

        .form-input.input-error {
            border-color: #ef4444 !important;
            background: #fef2f2 !important;
        }

        .error-msg {
            color: #ef4444;
            font-size: 12px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
            font-weight: 500;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
        }

        .remember-row input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            accent-color: #0057a8;
            cursor: pointer;
        }

        .remember-row label {
            font-size: 13px;
            color: #475569;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-login {
            width: 100%;
            padding: 13px 24px;
            background: #0057a8;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(0, 87, 168, 0.3);
        }

        .btn-login:hover {
            background: #004a9e;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(0, 87, 168, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* ── Modern Demo Account Chips ── */
        .demo-card {
            margin-top: 28px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px dashed #cbd5e1;
        }

        .demo-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 12px;
        }

        .demo-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .demo-chip {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 8px 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.15s ease;
            display: flex;
            flex-direction: column;
            gap: 2px;
            text-align: left;
        }

        .demo-chip:hover {
            border-color: #0057a8;
            background: #f0f7ff;
            transform: translateY(-1px);
        }

        .demo-role {
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .demo-user {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            color: #64748b;
        }

        .dot-role {
            width: 7px;
            height: 7px;
            border-radius: 50%;
        }

        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
            }

            .login-brand {
                display: none;
            }

            .login-form-card {
                padding: 36px 24px;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">

        {{-- Brand Panel (left) --}}
        <div class="login-brand">
            <div class="brand-header">
                <div class="login-logo">
                    {{-- Logo BPS Vector / Image --}}
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/28/Lambang_Badan_Pusat_Statistik_%28BPS%29_Indonesia.svg"
                        alt="Logo BPS">
                </div>
                <h1>Sistem Data Digital<br>Arsip Keuangan</h1>
                <p>BPS Kabupaten Subang — Pengelolaan dokumen pertanggungjawaban keuangan yang terstruktur, aman, dan
                    efisien.</p>
            </div>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon-wrapper">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </div>
                    Multi-file upload SPJ, BAPP & Kuitansi
                </div>

                <div class="feature-item">
                    <div class="feature-icon-wrapper">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    Pratinjau PDF langsung di browser
                </div>

                <div class="feature-item">
                    <div class="feature-icon-wrapper">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    Verifikasi pencairan oleh Bendahara
                </div>

                <div class="feature-item">
                    <div class="feature-icon-wrapper">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
                        </svg>
                    </div>
                    Navigasi hirarki POK 7-level dinamis
                </div>

                <div class="feature-item">
                    <div class="feature-icon-wrapper">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    Akses berbasis peran (RBAC)
                </div>
            </div>

            <div class="brand-footer">
                Tahun Anggaran 2026 • GG.2902 — BMA.006 Sensus Ekonomi
            </div>
        </div>

        {{-- Login Form (right) --}}
        <div class="login-form-card">
            <div class="form-title">
                Selamat Datang
                <span style="display:inline-block; transform: rotate(10deg);">👋</span>
            </div>
            <div class="form-sub">Masuk dengan NIP / Username dan password Anda</div>

            {{-- Session Error Alert --}}
            @if ($errors->any())
                <div style="background:#fef2f2; border: 1px solid #fca5a5; padding: 12px 14px; border-radius: 10px; margin-bottom: 20px; display:flex; align-items:center; gap:10px; color:#b91c1c;"
                    role="alert">
                    <svg style="width:20px; height:20px; flex-shrink:0;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span style="font-size:13px; font-weight:500;">{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                {{-- NIP / Username --}}
                <div class="form-group">
                    <label class="form-label" for="nip_username">NIP / Username</label>
                    <div class="input-relative">
                        <span class="input-icon-left">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                        <input type="text" id="nip_username" name="nip_username"
                            class="form-input {{ $errors->has('nip_username') ? 'input-error' : '' }}"
                            value="{{ old('nip_username') }}" placeholder="Masukkan NIP atau username" autofocus
                            autocomplete="username" required>
                    </div>
                    @error('nip_username')
                        <div class="error-msg" role="alert">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-relative">
                        <span class="input-icon-left">
                            <svg width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <input type="password" id="password" name="password"
                            class="form-input {{ $errors->has('password') ? 'input-error' : '' }}"
                            placeholder="••••••••" autocomplete="current-password" required>
                        <button type="button" class="btn-toggle-pw" onclick="togglePasswordVisibility()"
                            aria-label="Toggle Password">
                            <svg id="eye-icon" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-msg" role="alert">
                            <svg width="14" height="14" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="remember-row">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ingat saya di perangkat ini</label>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="btn-login">
                    <span>Masuk ke Sistem</span>
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            {{-- Demo Accounts (Interactive Quick-Fill) --}}
            <div class="demo-card">
                <div class="demo-title">Klik untuk Auto-fill Akun Demo:</div>
                <div class="demo-grid">
                    <div class="demo-chip" onclick="fillDemo('admin', 'admin123')">
                        <span class="demo-role" style="color: #ef4444;">
                            <span class="dot-role" style="background:#ef4444;"></span> Admin
                        </span>
                        <span class="demo-user">admin / admin123</span>
                    </div>

                    <div class="demo-chip" onclick="fillDemo('supervisor', 'super123')">
                        <span class="demo-role" style="color: #3b82f6;">
                            <span class="dot-role" style="background:#3b82f6;"></span> Supervisor
                        </span>
                        <span class="demo-user">supervisor / super123</span>
                    </div>

                    <div class="demo-chip" onclick="fillDemo('operator', 'oper123')">
                        <span class="demo-role" style="color: #10b981;">
                            <span class="dot-role" style="background:#10b981;"></span> Operator
                        </span>
                        <span class="demo-user">operator / oper123</span>
                    </div>

                    <div class="demo-chip" onclick="fillDemo('bendahara', 'bend123')">
                        <span class="demo-role" style="color: #f59e0b;">
                            <span class="dot-role" style="background:#f59e0b;"></span> Bendahara
                        </span>
                        <span class="demo-user">bendahara / bend123</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle Show/Hide Password
        function togglePasswordVisibility() {
            const pwInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (pwInput.type === 'password') {
                pwInput.type = 'text';
                eyeIcon.innerHTML =
                    `<path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.018 10.018 0 013.896-.863c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m-6.16-6.16a3 3 0 104.243 4.243M3 3l18 18"/>`;
            } else {
                pwInput.type = 'password';
                eyeIcon.innerHTML =
                    `<path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
            }
        }

        // Auto Fill Credentials
        function fillDemo(username, password) {
            document.getElementById('nip_username').value = username;
            document.getElementById('password').value = password;
        }
    </script>

</body>

</html>
