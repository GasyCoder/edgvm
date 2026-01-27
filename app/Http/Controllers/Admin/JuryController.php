<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJuryRequest;
use App\Http\Requests\UpdateJuryRequest;
use App\Models\Jury;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class JuryController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());

        $query = Jury::query()->withCount('theses');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', '%'.$search.'%')
                    ->orWhere('grade', 'like', '%'.$search.'%')
                    ->orWhere('universite', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        $jurys = $query
            ->orderBy('nom')
            ->paginate(12)
            ->withQueryString()
            ->through(fn (Jury $jury) => [
                'id' => $jury->id,
                'nom' => $jury->nom,
                'grade' => $jury->grade,
                'universite' => $jury->universite,
                'email' => $jury->email,
                'phone' => $jury->phone,
                'theses_count' => $jury->theses_count ?? 0,
            ]);

        $statsQuery = Jury::query();
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'avec_theses' => (clone $statsQuery)->whereHas('theses')->count(),
        ];

        return Inertia::render('Admin/Jurys/Index', [
            'filters' => [
                'search' => $search,
            ],
            'jurys' => $jurys,
            'stats' => $stats,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Jurys/Create');
    }

    public function store(StoreJuryRequest $request): RedirectResponse
    {
        Jury::create($request->validated());

        return redirect()
            ->route('admin.jurys.index')
            ->with('success', 'Jury cree.');
    }

    public function edit(Jury $jury): Response
    {
        return Inertia::render('Admin/Jurys/Edit', [
            'jury' => [
                'id' => $jury->id,
                'nom' => $jury->nom,
                'grade' => $jury->grade,
                'universite' => $jury->universite,
                'email' => $jury->email,
                'phone' => $jury->phone,
            ],
        ]);
    }

    public function update(UpdateJuryRequest $request, Jury $jury): RedirectResponse
    {
        $jury->update($request->validated());

        return redirect()
            ->route('admin.jurys.index')
            ->with('success', 'Jury mis a jour.');
    }

    public function destroy(Jury $jury): RedirectResponse
    {
        $jury->delete();

        return redirect()
            ->route('admin.jurys.index')
            ->with('success', 'Jury supprime.');
    }
}
