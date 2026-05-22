<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\MasterData\ProductCategoryController;
use App\Http\Controllers\MasterData\ProductController;
use App\Http\Controllers\MasterData\StockInboundController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::resource('roles', RoleController::class);

    // Master Data Routes
    Route::resource('product-categories', ProductCategoryController::class);

    Route::resource('product', ProductController::class);

    Route::get('inventory/inbound', [StockInboundController::class, 'create'])->name('inventory.inbound.create');
    Route::post('inventory/inbound', [StockInboundController::class, 'store'])->name('inventory.inbound.store');
});

require __DIR__ . '/auth.php';
