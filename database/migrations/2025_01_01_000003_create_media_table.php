<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('nom_original');
            $table->string('nom_fichier')->unique();
            $table->string('chemin');
            $table->enum('type', ['image', 'document', 'video', 'other']);
            $table->bigInteger('taille_bytes')->nullable();
            $table->string('mime_type')->nullable();
            $table->foreignId('uploader_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
