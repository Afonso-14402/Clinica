<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_user_id',
        'doctor_user_id',
        'specialties_id',
        'status_id',
        'appointment_date_time',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_user_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_user_id');
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'specialties_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function report()
    {
        return $this->hasOne(Report::class);
    }
}

