{{-- Sidebar POK Treeview Navigation --}}
<div class="sidebar" :class="{ 'open': sidebarOpen }" x-data="pokSidebar()">

    {{-- Logo & Branding --}}
    <div class="sidebar-logo">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
            <div style="width:36px;height:36px;background:#fff;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <span style="font-size:20px;">📊</span>
            </div>
            <div>
                <div class="app-name">Arsip Keuangan BPS</div>
                <div class="app-sub">Kabupaten Subang</div>
            </div>
        </div>
        <div style="font-size:10px;color:rgba(255,255,255,.4);margin-top:4px;">
            Tahun Anggaran: <strong style="color:rgba(255,255,255,.8);">2026</strong>
        </div>
    </div>

    {{-- Search --}}
    <div class="sidebar-search">
        <input type="text" x-model="search" placeholder="🔍 Cari kode / nama item..." @input="filterTree()">
    </div>

    {{-- Navigation --}}
    <div class="sidebar-nav">

        {{-- Dashboard Link --}}
        <a href="{{ route('dashboard') }}"
           class="tree-leaf {{ request()->routeIs('dashboard') ? 'active' : '' }}"
           style="margin-bottom:4px;">
            🏠 <span>Dashboard</span>
        </a>

        {{-- Dynamic POK Treeview --}}
        @php
            $programs = \App\Models\Program::with([
                'outputs.subOutputs.components.subComponents.accounts.items'
            ])->get();
        @endphp

        @foreach($programs as $program)
        <div class="tree-item" x-show="shouldShow('{{ strtolower($program->code) }}', '{{ strtolower($program->name) }}')">
            <div class="tree-toggle" :class="{ 'open': isOpen('prog_{{ $program->id }}') }"
                 @click="toggle('prog_{{ $program->id }}')">
                <span class="caret">▶</span>
                <span style="font-size:10px;color:rgba(255,255,255,.4);">[{{ $program->code }}]</span>
                <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ Str::limit($program->name, 28) }}</span>
            </div>
            <div class="tree-children" x-show="isOpen('prog_{{ $program->id }}')" x-transition>
                @foreach($program->outputs as $output)
                <div class="tree-item" x-show="shouldShow('{{ strtolower($output->code) }}', '{{ strtolower($output->name) }}')">
                    <div class="tree-toggle" :class="{ 'open': isOpen('out_{{ $output->id }}') }"
                         @click="toggle('out_{{ $output->id }}')">
                        <span class="caret">▶</span>
                        <span style="font-size:10px;color:rgba(255,255,255,.4);">[{{ $output->code }}]</span>
                        <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ Str::limit($output->name, 25) }}</span>
                    </div>
                    <div class="tree-children" x-show="isOpen('out_{{ $output->id }}')" x-transition>
                        @foreach($output->subOutputs as $subOutput)
                        <div class="tree-item" x-show="shouldShow('{{ strtolower($subOutput->code) }}', '{{ strtolower($subOutput->name) }}')">
                            <div class="tree-toggle" :class="{ 'open': isOpen('so_{{ $subOutput->id }}') }"
                                 @click="toggle('so_{{ $subOutput->id }}')">
                                <span class="caret">▶</span>
                                <span style="font-size:10px;color:rgba(255,255,255,.4);">[{{ $subOutput->code }}]</span>
                                <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ Str::limit($subOutput->name, 22) }}</span>
                            </div>
                            <div class="tree-children" x-show="isOpen('so_{{ $subOutput->id }}')" x-transition>
                                @foreach($subOutput->components as $component)
                                <div class="tree-item" x-show="shouldShow('{{ strtolower($component->code) }}', '{{ strtolower($component->name) }}')">
                                    <div class="tree-toggle" :class="{ 'open': isOpen('comp_{{ $component->id }}') }"
                                         @click="toggle('comp_{{ $component->id }}')">
                                        <span class="caret">▶</span>
                                        <span style="font-size:10px;color:rgba(255,255,255,.35);">[{{ $component->code }}]</span>
                                        <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ Str::limit($component->name, 20) }}</span>
                                    </div>
                                    <div class="tree-children" x-show="isOpen('comp_{{ $component->id }}')" x-transition>
                                        @foreach($component->subComponents as $subComp)
                                        <div class="tree-item">
                                            <div class="tree-toggle" :class="{ 'open': isOpen('sc_{{ $subComp->id }}') }"
                                                 @click="toggle('sc_{{ $subComp->id }}')">
                                                <span class="caret">▶</span>
                                                <span style="font-size:9.5px;color:rgba(255,255,255,.3);">[{{ $subComp->code }}]</span>
                                                <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:10.5px;">{{ Str::limit($subComp->name, 18) }}</span>
                                            </div>
                                            <div class="tree-children" x-show="isOpen('sc_{{ $subComp->id }}')" x-transition>
                                                @foreach($subComp->accounts as $account)
                                                <div class="tree-item">
                                                    <div class="tree-toggle" :class="{ 'open': isOpen('acc_{{ $account->id }}') }"
                                                         @click="toggle('acc_{{ $account->id }}')">
                                                        <span class="caret">▶</span>
                                                        <span style="font-size:9px;color:rgba(255,255,255,.3);">[{{ $account->code }}]</span>
                                                        <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:10px;">{{ Str::limit($account->name, 16) }}</span>
                                                    </div>
                                                    <div class="tree-children" x-show="isOpen('acc_{{ $account->id }}')" x-transition>
                                                        @foreach($account->items as $item)
                                                        <a href="{{ route('items.show', $item) }}"
                                                           class="tree-leaf {{ request()->route('item') && request()->route('item')->id === $item->id ? 'active' : '' }}"
                                                           x-show="shouldShow('{{ strtolower($item->code) }}', '{{ addslashes(strtolower($item->name)) }}')">
                                                            <span class="badge {{ $item->status_badge_class }}" style="padding:1px 5px;font-size:9px;">
                                                                @if($item->verification_status === 'APPROVED') ✓
                                                                @elseif($item->verification_status === 'REJECTED') ✕
                                                                @else ●
                                                                @endif
                                                            </span>
                                                            <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                                ({{ $item->code }})
                                                            </span>
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

        {{-- Master Data Link --}}
        @if(auth()->user()->canManageMaster())
        <div style="border-top:1px solid rgba(255,255,255,.1);margin:8px 6px;"></div>
        <a href="{{ route('master.index') }}"
           class="tree-leaf {{ request()->routeIs('master.*') ? 'active' : '' }}">
            ⚙️ <span>Kelola Master POK</span>
        </a>
        @endif

        {{-- User Management --}}
        @if(auth()->user()->isAdmin())
        <a href="{{ route('users.index') }}"
           class="tree-leaf {{ request()->routeIs('users.*') ? 'active' : '' }}">
            👥 <span>Manajemen Pengguna</span>
        </a>
        @endif
    </div>

    {{-- User Info --}}
    <div class="sidebar-user">
        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div class="user-info" style="min-width:0;">
            <div class="user-name" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ auth()->user()->name }}</div>
            <span class="user-role">{{ auth()->user()->role }}</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
function pokSidebar() {
    return {
        search: '',
        openNodes: {},

        toggle(key) {
            this.openNodes[key] = !this.openNodes[key];
        },
        isOpen(key) {
            return this.openNodes[key] || false;
        },
        shouldShow(code, name) {
            if (!this.search) return true;
            const q = this.search.toLowerCase();
            return code.includes(q) || name.includes(q);
        },
        filterTree() {
            // Auto-expand all when searching
            if (this.search) {
                document.querySelectorAll('.tree-children').forEach(el => el.style.display = '');
            }
        }
    };
}
</script>
@endpush
