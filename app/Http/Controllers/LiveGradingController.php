<?php

namespace App\Http\Controllers;

use App\Events\GradesUpdated;
use App\Models\Committee;
use App\Models\ExamSession;
use App\Models\Grade;
use App\Models\GradingCategory;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LiveGradingController extends Controller
{
    public function papers(ExamSession $examSession)
    {
        $committee = Committee::ofProfessor(Auth::user(), $examSession)->firstOrFail();

        return $examSession->papers()->where('committee_id', $committee->id)->with('student')->get()->sortBy('student.name')->values();
    }

    public function committee(ExamSession $examSession)
    {
        return Committee::ofProfessor(Auth::user(), $examSession)->with('leader')->firstOrFail();
    }

    public function paperData(Paper $paper)
    {
        return $paper->load(['student', 'finalRevision']);
    }

    public function grades(Paper $paper)
    {
        /** @var Collection $keyedGrades */
        $count = GradingCategory::whereExamSessionId($paper->exam_session_id)->count();
        if ($paper->grades()->count() < 3 * $count) {
            $profIds = collect([$paper->committee->leader_id, $paper->committee->member1_id, $paper->committee->member2_id])->filter();
            $categoryIds = GradingCategory::whereExamSessionId($paper->exam_session_id)->pluck('id');

            Grade::insert($profIds->crossJoin($categoryIds, [$paper->id])->transform(function($item) {
                return array_combine(['professor_id', 'category_id', 'paper_id'], $item);
            })->toArray());
        }

        return $paper->load('grades')->keyed_grades->toJson(JSON_FORCE_OBJECT);
    }

    /**
     * @param Request $request
     * @param Paper $paper
     * @param GradingCategory $category
     * @throws ValidationException
     */
    public function setGrade(Request $request, Paper $paper, GradingCategory $category)
    {
        $attributes = $this->validate($request, [
            'value' => 'nullable|numeric|between:1,10'
        ]);

        Grade::updateOrCreate([
            'paper_id' => $paper->id,
            'category_id' => $category->id,
            'professor_id' => Auth::id(),
        ], $attributes);

        broadcast(new GradesUpdated($paper))->toOthers();
    }
}
