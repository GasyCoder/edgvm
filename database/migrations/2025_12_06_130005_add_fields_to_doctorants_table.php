<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctorants', function (Blueprint $table) {
            // Champs académiques
            $table->text('sujet_these')->nullable()->after('niveau');
            $table->foreignId('directeur_id')->nullable()->constrained('encadrants')->nullOnDelete()->after('sujet_these');
            $table->foreignId('codirecteur_id')->nullable()->constrained('encadrants')->nullOnDelete()->after('directeur_id');
            $table->foreignId('ead_id')->nullable()->constrained('eads')->nullOnDelete()->after('codirecteur_id');
            
            // Informations personnelles
            $table->date('date_naissance')->nullable()->after('ead_id');
            $table->string('lieu_naissance')->nullable()->after('date_naissance');
        });
    }

    public function down(): void
    {
        Schema::table('doctorants', function (Blueprint $table) {
            $table->dropForeign(['directeur_id']);
            $table->dropForeign(['codirecteur_id']);
            $table->dropForeign(['ead_id']);
            
            $table->dropColumn([
                'sujet_these',
                'directeur_id',
                'codirecteur_id',
                'ead_id',
                'date_naissance',
                'lieu_naissance',
            ]);
        });
    }
};