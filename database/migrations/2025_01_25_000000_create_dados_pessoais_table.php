<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dados_pessoais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('data_nascimento')->nullable();
            $table->string('nif', 9)->unique()->nullable();
            $table->enum('sexo', ['Masculino', 'Feminino'])->nullable();
            $table->enum('estado_civil', ['Solteiro(a)', 'Casado(a)', 'Divorciado(a)', 'Viúvo(a)'])->nullable();
            $table->string('codigo_postal', 8)->nullable();
            $table->string('morada', 255)->nullable();
            $table->string('numero', 20)->nullable();
            $table->string('freguesia', 100)->nullable();
            $table->string('concelho', 100)->nullable();
            $table->string('distrito', 100)->nullable();
            $table->enum('grupo_sanguineo', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->decimal('peso', 5, 2)->nullable();
            $table->decimal('altura', 3, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dados_pessoais');
    }
}; 