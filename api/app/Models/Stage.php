<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\ExerciseResource;

/**
 * App\Models\Stage
 *
 * @property-read \App\Models\Course|null $course
 * @property-read \App\Models\Account|null $account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Exercise> $exercises
 * @property-read int|null $exercises_count
 * @method static \Illuminate\Database\Eloquent\Builder|Stage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stage query()
 * @mixin \Eloquent
 */
class Stage extends Model
{
    use HasFactory;

    protected $table = 'accounts_courses_stages';

    protected $fillable = [
        'course_id',
        'title',
        'description'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function account()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }
}
