<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProfessorsController extends Controller
{

    /**
     * @param Request $request
     * @throws ValidationException
     */
    public function create(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        User::create(['email' => $request->email])->assignRole(Roles::PROFESSOR);
    }

    public function getAll()
    {
        return User::role(Roles::PROFESSOR)->get();
    }


    /**
     * @param User $user
     * @throws Exception
     */
    public function delete(User $user)
    {
        //TODO solve deletion when having students assigned

        $this->checkIsProfessor($user);

        $user->delete();
    }

    /**
     * @param User $user
     * @throws AuthorizationException
     */
    private function checkIsProfessor(User $user): void
    {
        if (!$user->hasRole(Roles::PROFESSOR)) {
            throw new AuthorizationException("Cannot perform operation on a non professor");
        }
    }

    /**
     * @param User $user
     * @throws AuthorizationException
     */
    public function toggleCoordinator(User $user)
    {
        $this->toggleRole($user, Roles::COORDINATOR);
    }

    /**
     * @param User $user
     * @param string $role
     * @throws AuthorizationException
     */
    private function toggleRole(User $user, string $role): void
    {
        $this->checkIsProfessor($user);

        if ($user->hasRole($role)) {
            $user->removeRole($role);
        } else {
            $user->assignRole($role);
        }
    }

    /**
     * @param User $user
     * @throws AuthorizationException
     */
    public function toggleEvaluator(User $user)
    {
        $this->toggleRole($user, Roles::EVALUATOR);
    }
}
