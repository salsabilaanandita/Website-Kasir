<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts       = Shift::with('user')->latest()->paginate(10);
        $activeShift  = Shift::where('user_id', Auth::id())->where('status', 'buka')->latest()->first();

        return view('shifts.index', compact('shifts', 'activeShift'));
    }

    public function open()
    {
        $activeShift = Shift::where('user_id', Auth::id())->where('status', 'buka')->latest()->first();
        if ($activeShift) {
            return redirect()->route('shifts.index')->with('error', 'Kamu sudah memiliki shift yang aktif!');
        }

        return view('shifts.open');
    }

    public function store(Request $request)
    {
        $request->validate([
            'modal_awal' => 'required|numeric|min:0',
        ]);

        // Cek tidak ada shift aktif
        $existing = Shift::where('user_id', Auth::id())->where('status', 'buka')->first();
        if ($existing) {
            return back()->with('error', 'Kamu sudah memiliki shift aktif!');
        }

        Shift::create([
            'user_id'         => Auth::id(),
            'waktu_buka'      => now(),
            'modal_awal'      => $request->modal_awal,
            'total_penjualan' => 0,
            'total_tunai'     => 0,
            'status'          => 'buka',
        ]);

        return redirect()->route('kasir.index')->with('success', 'Shift berhasil dibuka! Selamat bekerja.');
    }

    public function close()
    {
        $activeShift = Shift::where('user_id', Auth::id())->where('status', 'buka')->latest()->first();
        if (!$activeShift) {
            return redirect()->route('shifts.index')->with('error', 'Tidak ada shift aktif!');
        }

        return view('shifts.close', compact('activeShift'));
    }

    public function tutup(Request $request)
    {
        $request->validate([
            'kas_akhir' => 'required|numeric|min:0',
            'catatan'   => 'nullable|string',
        ]);

        $shift = Shift::where('user_id', Auth::id())->where('status', 'buka')->latest()->first();
        if (!$shift) {
            return redirect()->route('shifts.index')->with('error', 'Tidak ada shift aktif!');
        }

        $shift->update([
            'waktu_tutup' => now(),
            'kas_akhir'   => $request->kas_akhir,
            'catatan'     => $request->catatan,
            'status'      => 'tutup',
        ]);

        $notifyUsers = \App\Models\User::whereIn('role', ['admin', 'manager'])->get();
        $msg = Auth::user()->name . " telah menutup shift dengan kas akhir Rp " . number_format($request->kas_akhir, 0, ',', '.');
        \Illuminate\Support\Facades\Notification::send($notifyUsers, new \App\Notifications\ShiftClosedNotification($shift, $msg));

        return redirect()->route('shifts.index')->with('success', 'Shift berhasil ditutup!');
    }
}
