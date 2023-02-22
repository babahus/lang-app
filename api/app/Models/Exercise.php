<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ExerciseType
 *
 * @property int $exercise_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Dictionary[] $dictionaryExercise
 * @property-read int|null $dictionary_exercise_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CompilePhrase[] $phrasesExercise
 * @property-read int|null $phrases_exercise_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise query()
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereExerciseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereUserId($value)
 * @mixin \Eloquent
 * @property int $solved
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereIsDone($value)
 * @property-read \App\Models\User|null $user
 * @property int $id
 * @property string $exercise_type
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereExerciseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereId($value)
 * @property string $type
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereType($value)
 */
class Exercise extends Model
{
    use HasFactory;

    protected $table = 'user_exercise_type';

    protected $fillable = [
      'exercise_id',
      'user_id'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class,'user_exercise_type','exercise_id', 'user_id')->withTimestamps();
    }

}
