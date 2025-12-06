<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Specialites
        if (!Schema::hasColumn('specialites', 'slug')) {
            Schema::table('specialites', function (Blueprint $table) {
                $table->string('slug')->unique()->after('nom');
            });

            // Générer les slugs pour les données existantes
            DB::table('specialites')->get()->each(function ($specialite) {
                DB::table('specialites')
                    ->where('id', $specialite->id)
                    ->update(['slug' => Str::slug($specialite->nom)]);
            });
        }

        // EADs
        if (!Schema::hasColumn('eads', 'slug')) {
            Schema::table('eads', function (Blueprint $table) {
                $table->string('slug')->unique()->after('nom');
            });

            // Générer les slugs pour les données existantes
            DB::table('eads')->get()->each(function ($ead) {
                DB::table('eads')
                    ->where('id', $ead->id)
                    ->update(['slug' => Str::slug($ead->nom)]);
            });
        }
    }

    public function down(): void
    {
        Schema::table('specialites', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('eads', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};