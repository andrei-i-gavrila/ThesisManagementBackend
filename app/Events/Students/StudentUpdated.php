<?php

namespace App\Events\Students;

use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;

class StudentUpdated extends BaseEvent
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
        return new PrivateChannel('students' . $this->student->id);
    }
}
