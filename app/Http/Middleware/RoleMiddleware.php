<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Penggunaan: Route::middleware('role:admin') atau Route::middleware('role:admin,staff')
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Jika AJAX, return JSON
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Akses tidak diizinkan.'], 403);
        }

        return redirect()->route('dashboard')
            ->with('error', 'Akses tidak diizinkan. Halaman ini hanya untuk: ' . implode(', ', $roles) . '.');
    }
}
