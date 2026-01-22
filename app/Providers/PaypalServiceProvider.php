<?php

namespace App\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use PayPal\Checkout\Environment\ProductionEnvironment;
use PayPal\Checkout\Environment\SandboxEnvironment;
use PayPal\Checkout\Http\PayPalClient;

class PaypalServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton(PayPalClient::class, function ($app) {

            $paypal_client_id = env('PAYPAL_CLIENT_ID');
            $paypal_secret = env('PAYPAL_SECRET');
            $paypal_mode = env('PAYPAL_MODE');
            if ('sandbox' == $paypal_mode) {
                $environment = new SandboxEnvironment($paypal_client_id, $paypal_secret);
            } else {
                $environment = new ProductionEnvironment($paypal_client_id, $paypal_secret);
            }

            return new PayPalClient($environment);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [PayPalClient::class];
    }
}
