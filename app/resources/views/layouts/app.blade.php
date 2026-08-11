<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | @yield('title', 'Dashboard')</title>
    <meta name="description" content="Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang - Pengelolaan dokumen pertanggungjawaban keuangan secara digital dan terstruktur.">

    <!-- View Transitions API support for zero-flicker SPA navigation -->
    <meta name="view-transition" content="same-origin">

    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── View Transitions & Smooth Navigation ── */
        @view-transition {
            navigation: auto;
        }

        /* ── Turbo Progress Bar Styling ── */
        .turbo-progress-bar {
            height: 3px;
            background: linear-gradient(90deg, #003087 0%, #3b82f6 50%, #001F54 100%);
            box-shadow: 0 1px 4px rgba(0, 48, 135, 0.5);
        }

        /* ── BPS Subang Corporate Design System ── */
        :root {
            --font-sans: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --sidebar-w: 270px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: var(--font-sans);
            background-color: #f8fafc;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
        }

        .font-mono { font-family: var(--font-mono) !important; }

        /* ── Main Layout ── */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.25s ease;
        }

        /* ── Header Topbar ── */
        .topbar-v4 {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 32px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 30;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        /* ── Page Body Entrance Animation (Silky Smooth) ── */
        @keyframes pageFadeIn {
            from {
                opacity: 0.2;
                transform: translateY(6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-body {
            padding: 32px;
            flex: 1;
            animation: pageFadeIn 0.22s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            will-change: opacity, transform;
        }

        /* ── Corporate Cards ── */
        .card-corporate {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        /* ── Tables ── */
        .table-container-v4 {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
        }
        .table-v4 { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-v4 th {
            background: #f1f5f9;
            padding: 14px 20px;
            text-align: left;
            font-size: 11px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 1px solid #cbd5e1;
        }
        .table-v4 td {
            padding: 16px 20px;
            font-size: 13.5px;
            color: #1e293b;
            border-bottom: 1px solid #f1f5f9;
        }
        .table-v4 tr:last-child td { border-bottom: none; }
        .table-v4 tr:hover td { background: #f8fafc; }

        /* ── Muted Corporate Badges ── */
        .badge-corp {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 11.5px;
            font-weight: 700;
        }
        .badge-corp-approved {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        .badge-corp-rejected {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }
        .badge-corp-pending {
            background-color: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        /* ── Muted Buttons ── */
        .btn-bps {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.15s ease;
            text-decoration: none;
        }
        .btn-bps-primary {
            background: #003087;
            color: #ffffff;
        }
        .btn-bps-primary:hover {
            background: #001F54;
        }
        .btn-bps-success {
            background: #15803d;
            color: #ffffff;
        }
        .btn-bps-success:hover {
            background: #166534;
        }
        .btn-bps-danger {
            background: #b91c1c;
            color: #ffffff;
        }
        .btn-bps-danger:hover {
            background: #991b1b;
        }
        .btn-bps-secondary {
            background: #ffffff;
            color: #475569;
            border-color: #cbd5e1;
        }
        .btn-bps-secondary:hover {
            background: #f1f5f9;
            color: #0f172a;
        }
        .btn-bps-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        /* ── Form Inputs ── */
        .form-input-v4 {
            width: 100%;
            padding: 10px 14px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 500;
            color: #0f172a;
            outline: none;
            transition: border-color 0.15s;
        }
        .form-input-v4:focus {
            border-color: #003087;
            box-shadow: 0 0 0 3px rgba(0, 48, 135, 0.12);
        }

        /* ── Responsive ── */
        @media (max-width: 1024px) {
            :root { --sidebar-w: 0px; }
            .sidebar-v4 { transform: translateX(-100%); }
            .sidebar-v4.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body class="h-full" x-data="{ sidebarOpen: false }">

{{-- ── SIDEBAR NAVIGATION ── --}}
@include('layouts.sidebar')

{{-- ── MAIN CONTENT WORKSPACE ── --}}
<div class="main-content">

    {{-- Topbar Header --}}
    <header class="topbar-v4">
        <div class="flex items-center gap-4">
            <button class="lg:hidden btn-bps btn-bps-secondary btn-bps-sm" @click="sidebarOpen = !sidebarOpen">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>

            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#001F54] flex items-center justify-center text-white font-black text-xs shadow-sm">
                    BPS
                </div>
                <div>
                    <h1 class="text-sm font-extrabold text-slate-900 tracking-tight leading-none">Sistem Data Digital Arsip Keuangan</h1>
                    <span class="text-[11px] font-semibold text-slate-500">Badan Pusat Statistik Kabupaten Subang</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            {{-- Fiscal Year Pill --}}
            <div class="hidden sm:flex items-center gap-2 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-lg">
                <span class="text-xs font-extrabold text-amber-900 font-mono">DIPA 2026</span>
            </div>

            {{-- User Role Pill --}}
            <div class="flex items-center gap-3 pl-3 border-l border-slate-200">
                <div class="text-right hidden sm:block">
                    <div class="text-xs font-extrabold text-slate-900 leading-snug">{{ auth()->user()->name }}</div>
                    <div class="text-[10px] font-bold text-blue-800 uppercase tracking-wider">{{ auth()->user()->role }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" data-turbo="false">
                    @csrf
                    <button type="submit" class="btn-bps btn-bps-secondary btn-bps-sm hover:border-red-300 hover:bg-red-50 hover:text-red-700" title="Keluar dari Sistem">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="hidden md:inline">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- Page Body with Smooth Entrance Animation --}}
    <main class="page-body" id="page-body-container">
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm font-semibold mb-6 flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-900 text-sm font-semibold mb-6 flex items-center gap-3">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

</div>

{{-- Mobile Overlay --}}
<div x-show="sidebarOpen" @click="sidebarOpen=false"
     class="fixed inset-0 bg-slate-900/50 z-39"
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
