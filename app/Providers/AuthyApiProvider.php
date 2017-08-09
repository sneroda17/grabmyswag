<?php

namespace App\Providers;

use Authy\AuthyApi as AuthyApi;
use Illuminate\Support\ServiceProvider;

class AuthyApiProvider extends ServiceProvider
{

    public function __construct(\Illuminate\Contracts\Foundation\Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton(AuthyApi::class, function ($app) {
            $authyKey = getenv('AUTHY_API_KEY') or die(
                "You must specify your api key for Authy. " .
                "Visit https://dashboard.authy.com/"
            );

            return new AuthyApi($authyKey);
        });
    }
}
