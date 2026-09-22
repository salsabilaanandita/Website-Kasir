@extends('layouts.app')
@section('title', 'Log Aktivitas')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#1d1d1f]">Log Aktivitas Sistem</h1>
            <p class="text-xs text-[#86868b] mt-1">Audit jejak operasional pengguna dan transaksi sistem.</p>
        </div>
        <form action="{{ route('activity.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus log yang berumur lebih dari 30 hari?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-apple-danger text-xs py-2 px-3.5 inline-flex items-center gap-1.5">
                <i class="fas fa-trash text-xs"></i> Bersihkan Log Lama
            </button>
        </form>
    </div>

    {{-- Filter Toolbar --}}
    <div class="apple-card p-4">
        <form action="{{ route('activity.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-[#86868b] text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pengguna, modul, aksi..." class="apple-input pl-9 text-xs">
            </div>
            <div class="w-full sm:w-44">
                <select name="action" class="apple-input text-xs">
                    <option value="">Semua Jenis Aksi</option>
                    @foreach(['login','logout','create','update','delete'] as $act)
                        <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ ucfirst($act) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-apple-primary text-xs py-2 px-4">
                <i class="fas fa-filter text-xs mr-1"></i> Filter
            </button>
        </form>
    </div>

    {{-- Logs Table --}}
    <div class="apple-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="apple-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Pengguna</th>
                        <th>Aksi</th>
                        <th>Modul</th>
                        <th>Deskripsi Aktivitas</th>
                        <th>Waktu Kejadian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="text-[#86868b] font-medium">{{ $logs->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-[#1d1d1f] text-white flex items-center justify-center font-bold text-[10px]">
                                        {{ strtoupper(substr($log->user_name ?? 'S', 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-[#1d1d1f] text-xs">{{ $log->user_name ?? 'Sistem' }}</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $badgeStyles = [
                                        'login' => 'bg-[#e8f2ff] text-[#0071e3] border-[#cce4ff]',
                                        'logout' => 'bg-[#f5f5f7] text-[#86868b] border-[#e5e5ea]',
                                        'create' => 'bg-[#eafaf1] text-[#1e7e34] border-[#c3f0d4]',
                                        'update' => 'bg-[#fef9c3] text-[#92400e] border-[#fef08a]',
                                        'delete' => 'bg-[#fff0f0] text-[#e03131] border-[#ffc9c9]',
                                    ];
                                    $style = $badgeStyles[$log->action] ?? 'bg-[#f5f5f7] text-[#86868b] border-[#e5e5ea]';
                                @endphp
                                <span class="apple-badge {{ $style }}">
                                    {{ ucfirst($log->action) }}
                                </span>
                            </td>
                            <td class="text-xs font-medium text-[#1d1d1f]">{{ $log->modul ?? '-' }}</td>
                            <td class="text-xs text-[#86868b] max-w-sm">{{ $log->deskripsi }}</td>
                            <td class="text-xs text-[#86868b] tabular-nums">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-[#86868b]">
                                <i class="fas fa-clock-rotate-left text-3xl mb-3 block opacity-40"></i>
                                <span class="text-sm">Belum ada catatan aktivitas sistem</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-[#f0f0f0]">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
