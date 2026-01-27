<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEncadrantRequest;
use App\Http\Requests\UpdateEncadrantRequest;
use App\Models\Encadrant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EncadrantController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());
        $filterGrade = $request->string('grade')->toString();

        $query = Encadrant::query()
            ->withCount('theses');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('specialite', 'like', '%'.$search.'%')
                    ->orWhere('grade', 'like', '%'.$search.'%')
                    ->orWhere('nom', 'like', '%'.$search.'%')
                    ->orWhere('prenoms', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        if ($filterGrade !== '') {
            $query->where('grade', $filterGrade);
        }

        $encadrants = $query
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString()
            ->through(fn (Encadrant $encadrant) => [
                'id' => $encadrant->id,
                'name' => $encadrant->name ?: 'Encadrant '.$encadrant->id,
                'email' => $encadrant->email,
                'grade' => $encadrant->grade,
                'specialite' => $encadrant->specialite,
                'phone' => $encadrant->phone,
                'theses_count' => $encadrant->theses_count ?? 0,
            ]);

        $statsQuery = Encadrant::query();
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'avec_theses' => (clone $statsQuery)->whereHas('theses')->count(),
        ];

        return Inertia::render('Admin/Encadrants/Index', [
            'filters' => [
                'search' => $search,
                'grade' => $filterGrade,
            ],
            'grades' => $this->grades(),
            'encadrants' => $encadrants,
            'stats' => $stats,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Encadrants/Create', [
            'grades' => $this->grades(),
        ]);
    }

    public function store(StoreEncadrantRequest $request): RedirectResponse
    {
        Encadrant::create($request->validated());

        return redirect()
            ->route('admin.encadrants.index')
            ->with('success', 'Encadrant cree.');
    }

    public function show(Encadrant $encadrant): Response
    {
        $encadrant->load(['theses.doctorant.user']);

        return Inertia::render('Admin/Encadrants/Show', [
            'encadrant' => [
                'id' => $encadrant->id,
                'nom' => $encadrant->nom,
                'prenoms' => $encadrant->prenoms,
                'email' => $encadrant->email,
                'genre' => $encadrant->genre,
                'grade' => $encadrant->grade,
                'specialite' => $encadrant->specialite,
                'phone' => $encadrant->phone,
                'bureau' => $encadrant->bureau,
            ],
            'theses' => $encadrant->theses->map(fn ($these) => [
                'id' => $these->id,
                'sujet_these' => $these->sujet_these,
                'statut' => $these->statut,
                'doctorant' => $these->doctorant ? [
                    'id' => $these->doctorant->id,
                    'name' => $these->doctorant->user?->name,
                    'matricule' => $these->doctorant->matricule,
                ] : null,
            ])->toArray(),
        ]);
    }

    public function edit(Encadrant $encadrant): Response
    {
        return Inertia::render('Admin/Encadrants/Edit', [
            'encadrant' => [
                'id' => $encadrant->id,
                'nom' => $encadrant->nom,
                'prenoms' => $encadrant->prenoms,
                'email' => $encadrant->email,
                'genre' => $encadrant->genre,
                'grade' => $encadrant->grade,
                'specialite' => $encadrant->specialite,
                'phone' => $encadrant->phone,
                'bureau' => $encadrant->bureau,
            ],
            'grades' => $this->grades(),
        ]);
    }

    public function update(UpdateEncadrantRequest $request, Encadrant $encadrant): RedirectResponse
    {
        $encadrant->update($request->validated());

        return redirect()
            ->route('admin.encadrants.index')
            ->with('success', 'Encadrant mis a jour.');
    }

    public function destroy(Encadrant $encadrant): RedirectResponse
    {
        $encadrant->delete();

        return redirect()
            ->route('admin.encadrants.index')
            ->with('success', 'Encadrant supprime.');
    }

    private function grades(): array
    {
        return Encadrant::query()
            ->select('grade')
            ->whereNotNull('grade')
            ->distinct()
            ->orderBy('grade')
            ->pluck('grade')
            ->filter()
            ->values()
            ->toArray();
    }
}
