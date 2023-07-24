<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Course
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course query()
 * @mixin \Eloquent
 */
class Course extends Model {

    use HasFactory;

    protected $fillable = [
        'account_id','title','description','price'
    ];

    protected $table = 'accounts_courses';

    public function stages()
    {
        return $this->hasMany(Stage::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'account_courses_students', 'course_id', 'student_id')
            ->withPivot('added_at', 'purchased_at')
            ->withTimestamps();
    }
}
