<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DetailOrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Controllers\DashboardController;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsExport;
use App\Http\Controllers\BarcodeController;



Route::get('/barcode/{id}', [BarcodeController::class, 'generate']);

Route::get('/reports/export-word', [ReportController::class, 'exportWord'])->name('reports.export-word');

Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.exportExcel');
// ========== Auth Routes ==========
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ========== Redirect Root to Login ==========
Route::get('/', function () {
    return redirect()->route('login');
});

// ========== Protected Routes (Requires Login) ==========
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard.index');

    Route::get('/no-access', function () {
        return view('errors.no_access');
    })->name('no-access');

    Route::middleware(['auth'])->group(function () {
        Route::get('/menus', function () {
            $menus = \App\Models\Menu::all();
            if (auth()->user()->role === 'user') {
                return view('menus.user_index', compact('menus'));
            } else {
                return view('menus.index', compact('menus')); // admin/kasir/pemilik
            }
        })->name('menus.index');
    });
    
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    Route::resource('kategoris', KategoriController::class);
    Route::resource('menus', MenuController::class);
    Route::resource('members', MemberController::class);
    Route::resource('reservasis', ReservasiController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('detail_orders', DetailOrderController::class);
    Route::resource('users', UserController::class);
    Route::resource('reports', ReportController::class);
});

Route::post('/orders/cart/add', [OrderController::class, 'addToCart'])->name('orders.cart.add');

Route::post('/orders/cart/remove', [OrderController::class, 'removeFromCart'])->name('orders.cart.remove');
;
Route::get('/detail_orders', [DetailOrderController::class, 'index'])->name('detail_orders.index');

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

// routes/web.php
Route::get('reports/{kasir_id}', [ReportController::class, 'show'])->name('reports.show');

Route::post('/orders/add-to-cart', [OrderController::class, 'addToCart'])->name('orders.addToCart');


Route::middleware('auth')->group(function () {
    Route::get('/profile-photo', [ProfilePhotoController::class, 'create'])->name('profile_photo.create');
    Route::post('/profile-photo', [ProfilePhotoController::class, 'store'])->name('profile_photo.store');
});


Route::get('/orders/cart/reload', function () {
    $cart = session('cart', []);
    if (count($cart) == 0) {
        return '<li class="list-group-item text-center text-muted">Keranjang kosong</li>';
    }
    $html = '';
    foreach ($cart as $id => $item) {
        $html .= '<li class="list-group-item" data-id="'. $id .'">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>'. e($item['nama_menu']) .'</strong>
                    <div class="mt-1 d-flex align-items-center">
                        <button class="btn btn-sm btn-outline-success px-2 btn-increase" data-id="'. $id .'">+</button>
                        <span class="mx-2">x'. $item['quantity'] .'</span>
                        <button class="btn btn-sm btn-outline-danger px-2 btn-decrease" data-id="'. $id .'">−</button>
                    </div>
                </div>
                <span>Rp '. number_format($item['harga'] * $item['quantity'], 0, ',', '.') .'</span>
            </div>
        </li>';
    }
    return $html;
})->name('orders.cart.reload');

Route::get('/orders/{id}/print', [OrderController::class, 'print'])->name('orders.print');
Route::post('/orders/cart/reset', [OrderController::class, 'resetCart'])->name('orders.cart.reset');
Route::get('/orders/{order}/struk', [OrderController::class, 'struk'])->name('orders.struk');
Route::get('/check-member', [OrderController::class, 'checkMember'])->name('orders.checkMember');


