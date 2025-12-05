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
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('matricule')->unique();
            $table->string('niveau')->nullable(); // D1, D2, D3
            $table->string('phone')->nullable();
            $table->string('adresse')->nullable();
            $table->date('date_inscription')->nullable();
            $table->enum('statut', ['actif', 'suspendu', 'diplome'])->default('actif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctorants');
    }
};
