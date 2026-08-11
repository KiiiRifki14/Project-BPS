@extends('layouts.app')
@section('title', 'Arsip Dokumen POK')
@section('page-title', '📂 Browser Arsip Dokumen POK')

@section('content')
{{-- ── PAGE HEADER & QUICK FILTERS ────────────────────── --}}
<div style="background:#fff;border-radius:14px;padding:20px 24px;border:1px solid #e2e8f0;margin-bottom:24px;box-shadow:0 2px 8px rgba(0,0,0,.03);">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
        <div>
            <h1 style="font-size:18px;font-weight:800;color:#0f172a;margin:0 0 4px;">Arsip Dokumen Pertanggungjawaban POK</h1>
            <p style="font-size:12.5px;color:#64748b;margin:0;">Jelajahi hirarki POK 7-level untuk mengunggah, melihat, dan memverifikasi dokumen keuangan BPS.</p>
        </div>

        {{-- Filter Badges --}}
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('arsip.index') }}"
               class="btn {{ !$filter && !$search ? 'btn-primary' : 'btn-secondary' }}" style="font-size:12px;padding:6px 14px;">
                Semua ({{ $stats['total'] }})
            </a>
            <a href="{{ route('arsip.index', ['filter' => 'pending']) }}"
               class="btn {{ $filter === 'pending' ? 'btn-primary' : 'btn-secondary' }}" style="font-size:12px;padding:6px 14px;">
                ⏳ Menunggu ({{ $stats['pending'] }})
            </a>
            <a href="{{ route('arsip.index', ['filter' => 'approved']) }}"
               class="btn {{ $filter === 'approved' ? 'btn-primary' : 'btn-secondary' }}" style="font-size:12px;padding:6px 14px;">
                ✅ Siap Cair ({{ $stats['approved'] }})
            </a>
            <a href="{{ route('arsip.index', ['filter' => 'rejected']) }}"
               class="btn {{ $filter === 'rejected' ? 'btn-primary' : 'btn-secondary' }}" style="font-size:12px;padding:6px 14px;">
                ❌ Ditolak ({{ $stats['rejected'] }})
            </a>
        </div>
    </div>

    {{-- Search Bar --}}
    <form method="GET" action="{{ route('arsip.index') }}" style="margin-top:16px;display:flex;gap:10px;">
        @if($filter) <input type="hidden" name="filter" value="{{ $filter }}"> @endif
        <div style="position:relative;flex:1;">
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="🔍 Cari kode item (misal: 001366), nama kegiatan, atau akun..."
                   class="form-input" style="padding-left:36px;background:#f8fafc;">
            <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:14px;color:#94a3b8;">🔍</span>
        </div>
        <button type="submit" class="btn btn-primary" style="padding:8px 20px;">Cari</button>
        @if($search || $filter)
            <a href="{{ route('arsip.index') }}" class="btn btn-secondary">Reset</a>
        @endif
    </form>
</div>

{{-- ── FILTERED LIST MODE (WHEN SEARCHING OR FILTERING) ────── --}}
@if($filteredItems !== null)
    <div class="table-card">
        <div style="padding:14px 18px;background:#f8fafc;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:13px;font-weight:700;color:#334155;">
                Hasil Pencarian / Filter: <span style="color:#003087;">{{ $filteredItems->count() }} item ditemukan</span>
            </span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Item Kegiatan</th>
                    <th>Hirarki Akun / Sub-Output</th>
                    <th>Pagu</th>
                    <th>Dokumen</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($filteredItems as $item)
                <tr>
                    <td><code style="background:#e8f0fe;color:#003087;padding:3px 8px;border-radius:6px;font-weight:700;">{{ $item->code }}</code></td>
                    <td style="font-weight:500;color:#1e293b;max-width:320px;">{{ $item->name }}</td>
                    <td style="font-size:11.5px;color:#64748b;">
                        <div><strong>Akun:</strong> {{ $item->account->code }} - {{ Str::limit($item->account->name, 25) }}</div>
                        <div style="color:#94a3b8;">{{ $item->account->subComponent->component->subOutput->code }}</div>
                    </td>
                    <td><span class="pagu-badge">Rp {{ number_format($item->pagu, 0, ',', '.') }}</span></td>
                    <td>
                        <span style="font-weight:600;color:{{ $item->documents->count() > 0 ? '#16a34a' : '#94a3b8' }}; font-size:12.5px;">
                            📄 {{ $item->documents->count() }} file
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $item->status_badge_class }}">
                            @if($item->verification_status === 'APPROVED') ✅ Siap Cair
                            @elseif($item->verification_status === 'REJECTED') ❌ Ditolak
                            @else ⏳ Menunggu
                            @endif
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('items.show', $item) }}" class="btn btn-primary btn-sm">
                            Buka Detail →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:40px;color:#94a3b8;">
                        🔍 Tidak ada item kegiatan yang sesuai dengan kriteria pencarian/filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@else
{{-- ── FULL POK HIERARCHY TREEVIEW BROWSER ──────────────────── --}}
<div x-data="pokBrowser()" style="display:flex;flex-direction:column;gap:16px;">

    {{-- Expand / Collapse Controls --}}
    <div style="display:flex;align-items:center;justify-content:space-between;padding:0 4px;">
        <span style="font-size:13px;font-weight:700;color:#334155;">Structure Hirarki POK (7-Level)</span>
        <div style="display:flex;gap:8px;">
            <button type="button" @click="expandAll()" class="btn btn-secondary btn-sm">➕ Buka Semua Accordion</button>
            <button type="button" @click="collapseAll()" class="btn btn-secondary btn-sm">➖ Tutup Semua Accordion</button>
        </div>
    </div>

    @foreach($programs as $program)
    <div style="background:#fff;border-radius:12px;border:1px solid #cbd5e1;overflow:hidden;box-shadow:0 2px 6px rgba(0,0,0,.02);">
        
        {{-- LEVEL 1: PROGRAM --}}
        <div @click="toggle('prog_{{ $program->id }}')"
             style="background:linear-gradient(135deg,#003087,#0d47a1);color:#fff;padding:14px 20px;cursor:pointer;display:flex;align-items:center;justify-content:space-between;user-select:none;">
            <div style="display:flex;align-items:center;gap:12px;">
                <span class="caret-icon" :class="{ 'open': isOpen('prog_{{ $program->id }}') }">▶</span>
                <span style="background:rgba(255,255,255,.2);padding:2px 8px;border-radius:6px;font-size:11px;font-weight:700;letter-spacing:.5px;">PROGRAM</span>
                <span style="font-size:15px;font-weight:700;">[{{ $program->code }}] {{ $program->name }}</span>
            </div>
            <span style="font-size:12px;opacity:.85;">{{ $program->outputs->count() }} Output</span>
        </div>

        <div x-show="isOpen('prog_{{ $program->id }}')" x-transition style="padding:16px 20px;background:#f8fafc;border-top:1px solid #e2e8f0;">
            @foreach($program->outputs as $output)
            
            {{-- LEVEL 2: OUTPUT --}}
            <div style="background:#fff;border-radius:10px;border:1px solid #e2e8f0;margin-bottom:12px;overflow:hidden;">
                <div @click.stop="toggle('out_{{ $output->id }}')"
                     style="background:#f1f5f9;padding:12px 16px;cursor:pointer;display:flex;align-items:center;justify-content:space-between;user-select:none;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span class="caret-icon" :class="{ 'open': isOpen('out_{{ $output->id }}') }">▶</span>
                        <span style="background:#cbd5e1;color:#334155;padding:2px 6px;border-radius:4px;font-size:10.5px;font-weight:700;">OUTPUT</span>
                        <span style="font-size:13.5px;font-weight:700;color:#1e293b;">[{{ $output->code }}] {{ $output->name }}</span>
                    </div>
                    <span style="font-size:11.5px;color:#64748b;">{{ $output->subOutputs->count() }} Sub-Output</span>
                </div>

                <div x-show="isOpen('out_{{ $output->id }}')" x-transition style="padding:14px 16px;">
                    @foreach($output->subOutputs as $subOutput)
                    
                    {{-- LEVEL 3: SUB-OUTPUT --}}
                    <div style="border-left:3px solid #003087;padding-left:14px;margin-bottom:14px;">
                        <div @click.stop="toggle('so_{{ $subOutput->id }}')"
                             style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;padding:6px 0;user-select:none;">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span class="caret-icon" :class="{ 'open': isOpen('so_{{ $subOutput->id }}') }">▶</span>
                                <span style="background:#e8f0fe;color:#003087;padding:2px 6px;border-radius:4px;font-size:11px;font-weight:700;">
                                    SUB-OUTPUT: {{ $subOutput->code }}
                                </span>
                                <span style="font-size:13px;font-weight:600;color:#0f172a;">{{ $subOutput->name }}</span>
                                @if(str_contains($subOutput->code, 'BMA.006'))
                                    <span style="background:#fef9c3;color:#854d0e;border:1px solid #fef08a;padding:1px 6px;border-radius:10px;font-size:10px;font-weight:700;">🎯 FOKUS MVP CORE</span>
                                @endif
                            </div>
                        </div>

                        <div x-show="isOpen('so_{{ $subOutput->id }}')" x-transition style="margin-top:10px;display:flex;flex-direction:column;gap:10px;">
                            @foreach($subOutput->components as $component)
                            
                            {{-- LEVEL 4: KOMPONEN --}}
                            <div style="background:#fafafa;border-radius:8px;border:1px solid #e2e8f0;padding:10px 14px;">
                                <div @click.stop="toggle('comp_{{ $component->id }}')"
                                     style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;user-select:none;">
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <span class="caret-icon" :class="{ 'open': isOpen('comp_{{ $component->id }}') }">▶</span>
                                        <span style="font-size:12px;font-weight:700;color:#475569;">Komponen [{{ $component->code }}]</span>
                                        <span style="font-size:12px;color:#334155;">{{ $component->name }}</span>
                                    </div>
                                </div>

                                <div x-show="isOpen('comp_{{ $component->id }}')" x-transition style="margin-top:8px;padding-left:12px;display:flex;flex-direction:column;gap:8px;">
                                    @foreach($component->subComponents as $subComp)
                                    
                                    {{-- LEVEL 5: SUB-KOMPONEN --}}
                                    <div style="background:#fff;border-radius:6px;border:1px dashed #cbd5e1;padding:8px 12px;">
                                        <div @click.stop="toggle('sc_{{ $subComp->id }}')"
                                             style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;user-select:none;">
                                            <div style="display:flex;align-items:center;gap:6px;">
                                                <span class="caret-icon" :class="{ 'open': isOpen('sc_{{ $subComp->id }}') }">▶</span>
                                                <span style="font-size:11.5px;font-weight:600;color:#64748b;">Sub-Komponen [{{ $subComp->code }}]: {{ $subComp->name }}</span>
                                            </div>
                                        </div>

                                        <div x-show="isOpen('sc_{{ $subComp->id }}')" x-transition style="margin-top:8px;display:flex;flex-direction:column;gap:6px;">
                                            @foreach($subComp->accounts as $account)
                                            
                                            {{-- LEVEL 6: AKUN --}}
                                            <div style="background:#f8fafc;border-radius:6px;padding:8px 10px;border-left:3px solid #f5a623;">
                                                <div @click.stop="toggle('acc_{{ $account->id }}')"
                                                     style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;user-select:none;">
                                                    <div style="display:flex;align-items:center;gap:8px;">
                                                        <span class="caret-icon" :class="{ 'open': isOpen('acc_{{ $account->id }}') }">▶</span>
                                                        <span style="font-size:12px;font-weight:700;color:#003087;">Akun {{ $account->code }}</span>
                                                        <span style="font-size:12px;color:#334155;">{{ $account->name }}</span>
                                                    </div>
                                                    <span style="font-size:11px;color:#94a3b8;">{{ $account->items->count() }} Item</span>
                                                </div>

                                                <div x-show="isOpen('acc_{{ $account->id }}')" x-transition style="margin-top:8px;display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:8px;">
                                                    @foreach($account->items as $item)
                                                    
                                                    {{-- LEVEL 7: ITEM KEGIATAN --}}
                                                    <a href="{{ route('items.show', $item) }}"
                                                       style="display:flex;align-items:center;justify-content:space-between;padding:8px 12px;background:#fff;border:1px solid #e2e8f0;border-radius:6px;text-decoration:none;transition:all .15s;"
                                                       onmouseover="this.style.borderColor='#003087';this.style.boxShadow='0 2px 8px rgba(0,48,135,.1)';"
                                                       onmouseout="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';">
                                                        <div style="min-width:0;flex:1;margin-right:8px;">
                                                            <div style="display:flex;align-items:center;gap:6px;margin-bottom:2px;">
                                                                <code style="font-size:11px;font-weight:700;color:#003087;background:#e8f0fe;padding:1px 5px;border-radius:4px;">{{ $item->code }}</code>
                                                                <span class="badge {{ $item->status_badge_class }}" style="font-size:9.5px;padding:1px 6px;">
                                                                    @if($item->verification_status === 'APPROVED') ✅
                                                                    @elseif($item->verification_status === 'REJECTED') ❌
                                                                    @else ⏳
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            <div style="font-size:12px;font-weight:500;color:#1e293b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                                {{ $item->name }}
                                                            </div>
                                                        </div>
                                                        <div style="text-align:right;flex-shrink:0;">
                                                            <div style="font-size:11px;font-weight:700;color:#0f172a;">Rp {{ number_format($item->pagu, 0, ',', '.') }}</div>
                                                            <div style="font-size:10px;color:#94a3b8;">{{ $item->documents->count() }} file</div>
                                                        </div>
                                                    </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

</div>
@endif

<style>
.caret-icon {
    display: inline-block;
    font-size: 10px;
    transition: transform .2s ease;
}
.caret-icon.open {
    transform: rotate(90deg);
}
</style>

@push('scripts')
<script>
function pokBrowser() {
    return {
        openNodes: {
            'prog_1': true,
            'out_1': true,
            'so_1': true,
            'comp_1': true,
            'sc_1': true,
            'acc_1': true
        },

        toggle(key) {
            this.openNodes[key] = !this.openNodes[key];
        },
        isOpen(key) {
            return this.openNodes[key] || false;
        },
        expandAll() {
            document.querySelectorAll('[x-show]').forEach(el => el.style.display = '');
            // Set state to true for all keys dynamically
            @foreach($programs as $p)
                this.openNodes['prog_{{ $p->id }}'] = true;
                @foreach($p->outputs as $o)
                    this.openNodes['out_{{ $o->id }}'] = true;
                    @foreach($o->subOutputs as $so)
                        this.openNodes['so_{{ $so->id }}'] = true;
                        @foreach($so->components as $c)
                            this.openNodes['comp_{{ $c->id }}'] = true;
                            @foreach($c->subComponents as $sc)
                                this.openNodes['sc_{{ $sc->id }}'] = true;
                                @foreach($sc->accounts as $a)
                                    this.openNodes['acc_{{ $a->id }}'] = true;
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
            @endforeach
        },
        collapseAll() {
            this.openNodes = {};
        }
    };
}
</script>
@endpush
@endsection
