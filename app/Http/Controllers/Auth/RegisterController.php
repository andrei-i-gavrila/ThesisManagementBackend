<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\AuthToken;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $user = User::whereEmail($request->email)->first();
        $user->update([
            'password' => Hash::make($request->password),
            'name' => $request->name,
            'activated' => 1
        ]);

        Auth::setUser($user);
        return response()->withAuthToken(AuthToken::createForUser($user), "Successful register");
    }
}
