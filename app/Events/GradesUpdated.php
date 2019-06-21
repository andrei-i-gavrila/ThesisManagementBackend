<?php


namespace App\Events;


use App\Models\Paper;

class GradesUpdated extends BaseEvent
{

    /**
     * @var Paper
     */
    private $paper;
    public $grades;
    public function __construct(Paper $paper)
    {
        $this->paper = $paper;
        $this->grades = $paper->keyedGrades;
    }

    public function channel(): string
    {
        return "grades.{$this->paper->id}";
    }
}