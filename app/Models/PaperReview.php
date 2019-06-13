<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PaperReview
 *
 * @property int $id
 * @property int $final
 * @property string $review
 * @property int $paper_id
 * @property int $professor_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperReview query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperReview whereFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperReview wherePaperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperReview whereProfessorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperReview whereReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaperReview whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PaperReview extends Model
{
    protected $fillable = ['final', 'review', 'paper_id', 'professor_id'];
}
