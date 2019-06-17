<?php

namespace App\Events\Students;

use App\Events\BaseEvent;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class StudentCreated extends BaseEvent
{
    /**
     * @var User
     */
    public $student;

    public function __construct(User $user)
    {
        $this->student = $user;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('students');
    }
}
