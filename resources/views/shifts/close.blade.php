@extends('layouts.app')
@section('title', 'Tutup Sesi Shift')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('shifts.index') }}" class="btn-apple-ghost inline-flex items-center gap-1.5 text-xs mb-1">
                <i class="fas fa-arrow-left text-xs"></i> Kembali ke Shift
            </a>
            <h1 class="text-2xl font-bold tracking-tight text-[#1d1d1f]">Tutup Sesi Shift Kasir</h1>
        </div>
        <div class="w-10 h-10 rounded-full bg-[#fff0f0] text-[#e03131] flex items-center justify-center text-sm">
            <i class="fas fa-lock"></i>
        </div>
    </div>

    @php
        $expected = $activeShift->modal_awal + $activeShift->total_tunai;
    @endphp

    {{-- Financial Overview Cards --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="apple-card p-4 text-center">
            <span class="text-[10px] uppercase font-bold text-[#86868b] block mb-1">Modal Kas Awal</span>
            <div class="text-base font-bold text-[#1d1d1f] tabular-nums">Rp {{ number_format($activeShift->modal_awal, 0, ',', '.') }}</div>
        </div>
        <div class="apple-card p-4 text-center">
            <span class="text-[10px] uppercase font-bold text-[#86868b] block mb-1">Penjualan Tunai</span>
            <div class="text-base font-bold text-[#1e7e34] tabular-nums">Rp {{ number_format($activeShift->total_tunai, 0, ',', '.') }}</div>
        </div>
        <div class="col-span-2 apple-card p-5 text-center bg-[#fafafc] border-[#e5e5ea]">
            <span class="text-[11px] uppercase font-bold text-[#86868b] block mb-1">Total Harapan Kas Fisik di Laci</span>
            <div class="text-2xl font-bold text-[#1d1d1f] tabular-nums">Rp {{ number_format($expected, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Close Form --}}
    <div class="apple-card p-6">
        <form action="{{ route('shifts.tutup') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Kas Akhir Aktual (Fisik di Laci) <span class="text-[#e03131]">*</span></label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-bold text-[#86868b]">Rp</span>
                    <input type="number" name="kas_akhir" id="kas_akhir" class="apple-input pl-10 text-lg font-bold tabular-nums" placeholder="0" min="0" required autofocus oninput="calcDiff()">
                </div>
                @error('kas_akhir')
                    <p class="text-[#e03131] text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="diffAlert" class="p-3.5 rounded-xl text-xs font-medium hidden"></div>

            <div>
                <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Catatan Penutupan (Opsional)</label>
                <textarea name="catatan" rows="2" class="apple-input text-xs" placeholder="Tuliskan keterangan jika ada selisih kas fisik..."></textarea>
            </div>

            <div class="pt-3 border-t border-[#f0f0f0] flex justify-end gap-2">
                <a href="{{ route('shifts.index') }}" class="btn-apple-secondary text-xs py-2 px-4">
                    Batal
                </a>
                <button type="submit" class="btn-apple-danger text-xs py-2 px-5 inline-flex items-center gap-2" onclick="return confirm('Konfirmasi penutupan shift kasir sekarang? Data tidak dapat diubah setelah ditutup.')">
                    <i class="fas fa-lock text-xs"></i> Tutup Sesi Shift
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const expected = {{ $expected }};
    function calcDiff() {
        const input = document.getElementById('kas_akhir').value;
        const diffAlert = document.getElementById('diffAlert');
        if (input === '') {
            diffAlert.classList.add('hidden');
            return;
        }

        const val = parseFloat(input);
        const diff = val - expected;
        
        diffAlert.className = 'p-3.5 rounded-xl text-xs font-medium border';

        if (diff === 0) {
            diffAlert.classList.add('bg-[#eafaf1]', 'text-[#1e7e34]', 'border-[#c3f0d4]');
            diffAlert.innerHTML = '<i class="fas fa-check-circle mr-1.5"></i> Kas fisik <strong>Sesuai / Klop</strong> (tidak ada selisih).';
        } else if (diff < 0) {
            diffAlert.classList.add('bg-[#fff0f0]', 'text-[#e03131]', 'border-[#ffc9c9]');
            diffAlert.innerHTML = `<i class="fas fa-triangle-exclamation mr-1.5"></i> Terdapat <strong>Selisih Kurang</strong> sebesar Rp ${Math.abs(diff).toLocaleString('id-ID')}.`;
        } else {
            diffAlert.classList.add('bg-[#e8f2ff]', 'text-[#0071e3]', 'border-[#cce4ff]');
            diffAlert.innerHTML = `<i class="fas fa-info-circle mr-1.5"></i> Terdapat <strong>Selisih Lebih</strong> sebesar Rp ${diff.toLocaleString('id-ID')}.`;
        }
    }
</script>
@endpush
