<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('titre');
        });

        $evenements = DB::table('evenements')
            ->select('id', 'titre')
            ->orderBy('id')
            ->get();

        foreach ($evenements as $evenement) {
            if (! $evenement->titre) {
                continue;
            }

            $baseSlug = Str::slug($evenement->titre);
            $slug = $baseSlug;
            $counter = 1;

            while ($slug !== '' && DB::table('evenements')->where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$counter;
                $counter++;
            }

            if ($slug === '') {
                $slug = 'evenement-'.$evenement->id;
            }

            DB::table('evenements')->where('id', $evenement->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
