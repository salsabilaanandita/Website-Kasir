@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="px-4 py-4">
    {{-- Header Section --}}
    <div class="flex flex-wrap items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Dashboard Overview</h1>
            <p class="text-sm text-gray-400 mt-1">{{ now()->setTimezone('Asia/Jakarta')->format('d F Y') }}</p>
        </div>
        <div class="flex gap-3">
            <button onclick="refreshData()" class="bg-white border border-gray-200 hover:border-gray-300 text-gray-600 text-sm font-medium py-2.5 px-5 rounded-xl shadow-sm hover:shadow transition-all duration-200">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
            <button onclick="window.print()" class="bg-white border border-gray-200 hover:border-gray-300 text-gray-600 text-sm font-medium py-2.5 px-5 rounded-xl shadow-sm hover:shadow transition-all duration-200">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print
            </button>
        </div>
    </div>

    @if(Auth::user()->role == 'admin')
        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Sales Card -->
            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-6 border border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Total Sales</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalPembelian, 0, ',', '.') }}</p>
                        <p class="text-emerald-500 text-xs mt-3 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            Transactions
                        </p>
                    </div>
                    <div class="bg-blue-50 group-hover:bg-blue-100 transition-colors duration-300 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Members Card -->
            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-6 border border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Total Members</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalMember, 0, ',', '.') }}</p>
                        <p class="text-emerald-500 text-xs mt-3 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            Active customers
                        </p>
                    </div>
                    <div class="bg-emerald-50 group-hover:bg-emerald-100 transition-colors duration-300 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Products Card -->
            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-6 border border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Total Products</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalProduk, 0, ',', '.') }}</p>
                        <p class="text-cyan-500 text-xs mt-3 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Available items
                        </p>
                    </div>
                    <div class="bg-cyan-50 group-hover:bg-cyan-100 transition-colors duration-300 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Revenue Card -->
            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-6 border border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Total Revenue</p>
                        <p class="text-2xl font-bold text-gray-800 mt-2">Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}</p>
                        <p class="text-emerald-500 text-xs mt-3 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Overall earnings
                        </p>
                    </div>
                    <div class="bg-amber-50 group-hover:bg-amber-100 transition-colors duration-300 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Area Chart -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h6 class="font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                            </svg>
                            Sales Trend
                        </h6>
                        <p class="text-gray-400 text-xs mt-1">Last 7 days performance</p>
                    </div>
                    <span class="bg-gray-50 text-gray-500 text-xs px-3 py-1.5 rounded-full border border-gray-200">Last 7 days</span>
                </div>
                <div class="p-6" style="height: 320px;">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h6 class="font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                        </svg>
                        Product Distribution
                    </h6>
                    <p class="text-gray-400 text-xs mt-1">Top selling products</p>
                </div>
                <div class="p-6">
                    <div style="height: 220px;">
                        <canvas id="pieChart"></canvas>
                    </div>
                    <div class="mt-6 space-y-3">
                        @foreach($productSales->take(5) as $index => $product)
                            <div class="flex justify-between items-center group">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full" style="background: {{ $colors[$index % count($colors)] }}"></div>
                                    <span class="text-gray-600 text-sm">{{ $product->nama_produk }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-gray-700 text-sm font-medium">{{ $product->total_sold }} sold</span>
                                    <span class="bg-gray-50 text-gray-500 text-xs px-2 py-1 rounded-lg">
                                        {{ round(($product->total_sold / $productSales->sum('total_sold')) * 100, 1) }}%
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Transactions Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-wrap justify-between items-center gap-4">
                <div>
                    <h6 class="font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Recent Sales Transactions
                    </h6>
                    <p class="text-gray-400 text-xs mt-1">Latest 5 transactions history</p>
                </div>
                <a href="{{ route('pembelian.index') }}" class="text-blue-500 hover:text-blue-600 text-sm font-medium border border-blue-200 hover:bg-blue-50 px-4 py-2 rounded-xl transition-all duration-200 flex items-center gap-2">
                    View All 
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left py-4 px-6 text-gray-500 text-xs font-semibold uppercase tracking-wider">No</th>
                            <th class="text-left py-4 px-6 text-gray-500 text-xs font-semibold uppercase tracking-wider">Invoice</th>
                            <th class="text-left py-4 px-6 text-gray-500 text-xs font-semibold uppercase tracking-wider">Customer</th>
                            <th class="text-left py-4 px-6 text-gray-500 text-xs font-semibold uppercase tracking-wider">Date</th>
                            <th class="text-left py-4 px-6 text-gray-500 text-xs font-semibold uppercase tracking-wider">Amount</th>
                            <th class="text-left py-4 px-6 text-gray-500 text-xs font-semibold uppercase tracking-wider">Staff</th>
                            <th class="text-center py-4 px-6 text-gray-500 text-xs font-semibold uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPembelians ?? [] as $index => $pembelian)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors duration-200">
                            <td class="py-3 px-6 text-gray-600 text-sm">{{ $index + 1 }}</td>
                            <td class="py-3 px-6">
                                <span class="font-mono text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg flex items-center gap-2 w-fit">
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    {{ $pembelian->invoice_number ?? 'INV-' . str_pad($pembelian->id, 6, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="py-3 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-50 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700 text-sm font-medium">{{ $pembelian->customer_name }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <div class="text-gray-500 text-sm flex items-center gap-2">
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d/m/Y') }}
                                    <span class="text-gray-300">•</span>
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('H:i') }}
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <span class="bg-emerald-50 text-emerald-600 font-semibold text-sm px-3 py-1.5 rounded-full flex items-center gap-2 w-fit">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Rp {{ number_format($pembelian->grand_total, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="py-3 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-500 text-sm">{{ $pembelian->dibuat_oleh ?? $pembelian->user->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <span class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-600 text-xs font-medium px-3 py-1.5 rounded-full">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Completed
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-16">
                                <div class="text-center">
                                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 mb-1">No transactions yet</p>
                                    <p class="text-gray-400 text-sm">Belum ada data pembelian</p>
                                    <a href="{{ route('pembelian.create') }}" class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-medium px-5 py-2.5 rounded-full mt-4 transition-all duration-200 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Create Transaction
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @else
        {{-- Staff Dashboard --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            {{-- Welcome Card --}}
            <div class="lg:col-span-2 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-2xl font-bold">Halo, {{ Auth::user()->name }}!</h2>
                        <p class="text-blue-100 mt-2 text-sm">Selamat bekerja! Tetap semangat dan berikan pelayanan terbaik.</p>
                        <a href="{{ route('pembelian.create') }}" class="inline-flex items-center gap-2 bg-white text-blue-600 hover:bg-blue-50 px-5 py-2.5 rounded-xl text-sm font-semibold mt-4 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Transaksi Baru
                        </a>
                    </div>
                    <div class="hidden sm:block opacity-20">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Today's Performance Stats --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Transaksi Hari Ini</p>
                    <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold text-gray-800">{{ $todaySales ?? 0 }}</p>
                <p class="text-gray-400 text-xs mt-2">Total nota hari ini</p>
                <div class="mt-4 h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 rounded-full transition-all duration-500" style="width: {{ min(($todaySales ?? 0) * 10, 100) }}%"></div>
                </div>
            </div>

            {{-- Total Personal Contribution --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Total Penjualan</p>
                    <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($recentPembelians->sum('grand_total'), 0, ',', '.') }}</p>
                <p class="text-gray-400 text-xs mt-2">Dari transaksi terakhir</p>
            </div>
        </div>

        {{-- Recent Sales Transactions Table for Staff --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-wrap justify-between items-center gap-4">
                <div>
                    <h6 class="font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Recent Sales Transactions
                    </h6>
                    <p class="text-gray-400 text-xs mt-1">Riwayat 5 transaksi terakhir</p>
                </div>
                <a href="{{ route('pembelian.index') }}" class="text-blue-500 hover:text-blue-600 text-sm font-medium border border-blue-200 hover:bg-blue-50 px-4 py-2 rounded-xl transition-all duration-200 flex items-center gap-2">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left py-4 px-6 text-gray-500 text-xs font-semibold uppercase tracking-wider">No</th>
                            <th class="text-left py-4 px-6 text-gray-500 text-xs font-semibold uppercase tracking-wider">Invoice</th>
                            <th class="text-left py-4 px-6 text-gray-500 text-xs font-semibold uppercase tracking-wider">Customer</th>
                            <th class="text-left py-4 px-6 text-gray-500 text-xs font-semibold uppercase tracking-wider">Date</th>
                            <th class="text-left py-4 px-6 text-gray-500 text-xs font-semibold uppercase tracking-wider">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPembelians ?? [] as $index => $pembelian)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors duration-200">
                            <td class="py-3 px-6 text-gray-600 text-sm">{{ $index + 1 }}</td>
                            <td class="py-3 px-6">
                                <span class="font-mono text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg flex items-center gap-2 w-fit">
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    {{ $pembelian->invoice_number ?? 'INV-' . str_pad($pembelian->id, 6, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="py-3 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-50 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700 text-sm font-medium">{{ $pembelian->customer_name }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <div class="text-gray-500 text-sm flex items-center gap-2">
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d/m/Y') }}
                                    <span class="text-gray-300">•</span>
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('H:i') }}
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <span class="bg-emerald-50 text-emerald-600 font-semibold text-sm px-3 py-1.5 rounded-full inline-flex items-center gap-2">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Rp {{ number_format($pembelian->grand_total, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-16">
                                <div class="text-center">
                                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 mb-1">Belum ada transaksi</p>
                                    <p class="text-gray-400 text-sm">Silakan buat transaksi baru</p>
                                    <a href="{{ route('pembelian.create') }}" class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-medium px-5 py-2.5 rounded-full mt-4 transition-all duration-200 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Transaksi Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<script>
function refreshData() { 
    location.reload(); 
}

function showDetail(id) {
    const modalBody = document.getElementById('detailModalBody');
    
    // Tampilkan modal dengan Bootstrap 5
    const modalElement = document.getElementById('detailModal');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
    
    // Tampilkan loading
    modalBody.innerHTML = `
        <div class="text-center py-12">
            <div class="spinner-border text-blue-500" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-gray-500 mt-4">Memuat data detail transaksi...</p>
        </div>
    `;
    
    // Panggil route yang sudah ada (pembelian.show atau sejenisnya)
    // Asumsinya Anda punya route /pembelian/{id} yang mengembalikan JSON atau HTML
    fetch(`/pembelian/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Format currency
            const formatRupiah = (angka) => {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(angka);
            };
            
            // Format tanggal
            const formatDate = (dateString) => {
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', { 
                    day: 'numeric', 
                    month: 'long', 
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            };
            
            // Build items HTML
            let itemsHtml = '';
            if (data.items && data.items.length > 0) {
                itemsHtml = `
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 rounded-lg">
                                <tr>
                                    <th class="text-left py-3 px-4 text-gray-500 text-xs font-semibold">Produk</th>
                                    <th class="text-center py-3 px-4 text-gray-500 text-xs font-semibold">Qty</th>
                                    <th class="text-right py-3 px-4 text-gray-500 text-xs font-semibold">Harga</th>
                                    <th class="text-right py-3 px-4 text-gray-500 text-xs font-semibold">Subtotal</th>
                                <tr>
                            </thead>
                            <tbody>
                                ${data.items.map(item => `
                                    <tr class="border-b border-gray-50">
                                        <td class="py-3 px-4 font-medium text-gray-700">${item.nama_produk || item.product_name || 'Produk'}</td>
                                        <td class="py-3 px-4 text-center text-gray-600">${item.quantity || item.qty || 0}</td>
                                        <td class="py-3 px-4 text-right text-gray-600">${formatRupiah(item.harga || item.price || 0)}</td>
                                        <td class="py-3 px-4 text-right font-semibold text-gray-700">${formatRupiah(item.subtotal || item.total || 0)}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            } else {
                itemsHtml = `
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-gray-500">Tidak ada item dalam transaksi ini.</p>
                    </div>
                `;
            }
            
            modalBody.innerHTML = `
                <div class="space-y-5">
                    <!-- Header Info -->
                    <div class="grid grid-cols-2 gap-4 pb-4 border-b border-gray-100">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">No. Invoice</p>
                            <p class="font-mono text-sm font-semibold text-gray-800">${data.invoice_number || 'INV-' + String(data.id).padStart(6, '0')}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Tanggal Transaksi</p>
                            <p class="text-sm text-gray-700">${formatDate(data.tanggal || data.created_at)}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Customer</p>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-800">${data.customer_name || 'Umum'}</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Kasir</p>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-700">${data.dibuat_oleh || data.user?.name || '-'}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Items Table -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Detail Produk
                        </p>
                        ${itemsHtml}
                    </div>
                    
                    <!-- Total -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-semibold">Grand Total</span>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-blue-600">${formatRupiah(data.grand_total || data.total || 0)}</span>
                                <p class="text-xs text-gray-500 mt-1">Sudah termasuk pajak</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            console.error('Error:', error);
            modalBody.innerHTML = `
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-red-500 font-medium">Gagal memuat data transaksi</p>
                    <p class="text-gray-400 text-sm mt-1">Silakan coba lagi nanti</p>
                    <button onclick="showDetail(${id})" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all">
                        Coba Lagi
                    </button>
                </div>
            `;
        });
}
</script>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(Auth::user()->role === 'admin')
        const dailyLabels = @json($dailySales->pluck('date'));
        const dailyData = @json($dailySales->pluck('total'));
        
        const ctx = document.getElementById("myAreaChart");
        if (ctx && dailyLabels.length > 0) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dailyLabels,
                    datasets: [{
                        label: "Penjualan",
                        data: dailyData,
                        borderColor: "#3B82F6",
                        backgroundColor: "rgba(59, 130, 246, 0.05)",
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: "#3B82F6",
                        pointBorderColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#1f2937',
                            bodyColor: '#6b7280',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
        
        const pieCtx = document.getElementById("pieChart");
        if (pieCtx && @json($nama_produk).length > 0) {
            new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: @json($nama_produk),
                    datasets: [{
                        data: @json($actualData),
                        backgroundColor: @json($colors),
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { 
                                boxWidth: 10, 
                                font: { size: 10 },
                                color: '#6b7280'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#1f2937',
                            bodyColor: '#6b7280',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            padding: 10
                        }
                    }
                }
            });
        }
    @endif
});
</script>
@endpush