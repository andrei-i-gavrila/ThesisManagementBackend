<?php

namespace App\Events\Students;

use App\Events\BaseEvent;
use App\Models\User;

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

    public function channel(): string
    {
        return 'students';
    }
}
