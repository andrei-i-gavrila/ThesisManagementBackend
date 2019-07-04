<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\PaperRevision
 *
 * @property int $id
 * @property string $name
 * @property string $filepath
 * @property int $paper_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read mixed $filename
 * @property-read \App\Models\Paper $paper
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperRevision newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperRevision newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperRevision query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperRevision whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperRevision whereFilepath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperRevision whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperRevision whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperRevision wherePaperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperRevision whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\PaperMetrics $metrics
 */
class PaperRevision extends Model
{
    protected $fillable = ['filepath', 'name', 'paper_id'];

    protected $appends = ['filename'];
    protected $with = ['metrics'];


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

    public function metrics(): HasOne
    {
        return $this->hasOne(PaperMetrics::class, 'paper_revision_id');
    }
}
