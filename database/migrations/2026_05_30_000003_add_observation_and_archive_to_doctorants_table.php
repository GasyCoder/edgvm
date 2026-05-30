<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctorants', function (Blueprint $table): void {
            $table->text('observation')->nullable()->after('statut');
            $table->timestamp('archived_at')->nullable()->after('observation');
        });
    }

    public function down(): void
    {
        Schema::table('doctorants', function (Blueprint $table): void {
            $table->dropColumn(['observation', 'archived_at']);
        });
    }
};
