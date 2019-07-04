<?php


namespace App\Services\Genetic;


use Illuminate\Support\Collection;

class Individual
{

    private $data = [];
    private $paperIds = [];


    private $committeeCount = null;
    private $generation = 0;

    private $fitness = null;
    private $unfitness = null;

    public static function initRandom($papers, $committees)
    {
        $individual = new Individual();

        $individual->paperIds = $papers->map->id->values();
        $individual->committeeCount = $committees->count();

        foreach ($committees as $i => $committee) {
            for ($k = 0; $k < self::getAmount($i, $papers->count(), $committees->count()); $k++) {
                $individual->data[] = $committee->id;
            }
        }
        shuffle($individual->data);
        return $individual;
    }

    private static function getAmount($index, $total, $parts)
    {
        return intdiv($total, $parts) + ($index == 0 ? $total % $parts : 0);
    }

    public function mutate($chance = 0.1)
    {
        if (mt_rand() / mt_getrandmax() > $chance) {
            return;
        }


        $a = mt_rand(0, count($this->data) - 1);
        $b = mt_rand(0, count($this->data) - 1);
        while ($b === $a) {
            $b = mt_rand(0, count($this->data) - 1);
        }

        $tmp = $this->data[$a];
        $this->data[$a] = $this->data[$b];
        $this->data[$b] = $tmp;
    }

    public function crossover(Individual $other)
    {
        $cutoff = mt_rand(0, count($this->data) - 1);

        $offspring = new Individual();
        $offspring->paperIds = $this->paperIds;
        $offspring->committeeCount = $this->committeeCount;
        $offspring->generation = max($this->generation, $other->generation) + 1;
        for ($i = 0; $i < count($this->data); $i++) {
            $offspring->data[] = $i < $cutoff ? $this->data[$i] : $other->data[$i];
        }

        return $offspring;
    }

    public function getFitness(FitnessCalculator $fitnessCalculator)
    {
        if (count($this->data) != count($this->paperIds)) {
            dd($this->data, $this->paperIds);
        }
        if ($this->fitness === null) {
            $this->fitness = 0;
            for ($i = 0; $i < count($this->data); $i++) {
                $this->fitness += $fitnessCalculator->calculateFitness($this->paperIds[$i], $this->data[$i]);
            }
        }

        return $this->fitness;
    }


    public function getUnfitness()
    {
        if ($this->unfitness === null) {
            $this->unfitness = 0;

            $average = count($this->data) / $this->committeeCount;
            $distribution = [];

            foreach ($this->data as $committee) {
                if (!isset($distribution[$committee])) {
                    $distribution[$committee] = 0;
                }
                $distribution[$committee]++;
            }

            foreach ($distribution as $perCommittee) {
                $this->unfitness += ($average - $perCommittee) * ($average - $perCommittee);
            }
        }

        return $this->unfitness;
    }

    /**
     * @return int
     */
    public function getGeneration(): int
    {
        return $this->generation;
    }

    public function getResult(): Collection
    {
        return collect($this->paperIds)->mapWithKeys(function ($paperId, $idx) {
            return [$paperId => $this->data[$idx]];
        });
    }


}