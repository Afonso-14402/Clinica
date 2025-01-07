<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamilyDoctorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('family_doctor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_user_id');
            $table->unsignedBigInteger('doctor_user_id');
            $table->timestamps();

            // Definindo as chaves estrangeiras
            $table->foreign('patient_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('doctor_user_id')->references('id')->on('users')->onDelete('cascade');

            // Adicionando uma restrição de unicidade para evitar duplicações
            $table->unique(['patient_user_id', 'doctor_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('family_doctor');
    }
}
