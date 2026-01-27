<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEvenementRequest;
use App\Http\Requests\UpdateEvenementRequest;
use App\Jobs\SendEvenementNewsletterJob;
use App\Models\Evenement;
use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class EvenementController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());
        $filterType = $request->string('type')->toString();
        $filterPublie = $request->string('publie')->toString();

        $types = $this->types();
        $typeValues = array_keys($types);

        if (! in_array($filterType, $typeValues, true)) {
            $filterType = '';
        }

        if (! in_array($filterPublie, ['all', 'publie', 'non'], true)) {
            $filterPublie = 'all';
        }

        $query = Evenement::query()->with(['image', 'document']);

        if ($search !== '') {
            $query->where('titre', 'like', '%'.$search.'%');
        }

        if ($filterType !== '') {
            $query->where('type', $filterType);
        }

        if ($filterPublie === 'publie') {
            $query->where('est_publie', true);
        } elseif ($filterPublie === 'non') {
            $query->where('est_publie', false);
        }

        $evenements = $query
            ->orderBy('est_archive')
            ->orderByDesc('est_important')
            ->orderByDesc('date_debut')
            ->paginate(15)
            ->withQueryString()
            ->through(fn (Evenement $evenement) => [
                'id' => $evenement->id,
                'titre' => $evenement->titre,
                'lieu' => $evenement->lieu,
                'type' => $evenement->type,
                'type_texte' => $evenement->type_texte,
                'type_classe' => $evenement->type_classe,
                'date_debut' => $evenement->date_debut?->format('Y-m-d'),
                'heure_debut' => $evenement->heure_debut_aff,
                'periode_aff' => $evenement->periode_aff,
                'est_publie' => $evenement->est_publie,
                'est_archive' => $evenement->est_archive,
                'est_important' => $evenement->est_important,
                'est_termine' => $evenement->est_termine,
                'image_url' => $evenement->image?->url,
                'document' => $evenement->document ? [
                    'id' => $evenement->document->id,
                    'nom' => $evenement->document->display_name,
                ] : null,
            ]);

        $baseQuery = Evenement::query();
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'publies' => (clone $baseQuery)->where('est_publie', true)->count(),
            'brouillons' => (clone $baseQuery)->where('est_publie', false)->where('est_archive', false)->count(),
            'archives' => (clone $baseQuery)->where('est_archive', true)->count(),
        ];

        return Inertia::render('Admin/Evenements/Index', [
            'filters' => [
                'search' => $search,
                'type' => $filterType,
                'publie' => $filterPublie,
            ],
            'stats' => $stats,
            'types' => $types,
            'evenements' => $evenements,
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Admin/Evenements/Create', [
            'types' => $this->types(),
            'documents' => $this->pdfMedias($request->string('pdfSearch')->toString()),
            'filters' => [
                'pdfSearch' => $request->string('pdfSearch')->toString(),
            ],
            'defaults' => [
                'date_debut' => now()->format('Y-m-d'),
                'type' => 'autre',
                'est_publie' => true,
                'est_important' => false,
            ],
        ]);
    }

    public function store(StoreEvenementRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $notifyAll = (bool) ($data['notify_all'] ?? false);
        $notifyEncadrants = (bool) ($data['notify_encadrants'] ?? false);
        $notifyDoctorants = (bool) ($data['notify_doctorants'] ?? false);
        $documentId = $data['document_media_id'] ?? null;

        unset($data['notify_all'], $data['notify_encadrants'], $data['notify_doctorants'], $data['document_media_id']);

        $imageMediaId = null;
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $path = $file->store('evenements/images', 'public');

            $media = Media::create([
                'nom_original' => $file->getClientOriginalName(),
                'nom_fichier' => basename($path),
                'chemin' => $path,
                'type' => 'image',
                'taille_bytes' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploader_id' => Auth::id(),
            ]);

            $imageMediaId = $media->id;
        }

        $evenement = Evenement::create([
            ...$data,
            'est_archive' => false,
            'image_id' => $imageMediaId,
            'document_id' => $documentId,
        ]);

        if ($notifyAll || $notifyEncadrants || $notifyDoctorants) {
            $types = [];

            if (! $notifyAll) {
                if ($notifyEncadrants) {
                    $types[] = 'encadrant';
                }

                if ($notifyDoctorants) {
                    $types[] = 'doctorant';
                }

                if (empty($types)) {
                    $types = ['__none__'];
                }
            }

            if ($notifyAll) {
                $types = [];
            }

            if ($types !== ['__none__']) {
                SendEvenementNewsletterJob::dispatch($evenement->id, $types);
            }
        }

        return redirect()->route('admin.evenements.index')
            ->with('success', 'Evenement cree avec succes.');
    }

    public function edit(Request $request, Evenement $evenement): Response
    {
        $evenement->load(['image', 'document']);
        $documentSearch = $request->string('pdfSearch')->toString();

        return Inertia::render('Admin/Evenements/Edit', [
            'evenement' => [
                'id' => $evenement->id,
                'titre' => $evenement->titre,
                'description' => $evenement->description,
                'date_debut' => $evenement->date_debut?->format('Y-m-d'),
                'heure_debut' => $evenement->heure_debut?->format('H:i'),
                'date_fin' => $evenement->date_fin?->format('Y-m-d'),
                'heure_fin' => $evenement->heure_fin?->format('H:i'),
                'lieu' => $evenement->lieu,
                'type' => $evenement->type,
                'est_important' => $evenement->est_important,
                'lien_inscription' => $evenement->lien_inscription,
                'est_publie' => $evenement->est_publie,
                'image_id' => $evenement->image_id,
                'image_url' => $evenement->image?->url,
                'document_media_id' => $evenement->document_id,
            ],
            'types' => $this->types(),
            'documents' => $this->pdfMedias($documentSearch),
            'selectedDocument' => $evenement->document ? [
                'id' => $evenement->document->id,
                'nom' => $evenement->document->display_name,
                'url' => $evenement->document->url,
            ] : null,
            'filters' => [
                'pdfSearch' => $documentSearch,
            ],
        ]);
    }

    public function update(UpdateEvenementRequest $request, Evenement $evenement): RedirectResponse
    {
        $data = $request->validated();
        $documentId = $data['document_media_id'] ?? null;
        unset($data['document_media_id']);

        $imageMediaId = $evenement->image_id;
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $path = $file->store('evenements/images', 'public');

            $media = Media::create([
                'nom_original' => $file->getClientOriginalName(),
                'nom_fichier' => basename($path),
                'chemin' => $path,
                'type' => 'image',
                'taille_bytes' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploader_id' => Auth::id(),
            ]);

            $imageMediaId = $media->id;
        }

        $evenement->update([
            ...$data,
            'image_id' => $imageMediaId,
            'document_id' => $documentId,
        ]);

        return redirect()->route('admin.evenements.index')
            ->with('success', 'Evenement mis a jour.');
    }

    public function destroy(Evenement $evenement): RedirectResponse
    {
        $evenement->delete();

        return redirect()->route('admin.evenements.index')
            ->with('success', 'Evenement supprime.');
    }

    public function togglePublication(Evenement $evenement): RedirectResponse
    {
        if ($evenement->est_archive) {
            return redirect()->route('admin.evenements.index')
                ->with('error', 'Cet evenement est archive et ne peut pas etre publie.');
        }

        $evenement->update(['est_publie' => ! $evenement->est_publie]);

        return redirect()->route('admin.evenements.index')
            ->with('success', $evenement->est_publie ? 'Evenement publie.' : 'Evenement mis en brouillon.');
    }

    public function archiver(Evenement $evenement): RedirectResponse
    {
        if (! $evenement->est_termine) {
            return redirect()->route('admin.evenements.index')
                ->with('error', "Cet evenement n'est pas encore termine.");
        }

        $evenement->update([
            'est_archive' => true,
            'est_publie' => false,
        ]);

        return redirect()->route('admin.evenements.index')
            ->with('success', 'Evenement archive avec succes.');
    }

    public function restaurer(Evenement $evenement): RedirectResponse
    {
        $evenement->update(['est_archive' => false]);

        return redirect()->route('admin.evenements.index')
            ->with('success', 'Evenement restaure depuis les archives.');
    }

    private function pdfMedias(string $search): array
    {
        $query = Media::query()->where('type', 'document');
        $term = trim($search);

        if ($term === '' || mb_strlen($term) < 2) {
            $medias = $query
                ->orderByDesc('created_at')
                ->limit(10)
                ->get();
        } else {
            $likeTerm = '%'.$term.'%';
            $medias = $query
                ->where(function ($q) use ($likeTerm) {
                    $q->where('nom_original', 'like', $likeTerm)
                        ->orWhere('nom_fichier', 'like', $likeTerm);
                })
                ->orderByDesc('created_at')
                ->limit(50)
                ->get();
        }

        return $medias->map(fn (Media $media) => [
            'id' => $media->id,
            'nom' => $media->display_name,
            'url' => $media->url,
            'mime_type' => $media->mime_type,
        ])->toArray();
    }

    private function types(): array
    {
        return [
            'soutenance' => 'Soutenance',
            'seminaire' => 'Seminaire',
            'conference' => 'Conference',
            'atelier' => 'Atelier',
            'autre' => 'Autre',
        ];
    }
}
