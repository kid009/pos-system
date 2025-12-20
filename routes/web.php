<?php

use App\Livewire\Dashboard;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Guest Route
Route::get('/login', Login::class)->name('login')->middleware('guest');

// Logout Route
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', Dashboard::class); // Redirect root to dashboard
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
});

// Employee Routes (Placeholder สำหรับ Sprint หน้า)
Route::middleware(['auth', 'role:employee,admin'])->group(function () {
    Route::get('/pos', function() {
        return "POS Page (Coming Soon in Sprint 2)";
    })->name('pos');
});
