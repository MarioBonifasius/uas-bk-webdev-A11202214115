<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\HistoriesController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\PaymentController;


Route::get('/', [DashboardController::class, 'index'])->name('home');
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('form.login');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->name('login');
Route::get('/register', [RegisteredUserController::class, 'create'])
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->name('register.tambah');


Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/payments/{order}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{order}/process', [PaymentController::class, 'process'])->name('payments.process');
});


Route::get('/detailevent/{event}', [EventsController::class, 'lihat'])->name('events.show');


Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'home'])->name('dashboard');
    Route::resource('categories', KategoriController::class);
    Route::resource('events', EventsController::class);
    Route::resource('tickets', TiketController::class);
    Route::resource('histories', HistoryController::class);
});
