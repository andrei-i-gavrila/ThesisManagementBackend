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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DomainOfInterest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DomainOfInterest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DomainOfInterest query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DomainOfInterest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DomainOfInterest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DomainOfInterest whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DomainOfInterest whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DomainOfInterest extends Model
{
    protected $fillable = ['name'];
}
