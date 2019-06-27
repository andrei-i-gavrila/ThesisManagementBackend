<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\PaperProfessorScore
 *
 * @property int $id
 * @property float $value
 * @property int $paper_id
 * @property int $professor_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Paper $paper
 * @property-read User $professor
 * @method static Builder|PaperProfessorScore newModelQuery()
 * @method static Builder|PaperProfessorScore newQuery()
 * @method static Builder|PaperProfessorScore query()
 * @method static Builder|PaperProfessorScore whereCreatedAt($value)
 * @method static Builder|PaperProfessorScore whereId($value)
 * @method static Builder|PaperProfessorScore wherePaperId($value)
 * @method static Builder|PaperProfessorScore whereProfessorId($value)
 * @method static Builder|PaperProfessorScore whereUpdatedAt($value)
 * @method static Builder|PaperProfessorScore whereValue($value)
 * @mixin Eloquent
 */
class PaperProfessorScore extends Model
{

    protected $fillable = ['paper_id', 'professor_id', 'value'];

    public function paper(): BelongsTo
    {
        return $this->belongsTo(Paper::class);
    }


    public function professor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professor_id');
    }


}
