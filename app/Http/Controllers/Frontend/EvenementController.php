<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EvenementController extends Controller
{
    public function index(Request $request): Response
    {
        $typeFilter = $request->string('type')->toString();

        $query = Evenement::query()
            ->with('image')
            ->orderBy('date_debut', 'asc');

        if ($typeFilter !== '') {
            $query->where('type', $typeFilter);
        }

        $evenements = $query->paginate(12)->withQueryString()
            ->through(function (Evenement $evenement): array {
                return [
                    'id' => $evenement->id,
                    'titre' => $evenement->titre,
                    'slug' => $evenement->slug,
                    'description' => $evenement->description,
                    'lieu' => $evenement->lieu,
                    'type' => $evenement->type,
                    'type_texte' => $evenement->type_texte,
                    'type_classe' => $evenement->type_classe,
                    'est_important' => $evenement->est_important,
                    'est_termine' => $evenement->est_termine,
                    'jour' => $evenement->jour,
                    'mois' => $evenement->mois,
                    'periode_aff' => $evenement->periode_aff,
                    'heure_debut_aff' => $evenement->heure_debut_aff,
                    'lien_inscription' => $evenement->lien_inscription,
                    'image_url' => $evenement->image?->url,
                ];
            });

        return Inertia::render('Frontend/Evenements/Index', [
            'typeFilter' => $typeFilter,
            'evenements' => $evenements,
        ]);
    }

    public function show(Evenement $evenement): Response
    {
        $evenement->load(['image', 'document']);

        return Inertia::render('Frontend/Evenements/Show', [
            'evenement' => [
                'id' => $evenement->id,
                'titre' => $evenement->titre,
                'description' => $evenement->description,
                'lieu' => $evenement->lieu,
                'type' => $evenement->type,
                'type_texte' => $evenement->type_texte,
                'type_classe' => $evenement->type_classe,
                'est_important' => $evenement->est_important,
                'est_termine' => $evenement->est_termine,
                'jour' => $evenement->jour,
                'mois' => $evenement->mois,
                'periode_aff' => $evenement->periode_aff,
                'heure_debut_aff' => $evenement->heure_debut_aff,
                'lien_inscription' => $evenement->lien_inscription,
                'image_url' => $evenement->image?->url,
                'document' => $evenement->document ? [
                    'id' => $evenement->document->id,
                    'url' => $evenement->document->url,
                    'display_name' => $evenement->document->display_name,
                    'nom_original' => $evenement->document->nom_original,
                    'mime_type' => $evenement->document->mime_type,
                    'created_at' => $evenement->document->created_at?->format('d/m/Y'),
                ] : null,
            ],
        ]);
    }
}
