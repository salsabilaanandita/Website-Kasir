@extends('layouts.app')

@section('title', 'Pembelian')

@section('content')
<div class="px-4 py-4">
    {{-- Header Section --}}
    <div class="flex flex-wrap items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Pembelian</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola semua transaksi penjualan</p>
        </div>
        
        @if(Auth::user()->role === 'staff')
            <a href="{{ route('pembelian.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-xl shadow-sm transition-all inline-flex items-center gap-2">
                <i class="fas fa-plus text-sm"></i> Add
            </a>
        @endif
    </div>

    {{-- Main Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Card Header --}}
        <div class="px-5 py-3 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <i class="fas fa-shopping-cart text-blue-500"></i>
                <h6 class="font-semibold text-gray-700">Data Pembelian</h6>
            </div>
            <a href="{{ route('pembelian.export') }}" class="bg-green-500 hover:bg-green-600 text-white text-sm font-medium py-1.5 px-4 rounded-lg transition-all inline-flex items-center gap-1">
                <i class="fas fa-file-excel text-sm"></i> Export Excel
            </a>
        </div>

        {{-- Card Body --}}
        <div class="p-4">
            {{-- Search and Per Page --}}
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="search" class="pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64 text-sm" placeholder="Cari pembelian..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-gray-500 text-sm">Tampilkan</span>
                    <select id="per-page" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-gray-500 text-sm">entri</span>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="py-3 px-3 text-gray-500 text-xs font-semibold uppercase tracking-wide text-center w-16">#</th>
                            <th class="py-3 px-3 text-gray-500 text-xs font-semibold uppercase tracking-wide">Pelanggan</th>
                            <th class="py-3 px-3 text-gray-500 text-xs font-semibold uppercase tracking-wide">Tanggal</th>
                            <th class="py-3 px-3 text-gray-500 text-xs font-semibold uppercase tracking-wide">Total</th>
                            <th class="py-3 px-3 text-gray-500 text-xs font-semibold uppercase tracking-wide">Kasir</th>
                            <th class="py-3 px-3 text-gray-500 text-xs font-semibold uppercase tracking-wide text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembelians as $index => $pembelian)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
                            <td class="py-3 px-3 text-center text-gray-500 text-sm">
                                {{ ($pembelians->currentPage() - 1) * $pembelians->perPage() + $index + 1 }}
                            </td>
                            <td class="py-3 px-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-user text-blue-500 text-xs"></i>
                                    </div>
                                    <span class="text-gray-700 text-sm font-medium">{{ $pembelian->customer_name }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <div class="text-gray-500 text-xs">
                                    <i class="far fa-calendar-alt text-gray-400 mr-1"></i>
                                    {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d/m/Y') }}
                                    <span class="mx-1">•</span>
                                    <i class="far fa-clock text-gray-400 mr-1"></i>
                                    {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('H:i') }}
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <span class="inline-block bg-green-100 text-green-700 font-semibold text-xs px-3 py-1.5 rounded-full">
                                    Rp {{ number_format($pembelian->grand_total, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="py-3 px-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-user-circle text-gray-500 text-xs"></i>
                                    </div>
                                    <span class="text-gray-500 text-sm">{{ $pembelian->dibuat_oleh ?? $pembelian->user->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" onclick="showDetail({{ $pembelian->id }})" class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-all duration-200 cursor-pointer" title="Detail">
                                        <i class="fas fa-eye text-sm"></i>
                                    </button>
                                    <a href="{{ route('pembelian.export_pdf', $pembelian->id) }}" class="w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all duration-200" title="Download PDF">
                                        <i class="fas fa-file-pdf text-sm"></i>
                                    </a>
                                </div>
                            <td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                        <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">Belum ada data pembelian</p>
                                    <p class="text-gray-400 text-sm mt-1">Belum ada transaksi yang tercatat</p>
                                    @if(Auth::user()->role == 'staff' || Auth::user()->role == 'staf')
                                        <a href="{{ route('pembelian.create') }}" class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg mt-3 transition-all">
                                            <i class="fas fa-plus"></i> Tambah Pembelian
                                        </a>
                                    @endif
                                </div>
                            </td>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Info --}}
            <div class="flex flex-wrap items-center justify-between gap-3 mt-4 pt-3 border-t border-gray-100">
                <div class="text-gray-500 text-sm">
                    Menampilkan <span class="font-medium">{{ $pembelians->firstItem() ?? 0 }}</span> sampai <span class="font-medium">{{ $pembelians->lastItem() ?? 0 }}</span> dari <span class="font-medium">{{ $pembelians->total() }}</span> data
                </div>
                <div>
                    {{ $pembelians->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail --}}
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-2xl overflow-hidden">
            <div class="bg-blue-600 px-5 py-3 flex justify-between items-center">
                <h5 class="font-semibold text-white m-0">
                    <i class="fas fa-receipt mr-2"></i> Detail Pembelian
                </h5>
                <button type="button" class="text-white hover:text-gray-200 text-2xl leading-none" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0" id="detailModalBody">
                <div class="text-center py-8">
                    <div class="inline-block w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-gray-500 mt-3">Memuat data detail pembelian...</p>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 flex justify-end">
                <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-5 rounded-lg transition-all" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showDetail(id) {
    const modalBody = document.getElementById('detailModalBody');
    
    // Tampilkan loading
    if (modalBody) {
        modalBody.innerHTML = `
            <div class="text-center py-8">
                <div class="inline-block w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                <p class="text-gray-500 mt-3">Memuat data detail pembelian...</p>
            </div>
        `;
    }
    
    // Tampilkan modal
    $('#detailModal').modal('show');
    
    // Fetch data detail (HTML dari server)
    fetch(`/pembelian/detail/${id}`)
        .then(response => response.text())
        .then(html => {
            if (modalBody) {
                modalBody.innerHTML = html;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (modalBody) {
                modalBody.innerHTML = `
                    <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg m-4">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Gagal memuat data detail. Silakan coba lagi.</span>
                        </div>
                    </div>
                `;
            }
        });
}

function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const perPageSelect = document.getElementById('per-page');
    let typingTimer;

    function updatePage() {
        const search = searchInput.value;
        const perPage = perPageSelect.value;
        const url = new URL(window.location.href);
        url.searchParams.set('search', search);
        url.searchParams.set('per_page', perPage);
        window.location.href = url.toString();
    }

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(updatePage, 500);
        });
    }

    if (perPageSelect) {
        perPageSelect.addEventListener('change', updatePage);
    }
});
</script>
@endpush

@push('styles')
<style>
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .animate-spin {
        animation: spin 0.8s linear infinite;
    }
    .rounded-2xl {
        border-radius: 1rem;
    }
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
    .duration-200 {
        transition-duration: 200ms;
    }
    .hover\:bg-gray-50:hover {
        background-color: #f9fafb;
    }
    
    /* Modal detail styles */
    .modal-detail-container {
        max-height: 70vh;
        overflow-y: auto;
    }
    
    /* Custom scrollbar */
    .modal-detail-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .modal-detail-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .modal-detail-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    .modal-detail-container::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endpush
@endsection