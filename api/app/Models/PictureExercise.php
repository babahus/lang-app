<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static whereId(int $id)
 */
class PictureExercise extends Model
{
    use HasFactory;

    protected $table = 'picture_exercises';

    protected $fillable = [
        'image_path',
        'option_json'
    ];

    public function exercises()
    {
        return $this->belongsToMany(PictureExercise::class,'user_exercise_type','exercise_id', 'exercise_id', 'id')
            ->withPivotValue('type', PictureExercise::class)
            ->withPivot('solved');
    }
}
