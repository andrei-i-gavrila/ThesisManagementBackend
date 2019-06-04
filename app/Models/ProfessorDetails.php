<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfessorDetails extends Model
{
    protected $fillable = ['image_url', 'interest_domains', 'professor_id'];

    public function professor() {
        return $this->belongsTo(User::class, 'professor_id');
    }
}
