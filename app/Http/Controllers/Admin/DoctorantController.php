<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDoctorantRequest;
use App\Http\Requests\UpdateDoctorantRequest;
use App\Models\Doctorant;
use App\Models\EAD;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DoctorantController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());
        $filterStatut = $request->string('statut')->toString();
        $filterEad = $request->string('ead')->toString();

        $query = Doctorant::query()
            ->with(['user', 'eadDirect', 'theses.ead', 'theses.specialite'])
            ->withCount('theses');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('matricule', 'like', '%'.$search.'%')
                    ->orWhere('nom', 'like', '%'.$search.'%')
                    ->orWhere('prenoms', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        if ($filterStatut !== '') {
            $query->where('statut', $filterStatut);
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

                return [
                    'id' => $doctorant->id,
                    'matricule' => $doctorant->matricule,
                    'name' => $doctorant->name,
                    'email' => $doctorant->user?->email,
                    'niveau' => $doctorant->niveau,
                    'statut' => $doctorant->statut,
                    'date_inscription' => $doctorant->date_inscription?->format('Y-m-d'),
                    'theses_count' => $doctorant->theses_count ?? 0,
                    'ead' => $ead ? [
                        'id' => $ead->id,
                        'nom' => $ead->nom,
                    ] : null,
                ];
            });

        return Inertia::render('Admin/Doctorants/Index', [
            'filters' => [
                'search' => $search,
                'statut' => $filterStatut,
                'ead' => $filterEad,
            ],
            'stats' => [
                'total' => Doctorant::count(),
                'actifs' => Doctorant::where('statut', 'actif')->count(),
                'diplomes' => Doctorant::where('statut', 'diplome')->count(),
            ],
            'eads' => EAD::orderBy('nom')->get()->map(fn (EAD $ead) => [
                'id' => $ead->id,
                'nom' => $ead->nom,
            ])->toArray(),
            'doctorants' => $doctorants,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Doctorants/Create', [
            'eads' => EAD::orderBy('nom')->get()->map(fn (EAD $ead) => [
                'id' => $ead->id,
                'nom' => $ead->nom,
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
        $doctorant->load(['user', 'eadDirect', 'theses.ead', 'theses.specialite']);

        return Inertia::render('Admin/Doctorants/Show', [
            'doctorant' => [
                'id' => $doctorant->id,
                'matricule' => $doctorant->matricule,
                'nom' => $doctorant->nom,
                'prenoms' => $doctorant->prenoms,
                'genre' => $doctorant->genre,
                'email' => $doctorant->email,
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
        ]);
    }

    public function edit(Doctorant $doctorant): Response
    {
        return Inertia::render('Admin/Doctorants/Edit', [
            'doctorant' => [
                'id' => $doctorant->id,
                'nom' => $doctorant->nom,
                'prenoms' => $doctorant->prenoms,
                'genre' => $doctorant->genre,
                'email' => $doctorant->email,
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
