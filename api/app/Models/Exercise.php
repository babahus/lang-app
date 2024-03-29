<?php

namespace App\Models;

use App\Enums\ExercisesResourcesTypes;
use App\Enums\ExercisesTypes;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Exercise
 *
 * @property int $id
 * @property int $account_id
 * @property int $exercise_id
 * @property string $solved
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise query()
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereExerciseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereSolved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereAccountId($value)
 * @property-read \App\Models\Stage|null $stages
 * @mixin Eloquent
 */
final class Exercise extends Model
{
    use HasFactory;

    protected $table = 'accounts_exercises';

    protected $fillable = [
        'account_id',
        'course_id',
        'stage_id',
        'exercise_id',
        'exercise_type',
    ];

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'account_id');
    }

    public function stages(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }

    public function dictionary(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Dictionary::class, 'exercise_id');
    }

    public function compilePhrase()
    {
        return $this->morphTo('exercise', 'exercise_type', 'exercise_id');
    }

    public function audit()
    {
        return $this->morphTo('exercise', 'exercise_type', 'exercise_id');
    }

    public function pairExercise()
    {
        return $this->morphTo('exercise', 'exercise_type', 'exercise_id');
    }

    public function pictureExercise()
    {
        return $this->morphTo('exercise', 'exercise_type', 'exercise_id');
    }

    public function sentence()
    {
        return $this->morphTo('exercise', 'exercise_type', 'exercise_id');
    }

    public function progressExercises(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProgressExercise::class, 'accounts_exercise_id', 'id');
    }
}
