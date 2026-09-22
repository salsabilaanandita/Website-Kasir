@extends('layouts.app')
@section('title', 'Return Penjualan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#1d1d1f]">Return Penjualan</h1>
            <p class="text-xs text-[#86868b] mt-1">Daftar pengembalian barang transaksi oleh pelanggan.</p>
        </div>
        <div>
            <a href="{{ route('returns.create') }}" class="btn-apple-primary inline-flex items-center gap-2">
                <i class="fas fa-undo-alt text-xs"></i> Proses Return Baru
            </a>
        </div>
    </div>

    <div class="apple-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="apple-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>No. Return & Waktu</th>
                        <th>No. Transaksi</th>
                        <th>Produk</th>
                        <th class="text-center">Kuantitas</th>
                        <th class="text-right">Total Nominal</th>
                        <th>Alasan Pengembalian</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returns as $return)
                        <tr>
                            <td class="text-[#86868b] font-medium">{{ $returns->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="font-mono font-semibold text-[#1d1d1f] text-xs">{{ $return->no_return }}</div>
                                <div class="text-[11px] text-[#86868b] mt-0.5">{{ \Carbon\Carbon::parse($return->created_at)->format('d/m/Y H:i') }}</div>
                            </td>
                            <td>
                                <a href="{{ route('penjualan.detail', $return->penjualan_id) }}" class="font-mono text-xs font-semibold text-[#0071e3] hover:underline">
                                    {{ $return->penjualan->no_transaksi ?? '-' }}
                                </a>
                            </td>
                            <td class="font-medium text-[#1d1d1f]">
                                {{ $return->product->nama_produk ?? 'Produk Dihapus' }}
                            </td>
                            <td class="text-center">
                                <span class="apple-badge bg-[#fff0f0] text-[#e03131] border-[#ffc9c9]">
                                    {{ $return->qty }}x
                                </span>
                            </td>
                            <td class="text-right font-semibold text-[#1d1d1f] tabular-nums">
                                Rp {{ number_format($return->total_return, 0, ',', '.') }}
                            </td>
                            <td class="text-xs text-[#86868b] max-w-xs">
                                {{ $return->alasan }}
                            </td>
                            <td class="text-xs text-[#1d1d1f]">
                                {{ $return->user->name ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-[#86868b]">
                                <i class="fas fa-undo-alt text-3xl mb-3 block opacity-40"></i>
                                <span class="text-sm">Belum ada data pengembalian transaksi</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($returns->hasPages())
            <div class="p-4 border-t border-[#f0f0f0]">
                {{ $returns->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
