<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                return match ($user->role) {
                    'super_admin', 'direction', 'secretariat', 'communication' => redirect()->route('admin.dashboard'),
                    'encadrant' => redirect()->route('encadrant.dashboard'),
                    'doctorant' => redirect()->route('doctorant.dashboard'),
                    default => redirect('/dashboard'),
                };
            }
        }

        return $next($request);
    }
}
