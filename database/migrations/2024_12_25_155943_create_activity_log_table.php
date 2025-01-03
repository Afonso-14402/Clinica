<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogTable extends Migration
{
    public function up()
    {
        Schema::create('activity_log', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Tipo de atividade (ex: added, updated, removed)
            $table->text('description'); // Descrição da atividade
            $table->unsignedBigInteger('user_id')->nullable(); // Usuário que realizou a ação
            $table->timestamps();

            // Chave estrangeira para a tabela de usuários
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_log');
    }
}
