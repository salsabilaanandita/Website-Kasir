@extends('layouts.app')
@section('title', 'Add Product')

@section('content')
<div class="px-4 py-4">
    {{-- Header Section --}}
    <div class="flex flex-wrap items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Add New Product</h1>
            <p class="text-sm text-gray-500 mt-1">Create a new product for your inventory</p>
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

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100">
            <h6 class="font-bold text-blue-600">
                <i class="fas fa-plus-circle mr-2"></i>Product Information
            </h6>
        </div>
        <div class="p-5">
            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" id="productForm">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Left Column --}}
                    <div>
                        {{-- Product Name --}}
                        <div class="mb-4">
                            <label for="nama_produk" class="font-semibold text-gray-700 block mb-2">Product Name <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_produk') border-red-500 @enderror" 
                                id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" 
                                required minlength="3" maxlength="255"
                                placeholder="Enter product name">
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
                                    id="harga" name="harga" value="{{ old('harga') }}" 
                                    placeholder="0" required>
                            </div>
                            @error('harga')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div>
                        {{-- Initial Stock --}}
                        <div class="mb-4">
                            <label for="stok" class="font-semibold text-gray-700 block mb-2">Initial Stock <span class="text-red-500">*</span></label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('stok') border-red-500 @enderror" 
                                id="stok" name="stok" value="{{ old('stok', 0) }}" 
                                min="0" required placeholder="Enter initial stock">
                            @error('stok')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Product Image --}}
                        <div class="mb-4">
                            <label for="img" class="font-semibold text-gray-700 block mb-2">Product Image <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                                    id="img" name="img" accept="image/jpeg,image/png,image/jpg" required>
                                <div class="bg-gray-50 border border-gray-300 rounded-xl px-4 py-2 text-gray-500 text-sm cursor-pointer hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-cloud-upload-alt mr-2"></i> Choose file...
                                </div>
                            </div>
                            @error('img')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <small class="text-gray-400 text-xs mt-1 block">Accepted formats: JPG, JPEG, PNG. Max size: 2MB</small>
                        </div>
                    </div>
                </div>

                <hr class="my-6 border-gray-200">

                {{-- Action Buttons --}}
                <div class="flex justify-end gap-3">
                    <button type="reset" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-5 rounded-full transition-all inline-flex items-center gap-2">
                        <i class="fas fa-undo-alt"></i> Reset
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-5 rounded-full transition-all inline-flex items-center gap-2">
                        <i class="fas fa-plus-circle"></i> Add Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Format harga dengan separator ribuan
    const priceInput = document.getElementById('harga');
    
    priceInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^\d]/g, '');
        if (value.length > 0) {
            value = parseInt(value, 10).toLocaleString('id-ID');
        }
        e.target.value = value;
    });

    // Validasi submit form
    document.getElementById('productForm').addEventListener('submit', function(e) {
        const priceInput = document.getElementById('harga');
        const numericValue = priceInput.value.replace(/\./g, '');
        
        if (!numericValue || isNaN(numericValue) || numericValue <= 0) {
            e.preventDefault();
            alert('Please enter a valid price');
            priceInput.focus();
            return;
        }
        priceInput.value = numericValue;
        
        // Validasi file gambar
        const fileInput = document.getElementById('img');
        if (!fileInput.files.length) {
            e.preventDefault();
            alert('Please select a product image');
            fileInput.focus();
            return;
        }
    });

    // Update label file upload
    document.getElementById('img').addEventListener('change', function() {
        const fileName = this.files[0]?.name || 'Choose file...';
        const label = this.nextElementSibling;
        label.innerHTML = `<i class="fas fa-cloud-upload-alt mr-2"></i> ${fileName}`;
    });
</script>
@endpush
@endsection