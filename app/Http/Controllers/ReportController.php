<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Pengeluaran;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Laporan penjualan harian/bulanan
     */
    public function sales(Request $request)
    {
        $tanggalDari   = $request->tanggal_dari ?? now()->startOfMonth()->toDateString();
        $tanggalSampai = $request->tanggal_sampai ?? now()->toDateString();

        $penjualans = Penjualan::with(['user', 'member'])
            ->whereBetween(DB::raw('DATE(created_at)'), [$tanggalDari, $tanggalSampai])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalPenjualan = Penjualan::whereBetween(DB::raw('DATE(created_at)'), [$tanggalDari, $tanggalSampai])
            ->sum('total');

        $jumlahTransaksi = Penjualan::whereBetween(DB::raw('DATE(created_at)'), [$tanggalDari, $tanggalSampai])
            ->count();

        // Grafik harian
        $dailyData = Penjualan::selectRaw('DATE(created_at) as tanggal, SUM(total) as total, COUNT(*) as jumlah')
            ->whereBetween(DB::raw('DATE(created_at)'), [$tanggalDari, $tanggalSampai])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return view('reports.sales', compact(
            'penjualans', 'totalPenjualan', 'jumlahTransaksi',
            'dailyData', 'tanggalDari', 'tanggalSampai'
        ));
    }

    /**
     * Laporan pembayaran (termasuk metode bayar)
     */
    public function payments(Request $request)
    {
        $tanggalDari   = $request->tanggal_dari ?? now()->startOfMonth()->toDateString();
        $tanggalSampai = $request->tanggal_sampai ?? now()->toDateString();

        $penjualans = Penjualan::with(['user', 'member'])
            ->whereBetween(DB::raw('DATE(created_at)'), [$tanggalDari, $tanggalSampai])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Rekap per metode pembayaran
        $metodeBayar = Penjualan::selectRaw('metode_bayar, COUNT(*) as jumlah, SUM(total) as total')
            ->whereBetween(DB::raw('DATE(created_at)'), [$tanggalDari, $tanggalSampai])
            ->groupBy('metode_bayar')
            ->get();

        $totalPembayaran = $metodeBayar->sum('total');

        return view('reports.payments', compact(
            'penjualans', 'metodeBayar', 'totalPembayaran',
            'tanggalDari', 'tanggalSampai'
        ));
    }

    /**
     * Laporan produk terlaris
     */
    public function products(Request $request)
    {
        $tanggalDari   = $request->tanggal_dari ?? now()->startOfMonth()->toDateString();
        $tanggalSampai = $request->tanggal_sampai ?? now()->toDateString();

        $topProducts = DB::table('detail_penjualans')
            ->join('products', 'detail_penjualans.product_id', '=', 'products.id')
            ->join('penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id')
            ->whereBetween(DB::raw('DATE(penjualans.created_at)'), [$tanggalDari, $tanggalSampai])
            ->selectRaw('products.id, products.nama_produk, products.harga, products.img,
                         SUM(detail_penjualans.qty) as total_qty,
                         SUM(detail_penjualans.subtotal) as total_omzet')
            ->groupBy('products.id', 'products.nama_produk', 'products.harga', 'products.img')
            ->orderByDesc('total_qty')
            ->paginate(10)
            ->withQueryString();

        return view('reports.products', compact('topProducts', 'tanggalDari', 'tanggalSampai'));
    }

    /**
     * Laporan profit (penjualan - pengeluaran)
     */
    public function profit(Request $request)
    {
        $tanggalDari   = $request->tanggal_dari ?? now()->startOfMonth()->toDateString();
        $tanggalSampai = $request->tanggal_sampai ?? now()->toDateString();

        $totalPenjualan  = Penjualan::whereBetween(DB::raw('DATE(created_at)'), [$tanggalDari, $tanggalSampai])->sum('total');
        $totalPengeluaran = Pengeluaran::whereBetween('tanggal', [$tanggalDari, $tanggalSampai])->sum('jumlah');
        $profit          = $totalPenjualan - $totalPengeluaran;

        // Breakdown pengeluaran per kategori
        $pengeluaranByKat = Pengeluaran::selectRaw('kategori, SUM(jumlah) as total')
            ->whereBetween('tanggal', [$tanggalDari, $tanggalSampai])
            ->groupBy('kategori')
            ->get();

        // Grafik bulanan
        $monthlyProfit = DB::table('penjualans')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, SUM(total) as penjualan")
            ->whereBetween(DB::raw('DATE(created_at)'), [$tanggalDari, $tanggalSampai])
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return view('reports.profit', compact(
            'totalPenjualan', 'totalPengeluaran', 'profit',
            'pengeluaranByKat', 'monthlyProfit', 'tanggalDari', 'tanggalSampai'
        ));
    }

    /**
     * Laporan kasir per shift
     */
    public function cashier(Request $request)
    {
        $tanggalDari   = $request->tanggal_dari ?? now()->startOfMonth()->toDateString();
        $tanggalSampai = $request->tanggal_sampai ?? now()->toDateString();

        $perKasir = DB::table('penjualans')
            ->join('users', 'penjualans.user_id', '=', 'users.id')
            ->whereBetween(DB::raw('DATE(penjualans.created_at)'), [$tanggalDari, $tanggalSampai])
            ->selectRaw('users.name, COUNT(*) as jumlah_transaksi, SUM(penjualans.total) as total_omzet')
            ->groupBy('users.name')
            ->orderByDesc('total_omzet')
            ->get();

        return view('reports.cashier', compact('perKasir', 'tanggalDari', 'tanggalSampai'));
    }
}
