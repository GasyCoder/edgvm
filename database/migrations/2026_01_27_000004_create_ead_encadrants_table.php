<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ead_encadrants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ead_id')->constrained('eads')->cascadeOnDelete();
            $table->foreignId('encadrant_id')->constrained('encadrants')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['ead_id', 'encadrant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ead_encadrants');
    }
};
