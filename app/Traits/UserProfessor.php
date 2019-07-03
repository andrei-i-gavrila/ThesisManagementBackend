<?php


namespace App\Traits;


use App\Enums\Roles;
use App\Models\DomainOfInterest;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait UserProfessor
{
    public function scopeProfessor($query)
    {
        return $query->role(Roles::PROFESSOR);
    }

    public function domainsOfInterest(): BelongsToMany
    {
        return $this->belongsToMany(DomainOfInterest::class, 'professor_doi', 'professor_id', 'doi_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_professor', 'professor_id', 'student_id');
    }
}