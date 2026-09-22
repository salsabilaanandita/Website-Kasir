<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('img')) {
            $imagePath = $request->file('img')->store('products', 'public');
            $validated['img'] = $imagePath;
        }



        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $oldStock = $product->stok; // Simpan stock lama untuk info
        
        $product->nama_produk = $request->nama_produk;
        $product->harga = str_replace('.', '', $request->harga);
        $product->stok = $request->stok;
        
        // Handle upload gambar baru
        if ($request->hasFile('img')) {
            if ($product->img && file_exists(storage_path('app/public/' . $product->img))) {
                unlink(storage_path('app/public/' . $product->img));
            }
            
            $file = $request->file('img');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/products', $filename);
            $product->img = 'products/' . $filename;
        }
        
        $product->save();
        
        // Pesan sukses dengan info perubahan stock
        $message = "Product updated successfully! ";
        if ($oldStock != $request->stok) {
            $message .= "Stock changed from {$oldStock} to {$request->stok}.";
        }
        
        return redirect()->route('products.index')->with('success', $message);
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stok' => 'required|numeric|min:0'
        ]);
    
        $product->update([
            'stok' => $request->stok
        ]);
    
        return redirect()->back()->with('success', 'Stock updated successfully');
    }

    public function destroy(Product $product)
    {
        if ($product->img) {
            Storage::disk('public')->delete($product->img);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}

