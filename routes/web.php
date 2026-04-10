<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AccountController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;


// ═══════════════════════════════════════
//  PUBLIC ROUTES
// ═══════════════════════════════════════
Route::get('/', [ProductController::class, 'home'])->name('home');
Route::get('/menu', [ProductController::class, 'index'])->name('menu');
Route::get('/menu/{slug}', [ProductController::class, 'show'])->name('menu.show');

// ═══════════════════════════════════════
//  AUTH ROUTES
// ═══════════════════════════════════════
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    // Google Login
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ═══════════════════════════════════════
//  PROFILE ROUTES (galing sa Breeze)
// ═══════════════════════════════════════
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ═══════════════════════════════════════
//  CUSTOMER ROUTES
// ═══════════════════════════════════════
Route::middleware('auth')->group(function () {

    // Cart
    Route::get('/cart',         [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add',    [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{id}',  [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart',      [CartController::class, 'clear'])->name('cart.clear');

    // Orders
    Route::get('/orders',              [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}',         [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/checkout',    [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::get('/orders/success/{id}', [OrderController::class, 'success'])->name('orders.success');

    // Account
    Route::get('/account',                  [AccountController::class, 'index'])->name('account.index');
    Route::patch('/account',                [AccountController::class, 'update'])->name('account.update');
    Route::patch('/account/password',       [AccountController::class, 'updatePassword'])->name('account.password');
    Route::post('/account/photo',           [AccountController::class, 'updatePhoto'])->name('account.photo');

});

// ═══════════════════════════════════════
//  ADMIN ROUTES
// ═══════════════════════════════════════
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::get('/products',             [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create',      [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products',            [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit',   [AdminProductController::class, 'edit'])->name('products.edit');
    Route::patch('/products/{id}',      [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}',     [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Orders
    Route::get('/orders',               [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}',          [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

});

require __DIR__.'/auth.php';