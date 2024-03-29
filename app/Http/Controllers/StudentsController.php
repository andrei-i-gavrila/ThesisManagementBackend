<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Models\Paper;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class StudentsController extends Controller
{

    /**
     * @param Request $request
     * @throws ValidationException
     */
    public function create(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
        $student = User::firstOrCreate(['email' => $request->email])->assignRole(Roles::STUDENT);

        Auth::user()->students()->attach($student);
    }

    /**
     * @param User $user
     * @return User
     * @throws AuthorizationException
     */
    public function get(User $user)
    {
        $this->checkIsStudent($user);
        return $user->load(['paper', 'paper.revisions']);
    }

    /**
     * @param User $user
     * @throws AuthorizationException
     */
    private function checkIsStudent(User $user): void
    {
        if (!$user->hasRole(Roles::STUDENT)) {
            throw new AccessDeniedHttpException("Cannot perform operation on a non student");
        }
    }

    public function getMyStudents()
    {
        return Auth::user()->students()->get(['users.id', 'name', 'email'])->keyBy('id')->toJson(JSON_FORCE_OBJECT);
    }

    /**
     * @param User $user
     * @throws Exception
     */
    public function delete(User $user)
    {
        $this->checkIsStudent($user);

        $user->delete();
    }

}