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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedInteger('order')->default(0);
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('active')->default(false);
            $table->foreignId('media_id')->nullable()->constrained('training_media')->cascadeOnDelete();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    
    {
        
        Schema::dropIfExists('training_modules');
        Schema::dropIfExists('training_chapters');
        Schema::dropIfExists('trainings');
    }
};
