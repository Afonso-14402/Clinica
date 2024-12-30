<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'status',
        
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function showForm()
{
    $medicos = User::whereHas('role', function ($query) {
        $query->where('role', 'medico'); // Filtra apenas médicos
    })->get();

    return view('sua-view', compact('medicos'));
}

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function specialties()
    {
        return $this->belongsToMany(Specialty::class, 'user_specialties', 'user_id', 'specialty_id')->withTimestamps();
    }

    
    public function doctorAgenda()
    {
        return $this->hasMany(UserDoctorAgenda::class, 'doctor_id');
    }

    public function appointmentsAsDoctor()
    {
        return $this->hasMany(Appointment::class, 'doctor_user_id');
    }

    public function appointmentsAsPatient()
    {
        return $this->hasMany(Appointment::class, 'patient_user_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'doctor_report_user_id');
    }


    public function specialty()
{
    return $this->belongsTo(Specialty::class, 'users_specialties', 'user_id', 'specialty_id');
}


public function UserDoctorAgenda()
    {
        return $this->hasMany(UserDoctorAgenda::class);
    }
    
}
