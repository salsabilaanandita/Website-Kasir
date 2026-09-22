@extends('layouts.app')
@section('title', 'Laporan Laba Bersih')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#1d1d1f]">Laporan Profit & Laba</h1>
            <p class="text-xs text-[#86868b] mt-1">Analisa akumulasi omzet penjualan dikurangi pengeluaran operasional.</p>
        </div>
        
        <form action="{{ route('reports.profit') }}" method="GET" class="flex flex-col sm:flex-row gap-2.5">
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

    {{-- Main Net Profit Banner (Apple dark tile card) --}}
    <div class="apple-card p-8 bg-[#1d1d1f] text-white text-center">
        <span class="text-xs font-semibold uppercase tracking-widest text-[#86868b] block mb-2">Net Profit (Estimasi Laba Bersih)</span>
        <div class="text-4xl sm:text-5xl font-extrabold tracking-tight tabular-nums {{ $profit < 0 ? 'text-[#ff6b6b]' : 'text-[#40c057]' }}">
            Rp {{ number_format($profit, 0, ',', '.') }}
        </div>
        <p class="text-xs text-[#86868b] mt-3 max-w-md mx-auto">
            Dihitung dari total omzet penjualan dikurangi total pengeluaran beban operasional yang tercatat pada rentang waktu ini.
        </p>
    </div>

    {{-- Details Breakdown --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="apple-card p-6">
            <div class="flex items-center gap-3 pb-4 border-b border-[#f0f0f0] mb-4">
                <div class="w-10 h-10 rounded-xl bg-[#eafaf1] text-[#1e7e34] flex items-center justify-center text-sm">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <div>
                    <span class="text-[11px] font-semibold text-[#86868b] uppercase tracking-wider block">Pemasukan (Omzet)</span>
                    <div class="text-xl font-bold text-[#1d1d1f] tabular-nums">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1.5 text-[#86868b]">
                    <span>Penjualan Kasir POS</span>
                    <span class="font-semibold text-[#1d1d1f] tabular-nums">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="apple-card p-6">
            <div class="flex items-center gap-3 pb-4 border-b border-[#f0f0f0] mb-4">
                <div class="w-10 h-10 rounded-xl bg-[#fff0f0] text-[#e03131] flex items-center justify-center text-sm">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <div>
                    <span class="text-[11px] font-semibold text-[#86868b] uppercase tracking-wider block">Beban Pengeluaran</span>
                    <div class="text-xl font-bold text-[#1d1d1f] tabular-nums">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1.5 text-[#86868b]">
                    <span>Operasional & Beban Usaha</span>
                    <span class="font-semibold text-[#1d1d1f] tabular-nums">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
