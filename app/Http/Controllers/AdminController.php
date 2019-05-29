<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{

    /**
     * @param Request $request
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function createCoordinator(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:users,email'
        ]);

        $user = User::create([
            'email' => $request->email,
        ]);

        $user->assignRole(Roles::TEACHER);

        return response()->json();
    }

    public function allCoordinators()
    {
        return User::role(Roles::TEACHER)->get();
    }

    /**
     * @param User $user
     * @throws Exception
     */
    public function removeCoordinator(User $user)
    {
        $user->delete();
    }

}
