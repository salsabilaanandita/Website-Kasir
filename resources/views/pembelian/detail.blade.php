<div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0,0,0,0.6)">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-5xl mx-auto">
            {{-- Header --}}
            <div class="bg-blue-600 rounded-t-2xl px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <i class="fas fa-receipt text-white text-xl"></i>
                    <h5 class="text-white font-semibold text-lg m-0">Detail Penjualan #{{ $pembelian->id }}</h5>
                </div>
                <a href="{{ route('pembelian.index') }}" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </a>
            </div>

            {{-- Body --}}
            <div class="p-6">
                {{-- Info Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    {{-- Informasi Pelanggan --}}
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-user-circle text-blue-500 text-lg"></i>
                            <h6 class="font-semibold text-gray-800">Informasi Pelanggan</h6>
                        </div>
                        
                        @php
                            $member = \App\Models\Member::where('name', $pembelian->customer_name)->first();
                        @endphp
                        
                        @if($member)
                            <div class="mb-3">
                                <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">
                                    <i class="fas fa-star text-xs"></i> Member
                                </span>
                            </div>
                            <div class="space-y-2">
                                <div>
                                    <p class="text-gray-400 text-xs">Nama Pelanggan</p>
                                    <p class="font-semibold text-gray-800">{{ $pembelian->customer_name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-xs">Nomor Telepon</p>
                                    <p class="font-semibold text-gray-800">{{ $member->phone_number }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-xs">Total Poin</p>
                                    <p class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 text-xs font-semibold px-2 py-1 rounded-full">
                                        <i class="fas fa-award"></i> {{ $member->points }} Poin
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-xs">Member Sejak</p>
                                    <p class="text-blue-600 text-sm">{{ \Carbon\Carbon::parse($member->member_since)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') }}</p>
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 text-xs font-semibold px-2 py-1 rounded-full">
                                    <i class="fas fa-user text-xs"></i> Non-Member
                                </span>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs">Nama Pelanggan</p>
                                <p class="font-semibold text-gray-800">{{ $pembelian->customer_name ?? '-' }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Informasi Transaksi --}}
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-info-circle text-blue-500 text-lg"></i>
                            <h6 class="font-semibold text-gray-800">Informasi Transaksi</h6>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <p class="text-gray-400 text-xs">Tanggal Transaksi</p>
                                <p class="font-semibold text-gray-800">{{ $pembelian->created_at->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs">Waktu Transaksi</p>
                                <p class="font-semibold text-gray-800">{{ $pembelian->created_at->format('H:i') }} WIB</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs">Kasir</p>
                                <p class="font-semibold text-gray-800">
                                    <i class="fas fa-user-tie text-gray-400 mr-1"></i>
                                    {{ $pembelian->dibuat_oleh }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Detail Produk --}}
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-shopping-basket text-blue-500"></i>
                            <h6 class="font-semibold text-gray-800">Detail Pembelian</h6>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr class="border-b border-gray-100">
                                    <th class="text-left py-3 px-4 text-gray-500 text-xs font-semibold uppercase tracking-wide">Produk</th>
                                    <th class="text-center py-3 px-4 text-gray-500 text-xs font-semibold uppercase tracking-wide w-24">Qty</th>
                                    <th class="text-right py-3 px-4 text-gray-500 text-xs font-semibold uppercase tracking-wide w-36">Harga Satuan</th>
                                    <th class="text-right py-3 px-4 text-gray-500 text-xs font-semibold uppercase tracking-wide w-36">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pembelian->details as $detail)
                                <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4 text-gray-700">{{ $detail->product->nama_produk }}</td>
                                    <td class="py-3 px-4 text-center text-gray-600">{{ $detail->quantity }}</td>
                                    <td class="py-3 px-4 text-right text-gray-600">Rp {{ number_format($detail->product->harga, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right font-semibold text-gray-800">Rp {{ number_format($detail->total_price, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 border-t border-gray-100">
                                <tr>
                                    <td colspan="3" class="py-3 px-4 text-right font-semibold text-gray-700">Total Pembelian</td>
                                    <td class="py-3 px-4 text-right">
                                        <span class="text-blue-600 font-bold text-lg">Rp {{ number_format($pembelian->grand_total, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="bg-gray-50 rounded-b-2xl px-6 py-3 flex justify-end border-t border-gray-100">
                <a href="{{ route('pembelian.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-5 rounded-lg transition-all inline-flex items-center gap-2">
                    <i class="fas fa-arrow-left text-sm"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>