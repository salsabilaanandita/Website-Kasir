<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckStaffRole
{
    public function handle(Request $request, Closure $next)
    {
        // GANTI 'staff' JADI 'staff' atau 'staf'
        if (Auth::check() && (Auth::user()->role === 'staff' || Auth::user()->role === 'staf')) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'Akses tidak diizinkan. Hanya staff yang dapat mengakses halaman ini.');
    }
}