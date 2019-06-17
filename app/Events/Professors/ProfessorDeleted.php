<?php

namespace App\Events\Professors;

use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;

class ProfessorDeleted extends BaseEvent
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
        return new PrivateChannel('professors');
    }
}
