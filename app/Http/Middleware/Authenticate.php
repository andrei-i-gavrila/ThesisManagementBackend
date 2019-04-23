<?php

namespace App\Http\Middleware;


use App\Models\AuthToken;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public const TOKEN_FIELD = "Authorization";

    /**
     * @param Request $request
     * @param $next
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle(Request $request, $next)
    {
        $authToken = $this->getAuthToken($this->getTokenOrFail($request));

        if (!$authToken) {
            throw new AuthenticationException();
        }

        return $this->continueRequestWithLoggedUser($request, $next, $authToken);
    }

    /**
     * @param string $tokenValue
     * @return AuthToken
     */
    public function getAuthToken($tokenValue)
    {
        $authToken = AuthToken::with('user')->whereToken($tokenValue)->first();
        return $authToken;
    }

    /**
     * @param Request $request
     * @return string
     * @throws AuthenticationException
     */
    public function getTokenOrFail(Request $request): string
    {
        if (!$request->hasHeader('Authorization')) {
            throw new AuthenticationException();
        }
        return $request->header('Authorization');
    }

    /**
     * @param Request $request
     * @param $next
     * @param AuthToken $token
     * @return mixed
     */
    public function continueRequestWithLoggedUser(Request $request, $next, AuthToken $token)
    {
        Auth::setUser($token->user);

        $response = $next($request);

        $response->header('Authorization', $token->refreshToken()->token);

        return $response;
    }

}
