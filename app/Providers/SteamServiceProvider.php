<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \App\External\Steam;

class SteamServiceProvider extends ServiceProvider
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
        $this->app->singleton(Steam::class, function(){
            return new Steam(config('services.steam.secret'));
        });
    }
}
