<?php

namespace App\Http\Controllers;

use App\Models\FinalReview;
use App\Models\Paper;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PDF;

class FinalReviewController extends Controller
{
    /**
     * @param Request $request
     * @param Paper $paper
     * @throws ValidationException
     */
    public function store(Request $request, Paper $paper)
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
            'paper_id' => $paper->id
        ], $attributes);
    }

    public function delete(Paper $paper)
    {
        $paper->review()->delete();
    }

    public function download(Paper $paper)
    {
        abort_if(!$paper->review, 422, "No review for this student");

        $paper->load('review', 'student', 'examSession');

        return PDF::loadView('pdf.review', compact('paper'))->download();
    }

    public function get(Paper $paper)
    {
        return $paper->review;
    }
}
