<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('modul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(10)->withQueryString();

        return view('activity.index', compact('logs'));
    }

    public function clear()
    {
        // Hanya hapus log lebih dari 30 hari
        ActivityLog::where('created_at', '<', now()->subDays(30))->delete();

        return redirect()->route('activity.index')
            ->with('success', 'Log aktivitas lebih dari 30 hari berhasil dihapus!');
    }
}
