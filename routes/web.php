<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;

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

    // HALAMAN UMUM
    Route::get('/home', [PageController::class, 'home']);
    

    // ================= ADMIN =================
    Route::middleware('role:admin')->group(function () {

        // Produk / Kelas
        Route::resource('products', ProductController::class);

        // Kasir
        Route::get('/kasir', [PosController::class, 'index'])->name('kasir.index');
        Route::post('/kasir/checkout', [PosController::class, 'checkout']);
        Route::get('/kasir/receipt/{id}', [PosController::class, 'receipt']);
        Route::get('/kasir/setor', [PosController::class, 'setor']);
        Route::get('/kasir/receipt-pdf/{id}', [PosController::class, 'receiptPdf']);

        // Report
        Route::get('/report', [ReportController::class, 'dailyReport']);
        Route::get('/report/pdf', [ReportController::class, 'downloadPdf']);
        Route::get('/report/setoran', [ReportController::class, 'depositReport'])->name('report.setoran');
    });

    // ================= KASIR, USER & ADMIN =================
    Route::middleware('role:admin,kasir,user')->group(function () {
        Route::get('/kasir', [PosController::class, 'index'])->name('kasir.index');
        Route::post('/kasir/checkout', [PosController::class, 'checkout']);
        Route::get('/kasir/receipt/{id}', [PosController::class, 'receipt']);
        Route::get('/kasir/receipt-pdf/{id}', [PosController::class, 'receiptPdf']);
        Route::post('/kasir/setor', [PosController::class, 'setor'])->name('kasir.setor');
    });

    // ================= KASIR & ADMIN =================
    Route::middleware('role:admin,kasir')->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::get('/report/setoran', [ReportController::class, 'depositReport']);
        Route::get('/classroom', [ClassRoomController::class, 'index'])->name('classroom.index');
        Route::get('/report/transaksi', [PosController::class, 'dailyReport'])->name('report.transaksi');
    });

    // ================= PROFIL =================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // ================= USER =================
    Route::middleware('role:admin,user')->group(function () {
        // user hanya akses halaman umum
    });

});
