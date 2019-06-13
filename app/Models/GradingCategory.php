<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\GradingCategory
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property float $points
 * @property int|null $parent_category_id
 * @property int $exam_session_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|GradingCategory[] $subcategories
 * @method static Builder|GradingCategory newModelQuery()
 * @method static Builder|GradingCategory newQuery()
 * @method static Builder|GradingCategory query()
 * @method static Builder|GradingCategory whereCreatedAt($value)
 * @method static Builder|GradingCategory whereDescription($value)
 * @method static Builder|GradingCategory whereExamSessionId($value)
 * @method static Builder|GradingCategory whereId($value)
 * @method static Builder|GradingCategory whereName($value)
 * @method static Builder|GradingCategory whereParentCategoryId($value)
 * @method static Builder|GradingCategory wherePoints($value)
 * @method static Builder|GradingCategory whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $order
 * @method static Builder|GradingCategory whereOrder($value)
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
}
