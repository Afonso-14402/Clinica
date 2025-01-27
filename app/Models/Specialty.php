<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

 
class Specialty extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_specialties', 'specialty_id', 'user_id');
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'specialties_id');
    }

 


    
}
