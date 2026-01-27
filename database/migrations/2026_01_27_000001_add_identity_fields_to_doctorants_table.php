<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctorants', function (Blueprint $table) {
            $table->string('nom')->nullable()->after('user_id');
            $table->string('prenoms')->nullable()->after('nom');
            $table->string('email')->nullable()->unique()->after('prenoms');
            $table->foreignId('ead_id')->nullable()->after('email')->constrained('eads')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('doctorants', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ead_id');
            $table->dropColumn(['nom', 'prenoms', 'email']);
        });
    }
};
