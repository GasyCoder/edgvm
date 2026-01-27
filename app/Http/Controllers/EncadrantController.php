<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Support\Facades\Auth;

class EncadrantController extends Controller
{
    public function dashboard()
    {
        $encadrant = Auth::user()->encadrant;
        if (! $encadrant) {
            abort(404);
        }

        $stats = [
            'mes_theses' => $encadrant->theses()->count(),
            'theses_en_cours' => $encadrant->theses()->where('statut', 'en_cours')->count(),
            'theses_soutenues' => $encadrant->theses()->where('statut', 'soutendue')->count(),
            'publications' => Publication::whereIn('these_id', $encadrant->theses()->pluck('theses.id'))->count(),
        ];

        $theses_recentes = $encadrant->theses()
            ->with(['doctorant.user', 'specialite'])
            ->latest('theses.created_at')
            ->take(5)
            ->get();

        return view('roles.encadrant.dashboard', compact('stats', 'theses_recentes'));
    }
}
