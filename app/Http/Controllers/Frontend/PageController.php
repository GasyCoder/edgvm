<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function show(string $slug): Response
    {
        $page = Page::with([
            'sections' => function ($query) {
                $query->orderBy('ordre');
            },
            'sections.image',
        ])
            ->where('slug', $slug)
            ->where('visible', true)
            ->firstOrFail();

        return Inertia::render('Frontend/Pages/Show', [
            'page' => [
                'id' => $page->id,
                'slug' => $page->slug,
                'titre' => $page->titre,
                'contenu' => $page->contenu,
                'updated_at' => $page->updated_at?->format('d/m/Y'),
                'sections' => $page->sections->map(fn ($section, int $index) => [
                    'id' => $section->id,
                    'index' => $index,
                    'titre' => $section->titre,
                    'contenu' => $section->contenu,
                    'image_url' => $section->image?->url,
                ])->toArray(),
            ],
        ]);
    }
}
