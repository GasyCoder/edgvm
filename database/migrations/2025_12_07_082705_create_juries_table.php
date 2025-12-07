<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurys', function (Blueprint $table) {
            $table->id();
            $table->string('nom');              // Nom complet du membre du jury
            $table->string('grade')->nullable(); // Prof., Dr., MCF, etc.
            $table->string('universite')->nullable(); // Université d’appartenance
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });

        Schema::create('these_jury', function (Blueprint $table) {
            $table->id();
            $table->foreignId('these_id')
                  ->constrained('theses')
                  ->onDelete('cascade');

            $table->foreignId('jury_id')
                  ->constrained('jurys')
                  ->onDelete('cascade');

            // Rôle dans le jury
            $table->enum('role', [
                'president',
                'rapporteur',
                'examinateur',
                'invite',
            ])->nullable();

            $table->unsignedTinyInteger('ordre')->nullable(); // ordre d’affichage
            $table->timestamps();

            $table->unique(['these_id', 'jury_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('these_jury');
        Schema::dropIfExists('jurys');
    }
};
