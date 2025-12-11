<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FrontendMaintenanceFromSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // On récupère l’unique enregistrement des paramètres
        $settings = Setting::main();

        // Si le mode maintenance est activé -> on affiche la page maintenance
        if ((bool) $settings->maintenance_mode === true) {
            return response()->view('maintenance', [
                'settings' => $settings,
            ], 503);
        }

        // Sinon on continue normalement
        return $next($request);
    }
}
