@extends('layouts.app')
@section('title', 'Tambah Produk')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-[#1d1d1f] tracking-tight">Tambah Produk Baru</h1>
            <p class="text-xs text-[#86868b] mt-1">Masukkan informasi produk untuk ditambahkan ke katalog kasir</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn-apple-secondary">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Kembali</span>
        </a>
    </div>

    {{-- Error Alert --}}
    @if($errors->any())
        <div class="p-4 bg-[#fff2f2] border border-[#ffccd0] text-[#d70015] rounded-xl text-xs">
            <div class="font-semibold mb-1">Periksa kembali data yang dimasukkan:</div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="apple-card p-6">
        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Product Name --}}
                <div class="sm:col-span-2">
                    <label for="nama_produk" class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Nama Produk <span class="text-[#d70015]">*</span></label>
                    <input type="text" class="apple-input @error('nama_produk') border-[#d70015] @enderror" 
                        id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" 
                        required minlength="2" maxlength="255"
                        placeholder="Contoh: Kopi Susu Aren">
                    @error('nama_produk')
                        <p class="text-[#d70015] text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Price --}}
                <div>
                    <label for="harga" class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Harga Jual (Rp) <span class="text-[#d70015]">*</span></label>
                    <input type="number" class="apple-input @error('harga') border-[#d70015] @enderror" 
                        id="harga" name="harga" value="{{ old('harga') }}" 
                        min="0" placeholder="Contoh: 18000" required>
                    @error('harga')
                        <p class="text-[#d70015] text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Initial Stock --}}
                <div>
                    <label for="stok" class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Stok Awal <span class="text-[#d70015]">*</span></label>
                    <input type="number" class="apple-input @error('stok') border-[#d70015] @enderror" 
                        id="stok" name="stok" value="{{ old('stok', 0) }}" 
                        min="0" required placeholder="0">
                    @error('stok')
                        <p class="text-[#d70015] text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Product Image --}}
                <div class="sm:col-span-2">
                    <label for="img" class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Foto Produk</label>
                    <input type="file" class="apple-input file:mr-3 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#f5f5f7] file:text-[#0071e3] hover:file:bg-[#e5e5ea] cursor-pointer" 
                        id="img" name="img" accept="image/jpeg,image/png,image/jpg,image/webp">
                    <p class="text-[11px] text-[#86868b] mt-1">Format: JPG, PNG, atau WebP (Maks: 2MB)</p>
                    @error('img')
                        <p class="text-[#d70015] text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-4 border-t border-[#e5e5ea] flex items-center justify-end gap-3">
                <a href="{{ route('products.index') }}" class="btn-apple-ghost">Batal</a>
                <button type="submit" class="btn-apple-primary">
                    <i class="fas fa-check text-xs"></i>
                    <span>Simpan Produk</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection