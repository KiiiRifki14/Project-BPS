<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')->orderBy('name')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip_username' => 'required|string|max:50|unique:users,nip_username',
            'name'         => 'required|string|max:100',
            'role'         => 'required|in:ADMIN,SUPERVISOR,OPERATOR,BENDAHARA',
            // ─── A07: Password Policy Diperkuat ──────────────────────────
            // Min 8 karakter, harus ada huruf besar+kecil dan angka.
            'password'     => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'nip_username.unique'   => 'NIP/Username sudah terdaftar di sistem.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
            'password.min'          => 'Password minimal 8 karakter.',
        ]);

        $user = User::create([
            'nip_username' => $request->nip_username,
            'name'         => $request->name,
            'role'         => $request->role,
            'password'     => Hash::make($request->password),
        ]);

        // ─── Audit Log ────────────────────────────────────────────────────
        AuditLogger::log('USER_CREATE', "Pengguna baru [{$user->nip_username}] dengan role [{$user->role}] dibuat oleh [" . auth()->user()->nip_username . "].");

        return back()->with('success', "Pengguna [{$request->name}] berhasil ditambahkan dengan role {$request->role}.");
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'role' => 'required|in:ADMIN,SUPERVISOR,OPERATOR,BENDAHARA',
        ]);

        $oldRole = $user->role;
        $user->update($request->only('name', 'role'));

        // ─── Audit Log ────────────────────────────────────────────────────
        AuditLogger::log('USER_UPDATE', "Data pengguna [{$user->nip_username}] diperbarui oleh [" . auth()->user()->nip_username . "]. Role: {$oldRole} → {$user->role}.");

        return back()->with('success', "Data pengguna [{$user->nip_username}] berhasil diperbarui.");
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            // ─── A07: Password Policy Diperkuat ──────────────────────────
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min'       => 'Password minimal 8 karakter.',
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        // ─── Audit Log ────────────────────────────────────────────────────
        AuditLogger::log('PASSWORD_RESET', "Password pengguna [{$user->nip_username}] direset oleh [" . auth()->user()->nip_username . "].");

        return back()->with('success', "Password pengguna [{$user->name}] berhasil direset.");
    }

    public function destroy(User $user)
    {
        // ─── A01: Prevent self-deletion ───────────────────────────────────
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $deletedUsername = $user->nip_username;
        $deletedRole     = $user->role;

        $user->delete();

        // ─── Audit Log ────────────────────────────────────────────────────
        AuditLogger::log('USER_DELETE', "Pengguna [{$deletedUsername}] (role: {$deletedRole}) dihapus oleh [" . auth()->user()->nip_username . "].");

        return back()->with('success', "Pengguna [{$user->name}] berhasil dihapus.");
    }
}
