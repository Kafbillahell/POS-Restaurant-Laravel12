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
use App\Exports\ReportsExport; // Tidak wajib ada di sini, tapi tidak masalah
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\KitchenSettingController;
use App\Http\Controllers\PromoController;

// ========== Route Barcode (Tidak perlu Auth jika hanya untuk generate) ==========
Route::get('/barcode/{id}', [BarcodeController::class, 'generate']);

// ========== Route Auth ==========
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ========== Redirect Root to Login/Dashboard (Opsional, lebih baik ke login jika belum login) ==========
Route::get('/', function () {
    // Cek apakah user sudah login, jika sudah, arahkan ke dashboard
    if (Auth::check()) {
        return redirect()->route('dashboard.index');
    }
    return redirect()->route('login');
});

// ========== Protected Routes (Membutuhkan Auth) ==========
Route::middleware('auth')->group(function () {
    
    // Route Dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard.index');

    // Route No Access
    Route::get('/no-access', function () {
        return view('errors.no_access');
    })->name('no-access');

    // Route Custom Index Menu (Menggantikan Route::resource('menus',...) untuk index saja)
    // Sebaiknya, logic ini dipindahkan ke MenuController@index jika memungkinkan
    Route::get('/menus', function () {
        $menus = \App\Models\Menu::all();
        if (auth()->user()->role === 'user') {
            return view('menus.user_index', compact('menus'));
        } else {
            return view('menus.index', compact('menus')); // admin/kasir/pemilik
        }
    })->name('menus.index');

    // Resource Routes
    Route::resource('kategoris', KategoriController::class);
    // Route::resource('menus', MenuController::class); // Index diganti custom di atas, tapi buatlah yang lain di MenuController
    Route::resource('members', MemberController::class);
    Route::resource('reservasis', ReservasiController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('detail_orders', DetailOrderController::class); // Sudah ada index di bawah, ini mencakup semua
    Route::resource('users', UserController::class);
    Route::resource('reports', ReportController::class)->except(['show']); // Index dan lainnya, show diganti di bawah

    // Route Menu (sisa dari resource)
    Route::get('/menus/create', [MenuController::class, 'create'])->name('menus.create');
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
    Route::get('/menus/{menu}', [MenuController::class, 'show'])->name('menus.show');
    Route::get('/menus/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');

    // Custom Order Routes
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store'); // Duplikasi, tapi kita biarkan, mungkin untuk API/AJAX

    // Route Cart (Order)
    Route::post('/orders/cart/add', [OrderController::class, 'addToCart'])->name('orders.cart.add');
    Route::post('/orders/cart/remove', [OrderController::class, 'removeFromCart'])->name('orders.cart.remove');
    Route::post('/orders/cart/reset', [OrderController::class, 'resetCart'])->name('orders.cart.reset');
    Route::post('/orders/cart/sync-price', [OrderController::class, 'syncPrice'])->name('orders.cart.sync_price');
    Route::get('/orders/cart/reload', function () {
        $cart = session('cart', []);
        if (count($cart) == 0) {
            return '<li class="list-group-item text-center text-muted">Keranjang kosong</li>';
        }
        $html = '';
        foreach ($cart as $id => $item) {
            $html .= '<li class="list-group-item" data-id="' . $id . '">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>' . e($item['nama_menu']) . '</strong>
                        <div class="mt-1 d-flex align-items-center">
                            <button class="btn btn-sm btn-outline-success px-2 btn-increase" data-id="' . $id . '">+</button>
                            <span class="mx-2">x' . $item['quantity'] . '</span>
                            <button class="btn btn-sm btn-outline-danger px-2 btn-decrease" data-id="' . $id . '">−</button>
                        </div>
                    </div>
                    <span>Rp ' . number_format($item['harga'] * $item['quantity'], 0, ',', '.') . '</span>
                </div>
            </li>';
        }
        return $html;
    })->name('orders.cart.reload');

    // Route Order Utility
    Route::get('/orders/{id}/print', [OrderController::class, 'print'])->name('orders.print');
    Route::get('/orders/{order}/struk', [OrderController::class, 'struk'])->name('orders.struk');
    Route::get('/check-member', [OrderController::class, 'checkMember'])->name('orders.checkMember');

    // Route Reports
    Route::get('/reports/export-word', [ReportController::class, 'exportWord'])->name('reports.export-word');
    Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.exportExcel');
    Route::get('reports/{kasir_id}/{bulan_tahun}', [ReportController::class, 'show'])->name('reports.show');

    // Route Profile Photo
    Route::get('/profile-photo', [ProfilePhotoController::class, 'create'])->name('profile_photo.create');
    Route::post('/profile-photo', [ProfilePhotoController::class, 'store'])->name('profile_photo.store');

    // Route Kitchen Settings
    Route::prefix('settings/kitchen')->name('settings.kitchen.')->group(function () {
        Route::get('/', [KitchenSettingController::class, 'index'])->name('index');
        Route::post('/', [KitchenSettingController::class, 'update'])->name('update');
    });

    // Route Promo
    Route::prefix('promo')->name('promo.')->group(function () {
        Route::get('/', [PromoController::class, 'index'])->name('index');
        Route::post('/update', [PromoController::class, 'update'])->name('update');
    });

});