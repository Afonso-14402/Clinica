<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyDoctor extends Model
{
    use HasFactory;

    // Defina a tabela, caso o nome da tabela seja diferente de "family_doctors"
    protected $table = 'family_doctor';

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'patient_user_id',
        'doctor_user_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Relacionamento com o modelo User (paciente).
     * Um paciente está associado a um médico de família.
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_user_id', 'id');
    }

    /**
     * Relacionamento com o modelo User (médico).
     * Um médico pode estar associado como médico de família de um paciente.
     */
    public function doctor()
{
    return $this->belongsTo(User::class, 'doctor_user_id');
}
}
