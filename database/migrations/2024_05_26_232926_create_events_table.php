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
            $table->string('name');
            $table->text('description');
            $table->datetime('event_date');//datetime sobre la fecha del evento
            $table->datetime('kit_delivery')->nullable();//fecha de entrega de kits puede que se den o no
            $table->datetime('registration_deadline');//datetime del ultimo dia para inscribirse y la hora
            /* 
                aqui definimos que si la capacidad es limitada si se ocupa el campo capacidad 
                si no es limitada no se ocupa ese campo 
            */
            $table->boolean('is_limited_capacity')->default(true);
            $table->integer('capacity')->nullable();
            $table->boolean('activities');//campo para ver si el evento va a tener actividades
            $table->enum('status',['Activo','Inactivo','Cancelado',])->nullable()->default('Activo');
            $table->decimal('price', 10, 2);
            $table->timestamps();
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
