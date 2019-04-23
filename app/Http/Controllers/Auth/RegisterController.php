<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\AuthToken;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param RegisterRequest $request
     * @return Response
     */
    public function __invoke(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        Auth::setUser($user);
        return response()->withAuthToken(AuthToken::createForUser($user), "Successful register");
    }
}
