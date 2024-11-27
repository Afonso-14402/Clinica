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
        Schema::table('users', function (Blueprint $table) {
            // Verificar se a coluna já existe antes de adicionar
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['external', 'admin', 'doctor', 'nurse'])->default('external')->after('active');
            }
    
            // Verificar e remover a coluna 'function' caso exista
            if (Schema::hasColumn('users', 'function')) {
                $table->dropColumn('function');
            }
        });
    }
    
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverter a adição da coluna 'role' e 'function'
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
    
            if (!Schema::hasColumn('users', 'function')) {
                $table->string('function')->nullable()->after('active');
            }
        });
    }
};
