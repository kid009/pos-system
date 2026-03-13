<?php

use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'processLogin'])->name('login.process');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

    Route::resource('/shop', ShopController::class);
});



// สร้าง Route ทดสอบหลัง Login สำเร็จ
