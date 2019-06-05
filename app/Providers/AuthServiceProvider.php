<?php

namespace App\Providers;

use App\Auth\TokenGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPolicies();

        Auth::extend('authToken', function ($app, $name, array $config) {
            return new TokenGuard($app['request']);
        });
    }
}
