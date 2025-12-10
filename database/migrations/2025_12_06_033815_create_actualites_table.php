<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actualites', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique()->nullable();
            $table->longText('contenu');
            $table->foreignId('auteur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('image_id')->nullable()->constrained('media')->onDelete('set null');
            $table->date('date_publication')->nullable();
            $table->boolean('visible')->default(true);
            $table->boolean('est_important')->default(false);
            $table->boolean('notifier_abonnes')->default(false);
            $table->timestamp('notification_envoyee_le')->nullable();
            $table->unsignedBigInteger('vues')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actualites');
    }
};
