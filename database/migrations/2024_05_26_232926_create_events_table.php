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
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('description');
            //$table->string('image')->nullable(); ya esta una relacion en la tabla
            //falta poner lugar de entrga de kits y lugar del evento
            //$table->foreign('place_id')->references('id')->on('places')->onDelete('cascade')->onUpdate('cascade');
            //$table->foreign('place_id')->references('id')->on('places')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('capacity');
            $table->enum('status',['Activo','Inactivo','Cancelado',])->nullable()->default('Activo');
            //falta algo que ponga su status, de si esta activo el evento o ya no
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
