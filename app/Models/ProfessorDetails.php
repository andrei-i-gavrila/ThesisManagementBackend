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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $professor
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProfessorDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProfessorDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProfessorDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProfessorDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProfessorDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProfessorDetails whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProfessorDetails whereInterestDomains($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProfessorDetails whereProfessorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProfessorDetails whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProfessorDetails extends Model
{
    protected $fillable = ['image_url', 'interest_domains', 'professor_id'];

    public function professor() {
        return $this->belongsTo(User::class, 'professor_id');
    }
}
