<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSlideRequest;
use App\Http\Requests\UpdateSlideOrderRequest;
use App\Http\Requests\UpdateSlideRequest;
use App\Models\Actualite;
use App\Models\Media;
use App\Models\Slide;
use App\Models\Slider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class SlideController extends Controller
{
    public function index(Request $request, Slider $slider): Response
    {
        $search = trim($request->string('search')->toString());

        $slides = $slider->slides()
            ->with('image')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('titre_highlight', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            })
            ->orderBy('ordre')
            ->paginate(10)
            ->withQueryString()
            ->through(fn (Slide $slide) => [
                'id' => $slide->id,
                'titre_highlight' => $slide->titre_highlight,
                'titre_ligne1' => $slide->titre_ligne1,
                'titre_ligne2' => $slide->titre_ligne2,
                'description' => $slide->description,
                'image_url' => $slide->image?->url,
                'ordre' => $slide->ordre,
                'visible' => $slide->visible,
                'badge_texte' => $slide->badge_texte,
                'badge_icon' => $slide->badge_icon,
                'couleur_fond' => $slide->couleur_fond,
            ]);

        return Inertia::render('Admin/Slides/Index', [
            'slider' => [
                'id' => $slider->id,
                'nom' => $slider->nom,
            ],
            'slides' => $slides,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function create(Request $request, Slider $slider): Response
    {
        $searchActualite = $request->string('actualiteSearch')->toString();

        return Inertia::render('Admin/Slides/Create', [
            'slider' => [
                'id' => $slider->id,
                'nom' => $slider->nom,
            ],
            'images' => $this->imageMedias(),
            'actualiteResults' => $this->actualiteResults($searchActualite),
            'filters' => [
                'actualiteSearch' => $searchActualite,
            ],
            'defaults' => [
                'ordre' => ($slider->slides()->max('ordre') ?? 0) + 1,
                'visible' => true,
                'badge_icon' => 'university',
                'couleur_fond' => 'from-ed-primary/95 via-ed-secondary/90 to-teal-800/95',
            ],
        ]);
    }

    public function store(StoreSlideRequest $request, Slider $slider): RedirectResponse
    {
        $data = $request->validated();
        $imageId = $data['image_id'] ?? null;
        $actualiteId = $data['actualite_id'] ?? null;

        if ($request->hasFile('new_image')) {
            $file = $request->file('new_image');
            $path = $file->store('slides', 'public');

            $media = Media::create([
                'nom_original' => $file->getClientOriginalName(),
                'nom_fichier' => basename($path),
                'chemin' => $path,
                'type' => 'image',
                'taille_bytes' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploader_id' => Auth::id(),
            ]);

            $imageId = $media->id;
        }

        if ($actualiteId) {
            $actualite = Actualite::select('id', 'slug')->find($actualiteId);
            if ($actualite && $actualite->slug) {
                $data['lien_cta'] = route('actualites.show', ['actualite' => $actualite->slug]);
            }
        }

        Slide::create([
            ...$data,
            'slider_id' => $slider->id,
            'image_id' => $imageId,
        ]);

        return redirect()->route('admin.slides.index', $slider)
            ->with('success', 'Slide cree avec succes.');
    }

    public function edit(Request $request, Slider $slider, Slide $slide): Response
    {
        $this->ensureSlideBelongsToSlider($slider, $slide);
        $searchActualite = $request->string('actualiteSearch')->toString();

        $actualitePreview = null;
        if ($slide->actualite_id) {
            $actualitePreview = Actualite::select('id', 'titre', 'slug', 'date_publication')
                ->find($slide->actualite_id);
        }

        return Inertia::render('Admin/Slides/Edit', [
            'slider' => [
                'id' => $slider->id,
                'nom' => $slider->nom,
            ],
            'slide' => [
                'id' => $slide->id,
                'titre_highlight' => $slide->titre_highlight,
                'titre_ligne1' => $slide->titre_ligne1,
                'titre_ligne2' => $slide->titre_ligne2,
                'description' => $slide->description,
                'image_id' => $slide->image_id,
                'image_url' => $slide->image?->url,
                'lien_cta' => $slide->lien_cta,
                'texte_cta' => $slide->texte_cta,
                'ordre' => $slide->ordre,
                'visible' => $slide->visible,
                'badge_texte' => $slide->badge_texte,
                'badge_icon' => $slide->badge_icon,
                'couleur_fond' => $slide->couleur_fond,
                'actualite_id' => $slide->actualite_id,
            ],
            'images' => $this->imageMedias(),
            'actualiteResults' => $this->actualiteResults($searchActualite),
            'actualitePreview' => $actualitePreview ? [
                'id' => $actualitePreview->id,
                'titre' => $actualitePreview->titre,
                'slug' => $actualitePreview->slug,
                'date_publication' => $actualitePreview->date_publication?->format('Y-m-d'),
            ] : null,
            'filters' => [
                'actualiteSearch' => $searchActualite,
            ],
        ]);
    }

    public function update(UpdateSlideRequest $request, Slider $slider, Slide $slide): RedirectResponse
    {
        $this->ensureSlideBelongsToSlider($slider, $slide);
        $data = $request->validated();
        $imageId = $data['image_id'] ?? $slide->image_id;
        $actualiteId = $data['actualite_id'] ?? null;

        if ($request->hasFile('new_image')) {
            $file = $request->file('new_image');
            $path = $file->store('slides', 'public');

            $media = Media::create([
                'nom_original' => $file->getClientOriginalName(),
                'nom_fichier' => basename($path),
                'chemin' => $path,
                'type' => 'image',
                'taille_bytes' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploader_id' => Auth::id(),
            ]);

            $imageId = $media->id;
        }

        if ($actualiteId) {
            $actualite = Actualite::select('id', 'slug')->find($actualiteId);
            if ($actualite && $actualite->slug) {
                $data['lien_cta'] = route('actualites.show', ['actualite' => $actualite->slug]);
            }
        }

        $slide->update([
            ...$data,
            'image_id' => $imageId,
        ]);

        return redirect()->route('admin.slides.index', $slider)
            ->with('success', 'Slide mis a jour avec succes.');
    }

    public function destroy(Slider $slider, Slide $slide): RedirectResponse
    {
        $this->ensureSlideBelongsToSlider($slider, $slide);
        $slide->delete();

        return redirect()->route('admin.slides.index', $slider)
            ->with('success', 'Slide supprime avec succes.');
    }

    public function toggleVisibility(Slider $slider, Slide $slide): RedirectResponse
    {
        $this->ensureSlideBelongsToSlider($slider, $slide);
        $slide->update(['visible' => ! $slide->visible]);

        return redirect()->route('admin.slides.index', $slider)
            ->with('success', 'Visibilite mise a jour.');
    }

    public function updateOrder(UpdateSlideOrderRequest $request, Slider $slider): RedirectResponse
    {
        $data = $request->validated();

        foreach ($data['slides'] as $slideData) {
            Slide::where('slider_id', $slider->id)
                ->where('id', $slideData['id'])
                ->update(['ordre' => $slideData['ordre']]);
        }

        return redirect()->route('admin.slides.index', $slider)
            ->with('success', 'Ordre mis a jour.');
    }

    private function imageMedias(): array
    {
        return Media::where('type', 'image')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Media $media) => [
                'id' => $media->id,
                'nom' => $media->display_name,
                'url' => $media->url,
            ])
            ->toArray();
    }

    private function actualiteResults(string $search): array
    {
        $term = trim($search);

        if ($term === '' || mb_strlen($term) < 2) {
            return [];
        }

        return Actualite::visible()
            ->where('titre', 'like', '%'.$term.'%')
            ->orderByDesc('date_publication')
            ->limit(10)
            ->get()
            ->map(fn (Actualite $actualite) => [
                'id' => $actualite->id,
                'titre' => $actualite->titre,
                'slug' => $actualite->slug,
                'date_publication' => $actualite->date_publication?->format('Y-m-d'),
            ])
            ->toArray();
    }

    private function ensureSlideBelongsToSlider(Slider $slider, Slide $slide): void
    {
        if ($slide->slider_id !== $slider->id) {
            abort(404);
        }
    }
}
