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
        Schema::create('activity_event_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_user_id')->constrained('event_user')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('activity_id')->constrained('activities')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('gender',['M','F','Mix']);
            $table->foreignId('sub_id')->constrained()->onUpdate('cascade')->onDelete('cascade');//falta relacion con el modelo 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_event_user');
    }
};
