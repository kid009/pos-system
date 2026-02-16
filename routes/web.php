<?php

use App\Livewire\Admin\CategoryComponent;
use App\Livewire\Admin\CustomerComponent;
use App\Livewire\Admin\DashboardComponent;
use App\Livewire\Admin\ExpenseCategoryComponent;
use App\Livewire\Admin\GlobalDashboard;
use App\Livewire\Admin\MainCategoryComponent;
use App\Livewire\Admin\ProductComponent;
use App\Livewire\Admin\SalesReportComponent;
use App\Livewire\Admin\ShopComponent;
use App\Livewire\Admin\StockInComponent;
use App\Livewire\Admin\TransactionHistoryComponent;
use App\Livewire\Admin\UserComponent;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\SelectShop;
use App\Livewire\Pos\PosComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth', 'check-shop-selected'])->group(function () {

    //admin
    Route::get('/admin/categories', CategoryComponent::class)->name('admin.categories');

    Route::get('/admin/products', ProductComponent::class)->name('admin.products');

    Route::get('/admin/customers', CustomerComponent::class)->name('admin.customers');

    Route::get('/admin/stock-in', StockInComponent::class)->name('admin.stock-in');

    Route::get('/admin/transaction-history', TransactionHistoryComponent::class)->name('admin.transaction-history');

    Route::get('/admin/expense-categories', ExpenseCategoryComponent::class)->name('admin.expense-categories');

    Route::get('/admin/shops', ShopComponent::class)->name('admin.shops');

    Route::get('/admin/global-dashboard', GlobalDashboard::class)->name('admin.global-dashboard');

    Route::get('/admin/users', UserComponent::class)->name('admin.users');

    //user
    Route::get('/sales-report', SalesReportComponent::class)->name('sales-report');

    Route::get('/select-shop', SelectShop::class)->name('select-shop');

    Route::get('/pos', PosComponent::class)->name('pos');

    Route::get('/dashboard', DashboardComponent::class)->name('dashboard');
});


