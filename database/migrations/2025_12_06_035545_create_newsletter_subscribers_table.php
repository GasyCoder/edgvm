<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('nom')->nullable();
            $table->enum('type', ['doctorant', 'encadrant', 'autre'])->default('autre');
            $table->boolean('actif')->default(true);
            $table->timestamp('desabonne_le')->nullable();
            $table->string('token')->unique(); 
            $table->timestamp('abonne_le')->useCurrent();
            $table->timestamps();
            
            $table->index('actif');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscribers');
    }
};