<?php

namespace App\Providers;

use App\Models\Setting;
use App\Support\RolePermissions;
use Illuminate\Support\Facades\Gate;
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
        $this->registerAuthorization();

        if (app()->runningInConsole()) {
            return;
        }

        // Évite les erreurs pendant les migrations / premières installations
        try {
            if (Schema::hasTable('settings')) {
                // Partage une variable $appSettings dans toutes les vues (frontend + backend)
                View::share('appSettings', Setting::main());
            }
        } catch (Throwable) {
            // Ignore si la base n'est pas disponible.
        }
    }

    /**
     * Déclare les capacités (abilities) par rôle.
     * Le super administrateur passe partout via Gate::before.
     */
    private function registerAuthorization(): void
    {
        Gate::before(function ($user) {
            return $user->isSuperAdmin() ? true : null;
        });

        foreach (RolePermissions::ABILITIES as $ability) {
            Gate::define($ability, fn ($user) => RolePermissions::allows($user->role, $ability));
        }
    }
}
