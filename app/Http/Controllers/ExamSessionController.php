<?php

namespace App\Http\Controllers;

use App\Jobs\LexicalOrderAssignationJob;
use App\Jobs\RandomAssignationJob;
use App\Models\ExamSession;
use App\Models\Paper;
use App\Models\PaperProfessorScore;
use App\Services\Genetic\Algorithm;
use App\Services\MatchScoreCalculator;
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

    public function smartAssignment(ExamSession $examSession)
    {
        $geneticAlgorithm = new Algorithm($examSession);
        $bestSolution = $geneticAlgorithm->evolve()->getResult();

        $bestSolution->map(function($committeeId, $paperId) {
            Paper::whereId($paperId)->update(['committee_id' => $committeeId]);
        });
    }

    public function calculateScores(ExamSession $examSession, MatchScoreCalculator $matchScoreCalculator)
    {
        $matchScoreCalculator->calculate($examSession);
    }

    public function lexicalOrderAssignment(ExamSession $examSession)
    {
        dispatch_now(new LexicalOrderAssignationJob($examSession));
    }

    public function getScoreOfDistribution(ExamSession $examSession)
    {
        $score = 0;

        foreach ($examSession->committees as $committee) {
            foreach ($committee->assignedPapers as $paper) {
                $pps = PaperProfessorScore::where([
                    'professor_id' => $committee->leader_id,
                    'paper_id' => $paper->id,
                ])->first();
                if ($pps) {
                    $score += $pps->value;
                }

                $pps = PaperProfessorScore::where([
                    'professor_id' => $committee->member1_id,
                    'paper_id' => $paper->id,
                ])->first();
                if ($pps) {
                    $score += $pps->value;
                }


                $pps = PaperProfessorScore::where([
                    'professor_id' => $committee->member2_id,
                    'paper_id' => $paper->id,
                ])->first();
                if ($pps) {
                    $score += $pps->value;
                }
            }
        }
        return $score;
    }

}
