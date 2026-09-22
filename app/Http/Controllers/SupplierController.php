<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:150',
            'telepon'       => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:100',
            'alamat'        => 'nullable|string',
            'kontak_person' => 'nullable|string|max:100',
        ]);

        Supplier::create($request->only('nama_supplier', 'telepon', 'email', 'alamat', 'kontak_person'));

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan!');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:150',
            'telepon'       => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:100',
            'alamat'        => 'nullable|string',
            'kontak_person' => 'nullable|string|max:100',
        ]);

        $supplier->update($request->only('nama_supplier', 'telepon', 'email', 'alamat', 'kontak_person'));

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui!');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil dihapus!');
    }
}
