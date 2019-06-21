<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Grade
 *
 * @property int $id
 * @property int|null $value
 * @property int $paper_id
 * @property int $category_id
 * @property int $professor_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\GradingCategory $category
 * @property-read \App\Models\Paper $paper
 * @property-read \App\Models\User $professor
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grade query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grade whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grade wherePaperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grade whereProfessorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grade whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Grade whereValue($value)
 * @mixin \Eloquent
 */
class Grade extends Model
{
    protected $fillable = ['value', 'paper_id', 'professor_id', 'category_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(GradingCategory::class, 'category_id');
    }

    public function paper(): BelongsTo
    {
        return $this->belongsTo(Paper::class, 'paper_id');
    }

    public function professor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professor_id');
    }
}
