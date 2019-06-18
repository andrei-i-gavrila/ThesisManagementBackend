<?php

namespace App\Http\Controllers;

use App\Models\FinalReview;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PDF;

class FinalReviewController extends Controller
{
    /**
     * @param Request $request
     * @param User $student
     * @throws ValidationException
     */
    public function store(Request $request, User $student)
    {
        $attributes = $this->validate($request, [
            'overall' => 'required|integer|between:1,4',
            'grade_recommendation' => 'required|integer|between:1,3',
            'structure' => 'required|integer|between:1,5',
            'originality' => 'required|integer|between:1,5',
            'literature_results' => 'required|integer|between:1,5',
            'references' => 'required|integer|between:1,5',
            'form' => 'required|integer|between:1,5',
            'result_analysis' => 'required|integer|between:1,5',
            'result_presentation' => 'required|integer|between:1,5',
            'app_complexity' => 'required|integer|between:1,5',
            'app_quality' => 'required|integer|between:1,5',
            'observations' => 'nullable|string',
        ]);

        FinalReview::updateOrCreate([
            'professor_id' => Auth::id(),
            'student_id' => $student->id
        ], $attributes);
    }

    public function delete(User $student)
    {
        $student->review()->delete();
    }

    public function download(User $student)
    {
        abort_if(!$student->review, 422, "No review for this student");

        return PDF::loadView('pdf.review', compact('student'))->download();
    }

    public function get(User $student)
    {
        return $student->review;
    }
}
