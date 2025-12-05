<?php

namespace App\Http\Controllers;

use App\Models\These;
use App\Models\Inscription;
use App\Models\Publication;
use Illuminate\Support\Facades\Auth;

class DoctorantController extends Controller
{
    public function dashboard()
    {
        $doctorant = Auth::user()->doctorant;

        $these = $doctorant->theses()->first();
        $inscription = $doctorant->inscriptions()->latest()->first();

        $stats = [
            'statut' => $doctorant->statut,
            'niveau' => $doctorant->niveau,
            'has_these' => $these ? true : false,
            'publications' => $these ? $these->publications()->count() : 0,
        ];

        return view('roles.doctorant.dashboard', compact('stats', 'these', 'inscription', 'doctorant'));
    }
}