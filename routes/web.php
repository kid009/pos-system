<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MasterData\BankController;
use App\Http\Controllers\MasterData\CustomerController;
use App\Http\Controllers\MasterData\SalesChannelController;
use App\Http\Controllers\MasterData\ShippingMethodController;
use App\Http\Controllers\MasterData\SupplierController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'processLogin'])->name('login.process');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

    Route::resource('/shop', ShopController::class);

    Route::resource('/category', CategoryController::class);

    Route::resource('/products', ProductController::class);

    Route::resource('/users', UserController::class);

    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');

    Route::post('/pos/checkout', [PosController::class, 'store'])->name('pos.checkout');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::patch('/transactions/{id}/payment-method', [TransactionController::class, 'updatePaymentMethod'])->name('transactions.update-payment-method');

    Route::resource('customers', CustomerController::class);

    //Master Data
    Route::resource('banks', BankController::class);
    Route::resource('shipping-methods', ShippingMethodController::class);
    Route::resource('sales-channels', SalesChannelController::class);
    Route::resource('suppliers', SupplierController::class);
});
