<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Dictionary
 *
 * @property int $id
 * @property int $dictionary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary whereDictionary($value)
 * @property-read \App\Models\ExerciseUser|null $exerciseType
 * @property int $user_id
 * @property int $exercise_id
 * @property string $exercise_type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary whereExerciseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary whereExerciseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary whereUserId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Exercise[] $exercises
 * @property-read int|null $exercises_count
 */
class Dictionary extends Model
{
    use HasFactory;

    protected $fillable = [
        'dictionary'
    ];

    public function exercises()
    {
        return $this->belongsToMany(Dictionary::class,'user_exercise_type','exercise_id', 'id')->withPivotValue('type', Dictionary::class);
    }
}
