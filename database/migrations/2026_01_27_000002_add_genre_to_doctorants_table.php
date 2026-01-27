<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctorants', function (Blueprint $table) {
            $table->enum('genre', ['homme', 'femme'])->default('homme')->after('prenoms');
        });
    }

    public function down(): void
    {
        Schema::table('doctorants', function (Blueprint $table) {
            $table->dropColumn('genre');
        });
    }
};
