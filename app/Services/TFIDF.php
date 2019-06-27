<?php

namespace App\Services;

/**
 * An implementation of the TF Idf Algorithm
 * @author yooper (yooper)
 */
class TFIDF
{
    /**
     * Default mode of weighting uses frequency
     */
    const FREQUENCY_MODE = 1;
    const BOOLEAN_MODE = 2;
    const LOGARITHMIC_MODE = 3;
    const AUGMENTED_MODE = 4;


    /**
     * Store the idf for each token
     * @var array of floats
     */
    protected $idf = array();

    public function __construct($documents)
    {
        $this->buildIndex($documents);
    }

    protected function buildIndex($documents)
    {
        foreach ($documents as $document) {
            foreach ($document as $key => $freq) {
                if (!isset($this->idf[$key])) {
                    $this->idf[$key] = 1;
                }
                $this->idf[$key]++;
            }
        }

        $count = count($documents);
        foreach ($this->idf as $key => &$value) {
            $value = log($count / $value);
        }
    }

    public function getIdf($token = null)
    {
        if (!$token) {
            return $this->idf;
        }
        return $this->idf[$token];
    }


}

