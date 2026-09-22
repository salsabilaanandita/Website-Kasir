<?php

namespace App\Http\Controllers;

use App\Models\ReturnPenjualan;
use App\Models\Penjualan;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = ReturnPenjualan::with(['penjualan', 'product', 'user'])->latest()->paginate(10);
        return view('penjualan.returns', compact('returns'));
    }

    public function create(Request $request)
    {
        $penjualan = null;
        if ($request->filled('no_transaksi')) {
            $penjualan = Penjualan::with('details.product')
                ->where('no_transaksi', $request->no_transaksi)
                ->first();
        }

        return view('penjualan.return_create', compact('penjualan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'penjualan_id' => 'required|exists:penjualans,id',
            'product_id'   => 'required|exists:products,id',
            'qty'          => 'required|integer|min:1',
            'alasan'       => 'required|string|max:255',
        ]);

        $product     = Product::findOrFail($request->product_id);
        $totalReturn = $product->harga * $request->qty;
        $noReturn    = 'RTN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        DB::beginTransaction();
        try {
            ReturnPenjualan::create([
                'no_return'    => $noReturn,
                'penjualan_id' => $request->penjualan_id,
                'product_id'   => $request->product_id,
                'qty'          => $request->qty,
                'total_return' => $totalReturn,
                'alasan'       => $request->alasan,
                'status'       => 'selesai',
                'user_id'      => Auth::id(),
            ]);

            // Kembalikan stok
            $stokSebelum = $product->stok;
            $product->increment('stok', $request->qty);
            StockMovement::create([
                'product_id'   => $product->id,
                'tipe'         => 'masuk',
                'qty'          => $request->qty,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSebelum + $request->qty,
                'referensi'    => $noReturn,
                'keterangan'   => 'Return penjualan: ' . $request->alasan,
                'user_id'      => Auth::id(),
            ]);

            DB::commit();

            $notifyUsers = \App\Models\User::whereIn('role', ['admin', 'manager'])->get();
            $msg = Auth::user()->name . " memproses return {$product->nama_produk} (Qty: {$request->qty})";
            \Illuminate\Support\Facades\Notification::send($notifyUsers, new \App\Notifications\ReturnProcessedNotification($noReturn, $msg));

            return redirect()->route('returns.index')
                ->with('success', "Return {$noReturn} berhasil diproses! Stok dikembalikan.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
