<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaperProfessorScore extends Model
{

    protected $fillable = ['paper_id', 'professor_id', 'value'];

    public function paper(): BelongsTo
    {
        return $this->belongsTo(Paper::class);
    }


    public function professor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professor_id');
    }


}
