<?php

namespace App\Providers;

use App\Repositories\Eloquent\DepartmentRepository;
use App\Repositories\Eloquent\ItemLocationRepository;
use App\Repositories\Eloquent\ItemMasterRepository;
use App\Repositories\Eloquent\TransactionRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\WarehouseRepository;
use App\Repositories\Interfaces\DepartmentRepositoryInterface;
use App\Repositories\Interfaces\ItemLocationRepositoryInterface;
use App\Repositories\Interfaces\ItemMasterRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\WarehouseRepositoryInterface;
use App\Services\DepartmentService;
use App\Services\Interfaces\DepartmentServiceInterface;
use App\Services\Interfaces\ItemLocationServiceInterface;
use App\Services\Interfaces\ItemMasterServiceInterface;
use App\Services\Interfaces\TransactionServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use App\Services\Interfaces\WarehouseServiceInterface;
use App\Services\ItemLocationService;
use App\Services\ItemMasterService;
use App\Services\TransactionService;
use App\Services\UserService;
use App\Services\WarehouseService;
use Illuminate\Support\ServiceProvider;

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

        $this->app->bind(
            WarehouseRepositoryInterface::class,
            WarehouseRepository::class
        );

        $this->app->bind(
            WarehouseServiceInterface::class,
            WarehouseService::class
        );

        $this->app->bind(
            DepartmentRepositoryInterface::class,
            DepartmentRepository::class
        );

        $this->app->bind(
            DepartmentServiceInterface::class,
            DepartmentService::class
        );

        $this->app->bind(
            ItemLocationRepositoryInterface::class,
            ItemLocationRepository::class
        );

        $this->app->bind(
            ItemLocationServiceInterface::class,
            ItemLocationService::class
        );
    }

    public function boot()
    {
        //
    }
}
