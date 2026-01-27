<?php

namespace App\Http\Controllers;

use App\Models\Doctorant;
use App\Models\Encadrant;
use App\Models\NewsletterSubscriber;
use App\Models\These;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function dashboard(): Response
    {
        $stats = [
            'total_users' => User::count(),
            'total_doctorants' => Doctorant::count(),
            'total_encadrants' => Encadrant::count(),
            'theses_en_cours' => These::where('statut', 'en_cours')->count(),
            'theses_soutenues' => These::where('statut', 'soutendue')->count(),
            'total_subscribers' => NewsletterSubscriber::count(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
        ]);
    }
}
