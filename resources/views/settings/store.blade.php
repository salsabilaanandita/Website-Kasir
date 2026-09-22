@extends('layouts.app')
@section('title', 'Pengaturan Toko')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-[#1d1d1f]">Pengaturan Toko & Struk</h1>
        <p class="text-xs text-[#86868b] mt-1">Konfigurasi informasi bisnis dan format struk kasir.</p>
    </div>

    {{-- Store Info Card --}}
    <div class="apple-card overflow-hidden">
        <div class="p-5 border-b border-[#f0f0f0] bg-[#fafafc]">
            <h2 class="text-sm font-semibold text-[#1d1d1f] tracking-tight flex items-center gap-2">
                <i class="fas fa-store text-[#0071e3] text-xs"></i> Profil Toko
            </h2>
        </div>
        <form action="{{ route('settings.store.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Nama Toko <span class="text-[#e03131]">*</span></label>
                    <input type="text" name="nama_toko" value="{{ $settings['nama_toko'] }}" required class="apple-input text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">No. Telepon / WhatsApp</label>
                    <input type="text" name="telepon" value="{{ $settings['telepon'] }}" class="apple-input text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Email Toko</label>
                    <input type="email" name="email" value="{{ $settings['email'] }}" class="apple-input text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Slogan / Tagline</label>
                    <input type="text" name="tagline" value="{{ $settings['tagline'] }}" placeholder="Misal: Belanja Hemat Berkualitas" class="apple-input text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Alamat Lengkap</label>
                <textarea name="alamat" rows="2" class="apple-input text-xs">{{ $settings['alamat'] }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Tarif Pajak PPN (%)</label>
                    <input type="number" name="pajak" value="{{ $settings['pajak'] }}" min="0" max="100" step="0.5" class="apple-input text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Logo Toko (Opsional)</label>
                    <input type="file" name="logo" accept="image/*" class="apple-input text-xs py-1.5">
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="btn-apple-primary text-xs py-2 px-5 inline-flex items-center gap-2">
                    <i class="fas fa-check text-xs"></i> Simpan Informasi Toko
                </button>
            </div>
        </form>
    </div>

    {{-- Receipt Settings Card --}}
    <div class="apple-card overflow-hidden">
        <div class="p-5 border-b border-[#f0f0f0] bg-[#fafafc]">
            <h2 class="text-sm font-semibold text-[#1d1d1f] tracking-tight flex items-center gap-2">
                <i class="fas fa-receipt text-[#0071e3] text-xs"></i> Kustomisasi Teks Struk
            </h2>
        </div>
        <form action="{{ route('settings.receipt.update') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Header Struk (Pesan Pembuka)</label>
                <textarea name="receipt_header" rows="2" placeholder="Teks khusus di bagian atas struk..." class="apple-input text-xs">{{ \App\Models\Setting::get('receipt_header') }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#1d1d1f] mb-1.5">Footer Struk (Pesan Penutup)</label>
                <textarea name="receipt_footer" rows="2" placeholder="Teks ucapan terima kasih di bagian bawah..." class="apple-input text-xs">{{ \App\Models\Setting::get('receipt_footer', 'Terima kasih telah berbelanja!') }}</textarea>
            </div>
            <div class="pt-2 flex justify-end">
                <button type="submit" class="btn-apple-primary text-xs py-2 px-5 inline-flex items-center gap-2">
                    <i class="fas fa-check text-xs"></i> Simpan Teks Struk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
