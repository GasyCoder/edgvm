<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctorant_id')->constrained('doctorants')->cascadeOnDelete();
            $table->enum('niveau', ['D1', 'D2', 'D3']);
            $table->string('annee_universitaire', 20);
            $table->decimal('montant_du', 12, 2)->default(0);
            $table->decimal('montant_paye', 12, 2)->default(0);
            $table->date('date_paiement')->nullable();
            $table->string('mode', 40)->nullable();
            $table->string('reference')->nullable();
            $table->string('justificatif_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['doctorant_id', 'niveau', 'annee_universitaire']);
            $table->index('date_paiement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
