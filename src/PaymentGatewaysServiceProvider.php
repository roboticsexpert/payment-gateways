<?php

namespace Roboticsexpert\PaymentGateways;

use Illuminate\Support\ServiceProvider;

class PaymentGatewaysServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/Migrations');
        $this->loadViewsFrom(__DIR__.'/Views', 'pg');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/vendor/payment-gateways'),
        ]);
    }
}
