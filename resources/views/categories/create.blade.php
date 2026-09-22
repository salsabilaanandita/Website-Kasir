@extends('layouts.app')
@section('title', 'Tambah Kategori')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-[#1d1d1f] tracking-tight">Tambah Kategori</h1>
            <p class="text-xs text-[#86868b] mt-1">Buat kategori baru untuk mengelompokkan produk</p>
        </div>
        <a href="{{ route('categories.index') }}" class="btn-apple-secondary">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="apple-card p-6">
        <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Nama Kategori <span class="text-[#d70015]">*</span></label>
                <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}" required placeholder="Misal: Minuman, Makanan, Snack"
                    class="apple-input @error('nama_kategori') border-[#d70015] @enderror">
                @error('nama_kategori')
                    <div class="text-[#d70015] text-[11px] mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Deskripsi</label>
                <textarea name="deskripsi" rows="3" placeholder="Keterangan singkat..."
                    class="apple-input h-auto py-2">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="pt-4 border-t border-[#e5e5ea] flex items-center justify-end gap-3">
                <a href="{{ route('categories.index') }}" class="btn-apple-ghost">Batal</a>
                <button type="submit" class="btn-apple-primary">
                    <i class="fas fa-check text-xs"></i>
                    <span>Simpan Kategori</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
