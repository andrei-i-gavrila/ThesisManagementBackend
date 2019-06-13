<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\PaperReview
 *
 * @property int $id
 * @property int $final
 * @property string $review
 * @property int $paper_id
 * @property int $professor_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|PaperReview newModelQuery()
 * @method static Builder|PaperReview newQuery()
 * @method static Builder|PaperReview query()
 * @method static Builder|PaperReview whereCreatedAt($value)
 * @method static Builder|PaperReview whereFinal($value)
 * @method static Builder|PaperReview whereId($value)
 * @method static Builder|PaperReview wherePaperId($value)
 * @method static Builder|PaperReview whereProfessorId($value)
 * @method static Builder|PaperReview whereReview($value)
 * @method static Builder|PaperReview whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PaperReview extends Model
{
    protected $fillable = ['final', 'review', 'paper_id', 'professor_id'];
}
