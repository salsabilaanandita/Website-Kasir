@extends('layouts.app')
@section('title', 'Konfirmasi Penjualan')

@section('content')
<div class="px-4 py-4">
    {{-- Header Section --}}
    <div class="flex flex-wrap items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Konfirmasi Penjualan</h1>
            <p class="text-sm text-gray-500 mt-1">Review pesanan dan lakukan pembayaran</p>
        </div>
        <nav class="flex items-center gap-2 text-sm">
            <a href="{{ route('pembelian.index') }}" class="text-gray-500 hover:text-blue-500 transition-colors">Penjualan</a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-blue-600 font-medium">Konfirmasi</span>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column - Product List --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h6 class="font-bold text-gray-800">
                        <i class="fas fa-shopping-cart text-blue-500 mr-2"></i>Produk yang dipilih
                    </h6>
                </div>
                <div class="p-4">
                    <div class="space-y-2">
                        @foreach($selectedProducts as $product)
                        <div class="bg-gray-50 rounded-xl p-3 hover:bg-gray-100 transition-colors">
                            <div class="flex justify-between items-center">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">{{ $product['name'] }}</p>
                                    <p class="text-gray-500 text-sm mt-0.5">
                                        Rp {{ number_format($product['price'], 0, ',', '.') }} × {{ $product['quantity'] }}
                                    </p>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">Rp {{ number_format($product['subtotal'], 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Total Summary --}}
                    <div class="mt-4 bg-blue-50 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-700">Total Belanja</span>
                            <span class="font-bold text-blue-600 text-xl">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        @if($points_earned > 0)
                        <div class="flex justify-between items-center mt-2 pt-2 border-t border-blue-100">
                            <span class="font-semibold text-green-600">
                                <i class="fas fa-star mr-1"></i> Poin yang didapatkan
                            </span>
                            <span class="font-bold text-green-600">
                                {{ number_format($points_earned, 0, ',', '.') }} Poin
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column - Payment --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h6 class="font-bold text-gray-800">
                        <i class="fas fa-credit-card text-blue-500 mr-2"></i>Pembayaran
                    </h6>
                </div>
                <div class="p-5">
                    {{-- Member Status --}}
                    <div class="mb-4">
                        <label class="font-semibold text-gray-700 block mb-2">Member Status</label>
                        <select id="memberType" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="non_member">Bukan Member</option>
                            <option value="member">Member</option>
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Dengan member, anda bisa mendapatkan poin</p>
                    </div>

                    {{-- Phone Field (Hidden by default) --}}
                    <div id="memberFields" class="mb-4 hidden">
                        <label class="font-semibold text-gray-700 block mb-2">Nomor Telepon</label>
                        <div class="relative">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-phone text-sm"></i>
                            </div>
                            <input type="text" name="phone_number" class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="08123456789">
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Input nomor telepon untuk mendapatkan poin</p>
                    </div>

                    {{-- Total Bayar --}}
                    <div class="mb-5">
                        <label class="font-semibold text-gray-700 block mb-2">Total Bayar</label>
                        <div class="relative">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-money-bill-wave text-sm"></i>
                            </div>
                            <input type="text" name="total_bayar" id="total_bayar" class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="Rp 0" oninput="validatePayment(this.value)">
                        </div>
                        <div id="payment_error" class="text-red-500 text-xs mt-1 hidden flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> Jumlah bayar kurang dari total belanja
                        </div>
                    </div>

                    {{-- Hidden Form --}}
                    <form action="{{ route('pembelian.member-info') }}" method="POST" id="payment_form">
                        @csrf
                        <input type="hidden" name="products" value="{{ json_encode($selectedProducts) }}">
                        <input type="hidden" name="total_amount" value="{{ $total }}">
                        <input type="hidden" name="member_type" id="hidden_member_type">
                        <input type="hidden" name="phone_number" id="hidden_phone_number">
                        <input type="hidden" name="total_bayar" id="hidden_total_bayar">
                        
                        <button type="submit" id="submit_btn" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <i class="fas fa-arrow-right"></i> Selanjutnya
                        </button>
                    </form>

                    {{-- Payment Info --}}
                    <div class="mt-4 p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center gap-2 text-gray-500 text-xs">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            <span>Pastikan jumlah bayar sesuai dengan total belanja</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Update form action based on member type
    document.addEventListener('DOMContentLoaded', function() {
        const memberType = document.getElementById('memberType');
        updateFormAction(memberType.value);
    });

    document.getElementById('memberType').addEventListener('change', function() {
        const memberFields = document.getElementById('memberFields');
        memberFields.classList.toggle('hidden', this.value === 'non_member');
        updateFormAction(this.value);
    });

    function updateFormAction(memberType) {
        const paymentForm = document.getElementById('payment_form');
        if (memberType === 'non_member') {
            paymentForm.action = "{{ route('pembelian.pembayaran') }}";
        } else {
            paymentForm.action = "{{ route('pembelian.member-info') }}";
        }
    }

    function formatRupiah(angka) {
        const number_string = angka.toString().replace(/[^,\d]/g, '');
        const split = number_string.split(',');
        const sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            const separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return 'Rp ' + rupiah;
    }

    function validatePayment(value) {
        const cleanValue = parseInt(value.replace(/[^\d]/g, '')) || 0;
        const totalAmount = {{ $total }};
        const errorElement = document.getElementById('payment_error');
        const submitButton = document.getElementById('submit_btn');
        const inputElement = document.getElementById('total_bayar');
        
        inputElement.value = formatRupiah(value);
        document.getElementById('hidden_total_bayar').value = cleanValue;

        if (cleanValue < totalAmount) {
            errorElement.classList.remove('hidden');
            inputElement.classList.add('border-red-500', 'ring-red-500');
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            errorElement.classList.add('hidden');
            inputElement.classList.remove('border-red-500', 'ring-red-500');
            submitButton.disabled = false;
            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    document.getElementById('payment_form').addEventListener('submit', function(e) {
        e.preventDefault();
        const memberType = document.getElementById('memberType').value;
        const phoneNumber = document.querySelector('input[name="phone_number"]')?.value;
        const totalBayar = document.getElementById('total_bayar').value;

        document.getElementById('hidden_member_type').value = memberType;
        document.getElementById('hidden_phone_number').value = phoneNumber || '';
        document.getElementById('hidden_total_bayar').value = totalBayar.replace(/[^\d]/g, '');

        if (memberType === 'member' && !phoneNumber) {
            alert('Mohon isi nomor telepon untuk member');
            return;
        }

        if (!totalBayar || totalBayar === 'Rp 0') {
            alert('Mohon isi total bayar');
            return;
        }

        this.submit();
    });
</script>
@endpush