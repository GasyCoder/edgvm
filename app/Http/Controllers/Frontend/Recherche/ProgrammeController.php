<?php

namespace App\Http\Controllers\Frontend\Recherche;

use App\Http\Controllers\Controller;
use App\Models\EAD;
use App\Models\Specialite;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProgrammeController extends Controller
{
    public function index(Request $request): Response
    {
        $ead = $request->string('ead')->toString();
        $search = trim($request->string('search')->toString());
        $view = $request->string('view')->toString();

        if (! in_array($view, ['grid', 'list'], true)) {
            $view = 'grid';
        }

        $query = Specialite::with(['ead.responsable'])
            ->active()
            ->withCount([
                'theses as theses_en_cours' => function ($q) {
                    $q->enCours();
                },
                'theses as theses_soutenues' => function ($q) {
                    $q->soutendue();
                },
            ]);

        if ($ead !== '') {
            $query->whereHas('ead', function ($q) use ($ead) {
                $q->where('slug', $ead);
            });
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhereHas('ead', function ($sub) use ($search) {
                        $sub->where('nom', 'like', '%'.$search.'%');
                    });
            });
        }

        $specialites = $query->orderBy('nom')->get()->map(fn (Specialite $specialite) => [
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
            ] : null,
        ])->toArray();

        $eads = EAD::active()
            ->withCount('specialites')
            ->orderBy('nom')
            ->get()
            ->map(fn (EAD $eadItem) => [
                'id' => $eadItem->id,
                'slug' => $eadItem->slug,
                'nom' => $eadItem->nom,
                'specialites_count' => $eadItem->specialites_count,
            ])
            ->toArray();

        return Inertia::render('Frontend/Recherche/Programmes/Index', [
            'filters' => [
                'ead' => $ead,
                'search' => $search,
                'view' => $view,
            ],
            'specialites' => $specialites,
            'eads' => $eads,
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
