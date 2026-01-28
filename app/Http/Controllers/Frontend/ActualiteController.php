<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Actualite;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ActualiteController extends Controller
{
    public function index(Request $request): Response
    {
        $category = $request->string('category')->toString();
        $tag = $request->string('tag')->toString();
        $search = trim($request->string('search')->toString());
        $view = $request->string('view')->toString();

        if (! in_array($view, ['grid', 'list'], true)) {
            $view = 'grid';
        }

        $query = Actualite::with(['category', 'categories', 'image', 'tags', 'auteur'])
            ->withSlug()
            ->published();

        if ($category !== '') {
            $query->whereHas('categories', fn ($q) => $q->where('slug', $category));
        }

        if ($tag !== '') {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $tag));
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', '%'.$search.'%')
                    ->orWhere('contenu', 'like', '%'.$search.'%');
            });
        }

        $actualites = $query->orderBy('est_important', 'desc')
            ->orderBy('date_publication', 'desc')
            ->paginate($view === 'list' ? 10 : 9)
            ->withQueryString()
            ->through(function (Actualite $actualite): array {
                return [
                    'id' => $actualite->id,
                    'titre' => $actualite->titre,
                    'slug' => $actualite->slug,
                    'contenu_extrait' => $this->extractText($actualite->contenu, 250),
                    'resume' => $this->extractText($actualite->contenu, 110),
                    'reading_time' => $this->readingTime($actualite->contenu),
                    'date_publication' => $actualite->date_publication?->translatedFormat('d M Y'),
                    'vues_formatted' => $actualite->vues_formatted,
                    'est_important' => $actualite->est_important,
                    'image_url' => $actualite->image?->url,
                    'category' => $actualite->category ? [
                        'id' => $actualite->category->id,
                        'nom' => $actualite->category->nom,
                        'slug' => $actualite->category->slug,
                        'couleur' => $actualite->category->couleur,
                    ] : null,
                    'auteur' => $actualite->auteur ? [
                        'name' => $actualite->auteur->name,
                    ] : null,
                    'tags' => $actualite->tags->map(fn ($tagItem) => [
                        'id' => $tagItem->id,
                        'nom' => $tagItem->nom,
                    ])->toArray(),
                ];
            });

        $categories = Category::visible()
            ->whereHas('actualites', fn ($q) => $q->withSlug()->published())
            ->withCount(['actualites' => fn ($q) => $q->withSlug()->published()])
            ->orderBy('nom')
            ->get()
            ->map(fn (Category $item) => [
                'id' => $item->id,
                'slug' => $item->slug,
                'nom' => $item->nom,
                'couleur' => $item->couleur,
                'actualites_count' => $item->actualites_count,
            ])
            ->toArray();

        $tags = Tag::whereHas('actualites', fn ($q) => $q->withSlug()->published())
            ->withCount(['actualites' => fn ($q) => $q->withSlug()->published()])
            ->orderBy('nom')
            ->get()
            ->map(fn (Tag $item) => [
                'id' => $item->id,
                'slug' => $item->slug,
                'nom' => $item->nom,
                'actualites_count' => $item->actualites_count,
            ])
            ->toArray();

        return Inertia::render('Frontend/Actualites/Index', [
            'filters' => [
                'category' => $category,
                'tag' => $tag,
                'search' => $search,
                'view' => $view,
            ],
            'actualites' => $actualites,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    public function show(Request $request, Actualite $actualite): Response
    {
        if (! $actualite->visible || $actualite->date_publication > now()) {
            abort(404);
        }

        $this->incrementUniqueView($request, $actualite);

        $actualite->load(['category', 'categories', 'image', 'tags', 'auteur', 'galerie']);

        $categoryIds = $actualite->categories->pluck('id')->values();
        if ($categoryIds->isEmpty() && $actualite->category_id) {
            $categoryIds = collect([$actualite->category_id]);
        }

        $similaires = Actualite::with(['category', 'image'])
            ->withSlug()
            ->published()
            ->where('id', '!=', $actualite->id)
            ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $categoryIds))
            ->limit(3)
            ->get();

        if ($similaires->count() < 3) {
            $similaires = Actualite::with(['category', 'image'])
                ->withSlug()
                ->published()
                ->where('id', '!=', $actualite->id)
                ->latest('date_publication')
                ->limit(3)
                ->get();
        }

        $precedent = Actualite::withSlug()
            ->published()
            ->where('date_publication', '<', $actualite->date_publication)
            ->orderBy('date_publication', 'desc')
            ->first();

        $suivant = Actualite::withSlug()
            ->published()
            ->where('date_publication', '>', $actualite->date_publication)
            ->orderBy('date_publication', 'asc')
            ->first();

        return Inertia::render('Frontend/Actualites/Show', [
            'actualite' => [
                'id' => $actualite->id,
                'titre' => $actualite->titre,
                'slug' => $actualite->slug,
                'contenu' => $actualite->contenu,
                'vues_formatted' => $actualite->vues_formatted,
                'est_important' => $actualite->est_important,
                'date_publication_human' => $actualite->date_publication?->locale('fr')->diffForHumans(),
                'date_publication' => $actualite->date_publication?->translatedFormat('d M Y'),
                'image_url' => $actualite->image?->url,
                'category' => $actualite->category ? [
                    'id' => $actualite->category->id,
                    'nom' => $actualite->category->nom,
                    'slug' => $actualite->category->slug,
                    'couleur' => $actualite->category->couleur,
                ] : null,
                'auteur' => $actualite->auteur ? [
                    'name' => $actualite->auteur->name,
                ] : null,
                'tags' => $actualite->tags->map(fn ($tagItem) => [
                    'id' => $tagItem->id,
                    'nom' => $tagItem->nom,
                    'slug' => $tagItem->slug,
                ])->toArray(),
                'galerie' => $actualite->galerie->map(fn ($media) => [
                    'id' => $media->id,
                    'url' => $media->url,
                ])->toArray(),
            ],
            'similaires' => $similaires->map(fn (Actualite $item) => [
                'id' => $item->id,
                'slug' => $item->slug,
                'titre' => $item->titre,
                'image_url' => $item->image?->url,
            ])->toArray(),
            'precedent' => $precedent ? [
                'id' => $precedent->id,
                'slug' => $precedent->slug,
                'titre' => $precedent->titre,
            ] : null,
            'suivant' => $suivant ? [
                'id' => $suivant->id,
                'slug' => $suivant->slug,
                'titre' => $suivant->titre,
            ] : null,
            'shareUrl' => $request->fullUrl(),
        ]);
    }

    private function readingTime(?string $content): int
    {
        $wordCount = str_word_count(strip_tags($content ?? ''));

        return max(1, (int) ceil($wordCount / 200));
    }

    private function extractText(?string $content, int $limit): string
    {
        $text = trim(strip_tags($content ?? ''));

        if ($text === '') {
            return '';
        }

        return mb_strlen($text) > $limit ? mb_substr($text, 0, $limit - 3).'...' : $text;
    }

    private function incrementUniqueView(Request $request, Actualite $actualite): void
    {
        $key = 'actualite_viewed_'.$actualite->id;

        if (! $request->session()->has($key)) {
            $actualite->incrementVues();
            $request->session()->put($key, true);
        }
    }
}
