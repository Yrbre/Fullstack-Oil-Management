<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Interfaces
use App\Repositories\Interfaces\ItemMasterRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;

// Implementations
use App\Repositories\Eloquent\ItemMasterRepository;
use App\Repositories\Eloquent\TransactionRepository;

class RepositoryServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(
            ItemMasterRepositoryInterface::class,
            ItemMasterRepository::class
        );

        $this->app->bind(
            TransactionRepositoryInterface::class,
            TransactionRepository::class
        );
    }

    public function boot()
    {
        //
    }
}
