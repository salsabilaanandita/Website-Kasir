@extends('layouts.app')
@section('title', 'Stok Inventori')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-[#1d1d1f] tracking-tight">Manajemen Inventori</h1>
            <p class="text-xs text-[#86868b] mt-1">Pantau kuantitas stok, pergerakan barang, dan valuasi aset</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('inventory.adjustment') }}" class="btn-apple-secondary">
                <i class="fas fa-sliders text-xs"></i>
                <span>Penyesuaian Stok</span>
            </a>
            <a href="{{ route('inventory.movement') }}" class="btn-apple-primary">
                <i class="fas fa-clock-rotate-left text-xs"></i>
                <span>Riwayat Stok</span>
            </a>
        </div>
    </div>

    {{-- Stats Grid (Apple Tile Aesthetic) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="apple-card p-4 flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-[#f5f5f7] text-[#1d1d1f] flex items-center justify-center text-sm border border-[#e5e5ea]">
                <i class="fas fa-boxes-stacked"></i>
            </div>
            <div>
                <div class="text-xs text-[#86868b] font-medium">Total Produk</div>
                <div class="text-lg font-semibold text-[#1d1d1f]">{{ number_format($totalProduk) }}</div>
            </div>
        </div>

        <div class="apple-card p-4 flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-[#fffbeb] text-[#d97706] flex items-center justify-center text-sm border border-[#fef3c7]">
                <i class="fas fa-triangle-exclamation"></i>
            </div>
            <div>
                <div class="text-xs text-[#86868b] font-medium">Stok Menipis (≤10)</div>
                <div class="text-lg font-semibold text-[#d97706]">{{ number_format($stokLow) }}</div>
            </div>
        </div>

        <div class="apple-card p-4 flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-[#fff2f2] text-[#d70015] flex items-center justify-center text-sm border border-[#ffccd0]">
                <i class="fas fa-circle-xmark"></i>
            </div>
            <div>
                <div class="text-xs text-[#86868b] font-medium">Stok Habis (0)</div>
                <div class="text-lg font-semibold text-[#d70015]">{{ number_format($stokHabis) }}</div>
            </div>
        </div>

        <div class="apple-card p-4 flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-[#f0fdf4] text-[#16a34a] flex items-center justify-center text-sm border border-[#dcfce7]">
                <i class="fas fa-coins"></i>
            </div>
            <div>
                <div class="text-xs text-[#86868b] font-medium">Total Valuasi</div>
                <div class="text-base font-semibold text-[#16a34a]">Rp {{ number_format($nilaiInventory, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="apple-card p-3.5">
        <form action="{{ route('inventory.index') }}" method="GET" class="flex flex-wrap gap-2.5 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..." 
                class="apple-input flex-1 min-w-[200px]">
            <select name="stok" class="apple-input w-auto">
                <option value="">Semua Status Stok</option>
                <option value="low" {{ request('stok')=='low' ? 'selected' : '' }}>Menipis (≤10)</option>
                <option value="habis" {{ request('stok')=='habis' ? 'selected' : '' }}>Habis (0)</option>
            </select>
            <button type="submit" class="btn-apple-dark py-1.5 px-4 text-xs">
                <i class="fas fa-filter text-xs"></i>
                <span>Terapkan</span>
            </button>
        </form>
    </div>

    {{-- Inventory Table Card --}}
    <div class="apple-card">
        <div class="px-5 py-3.5 border-b border-[#e5e5ea] flex items-center justify-between bg-white">
            <div class="flex items-center gap-2">
                <i class="fas fa-warehouse text-xs text-[#0071e3]"></i>
                <span class="text-xs font-semibold text-[#1d1d1f]">Detail Item Stok</span>
            </div>
            <span class="apple-badge">
                Total: {{ $products->total() }} produk
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="apple-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Produk</th>
                        <th style="text-align: right;">Harga Satuan</th>
                        <th style="text-align: center;">Jumlah Stok</th>
                        <th style="text-align: right;">Total Nilai</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center; width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                    <tr>
                        <td class="text-[#86868b] font-medium">{{ $loop->iteration + ($products->currentPage()-1) * $products->perPage() }}</td>
                        <td>
                            <div class="font-semibold text-[#1d1d1f]">{{ $p->nama_produk }}</div>
                            <div class="text-[11px] text-[#86868b]">Kategori: {{ $p->category->nama_kategori ?? 'Umum' }}</div>
                        </td>
                        <td style="text-align: right;" class="text-[#1d1d1f] font-medium">
                            Rp {{ number_format($p->harga, 0, ',', '.') }}
                        </td>
                        <td style="text-align: center;" class="font-semibold text-[#1d1d1f]">
                            {{ $p->stok }} unit
                        </td>
                        <td style="text-align: right;" class="font-semibold text-[#1d1d1f]">
                            Rp {{ number_format($p->harga * $p->stok, 0, ',', '.') }}
                        </td>
                        <td style="text-align: center;">
                            @if($p->stok == 0)
                                <span class="apple-badge apple-badge-red">Habis</span>
                            @elseif($p->stok <= 10)
                                <span class="apple-badge apple-badge-amber">Menipis</span>
                            @else
                                <span class="apple-badge apple-badge-green">Tersedia</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('products.edit', $p->id) }}" class="btn-apple-ghost" title="Edit Stok">
                                <i class="fas fa-sliders text-xs"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-[#86868b]">
                            <div class="w-12 h-12 bg-[#f5f5f7] rounded-full flex items-center justify-center mx-auto mb-3 text-[#86868b]">
                                <i class="fas fa-boxes-stacked text-xl"></i>
                            </div>
                            <p class="text-xs font-medium">Tidak ada data stok produk ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="p-4 border-t border-[#e5e5ea]">{{ $products->links() }}</div>
        @endif
    </div>
</div>
@endsection
