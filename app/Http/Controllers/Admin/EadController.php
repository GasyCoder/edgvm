<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEadRequest;
use App\Http\Requests\UpdateEadRequest;
use App\Models\EAD;
use App\Models\Encadrant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class EadController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());

        $baseQuery = EAD::query();

        $query = $baseQuery->with(['responsable'])->withCount(['specialites', 'theses', 'encadrants']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhere('slug', 'like', '%'.$search.'%');
            });
        }

        $eads = $query
            ->orderBy('nom')
            ->paginate(12)
            ->withQueryString()
            ->through(fn (EAD $ead) => [
                'id' => $ead->id,
                'nom' => $ead->nom,
                'sigle' => $ead->sigle,
                'slug' => $ead->slug,
                'description' => $ead->description,
                'specialites_count' => $ead->specialites_count ?? 0,
                'theses_count' => $ead->theses_count ?? 0,
                'encadrants_count' => $ead->encadrants_count ?? 0,
                'responsable' => $ead->responsable ? [
                    'id' => $ead->responsable->id,
                    'name' => $ead->responsable->name,
                    'grade' => $ead->responsable->grade,
                ] : null,
            ]);

        $statsQuery = EAD::query();
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'avec_responsable' => (clone $statsQuery)->whereNotNull('responsable_id')->count(),
            'avec_specialites' => (clone $statsQuery)->whereHas('specialites')->count(),
            'avec_encadrants' => (clone $statsQuery)->whereHas('encadrants')->count(),
        ];

        return Inertia::render('Admin/Eads/Index', [
            'filters' => [
                'search' => $search,
            ],
            'eads' => $eads,
            'stats' => $stats,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Eads/Create', [
            'encadrants' => $this->encadrants(),
        ]);
    }

    public function store(StoreEadRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $encadrants = collect($data['encadrants'] ?? [])
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();
        unset($data['encadrants']);

        $data['slug'] = $this->uniqueSlug($data['nom']);

        $ead = EAD::create($data);
        $ead->encadrants()->sync($encadrants);

        return redirect()
            ->route('admin.ead.index')
            ->with('success', "L'équipe d'accueil a été créée.");
    }

    public function edit(EAD $ead): Response
    {
        return Inertia::render('Admin/Eads/Edit', [
            'ead' => [
                'id' => $ead->id,
                'slug' => $ead->slug,
                'nom' => $ead->nom,
                'sigle' => $ead->sigle,
                'description' => $ead->description,
                'responsable_id' => $ead->responsable_id,
                'encadrants' => $ead->encadrants()->pluck('encadrants.id')->toArray(),
            ],
            'encadrants' => $this->encadrants(),
        ]);
    }

    public function update(UpdateEadRequest $request, EAD $ead): RedirectResponse
    {
        $data = $request->validated();
        $encadrants = collect($data['encadrants'] ?? [])
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();
        unset($data['encadrants']);

        $data['slug'] = $this->uniqueSlug($data['nom'], $ead->id);

        $ead->update($data);
        $ead->encadrants()->sync($encadrants);

        return redirect()
            ->route('admin.ead.index')
            ->with('success', "L'équipe d'accueil a été mise à jour.");
    }

    private function uniqueSlug(string $nom, ?int $ignoreId = null): string
    {
        $base = Str::slug($nom) ?: 'ead';
        $slug = $base;
        $i = 1;

        while (EAD::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }

    public function destroy(EAD $ead): RedirectResponse
    {
        $ead->delete();

        return redirect()
            ->route('admin.ead.index')
            ->with('success', 'EAD supprime.');
    }

    private function encadrants(): array
    {
        return Encadrant::query()
            ->orderBy('id')
            ->get()
            ->map(fn (Encadrant $encadrant) => [
                'id' => $encadrant->id,
                'name' => $encadrant->name ?: 'Encadrant '.$encadrant->id,
                'grade' => $encadrant->grade,
            ])
            ->toArray();
    }
}
