<?php


namespace App\Traits;


use App\Models\FinalReview;
use App\Models\Paper;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait UserStudent
{
    public function paper(): HasMany
    {
        return $this->hasMany(Paper::class, 'student_id');
    }

    public function review(): HasMany
    {
        return $this->hasMany(FinalReview::class, 'student_id');
    }
}