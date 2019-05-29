<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    /**
     * @return array
     * @throws Exception
     */
    public function loggedUser()
    {
        $user = Auth::user();

        return [
            'user' => $user,
            'permissions' => $user->getAllPermissions()->map->name
        ];

    }
}
