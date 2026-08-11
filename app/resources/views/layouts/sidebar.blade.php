{{-- ════════════════════════════════════════════
    SIDEBAR — Main Navigation Only (Slim Design)
    Treeview POK dipindah ke halaman /arsip
═════════════════════════════════════════════ --}}
<div class="sidebar" :class="{ 'open': sidebarOpen }">

    {{-- ── LOGO ── --}}
    <div class="sidebar-logo">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:40px;height:40px;background:#fff;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(0,0,0,.2);">
                <span style="font-size:22px;">📊</span>
            </div>
            <div style="min-width:0;">
                <div class="app-name">Arsip Keuangan BPS</div>
                <div class="app-sub">Kabupaten Subang</div>
            </div>
        </div>
        {{-- Fiscal Year Badge --}}
        <div style="margin-top:12px;padding:6px 10px;background:rgba(245,166,35,.15);border:1px solid rgba(245,166,35,.3);border-radius:8px;display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:10.5px;color:rgba(255,255,255,.6);">Tahun Anggaran</span>
            <span style="font-size:13px;font-weight:700;color:#f5a623;">2026</span>
        </div>
    </div>

    {{-- ── MAIN NAV ── --}}
    <nav class="sidebar-nav" style="padding:12px 10px;">

        {{-- Section: Utama --}}
        <div class="nav-section-label">UTAMA</div>

        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') ? 'nav-item-active' : '' }}"
           id="nav-dashboard">
            <div class="nav-icon" style="background:{{ request()->routeIs('dashboard') ? 'rgba(245,166,35,.2)' : 'rgba(255,255,255,.08)' }};">
                <span>🏠</span>
            </div>
            <div class="nav-label">
                <span class="nav-text">Dashboard</span>
                <span class="nav-desc">Ringkasan & statistik</span>
            </div>
        </a>

        <a href="{{ route('arsip.index') }}"
           class="nav-item {{ request()->routeIs('arsip.*') || request()->routeIs('items.*') ? 'nav-item-active' : '' }}"
           id="nav-arsip">
            <div class="nav-icon" style="background:{{ request()->routeIs('arsip.*') || request()->routeIs('items.*') ? 'rgba(245,166,35,.2)' : 'rgba(255,255,255,.08)' }};">
                <span>📂</span>
            </div>
            <div class="nav-label">
                <span class="nav-text">Arsip Dokumen</span>
                <span class="nav-desc">Browser hirarki POK</span>
            </div>
            {{-- Item count badge --}}
            @php $totalItems = \App\Models\Item::count(); @endphp
            @if($totalItems > 0)
            <span class="nav-badge">{{ $totalItems }}</span>
            @endif
        </a>

        {{-- Section: Pengelolaan --}}
        @if(auth()->user()->canManageMaster() || auth()->user()->isAdmin())
        <div class="nav-section-label" style="margin-top:12px;">PENGELOLAAN</div>
        @endif

        @if(auth()->user()->canManageMaster())
        <a href="{{ route('master.index') }}"
           class="nav-item {{ request()->routeIs('master.*') ? 'nav-item-active' : '' }}"
           id="nav-master">
            <div class="nav-icon" style="background:{{ request()->routeIs('master.*') ? 'rgba(245,166,35,.2)' : 'rgba(255,255,255,.08)' }};">
                <span>⚙️</span>
            </div>
            <div class="nav-label">
                <span class="nav-text">Master Data</span>
                <span class="nav-desc">Kelola hirarki POK</span>
            </div>
        </a>
        @endif

        @if(auth()->user()->isAdmin())
        <a href="{{ route('users.index') }}"
           class="nav-item {{ request()->routeIs('users.*') ? 'nav-item-active' : '' }}"
           id="nav-users">
            <div class="nav-icon" style="background:{{ request()->routeIs('users.*') ? 'rgba(245,166,35,.2)' : 'rgba(255,255,255,.08)' }};">
                <span>👥</span>
            </div>
            <div class="nav-label">
                <span class="nav-text">Pengguna</span>
                <span class="nav-desc">Manajemen akun & role</span>
            </div>
            <span class="nav-badge" style="background:rgba(220,38,38,.7);">{{ \App\Models\User::count() }}</span>
        </a>
        @endif

        {{-- Section: Status Cepat (hanya Bendahara/Admin) --}}
        @if(auth()->user()->canVerify())
        <div class="nav-section-label" style="margin-top:12px;">STATUS CEPAT</div>

        @php
            $pendingCount = \App\Models\Item::where('verification_status','PENDING')->count();
            $rejectedCount = \App\Models\Item::where('verification_status','REJECTED')->count();
        @endphp

        <a href="{{ route('arsip.index', ['filter' => 'pending']) }}"
           class="nav-item" id="nav-pending">
            <div class="nav-icon" style="background:rgba(217,119,6,.15);">
                <span>⏳</span>
            </div>
            <div class="nav-label">
                <span class="nav-text">Menunggu Verifikasi</span>
                <span class="nav-desc">Perlu tindakan Bendahara</span>
            </div>
            @if($pendingCount > 0)
            <span class="nav-badge" style="background:rgba(217,119,6,.8);">{{ $pendingCount }}</span>
            @endif
        </a>

        @if($rejectedCount > 0)
        <a href="{{ route('arsip.index', ['filter' => 'rejected']) }}"
           class="nav-item" id="nav-rejected">
            <div class="nav-icon" style="background:rgba(220,38,38,.1);">
                <span>❌</span>
            </div>
            <div class="nav-label">
                <span class="nav-text">Ditolak</span>
                <span class="nav-desc">Butuh revisi dokumen</span>
            </div>
            <span class="nav-badge" style="background:rgba(220,38,38,.7);">{{ $rejectedCount }}</span>
        </a>
        @endif
        @endif

    </nav>

    {{-- ── USER INFO ── --}}
    <div class="sidebar-user">
        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div class="user-info" style="min-width:0;flex:1;">
            <div class="user-name" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                {{ auth()->user()->name }}
            </div>
            <span class="user-role">{{ auth()->user()->role }}</span>
        </div>
        {{-- Logout icon --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" title="Keluar"
                    style="background:rgba(255,255,255,.1);border:none;border-radius:8px;padding:7px;cursor:pointer;color:rgba(255,255,255,.7);font-size:14px;transition:all .15s;"
                    onmouseover="this.style.background='rgba(220,38,38,.4)';this.style.color='#fff';"
                    onmouseout="this.style.background='rgba(255,255,255,.1)';this.style.color='rgba(255,255,255,.7)';">
                🚪
            </button>
        </form>
    </div>
</div>

<style>
/* ── Slim Sidebar Nav Styles ── */
.nav-section-label {
    font-size: 10px;
    font-weight: 700;
    color: rgba(255,255,255,.3);
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 4px 6px 6px;
    margin-bottom: 2px;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 10px;
    border-radius: 10px;
    text-decoration: none;
    transition: all .15s;
    margin-bottom: 3px;
    cursor: pointer;
}

.nav-item:hover {
    background: rgba(255,255,255,.08);
}

.nav-item-active {
    background: rgba(255,255,255,.1) !important;
    border-left: 3px solid #f5a623;
    padding-left: 7px;
}

.nav-icon {
    width: 36px;
    height: 36px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    flex-shrink: 0;
    transition: all .15s;
}

.nav-label {
    display: flex;
    flex-direction: column;
    min-width: 0;
    flex: 1;
}

.nav-text {
    font-size: 13px;
    font-weight: 600;
    color: #fff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.nav-desc {
    font-size: 10.5px;
    color: rgba(255,255,255,.45);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-top: 1px;
}

.nav-badge {
    font-size: 10px;
    font-weight: 700;
    background: rgba(255,255,255,.2);
    color: #fff;
    padding: 2px 7px;
    border-radius: 20px;
    flex-shrink: 0;
}
</style>
