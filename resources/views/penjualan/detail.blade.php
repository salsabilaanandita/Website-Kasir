@extends('layouts.app')
@section('title', 'Detail Transaksi ' . $penjualan->no_transaksi)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Top Back Navigation --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('penjualan.index') }}" class="btn-apple-ghost inline-flex items-center gap-1.5 text-xs">
            <i class="fas fa-arrow-left text-xs"></i> Kembali ke Riwayat
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('kasir.receipt', $penjualan->id) }}" class="btn-apple-secondary text-xs py-2 px-3.5 inline-flex items-center gap-1.5">
                <i class="fas fa-print text-xs"></i> Cetak Struk
            </a>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('returns.create') }}?no_transaksi={{ $penjualan->no_transaksi }}" class="btn-apple-danger text-xs py-2 px-3.5 inline-flex items-center gap-1.5">
                    <i class="fas fa-undo-alt text-xs"></i> Proses Return
                </a>
            @endif
        </div>
    </div>

    {{-- Info Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Card 1: Transaction Metadata --}}
        <div class="apple-card p-6 flex flex-col justify-between">
            <div>
                <span class="text-[11px] font-semibold text-[#86868b] uppercase tracking-wider block mb-1">Informasi Transaksi</span>
                <div class="text-xl font-bold tracking-tight text-[#1d1d1f] font-mono mb-1">
                    {{ $penjualan->no_transaksi }}
                </div>
                <p class="text-xs text-[#86868b]">
                    {{ $penjualan->created_at->translatedFormat('d F Y, H:i') }} WIB
                </p>
            </div>

            <div class="divide-y divide-[#f0f0f0] mt-6 text-xs">
                <div class="py-2.5 flex items-center justify-between">
                    <span class="text-[#86868b]">Kasir Bertugas</span>
                    <span class="font-semibold text-[#1d1d1f]">{{ $penjualan->kasir_nama ?? $penjualan->user?->name ?? '-' }}</span>
                </div>
                <div class="py-2.5 flex items-center justify-between">
                    <span class="text-[#86868b]">Metode Pembayaran</span>
                    <span class="apple-badge {{ $penjualan->metode_bayar === 'Tunai' ? 'bg-[#eafaf1] text-[#1e7e34]' : 'bg-[#e8f2ff] text-[#0071e3]' }}">
                        {{ $penjualan->metode_bayar }}
                    </span>
                </div>
                <div class="py-2.5 flex items-center justify-between">
                    <span class="text-[#86868b]">Status Pembayaran</span>
                    <span class="apple-badge bg-[#eafaf1] text-[#1e7e34]">
                        {{ ucfirst($penjualan->status) }}
                    </span>
                </div>
                @if($penjualan->shift)
                    <div class="py-2.5 flex items-center justify-between">
                        <span class="text-[#86868b]">Sesi Shift</span>
                        <span class="font-medium text-[#1d1d1f]">{{ $penjualan->shift->waktu_buka->format('H:i') }} WIB</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Card 2: Financial Summary & Member --}}
        <div class="space-y-4">
            @if($penjualan->member)
                <div class="apple-card p-5 border-l-4 border-l-[#0071e3]">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#0071e3]">Member Terdaftar</span>
                            <div class="text-sm font-bold text-[#1d1d1f] mt-0.5">{{ $penjualan->member->name }}</div>
                            <div class="text-xs text-[#86868b]">{{ $penjualan->member->phone_number }}</div>
                        </div>
                        <div class="text-right">
                            <span class="apple-badge bg-[#e8f2ff] text-[#0071e3]">
                                <i class="fas fa-star text-[10px] mr-1"></i> {{ $penjualan->member->points }} poin
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="apple-card p-6 bg-[#fafafc]">
                <span class="text-[11px] font-semibold text-[#86868b] uppercase tracking-wider block mb-2">Ringkasan Pembayaran</span>
                <div class="text-3xl font-bold tracking-tight text-[#1d1d1f] tabular-nums mb-4">
                    Rp {{ number_format($penjualan->total, 0, ',', '.') }}
                </div>
                <div class="space-y-2 text-xs border-t border-[#e5e5ea] pt-3">
                    <div class="flex justify-between text-[#86868b]">
                        <span>Uang Diterima</span>
                        <span class="font-semibold text-[#1d1d1f] tabular-nums">Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-[#86868b]">
                        <span>Kembalian</span>
                        <span class="font-semibold text-[#1e7e34] tabular-nums">Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Items Detail Table --}}
    <div class="apple-card overflow-hidden">
        <div class="p-5 border-b border-[#f0f0f0] flex items-center justify-between">
            <h2 class="text-sm font-semibold text-[#1d1d1f] tracking-tight">Rincian Item Pembelian</h2>
            <span class="text-xs text-[#86868b]">{{ $penjualan->details->count() }} item</span>
        </div>
        <div class="overflow-x-auto">
            <table class="apple-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="text-center">Kuantitas</th>
                        <th class="text-right">Harga Satuan</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penjualan->details as $detail)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-[#f5f5f7] border border-[#e5e5ea] flex items-center justify-center overflow-hidden flex-shrink-0">
                                        @if($detail->product && $detail->product->img)
                                            <img src="{{ asset('storage/' . $detail->product->img) }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-box text-[#86868b] text-xs"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-[#1d1d1f]">{{ $detail->product->nama_produk ?? 'Produk' }}</div>
                                        <div class="text-[11px] text-[#86868b] font-mono">{{ $detail->product->kode_produk ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center font-semibold text-[#1d1d1f] tabular-nums">
                                {{ $detail->qty }}
                            </td>
                            <td class="text-right text-[#86868b] tabular-nums">
                                Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                            </td>
                            <td class="text-right font-semibold text-[#1d1d1f] tabular-nums">
                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-[#fafafc]">
                        <td colspan="3" class="text-right font-medium text-[#86868b] text-xs">Subtotal Bruto</td>
                        <td class="text-right font-semibold text-[#1d1d1f] tabular-nums">
                            Rp {{ number_format($penjualan->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @if($penjualan->diskon > 0)
                        <tr class="bg-[#fafafc]">
                            <td colspan="3" class="text-right font-medium text-[#e03131] text-xs">Diskon Langsung</td>
                            <td class="text-right font-semibold text-[#e03131] tabular-nums">
                                - Rp {{ number_format($penjualan->diskon, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endif
                    @if($penjualan->poin_digunakan > 0)
                        <tr class="bg-[#fafafc]">
                            <td colspan="3" class="text-right font-medium text-[#1e7e34] text-xs">Potongan Poin</td>
                            <td class="text-right font-semibold text-[#1e7e34] tabular-nums">
                                - Rp {{ number_format($penjualan->poin_digunakan, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endif
                    <tr class="bg-[#f5f5f7] border-t-2 border-[#e5e5ea]">
                        <td colspan="3" class="text-right font-bold text-[#1d1d1f] text-sm">TOTAL BERSIH</td>
                        <td class="text-right font-bold text-[#0071e3] text-lg tabular-nums">
                            Rp {{ number_format($penjualan->total, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
