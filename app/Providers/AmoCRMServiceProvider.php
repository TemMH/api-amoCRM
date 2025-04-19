<?php

namespace App\Providers;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\LongLivedAccessToken;
use Illuminate\Support\ServiceProvider;

class AmoCrmServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(AmoCRMApiClient::class, function () {
            $longLivedAccessToken = new LongLivedAccessToken(config('services.amocrm.long_token'));
            return (new AmoCRMApiClient())->setAccessToken($longLivedAccessToken)->setAccountBaseDomain(config('services.amocrm.client_domain'));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
