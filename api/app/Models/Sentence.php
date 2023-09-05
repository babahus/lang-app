<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static whereId(int $id)
 */
class Sentence extends Model
{
    use HasFactory;

    protected $table = 'sentence';

    protected $fillable = [
        'sentence_with_gaps',
        'correct_answers_json'
    ];

    public function exercise()
    {
        return $this->morphOne(Exercise::class, 'exercise', 'exercise_type', 'exercise_id');
    }
}
