<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Models\Doctorant;
use App\Models\These;

class SecretaireController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'inscriptions_en_attente' => Inscription::where('statut', 'en_attente')->count(),
            'inscriptions_validees' => Inscription::whereDate('date_validation', today())->count(),
            'doctorants_actifs' => Doctorant::where('statut', 'actif')->count(),
            'theses_en_cours' => These::where('statut', 'en_cours')->count(),
        ];

        $inscriptions_recentes = Inscription::with(['doctorant.user', 'specialite'])
            ->where('statut', 'en_attente')
            ->latest()
            ->take(5)
            ->get();

        return view('roles.secretaire.dashboard', compact('stats', 'inscriptions_recentes'));
    }
}