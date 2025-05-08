<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DetailOrderController;







Route::get('/', function () {
    return view('dashboard.home');
});

Route::resource('kategoris', KategoriController::class);
Route::get('/dashboard', [homeController::class, 'index'])->name('dashboard.home');
Route::resource('menus', MenuController::class);
Route::resource('members', MemberController::class);
Route::resource('reservasis', ReservasiController::class);
Route::resource('orders', OrderController::class);
Route::resource('detail_orders', DetailOrderController::class);