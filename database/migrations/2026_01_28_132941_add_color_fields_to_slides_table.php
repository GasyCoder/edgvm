<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('slides', function (Blueprint $table) {
            $table->string('couleur_texte_titre', 7)->default('#FFFFFF')->after('couleur_fond');
            $table->string('couleur_cta', 7)->default('#FFFFFF')->after('couleur_texte_titre');
        });
    }

    public function down(): void
    {
        Schema::table('slides', function (Blueprint $table) {
            $table->dropColumn(['couleur_texte_titre', 'couleur_cta']);
        });
    }
};
