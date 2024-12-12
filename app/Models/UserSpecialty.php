<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSpecialty extends Model
{
    use HasFactory;

    protected $table = 'user_specialties';

    protected $fillable = [
        'user_id',
        'specialty_id',
    ];
}
