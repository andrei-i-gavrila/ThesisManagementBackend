<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Paper
 *
 * @property int $id
 * @property string $name
 * @property string $filepath
 * @property int $student_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $student
 * @method static Builder|Paper newModelQuery()
 * @method static Builder|Paper newQuery()
 * @method static Builder|Paper query()
 * @method static Builder|Paper whereCreatedAt($value)
 * @method static Builder|Paper whereFilepath($value)
 * @method static Builder|Paper whereId($value)
 * @method static Builder|Paper whereName($value)
 * @method static Builder|Paper whereStudentId($value)
 * @method static Builder|Paper whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read mixed $filename
 * @property-read Collection|Comment[] $comments
 * @property-read PaperReview $review
 */
class Paper extends Model
{
    protected $fillable = ['filepath', 'name', 'student_id'];

    protected $appends = ['filename'];


    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }


    public function getFilenameAttribute()
    {
        return basename($this->filepath);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(PaperReview::class);
    }
}
