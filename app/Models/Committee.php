<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Committee extends Model
{
    protected $guarded = [];

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function member1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member1_id');
    }

    public function member2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member2_id');
    }

    public function secretary(): BelongsTo
    {
        return $this->belongsTo(User::class, 'secretary_id');
    }

    public function examSession(): BelongsTo
    {
        return $this->belongsTo(User::class, 'exam_session_id');
    }
}
