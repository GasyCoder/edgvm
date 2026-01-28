<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actualite_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actualite_id')->constrained('actualites')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['actualite_id', 'category_id']);
        });

        $actualites = DB::table('actualites')
            ->whereNotNull('category_id')
            ->get(['id', 'category_id']);

        foreach ($actualites as $actualite) {
            DB::table('actualite_category')->insertOrIgnore([
                'actualite_id' => $actualite->id,
                'category_id' => $actualite->category_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('actualite_category');
    }
};
