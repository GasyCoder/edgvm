<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // Général
            $table->string('site_name')->default('EDGVM');
            $table->string('seo_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            // Contact
            $table->string('site_email')->nullable();
            $table->string('site_phone')->nullable();
            $table->string('site_address')->nullable();

            // Réseaux sociaux
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();   // ou X
            $table->string('linkedin_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('instagram_url')->nullable();

            // SEO / Sitemap
            $table->string('sitemap_url')->nullable();

            // Apparence
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();

            // Maintenance
            $table->boolean('maintenance_mode')->default(false);
            $table->text('maintenance_message')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
