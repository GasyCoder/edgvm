<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // L'énum figé est remplacé par une chaîne (plus évolutif, validé par l'enum PHP UserRole).
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('doctorant')->change();
        });

        DB::table('users')->where('role', 'admin')->update(['role' => 'super_admin']);
        DB::table('users')->where('role', 'secrétaire')->update(['role' => 'secretariat']);
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'super_admin')->update(['role' => 'admin']);
        DB::table('users')->where('role', 'secretariat')->update(['role' => 'secrétaire']);
        DB::table('users')->whereIn('role', ['direction', 'communication'])->update(['role' => 'admin']);

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'secrétaire', 'doctorant', 'encadrant'])->default('doctorant')->change();
        });
    }
};
