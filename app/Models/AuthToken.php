<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;


/**
 * App\Models\AuthToken
 *
 * @property string $token
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $expiration_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuthToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuthToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuthToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuthToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuthToken whereExpirationTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuthToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuthToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuthToken whereUserId($value)
 * @mixin \Eloquent
 */
class AuthToken extends Model
{
    public $incrementing = false;
    protected $fillable = ["token", "user_id"];
    protected $primaryKey = 'token';
    protected $keyType = 'string';

    protected $dates = [
        'expiration_time',
    ];


    public static function createForUser($user, $expirationTime)
    {
        return static::create([
            'user_id' => $user->id,
            'token' => self::generateRandomToken(),
            'expiration_time' => $expirationTime
        ]);
    }

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
        $this->token = self::generateRandomToken();
        $this->save();
        return $this;
    }

    public function expired()
    {
        return $this->expiration_time != null ? $this->expiration_time->isPast() : false;
    }

}
