<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Halaman manajemen role — menampilkan daftar user dan role-nya
     */
    public function index()
    {
        $users = \App\Models\User::orderBy('name')->paginate(10);
        $roles = ['admin', 'manager', 'staff'];

        return view('roles.index', compact('users', 'roles'));
    }

    /**
     * Update role user
     */
    public function updateRole(Request $request, \App\Models\User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,manager,staff',
        ]);

        // Cegah mengubah role diri sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat mengubah role akun sendiri!');
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', "Role {$user->name} berhasil diubah menjadi {$request->role}!");
    }
}
