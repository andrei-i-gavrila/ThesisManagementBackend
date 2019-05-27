<?php

namespace App\Providers;

use App\Http\Middleware\Authenticate;
use App\Models\AuthToken;
use Illuminate\Support\Facades\Response;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('withAuthToken', function (AuthToken $authToken, string $message = NULL) {
            return Response::json(compact('message'), 200, [
                Authenticate::TOKEN_FIELD => $authToken->token
            ]);
        });
    }
}
