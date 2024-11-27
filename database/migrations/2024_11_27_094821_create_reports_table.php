<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('reports', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('appointment_id')->unique(); // Relacionado à consulta
        $table->unsignedBigInteger('doctor_id'); // Médico que escreveu o relatório
        $table->text('description'); // Detalhes do relatório
        $table->timestamps();

        // Chaves estrangeiras
      
        $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
