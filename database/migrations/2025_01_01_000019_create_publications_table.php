<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('these_id')->constrained('theses')->onDelete('cascade');
            $table->string('titre');
            $table->string('auteurs')->nullable();
            $table->string('revue')->nullable();
            $table->date('date_publication')->nullable();
            $table->string('url_publication')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
