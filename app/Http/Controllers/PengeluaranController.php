<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengeluaran::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        $pengeluarans = $query->paginate(10)->withQueryString();
        $totalPengeluaran = Pengeluaran::sum('jumlah');

        return view('expenses.index', compact('pengeluarans', 'totalPengeluaran'));
    }

    public function create()
    {
        $kategoriList = ['Operasional', 'Gaji', 'Utilitas', 'Sewa', 'Pembelian Barang', 'Lainnya'];
        return view('expenses.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:150',
            'keterangan'=> 'nullable|string',
            'jumlah'    => 'required|numeric|min:0',
            'kategori'  => 'required|string|max:50',
            'tanggal'   => 'required|date',
        ]);

        Pengeluaran::create([
            'judul'      => $request->judul,
            'keterangan' => $request->keterangan,
            'jumlah'     => $request->jumlah,
            'kategori'   => $request->kategori,
            'tanggal'    => $request->tanggal,
            'user_id'    => Auth::id(),
        ]);

        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil dicatat!');
    }

    public function edit(Pengeluaran $expense)
    {
        $kategoriList = ['Operasional', 'Gaji', 'Utilitas', 'Sewa', 'Pembelian Barang', 'Lainnya'];
        return view('expenses.edit', compact('expense', 'kategoriList'));
    }

    public function update(Request $request, Pengeluaran $expense)
    {
        $request->validate([
            'judul'     => 'required|string|max:150',
            'keterangan'=> 'nullable|string',
            'jumlah'    => 'required|numeric|min:0',
            'kategori'  => 'required|string|max:50',
            'tanggal'   => 'required|date',
        ]);

        $expense->update($request->only('judul', 'keterangan', 'jumlah', 'kategori', 'tanggal'));

        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil diperbarui!');
    }

    public function destroy(Pengeluaran $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil dihapus!');
    }
}
