<?php

namespace App\Providers;

use App\Auth\TokenGuard;
use App\Models\AuthToken;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
                TokenGuard::TOKEN_FIELD => $authToken->token
            ]);
        });

        DB::listen(function (QueryExecuted $query) {
            Log::info($query->sql);
            Log::info($query->bindings);
        });

    }
}
