<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use App\Models\Paper;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PaperController extends Controller
{

    /**
     * @param Request $request
     * @param ExamSession $examSession
     * @throws ValidationException
     */
    public function updateDetails(Request $request, ExamSession $examSession)
    {
        $attributes = $this->validate($request, [
            'name' => 'string|required',
            'link' => 'nullable|string',
        ]);

        Paper::updateOrCreate([
            'student_id' => Auth::id(),
            'exam_session_id' => $examSession->id
        ], $attributes);
    }


    /**
     * @param User $user
     * @param ExamSession $examSession
     * @return Model|HasOne|object|null
     */
    public function getWithRevisions(ExamSession $examSession, User $user)
    {
        $paper = $user->paper()->where('exam_session_id', $examSession->id)->first();
        if (!$paper) {
            $paper = Paper::create([
                'student_id' => $user->id,
                'exam_session_id' => $examSession->id
            ]);
        }
        return $paper->load('revisions');
    }
}
