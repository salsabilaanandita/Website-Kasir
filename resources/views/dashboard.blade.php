@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-1">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-950 sm:text-2xl">Ringkasan Bisnis</h1>
            <p class="text-xs text-slate-500 mt-1">
                Data operasional per <span class="font-medium text-slate-700">{{ now()->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB</span>
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="refreshData()" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-medium py-2 px-3.5 rounded-lg shadow-xs transition-colors">
                <i class="fas fa-arrows-rotate text-slate-400 text-xs"></i>
                <span>Segarkan</span>
            </button>
            <button onclick="window.print()" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-medium py-2 px-3.5 rounded-lg shadow-xs transition-colors">
                <i class="fas fa-print text-slate-400 text-xs"></i>
                <span>Cetak</span>
            </button>
        </div>
    </div>

    @if(Auth::user()->role == 'admin')
        {{-- Statistics Cards (Clean Slate & Monochrome) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Sales Card -->
            <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Total Transaksi</span>
                        <div class="text-2xl font-bold tracking-tight text-slate-950 tabular-nums mt-1.5">
                            {{ number_format($totalPembelian, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center text-xs">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
                <div class="pt-3 mt-3 border-t border-slate-100 text-[11px] text-slate-500 flex items-center gap-1.5">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                    <span>Total nota penjualan tercatat</span>
                </div>
            </div>

            <!-- Total Members Card -->
            <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Pelanggan Member</span>
                        <div class="text-2xl font-bold tracking-tight text-slate-950 tabular-nums mt-1.5">
                            {{ number_format($totalMember, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center text-xs">
                        <i class="fas fa-id-card"></i>
                    </div>
                </div>
                <div class="pt-3 mt-3 border-t border-slate-100 text-[11px] text-slate-500 flex items-center gap-1.5">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                    <span>Pelanggan terdaftar aktif</span>
                </div>
            </div>

            <!-- Total Products Card -->
            <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Katalog Produk</span>
                        <div class="text-2xl font-bold tracking-tight text-slate-950 tabular-nums mt-1.5">
                            {{ number_format($totalProduk, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center text-xs">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                </div>
                <div class="pt-3 mt-3 border-t border-slate-100 text-[11px] text-slate-500 flex items-center gap-1.5">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                    <span>Item varian terdaftar</span>
                </div>
            </div>

            <!-- Total Revenue Card -->
            <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Total Pendapatan</span>
                        <div class="text-2xl font-bold tracking-tight text-slate-950 tabular-nums mt-1.5">
                            Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="w-9 h-9 rounded-lg bg-slate-900 text-white flex items-center justify-center text-xs">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
                <div class="pt-3 mt-3 border-t border-slate-100 text-[11px] text-slate-500 flex items-center gap-1.5">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>Akumulasi omzet bruto</span>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <!-- Line Chart (Sales Trend) -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200/90 shadow-xs flex flex-col overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900 tracking-tight">Tren Penjualan Harian</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Grafik volume transaksi 30 hari terakhir</p>
                    </div>
                    <span class="text-[11px] font-mono font-medium text-slate-600 bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200/60">
                        30 Hari Terakhir
                    </span>
                </div>
                <div class="p-4 sm:p-5 flex-1" style="min-height: 280px; height: 300px;">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>

            <!-- Top Products Ranking -->
            <div class="bg-white rounded-xl border border-slate-200/90 shadow-xs flex flex-col overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900 tracking-tight">Produk Terlaris</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Top 5 produk berdasarkan unit terjual</p>
                    </div>
                    <span class="text-[11px] font-mono text-slate-400">Peringkat</span>
                </div>
                <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between">
                    <div class="space-y-4">
                        @php
                            $maxSold = $productSales->max('total_sold') ?: 1;
                            $sumSold = $productSales->sum('total_sold') ?: 1;
                        @endphp
                        @forelse($productSales->take(5) as $index => $product)
                            @php
                                $percent = round(($product->total_sold / $sumSold) * 100, 1);
                                $barPercent = round(($product->total_sold / $maxSold) * 100);
                            @endphp
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2 truncate">
                                        <span class="w-5 h-5 rounded-md bg-slate-100 text-slate-700 font-mono text-[10px] font-bold flex items-center justify-center flex-shrink-0">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="font-medium text-slate-900 truncate">{{ $product->nama_produk }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <span class="font-semibold text-slate-900 tabular-nums">{{ number_format($product->total_sold, 0, ',', '.') }} unit</span>
                                        <span class="text-[10px] font-mono text-slate-500 bg-slate-50 border border-slate-200/60 px-1.5 py-0.5 rounded">
                                            {{ $percent }}%
                                        </span>
                                    </div>
                                </div>
                                <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-slate-900 rounded-full transition-all duration-300" style="width: {{ $barPercent }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-slate-400 text-xs">
                                Belum ada data penjualan produk
                            </div>
                        @endforelse
                    </div>

                    <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                        <span>Total unit terjual tercatat</span>
                        <span class="font-mono font-semibold text-slate-900 tabular-nums">{{ number_format($sumSold, 0, ',', '.') }} pcs</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Transactions Table --}}
        <div class="bg-white rounded-xl border border-slate-200/90 shadow-xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900 tracking-tight">Transaksi Terakhir</h2>
                    <p class="text-xs text-slate-500 mt-0.5">5 nota penjualan terbaru yang berhasil dicatat sistem</p>
                </div>
                <a href="{{ route('pembelian.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-900 hover:text-slate-700 transition-colors self-start sm:self-auto">
                    <span>Semua Transaksi</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80">
                            <th class="py-3 px-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">No</th>
                            <th class="py-3 px-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">No. Nota</th>
                            <th class="py-3 px-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Pelanggan</th>
                            <th class="py-3 px-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Tanggal & Waktu</th>
                            <th class="py-3 px-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider text-right">Total Bayar</th>
                            <th class="py-3 px-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Kasir</th>
                            <th class="py-3 px-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider text-center">Status</th>
                            <th class="py-3 px-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($recentPembelians ?? [] as $index => $pembelian)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3 px-4 text-slate-400 font-mono">{{ $index + 1 }}</td>
                            <td class="py-3 px-4">
                                <span class="font-mono font-medium text-slate-900">
                                    {{ $pembelian->invoice_number ?? 'INV-' . str_pad($pembelian->id, 6, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-medium text-slate-800">
                                {{ $pembelian->customer_name ?: 'Pelanggan Umum' }}
                            </td>
                            <td class="py-3 px-4 text-slate-500 tabular-nums">
                                {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d/m/Y') }}
                                <span class="text-slate-300 mx-1">·</span>
                                {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('H:i') }}
                            </td>
                            <td class="py-3 px-4 text-right font-semibold text-slate-900 tabular-nums">
                                Rp {{ number_format($pembelian->grand_total, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                {{ $pembelian->dibuat_oleh ?? $pembelian->user->name ?? '-' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200/80">
                                    Selesai
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('pembelian.show', $pembelian->id) }}" class="inline-flex items-center gap-1 text-[11px] font-medium text-slate-600 hover:text-slate-900 hover:underline">
                                    <span>Detail</span>
                                    <i class="fas fa-chevron-right text-[9px]"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-12">
                                <p class="text-sm font-medium text-slate-600">Belum ada transaksi</p>
                                <p class="text-xs text-slate-400 mt-1">Transaksi penjualan yang selesai akan muncul di sini</p>
                                <a href="{{ route('kasir.index') }}" class="inline-flex items-center gap-1.5 mt-3 text-xs font-semibold text-slate-900 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-lg transition-colors">
                                    <i class="fas fa-plus text-[10px]"></i>
                                    <span>Buka Kasir Sekarang</span>
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @else
        {{-- Staff / Cashier Dashboard --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            {{-- Welcome Card (Clean Slate Dark) --}}
            <div class="lg:col-span-2 bg-[#090d16] text-white rounded-xl border border-slate-800 p-6 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white/10 text-slate-300 text-[11px] font-medium mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        <span>Kasir Siap Bertransaksi</span>
                    </div>
                    <h2 class="text-xl font-bold tracking-tight">Selamat Datang, {{ Auth::user()->name }}</h2>
                    <p class="text-slate-400 text-xs mt-1.5 max-w-md leading-relaxed">
                        Pastikan shift kasir Anda telah dibuka sebelum memproses transaksi pelanggan.
                    </p>
                </div>
                <div class="pt-6 flex flex-wrap gap-3">
                    <a href="{{ route('kasir.index') }}" class="inline-flex items-center gap-2 bg-white text-slate-950 hover:bg-slate-100 px-4 py-2 rounded-lg text-xs font-semibold transition-colors no-underline">
                        <i class="fas fa-calculator text-xs"></i>
                        <span>Buka Layar Kasir (POS)</span>
                    </a>
                    <a href="{{ route('shifts.index') }}" class="inline-flex items-center gap-2 bg-white/10 text-white hover:bg-white/15 border border-white/10 px-4 py-2 rounded-lg text-xs font-medium transition-colors no-underline">
                        <i class="fas fa-user-clock text-xs"></i>
                        <span>Status Shift</span>
                    </a>
                </div>
            </div>

            {{-- Today's Performance Stats --}}
            <div class="bg-white rounded-xl border border-slate-200/90 p-6 shadow-xs flex flex-col justify-between">
                <div>
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Transaksi Hari Ini</span>
                    <div class="text-3xl font-bold tracking-tight text-slate-950 tabular-nums mt-2">
                        {{ $todaySales ?? 0 }}
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Total transaksi yang tercatat hari ini</p>
                </div>
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500">Nilai Transaksi Terakhir</span>
                    <span class="font-semibold text-slate-900 tabular-nums">
                        Rp {{ number_format($recentPembelians->sum('grand_total'), 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Recent Sales Transactions Table for Staff --}}
        <div class="bg-white rounded-xl border border-slate-200/90 shadow-xs overflow-hidden mt-6">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900 tracking-tight">Transaksi Terakhir Anda</h2>
                    <p class="text-xs text-slate-500 mt-0.5">5 transaksi terkini yang diproses</p>
                </div>
                <a href="{{ route('penjualan.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-900 hover:text-slate-700 transition-colors">
                    <span>Riwayat Kasir</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80">
                            <th class="py-3 px-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">No</th>
                            <th class="py-3 px-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">No. Nota</th>
                            <th class="py-3 px-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Pelanggan</th>
                            <th class="py-3 px-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Waktu</th>
                            <th class="py-3 px-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider text-right">Total Bayar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($recentPembelians ?? [] as $index => $pembelian)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3 px-4 text-slate-400 font-mono">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 font-mono font-medium text-slate-900">
                                {{ $pembelian->invoice_number ?? 'INV-' . str_pad($pembelian->id, 6, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="py-3 px-4 font-medium text-slate-800">
                                {{ $pembelian->customer_name ?: 'Pelanggan Umum' }}
                            </td>
                            <td class="py-3 px-4 text-slate-500 tabular-nums">
                                {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 px-4 text-right font-semibold text-slate-900 tabular-nums">
                                Rp {{ number_format($pembelian->grand_total, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-400">
                                Belum ada transaksi yang diproses
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
                        label: "Transaksi",
                        data: dailyData,
                        borderColor: "#0f172a",
                        backgroundColor: "rgba(15, 23, 42, 0.04)",
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 3,
                        pointBackgroundColor: "#0f172a",
                        pointBorderColor: "#ffffff",
                        pointBorderWidth: 1.5,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#090d16',
                            titleColor: '#f8fafc',
                            bodyColor: '#cbd5e1',
                            borderColor: '#334155',
                            borderWidth: 1,
                            padding: 10,
                            boxPadding: 4,
                            usePointStyle: true,
                            bodyFont: { family: 'Inter', size: 12 },
                            titleFont: { family: 'Inter', size: 12, weight: 'bold' }
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: { family: 'Inter', size: 11 },
                                color: '#64748b'
                            },
                            grid: {
                                color: '#f1f5f9'
                            },
                            border: {
                                dash: [4, 4]
                            }
                        },
                        x: {
                            ticks: {
                                font: { family: 'Inter', size: 11 },
                                color: '#64748b',
                                maxTicksLimit: 10
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    @endif
});
</script>
@endpush