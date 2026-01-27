<?php

namespace App\Http\Controllers\Frontend\Recherche;

use App\Http\Controllers\Controller;
use App\Models\Doctorant;
use App\Models\EAD;
use App\Models\These;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DoctorantController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());
        $filterStatut = $request->string('filterStatut')->toString();
        $filterEad = $request->string('filterEad')->toString();
        $viewMode = $request->string('viewMode')->toString();

        if (! in_array($viewMode, ['grid', 'list'], true)) {
            $viewMode = 'grid';
        }

        $query = Doctorant::with([
            'user',
            'theses.specialite',
            'theses.ead',
        ])->whereHas('theses');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($sub) use ($search) {
                    $sub->where('name', 'like', '%'.$search.'%');
                })->orWhere('matricule', 'like', '%'.$search.'%');
            });
        }

        if ($filterStatut !== '') {
            $query->whereHas('theses', function ($q) use ($filterStatut) {
                $q->where('statut', $filterStatut);
            });
        }

        if ($filterEad !== '') {
            $query->whereHas('theses', function ($q) use ($filterEad) {
                $q->where('ead_id', $filterEad);
            });
        }

        $doctorants = $query->paginate(12)->withQueryString()
            ->through(function (Doctorant $doctorant): array {
                $thesePrincipale = $this->selectThesePrincipale($doctorant->theses);
                $badge = $this->badgeForDoctorant($thesePrincipale, $doctorant->statut);

                return [
                    'id' => $doctorant->id,
                    'name' => $doctorant->user?->name,
                    'matricule' => $doctorant->matricule,
                    'initials' => $this->initials($doctorant->user?->name),
                    'badge' => $badge,
                    'these_principale' => $thesePrincipale ? [
                        'sujet_these' => $thesePrincipale->sujet_these,
                        'statut' => $thesePrincipale->statut,
                        'specialite' => $thesePrincipale->specialite ? [
                            'nom' => $thesePrincipale->specialite->nom,
                        ] : null,
                        'ead' => $thesePrincipale->ead ? [
                            'nom' => $thesePrincipale->ead->nom,
                        ] : null,
                    ] : null,
                ];
            });

        $eads = EAD::orderBy('nom')->get()->map(fn (EAD $ead) => [
            'id' => $ead->id,
            'nom' => $ead->nom,
        ])->toArray();

        $totalDoctorants = Doctorant::whereHas('theses')->count();
        $doctorantsActifs = Doctorant::where('statut', 'actif')
            ->whereHas('theses')
            ->count();

        return Inertia::render('Frontend/Recherche/Doctorants/Index', [
            'filters' => [
                'search' => $search,
                'filterStatut' => $filterStatut,
                'filterEad' => $filterEad,
                'viewMode' => $viewMode,
            ],
            'doctorants' => $doctorants,
            'eads' => $eads,
            'stats' => [
                'total' => $totalDoctorants,
                'actifs' => $doctorantsActifs,
            ],
        ]);
    }

    public function show(Doctorant $doctorant): Response
    {
        $doctorant->load([
            'user',
            'ead',
            'theses.specialite',
            'theses.ead',
            'theses.encadrants',
        ]);

        $thesePrincipale = $this->selectThesePrincipale($doctorant->theses);
        $badge = $this->badgeForDoctorant($thesePrincipale, $doctorant->statut, true);

        return Inertia::render('Frontend/Recherche/Doctorants/Show', [
            'doctorant' => [
                'id' => $doctorant->id,
                'name' => $doctorant->user?->name,
                'matricule' => $doctorant->matricule,
                'niveau' => $doctorant->niveau,
                'statut' => $doctorant->statut,
                'date_inscription' => $doctorant->date_inscription?->format('Y'),
                'initials' => $this->initials($doctorant->user?->name),
                'badge' => $badge,
                'theses_count' => $doctorant->theses->count(),
                'theses_soutenues_count' => $doctorant->theses->where('statut', 'soutenue')->count(),
            ],
            'thesePrincipale' => $thesePrincipale ? [
                'id' => $thesePrincipale->id,
                'sujet_these' => $thesePrincipale->sujet_these,
                'description' => $thesePrincipale->description,
                'resume_these' => $thesePrincipale->resume_these,
                'resume_limite' => $this->limitText($thesePrincipale->resume_these, 400),
                'show_read_more' => $this->showReadMore($thesePrincipale->resume_these),
                'statut' => $thesePrincipale->statut,
                'date_debut' => $thesePrincipale->date_debut?->format('d/m/Y'),
                'date_publication' => $thesePrincipale->date_publication?->format('d/m/Y'),
                'date_prevue_fin' => $thesePrincipale->date_prevue_fin?->format('d/m/Y'),
                'duree' => $this->dureeThese($thesePrincipale),
                'mots_cles' => $this->motsClesArray($thesePrincipale->mots_cles),
                'specialite' => $thesePrincipale->specialite ? [
                    'id' => $thesePrincipale->specialite->id,
                    'nom' => $thesePrincipale->specialite->nom,
                    'slug' => $thesePrincipale->specialite->slug,
                ] : null,
                'ead' => $thesePrincipale->ead ? [
                    'id' => $thesePrincipale->ead->id,
                    'nom' => $thesePrincipale->ead->nom,
                    'slug' => $thesePrincipale->ead->slug,
                ] : null,
                'encadrants' => $thesePrincipale->encadrants->map(fn ($encadrant) => [
                    'id' => $encadrant->id,
                    'name' => $encadrant->name,
                    'email' => $encadrant->email,
                    'grade' => $encadrant->grade,
                    'role' => $encadrant->pivot?->role ?? 'Encadrant',
                ])->toArray(),
            ] : null,
            'thesesHistorique' => $this->thesesHistorique($doctorant, $thesePrincipale)->map(fn (These $these) => [
                'id' => $these->id,
                'sujet_these' => $these->sujet_these,
                'statut' => $these->statut,
                'date_debut' => $these->date_debut?->format('Y'),
                'date_publication' => $these->date_publication?->format('Y'),
            ])->values()->toArray(),
        ]);
    }

    private function selectThesePrincipale($theses): ?These
    {
        return $theses->sortBy(function ($these) {
            return match ($these->statut) {
                'en_cours' => 0,
                'preparation' => 1,
                'redaction' => 2,
                'soutenue' => 3,
                'suspendue' => 4,
                'abandonnee' => 5,
                default => 99,
            };
        })->first();
    }

    private function badgeForDoctorant(?These $these, ?string $statutDoctorant, bool $withBorder = false): ?array
    {
        if ($these) {
            $statutThese = $these->statut;

            $class = match ($statutThese) {
                'en_cours' => 'bg-emerald-500',
                'preparation' => 'bg-amber-500',
                'redaction' => 'bg-indigo-500',
                'soutenue' => 'bg-blue-600',
                'suspendue' => 'bg-orange-500',
                'abandonnee' => 'bg-red-500',
                default => 'bg-slate-500',
            };

            if ($withBorder) {
                $class = match ($statutThese) {
                    'en_cours' => 'bg-emerald-500/20 border-emerald-300/60 text-emerald-50',
                    'preparation' => 'bg-amber-500/20 border-amber-300/60 text-amber-50',
                    'redaction' => 'bg-indigo-500/20 border-indigo-300/60 text-indigo-50',
                    'soutenue' => 'bg-blue-600/20 border-blue-300/70 text-blue-50',
                    'suspendue' => 'bg-orange-500/20 border-orange-300/60 text-orange-50',
                    'abandonnee' => 'bg-red-500/20 border-red-300/60 text-red-50',
                    default => 'bg-white/20 border-white/30 text-white',
                };
            }

            return [
                'text' => ucfirst(str_replace('_', ' ', $statutThese)),
                'class' => $class,
            ];
        }

        if ($statutDoctorant) {
            $statutDoc = strtolower($statutDoctorant);

            $class = match ($statutDoc) {
                'actif' => 'bg-emerald-500',
                'diplome' => 'bg-blue-500',
                'inactif' => 'bg-gray-400',
                default => 'bg-slate-500',
            };

            if ($withBorder) {
                $class = match ($statutDoc) {
                    'actif' => 'bg-emerald-500/20 border-emerald-300/60 text-emerald-50',
                    'diplome' => 'bg-blue-500/20 border-blue-300/60 text-blue-50',
                    'inactif' => 'bg-slate-500/30 border-slate-300/60 text-slate-50',
                    default => 'bg-white/20 border-white/30 text-white',
                };
            }

            return [
                'text' => ucfirst($statutDoctorant),
                'class' => $class,
            ];
        }

        return null;
    }

    private function thesesHistorique(Doctorant $doctorant, ?These $thesePrincipale): \Illuminate\Support\Collection
    {
        if (! $thesePrincipale) {
            return $doctorant->theses;
        }

        return $doctorant->theses->where('id', '!=', $thesePrincipale->id);
    }

    private function motsClesArray(?string $motsCles): array
    {
        if (! $motsCles) {
            return [];
        }

        return array_map('trim', explode(',', $motsCles));
    }

    private function showReadMore(?string $resume): bool
    {
        if (! $resume) {
            return false;
        }

        return mb_strlen($resume) > 400;
    }

    private function limitText(?string $text, int $limit): ?string
    {
        if (! $text) {
            return null;
        }

        return mb_strlen($text) > $limit ? mb_substr($text, 0, $limit) : $text;
    }

    private function dureeThese(These $these): ?string
    {
        if (! $these->date_debut) {
            return null;
        }

        $debut = $these->date_debut;
        $fin = $these->date_publication ?? now();

        $totalMonths = $debut->diffInMonths($fin);
        $years = intdiv($totalMonths, 12);
        $months = $totalMonths % 12;

        if ($years > 0 && $months > 0) {
            return "{$years} an".($years > 1 ? 's' : '')." et {$months} mois";
        }

        if ($years > 0) {
            return "{$years} an".($years > 1 ? 's' : '');
        }

        if ($months > 0) {
            return "{$months} mois";
        }

        return "Moins d'un mois";
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
}
