<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('message_directions', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Mme ROUKIA
            $table->string('fonction')->nullable(); // Directrice de l'EDGVM
            $table->string('institution')->nullable(); // Université de Mahajanga

            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('photo_path')->nullable(); // chemin dans storage ou URL

            $table->text('citation')->nullable(); // le texte en italique
            $table->text('message_intro')->nullable(); // le paragraphe suivant

            // Chiffres utilisés dans la carte
            $table->unsignedInteger('nb_doctorants')->default(0);
            $table->unsignedInteger('nb_equipes')->default(0);
            $table->unsignedInteger('nb_theses')->default(0);

            $table->boolean('visible')->default(true);

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_directions');
    }
};
