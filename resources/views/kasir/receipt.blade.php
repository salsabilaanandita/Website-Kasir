@extends('layouts.app')
@section('title', 'Struk Pembayaran')

@section('content')
<div class="max-w-md mx-auto space-y-6">
    {{-- Notification Badge --}}
    <div class="text-center">
        <span class="apple-badge bg-[#eafaf1] text-[#1e7e34] border-[#c3f0d4] text-xs py-1 px-4 font-semibold inline-flex items-center gap-1.5">
            <i class="fas fa-circle-check text-xs"></i> Transaksi Berhasil Diproses
        </span>
    </div>

    {{-- Main Receipt Card --}}
    <div class="apple-card overflow-hidden shadow-sm" id="receiptPrintArea">
        {{-- Store Header --}}
        <div class="p-6 text-center border-b border-[#f0f0f0] bg-[#fafafc]">
            <div class="w-12 h-12 rounded-full bg-[#1d1d1f] text-white flex items-center justify-center mx-auto mb-3 text-lg">
                <i class="fas fa-store"></i>
            </div>
            <h2 class="text-base font-bold text-[#1d1d1f]">{{ \App\Models\Setting::get('nama_toko', 'Kasir App') }}</h2>
            <p class="text-xs text-[#86868b] mt-0.5">{{ \App\Models\Setting::get('alamat', 'Indonesia') }}</p>
            
            <div class="mt-4 pt-3 border-t border-[#e5e5ea] flex items-center justify-between text-xs">
                <span class="text-[#86868b]">No. Transaksi</span>
                <span class="font-mono font-bold text-[#1d1d1f]">{{ $penjualan->no_transaksi }}</span>
            </div>
        </div>

        {{-- Meta & Items Details --}}
        <div class="p-6 space-y-4">
            <div class="space-y-1.5 text-xs text-[#86868b] pb-3 border-b border-[#f0f0f0]">
                <div class="flex justify-between">
                    <span>Waktu</span>
                    <span class="text-[#1d1d1f] font-medium tabular-nums">{{ $penjualan->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Kasir</span>
                    <span class="text-[#1d1d1f] font-medium">{{ $penjualan->kasir_nama }}</span>
                </div>
                @if($penjualan->member)
                    <div class="flex justify-between">
                        <span>Member</span>
                        <span class="text-[#0071e3] font-semibold">{{ $penjualan->member->name }}</span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <span>Metode</span>
                    <span class="text-[#1d1d1f] font-medium">{{ $penjualan->metode_bayar }}</span>
                </div>
            </div>

            {{-- Purchased Items --}}
            <div class="space-y-2.5">
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#86868b] block">Detail Barang</span>
                @foreach($penjualan->details as $detail)
                    <div class="flex justify-between items-start text-xs">
                        <div class="pr-2">
                            <div class="font-medium text-[#1d1d1f]">{{ $detail->product->nama_produk }}</div>
                            <div class="text-[11px] text-[#86868b] tabular-nums">
                                {{ $detail->qty }} &times; Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="font-semibold text-[#1d1d1f] tabular-nums">
                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Calculations --}}
            <div class="pt-3 border-t border-[#f0f0f0] space-y-1.5 text-xs">
                <div class="flex justify-between text-[#86868b]">
                    <span>Subtotal</span>
                    <span class="text-[#1d1d1f] font-medium tabular-nums">Rp {{ number_format($penjualan->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($penjualan->diskon > 0)
                    <div class="flex justify-between text-[#e03131]">
                        <span>Diskon</span>
                        <span class="font-medium tabular-nums">- Rp {{ number_format($penjualan->diskon, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if($penjualan->poin_digunakan > 0)
                    <div class="flex justify-between text-[#1e7e34]">
                        <span>Poin Member</span>
                        <span class="font-medium tabular-nums">- Rp {{ number_format($penjualan->poin_digunakan, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between items-center pt-2 border-t border-[#e5e5ea] text-sm">
                    <span class="font-bold text-[#1d1d1f]">TOTAL</span>
                    <span class="font-bold text-[#0071e3] text-base tabular-nums">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-[#86868b] pt-1">
                    <span>Bayar</span>
                    <span class="text-[#1d1d1f] font-medium tabular-nums">Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-[#1e7e34] font-semibold bg-[#eafaf1] p-2 rounded-lg mt-2">
                    <span>Kembalian</span>
                    <span class="tabular-nums">Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="p-5 border-t border-[#f0f0f0] bg-[#fafafc] text-center text-xs text-[#86868b]">
            <p>{{ \App\Models\Setting::get('receipt_footer', 'Terima kasih atas kunjungan Anda!') }}</p>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex gap-3">
        <button onclick="window.print()" class="btn-apple-primary flex-1 py-2.5 justify-center inline-flex items-center gap-2 text-xs">
            <i class="fas fa-print text-xs"></i> Cetak Struk
        </button>
        <a href="{{ route('kasir.index') }}" class="btn-apple-secondary flex-1 py-2.5 justify-center inline-flex items-center gap-2 text-xs">
            <i class="fas fa-plus text-xs"></i> Transaksi Baru
        </a>
    </div>
</div>
@endsection
