@extends('layouts.app')
@section('title', 'Laporan Pembayaran')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#1d1d1f]">Laporan Pembayaran</h1>
            <p class="text-xs text-[#86868b] mt-1">Distribusi volume transaksi berdasarkan metode pembayaran (Tunai, QRIS, Transfer).</p>
        </div>
        
        <form action="{{ route('reports.payments') }}" method="GET" class="flex flex-col sm:flex-row gap-2.5">
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

    {{-- Method Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($metodeBayar as $metode)
            <div class="apple-card p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[11px] font-semibold text-[#86868b] uppercase tracking-wider block mb-1">{{ $metode->metode_bayar }}</span>
                        <div class="text-2xl font-bold text-[#1d1d1f] tracking-tight tabular-nums">
                            Rp {{ number_format($metode->total, 0, ',', '.') }}
                        </div>
                        <span class="text-xs text-[#86868b] mt-1 block">{{ $metode->jumlah }} Transaksi</span>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-[#fafafc] border border-[#e5e5ea] text-[#1d1d1f] flex items-center justify-center text-sm">
                        <i class="fas {{ strtolower($metode->metode_bayar) == 'qris' ? 'fa-qrcode' : (strtolower($metode->metode_bayar) == 'transfer' ? 'fa-building-columns' : 'fa-money-bill-wave') }}"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Transactions Table --}}
    <div class="apple-card overflow-hidden">
        <div class="p-4 border-b border-[#f0f0f0]">
            <h2 class="text-xs font-semibold uppercase tracking-wider text-[#1d1d1f]">Rincian Transaksi Pembayaran</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="apple-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>No. Transaksi</th>
                        <th>Waktu</th>
                        <th>Metode Bayar</th>
                        <th class="text-right">Total Tagihan</th>
                        <th class="text-right">Dibayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualans as $p)
                        <tr>
                            <td class="text-[#86868b] font-medium">{{ $penjualans->firstItem() + $loop->index }}</td>
                            <td class="font-mono text-xs font-semibold text-[#0071e3]">{{ $p->no_transaksi }}</td>
                            <td class="text-xs text-[#86868b] tabular-nums">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="apple-badge {{ $p->metode_bayar === 'Tunai' ? 'bg-[#eafaf1] text-[#1e7e34]' : 'bg-[#e8f2ff] text-[#0071e3]' }}">
                                    {{ $p->metode_bayar }}
                                </span>
                            </td>
                            <td class="text-right font-semibold text-xs text-[#1d1d1f] tabular-nums">
                                Rp {{ number_format($p->total, 0, ',', '.') }}
                            </td>
                            <td class="text-right text-xs text-[#86868b] tabular-nums">
                                Rp {{ number_format($p->bayar, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-[#86868b]">
                                <i class="fas fa-receipt text-3xl mb-3 block opacity-40"></i>
                                <span class="text-sm">Tidak ada transaksi pembayaran pada periode ini</span>
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
