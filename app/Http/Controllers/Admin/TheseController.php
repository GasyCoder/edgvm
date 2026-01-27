<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTheseRequest;
use App\Http\Requests\UpdateTheseJuryRequest;
use App\Http\Requests\UpdateTheseRequest;
use App\Models\Doctorant;
use App\Models\EAD;
use App\Models\Encadrant;
use App\Models\Jury;
use App\Models\Media;
use App\Models\Specialite;
use App\Models\These;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class TheseController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());
        $filterStatut = $request->string('statut')->toString();
        $filterEad = $request->string('ead')->toString();
        $filterSpecialite = $request->string('specialite')->toString();

        $query = These::query()
            ->with(['doctorant.user', 'ead', 'specialite'])
            ->withCount(['encadrants', 'jurys']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('sujet_these', 'like', '%'.$search.'%')
                    ->orWhereHas('doctorant.user', fn ($sub) => $sub->where('name', 'like', '%'.$search.'%'))
                    ->orWhereHas('doctorant', fn ($sub) => $sub->where('matricule', 'like', '%'.$search.'%'));
            });
        }

        if ($filterStatut !== '') {
            $query->where('statut', $filterStatut);
        }

        if ($filterEad !== '') {
            $query->where('ead_id', $filterEad);
        }

        if ($filterSpecialite !== '') {
            $query->where('specialite_id', $filterSpecialite);
        }

        $theses = $query
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString()
            ->through(fn (These $these) => [
                'id' => $these->id,
                'sujet_these' => $these->sujet_these,
                'statut' => $these->statut,
                'date_debut' => $these->date_debut?->format('Y-m-d'),
                'doctorant' => $these->doctorant ? [
                    'id' => $these->doctorant->id,
                    'name' => $these->doctorant->user?->name,
                    'matricule' => $these->doctorant->matricule,
                ] : null,
                'ead' => $these->ead ? [
                    'id' => $these->ead->id,
                    'nom' => $these->ead->nom,
                ] : null,
                'specialite' => $these->specialite ? [
                    'id' => $these->specialite->id,
                    'nom' => $these->specialite->nom,
                ] : null,
                'encadrants_count' => $these->encadrants_count ?? 0,
                'jurys_count' => $these->jurys_count ?? 0,
            ]);

        $statsQuery = These::query();
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'en_cours' => (clone $statsQuery)->where('statut', 'en_cours')->count(),
            'soutenue' => (clone $statsQuery)->where('statut', 'soutenue')->count(),
        ];

        return Inertia::render('Admin/Theses/Index', [
            'filters' => [
                'search' => $search,
                'statut' => $filterStatut,
                'ead' => $filterEad,
                'specialite' => $filterSpecialite,
            ],
            'statuts' => $this->statuts(),
            'eads' => $this->eads(),
            'specialites' => $this->specialites(),
            'theses' => $theses,
            'stats' => $stats,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Theses/Create', [
            'doctorants' => $this->doctorants(),
            'eads' => $this->eads(),
            'specialites' => $this->specialites(),
            'encadrants' => $this->encadrants(),
            'documents' => $this->pdfMedias(),
            'statuts' => $this->statuts(),
            'defaults' => [
                'statut' => 'en_cours',
            ],
        ]);
    }

    public function store(StoreTheseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $encadrants = $data['encadrants'] ?? [];
        $uploadedFile = $request->file('these_file');

        unset($data['encadrants'], $data['slug'], $data['these_file']);

        if ($uploadedFile) {
            $slug = Str::slug($request->string('slug')->toString());
            $extension = $uploadedFile->getClientOriginalExtension() ?: 'pdf';
            $filename = $slug !== '' ? "{$slug}.{$extension}" : '';
            $path = $filename !== '' ? "documents/{$filename}" : '';

            if ($filename === '') {
                return back()
                    ->withErrors(['slug' => 'Le slug est invalide.'])
                    ->withInput();
            }

            if (Media::where('nom_fichier', $filename)->exists() || Storage::disk('public')->exists($path)) {
                return back()
                    ->withErrors(['slug' => 'Ce slug est deja utilise pour un fichier.'])
                    ->withInput();
            }

            $storedPath = $uploadedFile->storeAs('documents', $filename, 'public');

            $media = Media::create([
                'nom_original' => $uploadedFile->getClientOriginalName(),
                'nom_fichier' => $filename,
                'chemin' => $storedPath,
                'type' => 'document',
                'taille_bytes' => $uploadedFile->getSize(),
                'mime_type' => $uploadedFile->getMimeType() ?? 'application/pdf',
                'uploader_id' => Auth::id(),
            ]);

            $data['media_id'] = $media->id;
        }

        $these = These::create($data);

        if ($encadrants) {
            $these->encadrants()->sync($this->formatEncadrants($encadrants));
        }

        return redirect()
            ->route('admin.theses.index')
            ->with('success', 'These creee.');
    }

    public function show(These $these): Response
    {
        $these->load(['doctorant.user', 'specialite', 'ead', 'encadrants', 'jurys']);

        return Inertia::render('Admin/Theses/Show', [
            'these' => [
                'id' => $these->id,
                'sujet_these' => $these->sujet_these,
                'description' => $these->description,
                'resume_these' => $these->resume_these,
                'mots_cles' => $these->mots_cles,
                'statut' => $these->statut,
                'date_debut' => $these->date_debut?->format('Y-m-d'),
                'date_prevue_fin' => $these->date_prevue_fin?->format('Y-m-d'),
                'date_publication' => $these->date_publication?->format('Y-m-d'),
                'universite_soutenance' => $these->universite_soutenance,
                'doctorant' => $these->doctorant ? [
                    'id' => $these->doctorant->id,
                    'name' => $these->doctorant->user?->name,
                    'matricule' => $these->doctorant->matricule,
                ] : null,
                'ead' => $these->ead ? [
                    'id' => $these->ead->id,
                    'nom' => $these->ead->nom,
                ] : null,
                'specialite' => $these->specialite ? [
                    'id' => $these->specialite->id,
                    'nom' => $these->specialite->nom,
                ] : null,
                'encadrants' => $these->encadrants->map(fn (Encadrant $encadrant) => [
                    'id' => $encadrant->id,
                    'name' => $encadrant->name,
                    'email' => $encadrant->email,
                    'grade' => $encadrant->grade,
                    'role' => $encadrant->pivot?->role,
                ])->toArray(),
                'jurys' => $these->jurys->map(fn (Jury $jury) => [
                    'id' => $jury->id,
                    'nom' => $jury->nom,
                    'role' => $jury->pivot?->role,
                    'ordre' => $jury->pivot?->ordre,
                ])->toArray(),
            ],
        ]);
    }

    public function edit(These $these): Response
    {
        $these->load(['encadrants']);

        return Inertia::render('Admin/Theses/Edit', [
            'these' => [
                'id' => $these->id,
                'doctorant_id' => $these->doctorant_id,
                'sujet_these' => $these->sujet_these,
                'description' => $these->description,
                'specialite_id' => $these->specialite_id,
                'ead_id' => $these->ead_id,
                'universite_soutenance' => $these->universite_soutenance,
                'date_debut' => $these->date_debut?->format('Y-m-d'),
                'date_prevue_fin' => $these->date_prevue_fin?->format('Y-m-d'),
                'date_publication' => $these->date_publication?->format('Y-m-d'),
                'statut' => $these->statut,
                'media_id' => $these->media_id,
                'resume_these' => $these->resume_these,
                'mots_cles' => $these->mots_cles,
                'encadrants' => $these->encadrants->map(fn (Encadrant $encadrant) => [
                    'id' => $encadrant->id,
                    'role' => $encadrant->pivot?->role ?? 'directeur',
                ])->toArray(),
            ],
            'doctorants' => $this->doctorants(),
            'eads' => $this->eads(),
            'specialites' => $this->specialites(),
            'encadrants' => $this->encadrants(),
            'documents' => $this->pdfMedias(),
            'statuts' => $this->statuts(),
        ]);
    }

    public function update(UpdateTheseRequest $request, These $these): RedirectResponse
    {
        $data = $request->validated();
        $encadrants = $data['encadrants'] ?? [];
        $uploadedFile = $request->file('these_file');

        unset($data['encadrants'], $data['slug'], $data['these_file']);

        if ($uploadedFile) {
            $slug = Str::slug($request->string('slug')->toString());
            $extension = $uploadedFile->getClientOriginalExtension() ?: 'pdf';
            $filename = $slug !== '' ? "{$slug}.{$extension}" : '';
            $path = $filename !== '' ? "documents/{$filename}" : '';

            if ($filename === '') {
                return back()
                    ->withErrors(['slug' => 'Le slug est invalide.'])
                    ->withInput();
            }

            if (Media::where('nom_fichier', $filename)->exists() || Storage::disk('public')->exists($path)) {
                return back()
                    ->withErrors(['slug' => 'Ce slug est deja utilise pour un fichier.'])
                    ->withInput();
            }

            $storedPath = $uploadedFile->storeAs('documents', $filename, 'public');

            $media = Media::create([
                'nom_original' => $uploadedFile->getClientOriginalName(),
                'nom_fichier' => $filename,
                'chemin' => $storedPath,
                'type' => 'document',
                'taille_bytes' => $uploadedFile->getSize(),
                'mime_type' => $uploadedFile->getMimeType() ?? 'application/pdf',
                'uploader_id' => Auth::id(),
            ]);

            $data['media_id'] = $media->id;
        }

        $these->update($data);

        $these->encadrants()->sync($this->formatEncadrants($encadrants));

        return redirect()
            ->route('admin.theses.index')
            ->with('success', 'These mise a jour.');
    }

    public function editJury(These $these): Response
    {
        $these->load('jurys');

        return Inertia::render('Admin/Theses/JuryEdit', [
            'these' => [
                'id' => $these->id,
                'sujet_these' => $these->sujet_these,
            ],
            'jurys' => Jury::orderBy('nom')->get()->map(fn (Jury $jury) => [
                'id' => $jury->id,
                'nom' => $jury->nom,
                'grade' => $jury->grade,
                'universite' => $jury->universite,
            ])->toArray(),
            'selected' => $these->jurys->map(fn (Jury $jury) => [
                'id' => $jury->id,
                'role' => $jury->pivot?->role,
                'ordre' => $jury->pivot?->ordre,
            ])->toArray(),
            'roles' => ['president', 'rapporteur', 'examinateur', 'invite'],
        ]);
    }

    public function updateJury(UpdateTheseJuryRequest $request, These $these): RedirectResponse
    {
        $items = $request->validated()['jurys'] ?? [];

        $sync = [];
        foreach ($items as $item) {
            $sync[$item['id']] = [
                'role' => $item['role'] ?? null,
                'ordre' => $item['ordre'] ?? null,
            ];
        }

        $these->jurys()->sync($sync);

        return redirect()
            ->route('admin.theses.show', $these)
            ->with('success', 'Jury mis a jour.');
    }

    public function destroy(These $these): RedirectResponse
    {
        $these->delete();

        return redirect()
            ->route('admin.theses.index')
            ->with('success', 'These supprimee.');
    }

    private function formatEncadrants(array $encadrants): array
    {
        $formatted = [];

        foreach ($encadrants as $item) {
            $formatted[$item['id']] = [
                'role' => $item['role'] ?? 'directeur',
            ];
        }

        return $formatted;
    }

    private function doctorants(): array
    {
        return Doctorant::query()
            ->orderBy('id')
            ->get()
            ->map(fn (Doctorant $doctorant) => [
                'id' => $doctorant->id,
                'name' => $doctorant->name,
                'matricule' => $doctorant->matricule,
            ])
            ->toArray();
    }

    private function eads(): array
    {
        return EAD::orderBy('nom')->get()->map(fn (EAD $ead) => [
            'id' => $ead->id,
            'nom' => $ead->nom,
        ])->toArray();
    }

    private function specialites(): array
    {
        return Specialite::orderBy('nom')->get()->map(fn (Specialite $specialite) => [
            'id' => $specialite->id,
            'nom' => $specialite->nom,
            'ead_id' => $specialite->ead_id,
        ])->toArray();
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

    private function pdfMedias(): array
    {
        return Media::query()
            ->where('mime_type', 'application/pdf')
            ->orderByDesc('id')
            ->limit(50)
            ->get()
            ->map(fn (Media $media) => [
                'id' => $media->id,
                'name' => $media->display_name,
                'url' => $media->url,
            ])
            ->toArray();
    }

    private function statuts(): array
    {
        return [
            'en_cours',
            'soutenue',
            'abandonnee',
            'suspendue',
            'preparation',
            'redaction',
        ];
    }
}
