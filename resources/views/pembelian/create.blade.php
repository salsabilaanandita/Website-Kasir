@extends('layouts.app')
@section('title', 'Tambah Penjualan')

@section('content')
<div class="px-4 py-4 max-w-7xl mx-auto mb-32"> {{-- Tambah margin bottom biar nggak ketutup button bawah --}}
    <div class="flex flex-wrap items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Penjualan Baru</h1>
            <p class="text-sm text-gray-500 mt-1">Pilih produk dan tentukan jumlah belanja</p>
        </div>
        <a href="{{ route('pembelian.index') }}" class="bg-white border border-gray-200 text-gray-600 text-sm font-medium py-2.5 px-5 rounded-xl shadow-sm hover:bg-gray-50 transition-all inline-flex items-center gap-2">
            Kembali
        </a>
    </div>

    <form action="{{ route('pembelian.confirm') }}" method="POST" id="purchaseForm">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="product-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-price="{{ $product->harga }}">
                {{-- Gambar Produk - Path diperbaiki --}}
                <div class="relative bg-gray-50 p-4 flex justify-center items-center h-48">
                    @if($product->img)
                        {{-- SESUAIKAN: Pakai asset('storage/' . $product->img) --}}
                        <img src="{{ asset('storage/' . $product->img) }}" 
                             alt="{{ $product->nama_produk }}" 
                             class="h-32 w-32 object-cover rounded-xl shadow-sm"
                             onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($product->nama_produk) }}&background=EBF4FF&color=7F9CF5';">
                    @else
                        <div class="h-32 w-32 bg-gray-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-image text-gray-300 text-3xl"></i>
                        </div>
                    @endif
                    
                    <div class="absolute top-3 right-3">
                        <span class="px-2 py-1 text-xs rounded-full font-bold {{ $product->stok > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                            Stok: {{ $product->stok }}
                        </span>
                    </div>
                </div>
                
                <div class="p-4">
                    <h5 class="font-bold text-gray-800 text-base mb-1 truncate">{{ $product->nama_produk }}</h5>
                    <p class="text-xl font-black text-blue-600 mb-4">
                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                    </p>
                    
                    <div class="mb-4">
                        <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-2">Jumlah Beli</label>
                        <div class="flex items-center gap-2">
                            <button type="button" class="btn-minus w-10 h-10 rounded-xl bg-gray-100 hover:bg-red-500 hover:text-white text-gray-600 flex items-center justify-center transition-all">
                                <i class="fas fa-minus text-xs"></i>
                            </button>
                            
                            <input type="number" 
                                   name="quantities[{{ $product->id }}]" 
                                   value="0" 
                                   min="0" 
                                   max="{{ $product->stok }}"
                                   class="input-qty w-full text-center py-2 border border-gray-200 rounded-xl font-bold focus:ring-2 focus:ring-blue-500 outline-none"
                                   readonly>

                            <button type="button" class="btn-plus w-10 h-10 rounded-xl bg-gray-100 hover:bg-blue-500 hover:text-white text-gray-600 flex items-center justify-center transition-all">
                                <i class="fas fa-plus text-xs"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="pt-3 border-t border-dashed border-gray-100">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-400 font-medium">Subtotal</span>
                            <span class="product-subtotal font-bold text-gray-800">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </form>

    {{-- Bottom Bar --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-md border-t border-gray-100 shadow-[0_-10px_40px_rgba(0,0,0,0.05)] z-50">
        <div class="max-w-7xl mx-auto px-6 py-5 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Bayar</p>
                    <p class="text-2xl font-black text-gray-800" id="grandTotal">Rp 0</p>
                </div>
            </div>
            <button type="submit" form="purchaseForm" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-10 rounded-2xl shadow-lg shadow-blue-200 transition-all flex items-center gap-2 active:scale-95">
                Konfirmasi Pesanan <i class="fas fa-arrow-right text-sm"></i>
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.product-card');
    const grandTotalElement = document.getElementById('grandTotal');

    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    function calculateGrandTotal() {
        let total = 0;
        cards.forEach(card => {
            const price = parseInt(card.dataset.price);
            const qty = parseInt(card.querySelector('.input-qty').value);
            total += price * qty;
        });
        grandTotalElement.innerText = formatRupiah(total);
    }

    cards.forEach(card => {
        const btnMinus = card.querySelector('.btn-minus');
        const btnPlus = card.querySelector('.btn-plus');
        const inputQty = card.querySelector('.input-qty');
        const subtotalText = card.querySelector('.product-subtotal');
        const price = parseInt(card.dataset.price);
        const maxStock = parseInt(inputQty.getAttribute('max'));

        btnPlus.addEventListener('click', function() {
            let currentQty = parseInt(inputQty.value);
            if (currentQty < maxStock) {
                currentQty++;
                inputQty.value = currentQty;
                subtotalText.innerText = formatRupiah(currentQty * price);
                calculateGrandTotal();
            } else {
                alert('Stok tidak mencukupi!');
            }
        });

        btnMinus.addEventListener('click', function() {
            let currentQty = parseInt(inputQty.value);
            if (currentQty > 0) {
                currentQty--;
                inputQty.value = currentQty;
                subtotalText.innerText = formatRupiah(currentQty * price);
                calculateGrandTotal();
            }
        });
    });
});
</script>
@endsection