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
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');//direccion
            $table->decimal('lat', 10, 8); // Latitud con 10 dígitos en total y 8 decimales
            $table->decimal('lon', 11, 8); // Longitud con 11 dígitos en total y 8 decimales
            $table->timestamps();
            $table->softDeletes(); // Permite eliminar lógicamente el registro "inhabilitarlo" sin borrarlo de la base de datos
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
