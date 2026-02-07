<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\CustomerRepository;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\Interfaces\ProductVariantRepositoryInterface;
use App\Repositories\ProductVariantRepository;
use App\Repositories\Interfaces\{RentRepositoryInterface, RentItemRepositoryInterface};
use App\Repositories\{RentRepository, RentItemRepository};

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(ProductVariantRepositoryInterface::class, ProductVariantRepository::class);
        $this->app->bind(RentRepositoryInterface::class, RentRepository::class);
        $this->app->bind(RentItemRepositoryInterface::class, RentItemRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\RentReturnRepositoryInterface::class, \App\Repositories\RentReturnRepository::class);
    }

    public function boot()
    {
        //
    }
}
