<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Keyword
 *
 * @property int $id
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Keyword newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Keyword newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Keyword query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Keyword whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Keyword whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Keyword whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Keyword whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Keyword extends Model
{
    protected $fillable = ['text'];
}
