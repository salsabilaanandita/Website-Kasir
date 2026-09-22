@extends('layouts.app')
@section('title', 'Hasil Pencarian')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-[#1d1d1f]">Hasil Pencarian: "{{ $query }}"</h1>
        <p class="text-xs text-[#86868b] mt-1">Daftar item produk dan data yang cocok dengan kata kunci.</p>
    </div>

    {{-- Products Results --}}
    <div class="apple-card overflow-hidden">
        <div class="p-4 border-b border-[#f0f0f0] bg-[#fafafc]">
            <h2 class="text-xs font-semibold uppercase tracking-wider text-[#1d1d1f]">Katalog Produk</h2>
        </div>
        <div class="p-5">
            <div class="grid gap-3">
                @forelse($products as $product)
                    <div class="apple-card p-4 flex items-center justify-between bg-[#fafafc]">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-white border border-[#e5e5ea] flex items-center justify-center overflow-hidden flex-shrink-0">
                                @if($product->img)
                                    <img src="{{ asset('storage/' . $product->img) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-box text-[#86868b] text-xs"></i>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-xs font-semibold text-[#1d1d1f]">{{ $product->nama_produk }}</h3>
                                <p class="text-[11px] text-[#86868b] mt-0.5">
                                    Rp {{ number_format($product->harga, 0, ',', '.') }} &bull; Stok: {{ $product->stok }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn-apple-secondary text-xs py-1 px-3">
                            Detail
                        </a>
                    </div>
                @empty
                    <div class="py-8 text-center text-[#86868b] text-xs">
                        Tidak ada produk yang cocok dengan pencarian.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection