<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ExamSessionController extends Controller
{
    public function index()
    {
        return ExamSession::query()->latest()->get();
    }

    public function get(ExamSession $examSession)
    {
        return $examSession->load(['gradingCategories', 'gradingCategories.subcategories']);
    }

    /**
     * @param Request $request
     * @throws ValidationException
     * @throws Exception
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|unique:exam_sessions,id'
        ]);


        ExamSession::create([
            'id' => $request->id
        ]);
    }

    /**
     * @param ExamSession $examSession
     * @throws Exception
     */
    public function delete(ExamSession $examSession)
    {
        $examSession->delete();
    }

}
