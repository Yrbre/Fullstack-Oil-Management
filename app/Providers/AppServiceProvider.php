<?php

namespace App\Providers;

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
        Gate::define('manager-or-staff', function ($user) {
            return in_array($user->designation, ['manager', 'staff']);
        });

        Gate::define('admin', function ($user) {
            return $user->designation === 'admin';
        });
    }
}
