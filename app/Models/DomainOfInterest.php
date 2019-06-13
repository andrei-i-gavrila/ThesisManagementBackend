<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\DomainOfInterest
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|DomainOfInterest newModelQuery()
 * @method static Builder|DomainOfInterest newQuery()
 * @method static Builder|DomainOfInterest query()
 * @method static Builder|DomainOfInterest whereCreatedAt($value)
 * @method static Builder|DomainOfInterest whereId($value)
 * @method static Builder|DomainOfInterest whereName($value)
 * @method static Builder|DomainOfInterest whereUpdatedAt($value)
 * @mixin Eloquent
 */
class DomainOfInterest extends Model
{
    protected $fillable = ['name'];
}
