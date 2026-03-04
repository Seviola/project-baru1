<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Auth;

Route::get('/home', [PageController::class, 'home'])->middleware('auth');
Route::get('/home/icon-tabler', [PageController::class, 'iconTabler']);
Route::get('/home/bc_typography', [PageController::class, 'typography']);
Route::get('/home/bc_color', [PageController::class, 'color']);  

Route::match(['get', 'post'], '/login', [PageController::class, 'login'])->name('login');     
Route::get('/home/login', [PageController::class, 'login']);
Route::get('/home/register', [PageController::class, 'register']);
Route::get('/home/sample-page', [PageController::class, 'samplePage']);

Route::get('/kasir', [PosController::class, 'index'])
    ->middleware('auth')
    ->name('kasir.index');

Route::post('/kasir/checkout', [PosController::class, 'store'])
    ->middleware('auth');

Route::get('/kasir/receipt/{id}', [PosController::class, 'receipt'])
    ->middleware('auth');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::resource('products', ProductController::class);