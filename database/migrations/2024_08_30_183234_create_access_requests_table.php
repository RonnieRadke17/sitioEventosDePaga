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
        Schema::create('access_requests', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->integer('attempts')->default(1); // Contador de intentos fallidos para esta "sesión" de intentos
            $table->string('ip_address')->nullable(); // Dirección IP
            $table->timestamps();// se encarga de cuando se hizo el primer intento"created_at" y cuando se hizo el último intento "updated_at"
            $table->softDeletes(); // Permite eliminar lógicamente el registro "inhabilitarlo" sin borrarlo de la base de datos
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_requests');
    }
};
