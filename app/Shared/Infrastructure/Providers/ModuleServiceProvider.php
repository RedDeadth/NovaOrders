<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

// Auth
use App\Modules\Auth\Domain\Repositories\UserRepositoryInterface;
use App\Modules\Auth\Infrastructure\Repositories\EloquentUserRepository;
use App\Modules\Auth\Application\UseCases\RegisterUser;
use App\Modules\Auth\Application\UseCases\LoginUser;

// Product
use App\Modules\Product\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Product\Infrastructure\Repositories\EloquentProductRepository;

// Order
use App\Modules\Order\Domain\Repositories\OrderRepositoryInterface;
use App\Modules\Order\Infrastructure\Repositories\EloquentOrderRepository;

// Customer
use App\Modules\Customer\Domain\Repositories\CustomerRepositoryInterface;
use App\Modules\Customer\Infrastructure\Repositories\EloquentCustomerRepository;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repositories
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, EloquentProductRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, EloquentOrderRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, EloquentCustomerRepository::class);

        // Bind use cases
        $this->app->bind(RegisterUser::class, function ($app) {
            return new RegisterUser($app->make(UserRepositoryInterface::class));
        });

        $this->app->bind(LoginUser::class, function ($app) {
            return new LoginUser($app->make(UserRepositoryInterface::class));
        });
    }

    public function boot(): void
    {
        //
    }
}
