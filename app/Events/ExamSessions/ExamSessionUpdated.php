<?php


namespace App\Events\ExamSessions;


use App\Events\BaseEvent;
use App\Models\ExamSession;
use Illuminate\Broadcasting\PrivateChannel;

class ExamSessionUpdated extends BaseEvent
{

    public $examSession;

    public function __construct(ExamSession $examSession)
    {
        $this->examSession = $examSession;
    }

    public function channel(): string
    {
        return "examSessions";
    }
}