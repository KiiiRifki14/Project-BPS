<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | @yield('title', 'Dashboard')</title>
    <meta name="description" content="Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang - Pengelolaan dokumen pertanggungjawaban keuangan secara digital dan terstruktur.">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── BPS Design System Layout V2 ── */
        :root {
            --bps-navy:    #001F54;
            --bps-blue:    #003087;
            --bps-orange:  #F39C12;
            --bps-green:   #16a34a;
            --bps-red:     #dc2626;
            --sidebar-w:   256px; /* w-64 */
        }

        * { box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, sans-serif; background: #f8fafc; color: #1e293b; }

        /* ── Main Content ── */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Top Bar Header ── */
        .topbar-v2 {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 30;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }

        .header-brand-title {
            font-size: 15px;
            font-weight: 700;
            color: #001F54;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── Page Content ── */
        .page-body {
            padding: 24px;
            flex: 1;
        }

        /* ── Badges ── */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; }
        .badge-approved { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-rejected { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }
        .badge-pending  { background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }

        /* ── Table Card ── */
        .table-card-v2 {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }
        .table-card-v2 table { width: 100%; border-collapse: collapse; }
        .table-card-v2 th {
            background: #f8fafc;
            padding: 12px 16px;
            text-align: left;
            font-size: 11.5px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
        }
        .table-card-v2 td {
            padding: 14px 16px;
            font-size: 13px;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }
        .table-card-v2 tr:last-child td { border-bottom: none; }
        .table-card-v2 tr:hover td { background: #f8fafc; }

        /* ── Alerts ── */
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 13.5px; display: flex; align-items: flex-start; gap: 10px; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* ── Form Inputs ── */
        .form-input {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 13.5px;
            outline: none;
            transition: all 0.15s;
        }
        .form-input:focus {
            border-color: #003087;
            box-shadow: 0 0 0 3px rgba(0,48,135,0.1);
        }
        .form-label { display: block; font-size: 12.5px; font-weight: 600; color: #374151; margin-bottom: 5px; }

        /* ── Buttons ── */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; transition: all 0.15s; text-decoration: none; }
        .btn-primary   { background: #003087; color: #fff; }
        .btn-primary:hover { background: #001F54; box-shadow: 0 4px 12px rgba(0,31,84,0.25); }
        .btn-success   { background: #16a34a; color: #fff; }
        .btn-success:hover { background: #15803d; }
        .btn-danger    { background: #dc2626; color: #fff; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-secondary { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
        .btn-secondary:hover { background: #e2e8f0; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sidebar-v2 { transform: translateX(-100%); }
            .sidebar-v2.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body class="h-full" x-data="{ sidebarOpen: false }">

{{-- ── SIDEBAR (Layout Modern V2) ── --}}
@include('layouts.sidebar')

{{-- ── MAIN CONTENT WORKSPACE ── --}}
<div class="main-content">

    {{-- Top Bar Header --}}
    <header class="topbar-v2">
        <div class="flex items-center gap-3">
            <button class="md:hidden btn btn-secondary btn-sm" @click="sidebarOpen = !sidebarOpen">
                ☰ Menu
            </button>
            <div class="header-brand-title">
                <span>🏢</span>
                <span>Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang</span>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-right hidden sm:block">
                <div class="text-xs font-bold text-slate-800">{{ auth()->user()->name }}</div>
                <div class="text-[10.5px] text-slate-500 font-semibold">{{ auth()->user()->role }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm" title="Keluar dari Sistem">
                    🚪 Keluar
                </button>
            </form>
        </div>
    </header>

    {{-- Page Body --}}
    <main class="page-body">
        @if(session('success'))
            <div class="alert alert-success">
                <span>✅</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <span>❌</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

</div>

{{-- Mobile Overlay --}}
<div x-show="sidebarOpen" @click="sidebarOpen=false"
     style="position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:39;"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
</div>

@stack('scripts')
</body>
</html>
