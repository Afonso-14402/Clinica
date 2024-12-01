<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); // Cria a coluna 'id' como primary key
            $table->string('role'); // Coluna de texto para 'role'
            $table->boolean('marcacao')->default(false); // Coluna booleana para 'marcacao'
            $table->boolean('criar_user')->default(false); // Coluna booleana para 'criar_user'
            $table->boolean('relatorio')->default(false); // Coluna booleana para 'relatorio'
            $table->timestamps(); // Cria 'created_at' e 'updated_at'
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role');
    }
};
