@extends('layouts.app')
@section('title', 'Kelola Pengguna')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-[#1d1d1f] tracking-tight">Manajemen Pengguna</h1>
            <p class="text-xs text-[#86868b] mt-1">Kelola akun staf, manajer, dan administrator sistem</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn-apple-primary">
            <i class="fas fa-plus text-xs"></i>
            <span>Tambah Pengguna</span>
        </a>
    </div>

    <div class="apple-card">
        <div class="px-5 py-3.5 border-b border-[#e5e5ea] flex items-center justify-between bg-white">
            <div class="flex items-center gap-2">
                <i class="fas fa-users text-xs text-[#0071e3]"></i>
                <span class="text-xs font-semibold text-[#1d1d1f]">Daftar Akun</span>
            </div>
            <span class="apple-badge">
                Total: {{ $users->total() }} pengguna
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="apple-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Pengguna</th>
                        <th>Alamat Email</th>
                        <th style="text-align: center; width: 140px;">Hak Akses / Role</th>
                        <th style="text-align: center; width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="text-[#86868b] font-medium">{{ $users->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-[#1d1d1f] text-white flex items-center justify-center font-semibold text-xs flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-[#1d1d1f]">{{ $user->name }}</div>
                                    @if($user->id === auth()->id())
                                        <span class="text-[10px] text-[#0071e3] font-medium">(Akun aktif Anda)</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-[#86868b]">{{ $user->email }}</td>
                        <td style="text-align: center;">
                            @if($user->role === 'admin')
                                <span class="apple-badge apple-badge-blue capitalize">
                                    <i class="fas fa-shield-alt text-[9px]"></i> Admin
                                </span>
                            @elseif($user->role === 'manager')
                                <span class="apple-badge apple-badge-green capitalize">
                                    <i class="fas fa-user-tie text-[9px]"></i> Manager
                                </span>
                            @else
                                <span class="apple-badge capitalize">
                                    <i class="fas fa-user text-[9px]"></i> Staff Kasir
                                </span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn-apple-ghost" title="Edit">
                                    <i class="fas fa-pen-to-square text-xs"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus akun pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-apple-danger" title="Hapus">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-[#86868b]">
                            <div class="w-12 h-12 bg-[#f5f5f7] rounded-full flex items-center justify-center mx-auto mb-3 text-[#86868b]">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <p class="text-xs font-medium">Belum ada data pengguna</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="p-4 border-t border-[#e5e5ea]">{{ $users->links() }}</div>
        @endif
    </div>
</div>
@endsection