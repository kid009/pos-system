<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'processLogin'])->name('login.process');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// สร้าง Route ทดสอบหลัง Login สำเร็จ
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');
