@extends('layouts.app')
@section('title', 'Arsip Keuangan POK')

@section('content')
<div class="space-y-6">

    {{-- ── TOP SECTION: PROMINENT INSTANT SEARCH (SEARCH-FIRST) ── --}}
    <div style="background: linear-gradient(135deg, #001F54 0%, #003087 100%);" class="rounded-2xl p-6 text-white shadow-lg">
        <div class="max-w-3xl">
            <h1 class="text-xl font-extrabold tracking-tight mb-1">🔍 Browser Arsip Keuangan POK</h1>
            <p class="text-xs text-blue-100 mb-4">Cari instan berdasarkan Kode Item (misal: 001366), Kode Akun (521213), atau Kata Kunci Kegiatan.</p>
            
            <form method="GET" action="{{ route('items.index') }}" class="flex gap-2">
                @if($filter) <input type="hidden" name="filter" value="{{ $filter }}"> @endif
                @if($subOutputId) <input type="hidden" name="sub_output_id" value="{{ $subOutputId }}"> @endif

                <div class="relative flex-1">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Ketik Kode Item (misal: 001366), Kode Akun, atau Nama Kegiatan..."
                           class="w-full pl-11 pr-4 py-3 bg-white text-slate-800 rounded-xl border-0 shadow-inner focus:ring-4 focus:ring-amber-400 text-sm font-medium outline-none">
                    <span class="absolute left-3.5 top-3.5 text-slate-400 text-base">🔍</span>
                </div>
                
                <button type="submit" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold rounded-xl shadow-md transition-all text-sm">
                    Cari Item
                </button>
                @if($search || $subOutputId || $outputId || $programId || $filter)
                    <a href="{{ route('items.index') }}" class="px-4 py-3 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl text-sm transition-all flex items-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- ── MIDDLE SECTION: CASCADING FILTER DROPDOWNS ── --}}
    <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">📁 Cascading Directory Filter (Navigasi Manual)</div>
        
        <form method="GET" action="{{ route('items.index') }}" id="cascadingFilterForm" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @if($search) <input type="hidden" name="search" value="{{ $search }}"> @endif
            @if($filter) <input type="hidden" name="filter" value="{{ $filter }}"> @endif

            {{-- Dropdown 1: Program --}}
            <div>
                <label class="form-label text-xs">Pilih Program</label>
                <select name="program_id" onchange="document.getElementById('cascadingFilterForm').submit()" class="form-input text-xs font-medium">
                    <option value="">-- Semua Program --</option>
                    @foreach($programs as $p)
                        <option value="{{ $p->id }}" {{ $programId == $p->id ? 'selected' : '' }}>
                            [{{ $p->code }}] {{ Str::limit($p->name, 35) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Dropdown 2: Output --}}
            <div>
                <label class="form-label text-xs">Pilih Output</label>
                <select name="output_id" onchange="document.getElementById('cascadingFilterForm').submit()" class="form-input text-xs font-medium">
                    <option value="">-- Semua Output --</option>
                    @foreach($outputs as $o)
                        <option value="{{ $o->id }}" {{ $outputId == $o->id ? 'selected' : '' }}>
                            [{{ $o->code }}] {{ Str::limit($o->name, 35) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Dropdown 3: Sub-Output --}}
            <div>
                <label class="form-label text-xs">Pilih Sub-Output</label>
                <select name="sub_output_id" onchange="document.getElementById('cascadingFilterForm').submit()" class="form-input text-xs font-medium">
                    <option value="">-- Semua Sub-Output --</option>
                    @foreach($subOutputs as $so)
                        <option value="{{ $so->id }}" {{ $subOutputId == $so->id ? 'selected' : '' }}>
                            [{{ $so->code }}] {{ Str::limit($so->name, 35) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        {{-- Quick Filter Pills --}}
        <div class="flex items-center justify-between flex-wrap gap-3 mt-4 pt-4 border-t border-slate-100">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-xs font-semibold text-slate-500">Filter Status:</span>
                <a href="{{ route('items.index', request()->except('filter')) }}"
                   class="px-3 py-1 rounded-full text-xs font-semibold {{ !$filter ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua ({{ $stats['total'] }})
                </a>
                <a href="{{ route('items.index', array_merge(request()->query(), ['filter' => 'pending'])) }}"
                   class="px-3 py-1 rounded-full text-xs font-semibold {{ $filter === 'pending' ? 'bg-amber-500 text-white' : 'bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
                    ⏳ Menunggu ({{ $stats['pending'] }})
                </a>
                <a href="{{ route('items.index', array_merge(request()->query(), ['filter' => 'approved'])) }}"
                   class="px-3 py-1 rounded-full text-xs font-semibold {{ $filter === 'approved' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}">
                    ✅ Siap Cair ({{ $stats['approved'] }})
                </a>
                <a href="{{ route('items.index', array_merge(request()->query(), ['filter' => 'rejected'])) }}"
                   class="px-3 py-1 rounded-full text-xs font-semibold {{ $filter === 'rejected' ? 'bg-red-600 text-white' : 'bg-red-50 text-red-800 hover:bg-red-100' }}">
                    ❌ Ditolak ({{ $stats['rejected'] }})
                </a>
            </div>

            @if($selectedSubOutput)
                <div class="text-xs font-bold text-blue-900 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-200">
                    📌 Sub-Output Aktif: [{{ $selectedSubOutput->code }}] {{ $selectedSubOutput->name }}
                </div>
            @endif
        </div>
    </div>

    {{-- ── MAIN SECTION: CLEAN DATA TABLE LISTING ITEMS ── --}}
    <div class="table-card-v2">
        <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <span>📋 Daftar Item Kegiatan POK</span>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800">
                    {{ $items->total() }} Item Ditemukan
                </span>
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th class="w-28">Kode Item</th>
                        <th>Nama Item Kegiatan</th>
                        <th>Akun / Sub-Output</th>
                        <th class="text-right">Pagu Anggaran</th>
                        <th class="text-center">Berkas</th>
                        <th class="text-center">Status Verifikasi</th>
                        <th class="text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td class="font-mono text-xs font-bold text-blue-900 bg-blue-50/50 px-3 py-2 rounded text-center">
                            {{ $item->code }}
                        </td>
                        <td>
                            <div class="font-semibold text-slate-900 text-sm mb-0.5">{{ $item->name }}</div>
                            @if(str_contains($item->code, '001366') || str_contains($item->code, '001211'))
                                <span class="inline-block bg-amber-100 text-amber-900 border border-amber-300 text-[10px] font-extrabold px-2 py-0.5 rounded-md">
                                    ⭐ MVP CORE FOCUS
                                </span>
                            @endif
                        </td>
                        <td class="text-xs text-slate-600">
                            <div class="font-semibold text-slate-800">Akun {{ $item->account->code }}</div>
                            <div class="text-[11px] text-slate-500 truncate max-w-xs">{{ $item->account->name }}</div>
                            <div class="text-[10px] font-mono text-blue-700 mt-0.5">
                                {{ $item->account->subComponent->component->subOutput->code }}
                            </div>
                        </td>
                        <td class="text-right font-mono font-bold text-emerald-700 text-sm whitespace-nowrap">
                            Rp {{ number_format($item->pagu, 0, ',', '.') }}
                        </td>
                        <td class="text-center whitespace-nowrap">
                            <span class="text-xs font-bold {{ $item->documents->count() > 0 ? 'text-emerald-600 bg-emerald-50 border border-emerald-200' : 'text-slate-400 bg-slate-100' }} px-2.5 py-1 rounded-full inline-flex items-center gap-1">
                                📄 {{ $item->documents->count() }} File
                            </span>
                        </td>
                        <td class="text-center whitespace-nowrap">
                            <span class="badge {{ $item->status_badge_class }}">
                                @if($item->verification_status === 'APPROVED') ✅ Siap Cair
                                @elseif($item->verification_status === 'REJECTED') ❌ Ditolak
                                @else ⏳ Menunggu
                                @endif
                            </span>
                        </td>
                        <td class="text-center whitespace-nowrap">
                            <a href="{{ route('items.show', $item) }}"
                               class="btn btn-primary btn-sm shadow-sm hover:shadow">
                                <span>Workspace</span>
                                <span>→</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-slate-400">
                            <div class="text-3xl mb-2">🔍</div>
                            <div class="font-medium text-slate-600">Tidak ada item kegiatan yang cocok dengan kriteria pencarian/filter.</div>
                            <div class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci atau reset filter cascading directory.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-5 py-3 bg-slate-50 border-t border-slate-200">
            {{ $items->links() }}
        </div>
    </div>

</div>
@endsection
