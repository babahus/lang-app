<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Dictionary
 *
 * @property int $id
 * @property mixed $dictionary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Dictionary> $exercises
 * @property-read int|null $exercises_count
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary whereDictionary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary whereUpdatedAt($value)
 * @mixin \Eloquent
 */
final class Dictionary extends Model
{
    use HasFactory;

    protected $table = 'dictionaries';

    protected $fillable = [
        'dictionary'
    ];

    public function exercise()
    {
        return $this->morphOne(Exercise::class, 'exercise', 'exercise_type', 'exercise_id');
    }
}
