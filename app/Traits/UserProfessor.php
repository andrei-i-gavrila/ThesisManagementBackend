<?php


namespace App\Traits;


use App\Enums\Roles;
use App\Models\ProfessorDetails;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait UserProfessor
{
    public function getIsEvaluatorAttribute(): bool
    {
        return $this->hasRole(Roles::EVALUATOR);
    }

    public function getIsCoordinatorAttribute(): bool
    {
        return $this->hasRole(Roles::COORDINATOR);
    }

    public function professorDetails(): HasOne
    {
        return $this->hasOne(ProfessorDetails::class, 'professor_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_professor', 'professor_id', 'student_id');
    }
}