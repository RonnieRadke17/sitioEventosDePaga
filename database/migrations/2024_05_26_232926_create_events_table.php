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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();//nombre del evento
            $table->text('description');
            $table->datetime('event_date');//datetime sobre la fecha del evento
            $table->datetime('registration_deadline');//datetime del ultimo dia para inscribirse y la hora
            $table->integer('capacity')->nullable();//si el campo esta vacio es porque no hay limite de cupos
            //$table->enum('status',['Activo','Inactivo','Cancelado',])->nullable()->default('Activo'); revisar si se requiere cancelación o reprogramación
            $table->decimal('price', 10, 2)->nullable();//se pone el precio nulo por si es un evento gratuito
            $table->timestamps();
            $table->softDeletes(); // Permite eliminar lógicamente el registro "inhabilitarlo" sin borrarlo de la base de datos
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
