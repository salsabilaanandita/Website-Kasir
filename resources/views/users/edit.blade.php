@extends('layouts.app')
@section('title', 'Edit Pengguna')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-[#1d1d1f] tracking-tight">Edit Akun Pengguna</h1>
            <p class="text-xs text-[#86868b] mt-1">Perbarui nama, email, kata sandi, atau hak akses role</p>
        </div>
        <a href="{{ route('users.index') }}" class="btn-apple-secondary">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Kembali</span>
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 bg-[#fff2f2] border border-[#ffccd0] text-[#d70015] rounded-xl text-xs">
            <div class="font-semibold mb-1">Periksa kembali data yang dimasukkan:</div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="apple-card p-6">
        <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Nama Lengkap <span class="text-[#d70015]">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    class="apple-input @error('name') border-[#d70015] @enderror">
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Alamat Email <span class="text-[#d70015]">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="apple-input @error('email') border-[#d70015] @enderror">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Kata Sandi Baru</label>
                <input type="password" id="password" name="password" minlength="3"
                    class="apple-input @error('password') border-[#d70015] @enderror" placeholder="Kosongkan jika tidak ingin mengubah password">
            </div>

            <div>
                <label for="role" class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Hak Akses / Role <span class="text-[#d70015]">*</span></label>
                <select id="role" name="role" required class="apple-input">
                    <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff Kasir (Hanya POS & Riwayat)</option>
                    <option value="manager" {{ old('role', $user->role) === 'manager' ? 'selected' : '' }}>Manager (Produk, Stok, Pengeluaran & Laporan)</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin (Akses Penuh Seluruh Sistem)</option>
                </select>
            </div>

            <div class="pt-4 border-t border-[#e5e5ea] flex items-center justify-end gap-3">
                <a href="{{ route('users.index') }}" class="btn-apple-ghost">Batal</a>
                <button type="submit" class="btn-apple-primary">
                    <i class="fas fa-check text-xs"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection