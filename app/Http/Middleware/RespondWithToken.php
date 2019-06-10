<?php

namespace App\Http\Middleware;

use App\Auth\TokenGuard;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RespondWithToken
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()) {
            $response->header(TokenGuard::TOKEN_FIELD, Auth::authToken()->token);
        }
        return $response;
    }
}
