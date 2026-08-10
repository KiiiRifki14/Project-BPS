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
        /* ── BPS Design System ── */
        :root {
            --bps-blue:    #003087;
            --bps-blue-l:  #0d47a1;
            --bps-blue-xl: #e8f0fe;
            --bps-gold:    #f5a623;
            --bps-green:   #16a34a;
            --bps-red:     #dc2626;
            --bps-amber:   #d97706;
            --sidebar-w:   280px;
        }

        * { box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, sans-serif; background: #f1f5f9; }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed; top: 0; left: 0; height: 100vh; width: var(--sidebar-w);
            background: linear-gradient(175deg, var(--bps-blue) 0%, #001a5c 100%);
            display: flex; flex-direction: column; z-index: 40;
            box-shadow: 4px 0 24px rgba(0,48,135,.35);
            transition: transform .3s ease;
        }
        .sidebar-logo {
            padding: 20px 16px 12px;
            border-bottom: 1px solid rgba(255,255,255,.12);
        }
        .sidebar-logo .app-name {
            font-size: 13px; font-weight: 700; color: #fff;
            line-height: 1.3; letter-spacing: .2px;
        }
        .sidebar-logo .app-sub {
            font-size: 10.5px; color: rgba(255,255,255,.6); margin-top: 2px;
        }

        /* Treeview */
        .sidebar-nav { flex: 1; overflow-y: auto; padding: 8px 0; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.2); border-radius:2px; }

        .tree-item { padding: 0; margin: 0; }
        .tree-toggle {
            display: flex; align-items: center; gap: 7px;
            padding: 7px 12px; cursor: pointer; user-select: none;
            color: rgba(255,255,255,.8); font-size: 11.5px; font-weight: 500;
            border-radius: 4px; margin: 1px 6px;
            transition: background .15s, color .15s;
        }
        .tree-toggle:hover { background: rgba(255,255,255,.1); color: #fff; }
        .tree-toggle .caret { font-size: 9px; transition: transform .2s; flex-shrink: 0; }
        .tree-toggle.open .caret { transform: rotate(90deg); }
        .tree-toggle .badge { margin-left: auto; }

        .tree-children { padding-left: 14px; border-left: 1px solid rgba(255,255,255,.1); margin-left: 18px; }
        .tree-leaf {
            display: flex; align-items: center; gap: 6px;
            padding: 5px 10px; font-size: 11px; color: rgba(255,255,255,.7);
            border-radius: 4px; margin: 1px 6px; text-decoration: none;
            transition: background .15s, color .15s;
        }
        .tree-leaf:hover { background: rgba(255,255,255,.12); color: #fff; }
        .tree-leaf.active { background: var(--bps-gold); color: #000; font-weight: 600; }

        /* Search box */
        .sidebar-search { padding: 8px 10px; }
        .sidebar-search input {
            width: 100%; background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.2);
            border-radius: 6px; padding: 6px 10px; color: #fff; font-size: 11.5px; outline: none;
        }
        .sidebar-search input::placeholder { color: rgba(255,255,255,.5); }
        .sidebar-search input:focus { border-color: var(--bps-gold); background: rgba(255,255,255,.15); }

        /* User info at bottom */
        .sidebar-user {
            padding: 12px 14px; border-top: 1px solid rgba(255,255,255,.1);
            display: flex; align-items: center; gap: 10px;
        }
        .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--bps-gold); display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; color: #000; flex-shrink: 0;
        }
        .user-info .user-name { font-size: 11.5px; font-weight: 600; color: #fff; }
        .user-info .user-role {
            font-size: 10px; color: rgba(255,255,255,.6);
            background: rgba(255,255,255,.12); padding: 1px 6px; border-radius: 10px;
            display: inline-block; margin-top: 2px;
        }

        /* ── Main Content ── */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }

        /* ── Top Bar ── */
        .topbar {
            background: #fff; border-bottom: 1px solid #e2e8f0;
            padding: 0 24px; height: 60px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 30;
            box-shadow: 0 1px 6px rgba(0,0,0,.06);
        }
        .topbar-title { font-size: 15px; font-weight: 600; color: #1e293b; }
        .topbar-actions { display: flex; align-items: center; gap: 12px; }
        .btn-logout {
            font-size: 12px; color: #64748b; text-decoration: none;
            padding: 6px 12px; border-radius: 6px; border: 1px solid #e2e8f0;
            transition: all .15s;
        }
        .btn-logout:hover { background: #fee2e2; color: #dc2626; border-color: #fca5a5; }

        /* ── Page Content ── */
        .page-body { padding: 24px; flex: 1; }

        /* ── Badges ── */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-approved { background: #dcfce7; color: #15803d; }
        .badge-rejected { background: #fee2e2; color: #b91c1c; }
        .badge-pending  { background: #fef9c3; color: #854d0e; }

        /* ── Cards ── */
        .stat-card {
            background: #fff; border-radius: 12px; padding: 20px;
            border: 1px solid #e2e8f0; transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }
        .stat-card .stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        .stat-card .stat-value { font-size: 24px; font-weight: 800; color: #0f172a; margin-top: 10px; }
        .stat-card .stat-label { font-size: 12px; color: #64748b; margin-top: 2px; }

        /* ── Alerts ── */
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 13.5px; display: flex; align-items: flex-start; gap: 10px; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* ── Buttons ── */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; border: none; transition: all .15s; text-decoration: none; }
        .btn-primary   { background: var(--bps-blue); color: #fff; }
        .btn-primary:hover { background: var(--bps-blue-l); box-shadow: 0 4px 12px rgba(0,48,135,.3); }
        .btn-success   { background: #16a34a; color: #fff; }
        .btn-success:hover { background: #15803d; }
        .btn-danger    { background: #dc2626; color: #fff; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-secondary { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
        .btn-secondary:hover { background: #e2e8f0; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }

        /* ── Table ── */
        .table-card { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; }
        .table-card table { width: 100%; border-collapse: collapse; }
        .table-card th { background: #f8fafc; padding: 11px 14px; text-align: left; font-size: 11.5px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: .5px; border-bottom: 1px solid #e2e8f0; }
        .table-card td { padding: 11px 14px; font-size: 13px; color: #334155; border-bottom: 1px solid #f1f5f9; }
        .table-card tr:last-child td { border-bottom: none; }
        .table-card tr:hover td { background: #fafbfc; }

        /* ── Breadcrumb ── */
        .breadcrumb { display: flex; flex-wrap: wrap; gap: 4px; align-items: center; font-size: 12px; color: #64748b; margin-bottom: 16px; }
        .breadcrumb-sep { color: #cbd5e1; }
        .breadcrumb span.current { color: #1e293b; font-weight: 600; }

        /* ── Dropzone ── */
        .dropzone {
            border: 2px dashed #94a3b8; border-radius: 12px; padding: 32px 20px; text-align: center;
            cursor: pointer; transition: all .2s; background: #f8fafc;
        }
        .dropzone:hover, .dropzone.drag-over { border-color: var(--bps-blue); background: var(--bps-blue-xl); }
        .dropzone .dz-icon { font-size: 40px; margin-bottom: 10px; }
        .dropzone .dz-text { font-size: 14px; color: #475569; }
        .dropzone .dz-hint { font-size: 11.5px; color: #94a3b8; margin-top: 4px; }

        /* ── Modal ── */
        .modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,.65); z-index: 100; display: flex; align-items: center; justify-content: center; padding: 16px; }
        .modal-box { background: #fff; border-radius: 14px; width: 100%; max-width: 900px; max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 20px 60px rgba(0,0,0,.3); }
        .modal-header { padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; }
        .modal-body { flex: 1; overflow: hidden; }
        .modal-iframe { width: 100%; height: 75vh; border: none; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }

        /* ── Form inputs ── */
        .form-input { width: 100%; padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13.5px; outline: none; transition: border-color .15s; }
        .form-input:focus { border-color: var(--bps-blue); box-shadow: 0 0 0 3px rgba(0,48,135,.1); }
        .form-label { display: block; font-size: 12.5px; font-weight: 600; color: #374151; margin-bottom: 5px; }

        /* ── Pagu display ── */
        .pagu-badge { display: inline-block; background: linear-gradient(135deg, #003087, #0d47a1); color: #fff; padding: 4px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; }
    </style>
</head>
<body class="h-full" x-data="{ sidebarOpen: false }">

{{-- ── SIDEBAR ────────────────────────────────────── --}}
@include('layouts.sidebar')

{{-- ── MAIN CONTENT ─────────────────────────────── --}}
<div class="main-content">
    {{-- Top Bar --}}
    <div class="topbar">
        <div class="flex items-center gap-3">
            <button class="md:hidden btn btn-secondary btn-sm" @click="sidebarOpen = !sidebarOpen">
                ☰
            </button>
            <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
        </div>
        <div class="topbar-actions">
            <span style="font-size:12px;color:#64748b;">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn-logout">🚪 Keluar</button>
            </form>
        </div>
    </div>

    {{-- Flash Messages --}}
    <div class="page-body">
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
    </div>
</div>

{{-- Mobile Sidebar Overlay --}}
<div x-show="sidebarOpen" @click="sidebarOpen=false"
     style="position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:39;"
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
