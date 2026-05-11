<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Pembelians;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        // 1. Cari Produk (Berdasarkan hasil tinker: nama_produk)
        $products = Product::where('nama_produk', 'LIKE', "%{$query}%")
                            ->limit(5)
                            ->get();

        // 2. Cari Transaksi
        $transactions = Pembelians::where('customer_name', 'LIKE', "%{$query}%")
            ->orWhere('invoice_number', 'LIKE', "%{$query}%")
            ->orWhereHas('products', function($q) use ($query) {
                // Cari transaksi yang di dalamnya ada produk tertentu
                $q->where('nama_produk', 'LIKE', "%{$query}%");
            })
            ->latest()
            ->limit(5)
            ->get();

        return view('search_results', compact('products', 'transactions', 'query'));
    }
}