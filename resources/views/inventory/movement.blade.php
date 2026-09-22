@extends('layouts.app')
@section('title', 'Pergerakan Stok')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('inventory.index') }}" class="btn-apple-ghost inline-flex items-center gap-1.5 text-xs">
            <i class="fas fa-arrow-left text-xs"></i> Kembali ke Inventori
        </a>
        <h1 class="text-xl font-bold tracking-tight text-[#1d1d1f]">Pergerakan Stok Barang</h1>
    </div>

    {{-- Filter Toolbar --}}
    <div class="apple-card p-4">
        <form action="{{ route('inventory.movement') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <select name="product_id" class="apple-input text-xs">
                    <option value="">Semua Produk</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>{{ $prod->nama_produk }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-44">
                <select name="tipe" class="apple-input text-xs">
                    <option value="">Semua Tipe Perubahan</option>
                    <option value="masuk" {{ request('tipe')=='masuk' ? 'selected' : '' }}>Masuk (Restock)</option>
                    <option value="keluar" {{ request('tipe')=='keluar' ? 'selected' : '' }}>Keluar (Penjualan)</option>
                    <option value="adjustment" {{ request('tipe')=='adjustment' ? 'selected' : '' }}>Penyesuaian (Adjustment)</option>
                </select>
            </div>
            <button type="submit" class="btn-apple-primary text-xs py-2 px-4">
                <i class="fas fa-filter text-xs mr-1"></i> Filter
            </button>
        </form>
    </div>

    {{-- Movements Table --}}
    <div class="apple-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="apple-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Produk</th>
                        <th class="text-center">Tipe</th>
                        <th class="text-center">Kuantitas</th>
                        <th class="text-center">Stok Sebelum</th>
                        <th class="text-center">Stok Sesudah</th>
                        <th>Referensi</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $m)
                        <tr>
                            <td class="text-[#86868b] font-medium">{{ $movements->firstItem() + $loop->index }}</td>
                            <td class="font-medium text-[#1d1d1f]">{{ $m->product?->nama_produk ?? '-' }}</td>
                            <td class="text-center">
                                @if($m->tipe === 'masuk')
                                    <span class="apple-badge bg-[#eafaf1] text-[#1e7e34] border-[#c3f0d4]">
                                        <i class="fas fa-arrow-down text-[10px] mr-1"></i> Masuk
                                    </span>
                                @elseif($m->tipe === 'keluar')
                                    <span class="apple-badge bg-[#fff0f0] text-[#e03131] border-[#ffc9c9]">
                                        <i class="fas fa-arrow-up text-[10px] mr-1"></i> Keluar
                                    </span>
                                @else
                                    <span class="apple-badge bg-[#e8f2ff] text-[#0071e3] border-[#cce4ff]">
                                        <i class="fas fa-rotate text-[10px] mr-1"></i> Adjustment
                                    </span>
                                @endif
                            </td>
                            <td class="text-center font-bold tabular-nums {{ $m->tipe === 'keluar' ? 'text-[#e03131]' : 'text-[#1e7e34]' }}">
                                {{ $m->tipe === 'keluar' ? '-' : '+' }}{{ $m->qty }}
                            </td>
                            <td class="text-center text-[#86868b] tabular-nums">{{ $m->stok_sebelum }}</td>
                            <td class="text-center font-semibold text-[#1d1d1f] tabular-nums">{{ $m->stok_sesudah }}</td>
                            <td class="font-mono text-xs text-[#0071e3]">{{ $m->referensi ?? '-' }}</td>
                            <td class="text-xs text-[#86868b] tabular-nums">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-[#86868b]">
                                <i class="fas fa-boxes-packing text-3xl mb-3 block opacity-40"></i>
                                <span class="text-sm">Belum ada mutasi stok tercatat</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($movements->hasPages())
            <div class="p-4 border-t border-[#f0f0f0]">
                {{ $movements->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
