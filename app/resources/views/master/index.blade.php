@extends('layouts.app')
@section('title', 'Manajemen Master Data POK')
@section('page-title', '⚙️ Kelola Master Data POK')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
    <div>
        <h1 style="font-size:18px;font-weight:800;color:#0f172a;margin:0;">Manajemen Master Data POK</h1>
        <p style="font-size:12.5px;color:#64748b;margin-top:4px;">Tambah dan kelola hirarki anggaran POK tanpa mengubah kode program</p>
    </div>
</div>

{{-- ── TABS ── --}}
<div x-data="{ tab: 'items' }">
    <div style="display:flex;gap:4px;margin-bottom:20px;background:#f1f5f9;padding:4px;border-radius:10px;overflow-x:auto;">
        @foreach([
            ['id' => 'items', 'label' => '📋 Item Kegiatan'],
            ['id' => 'accounts', 'label' => '💳 Akun'],
            ['id' => 'subcomponents', 'label' => '🔷 Sub-Komponen'],
            ['id' => 'components', 'label' => '🔶 Komponen'],
            ['id' => 'suboutputs', 'label' => '📦 Sub-Output'],
            ['id' => 'outputs', 'label' => '📁 Output'],
            ['id' => 'fiscal', 'label' => '📅 Tahun Anggaran'],
        ] as $t)
        <button @click="tab = '{{ $t['id'] }}'"
                :class="tab === '{{ $t['id'] }}' ? 'btn btn-primary' : 'btn btn-secondary'"
                style="white-space:nowrap;font-size:12px;padding:7px 14px;">
            {{ $t['label'] }}
        </button>
        @endforeach
    </div>

    {{-- ── ITEMS TAB ── --}}
    <div x-show="tab === 'items'" x-transition>
        <div style="display:grid;grid-template-columns:1fr 360px;gap:20px;align-items:start;">
            <div class="table-card">
                <div style="padding:14px 16px;border-bottom:1px solid #e2e8f0;font-size:13px;font-weight:600;color:#374151;">
                    Daftar Item Kegiatan ({{ $items->count() }} item)
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Item</th>
                            <th>Akun</th>
                            <th>Pagu</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td><code style="font-size:11.5px;background:#f1f5f9;padding:2px 6px;border-radius:4px;">{{ $item->code }}</code></td>
                            <td style="font-size:12.5px;max-width:280px;">{{ Str::limit($item->name, 50) }}</td>
                            <td style="font-size:11.5px;color:#64748b;">
                                [{{ $item->account->code }}]<br>
                                <span style="font-size:10.5px;color:#94a3b8;">{{ Str::limit($item->account->subComponent->component->subOutput->code, 12) }}</span>
                            </td>
                            <td style="font-size:12px;font-weight:600;color:#003087;">Rp {{ number_format($item->pagu, 0, ',', '.') }}</td>
                            <td><span class="badge {{ $item->status_badge_class }}">{{ $item->status_label }}</span></td>
                            <td>
                                <div style="display:flex;gap:4px;">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                            @click="$dispatch('open-edit-item', {{ $item->toJson() }})">
                                        ✏️
                                    </button>
                                    <form action="{{ route('master.items.destroy', $item) }}" method="POST"
                                          onsubmit="return confirm('Hapus item {{ $item->code }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Add Item Form --}}
            <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:20px;position:sticky;top:76px;"
                 x-data="{ editMode: false, editItem: null }"
                 @open-edit-item.window="editItem = $event.detail; editMode = true">

                <div style="font-size:13.5px;font-weight:700;color:#0f172a;margin-bottom:14px;">
                    <span x-show="!editMode">➕ Tambah Item Baru</span>
                    <span x-show="editMode">✏️ Edit Item</span>
                </div>

                {{-- Add Form --}}
                <form x-show="!editMode" action="{{ route('master.items.store') }}" method="POST" style="display:flex;flex-direction:column;gap:12px;">
                    @csrf
                    <div>
                        <label class="form-label">Akun *</label>
                        <select name="account_id" class="form-input" required>
                            <option value="">— Pilih Akun —</option>
                            @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}">[{{ $acc->code }}] {{ Str::limit($acc->name, 35) }} ({{ $acc->subComponent->component->subOutput->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Kode Item *</label>
                        <input type="text" name="code" class="form-input" placeholder="contoh: 001366" maxlength="10" required>
                    </div>
                    <div>
                        <label class="form-label">Nama Item *</label>
                        <input type="text" name="name" class="form-input" placeholder="Nama lengkap kegiatan/item" required>
                    </div>
                    <div>
                        <label class="form-label">Pagu Anggaran (Rp) *</label>
                        <input type="number" name="pagu" class="form-input" placeholder="925600000" min="0" step="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary">➕ Tambah Item</button>
                </form>

                {{-- Edit Form --}}
                <template x-if="editMode && editItem">
                    <form :action="'/master/items/' + editItem.id" method="POST" style="display:flex;flex-direction:column;gap:12px;">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <div>
                            <label class="form-label">Kode Item</label>
                            <input type="text" class="form-input" :value="editItem.code" disabled style="background:#f1f5f9;">
                        </div>
                        <div>
                            <label class="form-label">Nama Item *</label>
                            <input type="text" name="name" class="form-input" :value="editItem.name" required>
                        </div>
                        <div>
                            <label class="form-label">Pagu Anggaran (Rp) *</label>
                            <input type="number" name="pagu" class="form-input" :value="editItem.pagu" min="0" step="1" required>
                        </div>
                        <div style="display:flex;gap:8px;">
                            <button type="submit" class="btn btn-primary" style="flex:1;">💾 Simpan</button>
                            <button type="button" @click="editMode = false; editItem = null" class="btn btn-secondary">Batal</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>

    {{-- ── ACCOUNTS TAB ── --}}
    <div x-show="tab === 'accounts'" x-transition>
        <div style="display:grid;grid-template-columns:1fr 320px;gap:20px;">
            <div class="table-card">
                <div style="padding:14px 16px;border-bottom:1px solid #e2e8f0;font-size:13px;font-weight:600;color:#374151;">Daftar Akun ({{ $accounts->count() }})</div>
                <table>
                    <thead><tr><th>Kode</th><th>Nama Akun</th><th>Sub-Komponen</th></tr></thead>
                    <tbody>
                        @foreach($accounts as $acc)
                        <tr>
                            <td><code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:12px;">{{ $acc->code }}</code></td>
                            <td style="font-size:12.5px;">{{ $acc->name }}</td>
                            <td style="font-size:11.5px;color:#64748b;">{{ $acc->subComponent->code }} — {{ Str::limit($acc->subComponent->component->subOutput->code, 10) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:20px;">
                <div style="font-size:13.5px;font-weight:700;color:#0f172a;margin-bottom:14px;">➕ Tambah Akun</div>
                <form action="{{ route('master.accounts.store') }}" method="POST" style="display:flex;flex-direction:column;gap:12px;">
                    @csrf
                    <div>
                        <label class="form-label">Sub-Komponen *</label>
                        <select name="sub_component_id" class="form-input" required>
                            <option value="">— Pilih Sub-Komponen —</option>
                            @foreach($subComponents as $sc)
                            <option value="{{ $sc->id }}">[{{ $sc->code }}] {{ Str::limit($sc->name, 30) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Kode Akun *</label>
                        <input type="text" name="code" class="form-input" placeholder="contoh: 521213" maxlength="10" required>
                    </div>
                    <div>
                        <label class="form-label">Nama Akun *</label>
                        <input type="text" name="name" class="form-input" placeholder="Belanja Honor Output Kegiatan" required>
                    </div>
                    <button type="submit" class="btn btn-primary">➕ Tambah Akun</button>
                </form>
            </div>
        </div>
    </div>

    {{-- ── FISCAL YEAR TAB ── --}}
    <div x-show="tab === 'fiscal'" x-transition>
        <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;">
            <div class="table-card">
                <div style="padding:14px 16px;border-bottom:1px solid #e2e8f0;font-size:13px;font-weight:600;">Tahun Anggaran</div>
                <table>
                    <thead><tr><th>Tahun</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($fiscalYears as $fy)
                        <tr>
                            <td style="font-size:15px;font-weight:700;color:#003087;">{{ $fy->year }}</td>
                            <td>
                                <span class="badge {{ $fy->is_active ? 'badge-approved' : 'badge-rejected' }}">
                                    {{ $fy->is_active ? '✅ Aktif' : '⏸ Nonaktif' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:20px;">
                <div style="font-size:13.5px;font-weight:700;color:#0f172a;margin-bottom:14px;">➕ Tambah Tahun Anggaran</div>
                <form action="{{ route('master.fiscal-years.store') }}" method="POST" style="display:flex;flex-direction:column;gap:12px;">
                    @csrf
                    <div>
                        <label class="form-label">Tahun Anggaran *</label>
                        <input type="number" name="year" class="form-input" placeholder="2027" min="2024" max="2099" required>
                    </div>
                    <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;">
                        <input type="checkbox" name="is_active" value="1" checked> Jadikan Tahun Aktif
                    </label>
                    <button type="submit" class="btn btn-primary">➕ Tambah</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Other tabs: sub-outputs, components, sub-components, outputs --}}
    <div x-show="tab === 'suboutputs'" x-transition>
        <div style="display:grid;grid-template-columns:1fr 320px;gap:20px;">
            <div class="table-card">
                <div style="padding:14px 16px;border-bottom:1px solid #e2e8f0;font-size:13px;font-weight:600;">Sub-Output ({{ $subOutputs->count() }})</div>
                <table>
                    <thead><tr><th>Kode</th><th>Nama</th><th>Output</th></tr></thead>
                    <tbody>
                        @foreach($subOutputs as $so)
                        <tr>
                            <td><code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:12px;">{{ $so->code }}</code></td>
                            <td style="font-size:12.5px;">{{ Str::limit($so->name, 45) }}</td>
                            <td style="font-size:11.5px;color:#64748b;">{{ $so->output->code }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:20px;">
                <div style="font-size:13.5px;font-weight:700;margin-bottom:14px;">➕ Tambah Sub-Output</div>
                <form action="{{ route('master.sub-outputs.store') }}" method="POST" style="display:flex;flex-direction:column;gap:12px;">
                    @csrf
                    <div>
                        <label class="form-label">Output *</label>
                        <select name="output_id" class="form-input" required>
                            @foreach($outputs as $out)<option value="{{ $out->id }}">[{{ $out->code }}] {{ $out->name }}</option>@endforeach
                        </select>
                    </div>
                    <div><label class="form-label">Kode *</label><input type="text" name="code" class="form-input" placeholder="BMA.006" required></div>
                    <div><label class="form-label">Nama *</label><input type="text" name="name" class="form-input" required></div>
                    <button type="submit" class="btn btn-primary">➕ Tambah</button>
                </form>
            </div>
        </div>
    </div>

    <div x-show="tab === 'components'" x-transition>
        <div style="display:grid;grid-template-columns:1fr 320px;gap:20px;">
            <div class="table-card">
                <div style="padding:14px 16px;border-bottom:1px solid #e2e8f0;font-size:13px;font-weight:600;">Komponen ({{ $components->count() }})</div>
                <table>
                    <thead><tr><th>Kode</th><th>Nama</th><th>Sub-Output</th></tr></thead>
                    <tbody>
                        @foreach($components as $comp)
                        <tr>
                            <td><code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:12px;">{{ $comp->code }}</code></td>
                            <td style="font-size:12.5px;">{{ Str::limit($comp->name, 40) }}</td>
                            <td style="font-size:11.5px;color:#64748b;">{{ $comp->subOutput->code }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:20px;">
                <div style="font-size:13.5px;font-weight:700;margin-bottom:14px;">➕ Tambah Komponen</div>
                <form action="{{ route('master.components.store') }}" method="POST" style="display:flex;flex-direction:column;gap:12px;">
                    @csrf
                    <div>
                        <label class="form-label">Sub-Output *</label>
                        <select name="sub_output_id" class="form-input" required>
                            @foreach($subOutputs as $so)<option value="{{ $so->id }}">[{{ $so->code }}] {{ Str::limit($so->name, 35) }}</option>@endforeach
                        </select>
                    </div>
                    <div><label class="form-label">Kode *</label><input type="text" name="code" class="form-input" placeholder="005" required></div>
                    <div><label class="form-label">Nama *</label><input type="text" name="name" class="form-input" required></div>
                    <button type="submit" class="btn btn-primary">➕ Tambah</button>
                </form>
            </div>
        </div>
    </div>

    <div x-show="tab === 'subcomponents'" x-transition>
        <div style="display:grid;grid-template-columns:1fr 320px;gap:20px;">
            <div class="table-card">
                <div style="padding:14px 16px;border-bottom:1px solid #e2e8f0;font-size:13px;font-weight:600;">Sub-Komponen ({{ $subComponents->count() }})</div>
                <table>
                    <thead><tr><th>Kode</th><th>Nama</th><th>Komponen</th></tr></thead>
                    <tbody>
                        @foreach($subComponents as $sc)
                        <tr>
                            <td><code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:12px;">{{ $sc->code }}</code></td>
                            <td style="font-size:12.5px;">{{ Str::limit($sc->name, 35) }}</td>
                            <td style="font-size:11.5px;color:#64748b;">{{ $sc->component->code }} / {{ $sc->component->subOutput->code }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:20px;">
                <div style="font-size:13.5px;font-weight:700;margin-bottom:14px;">➕ Tambah Sub-Komponen</div>
                <form action="{{ route('master.sub-components.store') }}" method="POST" style="display:flex;flex-direction:column;gap:12px;">
                    @csrf
                    <div>
                        <label class="form-label">Komponen *</label>
                        <select name="component_id" class="form-input" required>
                            @foreach($components as $c)<option value="{{ $c->id }}">[{{ $c->code }}] {{ Str::limit($c->name, 30) }} ({{ $c->subOutput->code }})</option>@endforeach
                        </select>
                    </div>
                    <div><label class="form-label">Kode *</label><input type="text" name="code" class="form-input" placeholder="005.0A" required></div>
                    <div><label class="form-label">Nama *</label><input type="text" name="name" class="form-input" placeholder="TANPA SUB KOMPONEN" required></div>
                    <button type="submit" class="btn btn-primary">➕ Tambah</button>
                </form>
            </div>
        </div>
    </div>

    <div x-show="tab === 'outputs'" x-transition>
        <div style="display:grid;grid-template-columns:1fr 320px;gap:20px;">
            <div class="table-card">
                <div style="padding:14px 16px;border-bottom:1px solid #e2e8f0;font-size:13px;font-weight:600;">Output ({{ $outputs->count() }})</div>
                <table>
                    <thead><tr><th>Kode</th><th>Nama</th><th>Program</th></tr></thead>
                    <tbody>
                        @foreach($outputs as $out)
                        <tr>
                            <td><code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:12px;">{{ $out->code }}</code></td>
                            <td style="font-size:12.5px;">{{ $out->name }}</td>
                            <td style="font-size:11.5px;color:#64748b;">{{ $out->program->code }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:20px;">
                <div style="font-size:13.5px;font-weight:700;margin-bottom:14px;">➕ Tambah Output</div>
                <form action="{{ route('master.outputs.store') }}" method="POST" style="display:flex;flex-direction:column;gap:12px;">
                    @csrf
                    <div>
                        <label class="form-label">Program *</label>
                        <select name="program_id" class="form-input" required>
                            @foreach($programs as $p)<option value="{{ $p->id }}">[{{ $p->code }}] {{ Str::limit($p->name, 35) }}</option>@endforeach
                        </select>
                    </div>
                    <div><label class="form-label">Kode *</label><input type="text" name="code" class="form-input" placeholder="BMA" required></div>
                    <div><label class="form-label">Nama *</label><input type="text" name="name" class="form-input" required></div>
                    <button type="submit" class="btn btn-primary">➕ Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
