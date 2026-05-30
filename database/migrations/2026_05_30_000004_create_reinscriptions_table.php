<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reinscriptions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('doctorant_id')->constrained()->cascadeOnDelete();
            $table->string('annee_universitaire', 20);
            $table->enum('niveau', ['D1', 'D2', 'D3', 'D4', 'D5']);
            $table->enum('statut_annee', ['en_cours', 'admis', 'ajourne', 'valide', 'abandon'])->default('en_cours');
            $table->text('decision')->nullable();
            $table->date('date_decision')->nullable();
            $table->timestamps();

            $table->unique(['doctorant_id', 'annee_universitaire']);
            $table->index(['doctorant_id', 'niveau']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reinscriptions');
    }
};
