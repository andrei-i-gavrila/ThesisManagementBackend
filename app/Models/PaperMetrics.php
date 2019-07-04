<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PaperMetrics
 *
 * @property int $id
 * @property int $word_count
 * @property int $page_count
 * @property int $char_count
 * @property int $paper_revision_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PaperRevision $revision
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperMetrics newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperMetrics newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperMetrics query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperMetrics whereCharCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperMetrics whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperMetrics whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperMetrics wherePageCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperMetrics wherePaperRevisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperMetrics whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperMetrics whereWordCount($value)
 * @mixin \Eloquent
 */
class PaperMetrics extends Model
{
    protected $fillable = ['char_count', 'page_count', 'word_count'];

    public function revision()
    {
        return $this->belongsTo(PaperRevision::class, 'paper_revision_id');
    }
}
