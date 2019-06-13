<?php


namespace App\Traits;


use App\Enums\Roles;
use App\Models\Keyword;
use App\Models\ProfessorDetails;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait UserProfessor
{
    public function scopeProfessor($query)
    {
        return $query->role(Roles::PROFESSOR);
    }

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

    public function keywords(): BelongsToMany
    {
        return $this->belongsToMany(Keyword::class, 'professor_keyword', 'professor_id', 'keyword_id');
    }
}