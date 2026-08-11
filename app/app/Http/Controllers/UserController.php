<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            'password'     => ['required', 'confirmed', Password::min(6)],
        ], [
            'nip_username.unique' => 'NIP/Username sudah terdaftar di sistem.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'nip_username' => $request->nip_username,
            'name'         => $request->name,
            'role'         => $request->role,
            'password'     => Hash::make($request->password),
        ]);

        return back()->with('success', "Pengguna [{$request->name}] berhasil ditambahkan dengan role {$request->role}.");
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'role' => 'required|in:ADMIN,SUPERVISOR,OPERATOR,BENDAHARA',
        ]);

        $user->update($request->only('name', 'role'));
        return back()->with('success', "Data pengguna [{$user->nip_username}] berhasil diperbarui.");
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', "Password pengguna [{$user->name}] berhasil direset.");
    }

    public function destroy(User $user)
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return back()->with('success', "Pengguna [{$user->name}] berhasil dihapus.");
    }
}
