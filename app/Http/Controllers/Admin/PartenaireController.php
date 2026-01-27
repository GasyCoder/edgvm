<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePartenaireRequest;
use App\Http\Requests\UpdatePartenaireRequest;
use App\Models\Partenaire;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PartenaireController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());

        $partenaires = Partenaire::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('nom', 'like', '%'.$search.'%');
            })
            ->orderBy('ordre')
            ->orderBy('nom')
            ->paginate(10)
            ->withQueryString()
            ->through(fn (Partenaire $partenaire) => [
                'id' => $partenaire->id,
                'nom' => $partenaire->nom,
                'description' => $partenaire->description,
                'url' => $partenaire->url,
                'ordre' => $partenaire->ordre,
                'visible' => $partenaire->visible,
                'logo_url' => $partenaire->logo_url,
            ]);

        return Inertia::render('Admin/Partenaires/Index', [
            'filters' => [
                'search' => $search,
            ],
            'partenaires' => $partenaires,
        ]);
    }

    public function create(): Response
    {
        $nextOrdre = (Partenaire::max('ordre') ?? 0) + 1;

        return Inertia::render('Admin/Partenaires/Create', [
            'defaults' => [
                'ordre' => $nextOrdre,
                'visible' => true,
            ],
        ]);
    }

    public function store(StorePartenaireRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('partenaires', 'public');
        }

        Partenaire::create($data);

        return redirect()->route('admin.partenaires.index')
            ->with('success', 'Partenaire cree avec succes.');
    }

    public function edit(Partenaire $partenaire): Response
    {
        return Inertia::render('Admin/Partenaires/Edit', [
            'partenaire' => [
                'id' => $partenaire->id,
                'nom' => $partenaire->nom,
                'description' => $partenaire->description,
                'url' => $partenaire->url,
                'ordre' => $partenaire->ordre,
                'visible' => $partenaire->visible,
                'logo_url' => $partenaire->logo_url,
            ],
        ]);
    }

    public function update(UpdatePartenaireRequest $request, Partenaire $partenaire): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($partenaire->logo_path) {
                Storage::disk('public')->delete($partenaire->logo_path);
            }

            $data['logo_path'] = $request->file('logo')->store('partenaires', 'public');
        }

        $partenaire->update($data);

        return redirect()->route('admin.partenaires.index')
            ->with('success', 'Partenaire mis a jour avec succes.');
    }

    public function destroy(Partenaire $partenaire): RedirectResponse
    {
        if ($partenaire->logo_path) {
            Storage::disk('public')->delete($partenaire->logo_path);
        }

        $partenaire->delete();

        return redirect()->route('admin.partenaires.index')
            ->with('success', 'Partenaire supprime avec succes.');
    }

    public function toggleVisibility(Partenaire $partenaire): RedirectResponse
    {
        $partenaire->update(['visible' => ! $partenaire->visible]);

        return redirect()->route('admin.partenaires.index')
            ->with('success', 'Visibilite mise a jour.');
    }
}
