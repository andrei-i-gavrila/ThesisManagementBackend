<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\ExamSession
 *
 * @property string $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ExamSession newModelQuery()
 * @method static Builder|ExamSession newQuery()
 * @method static Builder|ExamSession query()
 * @method static Builder|ExamSession whereCreatedAt($value)
 * @method static Builder|ExamSession whereId($value)
 * @method static Builder|ExamSession whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|GradingCategory[] $gradingCategories
 * @property string $name
 * @method static Builder|ExamSession whereName($value)
 * @property-read Collection|Committee[] $committees
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $students
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FinalReview[] $finalReviews
 */
class ExamSession extends Model
{
    protected $fillable = ['name'];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function gradingCategories(): HasMany
    {
        return $this->hasMany(GradingCategory::class)->whereNull('parent_category_id')->orderBy('order');
    }

    public function committees(): HasMany
    {
        return $this->hasMany(Committee::class);
    }

    public function getStudentsAttribute()
    {
        return $this->finalReviews()->with('student')->get()->map->student;
    }

    public function finalReviews(): HasMany
    {
        return $this->hasMany(FinalReview::class);
    }

}
