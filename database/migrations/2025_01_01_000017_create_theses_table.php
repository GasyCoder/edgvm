<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('theses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctorant_id')->constrained('doctorants')->onDelete('cascade');
            $table->string('titre');
            $table->longText('description')->nullable();
            $table->foreignId('specialite_id')->constrained('specialites')->onDelete('cascade');
            $table->foreignId('ead_id')->constrained('eads')->onDelete('cascade');
            $table->date('date_debut')->nullable();
            $table->date('date_prevue_fin')->nullable();
            $table->enum('statut', ['en_cours', 'soutendue', 'abandonnee'])->default('en_cours');
            $table->foreignId('media_id')->nullable()->constrained('media')->onDelete('set null');
            $table->text('resume_these')->nullable();
            $table->string('mots_cles')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('theses');
    }
};
