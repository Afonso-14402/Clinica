<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('specialties_id')->constrained('specialties')->onDelete('cascade');
            $table->foreignId('status_id')->constrained('status')->onDelete('cascade');
            $table->dateTime('appointment_date_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};


