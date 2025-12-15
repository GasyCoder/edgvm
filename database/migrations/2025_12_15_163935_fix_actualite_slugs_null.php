<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $actualites = DB::table('actualites')
            ->whereNull('slug')
            ->orWhere('slug', '')
            ->get();

        foreach ($actualites as $actualite) {
            if (empty($actualite->titre)) continue;

            $slug = Str::slug($actualite->titre);
            $originalSlug = $slug;
            $count = 1;

            while (DB::table('actualites')
                     ->where('slug', $slug)
                     ->where('id', '!=', $actualite->id)
                     ->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            DB::table('actualites')
                ->where('id', $actualite->id)
                ->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        //
    }
};