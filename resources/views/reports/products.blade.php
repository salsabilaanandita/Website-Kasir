@extends('layouts.app')
@section('title', 'Produk Terlaris')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#1d1d1f]">Peringkat Produk Terlaris</h1>
            <p class="text-xs text-[#86868b] mt-1">Daftar item produk dengan volume unit penjualan tertinggi.</p>
        </div>
        
        <form action="{{ route('reports.products') }}" method="GET" class="flex flex-col sm:flex-row gap-2.5">
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

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        @if($topProducts->count() > 0)
            <div class="lg:col-span-4">
                <div class="apple-card p-6 text-center">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#0071e3] block mb-4">Peringkat #1 Terlaris</span>
                    <div class="w-24 h-24 rounded-2xl bg-[#fafafc] border border-[#e5e5ea] flex items-center justify-center mx-auto mb-4 overflow-hidden">
                        @if($topProducts[0]->img)
                            <img src="{{ asset('storage/' . $topProducts[0]->img) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-box text-3xl text-[#86868b]"></i>
                        @endif
                    </div>
                    <h3 class="text-base font-bold text-[#1d1d1f] line-clamp-2 leading-snug mb-2">{{ $topProducts[0]->nama_produk }}</h3>
                    <span class="apple-badge bg-[#e8f2ff] text-[#0071e3] border-[#cce4ff] font-bold text-xs py-1 px-3">
                        Terjual {{ $topProducts[0]->total_qty }} pcs
                    </span>
                </div>
            </div>
        @endif

        <div class="{{ $topProducts->count() > 0 ? 'lg:col-span-8' : 'lg:col-span-12' }}">
            <div class="apple-card overflow-hidden">
                <div class="p-4 border-b border-[#f0f0f0]">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-[#1d1d1f]">Daftar Penjualan Produk</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="apple-table">
                        <thead>
                            <tr>
                                <th class="text-center w-12">#</th>
                                <th>Produk</th>
                                <th class="text-right">Harga Satuan</th>
                                <th class="text-center">Kuantitas Terjual</th>
                                <th class="text-right">Total Omzet</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $index => $item)
                                @php $rank = $topProducts->firstItem() + $index; @endphp
                                <tr>
                                    <td class="text-center">
                                        <span class="w-6 h-6 rounded-full inline-flex items-center justify-center text-xs font-bold {{ $rank === 1 ? 'bg-[#1d1d1f] text-white' : 'bg-[#f5f5f7] text-[#86868b]' }}">
                                            {{ $rank }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-lg bg-[#fafafc] border border-[#e5e5ea] flex items-center justify-center overflow-hidden flex-shrink-0">
                                                @if($item->img)
                                                    <img src="{{ asset('storage/' . $item->img) }}" class="w-full h-full object-cover">
                                                @else
                                                    <i class="fas fa-box text-[#86868b] text-[10px]"></i>
                                                @endif
                                            </div>
                                            <span class="font-medium text-xs text-[#1d1d1f]">{{ $item->nama_produk }}</span>
                                        </div>
                                    </td>
                                    <td class="text-right text-xs text-[#86868b] tabular-nums">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center font-bold text-xs text-[#1d1d1f] tabular-nums">
                                        {{ number_format($item->total_qty, 0, ',', '.') }}
                                    </td>
                                    <td class="text-right font-semibold text-xs text-[#1d1d1f] tabular-nums">
                                        Rp {{ number_format($item->total_omzet, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12 text-[#86868b]">
                                        <i class="fas fa-boxes-stacked text-3xl mb-3 block opacity-40"></i>
                                        <span class="text-sm">Belum ada penjualan produk pada periode ini</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($topProducts->hasPages())
                    <div class="p-4 border-t border-[#f0f0f0]">
                        {{ $topProducts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
