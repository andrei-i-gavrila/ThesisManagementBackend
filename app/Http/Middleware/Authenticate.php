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
    private function getAuthToken($tokenValue)
    {
        return AuthToken::with('user')->find($tokenValue);
    }

    /**
     * @param Request $request
     * @return string
     * @throws AuthenticationException
     */
    private function getTokenOrFail(Request $request): string
    {
        if (!$request->hasHeader(self::TOKEN_FIELD)) {
            throw new AuthenticationException();
        }
        return $request->header(self::TOKEN_FIELD);
    }

    /**
     * @param Request $request
     * @param $next
     * @param AuthToken $token
     * @return mixed
     */
    private function continueRequestWithLoggedUser(Request $request, $next, AuthToken $token)
    {
        Auth::setUser($token->user);
        $response = $next($request);

        if (Auth::check()) {
            $response->header(self::TOKEN_FIELD, $token->refreshToken()->token);
        }
        return $response;
    }

}
