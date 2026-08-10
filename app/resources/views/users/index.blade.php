@extends('layouts.app')
@section('title', 'Manajemen Pengguna')
@section('page-title', '👥 Manajemen Pengguna')

@section('content')
<div style="display:grid;grid-template-columns:1fr 360px;gap:20px;align-items:start;">

    {{-- Users Table --}}
    <div class="table-card">
        <div style="padding:14px 16px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:13.5px;font-weight:700;color:#0f172a;">Daftar Pengguna Sistem</span>
            <span style="font-size:12px;color:#64748b;">{{ $users->count() }} pengguna</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>NIP / Username</th>
                    <th>Nama Lengkap</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:12px;">{{ $user->nip_username }}</code>
                    </td>
                    <td style="font-weight:500;font-size:13px;">
                        {{ $user->name }}
                        @if($user->id === auth()->id())
                            <span style="font-size:10px;background:#e8f0fe;color:#003087;padding:1px 5px;border-radius:8px;margin-left:4px;">Anda</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $roleBadge = match($user->role) {
                                'ADMIN'      => 'background:#fee2e2;color:#b91c1c;',
                                'SUPERVISOR' => 'background:#e8f0fe;color:#1d4ed8;',
                                'BENDAHARA'  => 'background:#fef9c3;color:#854d0e;',
                                default      => 'background:#f0fdf4;color:#166534;',
                            };
                        @endphp
                        <span class="badge" style="{{ $roleBadge }}">{{ $user->role }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:5px;flex-wrap:wrap;" x-data>
                            {{-- Edit Role --}}
                            <button type="button" class="btn btn-secondary btn-sm"
                                    @click="$dispatch('open-edit-user', {{ json_encode(['id' => $user->id, 'name' => $user->name, 'role' => $user->role]) }})">
                                ✏️ Edit
                            </button>

                            {{-- Reset Password --}}
                            <button type="button" class="btn btn-secondary btn-sm"
                                    @click="$dispatch('open-reset-pw', {{ json_encode(['id' => $user->id, 'name' => $user->name]) }})">
                                🔑 Reset PW
                            </button>

                            {{-- Delete (not self) --}}
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST"
                                  onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Right Panel --}}
    <div style="display:flex;flex-direction:column;gap:16px;position:sticky;top:76px;"
         x-data="{
            panel: 'add',
            editUser: null,
            resetUser: null,
         }"
         @open-edit-user.window="editUser = $event.detail; panel = 'edit'"
         @open-reset-pw.window="resetUser = $event.detail; panel = 'reset'">

        {{-- Panel Switcher --}}
        <div style="display:flex;gap:4px;background:#f1f5f9;padding:4px;border-radius:8px;">
            <button @click="panel = 'add'" :class="panel === 'add' ? 'btn btn-primary' : 'btn btn-secondary'" style="flex:1;font-size:12px;padding:6px;">➕ Tambah</button>
            <button @click="panel = 'edit'" :class="panel === 'edit' ? 'btn btn-primary' : 'btn btn-secondary'" style="flex:1;font-size:12px;padding:6px;" x-show="editUser">✏️ Edit</button>
            <button @click="panel = 'reset'" :class="panel === 'reset' ? 'btn btn-primary' : 'btn btn-secondary'" style="flex:1;font-size:12px;padding:6px;" x-show="resetUser">🔑 Reset PW</button>
        </div>

        {{-- Add User --}}
        <div x-show="panel === 'add'" style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:20px;">
            <div style="font-size:13.5px;font-weight:700;color:#0f172a;margin-bottom:14px;">➕ Tambah Pengguna Baru</div>
            <form action="{{ route('users.store') }}" method="POST" style="display:flex;flex-direction:column;gap:12px;">
                @csrf
                <div>
                    <label class="form-label">NIP / Username *</label>
                    <input type="text" name="nip_username" class="form-input" placeholder="190123456789" required>
                </div>
                <div>
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="name" class="form-input" placeholder="Nama Pengguna" required>
                </div>
                <div>
                    <label class="form-label">Role *</label>
                    <select name="role" class="form-input" required>
                        <option value="OPERATOR">OPERATOR — Input Dokumen</option>
                        <option value="SUPERVISOR">SUPERVISOR — Kelola Master POK</option>
                        <option value="BENDAHARA">BENDAHARA — Verifikasi Pencairan</option>
                        <option value="ADMIN">ADMIN — Akses Penuh</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter" required>
                </div>
                <div>
                    <label class="form-label">Konfirmasi Password *</label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>
                <button type="submit" class="btn btn-primary">➕ Tambah Pengguna</button>
            </form>
        </div>

        {{-- Edit User --}}
        <template x-if="panel === 'edit' && editUser">
            <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:20px;">
                <div style="font-size:13.5px;font-weight:700;margin-bottom:14px;">✏️ Edit: <span x-text="editUser.name"></span></div>
                <form :action="'/users/' + editUser.id" method="POST" style="display:flex;flex-direction:column;gap:12px;">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <div>
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="name" class="form-input" :value="editUser.name" required>
                    </div>
                    <div>
                        <label class="form-label">Role *</label>
                        <select name="role" class="form-input" required>
                            <option value="OPERATOR" :selected="editUser.role === 'OPERATOR'">OPERATOR</option>
                            <option value="SUPERVISOR" :selected="editUser.role === 'SUPERVISOR'">SUPERVISOR</option>
                            <option value="BENDAHARA" :selected="editUser.role === 'BENDAHARA'">BENDAHARA</option>
                            <option value="ADMIN" :selected="editUser.role === 'ADMIN'">ADMIN</option>
                        </select>
                    </div>
                    <div style="display:flex;gap:8px;">
                        <button type="submit" class="btn btn-primary" style="flex:1;">💾 Simpan</button>
                        <button type="button" @click="panel = 'add'; editUser = null" class="btn btn-secondary">Batal</button>
                    </div>
                </form>
            </div>
        </template>

        {{-- Reset Password --}}
        <template x-if="panel === 'reset' && resetUser">
            <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:20px;">
                <div style="font-size:13.5px;font-weight:700;margin-bottom:4px;">🔑 Reset Password</div>
                <div style="font-size:12px;color:#64748b;margin-bottom:14px;">Untuk: <strong x-text="resetUser.name"></strong></div>
                <form :action="'/users/' + resetUser.id + '/reset-password'" method="POST" style="display:flex;flex-direction:column;gap:12px;">
                    @csrf
                    <div>
                        <label class="form-label">Password Baru *</label>
                        <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter" required>
                    </div>
                    <div>
                        <label class="form-label">Konfirmasi Password Baru *</label>
                        <input type="password" name="password_confirmation" class="form-input" required>
                    </div>
                    <div style="display:flex;gap:8px;">
                        <button type="submit" class="btn btn-primary" style="flex:1;">🔑 Reset Password</button>
                        <button type="button" @click="panel = 'add'; resetUser = null" class="btn btn-secondary">Batal</button>
                    </div>
                </form>
            </div>
        </template>

        {{-- Role Guide --}}
        <div style="background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;padding:14px;">
            <div style="font-size:12px;font-weight:700;color:#374151;margin-bottom:8px;">📋 Panduan Hak Akses</div>
            <div style="font-size:11.5px;color:#64748b;line-height:1.8;">
                <div>🔴 <strong>ADMIN</strong> — Akses penuh semua fitur</div>
                <div>🔵 <strong>SUPERVISOR</strong> — Kelola master POK</div>
                <div>🟢 <strong>OPERATOR</strong> — Upload & lihat dokumen</div>
                <div>🟡 <strong>BENDAHARA</strong> — Verifikasi & pratinjau</div>
            </div>
        </div>
    </div>
</div>
@endsection
