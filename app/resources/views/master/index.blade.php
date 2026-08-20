@extends('layouts.app')
@section('title', 'Manajemen Master Data POK')

@section('content')
<div class="space-y-8" x-data="{ tab: 'items' }">

    {{-- Page Header --}}
    <div class="sakdi-card p-8 flex items-center justify-between flex-wrap gap-6"
         style="border-left: 4px solid var(--color-primary);">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg mb-2 text-xs font-extrabold"
                 style="background: var(--color-primary-50); border: 1px solid var(--color-primary-100); color: var(--color-primary-900);">
                <span>⚙️ KHUSUS SUPERVISOR &amp; ADMIN</span>
            </div>
            <h1 class="text-2xl font-black tracking-tight" style="color: var(--color-neutral-900);">
                Manajemen Master Data POK
            </h1>
            <p class="text-xs sm:text-sm font-medium mt-1" style="color: var(--color-neutral-500);">
                Kelola hirarki 8-level POK (Program, Output, Sub-Output, Komponen, Sub-Komponen, Akun, Item, &amp; Tahun Anggaran).
            </p>
        </div>
    </div>

    {{-- TABS BAR --}}
    <div class="sakdi-tabs mb-6">
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
                :class="tab === '{{ $t['id'] }}' ? 'sakdi-tab-item active' : 'sakdi-tab-item'">
            {{ $t['label'] }}
        </button>
        @endforeach
    </div>

    {{-- ITEMS TAB --}}
    <div x-show="tab === 'items'" x-transition>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 sakdi-table-wrapper">
                <div class="px-6 py-4 flex items-center justify-between"
                     style="background: var(--color-neutral-50); border-bottom: 1px solid var(--color-neutral-300);">
                    <h2 class="text-sm font-extrabold" style="color: var(--color-neutral-900);">
                        Daftar Item Kegiatan ({{ $items->total() }} Total Item)
                    </h2>
                </div>
                <table class="sakdi-table">
                    <thead>
                        <tr>
                            <th class="w-28 text-center">Kode</th>
                            <th>Nama Item Kegiatan</th>
                            <th>Akun / Sub-Output</th>
                            <th class="text-right">Pagu</th>
                            <th class="text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td class="text-center whitespace-nowrap">
                                <span class="num-mono text-xs font-bold px-2.5 py-1 rounded-lg"
                                      style="color: var(--color-primary-900); background: var(--color-primary-50); border: 1px solid var(--color-primary-100);">
                                    {{ $item->code }}
                                </span>
                            </td>
                            <td>
                                <div class="font-extrabold text-xs" style="color: var(--color-neutral-900);">
                                    {{ Str::limit($item->name, 50) }}
                                </div>
                            </td>
                            <td class="text-xs">
                                <div class="font-bold num-mono" style="color: var(--color-neutral-700);">[{{ $item->account->code }}]</div>
                                <div class="text-[10px] num-mono mt-0.5" style="color: var(--color-primary);">
                                    {{ Str::limit($item->account->subComponent->component->subOutput->code, 15) }}
                                </div>
                            </td>
                            <td class="text-right num-mono font-bold text-xs whitespace-nowrap" style="color: var(--color-positive-700);">
                                Rp {{ number_format($item->pagu, 0, ',', '.') }}
                            </td>
                            <td class="text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" class="sakdi-btn sakdi-btn-secondary sakdi-btn-sm"
                                            @click="$dispatch('open-edit-item', {{ $item->toJson() }})">
                                        ✏️
                                    </button>
                                    <form action="{{ route('master.items.destroy', $item) }}" method="POST"
                                          onsubmit="return confirm('Hapus item {{ $item->code }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="sakdi-btn sakdi-btn-danger sakdi-btn-sm">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Pagination Links for Items --}}
                <div class="px-6 py-4 border-t" style="background: var(--color-neutral-50); border-color: var(--color-neutral-300);">
                    {{ $items->appends(request()->query())->links() }}
                </div>
            </div>

            {{-- Form Column --}}
            <div class="sakdi-card p-6 lg:sticky lg:top-24"
                 x-data="{ editMode: false, editItem: null }"
                 @open-edit-item.window="editItem = $event.detail; editMode = true">
                <h3 class="text-sm font-extrabold mb-4" style="color: var(--color-neutral-900);" x-text="editMode ? 'Edit Item Kegiatan' : 'Tambah Item Baru'"></h3>
                <form :action="editMode ? '/master/items/' + editItem.id : '{{ route('master.items.store') }}'" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="sakdi-label sakdi-label-required">Akun POK</label>
                        <select name="account_id" class="sakdi-select" required x-model="editItem ? editItem.account_id : ''">
                            <option value="" disabled selected>-- Pilih Akun --</option>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">[{{ $acc->code }}] {{ Str::limit($acc->name, 35) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="sakdi-label sakdi-label-required">Kode Item (6 Digit)</label>
                        <input type="text" name="code" class="sakdi-input num-mono" placeholder="001366" maxlength="10" required x-model="editItem ? editItem.code : ''">
                    </div>

                    <div>
                        <label class="sakdi-label sakdi-label-required">Nama Item Kegiatan</label>
                        <input type="text" name="name" class="sakdi-input" placeholder="Nama item kegiatan..." required x-model="editItem ? editItem.name : ''">
                    </div>

                    <div>
                        <label class="sakdi-label sakdi-label-required">Pagu Anggaran (Rp)</label>
                        <input type="number" name="pagu" class="sakdi-input num-mono" placeholder="925600000" step="1" required x-model="editItem ? editItem.pagu : ''">
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="sakdi-btn sakdi-btn-primary flex-1">
                            <span x-text="editMode ? 'Simpan Perubahan' : 'Tambah Item'"></span>
                        </button>
                        <button type="button" x-show="editMode" @click="editMode = false; editItem = null" class="sakdi-btn sakdi-btn-secondary">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
