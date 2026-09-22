@extends('layouts.app')
@section('title', 'Edit Pengeluaran')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-[#1d1d1f] tracking-tight">Edit Pengeluaran</h1>
            <p class="text-xs text-[#86868b] mt-1">Perbarui rincian biaya pengeluaran</p>
        </div>
        <a href="{{ route('expenses.index') }}" class="btn-apple-secondary">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="apple-card p-6">
        <form action="{{ route('expenses.update', $expense->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Judul Pengeluaran <span class="text-[#d70015]">*</span></label>
                <input type="text" name="judul" value="{{ old('judul', $expense->judul) }}" required
                    class="apple-input @error('judul') border-[#d70015] @enderror">
                @error('judul')
                    <div class="text-[#d70015] text-[11px] mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Kategori Biaya <span class="text-[#d70015]">*</span></label>
                <select name="kategori" required class="apple-input">
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat }}" {{ old('kategori', $expense->kategori) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Nominal Biaya (Rp) <span class="text-[#d70015]">*</span></label>
                    <input type="number" name="jumlah" value="{{ old('jumlah', $expense->jumlah) }}" required min="0"
                        class="apple-input">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Tanggal <span class="text-[#d70015]">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $expense->tanggal->format('Y-m-d')) }}" required
                        class="apple-input">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Keterangan</label>
                <textarea name="keterangan" rows="3"
                    class="apple-input h-auto py-2">{{ old('keterangan', $expense->keterangan) }}</textarea>
            </div>

            <div class="pt-4 border-t border-[#e5e5ea] flex items-center justify-end gap-3">
                <a href="{{ route('expenses.index') }}" class="btn-apple-ghost">Batal</a>
                <button type="submit" class="btn-apple-primary">
                    <i class="fas fa-check text-xs"></i>
                    <span>Perbarui Pengeluaran</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
