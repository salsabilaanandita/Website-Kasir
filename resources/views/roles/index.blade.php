@extends('layouts.app')
@section('title', 'Manajemen Role')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#1d1d1f]">Manajemen Hak Akses & Role</h1>
            <p class="text-xs text-[#86868b] mt-1">Konfigurasi peran dan hak akses akun pengguna sistem.</p>
        </div>
    </div>

    {{-- Users Role Table --}}
    <div class="apple-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="apple-table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Email</th>
                        <th class="text-center">Role Saat Ini</th>
                        <th class="text-center">Perbarui Role</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[#1d1d1f] text-white flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-[#1d1d1f]">{{ $user->name }}</div>
                                        @if($user->id === auth()->id())
                                            <div class="text-[10px] text-[#0071e3] font-semibold">(Akun Anda)</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-xs text-[#86868b]">{{ $user->email }}</td>
                            <td class="text-center">
                                <span class="apple-badge {{ $user->role === 'admin' ? 'bg-[#1d1d1f] text-white' : ($user->role === 'manager' ? 'bg-[#e8f2ff] text-[#0071e3]' : 'bg-[#fafafc] text-[#1d1d1f]') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('roles.update', $user->id) }}" method="POST" class="inline-flex items-center gap-2">
                                        @csrf @method('PUT')
                                        <select name="role" class="apple-input text-xs py-1 px-2.5 w-auto">
                                            @foreach($roles as $r)
                                                <option value="{{ $r }}" {{ $user->role == $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn-apple-secondary text-xs py-1 px-3">
                                            Simpan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-[#86868b] italic">Terkunci</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-[#e5e5ea]">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- Role Documentation Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="apple-card p-5">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-8 h-8 rounded-lg bg-[#1d1d1f] text-white flex items-center justify-center text-xs">
                    <i class="fas fa-crown"></i>
                </div>
                <h3 class="text-sm font-bold text-[#1d1d1f]">Administrator</h3>
            </div>
            <ul class="text-xs text-[#86868b] space-y-1.5 list-disc pl-4 leading-relaxed">
                <li>Akses penuh tanpa batasan ke semua fitur sistem</li>
                <li>Manajemen pengguna, hak akses, dan pengaturan toko</li>
                <li>Pengelolaan produk, kategori, supplier, dan stok</li>
                <li>Laporan laba, omzet, dan log aktivitas</li>
            </ul>
        </div>

        <div class="apple-card p-5">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-8 h-8 rounded-lg bg-[#e8f2ff] text-[#0071e3] flex items-center justify-center text-xs">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3 class="text-sm font-bold text-[#1d1d1f]">Manager</h3>
            </div>
            <ul class="text-xs text-[#86868b] space-y-1.5 list-disc pl-4 leading-relaxed">
                <li>Manajemen katalog produk, kategori, dan supplier</li>
                <li>Pengelolaan inventory, stock opname, dan pengeluaran</li>
                <li>Monitoring laporan penjualan dan kinerja kasir</li>
                <li>Tidak memiliki akses ubah role atau pengaturan sistem</li>
            </ul>
        </div>

        <div class="apple-card p-5">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-8 h-8 rounded-lg bg-[#fafafc] text-[#1d1d1f] border border-[#e5e5ea] flex items-center justify-center text-xs">
                    <i class="fas fa-cash-register"></i>
                </div>
                <h3 class="text-sm font-bold text-[#1d1d1f]">Staff Kasir</h3>
            </div>
            <ul class="text-xs text-[#86868b] space-y-1.5 list-disc pl-4 leading-relaxed">
                <li>Akses modul POS & Kasir transaksi harian</li>
                <li>Katalog produk (mode baca dan transaksi)</li>
                <li>Membuka dan menutup sesi shift kasir</li>
                <li>Menu supplier, laporan, dan inventory disembunyikan</li>
            </ul>
        </div>
    </div>
</div>
@endsection
