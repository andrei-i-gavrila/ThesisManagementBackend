<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use App\Models\ExamSession;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

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

        foreach (['leader_id', 'member1_id', 'member2_id', 'secretary_id'] as $idField) {
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

        $committee->update($ids);
    }

    public function create(ExamSession $examSession)
    {
        return $examSession->committees()->save(new Committee());
    }

    /**
     * @param Committee $committee
     * @throws Exception
     */
    public function delete(Committee $committee)
    {
        $committee->delete();
    }
}
