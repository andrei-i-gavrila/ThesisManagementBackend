<?php

namespace App\Http\Controllers;

use App\Events\ExamSessions\ExamSessionDeleted;
use App\Events\ExamSessions\ExamSessionUpdated;
use App\Jobs\LexicalOrderAssignationJob;
use App\Jobs\RandomAssignationJob;
use App\Models\ExamSession;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ExamSessionController extends Controller
{
    public function index()
    {
        return ExamSession::query()->latest()->get()->keyBy('name')->toJson(JSON_FORCE_OBJECT);
    }

    public function get(ExamSession $examSession)
    {
        return $examSession->load(['gradingCategories', 'gradingCategories.subcategories', 'committees', 'committees.leader', 'committees.secretary', 'committees.assignedStudents:users.id,users.name,email']);
    }

    /**
     * @param Request $request
     * @throws ValidationException
     * @throws Exception
     */
    public function create(Request $request)
    {
        $attributes = $this->validate($request, [
            'name' => 'required|unique:exam_sessions,name'
        ]);


        $examSession = ExamSession::create($attributes);
        broadcast(new ExamSessionUpdated($examSession));
    }

    /**
     * @param ExamSession $examSession
     * @throws Exception
     */
    public function delete(ExamSession $examSession)
    {
        $examSession->delete();
        broadcast(new ExamSessionDeleted($examSession->id));
    }


    public function randomAssignment(ExamSession $examSession)
    {
        dispatch_now(new RandomAssignationJob($examSession));
    }

    public function lexicalOrderAssignment(ExamSession $examSession)
    {
        dispatch_now(new LexicalOrderAssignationJob($examSession));
    }

}
