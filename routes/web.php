<?php

use App\Livewire\Dashboard;
use App\Livewire\Auth\Login;
use App\Livewire\Pos\PosComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\ProductComponent;
use App\Livewire\Admin\CategoryComponent;
use App\Livewire\Admin\CustomerComponent;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Guest Routes (Login)
Route::get('/login', Login::class)->name('login')->middleware('guest');

Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Logout Route
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// 3. Admin Routes (Secured)
Route::middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Master Data (Categories & Products)
    // หมายเหตุ: ถ้ายังไม่ได้สร้างไฟล์ Component 2 ตัวนี้ จะ Error "Class not found"
    // ให้รันคำสั่งสร้างไฟล์ตามด้านล่างทันที
    Route::get('/categories', CategoryComponent::class)->name('admin.categories');

    Route::get('/products', ProductComponent::class)->name('admin.products');

    Route::get('/customers', CustomerComponent::class)->name('admin.customers');

    Route::get('/pos', PosComponent::class)->name('pos');

});

// 4. POS / Employee Routes
Route::middleware(['auth', 'role:employee,admin'])->group(function () {
    Route::get('/pos', PosComponent::class)->name('pos');
});
