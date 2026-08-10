<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Arsip Keuangan BPS Kabupaten Subang</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #001a5c 0%, #003087 50%, #0d47a1 100%);
            display: flex; align-items: center; justify-content: center; padding: 20px;
        }
        .login-container {
            display: grid; grid-template-columns: 1fr 1fr;
            max-width: 900px; width: 100%; border-radius: 20px; overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,.5);
        }
        .login-brand {
            background: linear-gradient(175deg, rgba(255,255,255,.12), rgba(255,255,255,.04));
            backdrop-filter: blur(10px);
            padding: 48px 40px;
            display: flex; flex-direction: column; justify-content: center;
            border-right: 1px solid rgba(255,255,255,.15);
        }
        .login-form-card {
            background: #fff; padding: 48px 40px;
        }
        .login-logo {
            width: 64px; height: 64px; background: #fff; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 32px; margin-bottom: 24px;
            box-shadow: 0 8px 24px rgba(0,0,0,.2);
        }
        .login-brand h1 { font-size: 22px; font-weight: 800; color: #fff; line-height: 1.3; margin: 0 0 12px; }
        .login-brand p { font-size: 13.5px; color: rgba(255,255,255,.7); line-height: 1.7; }
        .feature-list { margin-top: 28px; display: flex; flex-direction: column; gap: 10px; }
        .feature-item { display: flex; align-items: center; gap: 10px; font-size: 12.5px; color: rgba(255,255,255,.8); }
        .feature-icon { font-size: 18px; }

        .form-title { font-size: 20px; font-weight: 800; color: #0f172a; margin: 0 0 6px; }
        .form-sub { font-size: 13px; color: #64748b; margin-bottom: 28px; }
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 12.5px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .form-input {
            width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: 14px; font-family: inherit; outline: none; transition: border-color .15s;
        }
        .form-input:focus { border-color: #003087; box-shadow: 0 0 0 3px rgba(0,48,135,.1); }
        .btn-login {
            width: 100%; padding: 12px; background: linear-gradient(135deg, #003087, #0d47a1);
            color: #fff; border: none; border-radius: 10px; font-size: 14px; font-weight: 700;
            cursor: pointer; font-family: inherit; transition: all .2s;
            box-shadow: 0 4px 16px rgba(0,48,135,.4);
        }
        .btn-login:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(0,48,135,.5); }
        .error-msg { color: #dc2626; font-size: 12px; margin-top: 4px; }
        .remember-row { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; }
        .remember-row label { font-size: 13px; color: #64748b; cursor: pointer; }

        @media (max-width: 640px) {
            .login-container { grid-template-columns: 1fr; }
            .login-brand { display: none; }
        }
    </style>
</head>
<body>
<div class="login-container">

    {{-- Brand Panel --}}
    <div class="login-brand">
        <div class="login-logo">📊</div>
        <h1>Sistem Data Digital Arsip Keuangan</h1>
        <p>BPS Kabupaten Subang — Pengelolaan dokumen pertanggungjawaban keuangan yang terstruktur, aman, dan efisien.</p>

        <div class="feature-list">
            <div class="feature-item"><span class="feature-icon">📂</span> Multi-file upload SPJ, BAPP & Kuitansi</div>
            <div class="feature-item"><span class="feature-icon">👁️</span> Pratinjau PDF langsung di browser</div>
            <div class="feature-item"><span class="feature-icon">✅</span> Verifikasi pencairan oleh Bendahara</div>
            <div class="feature-item"><span class="feature-icon">🌳</span> Navigasi hirarki POK 7-level dinamis</div>
            <div class="feature-item"><span class="feature-icon">🔒</span> Akses berbasis peran (RBAC)</div>
        </div>

        <div style="margin-top:auto;padding-top:32px;font-size:11px;color:rgba(255,255,255,.4);">
            Tahun Anggaran 2026 • GG.2902 — BMA.006 Sensus Ekonomi
        </div>
    </div>

    {{-- Login Form --}}
    <div class="login-form-card">
        <div class="form-title">Selamat Datang 👋</div>
        <div class="form-sub">Masuk dengan NIP / Username dan password Anda</div>

        {{-- Session Error --}}
        @if ($errors->any())
        <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:13px;color:#991b1b;">
            ❌ {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="nip_username">NIP / Username</label>
                <input type="text" id="nip_username" name="nip_username" class="form-input"
                       value="{{ old('nip_username') }}" placeholder="Masukkan NIP atau username"
                       autofocus autocomplete="username" required>
                @error('nip_username')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input"
                       placeholder="••••••••" autocomplete="current-password" required>
                @error('password')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember" style="width:16px;height:16px;accent-color:#003087;">
                <label for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="btn-login">
                🔐 Masuk ke Sistem
            </button>
        </form>

        <div style="margin-top:24px;padding:14px;background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;">
            <div style="font-size:11.5px;font-weight:700;color:#374151;margin-bottom:8px;">Demo Akun (Development):</div>
            <div style="font-size:11px;color:#64748b;line-height:1.9;">
                <div>🔴 <code style="background:#e8e8e8;padding:1px 4px;border-radius:3px;">admin</code> / <code style="background:#e8e8e8;padding:1px 4px;border-radius:3px;">admin123</code> — Admin</div>
                <div>🔵 <code style="background:#e8e8e8;padding:1px 4px;border-radius:3px;">supervisor</code> / <code style="background:#e8e8e8;padding:1px 4px;border-radius:3px;">super123</code> — Supervisor</div>
                <div>🟢 <code style="background:#e8e8e8;padding:1px 4px;border-radius:3px;">operator</code> / <code style="background:#e8e8e8;padding:1px 4px;border-radius:3px;">oper123</code> — Operator</div>
                <div>🟡 <code style="background:#e8e8e8;padding:1px 4px;border-radius:3px;">bendahara</code> / <code style="background:#e8e8e8;padding:1px 4px;border-radius:3px;">bend123</code> — Bendahara</div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
