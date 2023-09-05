<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static whereId(int $id)
 */
class PairExercise extends Model
{
    use HasFactory;

    protected $table = 'pair_exercises';

    protected $fillable = [
        'correct_pair_json',
    ];

    public function exercise()
    {
        return $this->morphOne(Exercise::class, 'exercise', 'exercise_type', 'exercise_id');
    }
}
