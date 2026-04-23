<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// User
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;
use App\Services\UserService;

// ItemMaster
use App\Services\Interfaces\ItemMasterServiceInterface;
use App\Services\Interfaces\TransactionServiceInterface;
use App\Repositories\Interfaces\ItemMasterRepositoryInterface;
use App\Repositories\Eloquent\ItemMasterRepository;

//Transaction
use App\Services\ItemMasterService;
use App\Services\TransactionService;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
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

        $this->app->bind(
            ItemMasterServiceInterface::class,
            ItemMasterService::class
        );

        $this->app->bind(
            TransactionServiceInterface::class,
            TransactionService::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            UserServiceInterface::class,
            UserService::class
        );
    }

    public function boot()
    {
        //
    }
}
