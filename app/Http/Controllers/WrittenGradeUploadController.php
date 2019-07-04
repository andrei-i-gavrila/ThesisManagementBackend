<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use App\Models\Paper;
use App\Models\User;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WrittenGradeUploadController extends Controller
{
    /**
     * @param Request $request
     * @param ExamSession $examSession
     * @throws ValidationException
     * @throws FileNotFoundException
     */
    public function upload(Request $request, ExamSession $examSession)
    {
        $this->validate($request, [
            'file' => 'file|required',
        ]);


        $lines = preg_split("/\r\n/", $request->file('file')->get());
        foreach ($lines as $line) {
            [$email, $grade] = explode(',', $line);

            $user = User::whereEmail($email)->first();
            if (!$user) continue;

            $grade = floatval($grade);
            if ($grade < 1 || $grade > 10) continue;

            Paper::query()->where([
                'exam_session_id' => $examSession->id,
                'student_id' => $user->id
            ])->update([
                'written_exam_grade' => $grade
            ]);
        }
    }

    public function getAll(ExamSession $examSession)
    {
        return $examSession->papers()->with('student:users.id,email,name')->get();
    }
}
