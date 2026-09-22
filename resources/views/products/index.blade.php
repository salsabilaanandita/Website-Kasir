@extends('layouts.app')
@section('title', 'Katalog Produk')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-[#1d1d1f] tracking-tight">Katalog Produk</h1>
            <p class="text-xs text-[#86868b] mt-1">Daftar item produk, harga jual, dan stok saat ini</p>
        </div>
        @if(in_array(Auth::user()->role, ['admin', 'manager']))
            <a href="{{ route('products.create') }}" class="btn-apple-primary">
                <i class="fas fa-plus text-xs"></i>
                <span>Tambah Produk</span>
            </a>
        @endif
    </div>

    {{-- Products Table Card --}}
    <div class="apple-card">
        <div class="px-5 py-3.5 border-b border-[#e5e5ea] flex items-center justify-between bg-white">
            <div class="flex items-center gap-2">
                <i class="fas fa-box text-xs text-[#0071e3]"></i>
                <span class="text-xs font-semibold text-[#1d1d1f]">Daftar Produk</span>
            </div>
            <span class="apple-badge">
                Total: {{ $products->total() }} item
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="apple-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="width: 60px;">Foto</th>
                        <th>Nama Produk</th>
                        <th>Harga Jual</th>
                        <th>Status Stok</th>
                        @if(in_array(Auth::user()->role, ['admin', 'manager']))
                            <th style="text-align: center; width: 120px;">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="text-[#86868b] font-medium">{{ $products->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="w-10 h-10 rounded-lg overflow-hidden bg-[#f5f5f7] border border-[#e5e5ea] flex items-center justify-center">
                                @if($product->img)
                                    <img src="{{ asset('storage/' . $product->img) }}" 
                                         alt="{{ $product->nama_produk }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-box text-[#86868b] text-xs"></i>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="font-semibold text-[#1d1d1f]">{{ $product->nama_produk }}</div>
                            <div class="text-[11px] text-[#86868b]">Kode ID: #{{ $product->id }} &bull; Kat: {{ $product->category->nama_kategori ?? '-' }}</div>
                        </td>
                        <td class="font-semibold text-[#1d1d1f]">
                            Rp {{ number_format($product->harga, 0, ',', '.') }}
                        </td>
                        <td>
                            @if($product->stok == 0)
                                <span class="apple-badge apple-badge-red">
                                    <i class="fas fa-circle-xmark text-[9px]"></i> Stok Habis
                                </span>
                            @elseif($product->stok <= 5)
                                <span class="apple-badge apple-badge-amber">
                                    <i class="fas fa-triangle-exclamation text-[9px]"></i> Sisa {{ $product->stok }}
                                </span>
                            @else
                                <span class="apple-badge apple-badge-green">
                                    <i class="fas fa-circle-check text-[9px]"></i> {{ $product->stok }} unit
                                </span>
                            @endif
                        </td>
                        @if(in_array(Auth::user()->role, ['admin', 'manager']))
                            <td style="text-align: center;">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn-apple-ghost" title="Edit Produk">
                                        <i class="fas fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-apple-danger" title="Hapus Produk">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ in_array(Auth::user()->role, ['admin', 'manager']) ? '6' : '5' }}" class="text-center py-12 text-[#86868b]">
                            <div class="w-12 h-12 bg-[#f5f5f7] rounded-full flex items-center justify-center mx-auto mb-3 text-[#86868b]">
                                <i class="fas fa-box-open text-xl"></i>
                            </div>
                            <p class="text-xs font-medium">Belum ada produk dalam katalog</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-[#e5e5ea]">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection