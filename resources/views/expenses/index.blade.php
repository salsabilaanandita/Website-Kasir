@extends('layouts.app')
@section('title', 'Pengeluaran')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-[#1d1d1f] tracking-tight">Pengeluaran Operasional</h1>
            <p class="text-xs text-[#86868b] mt-1">Total Biaya Tercatat: <strong class="text-[#1d1d1f]">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</strong></p>
        </div>
        <a href="{{ route('expenses.create') }}" class="btn-apple-primary">
            <i class="fas fa-plus text-xs"></i>
            <span>Catat Pengeluaran</span>
        </a>
    </div>

    {{-- Filter Card --}}
    <div class="apple-card p-3.5">
        <form action="{{ route('expenses.index') }}" method="GET" class="flex flex-wrap gap-2.5 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul biaya..." 
                class="apple-input flex-1 min-w-[180px]">
            <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" 
                class="apple-input w-auto text-xs">
            <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" 
                class="apple-input w-auto text-xs">
            <button type="submit" class="btn-apple-dark py-1.5 px-4 text-xs">
                <i class="fas fa-filter text-xs"></i>
                <span>Filter</span>
            </button>
        </form>
    </div>

    {{-- Expenses Table Card --}}
    <div class="apple-card">
        <div class="px-5 py-3.5 border-b border-[#e5e5ea] flex items-center justify-between bg-white">
            <div class="flex items-center gap-2">
                <i class="fas fa-wallet text-xs text-[#0071e3]"></i>
                <span class="text-xs font-semibold text-[#1d1d1f]">Riwayat Pengeluaran</span>
            </div>
            <span class="apple-badge">
                Total: {{ $pengeluarans->total() }} catatan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="apple-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Judul Pengeluaran</th>
                        <th>Kategori</th>
                        <th style="text-align: right;">Jumlah Biaya</th>
                        <th>Tanggal</th>
                        <th style="text-align: center; width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengeluarans as $e)
                    <tr>
                        <td class="text-[#86868b] font-medium">{{ $loop->iteration + ($pengeluarans->currentPage()-1) * $pengeluarans->perPage() }}</td>
                        <td>
                            <div class="font-semibold text-[#1d1d1f]">{{ $e->judul }}</div>
                            @if($e->keterangan)
                                <div class="text-[11px] text-[#86868b]">{{ Str::limit($e->keterangan, 60) }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="apple-badge">{{ $e->kategori }}</span>
                        </td>
                        <td style="text-align: right;" class="font-semibold text-[#d70015]">
                            Rp {{ number_format($e->jumlah, 0, ',', '.') }}
                        </td>
                        <td class="text-[#86868b] text-xs">
                            {{ $e->tanggal->format('d M Y') }}
                        </td>
                        <td style="text-align: center;">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('expenses.edit', $e->id) }}" class="btn-apple-ghost" title="Edit">
                                    <i class="fas fa-pen-to-square text-xs"></i>
                                </a>
                                <form action="{{ route('expenses.destroy', $e->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengeluaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-apple-danger" title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-[#86868b]">
                            <div class="w-12 h-12 bg-[#f5f5f7] rounded-full flex items-center justify-center mx-auto mb-3 text-[#86868b]">
                                <i class="fas fa-wallet text-xl"></i>
                            </div>
                            <p class="text-xs font-medium">Belum ada catatan pengeluaran</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pengeluarans->hasPages())
            <div class="p-4 border-t border-[#e5e5ea]">{{ $pengeluarans->links() }}</div>
        @endif
    </div>
</div>
@endsection
