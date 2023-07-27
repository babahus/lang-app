<?php

namespace App\Models;

use App\Enums\ExercisesTypes;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Exercise
 *
 * @property int $id
 * @property int $user_id
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
 * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereUserId($value)
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

    public function users()
    {
        return $this->belongsToMany(User::class, 'accounts_exercises', 'exercise_id', 'account_id')
            ->withTimestamps();
    }

    public function stages()
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }

    public function dictionary()
    {
        return $this->belongsTo(Dictionary::class, 'exercise_id');
    }

    public function compilePhrase()
    {
        return $this->belongsTo(CompilePhrase::class, 'exercise_id');
    }

    public function audit()
    {
        return $this->belongsTo(Audit::class, 'exercise_id');
    }
}
