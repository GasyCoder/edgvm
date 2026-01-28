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

    public function create(Slider $slider): Response
    {
        $actualites = Actualite::query()
            ->published()
            ->orderBy('date_publication', 'desc')
            ->get(['id', 'titre', 'date_publication'])
            ->map(fn ($a) => [
                'id' => $a->id,
                'titre' => $a->titre,
                'date' => $a->date_publication?->format('d/m/Y'),
            ]);

        return Inertia::render('Admin/Slides/Create', [
            'slider' => [
                'id' => $slider->id,
                'nom' => $slider->nom,
            ],
            'actualites' => $actualites,
            'defaults' => [
                'ordre' => ($slider->slides()->max('ordre') ?? 0) + 1,
                'visible' => true,
            ],
        ]);
    }

    public function store(StoreSlideRequest $request, Slider $slider): RedirectResponse
    {
        $data = $request->validated();
        $imageId = null;

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

        Slide::create([
            'slider_id' => $slider->id,
            'titre_highlight' => $data['titre'],
            'description' => $data['description'] ?? null,
            'actualite_id' => $data['actualite_id'] ?? null,
            'texte_cta' => $data['texte_cta'] ?? null,
            'ordre' => $data['ordre'] ?? 1,
            'visible' => $data['visible'] ?? true,
            'image_id' => $imageId,
            'couleur_texte_titre' => $data['couleur_texte_titre'] ?? '#FFFFFF',
            'couleur_cta' => $data['couleur_cta'] ?? '#FFFFFF',
        ]);

        return redirect()->route('admin.slides.index', $slider)
            ->with('success', 'Slide cree avec succes.');
    }

    public function edit(Slider $slider, Slide $slide): Response
    {
        $this->ensureSlideBelongsToSlider($slider, $slide);

        $actualites = Actualite::query()
            ->published()
            ->orderBy('date_publication', 'desc')
            ->get(['id', 'titre', 'date_publication'])
            ->map(fn ($a) => [
                'id' => $a->id,
                'titre' => $a->titre,
                'date' => $a->date_publication?->format('d/m/Y'),
            ]);

        return Inertia::render('Admin/Slides/Edit', [
            'slider' => [
                'id' => $slider->id,
                'nom' => $slider->nom,
            ],
            'actualites' => $actualites,
            'slide' => [
                'id' => $slide->id,
                'titre' => $slide->titre_highlight,
                'description' => $slide->description,
                'image_url' => $slide->image?->url,
                'actualite_id' => $slide->actualite_id,
                'texte_cta' => $slide->texte_cta,
                'ordre' => $slide->ordre,
                'visible' => $slide->visible,
                'couleur_texte_titre' => $slide->couleur_texte_titre ?? '#FFFFFF',
                'couleur_cta' => $slide->couleur_cta ?? '#FFFFFF',
            ],
        ]);
    }

    public function update(UpdateSlideRequest $request, Slider $slider, Slide $slide): RedirectResponse
    {
        $this->ensureSlideBelongsToSlider($slider, $slide);
        $data = $request->validated();
        $imageId = $slide->image_id;

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

        $slide->update([
            'titre_highlight' => $data['titre'],
            'description' => $data['description'] ?? null,
            'actualite_id' => $data['actualite_id'] ?? null,
            'texte_cta' => $data['texte_cta'] ?? null,
            'ordre' => $data['ordre'] ?? $slide->ordre,
            'visible' => $data['visible'] ?? $slide->visible,
            'image_id' => $imageId,
            'couleur_texte_titre' => $data['couleur_texte_titre'] ?? $slide->couleur_texte_titre,
            'couleur_cta' => $data['couleur_cta'] ?? $slide->couleur_cta,
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

    private function ensureSlideBelongsToSlider(Slider $slider, Slide $slide): void
    {
        if ($slide->slider_id !== $slider->id) {
            abort(404);
        }
    }
}
