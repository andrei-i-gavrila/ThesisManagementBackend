<?php


namespace App\Traits;


use App\Models\Paper;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait UserStudent
{
    public function papers(): HasMany
    {
        return $this->hasMany(Paper::class, 'student_id');
    }
}