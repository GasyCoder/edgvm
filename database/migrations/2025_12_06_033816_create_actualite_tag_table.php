<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actualite_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actualite_id')->constrained('actualites')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['actualite_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actualite_tag');
    }
};