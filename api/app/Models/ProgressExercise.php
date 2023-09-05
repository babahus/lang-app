<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressExercise extends Model
{
    use HasFactory;

    protected $table = 'progress_exercise';

    protected $fillable = [
        'accounts_exercise_id',
        'account_id',
        'solved',
        'solved_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'account_id');
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class, 'accounts_exercise_id');
    }

}
