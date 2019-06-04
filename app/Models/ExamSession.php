<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\ExamSession
 *
 * @property string $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ExamSession newModelQuery()
 * @method static Builder|ExamSession newQuery()
 * @method static Builder|ExamSession query()
 * @method static Builder|ExamSession whereCreatedAt($value)
 * @method static Builder|ExamSession whereId($value)
 * @method static Builder|ExamSession whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ExamSession extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['id'];

    public function createPermissions() {

    }
}
