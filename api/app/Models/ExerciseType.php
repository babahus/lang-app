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
 * @method static \Illuminate\Database\Eloquent\Builder|ExerciseType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExerciseType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExerciseType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExerciseType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExerciseType whereExerciseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExerciseType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExerciseType whereUserId($value)
 * @mixin \Eloquent
 */
class ExerciseType extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class, 'exercise_types', 'exercise_id', 'user_id');
    }

    public function phrasesExercise()
    {
        return $this->hasMany(CompilePhrase::class, 'exercise_id', 'id');
    }

    public function dictionaryExercise()
    {
        return $this->hasMany(Dictionary::class, 'exercise_id', 'id');
    }
}
