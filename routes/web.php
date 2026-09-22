<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailPembelianController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::redirect('/home', '/dashboard');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // Global Search
    Route::get('/search', [SearchController::class, 'index'])->name('search.global');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');

    // ============ KASIR / POS ============
    Route::prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/', [PenjualanController::class, 'index'])->name('index');
        Route::get('/checkout', [PenjualanController::class, 'checkout'])->name('checkout');
        Route::post('/proses', [PenjualanController::class, 'proses'])->name('proses');
        Route::get('/receipt/{penjualan}', [PenjualanController::class, 'receipt'])->name('receipt');
        Route::post('/cart/add', [PenjualanController::class, 'addToCart'])->name('cart.add');
        Route::post('/cart/update', [PenjualanController::class, 'updateCart'])->name('cart.update');
        Route::post('/cart/remove', [PenjualanController::class, 'removeFromCart'])->name('cart.remove');
        Route::get('/cart', [PenjualanController::class, 'getCart'])->name('cart.get');
        Route::get('/cek-member', [PenjualanController::class, 'cekMember'])->name('cek_member');
    });

    // ============ RIWAYAT PENJUALAN ============
    Route::prefix('penjualan')->name('penjualan.')->group(function () {
        Route::get('/', [PenjualanController::class, 'riwayat'])->name('index');
        Route::get('/{penjualan}', [PenjualanController::class, 'detail'])->name('detail');
    });

    // ============ RETURN PENJUALAN ============
    Route::prefix('returns')->name('returns.')->group(function () {
        Route::get('/', [ReturnController::class, 'index'])->name('index');
        Route::get('/create', [ReturnController::class, 'create'])->name('create');
        Route::post('/', [ReturnController::class, 'store'])->name('store');
    });

    // ============ PRODUCTS ============
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::patch('/products/{product}/update-stock', [ProductController::class, 'updateStock'])->name('products.update-stock');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // ============ CATEGORIES ============
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::resource('categories', CategoryController::class);
    });

    // ============ SUPPLIERS ============
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::resource('suppliers', SupplierController::class);
    });

    // ============ INVENTORY ============
    Route::prefix('inventory')->name('inventory.')->middleware(['role:admin,manager'])->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/movement', [InventoryController::class, 'movement'])->name('movement');
        Route::get('/adjustment', [InventoryController::class, 'adjustment'])->name('adjustment');
        Route::post('/adjustment', [InventoryController::class, 'storeAdjustment'])->name('adjustment.store');
        Route::get('/opname', [InventoryController::class, 'opname'])->name('opname');
        Route::post('/opname', [InventoryController::class, 'storeOpname'])->name('opname.store');
    });

    // ============ EXPENSES / PENGELUARAN ============
    Route::prefix('expenses')->name('expenses.')->middleware(['role:admin,manager'])->group(function () {
        Route::get('/', [PengeluaranController::class, 'index'])->name('index');
        Route::get('/create', [PengeluaranController::class, 'create'])->name('create');
        Route::post('/', [PengeluaranController::class, 'store'])->name('store');
        Route::get('/{expense}/edit', [PengeluaranController::class, 'edit'])->name('edit');
        Route::put('/{expense}', [PengeluaranController::class, 'update'])->name('update');
        Route::delete('/{expense}', [PengeluaranController::class, 'destroy'])->name('destroy');
    });

    // ============ SHIFTS ============
    Route::prefix('shifts')->name('shifts.')->group(function () {
        Route::get('/', [ShiftController::class, 'index'])->name('index');
        Route::get('/open', [ShiftController::class, 'open'])->name('open');
        Route::post('/open', [ShiftController::class, 'store'])->name('store');
        Route::get('/close', [ShiftController::class, 'close'])->name('close');
        Route::post('/close', [ShiftController::class, 'tutup'])->name('tutup');
    });

    // ============ REPORTS ============
    Route::prefix('reports')->name('reports.')->middleware(['role:admin,manager'])->group(function () {
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/payments', [ReportController::class, 'payments'])->name('payments');
        Route::get('/products', [ReportController::class, 'products'])->name('products');
        Route::get('/profit', [ReportController::class, 'profit'])->name('profit');
        Route::get('/cashier', [ReportController::class, 'cashier'])->name('cashier');
    });

    // ============ SETTINGS ============
    Route::middleware(['admin'])->group(function () {
        Route::get('/settings/store', [SettingController::class, 'store'])->name('settings.store');
        Route::post('/settings/store', [SettingController::class, 'updateStore'])->name('settings.store.update');
        Route::get('/settings/receipt', [SettingController::class, 'receipt'])->name('settings.receipt');
        Route::post('/settings/receipt', [SettingController::class, 'updateReceipt'])->name('settings.receipt.update');
    });

    // ============ USER MANAGEMENT ============
    Route::middleware(['admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::put('/roles/{user}', [RoleController::class, 'updateRole'])->name('roles.update');
    });

    // ============ ACTIVITY LOG ============
    Route::middleware(['admin'])->group(function () {
        Route::get('/activity', [ActivityLogController::class, 'index'])->name('activity.index');
        Route::delete('/activity/clear', [ActivityLogController::class, 'clear'])->name('activity.clear');
    });

    // ============ PEMBELIAN (lama) ============
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
    Route::get('/export/pembelian', [PembelianController::class, 'exportToExcel'])->name('pembelian.export');
    Route::get('/pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::post('/pembelian/member-info', [PembelianController::class, 'memberInfo'])->name('pembelian.member-info');
    Route::post('/pembelian/confirm', [PembelianController::class, 'confirm'])->name('pembelian.confirm');
    Route::post('/pembelian/pembayaran', [PembelianController::class, 'pembayaran'])->name('pembelian.pembayaran');
    Route::post('/pembelian/non-member/pembayaran', [PembelianController::class, 'pembayaranNonMember'])->name('pembelian.non-member.pembayaran');
    Route::post('/pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
    Route::get('/pembelian/detail/{pembelian}', [PembelianController::class, 'detail'])->name('pembelian.detail');
    Route::get('/pembelian/{id}/export-pdf', [PembelianController::class, 'exportToPDF'])->name('pembelian.export_pdf');
    Route::get('/pembelian/{pembelian}', [PembelianController::class, 'show'])->name('pembelian.show');
    Route::get('/pembelian/{id}', [DetailPembelianController::class, 'ajaxDetailHTML']);
});