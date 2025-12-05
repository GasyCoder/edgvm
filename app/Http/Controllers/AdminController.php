<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctorant;
use App\Models\Encadrant;
use App\Models\These;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_doctorants' => Doctorant::count(),
            'total_encadrants' => Encadrant::count(),
            'theses_en_cours' => These::where('statut', 'en_cours')->count(),
            'theses_soutenues' => These::where('statut', 'soutendue')->count(),
        ];

        return view('roles.admin.dashboard', compact('stats'));
    }
}