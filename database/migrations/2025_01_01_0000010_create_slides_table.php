<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slider_id')->constrained('sliders')->onDelete('cascade');
            
            // SÉPARATION DU TITRE
            $table->string('titre_ligne1')->nullable(); // "Le"
            $table->string('titre_highlight'); // "Génie du Vivant"
            $table->string('titre_ligne2')->nullable(); // "au Service de l'Humanité"
            $table->foreignId('actualite_id')->nullable()->constrained('actualites')->nullOnDelete();
            $table->text('description')->nullable();
            $table->foreignId('image_id')->constrained('media')->onDelete('cascade');
            $table->string('lien_cta')->nullable();
            $table->string('texte_cta')->nullable();
            $table->integer('ordre')->default(0);
            $table->boolean('visible')->default(true);
            $table->string('badge_texte')->nullable();
            $table->string('badge_icon')->nullable(); // university, research, students
            $table->string('couleur_fond')->default('from-ed-primary/95 via-ed-secondary/90 to-teal-800/95');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['slider_id', 'ordre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slides');
    }
};