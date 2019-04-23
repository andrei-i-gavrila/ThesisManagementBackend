<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\AuthToken
 *
 * @property-read User $user
 * @method static Builder|AuthToken newModelQuery()
 * @method static Builder|AuthToken newQuery()
 * @method static Builder|AuthToken query()
 * @mixin Eloquent
 */
class AuthToken extends Model
{
    protected $fillable = ["token", "user_id"];

    /**
     * @return string
     */
    private static function generateRandomToken(): string
    {
        return Str::random(32);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function refreshToken()
    {
        $this->update([
            'token' => self::generateRandomToken()
        ]);
        return $this;
    }

    public static function createForUser($user)
    {
        return static::create([
            'user_id' => $user->id,
            'token' => self::generateRandomToken()
        ]);
    }

}
