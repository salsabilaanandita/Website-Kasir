<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan daftar user.
     * Menggunakan folder 'user' agar sinkron dengan file index.blade.php Anda.
     */
    public function index()
    {
        $users = User::latest()->paginate(10); 
        return view('user.index', compact('users')); 
    }

    /**
     * Form tambah user.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Menyimpan user baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3',
            'role' => 'required|in:admin,staff' // FIX: Ganti 'staff' jadi 'staff'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        // FIX: Pastikan redirect ke 'users.index' (plural)
        return redirect()->route('users.index')
            ->with('success', 'User successfully added');
    }

    /**
     * Form edit user.
     */
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    /**
     * Update data user.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:3',
            'role' => 'required|in:admin,staff' // FIX: Ganti 'staff' jadi 'staff'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User successfully updated');
    }

    /**
     * Menghapus user.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'User successfully deleted');
    }
}