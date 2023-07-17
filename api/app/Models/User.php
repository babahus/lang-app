<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audit
 * @property-read int|null $audit_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CompilePhrase> $compilePhrase
 * @property-read int|null $compile_phrase_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Dictionary> $dictionary
 * @property-read int|null $dictionary_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Exercise> $exercises
 * @property-read int|null $exercises_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CompilePhrase> $compilePhrase
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Dictionary> $dictionary
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Exercise> $exercises
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @mixin \Eloquent
 */
final class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')->withTimestamps();
    }

    public function exercises(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Exercise::class,'accounts_exercises', 'exercise_id', 'account_id')->withPivot('solved', 'created_at', 'updated_at')->withTimestamps();
    }

    public function dictionary(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Dictionary::class,'user_exercise_type','user_id', 'exercise_id')->withPivotValue('type', [Dictionary::class])->withPivot('solved')->withTimestamps();
    }

    public function compilePhrase(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(CompilePhrase::class,'user_exercise_type','user_id', 'exercise_id')->withPivotValue('type', [CompilePhrase::class])->withPivot('solved')->withTimestamps();
    }

    public function audit(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Audit::class,'user_exercise_type','user_id', 'exercise_id')->withPivotValue('type', [Audit::class])->withPivot('solved')->withTimestamps();
    }

}
