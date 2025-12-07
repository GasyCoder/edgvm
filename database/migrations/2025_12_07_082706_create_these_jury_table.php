<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('these_jury', function (Blueprint $table) {
            $table->id();

            $table->foreignId('these_id')
                ->constrained('theses')
                ->onDelete('cascade');

            $table->foreignId('jury_id')
                ->constrained('jurys')
                ->onDelete('cascade');

            // rôle dans le jury : président, rapporteur, examinateur, invité...
            $table->string('role')->nullable();

            // ordre d’affichage (1 = président, 2 = rapporteur, etc.)
            $table->unsignedTinyInteger('ordre')->nullable();

            $table->timestamps();

            $table->unique(['these_id', 'jury_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('these_jury');
    }
};
