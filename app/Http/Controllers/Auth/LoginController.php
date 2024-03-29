<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\AuthToken;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return mixed
     * @throws AuthorizationException
     */
    public function __invoke(LoginRequest $request)
    {
        $user = User::whereEmail($request->input('email'))->whereActivated(1)->first();
        if (!$user or !Hash::check($request->input('password'), $user->password)) {
            throw new AuthorizationException("Invalid credentials. Try again!");
        }
        $expirationTime = $request->input('remember_me', false) ? now()->addWeek() : null;
        return response()->withAuthToken(AuthToken::createForUser($user, $expirationTime), "Successful login");
    }
}
