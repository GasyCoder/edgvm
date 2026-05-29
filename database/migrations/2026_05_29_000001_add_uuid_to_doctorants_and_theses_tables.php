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
        Schema::table('doctorants', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        Schema::table('theses', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        foreach (DB::table('doctorants')->whereNull('uuid')->pluck('id') as $id) {
            DB::table('doctorants')->where('id', $id)->update(['uuid' => (string) Str::uuid()]);
        }

        foreach (DB::table('theses')->whereNull('uuid')->pluck('id') as $id) {
            DB::table('theses')->where('id', $id)->update(['uuid' => (string) Str::uuid()]);
        }
    }

    public function down(): void
    {
        Schema::table('doctorants', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('theses', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
