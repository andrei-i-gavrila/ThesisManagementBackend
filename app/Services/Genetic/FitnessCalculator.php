<?php


namespace App\Services\Genetic;


use App\Models\Committee;
use App\Models\ExamSession;
use App\Models\Paper;
use App\Models\PaperProfessorScore;

class FitnessCalculator
{

    private $scores = [];

    public function __construct(ExamSession $examSession)
    {

        $committees = $examSession->committees;

        $examSession->load('papers.scores', 'papers.finalRevision')->papers->filter(function (Paper $paper) {
            return $paper->finalRevision != null;
        })->each(function (Paper $paper) use ($committees) {
            $scoresPerProf = $paper->scores->mapWithKeys(function (PaperProfessorScore $score) {
                return [intval($score->professor_id) => $score->value];
            });

            $committees->each(function (Committee $committee) use ($scoresPerProf, $paper) {
                $committeeScore = $committee->allMemberIds->map(function ($id) use ($scoresPerProf) {
                    return $scoresPerProf->get(intval($id), 0);
                })->sum();

                $this->scores["$paper->id#$committee->id"] = $committeeScore;
            });
        });
    }

    public function getScoreAvgDeviation()
    {
        $total = 0;
        foreach ($this->scores as $score) {
            $total += $score;
        }

        $average  = $total / count($this->scores);
        $deviation = 0;

        foreach ($this->scores as $score) {
            $deviation += abs($score - $average);
        }

        return $deviation / count($this->scores);
    }


    public function calculateFitness($paperId, $committeeId)
    {
        return $this->scores["$paperId#$committeeId"];
    }
}