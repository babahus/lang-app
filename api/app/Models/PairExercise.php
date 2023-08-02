<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PairExercise extends Model
{
    use HasFactory;

    protected $table = 'pair_exercises';

    protected $fillable = [
        'correct_pair_json',
    ];

    public function exercises()
    {
        return $this->belongsToMany(PairExercise::class,'user_exercise_type','exercise_id', 'exercise_id', 'id')
            ->withPivotValue('type', PairExercise::class)
            ->withPivot('solved');
    }
}
