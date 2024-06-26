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
        Schema::create('event_activities', function (Blueprint $table) {
            $table->id();
            //si se amplia el negocio poner tabla de deportes
            $table->foreignId('event_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('activity_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->enum('gender',['M','F','Mix']);
            $table->foreignId('sub_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_activities');
    }
};
