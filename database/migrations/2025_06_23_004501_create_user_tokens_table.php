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
        Schema::create('user_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();//indice para revisar el correo de manera más sencilla
            $table->string('token')->unique();//cada token debe ser único 
            $table->string('ip_address')->nullable();//dirección IP del usuario
            $table->datetime('expiration');
            $table->enum('type', ['password_reset', 'email_verification']);
            $table->timestamps();
            $table->softDeletes(); // Permite eliminar lógicamente el registro "inhabilitarlo" sin borrarlo de la base de datos
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tokens');
    }
};
