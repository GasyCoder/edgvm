<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('actualites', function (Blueprint $table) {
            $table->boolean('est_important')->default(false)->after('visible');
            $table->boolean('notifier_abonnes')->default(false)->after('est_important');
            $table->timestamp('notification_envoyee_le')->nullable()->after('notifier_abonnes');
        });
    }

    public function down(): void
    {
        Schema::table('actualites', function (Blueprint $table) {
            $table->dropColumn(['est_important', 'notifier_abonnes', 'notification_envoyee_le']);
        });
    }
};