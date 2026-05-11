@extends('layouts.app')
@section('title', 'Edit Product')

@section('content')
<div class="px-4 py-4">
    {{-- Header Section --}}
    <div class="flex flex-wrap items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Product</h1>
            <p class="text-sm text-gray-500 mt-1">Update product information</p>
        </div>
        <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-5 rounded-full shadow-sm transition-all inline-flex items-center gap-2">
            <i class="fas fa-arrow-left text-sm"></i> Back to Products
        </a>
    </div>

    {{-- Error Alert --}}
    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-4">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle mt-0.5 mr-3"></i>
                <div>
                    <strong class="font-semibold">Error!</strong> Please check the following:
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Success Alert --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Edit Form Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100">
            <h6 class="font-bold text-blue-600">
                <i class="fas fa-edit mr-2"></i>Edit Product Information
            </h6>
        </div>
        <div class="p-5">
            <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data" id="editForm">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Left Column --}}
                    <div>
                        {{-- Product Name --}}
                        <div class="mb-4">
                            <label for="nama_produk" class="font-semibold text-gray-700 block mb-2">Product Name <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_produk') border-red-500 @enderror" 
                                id="nama_produk" name="nama_produk" 
                                value="{{ old('nama_produk', $product->nama_produk) }}" 
                                required>
                            @error('nama_produk')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Price --}}
                        <div class="mb-4">
                            <label for="harga" class="font-semibold text-gray-700 block mb-2">Price <span class="text-red-500">*</span></label>
                            <div class="flex items-center">
                                <span class="bg-blue-500 text-white px-3 py-2 rounded-l-xl">Rp</span>
                                <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-r-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('harga') border-red-500 @enderror" 
                                    id="harga" name="harga" 
                                    value="{{ old('harga', number_format($product->harga, 0, ',', '.')) }}" required>
                            </div>
                            @error('harga')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div>
                        {{-- Current Stock --}}
<form action="{{ route('products.update', $product->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <!-- input fields di sini -->
    <div class="mb-4">
        <label for="stok" class="font-semibold text-gray-700 block mb-2">
            Current Stock <span class="text-red-500">*</span>
        </label>
        <input type="number" name="stok" id="stok" value="{{ old('stok', $product->stok) }}" 
               class="w-full px-3 py-2 border border-gray-300 rounded-xl"
               min="0" required>
    </div>
    
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl">Update</button>
</form>
                        @error('stok')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <small class="text-gray-400 text-xs mt-1">Enter the current stock quantity (minimum 0)</small>
                    </div>

                        {{-- Product Image --}}
                        <div class="mb-4">
                            <label for="img" class="font-semibold text-gray-700 block mb-2">Product Image</label>
                            <div class="relative">
                                <input type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                                    id="img" name="img" accept="image/jpeg,image/png,image/jpg">
                                <div class="bg-gray-50 border border-gray-300 rounded-xl px-4 py-2 text-gray-500 text-sm cursor-pointer hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-upload mr-2"></i> Choose new image...
                                </div>
                            </div>
                            @error('img')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <small class="text-gray-400 text-xs mt-1 block">Leave empty to keep current image. Max size: 2MB</small>
                        </div>

                        {{-- Current Image Preview --}}
                        @if($product->img)
                            <div class="mt-3">
                                <label class="font-semibold text-gray-700 block mb-2">Current Image</label>
                                <div class="border border-gray-200 rounded-xl p-3 inline-block">
                                    @php
                                        $imageUrl = null;
                                        if (file_exists(public_path('storage/' . $product->img))) {
                                            $imageUrl = asset('storage/' . $product->img);
                                        } elseif (file_exists(storage_path('app/public/' . $product->img))) {
                                            $imageUrl = asset('storage/' . $product->img);
                                        } elseif (file_exists(public_path('storage/products/' . $product->img))) {
                                            $imageUrl = asset('storage/products/' . $product->img);
                                        } else {
                                            $imageUrl = asset('storage/' . $product->img);
                                        }
                                    @endphp
                                    <img src="{{ $imageUrl }}" 
                                        alt="{{ $product->nama_produk }}" 
                                        class="rounded-xl max-h-32 object-cover"
                                        onerror="this.src='https://via.placeholder.com/150?text=Image+Not+Found'">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <hr class="my-6 border-gray-200">

                {{-- Action Buttons --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('products.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-5 rounded-full transition-all inline-flex items-center gap-2">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-5 rounded-full transition-all inline-flex items-center gap-2">
                        <i class="fas fa-save"></i> Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Format harga
    const priceInput = document.getElementById('harga');
    
    if (priceInput) {
        priceInput.addEventListener('input', function(e) {
            let value = this.value.replace(/[^\d]/g, '');
            if (value) {
                value = parseInt(value, 10).toLocaleString('id-ID');
                this.value = value;
            }
        });
    }

    // Update label file (hidden file input handler)
    document.getElementById('img').addEventListener('change', function() {
        const fileName = this.files[0]?.name || 'Choose new image...';
        const label = this.nextElementSibling;
        if (label) {
            label.innerHTML = `<i class="fas fa-upload mr-2"></i> ${fileName}`;
        }
    });

    // Submit form
    document.getElementById('editForm').addEventListener('submit', function(e) {
        if (priceInput) {
            const numericValue = priceInput.value.replace(/\./g, '');
            priceInput.value = numericValue;
        }
        document.addEventListener('DOMContentLoaded', function() {
    const stokInput = document.getElementById('stok');
    const decrementBtn = document.getElementById('decrementStock');
    const incrementBtn = document.getElementById('incrementStock');
    
    if (decrementBtn && incrementBtn && stokInput) {
        decrementBtn.addEventListener('click', function() {
            let currentValue = parseInt(stokInput.value) || 0;
            if (currentValue > 0) {
                stokInput.value = currentValue - 1;
            }
        });
        
        incrementBtn.addEventListener('click', function() {
            let currentValue = parseInt(stokInput.value) || 0;
            stokInput.value = currentValue + 1;
        });
        
        // Prevent manual input below 0
        stokInput.addEventListener('change', function() {
            if (this.value < 0) this.value = 0;
        });
    }
});
    });
</script>
@endpush
@endsection     