<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soutenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('these_id')->unique()->constrained('theses')->onDelete('cascade');
            $table->date('date_soutenance')->nullable();
            $table->time('heure_soutenance')->nullable();
            $table->string('lieu')->nullable();
            $table->enum('resultat', ['admis', 'non_admis', 'ajourne'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soutenances');
    }
};
