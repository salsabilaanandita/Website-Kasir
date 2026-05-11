@extends('layouts.app')
@section('title', 'Products')

@section('content')
<div class="px-4 py-4">
    {{-- Header Section --}}
    <div class="flex flex-wrap items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Product List</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your inventory and products</p>
        </div>
        @if(Auth::user()->role == 'admin')
            <a href="{{ route('products.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-5 rounded-full shadow-sm transition-all inline-flex items-center gap-2">
                <i class="fas fa-plus text-sm"></i> Add Product
            </a>
        @endif
    </div>

    {{-- Products Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 flex flex-wrap items-center justify-between">
            <h6 class="font-bold text-blue-600">
                <i class="fas fa-box mr-2"></i>Products Data
            </h6>
            <span class="bg-gray-100 text-gray-500 text-xs font-medium px-3 py-1.5 rounded-full">
                <i class="fas fa-database mr-1 text-xs"></i> Total: {{ $products->count() }} products
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr class="text-left">
                        <th class="py-3 px-4 text-gray-500 text-xs font-semibold uppercase tracking-wide">#</th>
                        <th class="py-3 px-4 text-gray-500 text-xs font-semibold uppercase tracking-wide">Image</th>
                        <th class="py-3 px-4 text-gray-500 text-xs font-semibold uppercase tracking-wide">Product Name</th>
                        <th class="py-3 px-4 text-gray-500 text-xs font-semibold uppercase tracking-wide">Price</th>
                        <th class="py-3 px-4 text-gray-500 text-xs font-semibold uppercase tracking-wide">Stock</th>
                        @if(Auth::user()->role == 'admin')
                            <th class="py-3 px-4 text-center text-gray-500 text-xs font-semibold uppercase tracking-wide">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="border-b border-gray-100 hover:bg-gray-100 transition-colors duration-150">
                        <td class="py-3 px-4">
                            <span class="font-semibold text-blue-500 text-sm">{{ $loop->iteration }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100 border border-gray-100 flex items-center justify-center">
                                @if($product->img)
                                    {{-- Hapus 'products/' di dalam asset kalau di database lo isinya udah 'products/namafile.png' --}}
                                    <img src="{{ asset('storage/' . $product->img) }}" 
                                        alt="{{ $product->nama_produk }}" 
                                        class="w-full h-full object-cover"
                                        onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($product->nama_produk) }}&background=EBF4FF&color=7F9CF5';">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                        <i class="fas fa-image text-xl"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div>
                                <span class="font-semibold text-gray-800">{{ $product->nama_produk }}</span>
                                <p class="text-gray-400 text-xs mt-0.5">ID: {{ $product->id }}</p>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="bg-green-100 text-green-700 font-semibold text-sm px-3 py-1.5 rounded-full">
                                Rp {{ number_format($product->harga, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            @if($product->stok < 10)
                                <span class="inline-flex items-center gap-1 bg-red-100 text-red-600 text-xs font-medium px-3 py-1.5 rounded-full">
                                    <i class="fas fa-exclamation-triangle text-xs"></i> {{ $product->stok }} left
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-600 text-xs font-medium px-3 py-1.5 rounded-full">
                                    {{ $product->stok }} in stock
                                </span>
                            @endif
                        </td>
                        @if(Auth::user()->role == 'admin')
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('products.edit', $product->id) }}" class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white flex items-center justify-center transition-all duration-200" title="Edit Product">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this product?')" class="w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all duration-200" title="Delete Product">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::user()->role == 'admin' ? '6' : '5' }}" class="text-center py-12">
                            <div class="text-center">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-box-open text-gray-400 text-3xl"></i>
                                </div>
                                <h5 class="text-gray-500 font-medium mb-1">No products available</h5>
                                <p class="text-gray-400 text-sm">Get started by adding your first product</p>
                                @if(Auth::user()->role == 'admin')
                                    <a href="{{ route('products.create') }}" class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white font-medium px-5 py-2 rounded-full mt-3 transition-all">
                                        <i class="fas fa-plus text-sm"></i> Add First Product
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Stock Update Modal --}}
@foreach($products as $product)
<div class="modal fade" id="stockModal{{ $product->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-2xl overflow-hidden">
            <div class="bg-blue-600 text-white px-5 py-3 flex justify-between items-center">
                <h5 class="font-semibold m-0"><i class="fas fa-box-open mr-2"></i> Update Stock</h5>
                <button type="button" class="text-white hover:text-gray-200 text-2xl leading-none" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('products.update-stock', $product->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="p-5">
                    <div class="text-center mb-4">
                        <div class="w-20 h-20 rounded-xl overflow-hidden mx-auto mb-3 shadow-sm bg-gray-50 flex items-center justify-center">
                            @if($product->img)
                                <img src="{{ asset('storage/products/' . $product->img) }}" 
                                     alt="{{ $product->nama_produk }}" 
                                     class="w-full h-full object-cover"
                                     onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($product->nama_produk) }}&background=EBF4FF&color=7F9CF5';">
                            @else
                                <i class="fas fa-image text-gray-400 text-3xl"></i>
                            @endif
                        </div>
                        <h5 class="font-bold text-gray-800 mb-1">{{ $product->nama_produk }}</h5>
                        <p class="text-gray-400 text-xs">Product ID: {{ $product->id }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Current Stock</span>
                            <span class="font-bold text-gray-800">{{ $product->stok }} units</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold text-gray-700 block mb-2">New Stock Amount <span class="text-red-500">*</span></label>
                        <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-blue-500">
                            <div class="bg-gray-100 px-3 py-2 text-gray-500">
                                <i class="fas fa-edit"></i>
                            </div>
                            <input type="number" name="stok" value="{{ $product->stok }}" min="0" required class="flex-1 px-3 py-2 focus:outline-none text-center font-bold">
                            <div class="bg-gray-100 px-3 py-2 text-gray-500">units</div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3 flex justify-end gap-2">
                    <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium px-5 py-2 rounded-full transition-all" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium px-5 py-2 rounded-full transition-all">Update Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection