@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="px-4 py-4">
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pembayaran</h1>
            <p class="text-sm text-gray-500 mt-1">Detail transaksi dan invoice</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5">
            {{-- Invoice Header --}}
            <div class="flex flex-wrap justify-between items-start gap-4 mb-6 pb-4 border-b border-gray-100">
                <div class="flex-1">
                    @if(isset($member) && $member)
                    <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-xl">
                        <div class="w-12 h-12 rounded-xl bg-blue-500 flex items-center justify-center">
                            <i class="fas fa-user-tag text-white text-xl"></i>
                        </div>
                        <div>
                            <h6 class="font-bold text-gray-800 mb-1">{{ $member->phone_number }}</h6>
                            <div class="flex flex-wrap gap-x-3 gap-y-1 text-xs text-gray-500">
                                <span><i class="far fa-calendar-alt mr-1"></i> Member sejak: {{ \Carbon\Carbon::parse($member->member_since)->translatedFormat('d F Y') }}</span>
                                <span><i class="fas fa-star mr-1 text-yellow-500"></i> Total Poin: {{ number_format($member->points, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="text-right">
                    <div class="inline-block">
                        <div class="bg-gray-100 px-4 py-2 rounded-xl inline-block mb-1">
                            <span class="font-mono font-bold text-gray-800 text-lg">#{{ $invoice_number }}</span>
                        </div>
                        <p class="text-gray-500 text-sm mt-1">
                            <i class="far fa-calendar-alt mr-1"></i> {{ date('d F Y') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Products Table --}}
            <div class="mb-6">
                <h6 class="font-semibold text-gray-700 mb-3">
                    <i class="fas fa-box text-blue-500 mr-2"></i>Detail Produk
                </h6>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 rounded-xl">
                            <tr class="border-b border-gray-100">
                                <th class="text-left py-3 px-3 text-gray-500 text-xs font-semibold uppercase tracking-wide">Produk</th>
                                <th class="text-center py-3 px-3 text-gray-500 text-xs font-semibold uppercase tracking-wide w-32">Harga Satuan</th>
                                <th class="text-center py-3 px-3 text-gray-500 text-xs font-semibold uppercase tracking-wide w-20">Jumlah</th>
                                <th class="text-right py-3 px-3 text-gray-500 text-xs font-semibold uppercase tracking-wide w-36">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($selectedProducts as $product)
                            <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                                <td class="py-3 px-3">
                                    <span class="font-medium text-gray-800">{{ $product['name'] }}</span>
                                </table>
                                <td class="text-center py-3 px-3 text-gray-600">Rp {{ number_format($product['price'], 0, ',', '.') }}</td>
                                <td class="text-center py-3 px-3 font-medium text-gray-600">{{ $product['quantity'] }}</td>
                                <td class="text-right py-3 px-3 font-semibold text-gray-800">Rp {{ number_format($product['subtotal'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Payment Summary --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="flex items-center gap-2 text-gray-500 text-xs font-semibold uppercase tracking-wide mb-2">
                        <i class="fas fa-star text-yellow-500"></i>
                        <span>Poin Digunakan</span>
                    </div>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($points_used ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="flex items-center gap-2 text-gray-500 text-xs font-semibold uppercase tracking-wide mb-2">
                        <i class="fas fa-user-circle"></i>
                        <span>Kasir</span>
                    </div>
                    <p class="text-xl font-bold text-gray-800">{{ $pembelian->dibuat_oleh }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="flex items-center gap-2 text-gray-500 text-xs font-semibold uppercase tracking-wide mb-2">
                        <i class="fas fa-money-bill-wave text-green-500"></i>
                        <span>Kembalian</span>
                    </div>
                    <p class="text-xl font-bold text-green-600">Rp {{ number_format($kembalian, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Total Payment Card --}}
            <div class="flex justify-end mb-6">
                <div class="w-full md:w-96 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-5 shadow-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-white/80 text-xs uppercase tracking-wide mb-1">Total Pembayaran</p>
                            @if($discount_from_points > 0)
                                <p class="text-white/60 text-sm line-through mb-1">Rp {{ number_format($total, 0, ',', '.') }}</p>
                            @endif
                            <p class="text-white text-2xl font-bold">Rp {{ number_format($final_total, 0, ',', '.') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-receipt text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('pembelian.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2.5 px-5 rounded-xl transition-all inline-flex items-center gap-2">
                    <i class="fas fa-arrow-left text-sm"></i> Kembali ke Daftar
                </a>
                <a href="{{ route('pembelian.export_pdf', $pembelian->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2.5 px-5 rounded-xl transition-all inline-flex items-center gap-2 shadow-sm hover:shadow-md">
                    <i class="fas fa-file-pdf text-sm"></i> Unduh Invoice
                </a>
            </div>
        </div>
    </div>
</div>
@endsection