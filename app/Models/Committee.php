<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Committee
 *
 * @property int $id
 * @property int $exam_session_id
 * @property int|null $leader_id
 * @property int|null $member1_id
 * @property int|null $member2_id
 * @property int|null $secretary_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $examSession
 * @property-read User|null $leader
 * @property-read User|null $member1
 * @property-read User|null $member2
 * @property-read User|null $secretary
 * @method static Builder|Committee newModelQuery()
 * @method static Builder|Committee newQuery()
 * @method static Builder|Committee query()
 * @method static Builder|Committee whereCreatedAt($value)
 * @method static Builder|Committee whereExamSessionId($value)
 * @method static Builder|Committee whereId($value)
 * @method static Builder|Committee whereLeaderId($value)
 * @method static Builder|Committee whereMember1Id($value)
 * @method static Builder|Committee whereMember2Id($value)
 * @method static Builder|Committee whereSecretaryId($value)
 * @method static Builder|Committee whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $assignedStudents
 * @property-read mixed $members
 * @property-read mixed $members_id
 */
class Committee extends Model
{
    protected $guarded = [];

    protected $appends = ['members', 'members_id'];

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function member1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member1_id');
    }

    public function member2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member2_id');
    }

    public function secretary(): BelongsTo
    {
        return $this->belongsTo(User::class, 'secretary_id');
    }

    public function getMembersAttribute() {
        return collect([$this->member1, $this->member2])->filter();
    }

    public function getMembersIdAttribute() {
        return collect([$this->member1_id, $this->member2_id])->filter();
    }

    public function examSession(): BelongsTo
    {
        return $this->belongsTo(User::class, 'exam_session_id');
    }

    public function assignedStudents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'committee_student', 'committee_id', 'student_id')->orderBy('users.name');
    }
}
