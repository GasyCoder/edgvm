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

            // Relations
            $table->foreignId('doctorant_id')
                ->constrained('doctorants')
                ->onDelete('cascade');

            $table->foreignId('specialite_id')
                ->nullable()
                ->constrained('specialites')
                ->nullOnDelete();

            $table->foreignId('ead_id')
                ->nullable()
                ->constrained('eads')
                ->nullOnDelete();
            $table->string('universite_soutenance')->nullable();
            // Fichier PDF de la thèse (table media)
            $table->foreignId('media_id')
                ->nullable()
                ->constrained('media')
                ->nullOnDelete();

            // Informations principales
            $table->string('sujet_these');
            $table->text('description')->nullable();

            // Résumé & mots-clés
            $table->text('resume_these')->nullable();
            $table->string('mots_cles')->nullable();

            // Informations de publication
            $table->string('universite')->nullable();
            $table->date('date_debut')->nullable();
            $table->date('date_prevue_fin')->nullable();
            $table->date('date_publication')->nullable();

            // Statut de la thèse
            $table->enum('statut', [
                'en_cours',
                'soutenue',
                'abandonnee',
                'suspendue',
                'preparation',
                'redaction',
            ])->default('en_cours');

            $table->timestamps();

            // Index utiles
            $table->index('statut');
            $table->index('date_debut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('theses');
    }
};
