@extends('layouts.app')
@section('title', 'Proses Return')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('returns.index') }}" class="btn-apple-ghost inline-flex items-center gap-1.5 text-xs">
            <i class="fas fa-arrow-left text-xs"></i> Kembali ke Riwayat
        </a>
        <h1 class="text-xl font-bold tracking-tight text-[#1d1d1f]">Proses Return Penjualan</h1>
    </div>

    @if(session('error'))
        <div class="p-4 rounded-xl bg-[#fff0f0] border border-[#ffc9c9] text-[#e03131] text-xs font-medium flex items-center gap-2">
            <i class="fas fa-exclamation-circle text-sm"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Transaction Search Card --}}
    <div class="apple-card p-6">
        <form action="{{ route('returns.create') }}" method="GET" class="space-y-4">
            <label class="block text-xs font-semibold text-[#1d1d1f]">Cari Nomor Transaksi (TRX-...)</label>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-[#86868b] text-xs"></i>
                    <input type="text" name="no_transaksi" value="{{ request('no_transaksi') }}" class="apple-input pl-9 font-mono" placeholder="Masukkan nomor transaksi..." required>
                </div>
                <button type="submit" class="btn-apple-primary text-xs py-2 px-5">
                    Cari Transaksi
                </button>
            </div>
        </form>
    </div>

    @if(request()->has('no_transaksi') && !$penjualan)
        <div class="apple-card p-12 text-center">
            <i class="fas fa-search-minus text-3xl text-[#86868b] mb-3 block opacity-40"></i>
            <h3 class="text-sm font-semibold text-[#1d1d1f] mb-1">Transaksi Tidak Ditemukan</h3>
            <p class="text-xs text-[#86868b]">Pastikan format nomor transaksi yang Anda masukkan sudah benar.</p>
        </div>
    @endif

    @if($penjualan)
        <div class="apple-card overflow-hidden">
            <div class="p-5 border-b border-[#f0f0f0] bg-[#fafafc] flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#86868b] block">Nomor Transaksi</span>
                    <span class="font-mono font-bold text-sm text-[#1d1d1f]">{{ $penjualan->no_transaksi }}</span>
                </div>
                <div class="text-right text-xs text-[#86868b]">
                    {{ \Carbon\Carbon::parse($penjualan->created_at)->format('d M Y H:i') }} &bull; {{ $penjualan->kasir_nama }}
                </div>
            </div>

            <div class="p-5 space-y-4">
                @foreach($penjualan->details as $detail)
                    <form action="{{ route('returns.store') }}" method="POST" class="apple-card p-4 flex flex-col sm:flex-row items-center gap-4 bg-[#fbfbfe]">
                        @csrf
                        <input type="hidden" name="penjualan_id" value="{{ $penjualan->id }}">
                        <input type="hidden" name="product_id" value="{{ $detail->product_id }}">
                        
                        <div class="w-12 h-12 rounded-lg bg-white border border-[#e5e5ea] flex items-center justify-center flex-shrink-0 overflow-hidden">
                            @if($detail->product && $detail->product->img)
                                <img src="{{ asset('storage/' . $detail->product->img) }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-box text-[#86868b] text-xs"></i>
                            @endif
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs font-semibold text-[#1d1d1f] truncate">{{ $detail->product->nama_produk ?? 'Produk' }}</h4>
                            <div class="text-[11px] text-[#86868b] mt-0.5">
                                Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }} &times; {{ $detail->qty }} item
                            </div>
                        </div>

                        <div class="w-full sm:w-20">
                            <label class="block text-[10px] font-semibold text-[#86868b] uppercase mb-1">Qty</label>
                            <input type="number" name="qty" min="1" max="{{ $detail->qty }}" value="1" class="apple-input text-center py-1 text-xs font-bold" required>
                        </div>

                        <div class="w-full sm:w-44">
                            <label class="block text-[10px] font-semibold text-[#86868b] uppercase mb-1">Alasan</label>
                            <input type="text" name="alasan" class="apple-input py-1 text-xs" placeholder="Cth: Cacat fisik" required>
                        </div>

                        <div class="w-full sm:w-auto pt-2 sm:pt-4">
                            <button type="submit" class="btn-apple-danger text-xs py-1.5 px-3.5 w-full" onclick="return confirm('Konfirmasi proses return barang ini?')">
                                Return
                            </button>
                        </div>
                    </form>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
