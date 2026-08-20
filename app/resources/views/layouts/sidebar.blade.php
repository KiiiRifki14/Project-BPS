{{-- ══════════════════════════════════════════════════════════
    SAKDI BPS SIDEBAR — v2.0.0
    DESIGN.md: sidebar-width 256px, primary-900 bg (#002D5C)
    Responsive: hidden mobile | collapsed 64px tablet | full 256px desktop
══════════════════════════════════════════════════════════ --}}
<aside class="sidebar-v4"
       :class="{ 'open': sidebarOpen, 'sidebar-force-collapsed': sidebarCollapsed }"
       x-data="{ hovered: false }"
       @mouseenter="hovered = true"
       @mouseleave="hovered = false">

    {{-- BRANDING & HEADER --}}
    <div class="sidebar-header">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center text-white font-black text-lg shadow-sm"
                 style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
                📊
            </div>
            <div class="sidebar-text-block">
                <div class="text-sm font-extrabold text-white tracking-wide leading-snug">SAKDI</div>
                <div class="text-[11px] font-semibold" style="color: rgba(255,255,255,0.6);">BPS Kab. Subang</div>
            </div>
        </div>
        {{-- Tablet collapse toggle button --}}
        <button class="hidden md:flex lg:hidden items-center justify-center w-8 h-8 rounded-lg ml-auto transition-colors"
                style="color: rgba(255,255,255,0.6); background: rgba(255,255,255,0.05);"
                @click.stop="toggleSidebarCollapse()"
                :title="sidebarCollapsed ? 'Perluas sidebar' : 'Ciutkan sidebar'"
                aria-label="Toggle sidebar">
            <svg class="w-4 h-4 transition-transform" :class="sidebarCollapsed ? 'rotate-180' : ''"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
        </button>
    </div>

    {{-- FISCAL YEAR INDICATOR --}}
    <div class="px-4 py-3" style="border-bottom: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.1);">
        <div class="flex items-center justify-between rounded-xl px-3.5 py-2"
             style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
            <span class="text-[10px] font-bold uppercase tracking-wider" style="color: rgba(255,255,255,0.5);">TA</span>
            <span class="text-xs font-black font-mono px-2 py-0.5 rounded"
                  style="color: #FBD063; background: rgba(251,208,99,0.18); border: 1px solid rgba(251,208,99,0.3);">2026</span>
        </div>
    </div>

    {{-- NAVIGATION MENUS --}}
    <nav class="sidebar-nav-scroll">
        <div class="nav-section-label">NAVIGASI UTAMA</div>

        {{-- 1. Dashboard Utama --}}
        <a href="{{ route('dashboard') }}"
           class="nav-link-v4 {{ request()->routeIs('dashboard') ? 'active' : '' }}"
           title="Dashboard Utama">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
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
           class="nav-link-v4 {{ $isArsipActive ? 'active' : '' }}"
           title="Arsip Keuangan POK">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            <span class="font-bold">Arsip Keuangan POK</span>
        </a>

        {{-- 3. Verifikasi Pencairan (BENDAHARA only) --}}
        @if(auth()->user()->role === 'BENDAHARA')
        @php
            $pendingCount = \App\Models\Item::where('verification_status', 'PENDING')->count();
        @endphp
        <a href="{{ route('verification.index') }}"
           class="nav-link-v4 {{ $isVerificationActive ? 'active' : '' }}"
           title="Verifikasi Pencairan">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-bold flex-1">Verifikasi Pencairan</span>
            @if($pendingCount > 0)
                <span class="text-[10px] font-black px-2 py-0.5 rounded-full flex-shrink-0"
                      style="background: #F59E0B; color: #1C1917;">{{ $pendingCount }}</span>
            @endif
        </a>
        @endif

        {{-- 4. Kelola Master POK (SUPERVISOR & ADMIN) --}}
        @if(in_array(auth()->user()->role, ['SUPERVISOR', 'ADMIN']))
        <a href="{{ route('master.index') }}"
           class="nav-link-v4 {{ request()->routeIs('master.*') ? 'active' : '' }}"
           title="Kelola Master POK">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="font-bold">Kelola Master POK</span>
        </a>
        @endif

        {{-- 5. Laporan & Rekapitulasi --}}
        <a href="{{ route('reports.index') }}"
           class="nav-link-v4 {{ request()->routeIs('reports.*') ? 'active' : '' }}"
           title="Laporan & Rekapitulasi">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="font-bold">Laporan &amp; Rekapitulasi</span>
        </a>

        {{-- 6. Manajemen Pengguna (ADMIN only) --}}
        @if(auth()->user()->role === 'ADMIN')
        <a href="{{ route('users.index') }}"
           class="nav-link-v4 {{ request()->routeIs('users.*') ? 'active' : '' }}"
           title="Manajemen Pengguna">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
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
           class="block p-3 rounded-xl transition-all no-underline mb-2"
           style="border: 1px solid rgba(251,208,99,0.3); background: rgba(251,208,99,0.1);"
           onmouseover="this.style.background='rgba(251,208,99,0.2)'"
           onmouseout="this.style.background='rgba(251,208,99,0.1)'"
           title="Shortcut BMA.006">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full flex-shrink-0" style="background: #FBD063;"></span>
                <span class="text-xs font-black font-mono" style="color: #FBD063;">BMA.006</span>
            </div>
            <div class="text-[11px] font-semibold mt-1 truncate" style="color: rgba(255,255,255,0.8);">Sensus Ekonomi 2026</div>
        </a>
        @endif

        @if($item001366)
        <a href="{{ route('items.show', $item001366) }}"
           class="block p-3 rounded-xl transition-all no-underline"
           style="border: 1px solid rgba(61,184,107,0.3); background: rgba(61,184,107,0.1);"
           onmouseover="this.style.background='rgba(61,184,107,0.2)'"
           onmouseout="this.style.background='rgba(61,184,107,0.1)'"
           title="Shortcut 001366">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full flex-shrink-0" style="background: #3DB86B;"></span>
                <span class="text-xs font-black font-mono" style="color: #3DB86B;">001366</span>
            </div>
            <div class="text-[11px] font-semibold mt-1 truncate" style="color: rgba(255,255,255,0.8);">Honor Petugas Sensus</div>
        </a>
        @endif
    </nav>

    {{-- USER FOOTER --}}
    <div class="sidebar-footer-v4">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center text-white font-black text-xs shadow-sm"
                 style="background: var(--color-primary);">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1 sidebar-text-block">
                <div class="text-xs font-bold text-white truncate leading-snug user-name">{{ auth()->user()->name }}</div>
                <div class="text-[10px] font-extrabold uppercase tracking-wide user-role"
                     style="color: var(--color-primary-400); font-family: var(--font-mono);">
                    {{ auth()->user()->role }}
                </div>
            </div>
        </div>
    </div>

</aside>

