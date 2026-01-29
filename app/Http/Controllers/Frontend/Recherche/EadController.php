<?php

namespace App\Http\Controllers\Frontend\Recherche;

use App\Http\Controllers\Controller;
use App\Models\EAD;
use App\Models\Specialite;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EadController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());
        $domaine = $request->string('domaine')->toString();
        $view = $request->string('view')->toString();

        if (! in_array($view, ['grid', 'list'], true)) {
            $view = 'grid';
        }

        $baseQuery = EAD::query();

        $domaines = (clone $baseQuery)
            ->whereNotNull('domaine')
            ->orderBy('domaine')
            ->pluck('domaine')
            ->unique()
            ->values()
            ->toArray();

        $query = $baseQuery
            ->with(['responsable'])
            ->withCount('specialites')
            ->withCount([
                'theses as doctorats_count' => function ($q) {
                    $q->enCours();
                },
            ]);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('domaine', 'like', "%{$search}%");
            });
        }

        if ($domaine !== '') {
            $query->where('domaine', $domaine);
        }

        $eads = $query->orderBy('nom')->get()->map(function (EAD $ead) {
            return [
                'id' => $ead->id,
                'slug' => $ead->slug,
                'nom' => $ead->nom,
                'description' => $ead->description,
                'domaine' => $ead->domaine,
                'specialites_count' => $ead->specialites_count,
                'doctorats_count' => $ead->doctorats_count,
                'responsable' => $ead->responsable ? [
                    'name' => $ead->responsable->name,
                ] : null,
            ];
        })->toArray();

        return Inertia::render('Frontend/Recherche/Eads/Index', [
            'filters' => [
                'search' => $search,
                'domaine' => $domaine,
                'view' => $view,
            ],
            'domaines' => $domaines,
            'eads' => $eads,
        ]);
    }

    public function show(EAD $ead): Response
    {
        $ead->load(['responsable']);

        $ead->loadCount([
            'specialites',
            'theses as doctorats_count' => function ($q) {
                $q->enCours();
            },
            'theses as theses_soutenues_count' => function ($q) {
                $q->soutendue();
            },
        ]);

        $specialites = Specialite::where('ead_id', $ead->id)
            ->withCount([
                'theses as theses_en_cours' => function ($q) {
                    $q->enCours();
                },
                'theses as theses_soutenues' => function ($q) {
                    $q->soutendue();
                },
            ])
            ->orderBy('nom')
            ->get()
            ->map(fn (Specialite $specialite) => [
                'id' => $specialite->id,
                'slug' => $specialite->slug,
                'nom' => $specialite->nom,
                'description' => $specialite->description,
                'theses_en_cours' => $specialite->theses_en_cours,
                'theses_soutenues' => $specialite->theses_soutenues,
            ])
            ->toArray();

        $theses = $ead->theses()
            ->enCours()
            ->with(['doctorant.user', 'specialite'])
            ->get()
            ->map(function ($these) {
                return [
                    'id' => $these->id,
                    'sujet_these' => $these->sujet_these,
                    'date_debut' => $these->date_debut?->format('Y-m-d'),
                    'date_prevue_fin' => $these->date_prevue_fin?->format('Y-m-d'),
                    'doctorant' => [
                        'id' => $these->doctorant?->id,
                        'name' => $these->doctorant?->user?->name ?? 'Non renseigné',
                        'matricule' => $these->doctorant?->matricule,
                    ],
                    'specialite' => $these->specialite ? [
                        'nom' => $these->specialite->nom,
                    ] : null,
                ];
            })
            ->toArray();

        return Inertia::render('Frontend/Recherche/Eads/Show', [
            'ead' => [
                'id' => $ead->id,
                'slug' => $ead->slug,
                'nom' => $ead->nom,
                'description' => $ead->description,
                'domaine' => $ead->domaine,
                'specialites_count' => $ead->specialites_count,
                'doctorats_count' => $ead->doctorats_count,
                'theses_soutenues_count' => $ead->theses_soutenues_count,
                'responsable' => $ead->responsable ? [
                    'name' => $ead->responsable->name,
                    'email' => $ead->responsable->email,
                    'grade' => $ead->responsable->grade,
                    'phone' => $ead->responsable->phone,
                ] : null,
            ],
            'specialites' => $specialites,
            'theses' => $theses,
        ]);
    }
}
