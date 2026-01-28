<?php

namespace App\Http\Controllers\Frontend\Recherche;

use App\Http\Controllers\Controller;
use App\Models\Specialite;
use Inertia\Inertia;
use Inertia\Response;

class ProgrammeController extends Controller
{
    public function index(): Response
    {
        $specialites = Specialite::with(['ead'])
            ->active()
            ->orderBy('nom')
            ->get()
            ->map(fn (Specialite $specialite) => [
                'id' => $specialite->id,
                'nom' => $specialite->nom,
                'ead' => $specialite->ead?->nom,
            ])
            ->toArray();

        return Inertia::render('Frontend/Recherche/Programmes/Index', [
            'specialites' => $specialites,
        ]);
    }

    public function show(Specialite $specialite): Response
    {
        $specialite->load(['ead.responsable']);

        $specialite->loadCount([
            'theses as theses_en_cours' => function ($q) {
                $q->enCours();
            },
            'theses as theses_soutenues' => function ($q) {
                $q->soutendue();
            },
        ]);

        $autresSpecialites = Specialite::where('ead_id', $specialite->ead_id)
            ->where('id', '!=', $specialite->id)
            ->limit(3)
            ->get()
            ->map(fn (Specialite $item) => [
                'id' => $item->id,
                'slug' => $item->slug,
                'nom' => $item->nom,
            ])
            ->toArray();

        return Inertia::render('Frontend/Recherche/Programmes/Show', [
            'specialite' => [
                'id' => $specialite->id,
                'slug' => $specialite->slug,
                'nom' => $specialite->nom,
                'description' => $specialite->description,
                'theses_en_cours' => $specialite->theses_en_cours,
                'theses_soutenues' => $specialite->theses_soutenues,
                'ead' => $specialite->ead ? [
                    'id' => $specialite->ead->id,
                    'slug' => $specialite->ead->slug,
                    'nom' => $specialite->ead->nom,
                    'domaine' => $specialite->ead->domaine,
                    'responsable' => $specialite->ead->responsable ? [
                        'name' => $specialite->ead->responsable->name,
                        'email' => $specialite->ead->responsable->email,
                        'grade' => $specialite->ead->responsable->grade,
                    ] : null,
                ] : null,
            ],
            'autresSpecialites' => $autresSpecialites,
        ]);
    }
}
