<?php

namespace App\Services;

class TFIDF
{

    /**
     * Store the idf for each token
     * @var array of floats
     */
    protected $idf = [];

    private $bestScore = 0;

    public function indexDocument($keywords)
    {
        foreach ($keywords as $word => $freq) {
            if (!isset($this->idf[$word])) {
                $this->idf[$word] = 1;
            }
            $this->idf[$word]++;
            if ($this->idf[$word] > $this->bestScore) {
                $this->bestScore = $this->idf[$word];
            }
        }
    }

    public function calculateIdf()
    {
        foreach ($this->idf as $word => &$score) {
            $score = log($this->bestScore / $score);
        }
    }

    public function getIdf($token = null)
    {
        if (!$token) {
            return $this->idf;
        }
        return $this->idf[$token];
    }

    public function getTfIdfs($tokenScores)
    {
        $tfIdfsScores = [];

        $bestTokenScore = current($tokenScores);

        foreach ($tokenScores as $token => $score) {
            $tfScore = 0.5 + 0.5 * $score / $bestTokenScore;
            $tfIdfsScores[$token] = $tfScore * $this->idf[$token];
        }
        return $tfIdfsScores;
    }
}

