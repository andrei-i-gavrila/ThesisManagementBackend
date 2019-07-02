<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Jobs\ProfessorDetailImporter;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ProfessorsController extends Controller
{

    /**
     * @param Request $request
     * @throws ValidationException
     */
    public function create(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $professor = User::firstOrNew(['email' => $request->email])->assignRole(Roles::PROFESSOR);
        $professor->save();

        dispatch_now(new ProfessorDetailImporter($professor));
    }

    /**
     * @param User $user
     * @return User
     * @throws AuthorizationException
     */
    public function get(User $user)
    {
        $this->checkIsProfessor($user);
        return $user->load('professorDetails');
    }

    /**
     * @param User $user
     * @throws AuthorizationException
     */
    private function checkIsProfessor(User $user): void
    {
        if (!$user->hasRole(Roles::PROFESSOR)) {
            throw new AccessDeniedHttpException("Cannot perform operation on a non professor");
        }
    }

    /**
     * @param User $user
     * @throws AuthorizationException
     */
    public function reimportDetails(User $user)
    {
        $this->checkIsProfessor($user);
        dispatch_now(new ProfessorDetailImporter($user));
    }

    public function getAll()
    {
        return User::role(Roles::PROFESSOR)->get(['id', 'name', 'email'])->keyBy('id')->toJson(JSON_FORCE_OBJECT);
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
}
