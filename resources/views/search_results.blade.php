@extends('layouts.app') 
@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Hasil Pencarian: "{{ $query }}"</h1>

    <section class="mb-8">
        <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">Produk</h2>
        <div class="grid gap-4">
            @forelse($products as $product)
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex justify-between items-center">
                    <div>
                        <p class="font-semibold text-slate-800">{{ $product->nama_produk }}</p>
                        <p class="text-xs text-slate-500">Harga: Rp{{ number_format($product->harga) }} | Stok: {{ $product->stok }}</p>
                    </div>
                    <a href="{{ route('products.edit', $product->id) }}" class="text-blue-600 text-sm font-medium">Detail</a>
                </div>
            @empty
                <p class="text-slate-400 text-sm italic">Produk tidak ditemukan.</p>
            @endforelse
        </div>
    </section>

    <section>
        <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">Transaksi</h2>
        <div class="grid gap-4">
            @forelse($transactions as $trx)
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold text-blue-600">#{{ $trx->invoice_number }}</p>
                            <p class="text-sm text-slate-800 font-medium">{{ $trx->customer_name }}</p>
                            <p class="text-xs text-slate-500">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <p class="font-bold text-slate-800 text-lg">Rp{{ number_format($trx->grand_total) }}</p>
                    </div>
                </div>
            @empty
                <p class="text-slate-400 text-sm italic">Transaksi tidak ditemukan.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection