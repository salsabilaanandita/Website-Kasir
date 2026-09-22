@extends('layouts.app')
@section('title', 'Kinerja Kasir')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#1d1d1f]">Laporan Kinerja Kasir</h1>
            <p class="text-xs text-[#86868b] mt-1">Performa penjualan dan total transaksi masing-masing staf kasir.</p>
        </div>
        
        <form action="{{ route('reports.cashier') }}" method="GET" class="flex flex-col sm:flex-row gap-2.5">
            <div class="flex items-center gap-2">
                <input type="date" name="tanggal_dari" value="{{ $tanggalDari }}" class="apple-input text-xs" required>
                <span class="text-[#86868b] text-xs">s/d</span>
                <input type="date" name="tanggal_sampai" value="{{ $tanggalSampai }}" class="apple-input text-xs" required>
            </div>
            <button type="submit" class="btn-apple-primary text-xs py-2 px-4">
                <i class="fas fa-filter text-xs mr-1"></i> Filter
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($perKasir as $index => $kasir)
            <div class="apple-card p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-full bg-[#1d1d1f] text-white flex items-center justify-center text-sm font-bold flex-shrink-0">
                            {{ strtoupper(substr($kasir->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-[#1d1d1f]">{{ $kasir->name }}</h3>
                            <span class="text-[11px] text-[#86868b]">Staf Kasir</span>
                        </div>
                    </div>

                    <div class="space-y-2 text-xs pt-3 border-t border-[#f0f0f0]">
                        <div class="flex justify-between items-center text-[#86868b]">
                            <span>Total Transaksi</span>
                            <span class="font-semibold text-[#1d1d1f] tabular-nums">{{ $kasir->jumlah_transaksi }} struk</span>
                        </div>
                        <div class="flex justify-between items-center text-[#86868b]">
                            <span>Total Omzet</span>
                            <span class="font-bold text-[#0071e3] tabular-nums">Rp {{ number_format($kasir->total_omzet, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full apple-card p-12 text-center text-[#86868b]">
                <i class="fas fa-users text-3xl mb-3 block opacity-40"></i>
                <h3 class="text-sm font-semibold text-[#1d1d1f] mb-1">Belum Ada Data</h3>
                <p class="text-xs text-[#86868b]">Tidak ada aktivitas transaksi kasir pada rentang tanggal yang dipilih.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
