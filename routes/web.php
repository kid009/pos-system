<?php

use App\Livewire\Role\RoleForm;
use App\Livewire\Role\RoleIndex;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Customer\CustomerForm;
use App\Livewire\Customer\CustomerIndex;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    //Dashboard
    Route::view('dashboard', 'dashboard')
        ->name('dashboard');

    //Profile
    Route::view('profile', 'profile')
        ->name('profile');

    Route::middleware(['permission:manage_users'])->group(function () {
        //Role Management
        Route::get('roles', RoleIndex::class)
            ->name('roles.index');
        Route::get('roles/create', RoleForm::class)
            ->name('roles.create');
        Route::get('roles/{role}/edit', RoleForm::class)
            ->name('roles.edit');
    });

    // Customer Routes
    Route::get('/customers', CustomerIndex::class)->name('customers.index');
    Route::get('/customers/create', CustomerForm::class)->name('customers.create');
    Route::get('/customers/{customer}/edit', CustomerForm::class)->name('customers.edit');
});

require __DIR__ . '/auth.php';
