<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Évite les erreurs pendant les migrations / premières installations
        try {
            if (Schema::hasTable('settings')) {
                // Partage une variable $appSettings dans toutes les vues (frontend + backend)
                View::share('appSettings', Setting::main());
            }
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
