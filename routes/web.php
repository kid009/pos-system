<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // ถ้า Login แล้วให้ไป Dashboard เลย, ถ้ายังให้ไปหน้า Login
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
