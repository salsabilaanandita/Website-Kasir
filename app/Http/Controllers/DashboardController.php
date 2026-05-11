<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Pembelians;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;
        
        if ($role == 'admin') {
            // Data untuk Admin (yang sudah ada)
            $totalPembelian = Pembelians::count();
            $totalMember = Member::count();
            $totalProduk = Product::count();
            $totalKeuntungan = Pembelians::sum('grand_total');
            
            $dailySales = Pembelians::selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->whereDate('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            $productSales = DB::table('pembelian_details')
                ->join('products', 'pembelian_details.id_produk', '=', 'products.id')
                ->select('products.nama_produk', DB::raw('SUM(pembelian_details.quantity) as total_sold'))
                ->groupBy('products.nama_produk')
                ->orderByDesc('total_sold')
                ->get();
            
            $nama_produk = $productSales->pluck('nama_produk')->toArray();
            $actualData = $productSales->pluck('total_sold')->toArray();
            
            $colors = [
                'rgba(255, 99, 132, 0.8)', 'rgba(54, 162, 235, 0.8)', 
                'rgba(255, 206, 86, 0.8)', 'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)', 'rgba(255, 159, 64, 0.8)'
            ];
            
            $recentPembelians = Pembelians::with('user')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            return view('dashboard', compact(
                'totalPembelian', 'totalMember', 'totalProduk', 'totalKeuntungan',
                'dailySales', 'productSales', 'nama_produk', 'actualData', 
                'colors', 'recentPembelians'
            ));
            
        } else {
            // Data untuk Staff
            $todaySales = Pembelians::whereDate('created_at', today())->count();
            
            // TAMBAHKAN INI UNTUK STAFF
            $dailySales = Pembelians::selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->whereDate('created_at', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            $recentPembelians = Pembelians::with('user')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            return view('dashboard', compact('todaySales', 'dailySales', 'recentPembelians'));
        }
    }

    public function getStats(Request $request)
    {
        $period = $request->input('period', 'day');
        $query = Pembelians::query();
        $now = now();

        switch ($period) {
            case 'week':
                $query->whereDate('created_at', '>=', $now->startOfWeek())
                      ->whereDate('created_at', '<=', $now->endOfWeek());
                break;
            case 'month':
                $query->whereMonth('created_at', $now->month)
                      ->whereYear('created_at', $now->year);
                break;
            case 'year':
                $query->whereYear('created_at', $now->year);
                break;
            default:
                $query->whereDate('created_at', today());
                break;
        }

        $totalPembelian = $query->count();
        $totalKeuntungan = $query->sum('grand_total');
        $totalMember = Member::count();
        $totalProduk = Product::count();

        $dailySales = $query->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $productSales = DB::table('pembelian_details')
            ->join('products', 'pembelian_details.id_produk', '=', 'products.id')
            ->join('pembelians', 'pembelian_details.pembelian_id', '=', 'pembelians.id')
            ->whereBetween('pembelians.created_at', [$query->getQuery()->wheres[0]['value'] ?? now()->startOfDay(), $now])
            ->select('products.nama_produk', DB::raw('SUM(pembelian_details.quantity) as total_sold'))
            ->groupBy('products.nama_produk')
            ->orderByDesc('total_sold')
            ->get();

        return response()->json([
            'totalPembelian' => $totalPembelian,
            'totalMember' => $totalMember,
            'totalProduk' => $totalProduk,
            'totalKeuntungan' => $totalKeuntungan,
            'dailySales' => $dailySales,
            'productSales' => $productSales
        ]);
    }
}