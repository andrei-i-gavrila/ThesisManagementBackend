<?php

namespace App\Events\Professors;

use App\Events\BaseEvent;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProfessorCreated extends BaseEvent
{
    /**
     * @var User
     */
    public $professor;

    public function __construct(User $user)
    {
        $this->professor = $user;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('professors');
    }
}
