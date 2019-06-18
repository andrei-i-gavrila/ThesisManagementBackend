<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\PaperRevision
 *
 * @property int $id
 * @property string $name
 * @property string $filepath
 * @property int $paper_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Comment[] $comments
 * @property-read mixed $filename
 * @property-read Paper $paper
 * @method static Builder|PaperRevision newModelQuery()
 * @method static Builder|PaperRevision newQuery()
 * @method static Builder|PaperRevision query()
 * @method static Builder|PaperRevision whereCreatedAt($value)
 * @method static Builder|PaperRevision whereFilepath($value)
 * @method static Builder|PaperRevision whereId($value)
 * @method static Builder|PaperRevision whereName($value)
 * @method static Builder|PaperRevision wherePaperId($value)
 * @method static Builder|PaperRevision whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PaperRevision extends Model
{
    protected $fillable = ['filepath', 'name', 'paper_id'];

    protected $appends = ['filename'];


    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }


    public function getFilenameAttribute()
    {
        return basename($this->filepath);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
