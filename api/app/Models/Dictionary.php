<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Dictionary
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dictionary whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Dictionary extends Model
{
    use HasFactory;
}
