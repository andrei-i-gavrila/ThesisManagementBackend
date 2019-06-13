<?php


namespace App\Traits;


use App\Models\Paper;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait UserCoordinator
{
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_professor', 'professor_id', 'student_id');
    }


}