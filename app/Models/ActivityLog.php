<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_log';

    protected $fillable = [
        'type',
        'description',
        'user_id',
    ];

    // Relacionamento com usuários
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}


