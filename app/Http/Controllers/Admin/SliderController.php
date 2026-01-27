<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSliderRequest;
use App\Http\Requests\UpdateSliderRequest;
use App\Models\Slider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SliderController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());

        $sliders = Slider::withCount('slides')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('nom', 'like', '%'.$search.'%')
                    ->orWhere('position', 'like', '%'.$search.'%');
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString()
            ->through(fn (Slider $slider) => [
                'id' => $slider->id,
                'nom' => $slider->nom,
                'position' => $slider->position,
                'visible' => $slider->visible,
                'slides_count' => $slider->slides_count,
            ]);

        return Inertia::render('Admin/Sliders/Index', [
            'filters' => [
                'search' => $search,
            ],
            'sliders' => $sliders,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Sliders/Create');
    }

    public function store(StoreSliderRequest $request): RedirectResponse
    {
        Slider::create($request->validated());

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider cree avec succes.');
    }

    public function edit(Slider $slider): Response
    {
        return Inertia::render('Admin/Sliders/Edit', [
            'slider' => [
                'id' => $slider->id,
                'nom' => $slider->nom,
                'position' => $slider->position,
                'visible' => $slider->visible,
            ],
        ]);
    }

    public function update(UpdateSliderRequest $request, Slider $slider): RedirectResponse
    {
        $slider->update($request->validated());

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider mis a jour avec succes.');
    }

    public function destroy(Slider $slider): RedirectResponse
    {
        $slider->delete();

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider supprime avec succes.');
    }

    public function toggleVisibility(Slider $slider): RedirectResponse
    {
        $slider->update(['visible' => ! $slider->visible]);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Visibilite mise a jour.');
    }
}
