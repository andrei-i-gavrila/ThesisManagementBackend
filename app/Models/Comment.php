<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Comment
 *
 * @property int $id
 * @property string $message
 * @property int $paper_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Comment newModelQuery()
 * @method static Builder|Comment newQuery()
 * @method static Builder|Comment query()
 * @method static Builder|Comment whereCreatedAt($value)
 * @method static Builder|Comment whereId($value)
 * @method static Builder|Comment whereMessage($value)
 * @method static Builder|Comment wherePaperId($value)
 * @method static Builder|Comment whereUpdatedAt($value)
 * @method static Builder|Comment whereUserId($value)
 * @mixin Eloquent
 * @property int $paper_revision_id
 * @method static Builder|Comment wherePaperRevisionId($value)
 * @property-read \App\Models\PaperRevision $paperRevision
 * @property-read \App\Models\User $user
 */
class Comment extends Model
{
    protected $fillable = ['message', 'user_id'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paperRevision(): BelongsTo
    {
        return $this->belongsTo(PaperRevision::class);
    }
}
