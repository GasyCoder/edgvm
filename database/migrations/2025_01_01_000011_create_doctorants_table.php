<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctorants', function (Blueprint $table) {
            $table->id();
            
            // Relation optionnelle avec users (1-1)
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->onDelete('cascade');
            
            // Informations personnelles
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('matricule')->unique();
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->string('phone')->nullable();
            $table->string('adresse')->nullable();
            
            // Informations académiques
            $table->enum('niveau', ['D1', 'D2', 'D3'])->default('D1');
            $table->date('date_inscription');
            $table->enum('statut', ['actif', 'diplome', 'suspendu', 'abandonne'])->default('actif');
            
            // Thèse
            $table->text('sujet_these')->nullable();
            $table->foreignId('directeur_id')->nullable()->constrained('encadrants')->nullOnDelete();
            $table->foreignId('codirecteur_id')->nullable()->constrained('encadrants')->nullOnDelete();
            $table->foreignId('ead_id')->nullable()->constrained('eads')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctorants');
    }
};