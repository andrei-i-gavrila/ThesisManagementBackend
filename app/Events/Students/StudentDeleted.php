<?php

namespace App\Events\Students;

use App\Events\BaseEvent;
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

    public function channel(): string
    {
        return 'students';
    }
}
