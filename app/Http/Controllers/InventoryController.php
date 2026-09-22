<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\StockAdjustment;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    /**
     * Halaman utama inventory (ringkasan stok)
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('stok')) {
            if ($request->stok === 'low') {
                $query->where('stok', '<=', 10)->where('stok', '>', 0);
            } elseif ($request->stok === 'habis') {
                $query->where('stok', 0);
            }
        }

        $products     = $query->latest()->paginate(10)->withQueryString();
        $totalProduk  = Product::count();
        $stokLow      = Product::where('stok', '<=', 10)->where('stok', '>', 0)->count();
        $stokHabis    = Product::where('stok', 0)->count();
        $nilaiInventory = Product::selectRaw('SUM(harga * stok) as total')->value('total') ?? 0;

        return view('inventory.index', compact('products', 'totalProduk', 'stokLow', 'stokHabis', 'nilaiInventory'));
    }

    /**
     * Riwayat pergerakan stok
     */
    public function movement(Request $request)
    {
        $query = StockMovement::with(['product', 'user'])->latest();

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        $movements = $query->paginate(10)->withQueryString();
        $products  = Product::orderBy('nama_produk')->get();

        return view('inventory.movement', compact('movements', 'products'));
    }

    /**
     * Form penyesuaian stok
     */
    public function adjustment(Request $request)
    {
        $products    = Product::orderBy('nama_produk')->get();
        $adjustments = StockAdjustment::with(['product', 'user'])->latest()->paginate(10);

        return view('inventory.adjustment', compact('products', 'adjustments'));
    }

    /**
     * Simpan penyesuaian stok
     */
    public function storeAdjustment(Request $request)
    {
        $request->validate([
            'product_id'  => 'required|exists:products,id',
            'stok_fisik'  => 'required|integer|min:0',
            'keterangan'  => 'nullable|string',
        ]);

        $product   = Product::findOrFail($request->product_id);
        $stokSistem = $product->stok;
        $stokFisik  = $request->stok_fisik;
        $selisih    = $stokFisik - $stokSistem;

        $noAdj = 'ADJ-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        StockAdjustment::create([
            'no_adjustment' => $noAdj,
            'product_id'    => $product->id,
            'stok_sistem'   => $stokSistem,
            'stok_fisik'    => $stokFisik,
            'selisih'       => $selisih,
            'keterangan'    => $request->keterangan,
            'user_id'       => Auth::id(),
        ]);

        // Catat pergerakan stok
        StockMovement::create([
            'product_id'   => $product->id,
            'tipe'         => 'adjustment',
            'qty'          => abs($selisih),
            'stok_sebelum' => $stokSistem,
            'stok_sesudah' => $stokFisik,
            'referensi'    => $noAdj,
            'keterangan'   => $request->keterangan ?? 'Penyesuaian stok',
            'user_id'      => Auth::id(),
        ]);

        // Update stok produk
        $product->update(['stok' => $stokFisik]);

        return redirect()->route('inventory.adjustment')
            ->with('success', "Stok {$product->nama_produk} berhasil disesuaikan! (Selisih: {$selisih})");
    }

    /**
     * Stock opname
     */
    public function opname(Request $request)
    {
        $opnames  = StockOpname::with('user')->latest()->paginate(10);
        $products = Product::orderBy('nama_produk')->get();

        return view('inventory.opname', compact('opnames', 'products'));
    }

    /**
     * Simpan stock opname
     */
    public function storeOpname(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $noOpname = 'OPN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        StockOpname::create([
            'no_opname' => $noOpname,
            'tanggal'   => $request->tanggal,
            'status'    => 'selesai',
            'catatan'   => $request->catatan,
            'user_id'   => Auth::id(),
        ]);

        return redirect()->route('inventory.opname')
            ->with('success', 'Stock opname berhasil dicatat!');
    }
}
