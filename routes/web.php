<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailPembelianController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/search', [SearchController::class, 'index'])->name('search.global');
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');

    // User Management (Admin Only)
    Route::middleware(['admin'])->group(function () {
        // Menggunakan resource otomatis membuat nama: users.index, users.create, dll.
        Route::resource('users', UserController::class);
    });
    
    // Product Management
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::middleware(['admin'])->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::patch('/products/{product}/update-stock', [ProductController::class, 'updateStock'])->name('products.update-stock');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // ============ PEMBELIAN ROUTES ============
    // Route Statis (Taruh di ATAS)
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
    Route::get('/export/pembelian', [PembelianController::class, 'exportToExcel'])->name('pembelian.export');
    Route::get('/pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::post('/pembelian/member-info', [PembelianController::class, 'memberInfo'])->name('pembelian.member-info');
    Route::post('/pembelian/confirm', [PembelianController::class, 'confirm'])->name('pembelian.confirm');
    Route::post('/pembelian/pembayaran', [PembelianController::class, 'pembayaran'])->name('pembelian.pembayaran');
    Route::post('/pembelian/non-member/pembayaran', [PembelianController::class, 'pembayaranNonMember'])->name('pembelian.non-member.pembayaran');
    Route::post('/pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
    
    // Route Dinamis (Taruh di BAWAH)
    Route::get('/pembelian/detail/{pembelian}', [PembelianController::class, 'detail'])->name('pembelian.detail');
    Route::get('/pembelian/{id}/export-pdf', [PembelianController::class, 'exportToPDF'])->name('pembelian.export_pdf');
    Route::get('/pembelian/{pembelian}', [PembelianController::class, 'show'])->name('pembelian.show');
    Route::get('/pembelian/{id}', [DetailPembelianController::class, 'ajaxDetailHTML']);
});