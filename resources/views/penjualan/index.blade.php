@extends('layouts.app')
@section('title', 'Riwayat Penjualan')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#1d1d1f]">Riwayat Penjualan</h1>
            <p class="text-xs text-[#86868b] mt-1">Daftar semua transaksi penjualan kasir tercatat.</p>
        </div>
        <div>
            <a href="{{ route('kasir.index') }}" class="btn-apple-primary inline-flex items-center gap-2">
                <i class="fas fa-cash-register text-xs"></i> Buka Kasir
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="apple-card p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-[#f5f5f7] text-[#1d1d1f] flex items-center justify-center text-sm flex-shrink-0">
                <i class="fas fa-receipt"></i>
            </div>
            <div>
                <span class="text-[11px] font-semibold text-[#86868b] uppercase tracking-wider block">Transaksi Hari Ini</span>
                <div class="text-2xl font-bold tracking-tight text-[#1d1d1f] tabular-nums mt-0.5">
                    {{ number_format($jumlahHariIni, 0, ',', '.') }}
                </div>
            </div>
        </div>
        <div class="apple-card p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-[#e8f2ff] text-[#0071e3] flex items-center justify-center text-sm flex-shrink-0">
                <i class="fas fa-wallet"></i>
            </div>
            <div>
                <span class="text-[11px] font-semibold text-[#86868b] uppercase tracking-wider block">Omzet Hari Ini</span>
                <div class="text-2xl font-bold tracking-tight text-[#1d1d1f] tabular-nums mt-0.5">
                    Rp {{ number_format($totalHariIni, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Toolbar --}}
    <div class="apple-card p-4">
        <form action="{{ route('penjualan.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-[#86868b] text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor transaksi..." class="apple-input pl-9">
            </div>
            <div class="w-full sm:w-48">
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="apple-input">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="btn-apple-primary text-xs py-2 px-4">
                    <i class="fas fa-filter text-xs mr-1"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'tanggal']))
                    <a href="{{ route('penjualan.index') }}" class="btn-apple-secondary text-xs py-2 px-3">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Sales Table --}}
    <div class="apple-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="apple-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>No. Transaksi</th>
                        <th>Kasir</th>
                        <th>Member</th>
                        <th>Metode</th>
                        <th class="text-right">Total Tagihan</th>
                        <th>Waktu</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualans as $p)
                        <tr>
                            <td class="text-[#86868b] font-medium">{{ $penjualans->firstItem() + $loop->index }}</td>
                            <td>
                                <span class="font-mono font-semibold text-[#0071e3]">{{ $p->no_transaksi }}</span>
                            </td>
                            <td class="font-medium text-[#1d1d1f]">
                                {{ $p->kasir_nama ?? $p->user?->name ?? '-' }}
                            </td>
                            <td>
                                @if($p->member)
                                    <span class="apple-badge bg-[#e8f2ff] text-[#0071e3] border-[#cce4ff]">
                                        <i class="fas fa-id-card text-[10px] mr-1"></i> {{ $p->member->name }}
                                    </span>
                                @else
                                    <span class="text-xs text-[#86868b]">Non-Member</span>
                                @endif
                            </td>
                            <td>
                                <span class="apple-badge {{ $p->metode_bayar === 'Tunai' ? 'bg-[#eafaf1] text-[#1e7e34] border-[#c3f0d4]' : ($p->metode_bayar === 'QRIS' ? 'bg-[#fef9c3] text-[#92400e] border-[#fef08a]' : 'bg-[#e8f2ff] text-[#0071e3] border-[#cce4ff]') }}">
                                    {{ $p->metode_bayar }}
                                </span>
                            </td>
                            <td class="text-right font-semibold text-[#1d1d1f] tabular-nums">
                                Rp {{ number_format($p->total, 0, ',', '.') }}
                            </td>
                            <td class="text-xs text-[#86868b] tabular-nums">
                                {{ $p->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('penjualan.detail', $p->id) }}" class="btn-apple-secondary text-xs py-1 px-3">
                                    <i class="fas fa-eye text-xs mr-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-[#86868b]">
                                <i class="fas fa-receipt text-3xl mb-3 block opacity-40"></i>
                                <span class="text-sm">Belum ada transaksi penjualan yang tercatat</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($penjualans->hasPages())
            <div class="p-4 border-t border-[#f0f0f0]">
                {{ $penjualans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
