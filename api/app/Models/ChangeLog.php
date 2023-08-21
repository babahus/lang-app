<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeLog extends Model
{
    use HasFactory;

    protected $table = 'change_logs';

    protected $fillable = [
        'model_name',
        'record_id',
        'user_id',
        'old_values',
        'new_values',
        'operation'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
