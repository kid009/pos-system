<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Store\ProductController;
use App\Http\Controllers\Store\CustomerController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Store\ExpenseCategoryController;
use App\Http\Controllers\Store\ProductCategoryController;
use App\Http\Controllers\Store\ProductMainCategoryController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:super-admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', function () {
            // อาจจะเป็นหน้า Admin Dashboard ในอนาคต
            return "Welcome to Admin Panel";
        });

        Route::resource('users', UserController::class);
        Route::resource('permissions', PermissionController::class); // เพิ่ม
        Route::resource('roles', RoleController::class);   
        Route::resource('tenants', TenantController::class);
        Route::resource('branches', BranchController::class);
        Route::get('/get-branches/{tenantId}', [BranchController::class, 'getBranchesByTenant'])->name('get-branches');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- START: Store Panel Route Group ---
Route::middleware(['auth', 'role:branch-manager|super-admin'])
    ->prefix('store')
    ->name('store.')
    ->group(function () {
        Route::resource('product-main-categories', ProductMainCategoryController::class);
        Route::resource('product-categories', ProductCategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('customers', CustomerController::class);
        Route::resource('expense-categories', ExpenseCategoryController::class);
    });

require __DIR__.'/auth.php';
