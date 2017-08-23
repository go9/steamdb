<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \App\External\G2a;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(G2aApi::class, function(){
            return new G2aApi;
        });
    }
}
