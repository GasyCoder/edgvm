<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreActualiteRequest;
use App\Http\Requests\UpdateActualiteRequest;
use App\Jobs\SendActualiteNotification;
use App\Models\Actualite;
use App\Models\Category;
use App\Models\Media;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ActualiteController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());
        $filterVisible = $request->string('visible')->toString();

        if (! in_array($filterVisible, ['all', 'visible', 'hidden'], true)) {
            $filterVisible = 'all';
        }

        $query = Actualite::with(['category', 'image', 'auteur'])
            ->withSlug();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', '%'.$search.'%')
                    ->orWhere('contenu', 'like', '%'.$search.'%');
            });
        }

        if ($filterVisible === 'visible') {
            $query->where('visible', true);
        } elseif ($filterVisible === 'hidden') {
            $query->where('visible', false);
        }

        $actualites = $query->latest()
            ->paginate(15)
            ->withQueryString()
            ->through(fn (Actualite $actualite) => [
                'id' => $actualite->id,
                'slug' => $actualite->slug,
                'titre' => $actualite->titre,
                'extrait' => $this->extractText($actualite->contenu, 90),
                'date_publication' => $actualite->date_publication?->format('d/m/Y'),
                'visible' => $actualite->visible,
                'vues_formatted' => $actualite->vues_formatted,
                'image_url' => $actualite->image?->url,
                'category' => $actualite->category ? [
                    'id' => $actualite->category->id,
                    'nom' => $actualite->category->nom,
                    'couleur' => $actualite->category->couleur,
                ] : null,
                'auteur' => $actualite->auteur ? [
                    'name' => $actualite->auteur->name,
                ] : null,
            ]);

        $baseQuery = Actualite::withSlug();
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'visibles' => (clone $baseQuery)->where('visible', true)->count(),
            'cachees' => (clone $baseQuery)->where('visible', false)->count(),
        ];

        return Inertia::render('Admin/Actualites/Index', [
            'filters' => [
                'search' => $search,
                'visible' => $filterVisible,
            ],
            'stats' => $stats,
            'actualites' => $actualites,
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Admin/Actualites/Create', [
            'categories' => Category::visible()->orderBy('nom')->get()->map(fn (Category $category) => [
                'id' => $category->id,
                'nom' => $category->nom,
            ])->toArray(),
            'tags' => Tag::orderBy('nom')->get()->map(fn (Tag $tag) => [
                'id' => $tag->id,
                'nom' => $tag->nom,
            ])->toArray(),
            'media' => $this->mediaSelector($request),
            'defaults' => [
                'date_publication' => now()->format('Y-m-d'),
                'visible' => true,
                'est_important' => false,
                'notifier_abonnes' => false,
            ],
        ]);
    }

    public function store(StoreActualiteRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $categoryIds = array_values($data['category_ids'] ?? []);
        $selectedTags = $data['selectedTags'] ?? [];
        $galerieImages = $data['galerieImages'] ?? [];
        $notify = (bool) ($data['notifier_abonnes'] ?? false);

        unset($data['category_ids'], $data['selectedTags'], $data['galerieImages'], $data['notifier_abonnes']);

        $data['category_id'] = $categoryIds[0] ?? null;

        $actualite = Actualite::create([
            ...$data,
            'auteur_id' => Auth::id(),
        ]);

        if (! empty($categoryIds)) {
            $actualite->categories()->sync($categoryIds);
        }

        if (! empty($selectedTags)) {
            $actualite->tags()->attach($selectedTags);
        }

        if (! empty($galerieImages)) {
            foreach (array_values($galerieImages) as $index => $mediaId) {
                $actualite->galerie()->attach($mediaId, ['ordre' => $index]);
            }
        }

        if ($notify && $actualite->visible) {
            SendActualiteNotification::dispatch($actualite);

            return redirect()->route('admin.actualites.index')
                ->with('success', 'Actualite creee et notification envoyee.');
        }

        return redirect()->route('admin.actualites.index')
            ->with('success', 'Actualite creee.');
    }

    public function edit(Request $request, Actualite $actualite): Response
    {
        $actualite->load(['tags', 'image', 'galerie', 'category', 'categories']);
        $categoryIds = $actualite->categories->pluck('id')->values();
        if ($categoryIds->isEmpty() && $actualite->category_id) {
            $categoryIds = collect([$actualite->category_id]);
        }

        return Inertia::render('Admin/Actualites/Edit', [
            'actualite' => [
                'id' => $actualite->id,
                'slug' => $actualite->slug,
                'titre' => $actualite->titre,
                'contenu' => $actualite->contenu,
                'category_ids' => $categoryIds->values()->toArray(),
                'image_id' => $actualite->image_id,
                'image_url' => $actualite->image?->url,
                'date_publication' => $actualite->date_publication?->format('Y-m-d'),
                'visible' => $actualite->visible,
                'est_important' => $actualite->est_important,
                'vues_formatted' => $actualite->vues_formatted,
                'selectedTags' => $actualite->tags->pluck('id')->values()->toArray(),
                'galerieImages' => $actualite->galerie->pluck('id')->values()->toArray(),
                'galerieImagesData' => $actualite->galerie->map(fn (Media $media) => [
                    'id' => $media->id,
                    'url' => $media->url,
                ])->toArray(),
            ],
            'categories' => Category::visible()->orderBy('nom')->get()->map(fn (Category $category) => [
                'id' => $category->id,
                'nom' => $category->nom,
            ])->toArray(),
            'tags' => Tag::orderBy('nom')->get()->map(fn (Tag $tag) => [
                'id' => $tag->id,
                'nom' => $tag->nom,
            ])->toArray(),
            'media' => $this->mediaSelector($request),
        ]);
    }

    public function update(UpdateActualiteRequest $request, Actualite $actualite): RedirectResponse
    {
        $data = $request->validated();
        $categoryIds = array_values($data['category_ids'] ?? []);
        $selectedTags = $data['selectedTags'] ?? [];
        $galerieImages = $data['galerieImages'] ?? [];

        unset($data['category_ids'], $data['selectedTags'], $data['galerieImages']);

        $data['category_id'] = $categoryIds[0] ?? null;

        $actualite->update($data);

        $actualite->categories()->sync($categoryIds);
        $actualite->tags()->sync($selectedTags);

        $galerieData = [];
        foreach (array_values($galerieImages) as $index => $mediaId) {
            $galerieData[$mediaId] = ['ordre' => $index];
        }
        $actualite->galerie()->sync($galerieData);

        return redirect()->route('admin.actualites.index')
            ->with('success', 'Actualite mise a jour.');
    }

    public function destroy(Actualite $actualite): RedirectResponse
    {
        $actualite->delete();

        return redirect()->route('admin.actualites.index')
            ->with('success', 'Actualite supprimee.');
    }

    public function toggleVisibility(Actualite $actualite): RedirectResponse
    {
        $actualite->update(['visible' => ! $actualite->visible]);

        return redirect()->route('admin.actualites.index')
            ->with('success', 'Visibilite mise a jour.');
    }

    private function mediaSelector(Request $request): array
    {
        $mediaPage = $request->integer('media_page', 1);

        return Media::where('type', 'image')
            ->orderBy('created_at', 'desc')
            ->paginate(12, ['*'], 'media_page', $mediaPage)
            ->withQueryString()
            ->through(fn (Media $media) => [
                'id' => $media->id,
                'url' => $media->url,
            ])
            ->toArray();
    }

    private function extractText(?string $content, int $limit): string
    {
        $text = trim(strip_tags($content ?? ''));

        if ($text === '') {
            return '';
        }

        return mb_strlen($text) > $limit ? mb_substr($text, 0, $limit - 3).'...' : $text;
    }
}
