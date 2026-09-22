@extends('layouts.app')
@section('title', 'Buka Sesi Shift')

@section('content')
<div class="max-w-lg mx-auto space-y-6">
    <div>
        <a href="{{ route('shifts.index') }}" class="btn-apple-ghost inline-flex items-center gap-1.5 text-xs mb-3">
            <i class="fas fa-arrow-left text-xs"></i> Kembali ke Riwayat Shift
        </a>
        <h1 class="text-2xl font-bold tracking-tight text-[#1d1d1f]">Buka Shift Kasir Baru</h1>
        <p class="text-xs text-[#86868b] mt-1">Masukkan nominal modal awal tunai yang berada di laci kasir saat ini.</p>
    </div>

    @if(session('error'))
        <div class="p-4 rounded-xl bg-[#fff0f0] border border-[#ffc9c9] text-[#e03131] text-xs font-medium flex items-center gap-2">
            <i class="fas fa-exclamation-circle text-sm"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="apple-card p-6">
        <form action="{{ route('shifts.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Modal Kas Awal (Tunai) <span class="text-[#e03131]">*</span></label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-bold text-[#86868b]">Rp</span>
                    <input type="number" name="modal_awal" class="apple-input pl-10 text-base font-bold tabular-nums" placeholder="0" min="0" required autofocus>
                </div>
                @error('modal_awal')
                    <p class="text-[#e03131] text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-[#86868b] mt-1.5 flex items-center gap-1">
                    <i class="fas fa-info-circle"></i> Pastikan uang fisik di laci telah dihitung sebelum memulai shift.
                </p>
            </div>

            <div class="pt-3 border-t border-[#f0f0f0] flex justify-end gap-2">
                <a href="{{ route('shifts.index') }}" class="btn-apple-secondary text-xs py-2 px-4">
                    Batal
                </a>
                <button type="submit" class="btn-apple-primary text-xs py-2 px-5 inline-flex items-center gap-2">
                    <i class="fas fa-play text-xs"></i> Buka Sesi Shift
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
