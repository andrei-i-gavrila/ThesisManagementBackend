<?php

namespace App\Models;

use App\Traits\UserCoordinator;
use App\Traits\UserProfessor;
use App\Traits\UserStudent;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @mixin Eloquent
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @property int $activated
 * @property int $verified
 * @method static Builder|User whereActivated($value)
 * @method static Builder|User whereVerified($value)
 * @property-read Collection|Permission[] $permissions
 * @property-read Collection|Role[] $roles
 * @method static Builder|User permission($permissions)
 * @method static Builder|User role($roles, $guard = null)
 * @property-read bool $is_coordinator
 * @property-read bool $is_evaluator
 * @property-read ProfessorDetails $professorDetails
 * @property-read Collection|User[] $students
 * @property-read Collection|Paper[] $papers
 * @property-read Collection|DomainOfInterest[] $keywords
 * @method static Builder|User professor()
 * @property-read Collection|DomainOfInterest[] $domainsOfInterest
 * @property-read \App\Models\Paper $paper
 * @property-read \App\Models\FinalReview $review
 */
class User extends Authenticatable
{
    use Notifiable, HasRoles;
    use UserProfessor;
    use UserStudent;

    protected $guard_name = 'api';
    protected $fillable = ['email', 'name', 'activated'];
    protected $hidden = ['password'];

    public function getNameAttribute($name)
    {
        return $name ?? $this->email;
    }




}
