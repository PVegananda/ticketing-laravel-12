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

// Route profile user (hanya bisa diakses jika sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route Pembelian Tiket (Checkout)
    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');

    // Route Riwayat Pembelian Tiket
    Route::get('/my-tickets', [App\Http\Controllers\MyTicketController::class, 'index'])->name('my-tickets.index');
});

// Route detail event publik - bisa diakses siapa saja tanpa login
// Contoh URL: /events/1
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');


// Route dashboard admin (harus login + email terverifikasi)
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Route manajemen kategori (hanya admin, prefix 'admin' artinya URL jadi /admin/categories/...)
// name('categories.') artinya nama routenya jadi categories.index, categories.store, dll
Route::prefix('admin')->name('categories.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('destroy');
});

// Route manajemen event (hanya admin, prefix 'admin' artinya URL jadi /admin/events/...)
// name('admin.') artinya nama routenya jadi admin.events.index, admin.events.create, dll
// Route::resource otomatis membuat 7 route sekaligus (index, create, store, show, edit, update, destroy)
// ->except('show') artinya route show tidak dibuat (karena show event ada di route publik di atas)
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified'])
    ->group(function () {

        Route::resource('events', EventController::class)
            ->except('show'); // route show sudah ada di atas (publik)

        // Route Manajemen Transaksi (Admin)
        Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    });

// Memuat file route autentikasi (login, register, logout, dll)
require __DIR__.'/auth.php';