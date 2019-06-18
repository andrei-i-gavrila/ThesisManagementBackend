<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\FinalReview
 *
 * @property-read \App\Models\User $professor
 * @property-read \App\Models\User $student
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FinalReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FinalReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FinalReview query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $overall
 * @property int $grade_recommendation
 * @property int $structure
 * @property int $originality
 * @property int $literature_results
 * @property int $references
 * @property int $form
 * @property int $result_analysis
 * @property int $result_presentation
 * @property int $app_complexity
 * @property int $app_quality
 * @property string|null $observations
 * @property int $professor_id
 * @property int $student_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FinalReview whereAppComplexity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FinalReview whereAppQuality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FinalReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FinalReview whereForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FinalReview whereGradeRecommendation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FinalReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FinalReview whereLiteratureResults($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FinalReview whereObservations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FinalReview whereOriginality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FinalReview whereOverall($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FinalReview whereProfessorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FinalReview whereReferences($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FinalReview whereResultAnalysis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FinalReview whereResultPresentation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FinalReview whereStructure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FinalReview whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FinalReview whereUpdatedAt($value)
 */
class FinalReview extends Model
{
    protected $guarded = [];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function professor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professor_id');
    }
}
