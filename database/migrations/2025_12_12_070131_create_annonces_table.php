<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();

            $table->string('titre');
            $table->longText('contenu_html')->nullable();

            // Cible d'affichage (dans l'app)
            $table->enum('cible', ['doctorant', 'encadrant', 'both'])->default('both');

            // Fichier externe (via Media)
            $table->foreignId('media_id')->nullable()
                ->constrained('media')
                ->nullOnDelete();

            // Publication
            $table->boolean('est_publie')->default(false);
            $table->timestamp('publie_at')->nullable();

            // Email
            $table->boolean('envoyer_email')->default(false);
            $table->enum('email_cible', ['doctorant', 'encadrant', 'both'])->nullable();
            $table->timestamp('email_envoye_at')->nullable();

            // Auteur (admin)
            $table->foreignId('auteur_id')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['est_publie', 'cible']);
            $table->index(['email_envoye_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};
