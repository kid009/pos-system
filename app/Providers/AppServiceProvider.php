<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::component('layouts.admin', 'admin-layout');

        Gate::before(function ($user, $ability) {
            if (isset($user->is_banned) && $user->is_banned) {
                return false;
            }

            return $user->hasRole('super_admin') ? true : null;
        });
    }
}
