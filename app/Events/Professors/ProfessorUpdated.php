<?php

namespace App\Events\Professors;

use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;

class ProfessorUpdated extends BaseEvent
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
        return new PrivateChannel('professors' . $this->professor->id);
    }
}
