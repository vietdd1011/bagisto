<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ProductFlatSyncService;
use Webkul\Product\Listeners\ProductFlat;

class ProductFlatSyncServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ProductFlatSyncService::class, function ($app) {
            return new ProductFlatSyncService($app->make(ProductFlat::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}