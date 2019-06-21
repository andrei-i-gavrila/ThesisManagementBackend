<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Paper
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $link
 * @property int $student_id
 * @property int $exam_session_id
 * @property int|null $committee_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Committee|null $committee
 * @property-read \App\Models\ExamSession $examSession
 * @property-read \App\Models\PaperRevision $finalRevision
 * @property-read mixed $keyed_grades
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Grade[] $grades
 * @property-read \App\Models\FinalReview $review
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PaperRevision[] $revisions
 * @property-read \App\Models\User $student
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Paper newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Paper newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Paper query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Paper whereCommitteeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Paper whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Paper whereExamSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Paper whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Paper whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Paper whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Paper whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Paper whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Paper extends Model
{
    protected $fillable = ['name', 'student_id', 'link', 'exam_session_id', 'committee_id'];


    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function examSession(): BelongsTo
    {
        return $this->belongsTo(ExamSession::class);
    }


    public function review(): HasOne
    {
        return $this->hasOne(FinalReview::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(PaperRevision::class);
    }

    public function finalRevision(): HasOne
    {
        return $this->hasOne(PaperRevision::class)->latest()->limit(1);
    }

    public function committee(): BelongsTo
    {
        return $this->belongsTo(Committee::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function getKeyedGradesAttribute() {
        return $this->grades->groupBy('professor_id')->map(function ($gradesFromProf) {
            return $gradesFromProf->keyBy->category_id;
        });
    }
}
