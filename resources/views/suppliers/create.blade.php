@extends('layouts.app')
@section('title', 'Tambah Supplier')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-[#1d1d1f] tracking-tight">Tambah Supplier</h1>
            <p class="text-xs text-[#86868b] mt-1">Daftarkan mitra atau pemasok barang baru</p>
        </div>
        <a href="{{ route('suppliers.index') }}" class="btn-apple-secondary">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="apple-card p-6">
        <form action="{{ route('suppliers.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Nama Supplier <span class="text-[#d70015]">*</span></label>
                <input type="text" name="nama_supplier" value="{{ old('nama_supplier') }}" required placeholder="Nama PT / Toko Supplier"
                    class="apple-input @error('nama_supplier') border-[#d70015] @enderror">
                @error('nama_supplier')
                    <div class="text-[#d70015] text-[11px] mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Kontak Person</label>
                    <input type="text" name="kontak_person" value="{{ old('kontak_person') }}" placeholder="Nama PIC"
                        class="apple-input">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">No. Telepon / WhatsApp</label>
                    <input type="text" name="telepon" value="{{ old('telepon') }}" placeholder="08..."
                        class="apple-input">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="supplier@email.com"
                    class="apple-input">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Alamat Lengkap</label>
                <textarea name="alamat" rows="3" placeholder="Alamat kantor atau gudang supplier..."
                    class="apple-input h-auto py-2">{{ old('alamat') }}</textarea>
            </div>

            <div class="pt-4 border-t border-[#e5e5ea] flex items-center justify-end gap-3">
                <a href="{{ route('suppliers.index') }}" class="btn-apple-ghost">Batal</a>
                <button type="submit" class="btn-apple-primary">
                    <i class="fas fa-check text-xs"></i>
                    <span>Simpan Supplier</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
