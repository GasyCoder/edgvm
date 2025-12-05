<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soutenance_jury', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soutenance_id')->constrained('soutenances')->onDelete('cascade');
            $table->foreignId('encadrant_id')->constrained('encadrants')->onDelete('cascade');
            $table->enum('role', ['president', 'rapporteur', 'examinateur']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soutenance_jury');
    }
};
