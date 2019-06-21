<?php

namespace App\Http\Controllers;

use App\Jobs\LexicalOrderAssignationJob;
use App\Jobs\RandomAssignationJob;
use App\Models\ExamSession;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ExamSessionController extends Controller
{
    public function index()
    {
        return ExamSession::query()->latest()->get();
    }

    /**
     * @param Request $request
     * @throws ValidationException
     * @throws Exception
     */
    public function create(Request $request)
    {
        $attributes = $this->validate($request, [
            'name' => 'required|string',
            'department' => 'required|string'
        ]);

        $attributes['presentation_name'] = $attributes['name'];
        $attributes['name'] = Str::slug($attributes['name']);

        ExamSession::create($attributes);
    }

    /**
     * @param ExamSession $examSession
     * @throws Exception
     */
    public function delete(ExamSession $examSession)
    {
        $examSession->delete();
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
