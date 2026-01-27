<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctorants', function (Blueprint $table) {
            $table->foreignId('ead_id')->nullable()->after('email')->constrained('eads')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('doctorants', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ead_id');
        });
    }
};
