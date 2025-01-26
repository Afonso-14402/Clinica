<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DadosPessoais extends Model
{
    protected $table = 'dados_pessoais';
    
    protected $fillable = [
        'user_id',
        'data_nascimento',
        'nif',
        'sexo',
        'estado_civil',
        'codigo_postal',
        'morada',
        'numero',
        'freguesia',
        'concelho',
        'distrito',
        'grupo_sanguineo',
        'peso',
        'altura'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 