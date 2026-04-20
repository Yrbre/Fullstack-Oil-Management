<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Interfaces
use App\Repositories\Interfaces\ItemMasterRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;

// Implementations
use App\Repositories\Eloquent\ItemMasterRepository;
use App\Repositories\Eloquent\TransactionRepository;

// Services ItemMaster
use App\Services\Interfaces\ItemMasterServiceInterface;
use App\Services\Interfaces\TransactionServiceInterface;

// Services Transaction
use App\Services\ItemMasterService;
use App\Services\TransactionService;

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

        $this->app->bind(
            ItemMasterServiceInterface::class,
            ItemMasterService::class
        );

        $this->app->bind(
            TransactionServiceInterface::class,
            TransactionService::class
        );
    }

    public function boot()
    {
        //
    }
}
