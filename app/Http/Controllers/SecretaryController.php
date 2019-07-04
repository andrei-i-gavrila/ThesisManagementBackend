<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use App\Models\ExamSession;
use App\Models\Paper;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecretaryController extends Controller
{
    public function download(Paper $paper)
    {
        $paper->load('student', 'grades', 'committee');

        return PDF::loadView('pdf.studentReport', compact('paper'))->download();
    }


    public function getAll(ExamSession $examSession)
    {
        return Committee::where([
            'secretary_id' => Auth::id(),
            'exam_session_id' => $examSession->id
        ])->with('assignedPapers', 'assignedPapers.student')->firstOrFail()->assignedPapers;
    }
}
