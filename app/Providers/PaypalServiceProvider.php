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

# PAYPAL API CREDENTIALS
            $client_id = "AZI-gkNUoN-vcY7WK80Qo-wijfqi9BOdpghemgBNAy50qSMEFywGg8FA7p5L6CmAFI4BMepe4-EDWDB-";
            $secret = "EGQh64GnPeOrmKo191m4WMtLAruCRETNCLha98ol15gpLIlng61yIRxoxsLh08luumMud1WWWlL9n6bP";
            $mode = 'sandbox';
            $paypal_client_id = $client_id;
            $paypal_secret = $secret;
            $paypal_mode = $mode;
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
