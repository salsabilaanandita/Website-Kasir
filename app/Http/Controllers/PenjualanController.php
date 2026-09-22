<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Product;
use App\Models\Member;
use App\Models\Shift;
use App\Models\StockMovement;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    /**
     * Tampilan POS / Kasir utama
     */
    public function index()
    {
        $products = Product::where('stok', '>', 0)->latest()->get();
        $activeShift = Shift::where('user_id', Auth::id())
            ->where('status', 'buka')
            ->latest()
            ->first();

        return view('kasir.index', compact('products', 'activeShift'));
    }

    /**
     * Halaman checkout (ringkasan keranjang)
     */
    public function checkout(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('kasir.index')->with('error', 'Keranjang kosong!');
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $subtotal = 0;
        $cartItems = [];

        foreach ($cart as $productId => $qty) {
            if (isset($products[$productId])) {
                $product   = $products[$productId];
                $itemTotal = $product->harga * $qty;
                $subtotal += $itemTotal;
                $cartItems[] = [
                    'product'  => $product,
                    'qty'      => $qty,
                    'subtotal' => $itemTotal,
                ];
            }
        }

        return view('kasir.checkout', compact('cartItems', 'subtotal'));
    }

    /**
     * Tambah produk ke keranjang via AJAX
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stok < $request->qty) {
            return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi! Stok: ' . $product->stok]);
        }

        $cart = session('cart', []);
        $currentQty = $cart[$request->product_id] ?? 0;

        if (($currentQty + $request->qty) > $product->stok) {
            return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi!']);
        }

        $cart[$request->product_id] = $currentQty + $request->qty;
        session(['cart' => $cart]);

        return response()->json([
            'success'    => true,
            'message'    => $product->nama_produk . ' ditambahkan ke keranjang',
            'cart_count' => array_sum($cart),
        ]);
    }

    /**
     * Update qty keranjang via AJAX
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'qty'        => 'required|integer|min:0',
        ]);

        $cart = session('cart', []);

        if ($request->qty == 0) {
            unset($cart[$request->product_id]);
        } else {
            $product = Product::find($request->product_id);
            if ($product && $request->qty > $product->stok) {
                return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi!']);
            }
            $cart[$request->product_id] = $request->qty;
        }

        session(['cart' => $cart]);

        return response()->json(['success' => true, 'cart_count' => array_sum($cart)]);
    }

    /**
     * Hapus item dari keranjang
     */
    public function removeFromCart(Request $request)
    {
        $cart = session('cart', []);
        unset($cart[$request->product_id]);
        session(['cart' => $cart]);

        return response()->json(['success' => true, 'cart_count' => array_sum($cart)]);
    }

    /**
     * Ambil data keranjang (JSON untuk AJAX)
     */
    public function getCart()
    {
        $cart      = session('cart', []);
        $products  = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $cartItems = [];
        $subtotal  = 0;

        foreach ($cart as $productId => $qty) {
            if (isset($products[$productId])) {
                $product  = $products[$productId];
                $itemTotal = $product->harga * $qty;
                $subtotal += $itemTotal;
                $cartItems[] = [
                    'product_id' => $product->id,
                    'nama'       => $product->nama_produk,
                    'harga'      => $product->harga,
                    'qty'        => $qty,
                    'subtotal'   => $itemTotal,
                    'img'        => $product->img ? asset('storage/' . $product->img) : null,
                ];
            }
        }

        return response()->json([
            'items'      => $cartItems,
            'subtotal'   => $subtotal,
            'cart_count' => array_sum($cart),
        ]);
    }

    /**
     * Cek member berdasarkan nomor HP (AJAX)
     */
    public function cekMember(Request $request)
    {
        $member = Member::where('phone_number', $request->phone)->first();

        if ($member) {
            return response()->json([
                'found'  => true,
                'member' => [
                    'id'     => $member->id,
                    'nama'   => $member->name,
                    'hp'     => $member->phone_number,
                    'points' => $member->points,
                ],
            ]);
        }

        return response()->json(['found' => false]);
    }

    /**
     * Proses transaksi & simpan ke database
     */
    public function proses(Request $request)
    {
        $request->validate([
            'metode_bayar' => 'required|in:Tunai,Transfer,QRIS',
            'bayar'        => 'required|numeric|min:0',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('kasir.index')->with('error', 'Keranjang kosong!');
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        // Validasi stok semua produk
        foreach ($cart as $productId => $qty) {
            if (!isset($products[$productId]) || $products[$productId]->stok < $qty) {
                return back()->with('error', 'Stok produk "' . ($products[$productId]->nama_produk ?? 'ID:' . $productId) . '" tidak mencukupi!');
            }
        }

        $subtotal = 0;
        foreach ($cart as $productId => $qty) {
            $subtotal += $products[$productId]->harga * $qty;
        }

        $diskon         = floatval($request->diskon ?? 0);
        $poinDigunakan  = floatval($request->poin_digunakan ?? 0);
        $total          = $subtotal - $diskon - $poinDigunakan;
        $bayar          = floatval($request->bayar);
        $kembalian      = $bayar - $total;

        // Handle member
        $member      = null;
        $memberId    = null;
        $poinBaru    = 0;

        if ($request->filled('member_id')) {
            $member   = Member::find($request->member_id);
            $memberId = $member?->id;
            $poinBaru = floor($total * 0.01); // 1% dari total
        }

        // Ambil shift aktif
        $shift = Shift::where('user_id', Auth::id())->where('status', 'buka')->latest()->first();

        DB::beginTransaction();
        try {
            // Buat transaksi penjualan
            $noTransaksi = 'TRX-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
            $penjualan   = Penjualan::create([
                'no_transaksi'   => $noTransaksi,
                'user_id'        => Auth::id(),
                'member_id'      => $memberId,
                'shift_id'       => $shift?->id,
                'subtotal'       => $subtotal,
                'diskon'         => $diskon,
                'poin_digunakan' => $poinDigunakan,
                'total'          => $total,
                'bayar'          => $bayar,
                'kembalian'      => max(0, $kembalian),
                'metode_bayar'   => $request->metode_bayar,
                'status'         => 'selesai',
                'kasir_nama'     => Auth::user()->name,
            ]);

            // Buat detail penjualan & kurangi stok
            foreach ($cart as $productId => $qty) {
                $product      = $products[$productId];
                $hargaSatuan  = $product->harga;
                $itemSubtotal = $hargaSatuan * $qty;

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'product_id'   => $productId,
                    'qty'          => $qty,
                    'harga_satuan' => $hargaSatuan,
                    'diskon'       => 0,
                    'subtotal'     => $itemSubtotal,
                ]);

                // Catat pergerakan stok
                $stokSebelum = $product->stok;
                $product->decrement('stok', $qty);
                StockMovement::create([
                    'product_id'   => $productId,
                    'tipe'         => 'keluar',
                    'qty'          => $qty,
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $stokSebelum - $qty,
                    'referensi'    => $noTransaksi,
                    'keterangan'   => 'Penjualan kasir',
                    'user_id'      => Auth::id(),
                ]);
            }

            // Update poin member
            if ($member) {
                if ($poinDigunakan > 0) {
                    $member->points = max(0, $member->points - $poinDigunakan);
                }
                $member->points += $poinBaru;
                $member->save();
            }

            // Update total penjualan di shift
            if ($shift) {
                $shift->increment('total_penjualan', $total);
                if ($request->metode_bayar === 'Tunai') {
                    $shift->increment('total_tunai', $bayar);
                }
            }

            // Catat activity log
            ActivityLog::catat('create', 'penjualan', "Transaksi {$noTransaksi} senilai Rp " . number_format($total, 0, ',', '.'));

            DB::commit();

            // Hapus keranjang
            session()->forget('cart');

            return redirect()->route('kasir.receipt', $penjualan->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan struk / receipt
     */
    public function receipt(Penjualan $penjualan)
    {
        $penjualan->load(['details.product', 'member', 'user']);
        return view('kasir.receipt', compact('penjualan'));
    }

    /**
     * Riwayat penjualan
     */
    public function riwayat(Request $request)
    {
        $query = Penjualan::with(['user', 'member'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_transaksi', 'like', "%{$search}%")
                  ->orWhere('kasir_nama', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $penjualans    = $query->paginate(10)->withQueryString();
        $totalHariIni  = Penjualan::whereDate('created_at', today())->sum('total');
        $jumlahHariIni = Penjualan::whereDate('created_at', today())->count();

        return view('penjualan.index', compact('penjualans', 'totalHariIni', 'jumlahHariIni'));
    }

    /**
     * Detail transaksi penjualan
     */
    public function detail(Penjualan $penjualan)
    {
        $penjualan->load(['details.product', 'member', 'user', 'shift']);
        return view('penjualan.detail', compact('penjualan'));
    }
}
