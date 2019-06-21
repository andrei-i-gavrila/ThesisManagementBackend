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
 * @property int $id
 * @property string $name
 * @property string $presentation_name
 * @property string $department
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Committee[] $committees
 * @property-read mixed $students
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GradingCategory[] $gradingCategories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Paper[] $papers
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExamSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExamSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExamSession query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExamSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExamSession whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExamSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExamSession whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExamSession wherePresentationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExamSession whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ExamSession extends Model
{
    protected $fillable = ['name', 'presentation_name', 'department'];

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
        return $this->papers()->whereHas('review')->with('student')->get()->map->student;
    }
    
    public function papers(): HasMany
    {
        return $this->hasMany(Paper::class);
    }

}
