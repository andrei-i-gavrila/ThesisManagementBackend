<?php


namespace App\Services\Genetic;


use App\Models\ExamSession;
use App\Models\Paper;

class Algorithm
{

    /**
     * @var FitnessCalculator
     */
    private $fitnessCalculator;
    /**
     * @var ExamSession
     */
    private $examSession;

    public function __construct(ExamSession $examSession)
    {
        $this->fitnessCalculator = new FitnessCalculator($examSession);
        $this->examSession = $examSession;
    }


    public function evolve($generations = 200, $tournamentSize = 25, $populationSize = 300): Individual
    {
        ini_set("max_execution_time", 3600);
        $population = [];

        for ($i = 0; $i < $populationSize; $i++) {
            $population[] = Individual::initRandom($this->examSession->papers->filter(function (Paper $paper) {
                return $paper->finalRevision != null;
            }), $this->examSession->committees);
        }

        $currentGen = 0;

        while ($currentGen < $generations) {
            $parent1 = $this->selection($population, $tournamentSize);
            $parent2 = $this->selection($population, $tournamentSize);

            while ($parent2 === $parent1) {
                $parent2 = $this->selection($population, $tournamentSize);
            }

            $offspring = $population[$parent1]->crossover($population[$parent2]);
            $offspring->mutate();

            if ($offspring->getGeneration() > $currentGen) {
                $currentGen = $offspring->getGeneration();
            }

            usort($population, function (Individual $a, Individual $b) {
                if ($a->getUnfitness() === $b->getUnfitness()) {
                    return ($a->getFitness($this->fitnessCalculator) > $b->getFitness($this->fitnessCalculator)) ? -1 : 1;
                }
                return ($a->getUnfitness() < $b->getUnfitness()) ? -1 : 1;
            });
            $population[count($population) - 1] = $offspring;

        }
        return $population[$this->maxByFitness($population)];
    }

    private function selection($population, $tournamentSize): int
    {
        $tournamentIndividuals = [];


        while (count($tournamentIndividuals) < $tournamentSize) {
            $selected = mt_rand(0, count($population) - 1);
            if (!isset($tournamentIndividuals[$selected])) {
                $tournamentIndividuals[$selected] = true;
            }
        }

        $sample = [];
        foreach ($tournamentIndividuals as $index => $_) {
            $sample[] = $population[$index];
        }

        return $this->maxByFitness($sample);
    }

    private function maxByFitness($sample): int
    {
        $max = -1;
        $maxIdx = -1;

        foreach ($sample as $i => $individual) {
            $fitness = $individual->getFitness($this->fitnessCalculator);
            if ($fitness > $max) {
                $max = $fitness;
                $maxIdx = $i;
            }
        }

        return $maxIdx;
    }


}