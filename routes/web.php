<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;

// Route::get('/', function () {
//     return view('dashboard');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');

Route::get('/', [DashboardController::class, 'index'])->name('/');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/keranjang', function () {
    return view('keranjang');
})->name('keranjang');

Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
Route::get('/produk/{id}', [ProdukController::class, 'show'])->name('produk.show');

Route::get('/cart', [CartController::class, 'index'])->middleware(['auth', 'verified'])->name('cart.index');
Route::post('/cart/{produk}', [CartController::class, 'add'])->middleware(['auth', 'verified'])->name('cart.add');
Route::post('/checkout', [CartController::class, 'checkout'])->middleware(['auth', 'verified'])->name('cart.checkout');

Route::patch('/cart/{cart}', [CartController::class, 'update'])->middleware(['auth', 'verified'])->name('cart.update');
Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->middleware(['auth', 'verified'])->name('cart.destroy');
Route::post('/checkout', [CartController::class, 'checkout'])->middleware(['auth', 'verified'])->name('cart.checkout');

Route::get('/checkout', [CartController::class,'checkoutPage'])->name('checkout.page');
Route::post('/checkout', [CartController::class,'checkoutStore'])->name('checkout.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Riwayat Belanja
    Route::get('/riwayat-belanja', [TransaksiController::class, 'riwayat'])->name('riwayat-belanja');
    Route::get('/riwayat-belanja/{id}', [TransaksiController::class, 'detail'])->name('riwayat-belanja.detail');
});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

        // PRODUK
        Route::get('/produk', [AdminController::class, 'kelolaProduk'])->name('produk.index');
        Route::get('/produk/create', [AdminController::class, 'createProduk'])->name('produk.create');
        Route::post('/produk', [AdminController::class, 'storeProduk'])->name('produk.store');
        Route::get('/produk/{id}/edit', [AdminController::class, 'editProduk'])->name('produk.edit');
        Route::put('/produk/{id}', [AdminController::class, 'updateProduk'])->name('produk.update');
        Route::delete('/produk/{id}', [AdminController::class, 'destroyProduk'])->name('produk.destroy');

        // TRANSAKSI
        Route::get('/riwayat-transaksi', [AdminController::class, 'riwayat'])->name('riwayat');

        // Unduh Riwayat Transaksi
        Route::get('/admin/riwayat/pdf', [AdminController::class, 'riwayatPdf'])
        ->name('riwayat.pdf');

        // Users
        Route::get('/admin/users', [AdminController::class, 'users'])->name('users');
        Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('users.store');

        Route::get('/admin/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

});


require __DIR__.'/auth.php';
