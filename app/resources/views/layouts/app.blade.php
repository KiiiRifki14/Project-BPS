<!DOCTYPE html>
{{-- Dark mode: tambah class .dark untuk paksa dark, .light untuk paksa light --}}
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | @yield('title', 'Dashboard')</title>
    <meta name="description" content="Sistem Data Digital Arsip Keuangan BPS Kabupaten Subang — Pengelolaan dokumen pertanggungjawaban keuangan secara digital dan terstruktur.">


    {{-- View Transitions API — zero-flicker SPA navigation --}}
    <meta name="view-transition" content="same-origin">

    {{-- Google Fonts: Plus Jakarta Sans & JetBrains Mono
         font-display=swap → mencegah FOIT (Flash of Invisible Text) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full"
      x-data="{
          sidebarOpen: false,
          sidebarCollapsed: false,
          toggleSidebarCollapse() {
              this.sidebarCollapsed = !this.sidebarCollapsed;
              localStorage.setItem('sakdi-sidebar-collapsed', this.sidebarCollapsed);
          },
          initSidebarState() {
              const saved = localStorage.getItem('sakdi-sidebar-collapsed');
              if (saved !== null) this.sidebarCollapsed = saved === 'true';
          }
      }"
      x-init="initSidebarState()">

{{-- ── SIDEBAR NAVIGATION ── --}}
@include('layouts.sidebar')

{{-- ── MAIN CONTENT WORKSPACE ── --}}
<div class="main-content">

    {{-- Topbar Header --}}
    <header class="sakdi-topbar">
        <div class="flex items-center gap-4">
            {{-- Mobile hamburger --}}
            <button class="lg:hidden sakdi-btn sakdi-btn-ghost sakdi-btn-sm p-2 min-h-0 h-10 w-10"
                    @click="sidebarOpen = !sidebarOpen"
                    aria-label="Buka menu navigasi">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Brand --}}
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-primary-900 flex items-center justify-center text-white font-black text-xs shadow-sm flex-shrink-0"
                     style="background-color: var(--color-primary-900);">
                    BPS
                </div>
                <div>
                    <p class="text-sm font-extrabold text-slate-900 tracking-tight leading-none"
                       style="color: var(--color-neutral-900);">
                        Sistem Data Digital Arsip Keuangan

                    </p>
                    <span class="text-[11px] font-semibold leading-none"
                          style="color: var(--color-neutral-500);">
                        Badan Pusat Statistik Kabupaten Subang
                    </span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            {{-- Fiscal Year Pill --}}
            <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg border"
                 style="background:var(--color-accent-50); border-color:var(--color-accent-200);">
                <span class="text-xs font-extrabold font-mono"
                      style="color:var(--color-accent-700);">DIPA 2026</span>
            </div>

            {{-- User info + logout --}}
            <div class="flex items-center gap-3 pl-3 border-l"
                 style="border-color: var(--color-neutral-300);">
                <div class="text-right hidden sm:block">
                    <div class="text-xs font-extrabold leading-snug"
                         style="color: var(--color-neutral-900);">
                        {{ auth()->user()->name }}
                    </div>
                    <div class="text-[10px] font-bold uppercase tracking-wider"
                         style="color: var(--color-primary); font-family: var(--font-mono);">
                        {{ auth()->user()->role }}
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" data-turbo="false">
                    @csrf
                    <button type="submit"
                            class="sakdi-btn sakdi-btn-ghost sakdi-btn-sm"
                            style="border: 1.5px solid var(--color-neutral-300);"
                            onmouseover="this.style.borderColor='#fca5a5';this.style.backgroundColor='#fff1f2';this.style.color='#b91c1c';"
                            onmouseout="this.style.borderColor='var(--color-neutral-300)';this.style.backgroundColor='';this.style.color='';"
                            title="Keluar dari Sistem">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="hidden md:inline">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- Page Body --}}
    <main class="page-body" id="page-body-container">

        {{-- Session Flash: Success --}}
        @if(session('success'))
            <div class="sakdi-alert sakdi-alert-success mb-6" role="alert">
                <svg class="sakdi-alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Session Flash: Error --}}
        @if(session('error'))
            <div class="sakdi-alert sakdi-alert-error mb-6" role="alert">
                <svg class="sakdi-alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-semibold">{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

</div>

{{-- Mobile Overlay --}}
<div x-show="sidebarOpen"
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-slate-900/50"
     style="z-index: calc(var(--z-sidebar) - 1);"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     aria-hidden="true">
</div>

@stack('scripts')
</body>
</html>
