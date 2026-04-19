<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// INTERFACE
use App\Repositories\Interfaces\RestaurantRepositoryInterface;
use App\Repositories\Interfaces\MenuRepositoryInterface;
use App\Repositories\Interfaces\ReviewRepositoryInterface;

// IMPLEMENTASI
use App\Repositories\RestaurantRepository;
use App\Repositories\MenuRepository;
use App\Repositories\ReviewRepository;
use App\Services\TelegramService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RestaurantRepositoryInterface::class, RestaurantRepository::class);
        $this->app->bind(MenuRepositoryInterface::class, MenuRepository::class);
        $this->app->bind(ReviewRepositoryInterface::class, ReviewRepository::class);
        $this->app->singleton(TelegramService::class, function () {
            return new TelegramService();
        });
    }

    public function boot(): void
    {
        //
    }
}