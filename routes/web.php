<?php

use App\Livewire\Admin\CategoryComponent;
use App\Livewire\Admin\CustomerComponent;
use App\Livewire\Admin\DashboardComponent;
use App\Livewire\Admin\ExpenseCategoryComponent;
use App\Livewire\Admin\GlobalDashboard;
use App\Livewire\Admin\ProductComponent;
use App\Livewire\Admin\SalesReportComponent;
use App\Livewire\Admin\ShopComponent;
use App\Livewire\Admin\StockInComponent;
use App\Livewire\Admin\TransactionHistoryComponent;
use App\Livewire\Admin\UserComponent;
use App\Livewire\Auth\Login;
use App\Livewire\Pos\PosComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/login', Login::class)->name('login');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    // --- Logout ---
    Route::post('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');

    Route::get('/admin/global-dashboard', GlobalDashboard::class)->name('admin.global-dashboard');
    Route::get('/admin/shops', ShopComponent::class)->name('admin.shops');
    Route::get('/pos', PosComponent::class)->name('pos');
    Route::get('/dashboard', DashboardComponent::class)->name('dashboard');
    Route::get('/admin/categories', CategoryComponent::class)->name('admin.categories');
    Route::get('/admin/products', ProductComponent::class)->name('admin.products');
    Route::get('/admin/stock-in', StockInComponent::class)->name('admin.stock-in');
    Route::get('/admin/users', UserComponent::class)->name('admin.users');
    Route::get('/admin/expense-categories', ExpenseCategoryComponent::class)->name('admin.expense-categories');
    Route::get('/admin/transaction-history', TransactionHistoryComponent::class)->name('admin.transaction-history');
    Route::get('/sales-report', SalesReportComponent::class)->name('sales-report');
    Route::get('/admin/customers', CustomerComponent::class)->name('admin.customers');
}); // จบกลุ่ม auth
