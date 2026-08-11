{{-- ══════════════════════════════════════════════════════════
    LAYOUT MODERN V2 — SIDEBAR NAVIGATION (Fixed w-64, Dark Navy)
    Includes 6 Core System Menus + Quick Access Shortcuts
══════════════════════════════════════════════════════════ --}}
<aside class="sidebar-v2" :class="{ 'open': sidebarOpen }">

    {{-- ── BRANDING & HEADER ── --}}
    <div class="sidebar-brand">
        <div class="brand-logo-box">
            <span class="brand-icon">📊</span>
        </div>
        <div class="brand-title-wrap">
            <div class="brand-title">Arsip Keuangan</div>
            <div class="brand-sub">BPS Kabupaten Subang</div>
        </div>
    </div>

    {{-- Fiscal Year Indicator --}}
    <div class="sidebar-fy-box">
        <span class="fy-label">Tahun Anggaran DIPA</span>
        <span class="fy-year">2026</span>
    </div>

    {{-- ── 6 CORE SYSTEM MENUS ── --}}
    <nav class="sidebar-menu-list">
        <div class="menu-section-header">NAVIGASI UTAMA</div>

        {{-- 1. Dashboard Utama --}}
        <a href="{{ route('dashboard') }}"
           class="sidebar-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon-box">📊</span>
            <span class="nav-title">Dashboard Utama</span>
        </a>

        {{-- 2. Arsip Keuangan POK --}}
        <a href="{{ route('items.index') }}"
           class="sidebar-nav-link {{ request()->routeIs('items.*') || request()->routeIs('arsip.*') ? 'active' : '' }}">
            <span class="nav-icon-box">📁</span>
            <span class="nav-title">Arsip Keuangan POK</span>
        </a>

        {{-- 3. Verifikasi Pencairan (BENDAHARA & ADMIN) --}}
        @if(in_array(auth()->user()->role, ['BENDAHARA', 'ADMIN']))
        @php
            $pendingCount = \App\Models\Item::where('verification_status', 'PENDING')->count();
        @endphp
        <a href="{{ route('verification.index') }}"
           class="sidebar-nav-link {{ request()->routeIs('verification.*') ? 'active' : '' }}">
            <span class="nav-icon-box">✅</span>
            <span class="nav-title flex-1">Verifikasi Pencairan</span>
            @if($pendingCount > 0)
                <span class="badge-counter-amber">{{ $pendingCount }} Pending</span>
            @endif
        </a>
        @endif

        {{-- 4. Kelola Master POK (SUPERVISOR & ADMIN) --}}
        @if(in_array(auth()->user()->role, ['SUPERVISOR', 'ADMIN']))
        <a href="{{ route('master.index') }}"
           class="sidebar-nav-link {{ request()->routeIs('master.*') ? 'active' : '' }}">
            <span class="nav-icon-box">⚙️</span>
            <span class="nav-title">Kelola Master POK</span>
        </a>
        @endif

        {{-- 5. Laporan & Rekapitulasi Digital --}}
        <a href="{{ route('reports.index') }}"
           class="sidebar-nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <span class="nav-icon-box">📈</span>
            <span class="nav-title">Laporan & Rekapitulasi</span>
        </a>

        {{-- 6. Manajemen Pengguna (ADMIN only) --}}
        @if(auth()->user()->role === 'ADMIN')
        <a href="{{ route('users.index') }}"
           class="sidebar-nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <span class="nav-icon-box">👥</span>
            <span class="nav-title">Manajemen Pengguna</span>
        </a>
        @endif

        {{-- ── QUICK ACCESS / SHORTCUTS ── --}}
        <div class="menu-section-header" style="margin-top: 20px;">AKSES CEPAT / FAVORIT</div>

        {{-- Shortcut 1: BMA.006 Sensus Ekonomi --}}
        @php
            $bma006SubOutput = \App\Models\SubOutput::where('code', 'BMA.006')->first();
            $item001366 = \App\Models\Item::where('code', '001366')->first();
        @endphp

        @if($bma006SubOutput)
        <a href="{{ route('items.index', ['sub_output_id' => $bma006SubOutput->id]) }}"
           class="shortcut-link orange-highlight">
            <span class="shortcut-icon">🟠</span>
            <div class="shortcut-info">
                <span class="shortcut-code">BMA.006</span>
                <span class="shortcut-name">Sensus Ekonomi 2026</span>
            </div>
        </a>
        @endif

        {{-- Shortcut 2: Item 001366 Honor Sensus --}}
        @if($item001366)
        <a href="{{ route('items.show', $item001366) }}"
           class="shortcut-link green-highlight">
            <span class="shortcut-icon">🟢</span>
            <div class="shortcut-info">
                <span class="shortcut-code">Item 001366</span>
                <span class="shortcut-name">Honor Petugas Sensus</span>
            </div>
        </a>
        @endif

    </nav>

    {{-- ── USER PROFILE AT BOTTOM ── --}}
    <div class="sidebar-user-footer">
        <div class="user-avatar-circle">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="user-meta flex-1 min-w-0">
            <div class="user-fullname truncate">{{ auth()->user()->name }}</div>
            <div class="user-role-badge">{{ auth()->user()->role }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" title="Keluar" class="btn-logout-sidebar">
                🚪
            </button>
        </form>
    </div>

</aside>

<style>
/* ── Layout Modern V2 Sidebar Styles ── */
.sidebar-v2 {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 256px; /* w-64 */
    background: linear-gradient(180deg, #001F54 0%, #001233 100%);
    color: #ffffff;
    display: flex;
    flex-direction: column;
    z-index: 40;
    box-shadow: 4px 0 20px rgba(0, 31, 84, 0.25);
    transition: transform 0.3s ease;
}

.sidebar-brand {
    padding: 20px 18px 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.brand-logo-box {
    width: 38px;
    height: 38px;
    background: #ffffff;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    flex-shrink: 0;
}

.brand-title-wrap {
    min-width: 0;
}

.brand-title {
    font-size: 13.5px;
    font-weight: 800;
    color: #ffffff;
    line-height: 1.25;
}

.brand-sub {
    font-size: 11px;
    color: rgba(255, 255, 255, 0.6);
    margin-top: 1px;
}

.sidebar-fy-box {
    margin: 12px 14px 4px;
    padding: 7px 12px;
    background: rgba(243, 156, 18, 0.15);
    border: 1px solid rgba(243, 156, 18, 0.3);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.fy-label {
    font-size: 10.5px;
    color: rgba(255, 255, 255, 0.7);
}

.fy-year {
    font-size: 13px;
    font-weight: 800;
    color: #F39C12;
}

.sidebar-menu-list {
    flex: 1;
    overflow-y: auto;
    padding: 12px 12px;
}

.sidebar-menu-list::-webkit-scrollbar {
    width: 4px;
}
.sidebar-menu-list::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
}

.menu-section-header {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1px;
    color: rgba(255, 255, 255, 0.4);
    padding: 4px 6px 6px;
}

.sidebar-nav-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    text-decoration: none;
    color: rgba(255, 255, 255, 0.85);
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 4px;
    transition: all 0.15s ease;
}

.sidebar-nav-link:hover {
    background: rgba(255, 255, 255, 0.08);
    color: #ffffff;
}

.sidebar-nav-link.active {
    background: rgba(255, 255, 255, 0.12);
    color: #ffffff;
    font-weight: 700;
    border-left: 4px solid #F39C12;
    padding-left: 8px;
}

.nav-icon-box {
    font-size: 16px;
    width: 24px;
    display: flex;
    justify-content: center;
}

.badge-counter-amber {
    font-size: 10px;
    font-weight: 700;
    background: #F39C12;
    color: #000000;
    padding: 2px 7px;
    border-radius: 12px;
}

/* Shortcuts */
.shortcut-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border-radius: 8px;
    text-decoration: none;
    margin-bottom: 4px;
    transition: all 0.15s ease;
    border: 1px solid transparent;
}

.shortcut-link.orange-highlight {
    background: rgba(243, 156, 18, 0.1);
    border-color: rgba(243, 156, 18, 0.25);
}
.shortcut-link.orange-highlight:hover {
    background: rgba(243, 156, 18, 0.2);
}

.shortcut-link.green-highlight {
    background: rgba(22, 163, 74, 0.1);
    border-color: rgba(22, 163, 74, 0.25);
}
.shortcut-link.green-highlight:hover {
    background: rgba(22, 163, 74, 0.2);
}

.shortcut-icon {
    font-size: 14px;
}

.shortcut-info {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.shortcut-code {
    font-size: 11.5px;
    font-weight: 700;
    color: #ffffff;
}

.shortcut-name {
    font-size: 10.5px;
    color: rgba(255, 255, 255, 0.6);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* User footer */
.sidebar-user-footer {
    padding: 12px 14px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(0, 0, 0, 0.15);
}

.user-avatar-circle {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #F39C12;
    color: #000000;
    font-weight: 800;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.user-fullname {
    font-size: 12px;
    font-weight: 600;
    color: #ffffff;
}

.user-role-badge {
    font-size: 9.5px;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.7);
    background: rgba(255, 255, 255, 0.12);
    padding: 1px 6px;
    border-radius: 4px;
    display: inline-block;
    margin-top: 1px;
}

.btn-logout-sidebar {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: 6px;
    padding: 6px;
    cursor: pointer;
    color: rgba(255, 255, 255, 0.7);
    font-size: 14px;
    transition: all 0.15s ease;
}
.btn-logout-sidebar:hover {
    background: rgba(220, 38, 38, 0.6);
    color: #ffffff;
}
</style>
