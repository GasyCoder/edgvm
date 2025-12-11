<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->date('date_debut');
            $table->time('heure_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->time('heure_fin')->nullable();
            $table->string('lieu')->nullable();
            $table->enum('type', ['soutenance', 'seminaire', 'conference', 'atelier', 'autre'])->default('autre');
            $table->boolean('est_important')->default(false);
            $table->string('lien_inscription')->nullable();
            $table->boolean('est_publie')->default(true);
            $table->boolean('est_archive')->default(false);
            $table->foreignId('image_id')
                ->nullable()
                ->constrained('media')
                ->nullOnDelete();
            $table->foreignId('document_id')
                ->nullable()
                ->constrained('media')
                ->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evenements');
    }
};