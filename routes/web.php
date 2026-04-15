<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\RestockController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ReportController;
use App\Models\Product;

// PUBLIC ROUTES
Route::match(['get', 'post'], '/login', [PageController::class, 'login'])->name('login');
Route::get('/home/login', [PageController::class, 'login']);
Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegister']);
Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::get('/home/register', [PageController::class, 'register']);
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::middleware('auth')->group(function () {

    // Halaman umum
    Route::get('/home', [PageController::class, 'home']);
    Route::get('/home/icon-tabler', [PageController::class, 'iconTabler']);
    Route::get('/home/bc_typography', [PageController::class, 'typography']);
    Route::get('/home/bc_color', [PageController::class, 'color']);
    Route::get('/home/sample-page', [PageController::class, 'samplePage']);

    // ADMIN
    Route::middleware('role:admin')->group(function () {
        Route::resource('vendor', VendorController::class)->except(['show']);
        Route::resource('products', ProductController::class);
        Route::get('/kasir', [PosController::class, 'index'])->name('kasir.index');
        Route::post('/kasir/checkout', [PosController::class, 'checkout']);
        Route::get('/kasir/receipt/{id}', [PosController::class, 'receipt']);
        Route::get('/kasir/setor', [PosController::class, 'setor']);

        Route::get('/restock/history', [RestockController::class, 'history'])->name('restock.history');
        Route::patch('/restock/{vendorProduct}/approve', [RestockController::class, 'approve'])->name('restock.approve');
        Route::patch('/restock/{vendorProduct}/reject', [RestockController::class, 'reject'])->name('restock.reject');
        Route::patch('/restock/{vendorProduct}/paid', [RestockController::class, 'markPaid'])->name('restock.markPaid');

        // Products (admin)
        Route::resource('products', ProductController::class);

        // Kasir (admin)
        Route::get('/kasir', [PosController::class, 'index'])->name('kasir.index');
        Route::post('/kasir/checkout', [PosController::class, 'checkout']);
        Route::get('/kasir/receipt/{id}', [PosController::class, 'receipt']);
    });

    // VENDOR & ADMIN
    Route::middleware('role:admin,vendor')->group(function () {
        Route::get('/restock', [RestockController::class, 'index'])->name('restock.index');
        Route::post('/restock', [RestockController::class, 'store'])->name('restock.store');
        Route::get('/vendor', [VendorController::class, 'index'])->name('vendor.index');
    });

    // KASIR, USER & ADMIN
    Route::middleware('role:admin,kasir,user')->group(function () {
        Route::get('/kasir', [PosController::class, 'index'])->name('kasir.index');
        Route::post('/kasir/checkout', [PosController::class, 'checkout']);
        Route::get('/kasir/receipt/{id}', [PosController::class, 'receipt']);
        Route::get('/kasir/setor', [PosController::class, 'setor']);
    });

    // KASIR & ADMIN
    Route::middleware('role:admin,kasir')->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::get('/report/setoran', [ReportController::class, 'depositReport']);
    });

});

Route::get('/vendor/restock', function () {
    $products = Product::all();
    return view('vendor.restock', compact('products'));
});
Route::post('/vendor/restock', [VendorController::class, 'restock']);