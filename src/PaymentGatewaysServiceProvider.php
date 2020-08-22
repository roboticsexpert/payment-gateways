<?php

namespace Roboticsexpert\PaymentGateways;

use Illuminate\Support\ServiceProvider;
use Roboticsexpert\PaymentGateways\Gateways\MellatPaymentGateway;
use Roboticsexpert\PaymentGateways\Gateways\SamanPaymentGateway;
use Roboticsexpert\PaymentGateways\Gateways\ZarinpalPaymentGateway;
use Roboticsexpert\PaymentGateways\Gateways\ZarinpalPaymentGatewaySandbox;
use Roboticsexpert\PaymentGateways\Services\PaymentGatewayService;

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
        $this->app->singleton(PaymentGatewayService::class,function($app){
            return new PaymentGatewayService([
                new ZarinpalPaymentGateway(),
                new ZarinpalPaymentGatewaySandbox(),
                new SamanPaymentGateway(),
                new MellatPaymentGateway(),
            ]);
        });
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
        //$this->loadMigrationsFrom(__DIR__.'/Migrations');
        $this->loadViewsFrom(__DIR__.'/Views', 'pg');
        $this->publishes([
            __DIR__.'/Views' => base_path('resources/views/vendor/payment-gateways'),
        ]);
        $this->publishes([
            __DIR__.'/Migrations' => base_path('database/migrations'),
        ]);
    }
}
