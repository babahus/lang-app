<?php

namespace App\Models;

use App\Services\AuditService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Audit
 *
 * @property int $id
 * @property string|null $path
 * @property string|null $transcription
 * @property string|null $request_id
 * @property string|null $request_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Audit> $exercises
 * @property-read int|null $exercises_count
 * @method static \Illuminate\Database\Eloquent\Builder|Audit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Audit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Audit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Audit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audit wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audit whereRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audit whereRequestStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audit whereTranscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Audit extends Model
{
    use HasFactory;

    public function exercises()
    {
        return $this->belongsToMany(Audit::class,'user_exercise_type','exercise_id', 'exercise_id', 'id')->withPivotValue('type', Dictionary::class);
    }
}
