<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->unsignedInteger('message_direction_doctorants')->default(0);
            $table->unsignedInteger('message_direction_equipes')->default(0);
            $table->unsignedInteger('message_direction_theses')->default(0);
        });

        $messageStats = DB::table('message_directions')
            ->where('visible', true)
            ->orderByDesc('created_at')
            ->first();

        if ($messageStats) {
            $settingsId = DB::table('settings')
                ->orderBy('id')
                ->value('id');

            if (! $settingsId) {
                $settingsId = DB::table('settings')->insertGetId([
                    'site_name' => 'EDGVM',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('settings')
                ->where('id', $settingsId)
                ->update([
                    'message_direction_doctorants' => (int) ($messageStats->nb_doctorants ?? 0),
                    'message_direction_equipes' => (int) ($messageStats->nb_equipes ?? 0),
                    'message_direction_theses' => (int) ($messageStats->nb_theses ?? 0),
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'message_direction_doctorants',
                'message_direction_equipes',
                'message_direction_theses',
            ]);
        });
    }
};
