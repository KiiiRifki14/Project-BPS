@extends('layouts.app')
@section('title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-8">

    {{-- Header Card --}}
    <div class="sakdi-card p-8 flex items-center justify-between flex-wrap gap-6"
         style="border-left: 4px solid var(--color-primary);">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg mb-2 text-xs font-extrabold"
                 style="background: var(--color-primary-50); border: 1px solid var(--color-primary-100); color: var(--color-primary-900);">
                <span>👥 KHUSUS ADMINISTRATOR</span>
            </div>
            <h1 class="text-2xl font-black tracking-tight" style="color: var(--color-neutral-900);">
                Manajemen Pengguna Sistem
            </h1>
            <p class="text-xs sm:text-sm font-medium mt-1" style="color: var(--color-neutral-500);">
                Kelola hak akses pengguna, peranan RBAC (Admin, Supervisor, Operator, Bendahara), dan reset password.
            </p>
        </div>
    </div>

    {{-- Grid Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        {{-- Left: Users Table --}}
        <div class="lg:col-span-2 sakdi-table-wrapper">
            <div class="px-6 py-4 flex items-center justify-between"
                 style="background: var(--color-neutral-50); border-bottom: 1px solid var(--color-neutral-300);">
                <h2 class="text-sm font-extrabold" style="color: var(--color-neutral-900);">Daftar Pengguna Aktif ({{ $users->total() }} Total User)</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="sakdi-table">
                    <thead>
                        <tr>
                            <th>NIP / Username</th>
                            <th>Nama Lengkap</th>
                            <th>Role Access</th>
                            <th class="text-center w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="whitespace-nowrap">
                                <span class="num-mono text-xs font-bold px-3 py-1 rounded-lg"
                                      style="color: var(--color-primary-900); background: var(--color-primary-50); border: 1px solid var(--color-primary-100);">
                                    {{ $user->nip_username }}
                                </span>
                            </td>
                            <td>
                                <div class="font-extrabold text-sm" style="color: var(--color-neutral-900);">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span class="sakdi-badge sakdi-badge-primary ml-1">Anda</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @php
                                    $roleBadge = match($user->role) {
                                        'ADMIN'      => 'sakdi-badge-error',
                                        'SUPERVISOR' => 'sakdi-badge-primary',
                                        'BENDAHARA'  => 'sakdi-badge-warning',
                                        default      => 'sakdi-badge-success',
                                    };
                                @endphp
                                <span class="sakdi-badge {{ $roleBadge }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5" x-data>
                                    <button type="button" class="sakdi-btn sakdi-btn-secondary sakdi-btn-sm"
                                            @click="$dispatch('open-edit-user', {{ json_encode(['id' => $user->id, 'name' => $user->name, 'role' => $user->role]) }})">
                                        ✏️ Edit
                                    </button>

                                    <button type="button" class="sakdi-btn sakdi-btn-secondary sakdi-btn-sm"
                                            @click="$dispatch('open-reset-pw', {{ json_encode(['id' => $user->id, 'name' => $user->name]) }})">
                                        🔑 Reset
                                    </button>

                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST"
                                          onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="sakdi-btn sakdi-btn-danger sakdi-btn-sm">🗑️</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links for Users --}}
            <div class="px-6 py-4 border-t" style="background: var(--color-neutral-50); border-color: var(--color-neutral-300);">
                {{ $users->links() }}
            </div>
        </div>

        {{-- Right: Add User Form --}}
        <div class="sakdi-card p-6 lg:sticky lg:top-24">
            <h3 class="text-sm font-extrabold mb-4" style="color: var(--color-neutral-900);">➕ Tambah Pengguna Baru</h3>
            <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="sakdi-label sakdi-label-required" for="nip_username">NIP / Username</label>
                    <input type="text" id="nip_username" name="nip_username" class="sakdi-input num-mono"
                           placeholder="199501012020011001" required value="{{ old('nip_username') }}">
                    @error('nip_username')
                        <div class="sakdi-input-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="sakdi-label sakdi-label-required" for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="sakdi-input"
                           placeholder="Ahmad Fauzi, S.S.T." required value="{{ old('name') }}">
                    @error('name')
                        <div class="sakdi-input-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="sakdi-label sakdi-label-required" for="role">Hak Akses / Peran (RBAC)</label>
                    <select id="role" name="role" class="sakdi-select" required>
                        <option value="" disabled selected>-- Pilih Peran --</option>
                        <option value="OPERATOR" {{ old('role') === 'OPERATOR' ? 'selected' : '' }}>🟢 OPERATOR — Upload SPJ & Dokumen</option>
                        <option value="BENDAHARA" {{ old('role') === 'BENDAHARA' ? 'selected' : '' }}>🟡 BENDAHARA — Verifikasi & Persetujuan Pencairan</option>
                        <option value="SUPERVISOR" {{ old('role') === 'SUPERVISOR' ? 'selected' : '' }}>🔵 SUPERVISOR — Kelola Master POK & Monitoring</option>
                        <option value="ADMIN" {{ old('role') === 'ADMIN' ? 'selected' : '' }}>🔴 ADMIN — Akses Penuh Sistem</option>
                    </select>
                    @error('role')
                        <div class="sakdi-input-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="sakdi-label sakdi-label-required" for="password">Password Default</label>
                    <input type="password" id="password" name="password" class="sakdi-input"
                           placeholder="••••••••" required>
                    @error('password')
                        <div class="sakdi-input-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="sakdi-label sakdi-label-required" for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="sakdi-input"
                           placeholder="••••••••" required>
                </div>

                <button type="submit" class="sakdi-btn sakdi-btn-primary w-full py-3">
                    💾 Simpan Pengguna Baru
                </button>
            </form>
        </div>

    </div>

</div>
@endsection
