<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'role',
        'marcacao',
        'criar_user',
        'relatorio',
    ];
    // Relacionamento com User
    public function users()
    {
        return $this->hasMany(User::class);
    }
}

