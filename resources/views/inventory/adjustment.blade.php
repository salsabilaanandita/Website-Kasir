@extends('layouts.app')
@section('title', 'Penyesuaian Stok')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('inventory.index') }}" class="btn-apple-ghost inline-flex items-center gap-1.5 text-xs">
            <i class="fas fa-arrow-left text-xs"></i> Kembali ke Inventori
        </a>
        <h1 class="text-xl font-bold tracking-tight text-[#1d1d1f]">Penyesuaian Stok (Adjustment)</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Form (4 cols) --}}
        <div class="lg:col-span-4">
            <div class="apple-card overflow-hidden">
                <div class="p-4 border-b border-[#f0f0f0] bg-[#fafafc]">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-[#1d1d1f] flex items-center gap-2">
                        <i class="fas fa-sliders text-[#0071e3]"></i> Buat Penyesuaian
                    </h2>
                </div>
                <form action="{{ route('inventory.adjustment.store') }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Pilih Produk <span class="text-[#e03131]">*</span></label>
                        <select name="product_id" id="prodSelect" required class="apple-input text-xs">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $prod)
                                <option value="{{ $prod->id }}" data-stok="{{ $prod->stok }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>
                                    {{ $prod->nama_produk }} (Stok: {{ $prod->stok }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="p-3.5 rounded-xl bg-[#fafafc] border border-[#f0f0f0] grid grid-cols-2 gap-3 text-center">
                        <div>
                            <span class="text-[10px] font-semibold text-[#86868b] uppercase block">Stok Sistem</span>
                            <span id="stokSistem" class="text-base font-bold text-[#1d1d1f] tabular-nums">-</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-semibold text-[#86868b] uppercase block">Selisih</span>
                            <span id="stokSelisih" class="text-base font-bold text-[#86868b] tabular-nums">0</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Stok Fisik Aktual <span class="text-[#e03131]">*</span></label>
                        <input type="number" name="stok_fisik" id="stokFisik" required min="0" placeholder="0" class="apple-input text-xs font-bold tabular-nums">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Keterangan / Alasan</label>
                        <textarea name="keterangan" rows="2" placeholder="Misal: Barang rusak atau hilang" class="apple-input text-xs"></textarea>
                    </div>

                    <button type="submit" class="btn-apple-primary w-full py-2.5 text-xs justify-center flex items-center gap-2">
                        Simpan Penyesuaian Stok
                    </button>
                </form>
            </div>
        </div>

        {{-- History Table (8 cols) --}}
        <div class="lg:col-span-8">
            <div class="apple-card overflow-hidden">
                <div class="p-4 border-b border-[#f0f0f0] bg-[#fafafc] flex items-center justify-between">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-[#1d1d1f]">Riwayat Penyesuaian</h2>
                    <span class="text-xs text-[#86868b]">{{ $adjustments->total() ?? 0 }} data</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="apple-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>No. Penyesuaian</th>
                                <th>Produk</th>
                                <th class="text-center">Sistem</th>
                                <th class="text-center">Fisik</th>
                                <th class="text-center">Selisih</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($adjustments as $a)
                                <tr>
                                    <td class="text-[#86868b] font-medium">{{ $adjustments->firstItem() + $loop->index }}</td>
                                    <td class="font-mono text-xs font-semibold text-[#0071e3]">{{ $a->no_adjustment }}</td>
                                    <td class="font-medium text-[#1d1d1f]">{{ $a->product?->nama_produk }}</td>
                                    <td class="text-center text-[#86868b] tabular-nums">{{ $a->stok_sistem }}</td>
                                    <td class="text-center font-bold text-[#1d1d1f] tabular-nums">{{ $a->stok_fisik }}</td>
                                    <td class="text-center font-bold tabular-nums {{ $a->selisih < 0 ? 'text-[#e03131]' : ($a->selisih > 0 ? 'text-[#1e7e34]' : 'text-[#86868b]') }}">
                                        {{ $a->selisih > 0 ? '+'.$a->selisih : $a->selisih }}
                                    </td>
                                    <td class="text-xs text-[#86868b] tabular-nums">{{ $a->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-12 text-[#86868b]">
                                        <i class="fas fa-sliders text-3xl mb-3 block opacity-40"></i>
                                        <span class="text-sm">Belum ada riwayat penyesuaian stok</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($adjustments->hasPages())
                    <div class="p-4 border-t border-[#f0f0f0]">
                        {{ $adjustments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const select = document.getElementById('prodSelect');
const fisikInput = document.getElementById('stokFisik');
const sysLabel = document.getElementById('stokSistem');
const diffLabel = document.getElementById('stokSelisih');

function updateCalc() {
    const opt = select.options[select.selectedIndex];
    if(!opt.value) {
        sysLabel.textContent = '-';
        diffLabel.textContent = '0';
        return;
    }
    const sys = parseInt(opt.dataset.stok);
    const fisik = parseInt(fisikInput.value || 0);
    const diff = fisik - sys;
    sysLabel.textContent = sys;
    diffLabel.textContent = diff > 0 ? '+'+diff : diff;
    diffLabel.style.color = diff < 0 ? '#e03131' : (diff > 0 ? '#1e7e34' : '#86868b');
}

select.addEventListener('change', updateCalc);
fisikInput.addEventListener('input', updateCalc);
if(select.value) updateCalc();
</script>
@endpush
