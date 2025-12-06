<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actualite_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actualite_id')->constrained('actualites')->onDelete('cascade');
            $table->foreignId('media_id')->constrained('media')->onDelete('cascade');
            $table->integer('ordre')->default(0);
            $table->timestamps();
            
            $table->unique(['actualite_id', 'media_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actualite_media');
    }
};