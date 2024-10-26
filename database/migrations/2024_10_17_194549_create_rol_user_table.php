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
        Schema::create('rol_user', function (Blueprint $table) {
            $table->id('pivote');
            $table->bigInteger('dni');
            $table->unsignedBigInteger('id_rol');

            //claves foráneas
            $table->foreign('dni')->references('dni')->on('users');
            $table->foreign('id_rol')->references('id_rol')->on('rols');

            $table->timestamps();
            $table->unique(['dni', 'id_rol']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rol_user');
    }
};
