<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('these_encadrants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('these_id')->constrained('theses')->onDelete('cascade');
            $table->foreignId('encadrant_id')->constrained('encadrants')->onDelete('cascade');
            $table->enum('role', ['directeur', 'co-directeur'])->default('directeur');
            $table->timestamps();
            $table->unique(['these_id', 'encadrant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('these_encadrants');
    }
};
