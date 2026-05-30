<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDoctorantRequest;
use App\Http\Requests\UpdateDoctorantRequest;
use App\Mail\DoctorantFinanceReportMailable;
use App\Models\Doctorant;
use App\Models\EAD;
use App\Models\Paiement;
use App\Models\User;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class DoctorantController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());
        $filterStatut = $request->string('statut')->toString();
        $filterEad = $request->string('ead')->toString();
        $filterNiveau = $request->string('niveau')->toString();
        $showArchives = $request->boolean('archives');

        $query = Doctorant::query()
            ->with(['user', 'eadDirect', 'theses.ead', 'theses.specialite'])
            ->withCount('theses')
            ->withSum('paiements', 'montant_du')
            ->withSum('paiements', 'montant_paye');

        $showArchives ? $query->archives() : $query->actifs();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('matricule', 'like', '%'.$search.'%')
                    ->orWhere('nom', 'like', '%'.$search.'%')
                    ->orWhere('prenoms', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhereHas('theses', fn ($sub) => $sub->where('sujet_these', 'like', '%'.$search.'%'));
            });
        }

        if ($filterStatut !== '') {
            $query->where('statut', $filterStatut);
        }

        if ($filterNiveau !== '') {
            $query->where('niveau', $filterNiveau);
        }

        if ($filterEad !== '') {
            $query->where(function ($q) use ($filterEad) {
                $q->where('ead_id', $filterEad)
                    ->orWhereHas('theses', fn ($sub) => $sub->where('ead_id', $filterEad));
            });
        }

        $doctorants = $query
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString()
            ->through(function (Doctorant $doctorant) {
                $thesePrincipale = $doctorant->these_principale;
                $ead = $doctorant->eadDirect ?? $thesePrincipale?->ead;

                $du = (float) ($doctorant->paiements_sum_montant_du ?? 0);
                $paye = (float) ($doctorant->paiements_sum_montant_paye ?? 0);
                $percentPaye = $du > 0 ? min(100, (int) round($paye / $du * 100)) : null;

                return [
                    'id' => $doctorant->id,
                    'matricule' => $doctorant->matricule,
                    'name' => $doctorant->name,
                    'email' => $doctorant->user?->email ?? $doctorant->getRawOriginal('email'),
                    'niveau' => $doctorant->niveau,
                    'statut' => $doctorant->statut,
                    'paiement_percent' => $percentPaye,
                    'observation' => $doctorant->observation,
                    'archived' => $doctorant->isArchived(),
                    'sujet_these' => $thesePrincipale?->sujet_these,
                    'date_inscription' => $doctorant->date_inscription?->format('Y-m-d'),
                    'theses_count' => $doctorant->theses_count ?? 0,
                    'ead' => $ead ? [
                        'id' => $ead->id,
                        'nom' => $ead->nom,
                        'sigle' => $ead->sigle,
                    ] : null,
                ];
            });

        return Inertia::render('Admin/Doctorants/Index', [
            'filters' => [
                'search' => $search,
                'statut' => $filterStatut,
                'niveau' => $filterNiveau,
                'ead' => $filterEad,
                'archives' => $showArchives,
            ],
            'stats' => [
                'total' => Doctorant::actifs()->count(),
                'actifs' => Doctorant::actifs()->where('statut', 'actif')->count(),
                'archives' => Doctorant::archives()->count(),
            ],
            'niveaux' => $this->niveaux(),
            'statuts' => $this->statuts(),
            'eads' => EAD::orderBy('nom')->get()->map(fn (EAD $ead) => [
                'id' => $ead->id,
                'nom' => $ead->nom,
                'sigle' => $ead->sigle,
            ])->toArray(),
            'doctorants' => $doctorants,
        ]);
    }

    /**
     * Actions groupées : archiver, restaurer, supprimer, notifier.
     */
    public function bulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'in:archive,restore,delete,notify'],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:doctorants,id'],
        ]);

        $doctorants = Doctorant::query()->whereIn('id', $validated['ids'])->get();

        return match ($validated['action']) {
            'archive' => $this->bulkArchive($doctorants),
            'restore' => $this->bulkRestore($doctorants),
            'delete' => $this->bulkDelete($doctorants),
            'notify' => $this->bulkNotify($doctorants),
        };
    }

    public function updateObservation(Request $request, Doctorant $doctorant): RedirectResponse
    {
        $validated = $request->validate([
            'observation' => ['nullable', 'string', 'max:2000'],
        ]);

        $doctorant->update(['observation' => $validated['observation'] ?: null]);

        return back()->with('success', 'Observation enregistrée.');
    }

    private function bulkArchive(Collection $doctorants): RedirectResponse
    {
        $doctorants->each(fn (Doctorant $doctorant) => $doctorant->update(['archived_at' => now()]));

        return back()->with('success', $doctorants->count().' doctorant(s) archivé(s).');
    }

    private function bulkRestore(Collection $doctorants): RedirectResponse
    {
        $doctorants->each(fn (Doctorant $doctorant) => $doctorant->update(['archived_at' => null]));

        return back()->with('success', $doctorants->count().' doctorant(s) restauré(s).');
    }

    private function bulkDelete(Collection $doctorants): RedirectResponse
    {
        abort_unless(Gate::allows('records.delete'), 403);

        $count = $doctorants->count();
        $doctorants->each(fn (Doctorant $doctorant) => $doctorant->delete());

        return back()->with('success', $count.' doctorant(s) supprimé(s).');
    }

    private function bulkNotify(Collection $doctorants): RedirectResponse
    {
        abort_unless(Gate::allows('communications.access') || Gate::allows('finances.access'), 403);

        $sent = 0;

        foreach ($doctorants as $doctorant) {
            $email = $doctorant->getRawOriginal('email');

            if (! $email) {
                continue;
            }

            $du = (float) $doctorant->paiements()->sum('montant_du');
            $paye = (float) $doctorant->paiements()->sum('montant_paye');
            $reste = max(0, $du - $paye);

            Mail::to($email)->send(new DoctorantFinanceReportMailable([
                'nom' => trim($doctorant->nom.' '.$doctorant->prenoms),
                'total_du' => number_format($du, 0, ',', ' ').' Ar',
                'total_paye' => number_format($paye, 0, ',', ' ').' Ar',
                'total_reste' => number_format($reste, 0, ',', ' ').' Ar',
                'reste_brut' => $reste,
                'paiements' => [],
            ]));

            $sent++;
        }

        return back()->with('success', $sent.' notification(s) envoyée(s).');
    }

    /**
     * Génère un document imprimable (HTML prêt pour impression / PDF) pour un ou plusieurs doctorants.
     */
    public function documents(Request $request): ViewContract
    {
        $validated = $request->validate([
            'type' => ['required', 'in:admission,attestation,evaluation,rapport'],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:doctorants,id'],
        ]);

        $statutLabels = [
            'actif' => 'Actif', 'diplome' => 'Diplômé', 'suspendu' => 'Suspendu', 'abandonne' => 'Abandonné',
        ];
        $paiementStatuts = ['paye' => 'Soldé', 'partiel' => 'Partiel', 'impaye' => 'Impayé'];
        $ariary = fn (float $value): string => number_format($value, 0, ',', ' ').' Ar';

        $doctorants = Doctorant::query()
            ->with(['eadDirect', 'theses.ead', 'theses.specialite', 'paiements'])
            ->whereIn('id', $validated['ids'])
            ->orderBy('nom')
            ->get()
            ->map(function (Doctorant $doctorant) use ($statutLabels, $paiementStatuts, $ariary) {
                $these = $doctorant->these_principale;
                $ead = $doctorant->eadDirect ?? $these?->ead;

                $paiements = $doctorant->paiements->sortBy([['niveau', 'asc'], ['annee_universitaire', 'asc']])->values();
                $totalDu = (float) $paiements->sum('montant_du');
                $totalPaye = (float) $paiements->sum('montant_paye');

                return [
                    'nom' => trim($doctorant->nom.' '.$doctorant->prenoms),
                    'matricule' => $doctorant->matricule,
                    'niveau' => $doctorant->niveau,
                    'genre' => $doctorant->genre,
                    'email' => $doctorant->getRawOriginal('email'),
                    'phone' => $doctorant->phone,
                    'adresse' => $doctorant->adresse,
                    'statut' => $statutLabels[$doctorant->statut] ?? $doctorant->statut,
                    'observation' => $doctorant->observation,
                    'date_inscription' => $doctorant->date_inscription?->format('d/m/Y'),
                    'date_naissance' => $doctorant->date_naissance?->format('d/m/Y'),
                    'lieu_naissance' => $doctorant->lieu_naissance,
                    'ead' => $ead?->nom,
                    'sujet_these' => $these?->sujet_these,
                    'specialite' => $these?->specialite?->nom,
                    'finances' => [
                        'total_du' => $ariary($totalDu),
                        'total_paye' => $ariary($totalPaye),
                        'total_reste' => $ariary(max(0, $totalDu - $totalPaye)),
                        'lignes' => $paiements->map(fn (Paiement $p) => [
                            'niveau' => $p->niveau,
                            'annee' => $p->annee_universitaire,
                            'du' => $ariary((float) $p->montant_du),
                            'paye' => $ariary((float) $p->montant_paye),
                            'reste' => $ariary((float) $p->reste),
                            'statut' => $paiementStatuts[$p->statut] ?? $p->statut,
                            'date' => $p->date_paiement?->format('d/m/Y'),
                        ])->all(),
                    ],
                ];
            });

        return view('documents.doctorants', [
            'type' => $validated['type'],
            'doctorants' => $doctorants,
            'genereLe' => now()->format('d/m/Y'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Doctorants/Create', [
            'eads' => EAD::orderBy('nom')->get()->map(fn (EAD $ead) => [
                'id' => $ead->id,
                'nom' => $ead->nom,
                'sigle' => $ead->sigle,
            ])->toArray(),
            'niveaux' => $this->niveaux(),
            'statuts' => $this->statuts(),
            'defaults' => [
                'date_inscription' => now()->format('Y-m-d'),
                'niveau' => 'D1',
                'statut' => 'actif',
                'genre' => 'homme',
            ],
        ]);
    }

    public function store(StoreDoctorantRequest $request): RedirectResponse
    {
        Doctorant::create($request->validated());

        return redirect()
            ->route('admin.doctorants.index')
            ->with('success', 'Doctorant cree.');
    }

    public function show(Doctorant $doctorant): Response
    {
        $doctorant->load(['user', 'eadDirect', 'theses.ead', 'theses.specialite', 'paiements']);

        $paiements = $doctorant->paiements
            ->sortBy([['niveau', 'asc'], ['annee_universitaire', 'asc']])
            ->values();

        $totalDu = (float) $paiements->sum('montant_du');
        $totalPaye = (float) $paiements->sum('montant_paye');

        $cumul = 0;
        $series = $paiements
            ->filter(fn ($p) => $p->date_paiement !== null)
            ->sortBy('date_paiement')
            ->map(function ($p) use (&$cumul) {
                $cumul += (float) $p->montant_paye;

                return ['label' => $p->date_paiement->format('d/m/Y'), 'value' => $cumul];
            })
            ->values()
            ->all();

        return Inertia::render('Admin/Doctorants/Show', [
            'doctorant' => [
                'id' => $doctorant->id,
                'matricule' => $doctorant->matricule,
                'nom' => $doctorant->nom,
                'prenoms' => $doctorant->prenoms,
                'genre' => $doctorant->genre,
                'email' => $doctorant->getRawOriginal('email'),
                'ead_id' => $doctorant->ead_id,
                'ead' => $doctorant->eadDirect ? [
                    'id' => $doctorant->eadDirect->id,
                    'nom' => $doctorant->eadDirect->nom,
                ] : null,
                'niveau' => $doctorant->niveau,
                'statut' => $doctorant->statut,
                'date_inscription' => $doctorant->date_inscription?->format('Y-m-d'),
                'date_naissance' => $doctorant->date_naissance?->format('Y-m-d'),
                'lieu_naissance' => $doctorant->lieu_naissance,
                'phone' => $doctorant->phone,
                'adresse' => $doctorant->adresse,
                'user' => $doctorant->user ? [
                    'id' => $doctorant->user->id,
                    'name' => $doctorant->user->name,
                    'email' => $doctorant->user->email,
                ] : null,
            ],
            'theses' => $doctorant->theses->map(fn ($these) => [
                'id' => $these->id,
                'sujet_these' => $these->sujet_these,
                'statut' => $these->statut,
                'ead' => $these->ead ? [
                    'id' => $these->ead->id,
                    'nom' => $these->ead->nom,
                ] : null,
                'specialite' => $these->specialite ? [
                    'id' => $these->specialite->id,
                    'nom' => $these->specialite->nom,
                ] : null,
            ])->toArray(),
            'paiements' => $paiements->map(fn (Paiement $p) => [
                'id' => $p->id,
                'niveau' => $p->niveau,
                'annee_universitaire' => $p->annee_universitaire,
                'montant_du' => (float) $p->montant_du,
                'montant_paye' => (float) $p->montant_paye,
                'reste' => $p->reste,
                'statut' => $p->statut,
                'date_paiement' => $p->date_paiement?->format('Y-m-d'),
                'date_paiement_human' => $p->date_paiement?->format('d/m/Y'),
                'mode' => $p->mode,
                'reference' => $p->reference,
                'notes' => $p->notes,
                'justificatif_url' => $p->justificatif_path ? Storage::disk('public')->url($p->justificatif_path) : null,
            ])->values()->all(),
            'finances' => [
                'total_du' => $totalDu,
                'total_paye' => $totalPaye,
                'total_reste' => max(0, $totalDu - $totalPaye),
                'series' => $series,
            ],
            'anneeUniversitaireDefaut' => $this->anneeUniversitaireCourante(),
        ]);
    }

    private function anneeUniversitaireCourante(): string
    {
        $now = now();
        $start = $now->month >= 9 ? $now->year : $now->year - 1;

        return $start.'-'.($start + 1);
    }

    public function edit(Doctorant $doctorant): Response
    {
        return Inertia::render('Admin/Doctorants/Edit', [
            'doctorant' => [
                'id' => $doctorant->id,
                'nom' => $doctorant->nom,
                'prenoms' => $doctorant->prenoms,
                'genre' => $doctorant->genre,
                'email' => $doctorant->getRawOriginal('email'),
                'ead_id' => $doctorant->ead_id,
                'matricule' => $doctorant->matricule,
                'niveau' => $doctorant->niveau,
                'phone' => $doctorant->phone,
                'adresse' => $doctorant->adresse,
                'date_inscription' => $doctorant->date_inscription?->format('Y-m-d'),
                'date_naissance' => $doctorant->date_naissance?->format('Y-m-d'),
                'lieu_naissance' => $doctorant->lieu_naissance,
                'statut' => $doctorant->statut,
            ],
            'eads' => EAD::orderBy('nom')->get()->map(fn (EAD $ead) => [
                'id' => $ead->id,
                'nom' => $ead->nom,
                'sigle' => $ead->sigle,
            ])->toArray(),
            'niveaux' => $this->niveaux(),
            'statuts' => $this->statuts(),
        ]);
    }

    public function update(UpdateDoctorantRequest $request, Doctorant $doctorant): RedirectResponse
    {
        $doctorant->update($request->validated());

        return redirect()
            ->route('admin.doctorants.index')
            ->with('success', 'Doctorant mis a jour.');
    }

    public function destroy(Doctorant $doctorant): RedirectResponse
    {
        $doctorant->delete();

        return redirect()
            ->route('admin.doctorants.index')
            ->with('success', 'Doctorant supprime.');
    }

    private function doctorantUsers(?int $currentUserId = null): array
    {
        return User::query()
            ->where('role', 'doctorant')
            ->where(function ($query) use ($currentUserId) {
                $query->whereDoesntHave('doctorant');

                if ($currentUserId) {
                    $query->orWhere('id', $currentUserId);
                }
            })
            ->orderBy('name')
            ->get()
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ])
            ->toArray();
    }

    private function niveaux(): array
    {
        return ['D1', 'D2', 'D3'];
    }

    private function statuts(): array
    {
        return ['actif', 'diplome', 'suspendu', 'abandonne'];
    }
}
