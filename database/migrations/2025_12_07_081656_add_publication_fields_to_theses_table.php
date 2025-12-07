<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('theses', function (Blueprint $table) {
            // Université de soutenance (UMG, UA, etc.)
            $table->string('universite_soutenance')->nullable()->after('ead_id');

            // Date officielle de publication/soutenance
            $table->date('date_publication')->nullable()->after('date_prevue_fin');
        });
    }

    public function down(): void
    {
        Schema::table('theses', function (Blueprint $table) {
            $table->dropColumn(['universite_soutenance', 'date_publication']);
        });
    }
};
