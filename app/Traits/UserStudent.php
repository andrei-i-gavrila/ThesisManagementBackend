<?php


namespace App\Traits;


use App\Models\FinalReview;
use App\Models\Paper;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait UserStudent
{
    public function paper(): HasOne
    {
        return $this->hasOne(Paper::class, 'student_id');
    }

    public function review(): HasOne
    {
        return $this->hasOne(FinalReview::class, 'student_id');
    }
}