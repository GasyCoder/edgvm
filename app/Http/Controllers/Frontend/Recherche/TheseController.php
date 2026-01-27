<?php

namespace App\Http\Controllers\Frontend\Recherche;

use App\Http\Controllers\Controller;
use App\Models\These;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TheseController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());
        $statut = $request->string('statut')->toString();
        $viewMode = $request->string('viewMode')->toString();

        if (! in_array($viewMode, ['grid', 'list'], true)) {
            $viewMode = 'grid';
        }

        $query = These::with(['doctorant.user', 'specialite', 'ead'])
            ->when($statut !== '', function ($query) use ($statut) {
                $query->where('statut', $statut);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $like = '%'.$search.'%';

                    $q->where('sujet_these', 'like', $like)
                        ->orWhere('resume_these', 'like', $like)
                        ->orWhere('mots_cles', 'like', $like)
                        ->orWhereHas('doctorant', function ($sub) use ($like) {
                            $sub->where('nom', 'like', $like)
                                ->orWhere('prenom', 'like', $like);
                        });
                });
            })
            ->orderByDesc('date_debut');

        $theses = $query->paginate(9)->withQueryString()->through(function (These $these): array {
            $doctorantName = $these->doctorant?->user?->name;

            return [
                'id' => $these->id,
                'sujet_these' => $these->sujet_these,
                'resume' => $this->limitText($these->resume_these, 220),
                'statut' => $these->statut,
                'statut_label' => $these->statut_label,
                'statut_badge_classes' => $these->statut_badge_classes,
                'date_debut' => $these->date_debut?->format('d/m/Y'),
                'date_publication' => $these->date_publication?->format('d/m/Y'),
                'date_prevue_fin' => $these->date_prevue_fin?->format('d/m/Y'),
                'doctorant' => $doctorantName ? [
                    'name' => $doctorantName,
                    'initials' => $this->initials($doctorantName),
                ] : null,
                'specialite' => $these->specialite ? [
                    'nom' => $these->specialite->nom ?? $these->specialite->intitule,
                ] : null,
                'ead' => $these->ead ? [
                    'nom' => $these->ead->nom,
                    'sigle' => $these->ead->sigle,
                ] : null,
            ];
        });

        return Inertia::render('Frontend/Recherche/Theses/Index', [
            'filters' => [
                'search' => $search,
                'statut' => $statut,
                'viewMode' => $viewMode,
            ],
            'theses' => $theses,
        ]);
    }

    public function show(These $these): Response
    {
        $these->load([
            'doctorant.user',
            'specialite',
            'ead',
            'encadrants',
            'jurys',
            'fichier',
        ]);

        $doctorantName = $these->doctorant?->user?->name;

        return Inertia::render('Frontend/Recherche/Theses/Show', [
            'these' => [
                'id' => $these->id,
                'sujet_these' => $these->sujet_these,
                'description' => $these->description,
                'resume_these' => $these->resume_these,
                'mots_cles' => $this->motsClesArray($these->mots_cles),
                'universite_soutenance' => $these->universite_soutenance,
                'date_debut' => $these->date_debut?->format('d/m/Y'),
                'date_prevue_fin' => $these->date_prevue_fin?->format('d/m/Y'),
                'date_publication' => $these->date_publication?->format('d/m/Y'),
                'statut' => $these->statut,
                'statut_label' => $these->statut_label,
                'statut_badge_classes' => $these->statut_badge_classes,
                'fichier_pdf_url' => $these->fichier_pdf_url,
                'doctorant' => $doctorantName ? [
                    'name' => $doctorantName,
                    'initials' => $this->initials($doctorantName),
                ] : null,
                'specialite' => $these->specialite ? [
                    'id' => $these->specialite->id,
                    'nom' => $these->specialite->nom ?? $these->specialite->intitule,
                ] : null,
                'ead' => $these->ead ? [
                    'id' => $these->ead->id,
                    'nom' => $these->ead->nom,
                    'sigle' => $these->ead->sigle,
                ] : null,
                'encadrants' => $these->encadrants->map(fn ($encadrant) => [
                    'id' => $encadrant->id,
                    'name' => $encadrant->name,
                    'email' => $encadrant->email,
                    'grade' => $encadrant->grade,
                    'role' => $encadrant->pivot?->role,
                ])->toArray(),
                'jurys' => $these->jurys->map(fn ($jury) => [
                    'id' => $jury->id,
                    'nom' => $jury->nom,
                    'role' => $jury->pivot?->role,
                    'ordre' => $jury->pivot?->ordre,
                ])->toArray(),
            ],
        ]);
    }

    private function limitText(?string $text, int $limit): ?string
    {
        if (! $text) {
            return null;
        }

        return mb_strlen($text) > $limit ? mb_substr($text, 0, $limit).'...' : $text;
    }

    private function initials(?string $name): string
    {
        if (! $name) {
            return 'NN';
        }

        $parts = preg_split('/\s+/', trim($name));
        $first = mb_substr($parts[0] ?? '', 0, 1);
        $second = mb_substr($parts[1] ?? '', 0, 1);

        return strtoupper($first.$second);
    }

    private function motsClesArray(?string $motsCles): array
    {
        if (! $motsCles) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $motsCles))));
    }
}
