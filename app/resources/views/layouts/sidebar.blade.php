{{-- ══════════════════════════════════════════════════════════
    BPS SUBANG CORPORATE SIDEBAR (w-270px, Dark Navy)
    6 Core System Menus + Quick Access Shortcuts
══════════════════════════════════════════════════════════ --}}
<aside class="sidebar-v4" :class="{ 'open': sidebarOpen }">

    {{-- BRANDING & HEADER --}}
    <div class="sidebar-header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center text-white font-black text-lg shadow-sm">
                📊
            </div>
            <div>
                <div class="text-sm font-extrabold text-white tracking-wide leading-snug">ARSIP KEUANGAN</div>
                <div class="text-[11px] font-semibold text-slate-300">BPS Kabupaten Subang</div>
            </div>
        </div>
    </div>

    {{-- FISCAL YEAR INDICATOR --}}
    <div class="px-5 py-3 border-b border-white/10 bg-black/10">
        <div class="flex items-center justify-between bg-white/5 border border-white/10 rounded-xl px-3.5 py-2">
            <span class="text-[11px] font-bold text-slate-300 uppercase tracking-wider">TAHUN ANGGARAN</span>
            <span class="text-xs font-black text-amber-300 font-mono bg-amber-500/20 border border-amber-400/30 px-2 py-0.5 rounded">2026</span>
        </div>
    </div>

    {{-- NAVIGATION MENUS --}}
    <nav class="sidebar-nav-scroll">
        <div class="nav-section-label">NAVIGASI UTAMA</div>

        {{-- 1. Dashboard Utama --}}
        <a href="{{ route('dashboard') }}"
           class="nav-link-v4 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="font-bold">Dashboard Utama</span>
        </a>

        {{-- 2. Arsip Keuangan POK --}}
        @php
            $isVerificationActive = request()->routeIs('verification.*') ||
                (request()->routeIs('items.show') && (request()->query('from') === 'verification' || auth()->user()->isBendahara()));

            $isArsipActive = (request()->routeIs('items.index') || request()->routeIs('arsip.*')) ||
                (request()->routeIs('items.show') && request()->query('from') !== 'verification' && !auth()->user()->isBendahara());
        @endphp
        <a href="{{ route('items.index') }}"
           class="nav-link-v4 {{ $isArsipActive ? 'active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
            <span class="font-bold">Arsip Keuangan POK</span>
        </a>

        {{-- 3. Verifikasi Pencairan (BENDAHARA & ADMIN) --}}
        @if(in_array(auth()->user()->role, ['BENDAHARA', 'ADMIN']))
        @php
            $pendingCount = \App\Models\Item::where('verification_status', 'PENDING')->count();
        @endphp
        <a href="{{ route('verification.index') }}"
           class="nav-link-v4 {{ $isVerificationActive ? 'active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold flex-1">Verifikasi Pencairan</span>
            @if($pendingCount > 0)
                <span class="bg-amber-500 text-slate-950 text-[10px] font-black px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
            @endif
        </a>
        @endif

        {{-- 4. Kelola Master POK (SUPERVISOR & ADMIN) --}}
        @if(in_array(auth()->user()->role, ['SUPERVISOR', 'ADMIN']))
        <a href="{{ route('master.index') }}"
           class="nav-link-v4 {{ request()->routeIs('master.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span class="font-bold">Kelola Master POK</span>
        </a>
        @endif

        {{-- 5. Laporan & Rekapitulasi Digital --}}
        <a href="{{ route('reports.index') }}"
           class="nav-link-v4 {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <span class="font-bold">Laporan & Rekapitulasi</span>
        </a>

        {{-- 6. Manajemen Pengguna (ADMIN only) --}}
        @if(auth()->user()->role === 'ADMIN')
        <a href="{{ route('users.index') }}"
           class="nav-link-v4 {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <span class="font-bold">Manajemen Pengguna</span>
        </a>
        @endif

        {{-- SHORTCUTS --}}
        <div class="nav-section-label mt-6">SHORTCUTS KEGIATAN</div>

        @php
            $bma006SubOutput = \App\Models\SubOutput::where('code', 'BMA.006')->first();
            $item001366 = \App\Models\Item::where('code', '001366')->first();
        @endphp

        @if($bma006SubOutput)
        <a href="{{ route('items.index', ['sub_output_id' => $bma006SubOutput->id]) }}"
           class="block p-3 rounded-xl border border-amber-400/30 bg-amber-500/10 hover:bg-amber-500/20 transition-all text-decoration-none">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                <span class="text-xs font-black text-amber-300 font-mono">BMA.006</span>
            </div>
            <div class="text-[11px] font-semibold text-slate-200 mt-1 truncate">Sensus Ekonomi 2026</div>
        </a>
        @endif

        @if($item001366)
        <a href="{{ route('items.show', $item001366) }}"
           class="block p-3 rounded-xl border border-emerald-400/30 bg-emerald-500/10 hover:bg-emerald-500/20 transition-all text-decoration-none mt-2">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span class="text-xs font-black text-emerald-300 font-mono">001366</span>
            </div>
            <div class="text-[11px] font-semibold text-slate-200 mt-1 truncate">Honor Petugas Sensus</div>
        </a>
        @endif
    </nav>

    {{-- USER FOOTER --}}
    <div class="sidebar-footer-v4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-700 flex items-center justify-center text-white font-black text-xs shadow-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-xs font-bold text-white truncate leading-snug">{{ auth()->user()->name }}</div>
                <div class="text-[10px] font-extrabold text-blue-200 font-mono uppercase tracking-wide">{{ auth()->user()->role }}</div>
            </div>
        </div>
    </div>

</aside>

<style>
.sidebar-v4 {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 270px;
    background: #001F54;
    color: #ffffff;
    display: flex;
    flex-direction: column;
    z-index: 40;
    border-right: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 4px 0 15px rgba(0, 0, 0, 0.15);
    transition: transform 0.25s ease;
}

.sidebar-header {
    padding: 20px 20px 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-nav-scroll {
    flex: 1;
    overflow-y: auto;
    padding: 16px 14px;
}
.sidebar-nav-scroll::-webkit-scrollbar { width: 4px; }
.sidebar-nav-scroll::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 4px; }

.nav-section-label {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1px;
    color: rgba(255, 255, 255, 0.5);
    padding: 0 10px 8px;
}

.nav-link-v4 {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 14px;
    border-radius: 12px;
    text-decoration: none;
    color: rgba(255, 255, 255, 0.8);
    font-size: 13px;
    margin-bottom: 4px;
    transition: all 0.15s ease;
}

.nav-link-v4:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
}

.nav-link-v4.active {
    background: #003087;
    color: #ffffff;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.sidebar-footer-v4 {
    padding: 16px 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.15);
}
</style>
