<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\PaperReview;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaperReviewController extends Controller
{
    /**
     * @param Request $request
     * @param Paper $paper
     * @throws ValidationException
     */
    public function create(Request $request, Paper $paper)
    {
        $this->validate($request, ['review' => 'required|string', 'final' => 'required|boolean']);

        PaperReview::create([
            'review' => $request->review,
            'final' => $request->final,
            'paper_id' => $paper->id,
            'professor_id' => Auth::id()
        ]);
    }

    /**
     * @param Request $request
     * @param PaperReview $paperReview
     * @throws ValidationException
     */
    public function update(Request $request, PaperReview $paperReview)
    {
        $attributes = $this->validate($request, ['review' => 'required|string', 'final' => 'required|boolean']);

        $paperReview->update($attributes);
    }


    /**
     * @param PaperReview $paperReview
     * @throws Exception
     */
    public function delete(PaperReview $paperReview)
    {
        $paperReview->delete();
    }

    public function get(Paper $paper)
    {
        return $paper->review;
    }
}
