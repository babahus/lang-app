<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CompilePhrase
 *
 * @property int $id
 * @property string $phrase
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ExerciseType|null $exerciseType
 * @method static \Illuminate\Database\Eloquent\Builder|CompilePhrase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompilePhrase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompilePhrase query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompilePhrase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompilePhrase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompilePhrase wherePhrase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompilePhrase whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CompilePhrase extends Model
{
    use HasFactory;

    public function exerciseType(){
        return $this->belongsTo(ExerciseType::class, 'exercise_id', 'id');
    }
}
