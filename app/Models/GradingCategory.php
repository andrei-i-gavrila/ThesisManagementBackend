<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\GradingCategory
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float $points
 * @property int $order
 * @property int|null $parent_category_id
 * @property int $exam_session_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ExamSession $examSession
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GradingCategory[] $subcategories
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GradingCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GradingCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GradingCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GradingCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GradingCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GradingCategory whereExamSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GradingCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GradingCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GradingCategory whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GradingCategory whereParentCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GradingCategory wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GradingCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GradingCategory extends Model
{
    protected $fillable = ['name', 'description', 'points', 'order', 'parent_category_id', 'id'];

    public function subcategories(): HasMany
    {
        return $this->hasMany(GradingCategory::class, 'parent_category_id')->orderBy('order');
    }

    public function getPointsAttribute($points)
    {
        return floatval($points);
    }

    public function examSession(): BelongsTo
    {
        return $this->belongsTo(ExamSession::class);
    }
}
