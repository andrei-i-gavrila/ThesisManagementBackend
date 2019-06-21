<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Models\Committee;
use App\Models\ExamSession;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class CommitteeController extends Controller
{

    /**
     * @param Request $request
     * @param Committee $committee
     * @throws ValidationException
     */
    public function update(Request $request, Committee $committee)
    {
        $ids = $this->validate($request, [
            'leader_id' => 'nullable|exists:users,id',
            'member1_id' => 'nullable|exists:users,id',
            'member2_id' => 'nullable|exists:users,id',
            'secretary_id' => 'nullable|exists:users,id'
        ]);

        $idFields = ['leader_id', 'member1_id', 'member2_id', 'secretary_id'];
        foreach ($idFields as $idField) {
            if (!$request[$idField]) continue;

            $foundCommittee = Committee::query()->orWhere([
                'leader_id' => $request[$idField],
                'member1_id' => $request[$idField],
                'member2_id' => $request[$idField],
                'secretary_id' => $request[$idField]
            ])->first();

            if (!$foundCommittee) continue;

            if ($foundCommittee->id == $committee->id) continue;

            throw new UnprocessableEntityHttpException('Duplicate professor assignments');
        }

        $newOnes = collect($idFields)->mapWithKeys(function ($idField) {
            return [$idField, User::find($idField)];
        });

        if ($newOnes->some(function (User $user) {
            return !$user->hasRole(Roles::PROFESSOR);
        })) {
            throw new UnprocessableEntityHttpException('Only profs can be evaluators');
        }

        $evalRole = Role::findByName(Roles::EVALUATOR);

        if ($committee->leader_id != $ids['leader_id']) {
            $committee->leader->removeRole($evalRole);
            $newOnes['leader_id']->assignRole($evalRole);
        }

        if ($committee->member1_id != $ids['member1_id']) {
            $committee->member1->removeRole($evalRole);
            $newOnes['member1_id']->assignRole($evalRole);
        }

        if ($committee->member2_id != $ids['member2_id']) {
            $committee->member2->removeRole($evalRole);
            $newOnes['member2_id']->assignRole($evalRole);
        }

        $secretaryRole = Role::findByName(Roles::SECRETARY);
        if ($committee->secretary_id != $ids['secretary_id']) {
            $committee->secretary->removeRole($secretaryRole);
            $newOnes['secretary_id']->assignRole($secretaryRole);
        }

        $committee->update($ids);
    }

    public function create(ExamSession $examSession)
    {
        return $examSession->committees()->save(new Committee());
    }

    public function get(ExamSession $examSession)
    {
        $committees = $examSession->load(['committees', 'committees.leader', 'committees.secretary', 'committees.assignedPapers.student:users.id,users.name,email'])->committees->toArray();

        foreach ($committees as &$committee) {
            usort($committee['assigned_papers'], function($ap1, $ap2) {
                return strcmp($ap1['student']['name'], $ap2['student']['name']);
            });
        }
        return $committees;
    }

    /**
     * @param Committee $committee
     * @throws Exception
     */
    public function delete(Committee $committee)
    {
        if ($committee->leader_id) $committee->leader->removeRole(Roles::EVALUATOR);
        if ($committee->member1_id) $committee->member1->removeRole(Roles::EVALUATOR);
        if ($committee->member2_id) $committee->member2->removeRole(Roles::EVALUATOR);
        if ($committee->secretary_id) $committee->member2->removeRole(Roles::SECRETARY);


        $committee->delete();
    }


}
