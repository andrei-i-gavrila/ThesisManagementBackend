<?php

namespace App\Http\Controllers\Auth;

use App\Http\Middleware\Authenticate;
use App\Models\AuthToken;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public function __invoke(Request $request)
    {
        Auth::logout();
        return response()->json();
    }
}
