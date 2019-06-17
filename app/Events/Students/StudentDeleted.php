<?php

namespace App\Events\Students;

use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;

class StudentDeleted extends BaseEvent
{
    /**
     * @var User
     */
    public $id;

    public function __construct($user)
    {
        $this->id = $user;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('students');
    }
}
