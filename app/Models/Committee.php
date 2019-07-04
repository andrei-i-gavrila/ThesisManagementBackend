<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Paper[] $assignedPapers
 * @property-read \App\Models\User $examSession
 * @property-read mixed $members
 * @property-read mixed $members_id
 * @property-read \App\Models\User|null $leader
 * @property-read \App\Models\User|null $member1
 * @property-read \App\Models\User|null $member2
 * @property-read \App\Models\User|null $secretary
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Committee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Committee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Committee ofProfessor(\App\Models\User $professor, \App\Models\ExamSession $examSession)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Committee query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Committee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Committee whereExamSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Committee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Committee whereLeaderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Committee whereMember1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Committee whereMember2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Committee whereSecretaryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Committee whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read mixed $all_member_ids
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

    public function getMembersAttribute()
    {
        return collect([$this->member1, $this->member2])->filter();
    }

    public function getMembersIdAttribute()
    {
        return collect([$this->member1_id, $this->member2_id])->filter();
    }

    public function examSession(): BelongsTo
    {
        return $this->belongsTo(User::class, 'exam_session_id');
    }

    public function assignedPapers(): HasMany
    {
        return $this->hasMany(Paper::class);
    }

    public function scopeOfProfessor($query, User $professor, ExamSession $examSession)
    {
        return $query->where('exam_session_id', $examSession->id)->where(function ($query) use ($professor) {
            return $query->orWhere([
                'member1_id' => $professor->id,
                'member2_id' => $professor->id,
                'leader_id' => $professor->id
            ]);
        });
    }

    public function getAllMemberIdsAttribute()
    {
        return collect([$this->leader_id, $this->member1_id, $this->member2_id]);
    }

}
