<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDoctorAgenda extends Model
{
    use HasFactory;

    protected $table = 'user_doctor_agenda'; 
    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'doctor_user_id', 
        'appointment_date_time'
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
    