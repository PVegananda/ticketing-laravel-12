<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Ticketing App
|--------------------------------------------------------------------------
| Semua route halaman web didaftarkan di sini.
| Middleware 'auth' artinya hanya user yang sudah login yang bisa akses.
| Middleware 'verified' artinya email user harus sudah terverifikasi.
*/

// Route halaman utama / homepage (bisa diakses siapa saja, tidak perlu login)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route Pusat Bantuan / Petunjuk Teknis (Publik)
Route::get('/bantuan', function () {
    return view('pages.help');
})->name('help');

// Route profile user (hanya bisa diakses jika sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route Pembelian Tiket (Checkout)
    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');

    // Route Pembayaran (QRIS Gimmick)
    Route::get('/checkout/payment/{id}', [App\Http\Controllers\CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/payment/{id}/process', [App\Http\Controllers\CheckoutController::class, 'processPayment'])->name('checkout.process');
    Route::post('/checkout/payment/{id}/cancel', [App\Http\Controllers\CheckoutController::class, 'cancelPayment'])->name('checkout.cancel');

    // Route Riwayat Pembelian Tiket
    Route::get('/my-tickets', [App\Http\Controllers\MyTicketController::class, 'index'])->name('my-tickets.index');
});

// Route detail event publik - bisa diakses siapa saja tanpa login
// Contoh URL: /events/1
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');


// Route dashboard admin (harus login + email terverifikasi + role admin/superadmin)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:admin,superadmin'])
    ->name('dashboard');

// Route manajemen kategori (hanya admin/superadmin, prefix 'admin' artinya URL jadi /admin/categories/...)
Route::prefix('admin')->name('categories.')->middleware(['auth', 'verified', 'role:admin,superadmin'])->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('destroy');
});

// Route manajemen event & transaksi (hanya admin/superadmin)
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:admin,superadmin'])
    ->group(function () {

        Route::resource('events', EventController::class)
            ->except('show'); // route show sudah ada di atas (publik)

        // Route Manajemen Transaksi (Admin)
        Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');

        // Route Manajemen User (Hanya Superadmin)
        Route::middleware(['role:superadmin'])->group(function () {
            Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
            Route::patch('/users/{id}/role', [App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.updateRole');
        });
    });

// Memuat file route autentikasi (login, register, logout, dll)
require __DIR__.'/auth.php';