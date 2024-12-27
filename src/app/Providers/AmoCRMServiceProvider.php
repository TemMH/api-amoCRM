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
            $longLivedAccessToken = new LongLivedAccessToken(env('AMO_LONG_TOKEN'));
            return (new AmoCRMApiClient())->setAccessToken($longLivedAccessToken)->setAccountBaseDomain(env('AMO_CLIENT_DOMAIN'));
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