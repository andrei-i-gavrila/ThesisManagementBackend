<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\ProfessorDetails
 *
 * @property int $id
 * @property string $image_url
 * @property string $interest_domains
 * @property int $professor_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $professor
 * @method static Builder|ProfessorDetails newModelQuery()
 * @method static Builder|ProfessorDetails newQuery()
 * @method static Builder|ProfessorDetails query()
 * @method static Builder|ProfessorDetails whereCreatedAt($value)
 * @method static Builder|ProfessorDetails whereId($value)
 * @method static Builder|ProfessorDetails whereImageUrl($value)
 * @method static Builder|ProfessorDetails whereInterestDomains($value)
 * @method static Builder|ProfessorDetails whereProfessorId($value)
 * @method static Builder|ProfessorDetails whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ProfessorDetails extends Model
{
    protected $fillable = ['image_url', 'interest_domains', 'professor_id'];

    public function professor() {
        return $this->belongsTo(User::class, 'professor_id');
    }
}
