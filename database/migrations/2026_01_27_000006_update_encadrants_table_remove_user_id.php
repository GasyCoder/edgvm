<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('encadrants', function (Blueprint $table) {
            $table->string('nom')->nullable()->after('user_id');
            $table->string('prenoms')->nullable()->after('nom');
            $table->enum('genre', ['homme', 'femme'])->default('homme')->after('prenoms');
            $table->string('email')->nullable()->after('genre');
        });

        Schema::table('encadrants', function (Blueprint $table) {
            $table->unique('email');
        });

        Schema::table('encadrants', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropUnique('encadrants_user_id_unique');
            $table->dropColumn('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('encadrants', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id');
        });

        Schema::table('encadrants', function (Blueprint $table) {
            $table->unique('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::table('encadrants', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->dropColumn(['nom', 'prenoms', 'genre', 'email']);
        });
    }
};
