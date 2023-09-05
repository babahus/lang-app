<?php

namespace App\Models;

use App\Enums\ExercisesTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CompilePhrase
 *
 * @property int $id
 * @property string $phrase
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CompilePhrase> $exercises
 * @property-read int|null $exercises_count
 * @method static \Illuminate\Database\Eloquent\Builder|CompilePhrase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompilePhrase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompilePhrase query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompilePhrase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompilePhrase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompilePhrase wherePhrase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompilePhrase whereUpdatedAt($value)
 * @mixin \Eloquent
 */
final class CompilePhrase extends Model
{
    use HasFactory;

    protected $table = 'compile_phrases';

    protected $fillable = [
      'phrase'
    ];

    public function exercise()
    {
        return $this->morphOne(Exercise::class, 'exercise', 'exercise_type', 'exercise_id');
    }

    public static function createIfNotExist(string $phrase): CompilePhrase
    {
        $exercise = self::where('phrase', $phrase)->first();

        if (!$exercise) {
            $exercise = self::create(['phrase' => $phrase]);
        }

        return $exercise;
    }
}
