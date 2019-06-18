<?php


namespace App\Events\ExamSessions;


use App\Events\BaseEvent;
use App\Models\ExamSession;
use Illuminate\Broadcasting\PrivateChannel;

class ExamSessionDeleted extends BaseEvent
{

    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function channel(): string
    {
        return "examSessions";
    }
}