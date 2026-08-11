@extends('layouts.app')
@section('title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-8">

    {{-- Header Card --}}
    <div class="card-corporate p-8 flex items-center justify-between flex-wrap gap-6">
        <div>
            <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-200 text-blue-900 font-extrabold text-xs px-3.5 py-1.5 rounded-lg mb-2">
                <span>👥 KHUSUS ADMINISTRATOR</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen Pengguna Sistem</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">
                Kelola hak akses pengguna, peranan RBAC (Admin, Supervisor, Operator, Bendahara), dan reset password.
            </p>
        </div>
    </div>

    {{-- Grid Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        {{-- Left: Users Table --}}
        <div class="lg:col-span-2 table-container-v4">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-sm font-extrabold text-slate-900">Daftar Pengguna Aktif ({{ $users->total() }} Total User)</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="table-v4">
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
                                <span class="font-mono text-xs font-bold text-blue-900 bg-blue-50 border border-blue-200 px-3 py-1 rounded-lg">
                                    {{ $user->nip_username }}
                                </span>
                            </td>
                            <td>
                                <div class="font-extrabold text-slate-900 text-sm">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span class="text-[10px] font-black bg-blue-100 text-blue-900 px-2 py-0.5 rounded ml-1">Anda</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @php
                                    $roleBadge = match($user->role) {
                                        'ADMIN'      => 'bg-red-50 text-red-700 border-red-200',
                                        'SUPERVISOR' => 'bg-blue-50 text-blue-800 border-blue-200',
                                        'BENDAHARA'  => 'bg-amber-50 text-amber-800 border-amber-200',
                                        default      => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                    };
                                @endphp
                                <span class="text-xs font-extrabold px-3 py-1 rounded-md border {{ $roleBadge }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5" x-data>
                                    <button type="button" class="btn-bps btn-bps-secondary btn-bps-sm"
                                            @click="$dispatch('open-edit-user', {{ json_encode(['id' => $user->id, 'name' => $user->name, 'role' => $user->role]) }})">
                                        ✏️ Edit
                                    </button>

                                    <button type="button" class="btn-bps btn-bps-secondary btn-bps-sm"
                                            @click="$dispatch('open-reset-pw', {{ json_encode(['id' => $user->id, 'name' => $user->name]) }})">
                                        🔑 Reset
                                    </button>

                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST"
                                          onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-bps btn-bps-danger btn-bps-sm">🗑️</button>
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
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                {{ $users->links() }}
            </div>
        </div>

        {{-- Right: User Form Panel --}}
        <div class="space-y-6 lg:sticky lg:top-24"
             x-data="{
                panel: 'add',
                editUser: null,
                resetUser: null,
             }"
             @open-edit-user.window="editUser = $event.detail; panel = 'edit'"
             @open-reset-pw.window="resetUser = $event.detail; panel = 'reset'">

            {{-- Panel Selector --}}
            <div class="flex gap-2 p-1.5 bg-slate-200/80 rounded-xl">
                <button @click="panel = 'add'" :class="panel === 'add' ? 'btn-bps btn-bps-primary text-xs flex-1' : 'btn-bps btn-bps-secondary text-xs flex-1'">
                    ➕ Tambah
                </button>
                <button @click="panel = 'edit'" :class="panel === 'edit' ? 'btn-bps btn-bps-primary text-xs flex-1' : 'btn-bps btn-bps-secondary text-xs flex-1'" x-show="editUser">
                    ✏️ Edit
                </button>
                <button @click="panel = 'reset'" :class="panel === 'reset' ? 'btn-bps btn-bps-primary text-xs flex-1' : 'btn-bps btn-bps-secondary text-xs flex-1'" x-show="resetUser">
                    🔑 Reset
                </button>
            </div>

            {{-- Add Form --}}
            <div x-show="panel === 'add'" class="card-corporate p-6">
                <h2 class="text-sm font-extrabold text-slate-900 mb-4">➕ Tambah Pengguna Baru</h2>
                <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="form-label-custom">NIP / Username *</label>
                        <input type="text" name="nip_username" class="form-input-v4" placeholder="190123456789" required>
                    </div>
                    <div>
                        <label class="form-label-custom">Nama Lengkap *</label>
                        <input type="text" name="name" class="form-input-v4" placeholder="Nama Lengkap" required>
                    </div>
                    <div>
                        <label class="form-label-custom">Role Access *</label>
                        <select name="role" class="form-input-v4" required>
                            <option value="OPERATOR">OPERATOR — Input Dokumen SPJ</option>
                            <option value="SUPERVISOR">SUPERVISOR — Kelola Master POK</option>
                            <option value="BENDAHARA">BENDAHARA — Verifikasi Pencairan</option>
                            <option value="ADMIN">ADMIN — Akses Penuh Sistem</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label-custom">Password *</label>
                        <input type="password" name="password" class="form-input-v4" placeholder="Minimal 6 karakter" required>
                    </div>
                    <div>
                        <label class="form-label-custom">Konfirmasi Password *</label>
                        <input type="password" name="password_confirmation" class="form-input-v4" required>
                    </div>
                    <button type="submit" class="btn-bps btn-bps-primary w-full py-3">➕ Simpan Pengguna Baru</button>
                </form>
            </div>

            {{-- Edit Form --}}
            <template x-if="panel === 'edit' && editUser">
                <div class="card-corporate p-6">
                    <h2 class="text-sm font-extrabold text-slate-900 mb-4">✏️ Edit Pengguna: <span x-text="editUser.name"></span></h2>
                    <form :action="'/users/' + editUser.id" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <div>
                            <label class="form-label-custom">Nama Lengkap *</label>
                            <input type="text" name="name" class="form-input-v4" :value="editUser.name" required>
                        </div>
                        <div>
                            <label class="form-label-custom">Role Access *</label>
                            <select name="role" class="form-input-v4" required>
                                <option value="OPERATOR" :selected="editUser.role === 'OPERATOR'">OPERATOR</option>
                                <option value="SUPERVISOR" :selected="editUser.role === 'SUPERVISOR'">SUPERVISOR</option>
                                <option value="BENDAHARA" :selected="editUser.role === 'BENDAHARA'">BENDAHARA</option>
                                <option value="ADMIN" :selected="editUser.role === 'ADMIN'">ADMIN</option>
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="btn-bps btn-bps-primary flex-1">💾 Simpan</button>
                            <button type="button" @click="panel = 'add'; editUser = null" class="btn-bps btn-bps-secondary">Batal</button>
                        </div>
                    </form>
                </div>
            </template>

            {{-- Reset PW Form --}}
            <template x-if="panel === 'reset' && resetUser">
                <div class="card-corporate p-6">
                    <h2 class="text-sm font-extrabold text-slate-900 mb-1">🔑 Reset Password</h2>
                    <p class="text-xs text-slate-500 mb-4">Untuk: <strong x-text="resetUser.name"></strong></p>
                    <form :action="'/users/' + resetUser.id + '/reset-password'" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="form-label-custom">Password Baru *</label>
                            <input type="password" name="password" class="form-input-v4" placeholder="Minimal 6 karakter" required>
                        </div>
                        <div>
                            <label class="form-label-custom">Konfirmasi Password Baru *</label>
                            <input type="password" name="password_confirmation" class="form-input-v4" required>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="btn-bps btn-bps-danger flex-1">🔑 Reset Password</button>
                            <button type="button" @click="panel = 'add'; resetUser = null" class="btn-bps btn-bps-secondary">Batal</button>
                        </div>
                    </form>
                </div>
            </template>

            {{-- Role Reference --}}
            <div class="p-4 rounded-xl bg-slate-100 border border-slate-200 text-xs leading-relaxed space-y-1">
                <div class="font-bold text-slate-800 mb-2">📋 Matriks Role Access System:</div>
                <div>🔴 <strong>ADMIN</strong> — Akses penuh seluruh modul</div>
                <div>🔵 <strong>SUPERVISOR</strong> — Kelola struktur master POK</div>
                <div>🟢 <strong>OPERATOR</strong> — Upload & kelola dokumen SPJ</div>
                <div>🟡 <strong>BENDAHARA</strong> — Verifikasi & persetujuan pencairan</div>
            </div>

        </div>

    </div>

</div>
@endsection
