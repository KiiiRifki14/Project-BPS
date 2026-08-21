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
            --login-bg-from: rgba(0, 45, 92, 0.4);
            /* Biru Tua Terang (dengan transparansi untuk gedung) */
            --login-bg-to: rgba(0, 74, 158, 0.6);
            /* Biru Medium Terang */
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;

            /* --- Konfigurasi Split Background --- */
            /* 1. Gradasi Biru Penuh di Seluruh Background */
            /* 2. Foto Gedung Hanya Dimunculkan di Sisi Kiri (Lebar 50%) */
            background:
                linear-gradient(135deg, rgba(0, 45, 92, 0.4) 0%, rgba(0, 74, 158, 0.85) 100%),
                url('/images/BPS Subang.jpg') no-repeat left center;

            /*Mengatur Ukuran Gambar: Lebar 50% (sisi Kiri), Tinggi 100% Layar */
            background-size: 100% 100%, 100% 100%;
        }

        /* Layout diperramping/dikecilkan menyerupai persegi panjang presisi */
        .login-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-width: 820px;
            width: 100%;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.1);

            /* --- Perubahan untuk transparansi kartu login --- */
            background: rgba(255, 255, 255, 0.9);
            /* Sedikit transparan agar gedung terlihat */
            backdrop-filter: blur(1.5px);
            /* Efek blur halus di belakang kartu */
            -webkit-backdrop-filter: blur(1.5px);
        }

        /* ── Brand Panel (Left) ── */
        .login-brand {
            position: relative;
            background: linear-gradient(145deg, rgba(0, 87, 168, 0.9), rgba(0, 45, 92, 0.95));
            /* Menyesuaikan transparansi */
            padding: 36px 32px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        .login-brand::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            opacity: 0.6;
            pointer-events: none;
        }

        .brand-header {
            position: relative;
            z-index: 1;
        }

        .brand-title-row {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .login-logo-clean {
            width: 72px;
            height: 72px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
        }

        .login-logo-clean img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .login-brand h1 {
            font-size: 18px;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.25;
            margin: 0;
            letter-spacing: -0.01em;
        }

        .login-brand p {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.5;
            margin: 0;
        }

        .feature-list {
            position: relative;
            z-index: 1;
            margin: 24px 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.95);
            font-weight: 500;
        }

        .feature-icon-wrapper {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.15);
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
            font-size: 10px;
            color: rgba(255, 255, 255, 0.5);
            letter-spacing: 0.02em;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            padding-top: 12px;
        }

        /* ── Form Card (Right) ── */
        .login-form-card {
            padding: 36px 32px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: transparent;
            /* Kartu login mengikuti transparansi container */
        }

        .form-title {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 4px;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-sub {
            font-size: 12px;
            color: #475569;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .input-relative {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-left {
            position: absolute;
            left: 12px;
            color: #64748b;
            pointer-events: none;
            display: flex;
            align-items: center;
        }

        .form-input {
            width: 100%;
            padding: 10px 12px 10px 38px;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            outline: none;
            transition: all 0.2s ease;
            color: #0f172a;
            background: rgba(248, 250, 252, 0.8);
            box-sizing: border-box;
        }

        .form-input:focus {
            background: #ffffff;
            border-color: #0057a8;
            box-shadow: 0 0 0 3px rgba(0, 87, 168, 0.15);
        }

        .btn-toggle-pw {
            position: absolute;
            right: 10px;
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            border-radius: 4px;
        }

        .btn-toggle-pw:hover {
            color: #1e293b;
        }

        .form-input.input-error {
            border-color: #ef4444 !important;
            background: #fef2f2 !important;
        }

        .error-msg {
            color: #ef4444;
            font-size: 11px;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
            font-weight: 500;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
        }

        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            accent-color: #0057a8;
            cursor: pointer;
        }

        .remember-row label {
            font-size: 12px;
            color: #334155;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-login {
            width: 100%;
            padding: 11px 20px;
            background: #0057a8;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(0, 87, 168, 0.3);
        }

        .btn-login:hover {
            background: #004a9e;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 87, 168, 0.4);
        }

        /* ── Demo Account Box (Static / Non-clickable) ── */
        .demo-card {
            margin-top: 20px;
            padding: 12px;
            background: rgba(248, 250, 252, 0.7);
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            user-select: none;
        }

        .demo-title {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
            margin-bottom: 8px;
        }

        .demo-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }

        .demo-chip-static {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 6px 8px;
            border-radius: 6px;
            cursor: default;
            display: flex;
            flex-direction: column;
            gap: 1px;
            text-align: left;
        }

        .demo-role {
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .demo-user {
            font-family: 'JetBrains Mono', monospace;
            font-size: 9px;
            color: #475569;
        }

        .dot-role {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
                max-width: 420px;
            }

            .login-brand {
                display: none;
            }

            .login-form-card {
                padding: 28px 20px;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">

        {{-- Brand Panel (left) --}}
        <div class="login-brand">
            <div class="brand-header">
                {{-- Row: Logo BPS Transparan (Tanpa BG) + Judul Sejajar --}}
                <div class="brand-title-row">
                    <div class="login-logo-clean">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/28/Lambang_Badan_Pusat_Statistik_%28BPS%29_Indonesia.svg"
                            alt="Logo BPS">
                    </div>
                    <h1>Sistem Data Digital<br>Arsip Keuangan</h1>
                </div>

                <p>BPS Kabupaten Subang — Pengelolaan dokumen pertanggungjawaban keuangan yang terstruktur, aman, dan
                    efisien.</p>
            </div>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon-wrapper">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </div>
                    Multi-file upload SPJ, BAPP & Kuitansi
                </div>

                <div class="feature-item">
                    <div class="feature-icon-wrapper">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
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
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    Verifikasi pencairan oleh Bendahara
                </div>

                <div class="feature-item">
                    <div class="feature-icon-wrapper">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
                        </svg>
                    </div>
                    Navigasi hirarki POK 7-level dinamis
                </div>

                <div class="feature-item">
                    <div class="feature-icon-wrapper">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
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
                <div style="background:#fef2f2; border: 1px solid #fca5a5; padding: 10px 12px; border-radius: 8px; margin-bottom: 16px; display:flex; align-items:center; gap:8px; color:#b91c1c;"
                    role="alert">
                    <svg style="width:18px; height:18px; flex-shrink:0;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span style="font-size:12px; font-weight:500;">{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                {{-- NIP / Username --}}
                <div class="form-group">
                    <label class="form-label" for="nip_username">NIP / Username</label>
                    <div class="input-relative">
                        <span class="input-icon-left">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
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
                            <svg width="12" height="12" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
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
                            <svg width="16" height="16" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <input type="password" id="password" name="password"
                            class="form-input {{ $errors->has('password') ? 'input-error' : '' }}"
                            placeholder="••••••••" autocomplete="current-password" required>
                        <button type="button" class="btn-toggle-pw" onclick="togglePasswordVisibility()"
                            aria-label="Toggle Password">
                            <svg id="eye-icon" width="16" height="16" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-msg" role="alert">
                            <svg width="12" height="12" fill="none" stroke="currentColor"
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
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            {{-- Demo Accounts (Statis - Tidak Bisa Diklik) --}}
            <div class="demo-card">
                <div class="demo-title">Demo Akun (Development):</div>
                <div class="demo-grid">
                    <div class="demo-chip-static">
                        <span class="demo-role" style="color: #ef4444;">
                            <span class="dot-role" style="background:#ef4444;"></span> Admin
                        </span>
                        <span class="demo-user">admin / admin123</span>
                    </div>

                    <div class="demo-chip-static">
                        <span class="demo-role" style="color: #3b82f6;">
                            <span class="dot-role" style="background:#3b82f6;"></span> Supervisor
                        </span>
                        <span class="demo-user">supervisor / super123</span>
                    </div>

                    <div class="demo-chip-static">
                        <span class="demo-role" style="color: #10b981;">
                            <span class="dot-role" style="background:#10b981;"></span> Operator
                        </span>
                        <span class="demo-user">operator / oper123</span>
                    </div>

                    <div class="demo-chip-static">
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
    </script>

</body>

</html>
