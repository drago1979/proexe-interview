<?php

namespace App\Providers;

use External\Bar\Auth\LoginService;
use External\Baz\Auth\Authenticator;
use External\Foo\Auth\AuthWS;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LoginService::class, function($app) {
            return new LoginService();
        });

        $this->app->bind(Authenticator::class, function($app) {
            return new Authenticator();
        });

        $this->app->bind(AuthWS::class, function($app) {
            return new AuthWS();
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
