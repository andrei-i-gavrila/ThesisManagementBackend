<?php

namespace App\Http\Middleware;


use App\Auth\TokenGuard;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{

    /**
     * @param Request $request
     * @param $next
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle(Request $request, $next)
    {
        if (Auth::guest()) {
            throw new AuthenticationException();
        }

        $response = $next($request);

        if (Auth::check()) {
            $response->header(TokenGuard::TOKEN_FIELD, Auth::authToken()->token);
        }
        return $response;
    }

}
