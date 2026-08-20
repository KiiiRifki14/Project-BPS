<!DOCTYPE html>
{{-- Dark mode: tambah class .dark pada <html> untuk paksa dark, .light untuk paksa light --}}
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang</title>
    <meta name="description" content="Masuk ke Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang.">


    {{-- Fonts: Plus Jakarta Sans (bukan Inter) — sesuai DESIGN.md Section 2 --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── Login Page Styles — semua menggunakan CSS token SAKDI ── */
        :root {
            /* Login-specific variables */
            --login-bg-from: var(--color-primary-900);   /* #002D5C */
            --login-bg-mid:  var(--color-primary);        /* #0057A8 */
            --login-bg-to:   var(--color-primary-700);    /* #004A9E */
        }

        body {
            font-family: var(--font-sans);
            min-height: 100vh;
            background: linear-gradient(135deg, var(--login-bg-from) 0%, var(--login-bg-mid) 50%, var(--login-bg-to) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-width: 900px;
            width: 100%;
            border-radius: var(--r-lg);
            overflow: hidden;
            box-shadow: var(--shadow-4), 0 32px 80px rgba(0,0,0,0.4);
        }

        .login-brand {
            background: linear-gradient(175deg, rgba(255,255,255,0.12), rgba(255,255,255,0.04));
            backdrop-filter: blur(10px);
            padding: var(--sp-12) var(--sp-10);
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-right: 1px solid rgba(255,255,255,0.15);
        }

        .login-form-card {
            background: var(--color-white);
            padding: var(--sp-12) var(--sp-10);
        }

        .login-logo {
            width: 64px;
            height: 64px;
            background: var(--color-white);
            border-radius: var(--r-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: var(--sp-6);
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        }

        .login-brand h1 {
            font-size: var(--text-xl);
            font-weight: 800;
            color: #fff;
            line-height: 1.3;
            margin: 0 0 var(--sp-3);
        }
        .login-brand p {
            font-size: var(--text-sm);
            color: rgba(255,255,255,0.75);
            line-height: 1.7;
        }

        .feature-list { margin-top: var(--sp-6); display: flex; flex-direction: column; gap: var(--sp-2); }
        .feature-item {
            display: flex;
            align-items: center;
            gap: var(--sp-2);
            font-size: var(--text-xs);
            color: rgba(255,255,255,0.85);
        }
        .feature-icon { font-size: 18px; }

        .form-title {
            font-size: var(--text-xl);
            font-weight: 800;
            color: var(--color-neutral-900);
            margin: 0 0 var(--sp-1);
        }
        .form-sub {
            font-size: var(--text-sm);
            color: var(--color-neutral-500);
            margin-bottom: var(--sp-6);
        }
        .form-group { margin-bottom: var(--sp-4); }

        /* Form label — SAKDI token */
        .form-label {
            display: block;
            font-size: var(--text-sm);
            font-weight: 500;
            color: var(--color-neutral-700);
            margin-bottom: var(--sp-1);
        }

        /* Form input — sesuai DESIGN.md input component */
        .form-input {
            width: 100%;
            padding: var(--sp-3) var(--sp-4);
            border: 1.5px solid var(--color-neutral-300);
            border-radius: var(--r-sm);
            font-size: var(--text-base);
            font-family: var(--font-sans);
            outline: none;
            transition: border-color var(--dur-fast) var(--ease-standard),
                        box-shadow var(--dur-fast) var(--ease-standard);
            min-height: 44px;
            color: var(--color-neutral-700);
            background: var(--color-white);
        }
        .form-input:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px var(--color-primary-100);
        }

        /* Input error state — DESIGN.md input._error */
        .form-input.input-error {
            border-color: var(--color-error) !important;
            box-shadow: 0 0 0 3px var(--color-error-light) !important;
        }
        .error-msg {
            color: var(--color-error);
            font-size: var(--text-xs);
            margin-top: var(--sp-1);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Login button — SAKDI button-primary */
        .btn-login {
            width: 100%;
            padding: var(--sp-3) var(--sp-6);
            background: var(--color-primary);
            color: #fff;
            border: none;
            border-radius: var(--r-md);
            font-size: var(--text-sm);
            font-weight: 700;
            cursor: pointer;
            font-family: var(--font-sans);
            transition: background-color var(--dur-fast) var(--ease-standard),
                        transform var(--dur-fast) var(--ease-spring),
                        box-shadow var(--dur-fast) var(--ease-standard);
            box-shadow: 0 4px 16px rgba(0,87,168,0.35);
            min-height: 44px;
        }
        .btn-login:hover {
            background: var(--color-primary-700);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(0,87,168,0.45);
        }
        .btn-login:active { transform: translateY(0); background: var(--color-primary-900); }
        .btn-login:focus-visible {
            outline: 3px solid var(--color-primary-400);
            outline-offset: 2px;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: var(--sp-2);
            margin-bottom: var(--sp-5);
        }
        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--color-primary);  /* #0057A8 */
            cursor: pointer;
        }
        .remember-row label {
            font-size: var(--text-sm);
            color: var(--color-neutral-500);
            cursor: pointer;
        }

        /* Demo accounts card */
        .demo-card {
            margin-top: var(--sp-6);
            padding: var(--sp-4);
            background: var(--color-neutral-50);
            border-radius: var(--r-md);
            border: 1px solid var(--color-neutral-300);
        }
        .demo-title {
            font-size: var(--text-xs);
            font-weight: 700;
            color: var(--color-neutral-700);
            margin-bottom: var(--sp-2);
        }
        .demo-list {
            font-size: var(--text-xs);
            color: var(--color-neutral-500);
            line-height: 1.9;
        }
        .demo-code {
            background: var(--color-neutral-100);
            padding: 1px 4px;
            border-radius: var(--r-xs);
            font-family: var(--font-mono);
            font-size: 10px;
        }

        @media (max-width: 640px) {
            .login-container { grid-template-columns: 1fr; }
            .login-brand { display: none; }
            .login-form-card { padding: var(--sp-8) var(--sp-6); }
        }
    </style>
</head>
<body>
<div class="login-container">

    {{-- Brand Panel (left) --}}
    <div class="login-brand">
        <div class="login-logo">📊</div>
        <h1>Sistem Data Digital Arsip Keuangan</h1>

        <p>BPS Kabupaten Subang — Pengelolaan dokumen pertanggungjawaban keuangan yang terstruktur, aman, dan efisien.</p>

        <div class="feature-list">
            <div class="feature-item"><span class="feature-icon">📂</span> Multi-file upload SPJ, BAPP &amp; Kuitansi</div>
            <div class="feature-item"><span class="feature-icon">👁️</span> Pratinjau PDF langsung di browser</div>
            <div class="feature-item"><span class="feature-icon">✅</span> Verifikasi pencairan oleh Bendahara</div>
            <div class="feature-item"><span class="feature-icon">🌳</span> Navigasi hirarki POK 7-level dinamis</div>
            <div class="feature-item"><span class="feature-icon">🔒</span> Akses berbasis peran (RBAC)</div>
        </div>

        <div style="margin-top:auto; padding-top: var(--sp-8); font-size: 11px; color: rgba(255,255,255,0.4);">
            Tahun Anggaran 2026 • GG.2902 — BMA.006 Sensus Ekonomi
        </div>
    </div>

    {{-- Login Form (right) --}}
    <div class="login-form-card">
        <div class="form-title">Selamat Datang 👋</div>
        <div class="form-sub">Masuk dengan NIP / Username dan password Anda</div>

        {{-- Session Error Alert --}}
        @if ($errors->any())
        <div class="sakdi-alert sakdi-alert-error mb-4" role="alert">
            <svg class="sakdi-alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm">{{ $errors->first() }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            {{-- NIP / Username --}}
            <div class="form-group">
                <label class="form-label sakdi-label" for="nip_username">NIP / Username</label>
                <input type="text"
                       id="nip_username"
                       name="nip_username"
                       class="form-input {{ $errors->has('nip_username') ? 'input-error' : '' }}"
                       value="{{ old('nip_username') }}"
                       placeholder="Masukkan NIP atau username"
                       autofocus
                       autocomplete="username"
                       aria-describedby="{{ $errors->has('nip_username') ? 'nip-error' : '' }}"
                       required>
                @error('nip_username')
                    <div class="error-msg" id="nip-error" role="alert">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label class="form-label sakdi-label" for="password">Password</label>
                <input type="password"
                       id="password"
                       name="password"
                       class="form-input {{ $errors->has('password') ? 'input-error' : '' }}"
                       placeholder="••••••••"
                       autocomplete="current-password"
                       aria-describedby="{{ $errors->has('password') ? 'pw-error' : '' }}"
                       required>
                @error('password')
                    <div class="error-msg" id="pw-error" role="alert">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="btn-login">
                🔐 Masuk ke Sistem
            </button>
        </form>

        {{-- Demo Accounts (Development) --}}
        <div class="demo-card">
            <div class="demo-title">Demo Akun (Development):</div>
            <div class="demo-list">
                <div>🔴 <code class="demo-code">admin</code> / <code class="demo-code">admin123</code> — Admin</div>
                <div>🔵 <code class="demo-code">supervisor</code> / <code class="demo-code">super123</code> — Supervisor</div>
                <div>🟢 <code class="demo-code">operator</code> / <code class="demo-code">oper123</code> — Operator</div>
                <div>🟡 <code class="demo-code">bendahara</code> / <code class="demo-code">bend123</code> — Bendahara</div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
