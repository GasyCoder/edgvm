<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSpecialiteRequest;
use App\Http\Requests\UpdateSpecialiteRequest;
use App\Models\EAD;
use App\Models\Specialite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class SpecialiteController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());
        $filterEad = $request->string('ead')->toString();

        $query = Specialite::query()
            ->with('ead')
            ->withCount('theses');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhere('slug', 'like', '%'.$search.'%');
            });
        }

        if ($filterEad !== '') {
            $query->whereHas('ead', fn ($q) => $q->where('id', $filterEad));
        }

        $specialites = $query
            ->orderBy('nom')
            ->paginate(12)
            ->withQueryString()
            ->through(fn (Specialite $specialite) => [
                'id' => $specialite->id,
                'nom' => $specialite->nom,
                'slug' => $specialite->slug,
                'description' => $specialite->description,
                'ead' => $specialite->ead ? [
                    'id' => $specialite->ead->id,
                    'nom' => $specialite->ead->nom,
                ] : null,
                'theses_count' => $specialite->theses_count ?? 0,
            ]);

        $statsQuery = Specialite::query();
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'avec_ead' => (clone $statsQuery)->whereNotNull('ead_id')->count(),
            'avec_theses' => (clone $statsQuery)->whereHas('theses')->count(),
        ];

        return Inertia::render('Admin/Specialites/Index', [
            'filters' => [
                'search' => $search,
                'ead' => $filterEad,
            ],
            'eads' => EAD::orderBy('nom')->get()->map(fn (EAD $ead) => [
                'id' => $ead->id,
                'nom' => $ead->nom,
            ])->toArray(),
            'specialites' => $specialites,
            'stats' => $stats,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Specialites/Create', [
            'eads' => $this->eads(),
        ]);
    }

    public function store(StoreSpecialiteRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->generateUniqueSlug($data['nom']);

        Specialite::create($data);

        return redirect()
            ->route('admin.specialites.index')
            ->with('success', 'Specialite creee.');
    }

    public function edit(Specialite $specialite): Response
    {
        return Inertia::render('Admin/Specialites/Edit', [
            'specialite' => [
                'id' => $specialite->id,
                'nom' => $specialite->nom,
                'slug' => $specialite->slug,
                'description' => $specialite->description,
                'ead_id' => $specialite->ead_id,
            ],
            'eads' => $this->eads(),
        ]);
    }

    public function update(UpdateSpecialiteRequest $request, Specialite $specialite): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->generateUniqueSlug($data['nom'], $specialite->id);

        $specialite->update($data);

        return redirect()
            ->route('admin.specialites.index')
            ->with('success', 'Specialite mise a jour.');
    }

    public function destroy(Specialite $specialite): RedirectResponse
    {
        $specialite->delete();

        return redirect()
            ->route('admin.specialites.index')
            ->with('success', 'Specialite supprimee.');
    }

    private function eads(): array
    {
        return EAD::orderBy('nom')->get()->map(fn (EAD $ead) => [
            'id' => $ead->id,
            'nom' => $ead->nom,
        ])->toArray();
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 2;

        while ($slug !== '') {
            $query = Specialite::query()->where('slug', $slug);

            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }

            if (! $query->exists()) {
                return $slug;
            }

            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return Str::uuid()->toString();
    }
}
